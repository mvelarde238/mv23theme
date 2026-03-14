window.gjsSidebar = function (editor, options) {
    const domc = editor.DomComponents;

    domc.addType('sidebar', {
        extend: 'async-component-abstract',
        model: {
            defaults: {
                name: 'Sidebar',
                tagName: 'div',
                droppable: false,
                stylable: false,
                copyable: false,
                __additionalDataCallback: (model, editor) => {
                    const data = {};
                    if (BUILDER_GLOBALS.post_id) {
                        data['post_id'] = BUILDER_GLOBALS.post_id;
                    }
                    return data;
                },
            },
        },
    });
}