(function(c) {
    const UF_Customize = UltimateFields.customize;
        body = document.querySelector('body'),
        html = document.querySelector('html'),
        root = document.querySelector(':root');

    function hexToRgba(hex, alpha) {
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
    }

    function set_CSS_prop(prop, value){
        root.style.setProperty(prop, value);
    }

    function addOrUpdateStyle(cssContent) {
        // Revisa si ya existe una etiqueta <style> con un ID específico
        let styleElement = document.getElementById('dynamic-css');
    
        // Si no existe, la creamos
        if (!styleElement) {
            styleElement = document.createElement('style');
            styleElement.id = 'dynamic-css'; // Asignamos un ID para que sea fácil encontrarla
            document.head.appendChild(styleElement); // Añadimos la etiqueta al head
        }
    
        // Sobrescribe el contenido del estilo con el nuevo cssContent
        styleElement.innerHTML = cssContent;
    }

    //  COLORS
    UF_Customize.bind( 'theme_colors', ( theme_colors, context ) => {
        if (!Array.isArray(theme_colors)) return;

        theme_colors.forEach(color_item => {
            // Process color type items
            if (color_item.__type === 'color') {
                if (color_item.color && color_item.css_property) {
                    set_CSS_prop(color_item.css_property, color_item.color);

                    // Generate variations if enabled
                    if (color_item.customize_variations) {
                        const base_var = color_item.css_property;
                        
                        // Generate light variation
                        const light_value = color_item.light || 70;
                        set_CSS_prop(
                            base_var + '-light',
                            `color-mix(in srgb, var(${base_var}), white ${light_value}%)`
                        );
                        
                        // Generate lighter variation
                        const lighter_value = color_item.lighter || 94;
                        set_CSS_prop(
                            base_var + '-lighter',
                            `color-mix(in srgb, var(${base_var}), white ${lighter_value}%)`
                        );
                        
                        // Generate dark variation
                        const dark_value = color_item.dark || 15;
                        set_CSS_prop(
                            base_var + '-dark',
                            `color-mix(in srgb, var(${base_var}), black ${dark_value}%)`
                        );
                    }
                }
            }
        });
    });

    ['typography','headings', 'links'].forEach( key => { 
        UF_Customize.bind( key+'_settings', ( properties, context ) => {
            for (const key in properties) {
                let value = properties[key];

                if( key.startsWith('--') ){
                    set_CSS_prop(key,value);
                } else {
                    if( key.startsWith('heading') ){
                        // is headings complex
                        let heading_complex = value;
                        for (const _key in heading_complex) {
                            let _value = heading_complex[_key];
                            if( _key.startsWith('--') ){
                                set_CSS_prop(_key,_value);
                            }
                        }
                    } else {
                        // is base font size
                        html.style.setProperty('font-size', value);
                    }
                }
            }
        });
    });

    //  CONTAINER
    UF_Customize.bind( 'containers_settings', ( values, context ) => {
        let cssRules = '';

        values.forEach(item => {
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

        if(cssRules) addOrUpdateStyle(cssRules);
    });
    
})(console.log);