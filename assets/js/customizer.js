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
    UF_Customize.bind( 'colors_wrapper', ( values, context ) => {
        set_CSS_prop('--primary-color', values.primary_color);
        set_CSS_prop('--secondary-color', values.secondary_color);
        set_CSS_prop('--font-color', values.font_color);
        set_CSS_prop('--headings-color', values.headings_color);
    });
    
    UF_Customize.bind( 'primary_color_variations', ( values, context ) => {
        set_CSS_prop('--primary-color-light', 'color-mix( in srgb, var(--primary-color), white '+values.light_primary_color_percentage+'%');
        set_CSS_prop('--primary-color-lighter', 'color-mix( in srgb, var(--primary-color), white '+values.lighter_primary_color_percentage+'%');
        set_CSS_prop('--primary-color-dark', 'color-mix( in srgb, var(--primary-color), black '+values.dark_primary_color_percentage+'%');
    });

    UF_Customize.bind( 'secondary_color_variations', ( values, context ) => {
        set_CSS_prop('--secondary-color-light', 'color-mix( in srgb, var(--secondary-color), white '+values.light_secondary_color_percentage+'%');
        set_CSS_prop('--secondary-color-lighter', 'color-mix( in srgb, var(--secondary-color), white '+values.lighter_secondary_color_percentage+'%');
        set_CSS_prop('--secondary-color-dark', 'color-mix( in srgb, var(--secondary-color), black '+values.dark_secondary_color_percentage+'%');
    });

    UF_Customize.bind( 'typography_css_vars', ( properties, context ) => {
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
                } else if( key == 'columns-gap' ) {
                    // is columns gap complex
                    let columns_gap = value;
                    for (const _key in columns_gap) {
                        let _value = columns_gap[_key];
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

    // HEADER
    ['static','sticky'].forEach( key => { 
        UF_Customize.bind( key+'_header_bgc', ( values, context ) => {
            let color = '';
            let css_property = '--'+key+'-header-color';
            if( values.add_bgc ) color = ( values.alpha != '100' ) ? hexToRgba(values.bgc, values.alpha) : values.bgc;
            set_CSS_prop(css_property, color);
        });

        UF_Customize.bind( key+'_header_logo_height', ( value, context ) => {
            let css_property = '--'+key+'-header-logo-height';
            set_CSS_prop(css_property, value+'px');
        });
        
        // wp.customize.preview.send('refresh');
    });

    //  CONTAINER
    UF_Customize.bind( 'containers_width', ( values, context ) => {
        let cssRules = '';

        values.forEach(item => {
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

        if(cssRules) addOrUpdateStyle(cssRules);
    });
    
})(console.log);