window.gjsThemeOptions = function (editor, options) {
    const domc = editor.DomComponents;
    const compClass = 'theme-options';

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
        savable: false,
    };

    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const compName = __('Theme Options');

    domc.addType(compClass, {
        model: {
            defaults: Object.assign({}, notSelectableComponent, {
                name: compName,
                tagName: 'div',
                selectable: true
            }),
        },
        view: {
            onRender({el, model}){
                // Add theme options stuff after a short delay to ensure canvas is ready
                setTimeout(() => {
                    const datastore = editor.getComponentDatastore(model);
                    const data = datastore.toJSON();
                    this.custom_datastore_change_callback(data);
                }, 500);
            },
            custom_datastore_change_callback(changed) {
                // Ignore changes that only affect __tab (tab switching)
                const keys = Object.keys(changed);
                if (keys.length && keys[0] === '__tab') return;

                // When changed is empty (e.g. repeater item deletion), read full state from datastore
                if (keys.length === 0) {
                    const datastore = editor.getComponentDatastore(this.model);
                    if (datastore) {
                        const fullData = datastore.toJSON();
                        if (fullData.fonts !== undefined) this.handleFontsChange(fullData.fonts);
                        if (fullData.theme_colors) this.applyThemeColors(fullData.theme_colors);
                        if (fullData.containers_settings) this.handleContainersWidthChange(fullData.containers_settings);
                    }
                    return;
                }

                if ( changed.theme_colors ) {
                    this.applyThemeColors( changed.theme_colors );
                }
                if ( changed.fonts !== undefined ) {
                    this.handleFontsChange( changed.fonts );
                }
                if ( changed.containers_settings ) {
                    this.handleContainersWidthChange( changed.containers_settings );
                }
            },
            set_CSS_prop(prop, value){
                const canvas = editor.Canvas,
                    _document = canvas.getDocument(),
                    root = _document.querySelector(':root');
                root.style.setProperty(prop, value);
            },
            applyThemeColors(theme_colors){
                if (!Array.isArray(theme_colors)) return;

                // Update global BUILDER_GLOBALS.theme_colors
                BUILDER_GLOBALS.theme_colors = theme_colors;

                // Trigger event to update CSS variables and datalist
                editor.trigger('theme-colors:update');

                theme_colors.forEach(color_item => {
                    // Process color type items
                    if (color_item.__type === 'color') {
                        if (color_item.color && color_item.css_property) {
                            this.set_CSS_prop(color_item.css_property, color_item.color);

                            // Generate variations if enabled
                            if (color_item.customize_variations) {
                                const base_var = color_item.css_property;
                                
                                // Generate light variation
                                const light_value = color_item.light || 70;
                                this.set_CSS_prop(
                                    base_var + '-light',
                                    `color-mix(in srgb, var(${base_var}), white ${light_value}%)`
                                );
                                
                                // Generate lighter variation
                                const lighter_value = color_item.lighter || 94;
                                this.set_CSS_prop(
                                    base_var + '-lighter',
                                    `color-mix(in srgb, var(${base_var}), white ${lighter_value}%)`
                                );
                                
                                // Generate dark variation
                                const dark_value = color_item.dark || 15;
                                this.set_CSS_prop(
                                    base_var + '-dark',
                                    `color-mix(in srgb, var(${base_var}), black ${dark_value}%)`
                                );
                            }
                        }
                    }
                });
            },
            handleFontsChange(fonts){
                if (!Array.isArray(fonts)) fonts = Object.values(fonts || {});
                let cssRules = '';
                const fileFontPromises = [];

                fonts.forEach(font_group => {
                    if( font_group.__type === 'google_font' ){
                        let value = font_group.google_font;
                        if( !value || typeof value !== 'object' || !value.family ) return;
                        var url = value.family.replace( /\s/g, '+' ) + ':' + value.variants.join( ',' );
                        const canvas = editor.Canvas,
                            _document = canvas.getDocument();
                        
                        const fontUrl = 'https://fonts.googleapis.com/css?family=' + url;
                        
                        // Verificar si ya existe un link con el mismo href
                        const existingLink = _document.querySelector(`link[href="${fontUrl}"]`);
                        if (!existingLink) {
                            let linkElement = _document.createElement('link');
                            linkElement.href = fontUrl;
                            linkElement.rel = 'stylesheet';
                            _document.head.appendChild(linkElement);
                        }

                        const scope = font_group.scope;
                        if( scope != 'any' ){
                            let scopes = {
                                'global': 'body',
                                'headings': 'h1, h2, h3, h4, h5, h6'
                            }
                            // scope can be custom for custom selector
                            if (scope == 'custom' && font_group.selector ) {
                                scopes['custom'] = font_group.selector;
                            }
                            const selector = scopes[scope];
                            cssRules += selector + '{ font-family: "' + value.family + '", Sans-Serif; }';
                        }
                    }
                    if( font_group.__type === 'custom_font' ){
                        const name = font_group.name;
                        const variant = font_group.variant || 'normal';
                        const type = font_group.type || 'file';
                        const scope = font_group.scope;

                        const buildFontRules = (fontUrls) => {
                            if (!fontUrls.length) return '';
                            let rules = `@font-face{font-family:"${name}";font-weight:${variant};src:${fontUrls.join(',')};} `;
                            if (scope !== 'any') {
                                const scopes = { global: 'body', headings: 'h1,h2,h3,h4,h5,h6' };
                                if (scope === 'custom' && font_group.selector) scopes['custom'] = font_group.selector;
                                const selector = scopes[scope];
                                if (selector) rules += `${selector}{font-family:"${name}",Sans-Serif;} `;
                            }
                            return rules;
                        };

                        if (type === 'url' && Array.isArray(font_group.urls) && font_group.urls.length) {
                            const fontUrls = font_group.urls.filter(item => item.url).map(item => `url(${item.url})`);
                            cssRules += buildFontRules(fontUrls);
                        } else if (type === 'file' && Array.isArray(font_group.files) && font_group.files.length) {
                            fileFontPromises.push(
                                Promise.all(font_group.files.map(id => editor.getPreparedFileObjectAsync(id)))
                                    .then(attachments => {
                                        const fontUrls = attachments
                                            .filter(a => a && a.get && a.get('url'))
                                            .map(a => `url(${a.get('url')})`);
                                        return buildFontRules(fontUrls);
                                    })
                            );
                        }
                    }
                });

                // Always overwrite to reflect deletions
                this.addOrUpdateStyle(cssRules, 'fonts-style');

                // Rebuild file-fonts style from scratch to reflect deletions
                const self = this;
                Promise.all(fileFontPromises).then(rulesArray => {
                    self.addOrUpdateStyle(rulesArray.filter(Boolean).join(''), 'custom-file-fonts-style');
                });
            },
            hexToRgba(hex, alpha) {
                // Remover el símbolo '#' si está presente
                hex = hex.replace(/^#/, '');

                // Si el valor hexadecimal es de 3 dígitos, convertirlo a 6 dígitos
                if (hex.length === 3) {
                    hex = hex.split('').map(char => char + char).join('');
                }
            
                // Convertir los valores hexadecimales a RGB
                const bigint = parseInt(hex, 16);
                const r = (bigint >> 16) & 255;
                const g = (bigint >> 8) & 255;
                const b = bigint & 255;
            
                // Convertir el valor de alpha de 0-100 a 0-1
                const a = alpha / 100;
            
                // Retornar el valor en formato rgba
                return `rgba(${r}, ${g}, ${b}, ${a})`;
            },
            handleContainersWidthChange(containers_settings){
                let cssRules = '';

                containers_settings.forEach(item => {
                    let width = item.width;                
                    let max_width = item.max_width;

                    if( item.scope == 'global' ){
                        if( width ) cssRules += ':root{--container-width:'+width+'%;}';
                        if( max_width ) cssRules += ':root{--container-max-width:'+max_width+'px;}';
                    } else if( item.scope == 'custom' && item.selector ) {
                        if( width ) cssRules += item.selector+'{--container-width:'+width+'%;}';
                        if( max_width ) cssRules += item.selector+'{--container-max-width:'+max_width+'px;}';
                    } else {
                        if( width ) cssRules += '.'+item.scope+'{--container-width:'+width+'%;}';
                        if( max_width ) cssRules += '.'+item.scope+'{--container-max-width:'+max_width+'px;}';
                    }
                });
            
                if(cssRules) this.addOrUpdateStyle(cssRules, 'containers-width-style');
            },
            addOrUpdateStyle(cssContent, key) {
                // Revisa si ya existe una etiqueta <style> con un ID específico
                const canvas = editor.Canvas,
                    _document = canvas.getDocument();
                let styleElement = _document.getElementById(key);

                // Si no existe, la creamos
                if (!styleElement) {
                    styleElement = _document.createElement('style');
                    styleElement.id = key; // Asignamos un ID para que sea fácil encontrarla
                    _document.body.appendChild(styleElement); // Añadimos la etiqueta al body
                }
            
                // Sobrescribe el contenido del estilo con el nuevo cssContent
                styleElement.innerHTML = cssContent;
            }
        }
    });

    // On builder loaded, append to wrapper component
    editor.on('builder:loaded', () => {
        const wrapper = editor.getWrapper();
        const themeOptions_exists = wrapper.findType('theme-options')[0];
        if ( !themeOptions_exists ) {
            wrapper.append({type: 'theme-options'});
        }
    });
};