window.gjsCommentsArea = function (editor, options) {
    const domc = editor.DomComponents;

    domc.addType('comments-area', {
        extend: 'async-component-abstract',
        model: {
            defaults: {
                name: 'Comments Area',
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