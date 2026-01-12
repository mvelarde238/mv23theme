window.gjsSection = function(editor) {
    const domc = editor.DomComponents;

    // Make classes private
    const privateCls = ['.page-module', '.page-module--layout1', '.page-module--layout2', '.page-module--layout3'];
    editor.on('selector:add', selector => privateCls.indexOf(selector.getFullName()) >= 0 && selector.set('private', 1));

    // Add the section type to the DomComponents
    domc.addType('section', {
        isComponent: el => el.classList && el.classList.contains('page-module'),
        model: {
            defaults: {
                name: 'Section',
                tagName: 'div',
                draggable: function (target, destination) {
                    return (destination.attributes.type) == 'container';
                },
                classes: ['page-module'],
            },
        },
        // view: {
        //     init( el, model ) {
        //         const componests = model.components();
        //         if (componests.length === 0) {
        //             // TODO: Add buttons to add inner structures for new sections
        //             // eg: row with 2 columns, row with 3 columns, etc.
        //         }   
        //     }
        // }
    });

    // Add a block in structure category
    editor.Blocks.add('section', {
        label: 'Section',
        media: '<i class="dashicons dashicons-align-wide"></i>',
        category: 'Structure',
        content: { type: 'section' }
    });
}