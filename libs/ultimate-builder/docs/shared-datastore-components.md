# Componentes con Datastore Compartido (`share_datastore_with`) — Documentación

## Índice
- [Descripción general](#descripción-general)
- [El problema que resuelve](#el-problema-que-resuelve)
- [Cómo funciona](#cómo-funciona)
  - [1. Declaración en PHP](#1-declaración-en-php)
  - [2. `component:create` — no crear datastore propio](#2-componentcreate--no-crear-datastore-propio)
  - [3. `resolveDatastoreOwner()` — resolver el dueño real](#3-resolvedatastoreowner--resolver-el-dueño-real)
  - [4. Efecto en `component:selected` / `component:deselected`](#4-efecto-en-componentselected--componentdeselected)
  - [5. Efecto al guardar](#5-efecto-al-guardar)
- [Ejemplo completo: Flip Box](#ejemplo-completo-flip-box)
- [Cuándo usar este patrón](#cuándo-usar-este-patrón)
- [Limitaciones y consideraciones](#limitaciones-y-consideraciones)
- [Diferencia con `custom_datastore_change_callback`](#diferencia-con-custom_datastore_change_callback)
- [Checklist de testing](#checklist-de-testing)

---

## Descripción general

Algunos componentes GrapesJS compuestos tienen "sub-componentes" internos (registrados con su propio `Repeater_Group` en PHP para poder tener bloque/tipo propio, permisos de selección, etc.) que en realidad **no necesitan sus propios datos**: sus campos son, conceptualmente, los mismos que los del componente padre.

El caso de referencia es **Flip Box** (`flipbox` → `flipbox-front` / `flipbox-back`): las caras front/back comparten aspect ratio y efecto de flip con la caja completa, no tienen configuración propia.

Para estos casos existe el flag de `builder_data`:

```php
'share_datastore_with' => '<tipo_gjs_ancestro>'
```

Cuando un tipo de componente declara este flag, **no se crea un datastore/modelo de Ultimate Fields propio** para él. En su lugar, al seleccionarlo se resuelve y muestra el datastore del ancestro declarado, y al guardar **no se persiste ninguna entrada en `components_data`** para ese componente — solo existe una entrada para el ancestro.

---

## El problema que resuelve

Antes de este patrón, componentes como `flipbox-front`/`flipbox-back` tenían su **propio** `Repeater_Group` con los mismos campos que `flipbox` (`flip_effect`, `aspect_ratio`, `custom_aspect_ratio`), y sincronizaban manualmente los cambios hacia el padre con `custom_datastore_change_callback`:

```
┌───────────────────────────────────────────────────────────┐
│  flipbox        → datastore propio (__type: flipbox)      │
│  flipbox-front  → datastore propio (__type: flipbox-front)│ ❌ duplicado
│  flipbox-back   → datastore propio (__type: flipbox-back) │ ❌ duplicado
└───────────────────────────────────────────────────────────┘
```

Esto generaba **3 datastores guardados por cada flip box** en vez de 1, y requería lógica extra para mantenerlos sincronizados (el cambio se editaba en el datastore de front/back y había que empujarlo manualmente al datastore de flipbox).

Con `share_datastore_with`, front/back dejan de tener datastore propio: solo existe **un** datastore (el de `flipbox`), y editarlo desde cualquier cara actualiza directamente ese único datastore — sin necesidad de sincronización manual.

---

## Cómo funciona

Toda la lógica vive en `libs/ultimate-builder/assets/js/plugins/gjs-extend-components.js`.

### 1. Declaración en PHP

En la clase del componente hijo (extiende `Core\Builder\Component`), en `get_builder_data()`:

```php
public static function get_builder_data() {
    return array(
        'display_gjs_block'    => false,       // opcional: oculta el bloque en el panel de bloques
        'share_datastore_with' => 'flipbox',    // tipo gjs del ancestro que posee el datastore real
    );
}
```

`share_datastore_with` acepta el **tipo gjs** (el mismo string usado en `domc.addType(...)`) del componente ancestro cuyo datastore se debe reutilizar. Se resuelve buscando hacia arriba con `component.closestType(tipo)`.

> Nota: `get_fields()` puede seguir devolviendo los mismos campos que el ancestro (por compatibilidad con datos antiguos ya guardados en producción), pero en la práctica esos campos **ya no se usan** para nada nuevo: el componente no vuelve a generar su propio datastore.

### 2. `component:create` — no crear datastore propio

```javascript
editor.on('component:create', (gjs_component) => {
    ...
    const generatedId = builderInstance.generateId();
    gjs_component.attributes.__tempID = generatedId; // se asigna igual, se usa para remove/clone

    const shareDatastoreWith = groupData.builder_data && groupData.builder_data.share_datastore_with;
    if (shareDatastoreWith) {
        return; // no se crea group_model/datastore ni entrada en temporalCompStore
    }

    editorConfig.temporalCompStore[generatedId] = {};
    // ...resto de la lógica de creación del datastore (sin cambios)
});
```

El componente sigue recibiendo un `__tempID` (necesario para `component:remove`, `component:clone`, etc.), pero **no** se le crea ninguna entrada en `editorConfig.temporalCompStore`.

### 3. `resolveDatastoreOwner()` — resolver el dueño real

```javascript
function resolveDatastoreOwner(component, editorConfig) {
    const groups = editorConfig.groups || [];
    let current = component;

    while (current) {
        const groupData = groups.find(g => g.id === current.get('type'));
        const shareWith = groupData && groupData.builder_data && groupData.builder_data.share_datastore_with;
        if (!shareWith) break;

        const ancestor = current.closestType(shareWith);
        if (!ancestor) break;

        current = ancestor;
    }

    return current;
}
```

Sube por la cadena de `share_datastore_with` (soporta encadenamientos, ej. A comparte con B y B comparte con C) hasta encontrar el primer componente que **no** delega su datastore. Ese es el "dueño" real.

### 4. Efecto en `component:selected` / `component:deselected`

Ambos handlers resuelven el dueño **al principio**, y reasignan la variable local `component` — el resto del handler (cache de vistas `viewCache`, `activeDatastore`, eventos `beforeOpenDatastore`/`openDatastore`, re-render final) no necesita ningún cambio porque opera transparentemente sobre el dueño:

```javascript
editor.on('component:selected', (component) => {
    const editorConfig = editor.getConfig();
    component = resolveDatastoreOwner(component, editorConfig);
    const compId = component.attributes && component.attributes.__tempID;
    // ...usa el compId/datastore del dueño (ej. flipbox) aunque se haya
    // seleccionado un hijo (ej. flipbox-front)
});
```

Como resultado:
- Seleccionar `flipbox-front` o `flipbox-back` muestra el **mismo panel** (misma `GroupView` cacheada) que mostraría seleccionar `flipbox` directamente.
- Cambiar de front a back (o viceversa) reutiliza la vista cacheada bajo la clave del dueño (`flipbox`'s `__tempID`), en vez de crear una vista nueva por cada cara.
- El re-render final del `changeHandler` (`component.view.render()`) repinta el componente dueño (`flipbox`), que es el que realmente necesita reflejar los cambios visuales (aspect ratio, efecto de flip).

### 5. Efecto al guardar

En `builder.js` → `prepare_project_data()`, solo se agrega una entrada a `components_data` si existe `temporalCompStore[__tempID]` para ese componente:

```javascript
if(componentDataStore.__type) components_data[compId] = componentDataStore;
```

Como los componentes con `share_datastore_with` nunca tienen entrada en `temporalCompStore`, **nunca generan una entrada propia** en `components_data` — solo se guarda la del ancestro dueño.

---

## Ejemplo completo: Flip Box

```
flipbox                  ← tiene datastore propio (flip_effect, aspect_ratio, ...)
└─ flipbox-inner
   ├─ flipbox-front       ← share_datastore_with: 'flipbox' (sin datastore propio)
   │  └─ text-editor      ← tiene su propio datastore (contenido de texto)
   └─ flipbox-back        ← share_datastore_with: 'flipbox' (sin datastore propio)
      └─ text-editor      ← tiene su propio datastore (contenido de texto)
```

Archivos involucrados:
- `Core/Builder/Component/Flip_Box.php` — define los campos reales (`flip_effect`, `aspect_ratio`, `custom_aspect_ratio`).
- `Core/Builder/Component/Flip_Box_Front.php` / `Flip_Box_Back.php` — declaran `'share_datastore_with' => 'flipbox'`.
- `libs/ultimate-builder/assets/js/components/gjs-flip-box.js` — define los tipos gjs (`flipbox`, `flipbox-inner`, `flipbox-front`, `flipbox-back`) y el delegado de selección (`delegate.select` en `flipbox` apunta a `flipbox-front`).

Nota: `text-editor` (el contenido de cada cara) **no** comparte datastore — solo los campos de `flip_effect`/`aspect_ratio` son compartidos a nivel de `flipbox-front`/`flipbox-back` mismos, no de sus hijos.

---

## Cuándo usar este patrón

Usa `share_datastore_with` cuando:
- Un sub-componente existe únicamente por razones estructurales de GrapesJS (delegar `select`/`remove`/`copy`, permitir estilos CSS específicos por cara, permitir "togglear" cuál se ve, etc.).
- Sus campos de Ultimate Fields son **exactamente los mismos** (o un subconjunto sin sentido propio) que los del ancestro.
- No necesitas que ese sub-componente tenga su propio registro independiente en `components_data`.

No lo uses si el sub-componente necesita datos propios e independientes del padre (en ese caso, cada uno debe mantener su propio datastore normalmente).

---

## Limitaciones y consideraciones

- **Compatibilidad con datos antiguos:** páginas guardadas *antes* de aplicar este patrón a un componente pueden tener entradas huérfanas en `page_content_datastore` con el `__type` del sub-componente (ej. `flipbox-front`). No se eliminan automáticamente; simplemente quedan sin usarse (`merge_component_datastore` en `Core/Frontend/Page.php` las ignora porque no hay `__id` que las referencie desde el árbol nuevo). No es necesario migrarlas para que todo siga funcionando, pero se pueden limpiar con un script de migración si se desea.
- **`closestType` busca solo ancestros**, no incluye al propio componente. Si el tipo declarado en `share_datastore_with` no existe como ancestro real en el árbol, `resolveDatastoreOwner` simplemente deja de subir y usa el último componente válido encontrado (puede terminar sin datastore — revisar en consola si el panel no muestra nada).
- El componente delegado **sigue necesitando `__tempID`** (se sigue asignando en `component:create`) porque otras partes del código (`component:remove`, `component:clone`) lo usan para limpieza/clonado, aunque no tenga datastore propio.
- Si se aplica a un componente cuyo `display()` en PHP sí depende de sus propios campos (a diferencia de flipbox-front/back que solo hacen wrap de sus hijos), hay que revisar que el front-end siga leyendo esos valores desde el ancestro correcto.

---

## Diferencia con `custom_datastore_change_callback`

No confundir ambos mecanismos, son independientes y sirven para cosas distintas:

| | `share_datastore_with` | `custom_datastore_change_callback` |
|---|---|---|
| Propósito | Evitar tener datastore propio; reutilizar el de un ancestro | Interceptar el evento de cambio de un datastore **propio** para hacer algo custom (o evitar el re-render por default) |
| ¿Tiene datastore propio? | No | Sí |
| ¿Se guarda entrada en `components_data`? | No | Sí |
| Dónde se usa | Solo `flipbox-front`/`flipbox-back` (por ahora) | Varios: `Accordion`, `Carousel`, `Column`, `Gallery`, `Listing`, etc. |

---

## Checklist de testing

Al aplicar este patrón a un nuevo componente, verificar:

- [ ] Seleccionar el sub-componente muestra los campos del ancestro (mismos valores, mismo panel).
- [ ] Editar desde el sub-componente actualiza visualmente el componente ancestro en el canvas.
- [ ] Alternar entre sub-componentes hermanos (ej. front/back) muestra siempre el mismo estado (no hay desincronización).
- [ ] Al guardar, `page_content_datastore` solo tiene **una** entrada (`__type` del ancestro) por instancia — ninguna con el `__type` del sub-componente.
- [ ] El front-end renderiza correctamente tras guardar y recargar.
- [ ] Clonar el componente ancestro clona correctamente también el contenido propio de los sub-componentes (ej. texto de `text-editor` dentro de front/back).
- [ ] Eliminar el componente ancestro no deja entradas huérfanas en `viewCache`/`temporalCompStore`.
- [ ] Páginas guardadas con datos antiguos (antes de aplicar el patrón) siguen cargando y renderizando sin errores.
