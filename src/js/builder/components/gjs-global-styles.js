window.gjsGlobalStyles = function (editor, options) {
    const domc = editor.DomComponents;
    const compClass = 'global-styles';

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

    const breakpoints = {
        'desktop': '',
        'tablet': '992',
        'mobileLandscape': '768',
        'mobilePortrait': '480'
    };

    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const compName = __('Global Styles');

    domc.addType(compClass, {
        model: {
            defaults: Object.assign({}, notSelectableComponent, {
                name: compName,
                tagName: 'div',
                selectable: true
            }),
        },
        view: {
            init(){
                editor.on('change:device', () => {
                    const model = this.model;
                    const datastore = editor.getComponentDatastore(model);
                    const deviceManager = editor.Devices;
                    const selected = deviceManager.getSelected();
                    const device = selected ? selected.get('id') : 'desktop';

                    // update the datastore to show the correct values for the current device
                    datastore.set({ device_switch: device }, {silent: true});

                    // also update the field in the model so it reflects the current device
                    const builder_comp_model = editor.getBuilderCompModel(model);
                    editor.updateBuilderCompModelField(builder_comp_model, 'device_switch');
                });       
            },
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

                if ( changed.device_switch ) {
                    const device = changed.device_switch;
                    editor.setDevice( device || 'desktop' );
                }

                const typography_keys = ['typography_settings','headings_settings','links_settings','custom_css_settings'];
                if ( keys.some( key => typography_keys.includes(key) ) ) {
                    typography_keys.forEach( key => {
                        if ( changed[key] ) {
                            this.applyTypographyCSSVars( changed[key] );
                        }
                    });
                }

                const breakpoints_ids = ['tablet', 'mobileLandscape', 'mobilePortrait'];
                const types = ['typography', 'headings', 'links', 'custom_css'];
                const bp_keys = breakpoints_ids.flatMap( bp => types.map( t => `_breakpoint_${bp}_${t}_settings` ) );
                if ( keys.some( key => bp_keys.includes(key) ) ) {
                    bp_keys.forEach( key => {
                        if ( key in changed ) {
                            const bp_id = key.match(/_breakpoint_([^_]+)_/)[1];
                            const bp = breakpoints_ids.includes(bp_id) ? bp_id : null;
                            if ( bp ) {
                                this.applyBreakpointCSSVars( key, bp, changed[key] || {} );
                            }
                        }
                    });
                }
            },
            set_CSS_prop(prop, value){
                const canvas = editor.Canvas,
                    _document = canvas.getDocument(),
                    root = _document.querySelector(':root');
                if ( value ) {
                    root.style.setProperty(prop, value);
                } else {
                    root.style.removeProperty(prop);
                }
            },
            set_base_font_size(value){
                // Use a dedicated <style> tag so we always override the saved
                // stylesheet (which keeps the last persisted value). When the
                // field is cleared we fall back to 16px — matching what the
                // frontend will render after saving with an empty value.
                const _document = editor.Canvas.getDocument();
                const styleId = 'uf-base-font-size';
                let styleEl = _document.getElementById(styleId);
                if ( !styleEl ) {
                    styleEl = _document.createElement('style');
                    styleEl.id = styleId;
                    _document.body.appendChild(styleEl);
                }
                styleEl.textContent = `html{font-size:${value || '16px'}}`;
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
                        } else if( key === 'base_font_size' ){
                            // is base font size — use a <style> tag so it always
                            // overrides the saved stylesheet. When cleared, reset
                            // to 16px (browser default = what the frontend will show).
                            this.set_base_font_size(value);
                        
                        } else if ( key === 'custom_global_css' ) {
                            this.inject_custom_global_css(value);
                        }
                    }
                }
            },
            inject_custom_global_css(css) {
                const _document = editor.Canvas.getDocument();
                const styleId = 'uf-custom-global-css';
                let styleEl = _document.getElementById(styleId);
                if ( !styleEl ) {
                    styleEl = _document.createElement('style');
                    styleEl.id = styleId;
                    _document.body.appendChild(styleEl);
                }
                styleEl.textContent = css || '';
            },
            applyBreakpointCSSVars(styleKey, bp, properties) {
                const bp_px = breakpoints[bp];
                if ( !bp_px ) return;

                const _document = editor.Canvas.getDocument();
                let root_lines = [];
                let html_css = '';

                for (const key in properties) {
                    const value = properties[key];

                    if ( key.startsWith('--') ) {
                        if ( value ) root_lines.push(`${key}:${value}`);
                    } else if ( key.startsWith('heading') ) {
                        for (const _key in value) {
                            if ( _key.startsWith('--') && value[_key] ) {
                                root_lines.push(`${_key}:${value[_key]}`);
                            }
                        }
                    } else if ( key === 'base_font_size' && value ) {
                        html_css = `html{font-size:${value}}`;
                    }
                }

                let inner = html_css;
                if ( root_lines.length ) inner += `:root{${root_lines.join(';')}}`;

                const styleId = `uf-bp-${styleKey}`;
                let styleEl = _document.getElementById(styleId);
                if ( !styleEl ) {
                    styleEl = _document.createElement('style');
                    styleEl.id = styleId;
                    _document.body.appendChild(styleEl);
                }
                styleEl.textContent = inner ? `@media(max-width:${bp_px}px){${inner}}` : '';
            },
        }
    });

    // On builder loaded, append to wrapper component
    editor.on('builder:loaded', () => {
        const wrapper = editor.getWrapper();
        const globalStyles_exists = wrapper.findType('global-styles')[0];
        if ( !globalStyles_exists ) {
            wrapper.append({type: 'global-styles'});
        }
    });
};