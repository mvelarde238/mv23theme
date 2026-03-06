window.gjsGallery = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'gallery';

    // add custom css to canvasCss
    let config = editor.getConfig();
    config.canvasCss = config.canvasCss || '';
    config.canvasCss += `.gallery .theme-gallery-comp {width: 100%;}`;
    config.canvasCss += `.gallery .theme-gallery__item-sizer {display: none;}`;
    editor.canvasCss = config.canvasCss;

    domc.addType(compClass, {
        extend: 'async-component-abstract',
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: 'Gallery',
                tagName: 'div',
                classes: [compClass, 'component'],
                __onSuccessCallback: (response, model, editor) => {
                    const el = model.getEl();
                    const temp = document.createElement('div');
                    temp.innerHTML = response.data;
                    const firstChild = temp.firstElementChild;
                    
                    if (firstChild) {
                        firstChild.removeAttribute('id');
                        el.innerHTML = temp.innerHTML;
                    }
                },
            },
        },
        view: {
            custom_datastore_change_callback(changed) {
                const model = this.model;
                
                // Ignore changes that only affect __tab (tab switching)
                const changed_keys = Object.keys(changed);
                if ( changed_keys[0] === '__tab') return;

                $rerender_gallery_on_change = [
                    'display', 'source', 'placeholders_quantity', 'gallery', 'wp_media_folder'
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
                    // marquee_settings: {speed: 40, fade_width: '200px', direction: 'left'}
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
            },
        },
    });
}