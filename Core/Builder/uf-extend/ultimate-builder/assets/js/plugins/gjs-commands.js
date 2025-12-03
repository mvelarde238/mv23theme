window.gjsCommands = function (editor, options) {

    const commands = editor.Commands;

    commands.add('builder:save-editor', (editor, sender, options) => {
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
            builder = options.builder;

        const values = builder.prepare_project_data(raw_project_data, temporalCompStore);

        uf_field_model.datastore.set(
            uf_field_model.get('name'),
            {
                builder_data: values.builder_data,
                components_data: values.components_data,
                css: editor.getCss()
            },
            { silent: false }
        );

        // Trigger .uf-form submit to save the post // dosn't work
        // document.querySelector('.uf-form').dispatchEvent(new Event('submit')); 

        // trigger click on .uf-form-footer button
        document.querySelector('.uf-form-footer [type="submit"]').click();
    });

    commands.add('builder:preview', (editor, sender, options = {}) => {
        const builder = options.builder;
        
        var payload = null;
        const raw_project_data = editor.getProjectData(),
            temporalCompStore = editor.getConfig().temporalCompStore || {},
            uf_field_model = editor.getConfig().uf_field_model,
            values = builder.prepare_project_data(raw_project_data, temporalCompStore);

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
        const raw_project_data = editor.getProjectData(),
            temporalCompStore = editor.getConfig().temporalCompStore || {},
            builder = options.builder;
        const values = builder.prepare_project_data(raw_project_data, temporalCompStore);
        console.log('raw_project_data', raw_project_data);
        console.log('temporalCompStore', temporalCompStore);
        console.log('editor update', values);
    });

    commands.add('update-font-size', (editor, sender, options = {}) => {
        let component = options.component,
            value = options.value;
        component.addStyle({
            'font-size': value+'px',
            'line-height': ( (value * 2) - (value / 2) )+'px'
        });
    });

    commands.add('update-text-align', (editor, sender, options = {}) => {
        let component = options.component,
            value = options.align;
        component.addStyle({
            'text-align': value
        });
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
        const rule = editor.Css.setRule(`#${id} ${selector}`, {}, ruleArgs);

        // Select the rule in the CSS editor
        editor.Selectors.select(rule);
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
}