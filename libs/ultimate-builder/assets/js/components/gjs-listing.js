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
            propagate: ['selectable', 'draggable'],
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
    config.canvasCss += `.listing a { pointer-events: none; }
        .listing .postcard { flex-shrink: 0; }
        .listing .carousel-wrapper .component__actions { display: none; }`;
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
                components: defaultComponents,
                __beforeSendCallback: (model, editor, datastore) => {
                    editor.ensureComponentStructure(model, defaultComponents, unwantedProps);
                    
                    const {listing_template, columns, carousel_settings, columns_gap} = datastore.toJSON();
                    const carouselWrapper = model.findType('carousel-wrapper')[0];
                    const carousel = model.findType('carousel')[0];
                    carousel.empty({silent: true});
                    
                    if (listing_template === 'carousel') {
                        carouselWrapper.getView().el.style.display = '';
                        const carouselDatastore = editor.getComponentDatastore(carouselWrapper);
                        carouselDatastore.set({
                            items: columns,
                            controls_settings: { 
                                show: carousel_settings.show_controls,
                                position: 'center'
                            },
                            nav_settings: {
                                show: carousel_settings.show_nav,
                                position: 'bottom',
                            },
                            autoplay_settings: {
                                active: carousel_settings.autoplay,
                                timeout: '',
                            },
                            slider_uid: carousel_settings.carousel_id,
                            gutter: columns_gap
                        }, {silent: true} );
                        carouselWrapper.getView().render();
                    } else {
                        carouselWrapper.getView().el.style.display = 'none';
                    }
                },
                __onSuccessCallback: (response, model, editor, datastore) => {
                    const el = model.getEl();
                    const {listing_template} = datastore.toJSON();
                    
                    // Create temporary container to parse response HTML
                    const temp = document.createElement('div');
                    temp.innerHTML = response.data;
                    const firstChild = temp.firstElementChild;

                    if (listing_template === 'carousel') {
                        const postcards = temp.querySelectorAll('.postcard');
                        const carousel = model.findType('carousel')[0];
                        editor.UndoManager.stop();
                        postcards.forEach(postcard => {
                            const carouselItem = carousel.append({ type: 'carousel-item' }, { temporary: true });
                            carouselItem[0].getView().el.innerHTML = postcard.outerHTML;
                        });
                        editor.UndoManager.start();
                        
                    } else {
                        // Remove class attribute from component to fix: settings dosnt apply on change datastore
                        if (firstChild) {
                            firstChild.removeAttribute('class');
                            el.innerHTML = temp.innerHTML;
                        }
                    }

                    if (listing_template === 'masonry' && BUILDER_GLOBALS.masonry_is_active) {
                        setTimeout(function () {
                            jQuery(el).find('.posts-listing').masonry({
                                itemSelector: '.masonry-grid-item',
                                columnWidth: '.masonry-grid-sizer',
                                percentPosition: true,
                                gutter: 20
                            });
                        }, 50);
                    }
                },
            },
        },
        view: {
            custom_datastore_change_callback(changed) {
                const model = this.model;
                
                // Ignore changes that only affect __tab (tab switching)
                const changed_keys = Object.keys(changed);
                if (changed_keys.length && changed_keys[0] === '__tab') return;

                $rerender_listing_on_change = [
                    'source',
                    'posttype',
                    'tax_params',
                    'query_params',
                    'status_params',
                    'listing_template', 
                    'carousel_settings',
                    'postcard_settings',
                    'pagination_type',
                    'show_filter',
                    'filters',
                ];
                if ( $rerender_listing_on_change.includes( changed_keys[0] ) ) {
                    this.render();
                }

                if (changed_keys[0] === 'columns' || changed_keys[0] === 'columns_gap') {
                    // Update CSS properties for columns and gap
                    const datastore = editor.getComponentDatastore(model);
                    const data = datastore.toJSON();
                    const listing_template = data.listing_template;
                    const listingElSelector = listing_template === 'carousel' ? '.carousel__slider' : '.posts-listing';
                    const listingEl = model.getEl().querySelector(listingElSelector);
                    const devices = ['desktop', 'laptop', 'tablet', 'mobile'];
                    const columns = data.columns || {};
                    const gaps = data.columns_gap || {};
                    if (listingEl) {
                        devices.forEach(device => {
                            listingEl.style.setProperty(`--${device[0]}-columns`, columns[device]);
                            listingEl.style.setProperty(`--${device[0]}-gap`, gaps[device]+'px');
                        });
                    }
                }

                if( changed_keys.includes('settings') ){
                    editor.handleCommonSettings(model);
                }
            },
        },
    });

    // Before saving remove data that shouldn't be saved
    editor.on('builder:before-save-editor', () => {
        const wrapper = editor.getWrapper();
        const container = wrapper.findType('container')[0];
        const listings = container.findType('section');
        listings.forEach(listing => {
            const carouselItems = listing.findType('carousel-item');
            carouselItems.forEach(carouselItem => {
                carouselItem.remove({silent: true});
            });
        });
    });

    UltimateFields.addFilter('builder_component_cleanup', function(data) {
        if (data.component.type === compClass) {
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
    });
}