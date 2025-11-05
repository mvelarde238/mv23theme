window.gjsCommands = function (editor, options) {

    const commands = editor.Commands;

    commands.add('builder:save-editor', (editor, sender, options) => {
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
}