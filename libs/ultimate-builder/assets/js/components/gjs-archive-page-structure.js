window.gjsArchivePageStructure = function (editor, options) {
    const domc = editor.DomComponents;

    let restrictedComponent = {
        tagName: 'div',
        droppable: false,
        stylable: false,
        copyable: false,
    };

    domc.addType('archive-title', {
        extend: 'async-component-abstract',
        model: {
            defaults: Object.assign({}, restrictedComponent, {
                name: 'Archive Title',
                classes: ['archive-title', 'component'],
                __additionalDataCallback: function(model, editor) {
                    const additionalData = BUILDER_GLOBALS.archive_settings;
                    return {
                        archive_settings: additionalData
                    };
                }
            }),
        }
    });

    domc.addType('archive-posts', {
        extend: 'async-component-abstract',
        model: {
            defaults: Object.assign({}, restrictedComponent, {
                name: 'Archive Posts',
                classes: ['archive-posts','component'],
                __additionalDataCallback: function(model, editor) {
                    const additionalData = BUILDER_GLOBALS.archive_settings;
                    return {
                        archive_settings: additionalData
                    };
                },
                __onSuccessCallback: (response, model, editor, datastore) => {
                    const el = model.getEl();
                    const {listing_template} = datastore.toJSON();
                    
                    // Create temporary container to parse response HTML
                    const temp = document.createElement('div');
                    temp.innerHTML = response.data;
                    const firstChild = temp.firstElementChild;

                    // TODO: review carrusel case if needed in the future
                    // if (listing_template === 'carousel') {
                    //     const postcards = temp.querySelectorAll('.postcard');
                    //     const carousel = model.findType('carousel')[0];
                    //     editor.UndoManager.stop();
                    //     postcards.forEach(postcard => {
                    //         const carouselItem = carousel.append({ type: 'carousel-item' }, { temporary: true });
                    //         carouselItem[0].getView().el.innerHTML = postcard.outerHTML;
                    //     });
                    //     editor.UndoManager.start();
                        
                    // } else {
                    //     // Remove class attribute from component to fix: settings dosnt apply on change datastore
                        if (firstChild) {
                            firstChild.removeAttribute('class');
                            el.innerHTML = temp.innerHTML;
                        }
                    // }

                    if (listing_template === 'masonry' && BUILDER_GLOBALS.masonry_is_active) {
                        setTimeout(function () {
                            jQuery(el).find('.posts-listing').masonry({
                                itemSelector: '.masonry-grid-item',
                                columnWidth: '.masonry-grid-sizer',
                                percentPosition: true,
                                gutter: 20
                            });
                        }, 100);
                    }
                },
            }),
        },
        view: {
            custom_datastore_change_callback(changed) {
                const model = this.model;
                
                // Ignore changes that only affect __tab (tab switching)
                const changed_keys = Object.keys(changed);
                if (changed_keys.length && changed_keys[0] === '__tab') return;

                $rerender_listing_on_change = [
                    // 'source',
                    // 'posttype',
                    // 'tax_params',
                    // 'query_params',
                    // 'status_params',
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

                // if listing template is masonry and columns value changed, re-render to update masonry layout
                if (changed_keys[0] === 'columns'){
                    const datastore = editor.getComponentDatastore(model);
                    const data = datastore.toJSON();
                    if (data.listing_template === 'masonry') {
                        this.render();
                    }
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

    // domc.addType('archive-page-structure', {
    //     model: {
    //         defaults: Object.assign({}, restrictedComponent, {
    //             name: 'Archive Page Structure',
    //             selectable: true,
    //             hoverable: true,
    //         }),
    //     },
    //     view: {
    //         custom_datastore_change_callback(changed) {
    //             const model = this.model;
                
    //             // Ignore changes that only affect __tab (tab switching)
    //             const changed_keys = Object.keys(changed);
    //             if (changed_keys.length && changed_keys[0] === '__tab') return;
                
    //             const datastore = editor.getComponentDatastore(model);
    //             const data = datastore.toJSON();
    //             const posttype = data.connected_posttype;
    //             const taxonomy = data['connected_'+posttype+'_taxonomy'];
    //             // const terms = data['connected_'+taxonomy+'_terms'];

    //             const archive_title = model.findType('archive-title')[0];
    //             const archive_posts = model.findType('archive-posts')[0];

    //             if ( changed.connected_posttype ) {
    //                 archive_title.getView().render();
    //             }
    //             if ( changed_keys[0] === 'connected_'+posttype+'_taxonomy' ) {
    //                 archive_title.getView().render();
    //             }
    //         },
    //     }
    // });
}