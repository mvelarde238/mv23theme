// (function () {
//   tinymce.PluginManager.add('fontSizeCleaner', function (editor) {

//     // Quitar div existente con font-size: var(--text-*)
//     function removeExistingFontSizeWrapper() {
//       const node = editor.selection.getNode();
//       const wrapper = editor.dom.getParent(node, function (el) {
//         return (
//           el.nodeName === 'DIV' &&
//           el.style &&
//           el.style.fontSize &&
//           el.style.fontSize.startsWith('var(--text-')
//         );
//       });

//       if (wrapper) {
//         console.log('found', wrapper);
//         // const content = wrapper.innerHTML;
//         editor.dom.remove(wrapper, true);
//         // editor.selection.setContent(content);
//       } else {
//         console.log('no found', wrapper);
//       }
//     }

//     // Comando que aplica un font-size después de limpiar
//     editor.addCommand('applyFontSize', function (ui, value) {
//       removeExistingFontSizeWrapper();

//       editor.formatter.unregister('fontSizeDynamic');
//       editor.formatter.register('fontSizeDynamic', {
//         block: 'div',
//         styles: { 'font-size': value },
//         wrapper: true,
//         remove_similar: true
//       });

//       editor.formatter.apply('fontSizeDynamic');
//     });

//     // Botón con menú desplegable
//     editor.addButton('fontSizeSelector', {
//       type: 'menubutton',
//       text: 'Tamaño',
//       icon: false,
//       menu: [
//         {
//           text: 'Texto XXS',
//           onclick: function () {
//             editor.execCommand('applyFontSize', false, 'var(--text-xxs)');
//           }
//         },
//         {
//           text: 'Texto XS',
//           onclick: function () {
//             editor.execCommand('applyFontSize', false, 'var(--text-xs)');
//           }
//         },
//         {
//           text: 'Texto SM',
//           onclick: function () {
//             editor.execCommand('applyFontSize', false, 'var(--text-sm)');
//           }
//         },
//         {
//           text: 'Texto LG',
//           onclick: function () {
//             editor.execCommand('applyFontSize', false, 'var(--text-lg)');
//           }
//         },
//         {
//           text: 'Texto XL',
//           onclick: function () {
//             editor.execCommand('applyFontSize', false, 'var(--text-xl)');
//           }
//         }
//       ]
//     });
//   });
// })();




// (function () {
// tinymce.PluginManager.add('fontSizeCleaner', function(editor) {
  

//   editor.on('init', function() {

//     console.log(editor.ui);

// //       editor.ui.registry.addMenuButton('fontSizes', {
// //   text: 'Tamaño',
// //   fetch: function(callback) {
// //     callback([
// //       {
// //         type: 'menuitem',
// //         text: 'Texto XS',
// //         onAction: function() {
// //           editor.execCommand('applyFontSize', false, 'var(--text-xs)');
// //         }
// //       },
// //       {
// //         type: 'menuitem',
// //         text: 'Texto XL',
// //         onAction: function() {
// //           editor.execCommand('applyFontSize', false, 'var(--text-xl)');
// //         }
// //       }
// //     ]);
// //   }
// // });

//     editor.formatter.register('fontSizeDynamic', {
//     block: 'div',
//     styles: {},
//     wrapper: true,
//     remove_similar: true
//   });

//     // Opcional: remover divs previos con font-size al aplicar uno nuevo
//     editor.on('ExecCommand', function(e) {
//       if (e.command === 'mceToggleFormat') {
//         const node = editor.selection.getNode();
//         const wrapper = editor.dom.getParent(node, function(el) {
//           return (
//             el.nodeName === 'DIV' &&
//             el.hasAttribute('style') &&
//             /font-size\s*:\s*var\(--text-[^)]+\)/.test(el.getAttribute('style'))
//           );
//         });
//         if (wrapper) {
//           const content = wrapper.innerHTML;
//           editor.dom.remove(wrapper, true);
//           editor.insertContent(content);
//         }
//       }
//     });
//   });

//   // Expone la función global para aplicarlo manualmente
//   editor.addCommand('applyFontSize', function(sizeVar) {
//     editor.formatter.unregister('fontSizeDynamic');
//     editor.formatter.register('fontSizeDynamic', {
//       block: 'div',
//       styles: { 'font-size': sizeVar },
//       wrapper: true,
//       remove_similar: true
//     });
//     editor.formatter.toggle('fontSizeDynamic');
//   });
// });
// })();


// (function () {
//     tinymce.PluginManager.add('fontSizeCleaner', function (editor) {
//         // Registra un formato personalizado
//         editor.on('init', function () {
//             editor.formatter.register('fontSizeXl', {
//                 block: 'div',
//                 styles: { 'font-size': 'var(--text-xl)' },
//                 wrapper: true,
//                 remove_similar: true
//             });

