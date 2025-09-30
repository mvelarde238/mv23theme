function gjsBuilderCommands(editor, options) {

    const commands = editor.Commands;

    commands.add('builder:save-editor', (editor, sender, options) => {
        const raw_project_data = editor.getProjectData(),
            temporalCompStore = editor.getConfig().temporalCompStore || {},
            uf_field_model = editor.getConfig().uf_field_model,
            builder = options.builder;

        const values = builder.prepare_project_data(raw_project_data, temporalCompStore);
        console.log('raw_project_data', raw_project_data);
        console.log('temporalCompStore', temporalCompStore);
        console.log('editor update', values);

        uf_field_model.datastore.set(
            uf_field_model.get('name'),
            {
                builder_data: values.builder_data,
                components_data: values.components_data,
                css: editor.getCss()
            },
            { silent: false }
        );

        // Trigger .uf-form submit to save the post
        // document.querySelector('.uf-form').dispatchEvent(new Event('submit')); // dosn't work

        // trigger click on .uf-form-footer button
        document.querySelector('.uf-form-footer [type="submit"]').click();
    });
}

window.gjsBuilderCommands = gjsBuilderCommands;