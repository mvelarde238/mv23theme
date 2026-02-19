window.gjsHeaderPreview = function (editor, options) {
    const domc = editor.DomComponents;
    const compClass = 'header-preview';

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
        extend: 'async-component-abstract',
        model: {
            defaults: Object.assign({}, notSelectableComponent, {
                name: 'Header Preview',
                tagName: 'section',
                __additionalDataCallback: (model, editor) => ({
                    '__type': 'header-preview',
                    'post_id': BUILDER_GLOBALS.post_id,
                    'apply_filters': model.get('apply_filters') || false,
                    'filters_to_apply': model.get('filters_to_apply') || [],
                }),
                __onSuccessCallback: (response, model, editor) => {
                    const el = model.getEl();
                    el.innerHTML = response.data;
                    
                    // Init sticky header
                    stickyHeader.init(
                        el.querySelector('.header'), 
                        editor.Canvas.getWindow(), 
                        BUILDER_GLOBALS.stickyHeaderBreakpoint
                    );
                }
            }),
        },
    });

    // On builder loaded, append to wrapper component
    editor.on('builder:loaded', () => {
        if ( BUILDER_GLOBALS.posttype === 'header' ) return;

        const wrapper = editor.getWrapper();
        const header_preview_exists = wrapper.findType('header-preview')[0];
        
        if (!header_preview_exists) {
            wrapper.append({ type: 'header-preview' }, { at: 0 });
        }

        // check if there's a custom header post set in the datastore and if so, 
        // apply the filter to the header preview component to show the correct header in the preview
        const wrapper_datastore = editor.getComponentDatastore(wrapper);
        if (wrapper_datastore) {
            const { custom_header_post } = wrapper_datastore.toJSON();
            const header_preview = wrapper.findType('header-preview')[0];
            if (custom_header_post && custom_header_post !== '0' && header_preview) {
                header_preview.set('filters_to_apply', [
                    {
                        'name': 'pre_option_theme_header_post',
                        'value': custom_header_post
                    }, 
                ]);
                header_preview.view.render();
            }
        }
    });

    // Before saving remove header preview component as it's only for previewing and shouldn't be saved in the content
    editor.on('builder:before-save-editor', () => {
        if ( BUILDER_GLOBALS.posttype === 'header' ) return;

        const wrapper = editor.getWrapper();
        const header_preview = wrapper.findType('header-preview')[0];
        if (header_preview) {
            header_preview.remove({ silent: true });
        }
    });
}