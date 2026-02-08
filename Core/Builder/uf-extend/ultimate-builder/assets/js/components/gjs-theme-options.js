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
        layerable: false
    };

    domc.addType(compClass, {
        model: {
            defaults: Object.assign({}, notSelectableComponent, {
                name: 'Theme Options',
                tagName: 'div',
                selectable: true
            }),
        },
        view: {
            custom_datastore_change_callback(changed) {
                // Ignore changes that only affect __tab (tab switching)
                const keys = Object.keys(changed);
                if (keys.length && keys[0] === '__tab') return;

                // Handle datastore data changes
                if( 
                    changed.main_logo_prepared || 
                    changed.secondary_logo_prepared ||
                    changed.static_header_logo_wrapper ||
                    changed.sticky_header_logo_wrapper ||
                    changed.static_header_color_scheme ||
                    changed.sticky_header_color_scheme
                ) {
                    this.applyChangesOnHeader(changed);
                }
                if ( changed.static_header_logo_height || changed.sticky_header_logo_height ) {
                    let key = changed.static_header_logo_height ? 'static_header_logo_height' : 'sticky_header_logo_height';
                    this.set_CSS_prop('--' + key.replace(/_/g, '-'), changed[key] + 'px');
                }
                if ( changed.static_header_bgc || changed.sticky_header_bgc ) {
                    let color = '';
                    let css_property = changed.static_header_bgc ? '--static-header-color' : '--sticky-header-color';
                    let values = changed.static_header_bgc ? changed.static_header_bgc : changed.sticky_header_bgc;
                    if( values.add_bgc ) color = ( values.alpha != '100' ) ? this.hexToRgba(values.bgc, values.alpha) : values.bgc;
                    this.set_CSS_prop(css_property, color);
                }
                if ( changed.theme_colors ) {
                    this.applyThemeColors( changed.theme_colors );
                }
                if ( changed.fonts) {
                    this.handleFontsChange( changed.fonts );
                }
                if ( changed.typography_css_vars) {
                    this.applyTypographyCSSVars( changed.typography_css_vars );
                }
                if ( changed.containers_width ) {
                    this.handleContainersWidthChange( changed.containers_width );
                }
            },
            applyChangesOnHeader(changed){
                // look for header component and rerender header component
                const headerComp = editor.getWrapper().findType('header')[0];
                if ( headerComp ) {
                    const model = this.model;

                    const datastore = editor.getComponentDatastore(model);
                    if (datastore) {
                        const data = datastore.toJSON();

                        // TODO: apply these filters on preview page as well
                        headerComp.set('apply_filters', true);
                        headerComp.set('filters_to_apply', [
                            {
                                'name': 'pre_option_main_logo',
                                'value': data.main_logo
                            }, 
                            {
                                'name': 'pre_option_secondary_logo',
                                'value': data.secondary_logo
                            },
                            {
                                'name': 'pre_option_static_header_logo',
                                'value': data.static_header_logo_wrapper.static_header_logo
                            },
                            {
                                'name': 'pre_option_custom_static_header_logo',
                                'value': data.static_header_logo_wrapper.custom_static_header_logo
                            },
                            {
                                'name': 'pre_option_static_header_color_scheme',
                                'value': data.static_header_color_scheme
                            },
                            {
                                'name': 'pre_option_sticky_header_logo',
                                'value': data.sticky_header_logo_wrapper.sticky_header_logo
                            },
                            {
                                'name': 'pre_option_custom_sticky_header_logo',
                                'value': data.sticky_header_logo_wrapper.custom_sticky_header_logo
                            },
                            {
                                'name': 'pre_option_sticky_header_color_scheme',
                                'value': data.sticky_header_color_scheme
                            }
                        ]);
                        headerComp.view.render();
                    }
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
                let cssRules = '';

                fonts.forEach(font_group => {
                    if( font_group.__type === 'google_font' ){
                        let value = font_group.google_font;
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
                        // TODO: handle custom font files loading
                    }
                });

                if(cssRules) this.addOrUpdateStyle(cssRules, 'fonts-style');
            },
            applyTypographyCSSVars(properties){
                for (const key in properties) {
                    let value = properties[key];

                    if( key.startsWith('--') ){
                        this.set_CSS_prop(key,value);
                    } else {
                        if( key.startsWith('heading') ){
                            // is headings complex
                            let heading_complex = value;
                            for (const _key in heading_complex) {
                                let _value = heading_complex[_key];
                                if( _key.startsWith('--') ){
                                    this.set_CSS_prop(_key,_value);
                                }
                            }
                        } else {
                            // is base font size
                            const canvas = editor.Canvas,
                                _document = canvas.getDocument();
                            _document.querySelector('html').style.setProperty('font-size', value);
                        }
                    }
                }
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
            handleContainersWidthChange(containers_width){
                let cssRules = '';

                containers_width.forEach(item => {
                    let width = item.width;
                    if( width ){
                        if( item.scope == 'global' ){
                            cssRules += ':root{--container-width:'+width+'px;}';
                        } else if( item.scope == 'custom' && item.selector ) {
                            cssRules += item.selector+'{--container-width:'+width+'px;}';
                        } else {
                            cssRules += '.'+item.scope+'{--container-width:'+width+'px;}';
                        }
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
                    _document.head.appendChild(styleElement); // Añadimos la etiqueta al head
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
            wrapper.append({type: 'theme-options'}, {at: 0});
        // }else {
            // console.warn('Theme Options component already exists in the wrapper. Skipping append.');
        }
    });
}