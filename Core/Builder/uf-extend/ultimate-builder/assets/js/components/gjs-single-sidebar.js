window.gjsSingleSidebar = function (editor, options) {
    const domc = editor.DomComponents;

    domc.addType('single_sidebar', {
        model: {
            defaults: {
                name: 'Single Sidebar',
                tagName: 'div',
                classes: ['single-sidebar', 'sidebar'],
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