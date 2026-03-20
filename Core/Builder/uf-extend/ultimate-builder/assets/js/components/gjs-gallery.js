window.gjsGallery = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'gallery';

    // add custom css to canvasCss
    let config = editor.getConfig();
    config.canvasCss = config.canvasCss || '';
    config.canvasCss += `.theme-gallery-comp {width: 100%;}`;
    config.canvasCss += `.theme-gallery .grid-stack-item a {pointer-events: none;}`;
    editor.canvasCss = config.canvasCss;

    domc.addType(compClass, {
        extend: 'async-component-abstract',
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: 'Gallery',
                tagName: 'div',
                classes: [compClass, 'component'],
                __additionalDataCallback: (model, editor) => {
                    const grid_data = model.get('grid_data') || [];
                    return { grid_data };
                },
                __onSuccessCallback: (response, model, editor) => {
                    const el = model.getEl();
                    const temp = document.createElement('div');
                    temp.innerHTML = response.data;
                    const firstChild = temp.firstElementChild;
                    
                    if (firstChild) {
                        firstChild.removeAttribute('id');
                        el.innerHTML = temp.innerHTML;
                        
                        // Trigger a custom event to notify that the gallery content has been updated, 
                        // so that the view can re-initialize the grid and masonry layouts
                        model.trigger('change:__temp-handle-gallery');
                    }
                },
            },
        },
        view: {
            init({model}){
                this.listenTo(model, 'change:__temp-handle-gallery', this.handle_gallery_display_change);
                editor.on('change:device', this.handle_editor_resize.bind(this));
            },
            handle_gallery_display_change() {
                const model = this.model;
                const galleryEl = model.getEl().querySelector('.theme-gallery');
                if(!galleryEl) return;
                
                const builder_comp_model = editor.getBuilderCompModel(model);
                const datastore = editor.getComponentDatastore(model);
                const {display, gallery} = datastore.toJSON();

                if(display === 'grid'){
                    var grid = GridStack.init({
                        resizable: {
                            handles: 'e,se,s,sw,w'
                        },
                        margin: 5,
                    }, galleryEl);

                    // store the attachment ID in the gridstackNode for later retrieval:
                    const items = grid.getGridItems();
                    items.forEach((item, index) => {
                        const attachment_id = gallery[index];
                        item.gridstackNode.attachment_id = attachment_id; 
                    });

                    grid.on('change', (event, changed_items) => {
                        const gridData = grid.save();
                        model.set('grid_data', gridData);

                        // Get the current order of attachment IDs from gridData
                        const attachmentOrder = gridData.map(item => item.attachment_id);
                        // If the order has changed compared to the original gallery array, update the datastore and trigger the model update
                        if(attachmentOrder.length !== gallery.length || !attachmentOrder.every((id, index) => id === gallery[index])){
                            datastore.set({'gallery': attachmentOrder}, {silent: true});
                            // Trigger update-views on gallery field
                            editor.updateBuilderCompModelField(builder_comp_model, 'gallery');
                        }
                    });
                }

                if(display === 'masonry' && BUILDER_GLOBALS.masonry_is_active){
                    setTimeout(function () {
                        jQuery(galleryEl).masonry({
                            itemSelector: '.theme-gallery__item',
                            columnWidth: '.theme-gallery__item-sizer',
                            percentPosition: true
                        });
                    }, 50);
                }
            },
            custom_datastore_change_callback(changed) {
                const model = this.model;
                
                // Ignore changes that only affect __tab (tab switching)
                const changed_keys = Object.keys(changed);
                if ( changed_keys[0] === '__tab') return;

                $rerender_gallery_on_change = [
                    'display', 'source', 'placeholders_quantity', 'gallery', 'wp_media_folder', 'placeholders_source'
                ];
                if ( 
                    $rerender_gallery_on_change.includes( changed_keys[0] ) ||
                    // in some cases "gallery" field changes might come with an empty "changed" object, so we want to re-render in that case as well:
                    changed_keys.length === 0 
                ) {
                    this.render();
                    return;
                }

                // For other changes, we can handle specific updates without full re-render
                const datastore = editor.getComponentDatastore(model);
                const data = datastore.toJSON();
                const galleryEl = model.getEl().querySelector('.theme-gallery');

                if (changed_keys[0] === 'items' || changed_keys[0] === 'gutter') {
                    // Update CSS properties for columns and gap
                    const devices = ['desktop', 'laptop', 'tablet', 'mobile'];
                    const columns = data.items || {};
                    const gaps = data.gutter || {};
                    if (galleryEl) {
                        devices.forEach(device => {
                            galleryEl.style.setProperty(`--${device[0]}-columns`, columns[device]);
                            galleryEl.style.setProperty(`--${device[0]}-gap`, gaps[device]+'px');
                        });
                    }
                }
                if (changed_keys[0] === 'aspect_ratio') {
                    // Update CSS property for aspect ratio
                    if (galleryEl) {
                        galleryEl.style.setProperty('--aspect-ratio', data.aspect_ratio);
                    }
                }
                if (changed_keys[0] === 'marquee_settings') {
                    // Update CSS property for fade width and data attribute for marquee direction and speed
                    const marqueeSettings = data.marquee_settings || {};
                    if (galleryEl) {
                        galleryEl.style.setProperty('--fade-width', marqueeSettings.fade_width || '0px');
                        galleryEl.setAttribute('data-direction', marqueeSettings.direction || 'left');
                        galleryEl.setAttribute('data-speed', marqueeSettings.speed || 0);
                    }
                }

                const updateImageDimensions = (property, cssProperties) => {
                    if (changed_keys[0] === property) {
                        const images = model.getEl().querySelectorAll('.theme-gallery__item img, .theme-gallery__item video');
                        images.forEach(image => {
                            cssProperties.forEach(({ key, style }) => {
                                if (data[property]?.use && data[property][key]) {
                                    image.style[style] = data[property][key];
                                } else {
                                    image.style[style] = '';
                                }
                            });
                        });
                    }
                };

                updateImageDimensions('images_height', [
                    { key: 'height', style: 'height' },
                    { key: 'max_height', style: 'maxHeight' },
                    { key: 'min_height', style: 'minHeight' }
                ]);

                updateImageDimensions('images_width', [
                    { key: 'width', style: 'width' },
                    { key: 'max_width', style: 'maxWidth' },
                    { key: 'min_width', style: 'minWidth' }
                ]);
                
                if( changed_keys.includes('settings') ){
                    editor.handleCommonSettings(model);
                }
                
                this.maybe_relayout_masonry();
            },
            handle_editor_resize(obj) {
                // trigger global resize event to make sure all components that need to adjust on editor resize can do it
                setTimeout(() => {
                    window.dispatchEvent(new Event('resize'));
                }, 250);
            },
            maybe_relayout_masonry() {
                const model = this.model;
                const galleryEl = model.getEl().querySelector('.theme-gallery');
                if(!galleryEl) return;
    
                // Get the Masonry instance via its element to re-layout after changes
                if(BUILDER_GLOBALS.masonry_is_active){
                    var msnry = Masonry.data(galleryEl);
                    if (msnry) msnry.layout();
                }
            }
        }
    });
}