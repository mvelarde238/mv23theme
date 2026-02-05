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
                removable: false,
                copyable: false,
                draggable: false,
                badgable: false,
                highlightable: false,
                selectable: false,
                hoverable: false,
                __additionalData: {
                    post_id: BUILDER_GLOBALS.post_id || null
                },
            },
        },
    });
}