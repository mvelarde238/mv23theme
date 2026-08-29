window.gjsSocialShare = function (editor, options) {
    const domc = editor.DomComponents;

    domc.addType('social-share', {
        extend: 'async-component-abstract',
        model: {
            defaults: {
                name: 'Social Share',
                tagName: 'div',
                classes: ['component'],
                droppable: false,
                copyable: false,
                __additionalData: {
                    post_id: BUILDER_GLOBALS.post_id || null
                },
            },
        },
    });
};