//             editor.formatter.register('fontSizeXxs', {
//                 block: 'div',
//                 styles: { 'font-size': 'var(--text-xxs)' },
//                 wrapper: true,
//                 remove_similar: true
//             });
//         });

//         // Elimina wrappers previos con font-size antes de aplicar uno nuevo
//         function removeFontSizeWrapper() {
//             const node = editor.selection.getNode();
//             const wrapper = editor.dom.getParent(node, function (el) {
//                 return (
//                     el.nodeName === 'DIV' &&
//                     el.hasAttribute('style') &&
//                     /font-size\s*:\s*var\(--text-[^)]+\)/.test(el.getAttribute('style'))
//                 );
//             });

//             if (wrapper) {
//                 const content = wrapper.innerHTML;
//                 editor.dom.remove(wrapper, true);
//                 editor.insertContent(content);
//             }
//         }

//         // Escucha clicks en botones del toolbar para interceptar
//         editor.on('ExecCommand', function (e) {
//             if (e.command === 'mceToggleFormat') {
//                 // removeFontSizeWrapper(); // ejecuta antes del próximo formato
//             }
//         });
//     });
// })();


// (function () {
//     tinymce.PluginManager.add('fontSizeCleaner', function (editor) {
//         editor.on('ExecCommand', function (e) {

//             // console.log(e);

//             if (e.command === 'mceToggleFormat') {
//                 const node = editor.selection.getNode();

//                 // Busca un div que tenga font-size inline (usando style atributo directamente)
//                 // const wrapper = editor.dom.getParent(node, function (el) {
//                 //     return (
//                 //         el.nodeName === 'DIV' &&
//                 //         el.hasAttribute('style') &&
//                 //         /font-size\s*:\s*var\(--text-[^)]+\)/.test(el.getAttribute('style'))
//                 //     );
//                 // });

//                 // console.log('node',node);
//                 // console.log('node parent',node.parentNode);

//                 const wrapper = editor.dom.getParent(node.parentNode, function (el) {
//                     return (
//                       el.nodeName === 'DIV' &&
//                       el.style &&
//                       el.style.fontSize &&
//                       el.style.fontSize.startsWith('var(--text-')
//                     );
//                   });

//                 if (wrapper) {
//                     console.log('found', wrapper.style.fontSize);

//                     // Limpia el wrapper manteniendo su contenido
//                     // const content = wrapper.innerHTML;
//                     const content = node.parentNode.outerHTML;
//                     // console.log(content);
//                     // console.log(node.parentNode.outerHTML);

//                     console.log('eliminar', wrapper.parentNode);
//                     // editor.dom.remove(wrapper, true);
//                     // editor.insertContent(content);
//                 } else {
//                     console.log('no found', wrapper);
//                 }
//             }
//         });
//     });
// })();


// (function () {
//     tinymce.PluginManager.add('fontSizeCleaner', function (editor) {
//         editor.on('ExecCommand', function (e) {
//             if (e.command === 'mceToggleFormat') {
//                 const node = editor.selection.getNode();

//                 // Recorremos hacia arriba desde el nodo actual
//                 let current = node;
//                 while (current && current.nodeName !== 'BODY') {
//                     if (
//                         current.nodeName === 'DIV' &&
//                         current.style &&
//                         current.style.fontSize &&
//                         current.style.fontSize.startsWith('var(--text-')
//                     ) {
//                         const parent = current.parentNode;

//                         // Extraer contenido y reemplazar el DIV
//                         const range = document.createRange();
//                         range.selectNodeContents(current);
//                         const content = range.extractContents();
//                         parent.replaceChild(content, current);

//                         break; // Salimos luego de limpiar el primer div con font-size
//                     }
//                     current = current.parentNode;
//                 }
//             }
//         });
//     });
// })();


(function () {
  tinymce.PluginManager.add('fontSizeCleaner', function (editor) {
    editor.on('ExecCommand', function (e) {
      if (e.command === 'mceToggleFormat') {
        const node = editor.selection.getNode();

        // Empezamos desde el nodo actual
        let wrappers = [];
        let current = node;

        while (current && current.nodeName !== 'BODY') {
          if (
            current.nodeName === 'DIV' &&
            current.style &&
            current.style.fontSize &&
            current.style.fontSize.startsWith('var(--text-')
          ) {
            wrappers.push(current);
          }
          current = current.parentNode;
        }

        // Si hay más de uno, eliminamos todos excepto el más interno (el primero de la lista)
        if (wrappers.length > 1) {
          for (let i = 1; i < wrappers.length; i++) {
            const wrapper = wrappers[i];
            const parent = wrapper.parentNode;

            const range = document.createRange();
            range.selectNodeContents(wrapper);
            const content = range.extractContents();

            parent.replaceChild(content, wrapper);
          }
        }

        // Opcional: forzar refresh del editor
        editor.nodeChanged();
      }
    });
  });
})();
