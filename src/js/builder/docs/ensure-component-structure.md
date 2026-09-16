# ensureComponentStructure() — Documentación

## Índice
- [Descripción general](#descripción-general)
- [Firma](#firma)
- [El problema que resuelve](#el-problema-que-resuelve)
- [Cómo funciona](#cómo-funciona)
- [Matching de componentes](#matching-de-componentes)
- [Flags especiales en componentDef](#flags-especiales-en-componentdef)
- [Componentes de contenido y `__needsSetup`](#componentes-de-contenido-y-__needssetup)
- [unwantedProps y builder_component_cleanup](#unwantedprops-y-builder_component_cleanup)
- [Ejemplo completo: componente compuesto](#ejemplo-completo-componente-compuesto)
- [Referencia rápida](#referencia-rápida)

---

## Descripción general

`editor.ensureComponentStructure()` es una utilidad definida en `gjs-extend-editor.js` que garantiza que un componente compuesto mantenga su estructura interna correcta. Recorre recursivamente un array de definiciones (`structureArray`) y para cada una:

1. **Busca** si el componente hijo ya existe.
2. **Crea** el componente si no existe (a menos que sea `__movable`).
3. **Aplica** las propiedades comportamentales definidas en el `structureArray`.
4. **Resetea** propiedades obsoletas que persisten en JSON antiguo (si se proporciona `unwantedProps`).

---

## Firma

```javascript
editor.ensureComponentStructure(parent, structureArray, unwantedProps?, _root?)
```

| Parámetro | Tipo | Requerido | Descripción |
|---|---|---|---|
| `parent` | `Component` | Sí | El componente padre (raíz del componente compuesto). |
| `structureArray` | `Array<ComponentDef>` | Sí | Array de definiciones esperadas para los hijos. Normalmente es el mismo `defaultComponents` usado en `model.defaults.components`. |
| `unwantedProps` | `string[]` | No | Lista de propiedades comportamentales que no deben persistir en JSON. Si una prop está en esta lista y NO está definida explícitamente en el `componentDef`, se resetea al default de GrapeJS. |
| `_root` | `Component` | No | Uso interno. Referencia al componente compuesto raíz, propagado en llamadas recursivas para búsquedas `__movable`. No pasar manualmente. |

---

## El problema que resuelve

En GrapeJS, los componentes registrados con `domc.addType()` manejan sus propiedades desde código (en `model.defaults`). Sin embargo, los **componentes hijos que no tienen `addType()` propio** (como `components-wrapper`, `figure`, etc. en un componente `testimonial`) serializan TODAS sus propiedades al JSON.

Esto genera un problema de retro-compatibilidad:

```
┌─────────────────────────────────────────────────────────┐
│  1. Defines defaultComponents con selectable: false     │
│  2. El usuario crea un testimonial → se guarda en JSON  │
│  3. Decides que selectable debería ser true (default)   │
│  4. Borras selectable: false del código                 │
│  5. ❌ Los componentes viejos siguen con selectable:    │
│     false porque está en el JSON guardado               │
└─────────────────────────────────────────────────────────┘
```

`ensureComponentStructure` con `unwantedProps` resuelve esto:
- **En runtime (al cargar):** Aplica las props del `structureArray` y resetea las obsoletas.
- **Al guardar:** Usado junto con `builder_component_cleanup`, las props se eliminan del JSON.

---

## Cómo funciona

```
ensureComponentStructure(testimonial, defaultComponents, unwantedProps)
│
├── Para cada componentDef en structureArray:
│   │
│   ├── 1. BUSCAR: ¿Existe el componente entre los hijos?
│   │   ├── Si tiene `classes` → match por type + TODAS las clases
│   │   ├── Si no tiene `classes` → match solo por type
│   │   └── Si tiene `__movable` → buscar en TODO el árbol desde root
│   │
│   ├── 2. CREAR: Si no existe y NO es __movable → crear el componente
│   │
│   ├── 3. APLICAR: Si existe, aplicar props del componentDef:
│   │   └── removable, copyable, draggable, selectable, badgable,
│   │       propagate, resizable, name, classes, tagName, droppable, delegate
│   │
│   ├── 4. RESETEAR: Si unwantedProps está definido, para cada prop:
│   │   └── Si NO está en componentDef → resetear al default de GrapeJS
│   │
│   └── 5. RECURSIÓN: Si componentDef.components existe → repetir
│
└── Fin
```

---

## Matching de componentes

### Match por type (default)

Cuando el `componentDef` no tiene `classes`, busca el primer hijo directo cuyo `type` coincida:

```javascript
{
    type: 'icon-box',
    name: 'Quote Icon',
    removable: false,
}
// Busca: primer hijo con type === 'icon-box'
```

### Match por type + clases

Cuando el `componentDef` tiene `classes`, busca un hijo que coincida en `type` Y tenga **todas** las clases definidas. Esto es esencial cuando el mismo `type` se usa en múltiples hijos:

```javascript
// Hijo 1: type 'components-wrapper' con clase 'testimonial__info'
{
    type: 'components-wrapper',
    classes: ['components-wrapper', 'testimonial__info'],
    removable: false,
}

// Hijo 2: type 'components-wrapper' con clase 'testimonial__body'  
{
    type: 'components-wrapper',
    classes: ['components-wrapper', 'testimonial__body'],
    draggable: false,
}
// Cada uno se identifica correctamente por sus clases
```

> **Importante:** Si tu componente compuesto tiene varios hijos del mismo `type`, define `classes` con al menos una clase única para distinguirlos.

> **⚠️ Cuidado con clases que no existen en JSON antiguo:** El matching por clases requiere que el componente guardado **ya tenga** esas clases. Si defines `classes: ['components-wrapper', 'content-wrapper']` en el `structureArray` pero los componentes viejos solo tienen `components-wrapper` en su JSON (porque `content-wrapper` se agregaba vía DOM), el matching fallará y se creará un duplicado. En ese caso, omite `classes` y usa matching solo por `type` (válido cuando no hay siblings del mismo tipo).

---

## Flags especiales en componentDef

### `__movable: true`

Indica que el componente puede ser arrastrado a diferentes padres dentro del componente compuesto. Cambia el comportamiento de `ensureComponentStructure`:

| Comportamiento | Sin `__movable` | Con `__movable: true` |
|---|---|---|
| Dónde busca | Hijos directos del padre | Todo el árbol desde la raíz |
| Si no lo encuentra | Crea el componente | No hace nada (no crea duplicados) |
| Aplica props | ✅ | ✅ (si lo encuentra) |
| Resetea stale props | ✅ | ✅ (si lo encuentra) |
| Recursión en hijos | ✅ | ✅ (si lo encuentra) |

**Caso de uso:** Un sub-componente que el usuario puede mover entre diferentes zonas del componente compuesto.

```javascript
{
    type: 'components-wrapper',
    name: 'Author Info',
    removable: false,
    copyable: false,
    classes: ['components-wrapper', 'testimonial__info'],
    draggable: '.testimonial__header, .testimonial',
    __movable: true,
}
// El usuario puede arrastrar "Author Info" del header al root del testimonial
// ensureComponentStructure lo encuentra en cualquier nivel y le aplica sus props
```

> **Cuándo usarlo:** Cuando `draggable` permite al usuario mover el componente a un padre diferente al original definido en `structureArray`. Sin `__movable`, se crearía un duplicado al no encontrarlo entre los hijos directos del padre esperado.

---

## Componentes de contenido y `__needsSetup`

### El problema con hijos de contenido en `defaultComponents`

`ensureComponentStructure` recursa en `componentDef.components`. Si defines hijos de contenido (como `text-editor`, `icon-box`) dentro del `structureArray`, la función los buscará entre los hijos del componente existente. Si el matching falla (por diferencias en clases, type, etc.), **creará duplicados**.

```
┌──────────────────────────────────────────────────────────────┐
│  defaultComponents define:                                   │
│    components-wrapper                                        │
│      └── text-editor    ← ensureComponentStructure recursa   │
│                                                              │
│  JSON guardado ya tiene:                                     │
│    components-wrapper                                        │
│      └── text-editor    ← ya existe                          │
│                                                              │
│  Si el matching falla → se crea OTRO text-editor             │
│  ❌ Resultado: dos text-editors                              │
└──────────────────────────────────────────────────────────────┘
```

### La solución: `__needsSetup`

Los componentes de contenido (text-editors, icon-boxes editables, etc.) **no deben estar** en el `defaultComponents` que se pasa a `ensureComponentStructure`. En su lugar, se crean solo una vez usando el flag `__needsSetup`:

```javascript
const defaultComponents = [
    {
        type: 'components-wrapper',
        classes: ['components-wrapper', 'mi-componente__body'],
        removable: false,
        // ❌ NO incluir components: [{ type: 'text-editor' }] aquí
    }
];

domc.addType(compClass, {
    model: {
        defaults: {
            name: 'Mi Componente',
            classes: [compClass, 'component'],
            components: defaultComponents,
            __needsSetup: true, // Flag para scaffolding inicial
        }
    },
    view: {
        onRender({el, model}) {
            editor.ensureComponentStructure(model, defaultComponents, unwantedProps);

            // Solo en la primera creación del componente
            if (model.get('__needsSetup')) {
                this.initialSetup({ model });
            }
        },
        initialSetup({ model }) {
            // Agregar hijos de contenido que no van en defaultComponents
            const body = model.find('.mi-componente__body')[0];
            if (body && !body.findType('text-editor').length) {
                body.append({ type: 'text-editor' });
            }

            model.set({ __needsSetup: false });
        }
    }
});
```

### ¿Cuándo necesitas `__needsSetup`?

| Situación | ¿Necesita `__needsSetup`? |
|---|---|
| El componente compuesto tiene hijos de contenido (text-editor, etc.) | **Sí** |
| Solo tiene wrappers estructurales sin hijos de contenido | No |
| Los hijos tienen su propio `addType()` que gestiona sus sub-componentes | No (su `addType` maneja su propia estructura) |

### ¿Cómo funciona con componentes cargados desde JSON?

Los componentes antiguos (cargados desde JSON) **no tienen `__needsSetup`** en sus datos guardados, por lo que `model.get('__needsSetup')` devuelve `undefined` (falsy). El `initialSetup` no se ejecuta, y los hijos de contenido que ya existen en el JSON se mantienen intactos.

---

## unwantedProps y builder_component_cleanup

Para que el sistema sea completo, se necesitan **dos mecanismos complementarios**:

### 1. Runtime: `unwantedProps` en `ensureComponentStructure`

Resetea props obsoletas **al cargar** el componente en el editor (en `onRender`):

```javascript
// Props que deben ser controladas por código, no por JSON guardado
const unwantedProps = [
    'removable', 'copyable', 'draggable', 'selectable', 
    'badgable', 'propagate', 'resizable', 'droppable', 'delegate'
];

// En el onRender del componente compuesto:
editor.ensureComponentStructure(model, defaultComponents, unwantedProps);
```

**Lógica por cada prop en `unwantedProps`:**
- Si la prop **está definida** en `componentDef` → se aplica el valor del `componentDef` (paso 3, aplica).
- Si la prop **NO está definida** en `componentDef` → se resetea al default de GrapeJS (paso 4, resetea).

Esto significa que si **borras** una prop de `defaultComponents`, los componentes antiguos que la tenían guardada en JSON la perderán al cargar y volverán al comportamiento por defecto de GrapeJS.

### 2. Save-time: `builder_component_cleanup`

Elimina las props del JSON **antes de guardar**, para que futuras cargas no tengan datos obsoletos:

```javascript
UltimateFields.addFilter('builder_component_cleanup', function(data) {
    if (data.component.type === 'mi-componente') {
        const cleanupNestedComponents = (obj) => {
            if (!obj || !Array.isArray(obj.components)) return;
            obj.components.forEach(child => {
                unwantedProps.forEach(prop => delete child[prop]);
                cleanupNestedComponents(child);
            });
        };
        cleanupNestedComponents(data.builderComponent);
    }
});
```

> **¿Por qué ambos?** El cleanup al guardar limpia el JSON para futuras cargas. El `unwantedProps` en runtime arregla el comportamiento **inmediatamente** en la sesión actual, sin esperar a que el usuario guarde.

### Flujo completo

```
┌──────────────────────────────────────────────────────────────┐
│ JSON guardado (viejo): { selectable: false, removable: false }│
│                                                              │
│ defaultComponents (actual): { removable: false }             │
│ (selectable fue eliminado → debería volver al default: true) │
└──────────────────────┬───────────────────────────────────────┘
                       │
            ┌──────────▼──────────┐
            │ Al cargar (onRender) │
            └──────────┬──────────┘
                       │
    ensureComponentStructure(model, defaultComponents, unwantedProps)
                       │
      ┌────────────────┼────────────────┐
      │                │                │
      ▼                ▼                ▼
  removable:       selectable:      Otras props
  false            (no está en      no definidas:
  (aplica del      componentDef     → resetear al
  componentDef)    → resetear)      default GrapeJS
      │                │
      ▼                ▼
  removable=false  selectable=true (default GrapeJS)
      │                │
      └────────┬───────┘
               │
    ┌──────────▼──────────┐
    │  Al guardar (save)  │
    └──────────┬──────────┘
               │
    builder_component_cleanup
               │
      Elimina removable, selectable, etc. del JSON
               │
               ▼
    JSON limpio: {} (sin props comportamentales)
```

---

## Ejemplo completo: componente compuesto

```javascript
window.gjsMiComponente = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'mi-componente';

    // 1. Definir las props que no deben persistir en JSON
    const unwantedProps = [
        'removable', 'copyable', 'draggable', 'selectable', 
        'badgable', 'propagate', 'resizable', 'droppable', 'delegate'
    ];

    // 2. Definir la estructura del componente compuesto
    // Solo incluir wrappers estructurales. Los hijos de contenido (text-editor, etc.)
    // se crean en initialSetup con __needsSetup para evitar duplicados.
    const defaultComponents = [
        {
            type: 'components-wrapper',
            classes: ['components-wrapper', 'mi-componente__header'],
            removable: false,
            copyable: false,
            draggable: false,
        },
        {
            type: 'components-wrapper',
            classes: ['components-wrapper', 'mi-componente__sidebar'],
            removable: false,
            copyable: false,
            // El sidebar puede moverse entre el header y el root
            draggable: '.mi-componente__header, .mi-componente',
            __movable: true,
        },
        {
            type: 'components-wrapper',
            classes: ['components-wrapper', 'mi-componente__body'],
            draggable: false,
            removable: false,
            copyable: false,
            // ❌ NO poner components: [{ type: 'text-editor' }] aquí
        }
    ];

    // 3. Registrar el componente compuesto
    domc.addType(compClass, {
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: 'Mi Componente',
                tagName: 'div',
                classes: [compClass, 'component'],
                components: defaultComponents,
                __needsSetup: true, // Flag para scaffolding inicial
            }
        },
        view: {
            onRender({el, model}) {
                // 4. Asegurar estructura + resetear props obsoletas al cargar
                editor.ensureComponentStructure(model, defaultComponents, unwantedProps);

                // 5. Solo en la primera creación: agregar hijos de contenido
                if (model.get('__needsSetup')) {
                    this.initialSetup({ model });
                }
            },
            initialSetup({ model }) {
                const header = model.find('.mi-componente__header')[0];
                if (header && !header.findType('image-component').length) {
                    header.append({ type: 'image-component', classes: ['mi-componente__image'] });
                }

                const body = model.find('.mi-componente__body')[0];
                if (body && !body.findType('text-editor').length) {
                    body.append({ type: 'text-editor' });
                }

                model.set({ __needsSetup: false });
            }
        }
    });

    // 5. Limpiar props al guardar
    UltimateFields.addFilter('builder_component_cleanup', function(data) {
        if (data.component.type === compClass) {
            const cleanupNestedComponents = (obj) => {
                if (!obj || !Array.isArray(obj.components)) return;
                obj.components.forEach(child => {
                    unwantedProps.forEach(prop => delete child[prop]);
                    cleanupNestedComponents(child);
                });
            };
            cleanupNestedComponents(data.builderComponent);
        }
    });
}
```

---

## Referencia rápida

### Propiedades aplicadas automáticamente

Estas propiedades se aplican desde `componentDef` al componente existente si están definidas:

| Propiedad | Tipo | Descripción |
|---|---|---|
| `removable` | `boolean` | Si el componente puede ser eliminado |
| `copyable` | `boolean` | Si el componente puede ser copiado |
| `draggable` | `boolean\|string` | Si/dónde puede ser arrastrado |
| `selectable` | `boolean` | Si puede ser seleccionado en el canvas |
| `badgable` | `boolean` | Si muestra badge al hacer hover |
| `propagate` | `string[]` | Propiedades que se propagan a los hijos |
| `resizable` | `boolean` | Si puede ser redimensionado |
| `droppable` | `boolean\|string` | Si/qué se puede soltar dentro |
| `delegate` | `object` | Delega acciones (select, move) a otro componente |
| `name` | `string` | Nombre mostrado en el panel de capas |
| `classes` | `string[]` | Clases CSS del componente |
| `tagName` | `string` | Tag HTML del componente |

### Checklist para nuevos componentes compuestos

- [ ] Definir `unwantedProps` con las propiedades comportamentales
- [ ] Definir `defaultComponents` con la estructura esperada (solo wrappers estructurales)
- [ ] **No incluir** hijos de contenido (`text-editor`, etc.) en `defaultComponents` — usar `__needsSetup` + `initialSetup()` para crearlos solo en la primera instanciación
- [ ] Usar `classes` con al menos una clase única por cada hijo que comparta `type` con otro — asegurarse de que las clases ya existan en el JSON guardado de componentes antiguos
- [ ] Usar `__movable: true` en hijos que puedan cambiar de padre
- [ ] Llamar `ensureComponentStructure(model, defaultComponents, unwantedProps)` en `onRender`
- [ ] Registrar `builder_component_cleanup` para limpiar props al guardar
- [ ] Las propiedades del componente raíz se manejan en `addType() > model.defaults`, no en `structureArray`
