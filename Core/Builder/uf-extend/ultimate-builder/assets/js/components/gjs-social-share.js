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