window.gjsPostContent = function (editor, options) {
    const domc = editor.DomComponents;

    domc.addType('post-content', {
        extend: 'async-component-abstract',
        model: {
            defaults: {
                name: 'Post Content',
                tagName: 'div',
                classes: ['post-content', 'component'],
                droppable: false,
                copyable: false,
            },
        },
    });
};