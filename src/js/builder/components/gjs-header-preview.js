window.gjsHeaderPreview = function (editor, options) {
    const domc = editor.DomComponents;
    const compClass = 'header-preview';

    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const headerPreviewName = __('Header Preview');

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
        layerable: false,
    };

    domc.addType(compClass, {
        extend: 'async-component-abstract',
        model: {
            defaults: Object.assign({}, notSelectableComponent, {
                name: headerPreviewName,
                tagName: 'section',
                __additionalDataCallback: (model, editor) => {
                    let apply_filters = false;
                    let filters_to_apply = [];

                    // check if there's a custom header post set in the datastore and if so, 
                    // apply the filter to the header preview component to show the correct header in the preview
                    const wrapper = editor.getWrapper();
                    if (wrapper) {
                        const wrapper_datastore = editor.getComponentDatastore(wrapper);
                        const { custom_header_post } = wrapper_datastore.toJSON();

                        if (custom_header_post && custom_header_post !== '0') {
                            apply_filters = true;
                            filters_to_apply.push({
                                'name': 'pre_option_theme_header_post',
                                'value': custom_header_post
                            });
                        }
                    }

                    return {
                        'apply_filters': apply_filters,
                        'filters_to_apply': filters_to_apply,
                    };
                },
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
};