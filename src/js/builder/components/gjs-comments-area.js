window.gjsCommentsArea = function (editor, options) {
    const domc = editor.DomComponents;

    domc.addType('comments-area', {
        extend: 'async-component-abstract',
        model: {
            defaults: {
                name: 'Comments Area',
                tagName: 'div',
                classes: ['comments-area','component'],
                droppable: false,
                copyable: false
            },
        },
    });
};