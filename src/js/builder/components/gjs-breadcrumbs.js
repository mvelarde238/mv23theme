window.gjsBreadcrumbs = function (editor, options) {
    const domc = editor.DomComponents;

    domc.addType('breadcrumbs', {
        extend: 'async-component-abstract',
        model: {
            defaults: {
                name: 'Breadcrumbs',
                tagName: 'div',
                classes: ['component'],
                droppable: false,
                copyable: false
            },
        },
    });
};