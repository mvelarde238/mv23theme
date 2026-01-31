window.gjsHeader = function (editor, options) {
    const domc = editor.DomComponents;
    const compClass = 'header';

    // TODO
    // Component should react to changes in Page_Settings datastore
    // and re-render itself accordingly.

    let notSelectableComponent = {
        tagName: 'div',
        droppable: false,
        stylable: false,
        removable: false,
        copyable: false,
        draggable: false,
        badgable: false,
        highlightable: false,
        selectable: false,
        hoverable: false,
    };

    domc.addType(compClass, {
        model: {
            defaults: Object.assign({}, notSelectableComponent, {
                name: 'Header',
                tagName: 'section'
            }),
        },
        view: {
            onRender({el, model}) {
                // const datastore = editor.getComponentDatastore(model);
                // const data = datastore.toJSON();
                
                const data = {
                    '__type': 'header',
                    'action': 'get_component_view',
                    'post_id': BUILDER_GLOBALS.post_id,
                };
                jQuery.ajax({
                    type: "POST",
                    dataType: "json",
                    url: MV23_GLOBALS.ajaxUrl,
                    data: data,
                    success: function(response) {
                        el.innerHTML = response.data;
                    },
                    error: function(xhr, status, error) {
                        console.error(`Error loading ${compClass} component view:`, error);
                    }
                });
            },
        },
    });

    // On builder loaded, append to wrapper component
    editor.on('builder:loaded', () => {
        const wrapper = editor.getWrapper();
        const header_exists = wrapper.findType('header')[0];
        if ( !header_exists ) {
            wrapper.append({type: 'header'}, {at: 0});
        }
    });

    // Before saving remove header
    editor.on('builder:before-save-editor', () => {
        const wrapper = editor.getWrapper();
        const header = wrapper.findType('header')[0];
        if ( header ) {
            header.remove({silent: true});
        }
    });
}