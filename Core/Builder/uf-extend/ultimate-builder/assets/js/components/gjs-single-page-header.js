window.gjsSinglePageHeader = function (editor, options) {
    const domc = editor.DomComponents;

    domc.addType('single_page_header', {
        model: {
            defaults: {
                name: 'Single Page Header',
                tagName: 'div',
                classes: ['single-page-header', 'page-header'],
                droppable: false,
                stylable: false,
                removable: false,
                copyable: false,
                draggable: false,
                badgable: false,
                highlightable: false
            },
        },
        view: {
            onRender({el, model}) {
                const datastore = editor.getComponentDatastore(model);
                
				if (datastore) {
                    const data = datastore.toJSON();
                    data['action'] = 'get_component_view';

                    const editorConfig = editor.getConfig();
                    const page_context = editorConfig.page_context || {};
                    if( page_context.post_id ) {
                        data['post_id'] = page_context.post_id;
                    }

                    jQuery.ajax({
                        type: "POST",
                        dataType: "json",
                        url: MV23_GLOBALS.ajaxUrl,
                        data: data,
                        success: function(response) {
                            el.innerHTML = response.data;
                        },
                        error: function(xhr, status, error) {
                            console.error(`Error loading single_page_header component view:`, error);
                        }
                    });
                }
            },
        },
    });
}