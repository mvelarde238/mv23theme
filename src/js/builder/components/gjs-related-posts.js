window.gjsRelatedPosts = function (editor, options) {
    const domc = editor.DomComponents;

    domc.addType('related-posts', {
        extend: 'listing',
        model: {
            defaults: {
                name: 'Related Posts',
                tagName: 'div',
                classes: ['related-posts','component'],
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