window.gjsSidebar = function (editor, options) {
    const domc = editor.DomComponents;

    domc.addType('sidebar', {
        model: {
            defaults: {
                name: 'Sidebar',
                tagName: 'div',
                droppable: false,
                stylable: false,
                removable: false,
                copyable: false,
                draggable: false,
                badgable: false,
                highlightable: false,
                selectable: false,
                hoverable: false
            },
        },
        view: {
            onRender({el, model}) {
                const datastore = editor.getComponentDatastore(model);
                
				if (datastore) {
                    const data = datastore.toJSON();
                    data['action'] = 'get_component_view';

                    if( BUILDER_GLOBALS.post_id ) {
                        data['post_id'] = BUILDER_GLOBALS.post_id;
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
                            console.error(`Error loading single_sidebar component view:`, error);
                        }
                    });
                }
            },
        },
    });
}