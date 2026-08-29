window.gjsRelatedPosts = function (editor, options) {
    const domc = editor.DomComponents;

    domc.addType('related-posts', {
        extend: 'archive-posts',
        model: {
            defaults: {
                name: 'Related Posts',
                tagName: 'div',
                classes: ['component'],
                droppable: false,
                stylable: false,
                copyable: false,
                __additionalData: {
                    post_id: BUILDER_GLOBALS.post_id || null,
                    post_type: BUILDER_GLOBALS.posttype || null,
                },
            },
        },
    });
};