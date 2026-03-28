# GrapesJS UndoManager - Guía Completa

## Índice
- [Arquitectura del UndoManager](#arquitectura-del-undomanager)
- [Ciclo de Eventos](#ciclo-de-eventos)
- [Por qué setTimeout(0) es necesario](#por-qué-settimeout0-es-necesario)
- [Eventos disponibles](#eventos-disponibles)
- [Patrón recomendado](#patrón-recomendado)
- [Problemas comunes y soluciones](#problemas-comunes-y-soluciones)
- [Opciones avanzadas](#opciones-avanzadas)
- [Mejores prácticas](#mejores-prácticas)

---

## Arquitectura del UndoManager

GrapesJS utiliza `backbone-undo` internamente para rastrear cambios en modelos y colecciones de Backbone.

```javascript
// Estructura interna
this.um = new UndoManager({ 
  track: true, 
  register: [], 
  maximumStackLength: 500 
});
```

### Tipos de cambios rastreados

| Tipo | Descripción | Cuándo se dispara |
|------|-------------|-------------------|
| `change` | Cambios en propiedades del modelo | `model.set('prop', value)` |
| `add` | Agregar elementos a colecciones | `collection.add(model)` |
| `remove` | Eliminar elementos de colecciones | `collection.remove(model)` |
| `reset` | Resetear colecciones completas | `collection.reset([...])` |

---

## Ciclo de Eventos

Cuando ejecutas una acción (como agregar componentes), el flujo es el siguiente:

```javascript
// 1. Tu código ejecuta
this.model.components([{ type: 'row-component', ... }]);

// 2. Internamente en GrapesJS:
Collection.add()                    // Se ejecuta el método
  ↓
UndoManager.detecta('add')          // Detecta el evento
  ↓
UndoManager.registra(cambio)        // Guarda en el stack ANTES de actualizar DOM
  ↓
Dispara evento 'add'                // Notifica a listeners
  ↓
handleComponentsChange()            // Tu listener se ejecuta
  ↓
DOM se actualiza                    // Renderiza cambios visuales
```

**⚠️ Importante:** El registro en el stack ocurre **ANTES** de la actualización del DOM.

---

## Por qué setTimeout(0) es necesario

### El Problema

```javascript
// ❌ SIN setTimeout - Se ejecuta ANTES del cambio
handleUndoRedo() {
  this.updateTemplateSelector();
  // En este punto, model.components() aún tiene los valores PRE-undo
}
```

### La Solución

```javascript
// ✅ CON setTimeout - Se ejecuta DESPUÉS del cambio
handleUndoRedo() {
  setTimeout(() => {
    this.updateTemplateSelector();
    // Ahora model.components() tiene los valores POST-undo
  }, 0);
}
```

### Explicación técnica: Event Loop de JavaScript

```javascript
// ORDEN DE EJECUCIÓN:

// 1. CALL STACK (actual) - Código síncrono
editor.UndoManager.undo();        // ← Inicia undo
em.trigger('undo');               // ← Dispara evento
handleUndoRedo();                 // ← Tu listener se ejecuta

// 2. MICROTASKS - Promesas, queueMicrotask
// (vacío en este caso)

// 3. MACROTASKS - setTimeout, setInterval
setTimeout(() => {
  // ← Este código se ejecuta DESPUÉS de que el stack actual termine
  this.updateTemplateSelector();
}, 0);

// 4. Continuación del CALL STACK - GrapesJS actualiza modelos
um.undo(all);                     // ← Restaura estado anterior
collection.reset(before);         // ← Actualiza colección
model.trigger('change');          // ← Dispara eventos de cambio
```

**Resultado:** `setTimeout(0)` mueve tu código al final del event loop, garantizando que `model.components()` refleja el estado POST-undo.

---

## Eventos disponibles

### Eventos del Editor

```javascript
// Definidos en /packages/core/src/editor/types.ts
editor.on('undo', () => {
  // Se dispara cuando se ejecuta undo
  console.log('Undo ejecutado');
});

editor.on('redo', () => {
  // Se dispara cuando se ejecuta redo
  console.log('Redo ejecutado');
});
```

### Timing de eventos

```javascript
// UndoManager.undo() internamente:
undo(all = true) {
  const { em, um } = this;
  um.undo(all);           // 1. Ejecuta undo
  em.trigger('undo');     // 2. Dispara evento (DURANTE la operación)
  return this;
}
```

⚠️ **El evento `'undo'` se dispara DURANTE la operación, NO después de que todos los cambios del DOM se completen.**

---

## Patrón recomendado

### Implementación completa

```javascript
domc.addType('my-component', {
  model: {
    defaults: {
      // ... configuración del modelo
    }
  },
  
  view: {
    init() {
      // 1. Listeners de componentes (sincrónicos)
      // Se ejecutan INMEDIATAMENTE cuando hay cambios directos
      this.listenTo(
        this.model.components(), 
        'add remove reset', 
        this.handleComponentsChange
      );
      
      // 2. Listeners de undo/redo (asíncronos con setTimeout)
      // Necesitan delay para esperar a que GrapesJS termine
      this.listenTo(
        this.em, 
        'undo redo', 
        this.handleUndoRedo
      );
    },
    
    onRender({ el, model }) {
      // Renderizado inicial
      this.updateUI();
    },
    
    handleComponentsChange() {
      // Se ejecuta cuando hay cambios DIRECTOS (drag & drop, add, remove)
      this.updateUI();
    },
    
    handleUndoRedo() {
      // Se ejecuta con delay para esperar cambios de undo/redo
      setTimeout(() => {
        this.updateUI();
      }, 0);
    },
    
    updateUI() {
      const components = this.model.components();
      
      // Lógica de actualización de UI
      if (components.length === 0) {
        this.showTemporaryUI();
      } else {
        this.hideTemporaryUI();
      }
    },
    
    showTemporaryUI() {
      // Mostrar elementos temporales (botones, selectores, etc.)
      const selector = document.createElement('div');
      selector.classList.add('temp-selector');
      this.el.appendChild(selector);
    },
    
    hideTemporaryUI() {
      // Ocultar/eliminar elementos temporales
      const selector = this.el.querySelector('.temp-selector');
      selector?.remove();
    }
  }
});
```

---

## Problemas comunes y soluciones

### Problema 1: UI no se actualiza después de undo/redo

**❌ Incorrecto:**
```javascript
// El listener se ejecuta antes del cambio
this.listenTo(this.em, 'undo redo', this.updateUI);
```

**✅ Correcto:**
```javascript
// Usa setTimeout para esperar
this.listenTo(this.em, 'undo redo', () => {
  setTimeout(() => this.updateUI(), 0);
});
```

---

### Problema 2: Elementos del DOM persisten después de undo

**❌ Incorrecto:**
```javascript
// Solo escucha eventos de componentes
this.listenTo(this.model.components(), 'add remove', this.update);
```

**✅ Correcto:**
```javascript
// También escucha undo/redo y el evento 'reset'
this.listenTo(
  this.model.components(), 
  'add remove reset',  // ← 'reset' es crucial para undo/redo
  this.update
);

this.listenTo(this.em, 'undo redo', () => {
  setTimeout(() => this.update(), 0);
});
```

---

### Problema 3: Cambios no registrados en undo stack

**❌ Incorrecto:**
```javascript
// Usa opciones que evitan tracking
this.model.set('prop', value, { noUndo: true });
```

**✅ Correcto:**
```javascript
// Sin opciones que bloqueen tracking
this.model.set('prop', value);
```

**Para operaciones temporales que NO quieres en el stack:**
```javascript
editor.UndoManager.skip(() => {
  this.model.set('prop', value);
  // Múltiples cambios aquí no se registran
});
```

---

## Opciones avanzadas

### Evitar que un cambio entre al stack

```javascript
// Opción 1: noUndo en la operación individual
model.set('content', 'texto', { noUndo: true });
component.remove({ noUndo: true });

// Opción 2: avoidStore (también evita storage)
model.set('prop', value, { avoidStore: true });
```

### Ejecutar múltiples cambios sin tracking

```javascript
editor.UndoManager.skip(() => {
  component1.set('content', 'A');
  component2.set('content', 'B');
  component3.remove();
  // Nada de esto se registra en el undo stack
});
```

### Pausar/reanudar tracking globalmente

```javascript
// Pausar tracking
editor.UndoManager.stop();

// Hacer cambios que no quieres rastrear
wrapper.append('<div>Componente temporal</div>');

// Reanudar tracking
editor.UndoManager.start();
```

### Verificar estado del stack

```javascript
// Verificar si hay undo disponible
if (editor.UndoManager.hasUndo()) {
  console.log('Hay cambios para deshacer');
}

// Verificar si hay redo disponible
if (editor.UndoManager.hasRedo()) {
  console.log('Hay cambios para rehacer');
}

// Ver el stack completo
const stack = editor.UndoManager.getStack();
console.log('Stack:', stack);

// Ver el stack agrupado (operaciones múltiples agrupadas)
const groupedStack = editor.UndoManager.getStackGroup();
console.log('Grouped stack:', groupedStack);

// Limpiar el stack
editor.UndoManager.clear();
```

---

## Mejores prácticas

### 1. Componentes con UI temporal

```javascript
view: {
  init() {
    // Escuchar AMBOS: cambios directos Y undo/redo
    this.listenTo(
      this.model.components(), 
      'add remove reset', 
      this.syncUI
    );
    
    this.listenTo(
      this.em, 
      'undo redo', 
      this.syncUIDelayed
    );
  },
  
  syncUI() {
    // Cambios directos: inmediato
    this.updateTemporaryElements();
  },
  
  syncUIDelayed() {
    // Undo/redo: con delay
    setTimeout(() => {
      this.updateTemporaryElements();
    }, 0);
  },
  
  updateTemporaryElements() {
    const isEmpty = this.model.components().length === 0;
    
    if (isEmpty) {
      this.showPlaceholder();
    } else {
      this.hidePlaceholder();
    }
  }
}
```

### 2. Operaciones que modifican el DOM sin afectar el modelo

```javascript
// Botones temporales, overlays, tooltips, etc.
showTemporaryButton() {
  const btn = document.createElement('button');
  btn.classList.add('temp-ui');
  btn.textContent = 'Click me';
  this.el.appendChild(btn);
  // ✅ No afecta el undo stack porque no modifica el modelo
}

hideTemporaryButton() {
  const btn = this.el.querySelector('.temp-ui');
  btn?.remove();
  // ✅ No afecta el undo stack
}
```

### 3. Debugging del UndoManager

```javascript
// Helper para debugging
const debugUndo = (editor) => {
  const um = editor.UndoManager;
  
  console.group('UndoManager Debug');
  console.log('Has Undo:', um.hasUndo());
  console.log('Has Redo:', um.hasRedo());
  console.log('Stack Length:', um.getStack().length);
  console.log('Grouped Stack:', um.getStackGroup().length);
  console.table(
    um.getStack().map((item, i) => ({
      index: i,
      type: item.get('type'),
      object: item.get('object')?.cid || 'N/A'
    }))
  );
  console.groupEnd();
};

// Uso
debugUndo(editor);
```

### 4. Escuchar cambios en el stack

```javascript
// Monitorear cuando se agrega algo al stack
editor.on('change:canvasOffset run:undo run:redo', () => {
  console.log('Stack changed');
  console.log('Can undo:', editor.UndoManager.hasUndo());
  console.log('Can redo:', editor.UndoManager.hasRedo());
});
```

---

## Ejemplo práctico completo

**Caso de uso:** Componente Section con selector de templates que desaparece cuando se agregan componentes.

```javascript
window.gjsSection = function(editor) {
  const domc = editor.DomComponents;
  
  domc.addType('section', {
    model: {
      defaults: {
        tagName: 'div',
        classes: ['page-module']
      }
    },
    
    view: {
      init() {
        // ✅ Escuchar cambios directos en componentes
        this.listenTo(
          this.model.components(), 
          'add remove reset', 
          this.handleComponentsChange
        );
        
        // ✅ Escuchar undo/redo con delay
        this.listenTo(
          this.em, 
          'undo redo', 
          this.handleUndoRedo
        );
      },
      
      onRender({ el, model }) {
        // Renderizado inicial
        this.updateTemplateSelector();
      },
      
      handleComponentsChange() {
        // Cambios directos: actualizar inmediatamente
        this.updateTemplateSelector();
      },
      
      handleUndoRedo() {
        // ✅ Undo/redo: esperar a que GrapesJS complete la operación
        setTimeout(() => {
          this.updateTemplateSelector();
        }, 0);
      },
      
      updateTemplateSelector() {
        const components = this.model.components();
        const existingSelector = this.el.querySelector('.template-selector');
        
        // Remover selector si hay componentes
        if (components.length > 0 && existingSelector) {
          existingSelector.remove();
        }
        
        // Mostrar selector solo si está vacío
        if (components.length === 0 && !existingSelector) {
          this.showTemplateSelector();
        }
      },
      
      showTemplateSelector() {
        const container = document.createElement('div');
        container.classList.add('template-selector');
        // ... agregar botones de templates
        this.el.appendChild(container);
      },
      
      events: {
        'click .template-btn': 'insertTemplate'
      },
      
      insertTemplate(ev) {
        const templateKey = ev.target.closest('.template-btn')?.dataset.template;
        if (!templateKey) return;
        
        // Insertar componentes (esto SÍ se registra en el undo stack)
        this.model.components([
          { type: 'row-component', /* ... */ }
        ]);
        
        // No es necesario remover manualmente el selector aquí
        // handleComponentsChange() lo hará automáticamente
      }
    }
  });
};
```

---

## Referencias

- [Repositorio oficial de GrapesJS](https://github.com/GrapesJS/grapesjs)
- [UndoManager Source Code](https://github.com/GrapesJS/grapesjs/blob/dev/packages/core/src/undo_manager/index.ts)
- [Editor Events](https://github.com/GrapesJS/grapesjs/blob/dev/packages/core/src/editor/types.ts)
- [backbone-undo library](https://github.com/osartun/Backbone.Undo.js)

---

## Resumen

**Por qué `setTimeout(0)` es necesario:**

1. ✅ Los eventos `'undo'/'redo'` se disparan **durante** la operación
2. ✅ Los cambios del modelo se completan **después** en el event loop
3. ✅ `setTimeout(0)` mueve tu código al final del event loop
4. ✅ Garantiza que `model.components()` refleja el estado POST-undo

**Patrón a seguir:**

```javascript
view: {
  init() {
    // Cambios directos: inmediato
    this.listenTo(this.model.components(), 'add remove reset', this.update);
    
    // Undo/redo: con setTimeout
    this.listenTo(this.em, 'undo redo', () => {
      setTimeout(() => this.update(), 0);
    });
  }
}
```

Este es un patrón común en frameworks event-driven como Backbone/GrapesJS y es fundamental para garantizar consistencia entre el estado del modelo y la UI.
