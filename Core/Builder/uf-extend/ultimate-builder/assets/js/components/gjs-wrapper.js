window.gjsWrapper = function (editor, options) {
    const domc = editor.DomComponents;

    /* Extend Wrapper (Body) Component */
    domc.addType('wrapper', {
        model: {
            defaults: {
                name: 'Page',
                droppable: false,
                highlightable: false,
                stylable: true,
                unstylable: []
            },
        },
        view: {
            onRender({el, model}) {
                // Initial handling of datastore data
                setTimeout(() => {
                    this.handle_datastore_data();
                }, 100);
            },
            custom_datastore_change_callback(changed) {
                const model = this.model;
                
                // Ignore changes that only affect __tab (tab switching)
                const changed_keys = Object.keys(changed);
                if (changed_keys.length && changed_keys[0] === '__tab') return;

                // NOTE: We specifically check for the first changed key to be 'custom_header_post' because when this value changes can be "falsy"
                // for example when the custom header post is unset. We want to make sure to still trigger the header preview update in that case.
                if (changed_keys[0] === 'custom_header_post') {
                    // If header post is changed, we need to trigger a full re-render to update the header preview component with the new header post data.
                    const header_preview = model.findType('header-preview')[0];
                    if (header_preview) {
                        if( changed.custom_header_post ){
                            header_preview.set('filters_to_apply', [
                                {
                                    'name': 'pre_option_theme_header_post',
                                    'value': changed.custom_header_post
                                }, 
                            ]);
                        } else {
                            header_preview.set('filters_to_apply', []);
                        }
                        header_preview.view.render();
                    }
                }

                if( changed_keys.includes('settings') ){
                    editor.handleCommonSettings(model);
                } else {
                    this.handle_datastore_data();
                }
            },
            handle_datastore_data() {
                const model = this.model;

                const datastore = editor.getComponentDatastore(model);
                if (datastore) {
                    const data = datastore.toJSON();
                    
                    // Handle placing content under header by removing padding top from canvas body
                    const { place_content_under_header } = data;
                    const canvas = editor.Canvas;
                    const canvasBody = canvas.getBody();
                    const wrapper = editor.getWrapper();
                    
                    if (place_content_under_header) {
                        wrapper.getEl().style.paddingTop = '0px';
                    } else {
                        wrapper.getEl().style.paddingTop = 'var(--static-header-height)';
                    }

                    // Handle hiding/showing static and sticky header and their logos by adding/removing classes to canvas body
                    const headerKeys = ['static', 'sticky'];
                    for (const key of headerKeys) {
                        const hideHeader = data['hide_' + key + '_header'] ?? false;
                        if (hideHeader) {
                            canvasBody.classList.add('hide-' + key + '-header');
                        } else {
                            canvasBody.classList.remove('hide-' + key + '-header');
                        }
                        
                        const hideHeaderLogo = data['hide_' + key + '_header_logo'] ?? false;
                        if (hideHeaderLogo) {
                            canvasBody.classList.add('hide-' + key + '-header-logo');
                        } else {
                            canvasBody.classList.remove('hide-' + key + '-header-logo');
                        }
                    }

                    // Handle hiding sticky header on builder by adding/removing class to canvas body
                    if ( BUILDER_GLOBALS.posttype != 'header' ){
                        const hideStickyHeaderOnBuilder = data['hide_sticky_header_on_builder'] ?? true;
                        if (hideStickyHeaderOnBuilder) {
                            canvasBody.classList.add('hide-sticky-header');
                        } else {
                            canvasBody.classList.remove('hide-sticky-header');
                        }
                    }
                }
            }
        }
    });
}