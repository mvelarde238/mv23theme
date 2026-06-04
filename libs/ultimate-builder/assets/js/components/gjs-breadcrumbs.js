window.gjsBreadcrumbs = function (editor, options) {
    const domc = editor.DomComponents;

    domc.addType('breadcrumbs', {
        extend: 'async-component-abstract',
        model: {
            defaults: {
                name: 'Breadcrumbs',
                tagName: 'div',
                classes: ['component'],
                droppable: false,
                copyable: false,
                __additionalDataCallback: function(model, editor) {
                    let post_id = BUILDER_GLOBALS.post_id || null;

                    if( BUILDER_GLOBALS.is_singular ){
                        // On singular pages, use the context post ID
                        post_id = BUILDER_GLOBALS.context?.post?.id || post_id;
                    }

                    return {
                        post_id: post_id
                    };
                }
            },
        },
    });
}