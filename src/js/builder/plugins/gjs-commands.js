window.gjsCommands = function (editor, options) {

    const commands = editor.Commands;
    var __ = editor.createTranslator(editor, 'ultimate_builder');

    commands.add('builder:save-editor', (editor, sender, options) => {
        editor.runCommand('show-preloader', { text: 'Saving changes...' });
        editor.trigger('builder:before-save-editor');

        // get the button and set it to loading state
        const builderSaveButton = document.querySelector('.builder-save-button');
        if (builderSaveButton) {
            builderSaveButton.setAttribute('data-status', 'loading');
            builderSaveButton.disabled = true;
        }

        const raw_project_data = editor.getProjectData(),
            temporalCompStore = editor.getConfig().temporalCompStore || {},
            uf_field_model = editor.getConfig().uf_field_model,
            builder = editor.getConfig().builderInstance;

        const values = builder.prepare_project_data(raw_project_data, temporalCompStore, editor);

        uf_field_model.datastore.set(
            uf_field_model.get('name'),
            {
                builder_data: values.builder_data,
                components_data: values.components_data
            },
            { silent: false }
        );

        // Mark editor as saved so the beforeunload warning is cleared
        editor.clearDirtyCount();

        // Trigger .uf-form submit to save the post // dosn't work
        // document.querySelector('.uf-form').dispatchEvent(new Event('submit')); 

        // trigger click on .uf-form-footer button
        document.querySelector('.uf-form-footer [type="submit"]').click();
    });

    commands.add('builder:preview', (editor, sender, options = {}) => {
        const builder = editor.getConfig().builderInstance;
        
        var payload = null;
        const raw_project_data = editor.getProjectData(),
            temporalCompStore = editor.getConfig().temporalCompStore || {},
            uf_field_model = editor.getConfig().uf_field_model,
            values = builder.prepare_project_data(raw_project_data, temporalCompStore, editor);

        payload = {
            builder_data: values.builder_data,
            components_data: values.components_data,
            css: editor.getCss()
        };

        if (!payload) {
            alert('La app del builder debe exponer una función que devuelva el estado para preview.');
            return;
        }

        // get the button and set it to loading state
        const builderPreviewButton = document.querySelector('.builder-preview-button');
        if (builderPreviewButton) {
            builderPreviewButton.setAttribute('data-status', 'loading');
            builderPreviewButton.disabled = true;
        }

        jQuery.post(BUILDER_GLOBALS.ajax_url, {
            action: 'ultimate_builder_preview_save',
            nonce: BUILDER_GLOBALS.nonce,
            post_id: BUILDER_GLOBALS.post_id,
            meta: uf_field_model.get('name'),
            data: JSON.stringify(payload)
        }, function(resp){
            if (resp && resp.success && resp.data && resp.data.preview_url) {
                window.open(resp.data.preview_url, 'builder_preview');

                // reset the button state
                if (builderPreviewButton) {
                    builderPreviewButton.removeAttribute('data-status');
                    builderPreviewButton.disabled = false;
                }
            } else {
                console.error('Preview error response:', resp);
                alert('Error al generar la vista previa: ' + (resp?.data || 'Unknown error'));
            }
        }, 'json').fail(function(xhr, status, error){
            console.error('Preview AJAX error:', xhr.responseText, status, error);
            alert('Error de comunicación con el servidor: ' + error);
        });
    });

    commands.add('builder:log-data', (editor, sender, options) => {
        editor.trigger('builder:before-save-editor'); // for testing purposes

        const raw_project_data = editor.getProjectData(),
            temporalCompStore = editor.getConfig().temporalCompStore || {},
            builder = editor.getConfig().builderInstance;
        const values = builder.prepare_project_data(raw_project_data, temporalCompStore, editor);
        console.log('raw_project_data', raw_project_data);
        console.log('temporalCompStore', temporalCompStore);
        console.log('editor update', values);
    });

    commands.add('update-css-property', (editor, sender, options = {}) => {
        const { component, property, value, unit, isFinal } = options;
        let finalValue = value;
        if(unit){
            finalValue = value + unit;
        }

        // build style props object, and include additional properties if specified in options
        let styleProps = {
            [property]: finalValue
        };
        if(options.additionalProperties && options.additionalValues){
            options.additionalProperties.forEach((additionalProp, index) => {
                if( options.additionalValues[index] !== undefined ){
                    styleProps[additionalProp] = options.additionalValues[index];
                }
            });
        }

        // addStyleTargets() method adds support for updating styles on multiple selected components
        const styleManager = editor.StyleManager;
        styleManager.addStyleTargets(styleProps, { partial: !isFinal });
    });

    commands.add('query-selector', (editor, sender, options = {}) => {
        const id = editor.getSelected().getId(),
            selector = options.selector;
        
        // If the rule exists already, merge passed styles instead of replacing them.
        let ruleArgs = { addStyles: true };

        // Handle media queries for non-desktop devices
        const currentDevice = editor.Devices.getSelected();
        if (currentDevice && currentDevice.get('id') !== 'desktop') {
            ruleArgs.atRuleType = 'media';
            ruleArgs.atRuleParams = `(max-width: ${currentDevice.get('widthMedia')})`;
        }

        // Create or get the CSS rule
        // if selector has {context.id} placeholder, replace it with the actual id
        // and dont append the id to the selector, since it is already in the placeholder
        const processedSelector = selector.replace(/{context\.id}/g, `#${id}`);
        const finalSelector = processedSelector.includes(id) ? processedSelector : `#${id} ${processedSelector}`;
        const rule = editor.Css.setRule(finalSelector, {}, ruleArgs);

        // Select the rule in the CSS editor
        editor.Selectors.select(rule);

        // open style manager
        editor.Commands.run('select-styles-tab');
    });

    commands.add('exit-to-wp-admin', (editor, sender, options = {}) => {
        // check the referer first
        if( BUILDER_GLOBALS.referer ){
            window.location.href = BUILDER_GLOBALS.referer;
            return;
        }
        // else, go to the post type admin list
        window.location.href = BUILDER_GLOBALS.admin_url;
    });

    commands.add('set-section-layout', (editor, sender, options = {}) => {
        let component = options.component,
            layout = options.layout,
            datastore = editor.getComponentDatastore( component );
            
        if ( datastore ) {
            let current_settings = datastore.get('settings') || {};
            
            datastore.set('settings', {
                ...current_settings,
                layout: { use: true, key: layout }
            });

            component.view.render();
        }
    });

    commands.add('set-color-scheme', (editor, sender, options = {}) => {
        let component = options.component,
            scheme = options.scheme,
            datastore = editor.getComponentDatastore( component );
        if ( datastore ) {
            let current_settings = datastore.get('settings') || {};
            datastore.set('settings', {
                ...current_settings,
                color_scheme: { use: true, key: scheme }
            });
        }
    });

    commands.add('add-section', (editor, sender, options = {}) => {
        const component = options.component,
            position = options.position,
            parent = component.parent();
        
        if (position === 'above') {
            parent.append(
                { type: 'section' },
                { at: component.index() }
            );
        }
        if (position === 'below') {
            parent.append(
                { type: 'section' },
                { at: component.index() + 1 }
            );
        }
    });

    commands.add('open-datastore', (editor) => {
        editor.Commands.run('select-component-settings-tab');
    });

    commands.add('edit-theme-options', (editor) => {
        const wrapper = editor.getWrapper();
        const themeOptions = wrapper.findType('theme-options')[0];  
        if ( themeOptions ) {
            editor.select(themeOptions);
            editor.Commands.run('open-datastore');
        }
    });

    commands.add('edit-global-styles', (editor) => {
        const wrapper = editor.getWrapper();
        const globalStyles = wrapper.findType('global-styles')[0];
        if ( globalStyles ) {
            editor.select(globalStyles);
            editor.Commands.run('open-datastore');
        }
    });

    commands.add('locked-components-toggle', (editor, sender, options = {}) => {
        let component = options.component;

        let lockedComponents = component.get('lockedComponents');
        component.components().forEach(element => {
            element.set('locked',!lockedComponents);
        });
        component.set('lockedComponents', !lockedComponents);
    });

    // Flip Box specific commands
    commands.add('select-flipbox-side', (editor, sender, options = {}) => {
        const { component, side } = options;
        const sideComponent = component.findType(`flipbox-${side}`)[0];
        if (sideComponent) {
            editor.select(sideComponent);

            // update data-visible attribute on the main flipbox component
            component.addAttributes({ 'data-visible': side });
        }
    });

    // Carousel specific commands
    commands.add('remove-carousel-item', (editor, sender, options = {}) => {
        const { component } = options;
        if (component.getType() === 'carousel-item') {
            component.getView().removeItem();
        }
    });

    commands.add('carousel-locked-components-toggle', (editor, sender, options = {}) => {
        let component = options.component,
            componentType = component.get('type');

        if(componentType === 'carousel-item'){
            let lockedComponents = component.get('lockedComponents');
            component.components().forEach(element => {
                element.set('locked',!lockedComponents);
            });
            component.set('lockedComponents', !lockedComponents);
        }
    });

    commands.add('get-component-uid', (editor, sender, options = {}) => {
        const component = options.component;
        const componentsWithUID = ['listing'];

        if (componentsWithUID.includes(component.getType())) {
            const datastore = editor.getComponentDatastore(component);
            if (datastore) {
                const key = component.getType() === 'listing' ? 'listing' : 'component';

                const uid = datastore.get(`${key}_uid`);
                if (uid) {
                    alert(`${key.charAt(0).toUpperCase() + key.slice(1)} UID: ` + uid);
                } else if( component.getId() ){
                    alert(`${key.charAt(0).toUpperCase() + key.slice(1)} UID: ${key}_${component.getId()}`);
                } else {
                    alert(__('No UID found for this component.', 'no_uid'));
                }
            }
        }
    });

    commands.add('get-slider-uid', (editor, sender, options = {}) => {
        const component = options.component;
        const componentsWithSliderUID = ['carousel-wrapper', 'listing'];

        if (componentsWithSliderUID.includes(component.getType())) {
            const datastore = editor.getComponentDatastore(component);
            if (datastore) {
                let sliderUID = null;
                datastore.get('slider_settings').forEach(setting => {
                    const key = setting.property || setting.__type;
                    if (key === 'slider_uid') {
                        sliderUID = setting.value;
                    }
                });

                if (sliderUID) {
                    alert('Slider UID: ' + sliderUID);
                } else {
                    alert(__('No Slider UID found for this component.', 'no_slider_uid'));
                }
            }
        }
    });

    // Testimonial specific commands
    commands.add('update-testimonial-image-position', (editor, sender, options = {}) => {
        const { component, position } = options;
        if (component.getType() === 'testimonial-header') {

            const directionMap = {
                top: 'column',
                bottom: 'column-reverse',
                left: 'row',
                right: 'row-reverse'
            };

            component.addStyle({
                'flex-direction': directionMap[position] || 'row'
            });
        }
    });

    // Icon List specific commands
    commands.add('icon-list-actions', (editor, sender, options = {}) => {
        const { component, action } = options;
        if (component.getType() === 'icon-list') {
            if (action === 'add') {
                component.getView().addItem();
            } else if (action === 'remove') {
                component.getView().removeLastItem();
            } else if (action === 'toggle-multiple-edit') {
                component.getView().toggleMultipleEdit();
            }
        }
    });
};