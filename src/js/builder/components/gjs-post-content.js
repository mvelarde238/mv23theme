window.gjsPostContent = function (editor, options) {
    const domc = editor.DomComponents;

    domc.addType('post-content', {
        extend: 'async-component-abstract',
        model: {
            defaults: {
                name: 'Post Content',
                tagName: 'div',
                classes: ['component'],
                droppable: false,
                stylable: false,
                copyable: false,
                __additionalData: {
                    post_id: BUILDER_GLOBALS.post_id || null
                },
            },
        },
    });
};