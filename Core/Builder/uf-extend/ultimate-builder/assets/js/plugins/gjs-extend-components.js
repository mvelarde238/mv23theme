window.gjsExtendComponents = function (editor) {
    const domc = editor.DomComponents;
    
    // Extend gjs component connecting it with Ultimate Fields group model / datastores
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

                // generate a temporal id and assign it to gjs component and
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

                // Allow arguments to be modified before creating the model, view and etc.
                args = {
                    model: UltimateFields.Container.Group.Model,
                    datastore: datastore,
                    settings: groupData,
                    silent: false
                };

                UltimateFields.applyFilters('repeater_group_classes', args);

                // Prepare the group model
                let group_model = new args.model(_.extend({}, args.settings));
                group_model.set('__type', __type);
                group_model.setDatastore(datastore);
                
                // save the model
                editorConfig.temporalCompStore[generateId] = group_model;
            }
        }
    });

    /*
    * Remove __id on clone to avoid duplications
    * We use __tempID to identify components during the session
    * and connect them with their datastores
    * __id is generated when saving to database
    */
    editor.on('component:clone', (clonedComponent) => {
        // delete from cloned component
        delete clonedComponent.attributes.__id;

        // copy database from original component to the cloned one
        const editorConfig = editor.getConfig(),
            temporalCompStore = editorConfig.temporalCompStore || {},
            clonedComponentId = clonedComponent.attributes.__tempID,
            originalComponent = editor.getSelected(),
            originalComponentId = originalComponent.attributes.__tempID;

        if ( temporalCompStore[originalComponentId] ) {
            if( originalComponent.getType() === clonedComponent.getType() ) {
                const ogAttributes = temporalCompStore[originalComponentId].datastore.attributes;
                editorConfig.temporalCompStore[clonedComponentId].datastore.attributes = { ...ogAttributes };
            }
        }

        // remove __id from datastore attributes
        if ( temporalCompStore[clonedComponentId] ) {
            delete temporalCompStore[clonedComponentId].datastore.attributes.__id;
        }
    });

    // When a component is selected, check if it has a temporal UF model
    // and render its Group view inside #component-settings (sidenav)
    editor.on('component:selected', (component) => {
        try {
            const editorConfig = editor.getConfig();
            const compId = component.attributes && component.attributes.__tempID;
            const store = editorConfig.temporalCompStore || {};

            if (!compId || !store[compId]) return;

            // Cleanup previous active instance if different
            if (editorConfig.activeDatastore && editorConfig.activeDatastore.componentId !== compId) {
                const prev = editorConfig.activeDatastore;
                try {
                    if (prev.model && prev.handler) prev.model.datastore.off('change', prev.handler);
                } catch (e) {}
                try { if (prev.view && typeof prev.view.remove === 'function') prev.view.remove(); } catch (e) {}
                // clear wrapper
                try { window.jQuery && window.jQuery('#component-settings').empty(); } catch (e) {}
                editorConfig.activeDatastore = null;
            }

            // If already rendered for this component, do nothing
            if (editorConfig.activeDatastore && editorConfig.activeDatastore.componentId === compId) return;

            // Build view
            const builder_comp_model = store[compId];
            if (!builder_comp_model) return;

            // Use inline Group view but render only the canonical fields inside the sidenav
            // We call `addFields` to reuse the UF field creation / wrappers / layout logic.
            const GroupView = UltimateFields.Container.Group.View || UltimateFields.Container.Group.fullScreenView;
            const view = new GroupView({ model: builder_comp_model });

            // Attach fields to the wrapper using addFields(). Prefer jQuery wrapper.
            const $wrapper = window.jQuery ? window.jQuery('#component-settings') : null;
            if ($wrapper && $wrapper.length) {
                // Clear wrapper and ensure a uf-fields container for addFields
                $wrapper.empty();
                let $fieldsContainer = $wrapper.find('.uf-fields');
                if (!$fieldsContainer.length) {
                    $fieldsContainer = window.jQuery('<div class="uf-fields" />').appendTo($wrapper);
                } else {
                    $fieldsContainer.empty();
                }

                // Determine wrap based on layout
                // const wrap = UltimateFields.Field[ (view.model && view.model.get('layout') === 'grid') ? 'GridWrap' : 'Wrap' ];

                // Use the canonical addFields method to build field views
                try { 
                    view.addFields($fieldsContainer, { tabs: false, wrap: 'GridWrap' }); 
                } catch (e) {
                    // Fallback: render full view if addFields fails
                    try { view.render(); $wrapper.append(view.$el); } catch (er) { console.error(er); }
                }
            } 

            // Debounced change handler: 
            // ignore __tab-only changes because they don't affect data
            const changeHandler = _.debounce(function () {
                try {
                    const changed = builder_comp_model.datastore.changed || {};
                    const keys = Object.keys(changed);
                    if (keys.length === 1 && keys[0] === '__tab') return;

                    // Validate using field.validate() before propagating changes
                    const validation = validateDatastore(builder_comp_model);
                    if (!validation.valid) {
                        editor.trigger('datastoreInvalid', builder_comp_model, component, validation.errors);
                        return;
                    }

                    // Notify editor that datastore changed; allow other code to persist
                    editor.trigger('datastoreChanged', builder_comp_model, component);

                    // Re-render component view to reflect data changes
                    try { component.view && component.view.render && component.view.render(); } catch (e) {}
                } catch (e) {}
            }, 200);

            builder_comp_model.datastore.on('change', changeHandler);

            // Save active instance reference for concurrency/cleanup
            editorConfig.activeDatastore = {
                componentId: compId,
                view: view,
                handler: changeHandler,
                model: builder_comp_model,
                component: component
            };
        } catch (e) {
            console.error('Error rendering inline datastore:', e);
        }
    });

    // Cleanup when a component is deselected: remove inline view and listeners
    editor.on('component:deselected', (component) => {
        try {
            const editorConfig = editor.getConfig();
            const active = editorConfig.activeDatastore;
            if (!active) return;
            // If the deselected component matches the active one, cleanup
            if (component && active.componentId === component.attributes.__tempID) {
                try { if (active.model && active.handler) active.model.datastore.off('change', active.handler); } catch (e) {}
                try { if (active.view && typeof active.view.remove === 'function') active.view.remove(); } catch (e) {}
                try { window.jQuery && window.jQuery('#component-settings').empty(); } catch (e) {}
                editorConfig.activeDatastore = null;
            }
        } catch (e) {}
    });

    
    // Validation helper for inline Ultimate Fields datastores using field.validate()
    // Mirrors the approach used in customizer.Model.isValid(): iterate fields,
    // skip fields in hidden tabs and call field.validate(true) for silent validation.
    function validateDatastore(groupModel) {
        try {
            if (!groupModel) return { valid: true };

            const tabs = (groupModel.get && groupModel.get('tabs')) || {};
            const fields = (groupModel.get && groupModel.get('fields')) || null;
            const errors = {};

            if (fields && typeof fields.each === 'function') {
                fields.each(function (field) {
                    try {
                        // If the field's tab is set and that tab is hidden, skip
                        if (field.get('tab') && tabs && !tabs[field.get('tab')]) return;

                        // Silent validation (true -> silent) to get state without UI side-effects
                        var state = typeof field.validate === 'function' ? field.validate(true) : undefined;

                        if (typeof state !== 'undefined') {
                            var name = (field.get && field.get('name')) || field.cid || 'unknown';
                            errors[name] = state;
                            try { field.set('invalid', true); } catch (e) {}
                        } else {
                            try { field.set('invalid', false); } catch (e) {}
                        }
                    } catch (err) {
                        try { errors[field.get('name') || field.cid || 'unknown'] = err; } catch (e) { errors['unknown'] = err; }
                    }
                });
            }

            if (Object.keys(errors).length) return { valid: false, errors: errors };
            return { valid: true };
        } catch (err) {
            return { valid: false, errors: err };
        }
    }
}