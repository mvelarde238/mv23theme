function gjsExtendComponents(editor) {
    const domc = editor.DomComponents;
    
    // Extend existing component types adding groupData and datastore
    editor.on('component:create', (component) => {
        const editorConfig = editor.getConfig(), 
            type = component.get('type'),
            typesControl = editorConfig.typesControl || {};

        // if the component type is registered in typesControl
        if (type && typesControl[type]) {
            // find the group associated with this type
            const groups = editorConfig.groups || [],
                groupData = groups.find(g => g.id === typesControl[type].group);

            if (groupData) {
                const initial_components_data = editorConfig.initial_components_data,
                    uf_field_model = editorConfig.uf_field_model,
                    builderInstance = editorConfig.builderInstance;

                let component_data, __type, datastore;

                // generate a temporal id and assign it to component and
                // temporalCompStore to connect them during the save process
                const generateId = builderInstance.generateId();
                component.attributes.__tempID = generateId;
                editorConfig.temporalCompStore[generateId] = {};

                // find the corresponding component dataStore using the builder instance method
                component_data = builderInstance.findComponentById(initial_components_data, component.get('__id'));

                // configure the data store
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

                // save the datastore
                editorConfig.temporalCompStore[generateId].datastore = datastore;

                // Allow arguments to be modified before creating the model, view and etc.
                args = {
                    model: UltimateFields.Container.Group.Model,
                    datastore: datastore,
                    settings: groupData,
                    silent: false
                };

                UltimateFields.applyFilters('repeater_group_classes', args);

                // Prepare the model for the pop up
                builder_comp_model = new args.model(_.extend({}, args.settings));
                builder_comp_model.set('__type', __type);
                builder_comp_model.setDatastore(datastore);
                
                // save the model
                editorConfig.temporalCompStore[generateId].builder_comp_model = builder_comp_model;

                // trigger update on editor when the model is saved
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
            component.get('__tempID') &&
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
            const editorConfig = editor.getConfig(),
                temporalCompStore = editorConfig.temporalCompStore || {},
                componentId = selectedComponent.attributes.__tempID;

            if ( temporalCompStore[componentId] ) {
                let builder_comp_model = temporalCompStore[componentId].builder_comp_model;

                // Save the state of the datastore
                builder_comp_model.backupState();

                let view = new UltimateFields.Container.Group.fullScreenView({
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