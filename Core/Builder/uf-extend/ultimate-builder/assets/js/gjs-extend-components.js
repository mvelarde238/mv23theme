function gjsExtendComponents(editor) {
    const domc = editor.DomComponents;
    const componentTypes = editor.getConfig().componentTypes || {};

    // Extend existing component types adding groupData and datastore
    editor.on('component:create', (component) => {
        const type = component.get('type');

        if (type && componentTypes[type]) {
            const groups = editor.getConfig().groups || [],
                groupData = groups.find(g => g.id === componentTypes[type].group);

            if (groupData) {
                component.set('groupData', groupData);
                // component.set('name', groupData.title);

                const components_data = editor.getConfig().components_data,
                    uf_field_model = editor.getConfig().uf_field_model,
                    builderInstance = editor.getConfig().builderInstance;

                let component_data, __type, datastore;

                // find the corresponding component data using the builder instance method
                component_data = builderInstance.findComponentById(components_data, component.get('__id'));

                if (component_data) {
                    // this component is loading from database
                    __type = component_data.__type;
                    datastore = new UltimateFields.Datastore(component_data);
                } else {
                    // is a new component
                    __type = groupData?.id;
                    datastore = new UltimateFields.Datastore({});
                    datastore.parent = uf_field_model.datastore;
                }

                datastore.set('__type', __type);
                component.set('datastore', datastore);

                // Allow arguments to be modified before creating the model, view and etc.
                args = {
                    model: UltimateFields.Container.Group.Model,
                    datastore: datastore,
                    silent: false
                };

                UltimateFields.applyFilters('builder_component_classes', args);

                // Prepare the model for the pop up
                builder_comp_model = new args.model(_.extend({}, groupData));
                builder_comp_model.set('__type', __type);
                builder_comp_model.setDatastore(datastore);
                component.set('builder_comp_model', builder_comp_model);

                builder_comp_model.on('stateSaved', function () {
                    editor.trigger('update');
                });
            }
        }
    });

    // Add toolbar button to open data store
    editor.on('component:selected', (component) => {
        const toolbar = component.get('toolbar') || [];

        const dataStoreButton = {
            id: 'data-store',
            className: 'bi bi-database',
            command: 'open-datastore',
            attributes: { title: 'Open Data Store' }
        };

        if (
            componentTypes[component.get('type')] &&
            component.get('groupData') &&
            !toolbar.find(item => item.id === 'data-store')
        ) {
            component.set('toolbar', [...toolbar, dataStoreButton]);
        }
    });

    // Command to open the data store
    const commands = editor.Commands;
    commands.add('open-datastore', (editor) => {
        const selectedComponent = editor.getSelected();
        if (selectedComponent) {
            const dataStore = selectedComponent.get('datastore');
            if (dataStore) {
                var builder_comp_model = selectedComponent.get('builder_comp_model'),
                    view;

                // Save the state of the datastore
                builder_comp_model.backupState();

                view = new UltimateFields.Container.Group.fullScreenView({
                    model: builder_comp_model
                });

                UltimateFields.Overlay.show({
                    view: view,
                    title: builder_comp_model.datastore.get('title') || builder_comp_model.get('title'),
                    buttons: view.getbuttons()
                });
            }
        }
    });
}

window.gjsExtendComponents = gjsExtendComponents;