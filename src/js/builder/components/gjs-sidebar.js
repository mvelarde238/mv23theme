window.gjsSidebar = function (editor, options) {
    const domc = editor.DomComponents;

    domc.addType('sidebar', {
        extend: 'async-component-abstract',
        model: {
            defaults: {
                name: 'Sidebar',
                tagName: 'div',
                classes: ['sidebar','component'],
                droppable: false,
                copyable: false
            },
        },
    });
};