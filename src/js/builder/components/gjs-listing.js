window.gjsListing = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'listing';

    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const compName = __('Listing');

    const unwantedProps = ['removable', 'copyable', 'draggable', 'selectable', 'badgable', 'propagate', 'resizable', 'droppable', 'delegate','layerable'];

    const defaultComponents = [
        {
            type: 'carousel-wrapper',
            selectable: false,
            draggable: false,
            layerable: false,
            droppable: false,
            propagate: ['selectable', 'draggable', 'droppable'],
            components: [
                {
                    type: 'carousel',
                    components: []

                },
                { type: 'carousel-controls' },
                { type: 'carousel-nav' },
            ]
        }
    ];

    // add custom css to canvasCss
    let config = editor.getConfig();
    config.canvasCss = config.canvasCss || '';
    config.canvasCss += `.listing-patch a { pointer-events: none; }
        .listing-patch .postcard { flex-shrink: 0; }
        .listing-patch .carousel-wrapper .component__actions { display: none; }`;
    editor.canvasCss = config.canvasCss;

    // Define the component
    domc.addType(compClass, {
        extend: 'async-component-abstract',
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: compName,
                tagName: 'div',
                classes: [compClass,'component'],
                __beforeSendCallback: (model, editor, datastore) => {
                    
                    const {listing_template, columns, slider_settings, columns_gap} = datastore.toJSON();
                    
                    if (listing_template === 'carousel') {
                        editor.ensureComponentStructure(model, defaultComponents, unwantedProps);
                        const carouselWrapper = model.findType('carousel-wrapper')[0];
                        const carousel = model.findType('carousel')[0];
                        carousel.empty({silent: true});

                        carouselWrapper.getView().el.style.display = '';
                        
                        const carouselDatastore = editor.getComponentDatastore(carouselWrapper);
                        carouselDatastore.set({
                            slider_settings: slider_settings,
                            items: columns,
                            gutter: columns_gap
                        }, {silent: true} );
                        carouselWrapper.getView().render();

                    } else {
                        const carouselWrapper = model.findType('carousel-wrapper')[0];
                        carouselWrapper?.remove();
                    }
                },
                __onSuccessCallback: (response, model, editor, datastore) => {
                    const el = model.getEl();
                    const {listing_template} = datastore.toJSON();

                    // Add a temporary class to fix listing styles in the canvas 
                    // in all listing components (Listing, Archive Posts, Related Posts)
                    el.classList.add('listing-patch');
                    
                    // Create temporary container to parse response HTML
                    const temp = document.createElement('div');
                    temp.innerHTML = response.data;
                    const firstChild = temp.firstElementChild;

                    if (listing_template === 'carousel') {
                        const postcards = temp.querySelectorAll('.postcard');
                        const carousel = model.findType('carousel')[0];
                        editor.UndoManager.stop();
                        postcards.forEach(postcard => {
                            const carouselItem = carousel.append({ 
                                type: 'carousel-item',
                                selectable: false,
                                droppable: false,
                                copyable: false,
                                hoverable: false,
                            }, { temporary: true });
                            carouselItem[0].getView().el.innerHTML = postcard.outerHTML;
                        });
                        editor.UndoManager.start();

                        // extract and paste postcard <style> if it exists
                        const postcardStyle = temp.querySelector('style');
                        if (postcardStyle) el.appendChild(postcardStyle);
                        
                    } else {
                        // Remove class attribute from component to fix: settings dosnt apply on change datastore
                        if (firstChild) {
                            firstChild.removeAttribute('class');
                            el.innerHTML = temp.innerHTML;
                        }
                    }

                    if (listing_template === 'masonry' && BUILDER_GLOBALS.masonry_is_active) {
                        const listingEl = el.querySelector('.posts-listing');
                        imagesLoaded(listingEl, function () {
                            new Packery(listingEl, {
                                itemSelector: '.masonry-grid-item',
                                columnWidth: '.masonry-grid-sizer',
                                gutter: '.masonry-gutter-sizer',
                                percentPosition: true,
                            });
                        });
                    }
                },
            },
        },
        view: {
            init(){
                editor.on('change:device', this.handle_editor_resize.bind(this));
            },
            custom_datastore_change_callback(changed) {
                const model = this.model;
                
                // Ignore changes that only affect __tab (tab switching)
                const changed_keys = Object.keys(changed);
                if (changed_keys.length && changed_keys[0] === '__tab') return;

                $rerender_listing_on_change = [
                    'source',
                    'posts',
                    'posttype',
                    'woocommerce_key',
                    'query_parameters',
                    'tax_queries',
                    'meta_queries',
                    'listing_template',
                    'postcard_settings',
                    'pagination_type',
                ];
                if ( $rerender_listing_on_change.includes( changed_keys[0] ) ) {
                    this.render();
                }

                const datastore = editor.getComponentDatastore(model);
                const {listing_template, columns, slider_settings, columns_gap,} = datastore.toJSON();

                // if listing template is masonry and columns value changed, re-render to update masonry layout
                if (changed_keys[0] === 'columns' && listing_template === 'masonry'){
                    this.render();
                }

                // Update CSS properties for columns and gap
                if (changed_keys[0] === 'columns' || changed_keys[0] === 'columns_gap') {
                    const listingElSelector = listing_template === 'carousel' ? '.carousel__slider' : '.posts-listing';
                    const listingEl = model.getEl().querySelector(listingElSelector);
                    
                    if (listingEl) {
                        const devices = ['desktop', 'laptop', 'tablet', 'mobile'];
                        devices.forEach(device => {
                            listingEl.style.setProperty(`--${device[0]}-columns`, columns[device]);
                            listingEl.style.setProperty(`--${device[0]}-gap`, columns_gap[device]+'px');
                        });
                    }
                }

                if( changed_keys.includes('settings') ){
                    editor.handleCommonSettings(model);
                }

                if( changed_keys.includes('slider_settings') && listing_template === 'carousel' ){
                    const carouselWrapper = model.findType('carousel-wrapper')[0];
                    const carouselDatastore = editor.getComponentDatastore(carouselWrapper);                       
                    carouselDatastore.set('slider_settings', slider_settings);
                    carouselWrapper.view.custom_datastore_change_callback({'slider_settings': slider_settings});
                }

                this.toggle_group_types_visibility(data.posttype);
            },
            toggle_group_types_visibility(posttype) {
                const model = this.model;
                const builder_comp_model = editor.getBuilderCompModel(model);
                if (builder_comp_model) {
                    const fields_models = builder_comp_model.get('fields') || {};
                    const tax_queries_repeater = fields_models.find(fm => fm.get('name') == 'tax_queries');
                    if (tax_queries_repeater) {
                        const groupTypes = tax_queries_repeater.groupTypes || [];
                        groupTypes.forEach(group => {
                            const group_id = group.get('id');
                            if (group_id.startsWith('taxgroup__')) {
                                const group_posttype = group_id.split('__')[1];
                                if (group_posttype !== posttype) {
                                    group.set('can_be_added', false);
                                } else {
                                    group.set('can_be_added', true);
                                }
                            }
                        });
                        tax_queries_repeater.trigger('value-replaced');
                    }
                }
            },
            handle_editor_resize(obj) {
                // trigger global resize event to make sure all components that need to adjust on editor resize can do it
                setTimeout(() => {
                    window.dispatchEvent(new Event('resize'));
                }, 150);
            },
        },
    });

    // on openDatastore, check if the component is a listing and if so, call toggle_group_types_visibility with the current posttype
    editor.on('openDatastore', (builder_comp_model, component) => {
        if (component && component.get('type') === compClass) {
            const datastore = editor.getComponentDatastore(component);
            if (datastore) {
                const { posttype } = datastore.toJSON();
                setTimeout(() => {
                    component.view.toggle_group_types_visibility(posttype); 
                }, 50);
            }
        }
    });

    // Before saving remove data that shouldn't be saved
    editor.on('builder:before-save-editor', () => {
        const wrapper = editor.getWrapper();
        const page_content = wrapper.findType('page-content')[0];

        const listings = page_content.findType('listing');
        const archivePosts = page_content.findType('archive-posts');
        const relatedPosts = page_content.findType('related-posts');
        const listingComponents = [...listings, ...archivePosts, ...relatedPosts];

        listingComponents.forEach(listing => {
            // Remove any carousel items before saving to prevent them from being persisted in JSON
            const carouselItems = listing.findType('carousel-item');
            carouselItems.forEach(carouselItem => {
                carouselItem.remove({silent: true});
            });

            // remove carouselWrapper datastore
            const carouselWrapper = listing.findType('carousel-wrapper')[0];
            if (carouselWrapper) {
                const datastore = editor.getComponentDatastore(carouselWrapper);
                if (datastore) {
                    datastore.clear();
                }
            }
        });
    });

    UltimateFields.addFilter('builder_component_cleanup', function(data) {
        const listingComponentsType = ['listing', 'archive-posts', 'related-posts'];

        if (listingComponentsType.includes(data.component.type)) {
            // Recursively strip behavioral props from nested children so they are never persisted in JSON.
            // These props are re-applied at runtime by ensureComponentStructure from defaultComponents.
            const cleanupNestedComponents = (obj) => {
                if (!obj || !Array.isArray(obj.components)) return;

                obj.components.forEach(child => {
                    unwantedProps.forEach(prop => delete child[prop]);
                    cleanupNestedComponents(child);
                });
            };
            cleanupNestedComponents(data.builderComponent);
        }

        if (data.component.type === compClass) {
            if( data.componentDataStore.listing_template != 'carousel' ){
                delete data.componentDataStore.slider_settings;
            }
        }
    });
};