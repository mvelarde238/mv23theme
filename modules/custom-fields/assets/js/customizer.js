(function(c) {
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

    const UF_Customize = UltimateFields.customize;
        body = document.querySelector('body'),
        root = document.querySelector(':root');

    function set_CSS_prop(prop, value){
        root.style.setProperty(prop, value);
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

    // TYPOGRAPHY
    ['paragraph', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'].forEach(element => {

        $is_heading = ( element.length === 2 );
        $option_name = ( $is_heading ) ? element+'_heading' : element;

        UF_Customize.bind( $option_name, ( values, context ) => {
            ['font-size','line-height','font-weight'].forEach(prop=>{
                $css_property_name = ( !$is_heading ) ? '--'+prop : '--'+element+'-'+prop;
                set_CSS_prop($css_property_name, values[prop.replace('-','_')]);
            });
        });        
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
    
})(console.log);