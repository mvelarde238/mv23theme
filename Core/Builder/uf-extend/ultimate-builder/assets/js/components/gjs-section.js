window.gjsSection = function(editor) {
    const domc = editor.DomComponents;

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