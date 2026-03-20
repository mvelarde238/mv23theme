window.gjsListing = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'listing';

    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const compName = __('Listing');

    // add custom css to canvasCss
    let config = editor.getConfig();
    config.canvasCss = config.canvasCss || '';
    config.canvasCss += `.listing a { pointer-events: none; }.listing .postcard { flex-shrink: 0; }`;
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
                __onSuccessCallback: (response, model, editor) => {
                    const el = model.getEl();
                    // Create temporary container to parse response HTML
                    const temp = document.createElement('div');
                    temp.innerHTML = response.data;
                    const firstChild = temp.firstElementChild;
                    
                    // Remove class attribute from component to fix: settings dosnt apply on change datastore
                    if (firstChild) {
                        firstChild.removeAttribute('class');
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
                if (changed_keys.length && changed_keys[0] === '__tab') return;

                $rerender_listing_on_change = [
                    'source',
                    'posttype',
                    'tax_params',
                    'query_params',
                    'status_params',
                    'listing_template', 
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
                    const listingEl = model.getEl().querySelector('.posts-listing');
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
                    console.log('Settings changed, applying common settings...');
                    editor.handleCommonSettings(model);
                }
            },
        },
    });
}