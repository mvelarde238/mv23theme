window.gjsExtendComponents = function (editor) {
    const domc = editor.DomComponents;

    // =====================================================================
    // VIEW CACHE: Stores rendered GroupViews per compId so they are created
    // only once and re-attached on subsequent selects instead of recreated.
    // Each entry: { view, handler, model, component }
    // =====================================================================
    const viewCache = {};

    /**
     * Invalidate (destroy) a cached view for a given compId.
     * Cleans up the change handler, removes the DOM, and deletes the entry.
     */
    function invalidateCache(compId) {
        const cached = viewCache[compId];
        if (!cached) return;
        try { if (cached.model && cached.handler) cached.model.datastore.off('change', cached.handler); } catch (e) {}
        try { if (cached.view && cached.view.$el) cached.view.$el.remove(); } catch (e) {}
        delete viewCache[compId];
        // console.log('[viewCache] Invalidated cache for', compId);
    }
    
    // Extend gjs component connecting it with Ultimate Fields group model / datastores
    editor.on('component:create', (gjs_component) => {
        const editorConfig = editor.getConfig(), 
            type = gjs_component.get('type');

        // find the group associated with this type
        const groups = editorConfig.groups || [],
            groupData = groups.find(g => g.id === type);

        if (groupData) {
            const initial_components_data = editorConfig.initial_components_data,
                uf_field_model = editorConfig.uf_field_model,
                builderInstance = editorConfig.builderInstance;

            let component_data, __type, datastore;

            // generate a temporal id and assign it to gjs component and
            // temporalCompStore to connect them during the save process
            const generatedId = builderInstance.generateId();
            gjs_component.attributes.__tempID = generatedId;
            editorConfig.temporalCompStore[generatedId] = {};

            // find the corresponding component dataStore using the builder instance method
            component_data = builderInstance.findComponentById(initial_components_data, gjs_component.get('__id'));

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
            editorConfig.temporalCompStore[generatedId] = group_model;
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

    // Invalidate cache when a component is removed from the canvas
    editor.on('component:remove', (component) => {
        const compId = component.attributes && component.attributes.__tempID;
        if (compId) invalidateCache(compId);
    });

    // When a component is selected, check if it has a temporal UF model
    // and render its Group view inside #component-settings (sidenav)
    editor.on('component:selected', (component) => {
        try {
            const editorConfig = editor.getConfig();
            const compId = component.attributes && component.attributes.__tempID;
            const store = editorConfig.temporalCompStore || {};

            if (!compId || !store[compId]) return;

            // If already active for this component, do nothing
            if (editorConfig.activeDatastore && editorConfig.activeDatastore.componentId === compId) return;

            // Detach previous active view (don't destroy — it stays in cache)
            if (editorConfig.activeDatastore && editorConfig.activeDatastore.componentId !== compId) {
                const prev = editorConfig.activeDatastore;
                try {
                    // Pause the change handler (don't remove — reuse from cache)
                    if (prev.model && prev.handler) prev.model.datastore.off('change', prev.handler);
                } catch (e) {}
                // Detach DOM without destroying the view
                try { if (prev.view && prev.view.$el) prev.view.$el.detach(); } catch (e) {}
                try { window.jQuery && window.jQuery('#component-settings').empty(); } catch (e) {}
                editorConfig.activeDatastore = null;
            }

            const builder_comp_model = store[compId];
            if (!builder_comp_model) return;

            const $wrapper = window.jQuery ? window.jQuery('#component-settings') : null;
            if (!$wrapper || !$wrapper.length) return;

            // Check if we have a cached view for this component
            if (viewCache[compId]) {
                const cached = viewCache[compId];
                // console.log('[viewCache] Re-attaching cached view for', compId);

                // Re-attach the cached DOM
                $wrapper.empty();
                $wrapper.append(cached.view.$el);

                // Re-activate the change handler
                builder_comp_model.datastore.on('change', cached.handler);

                // Trigger resize to fix grid field widths
                window.dispatchEvent(new Event('resize'));

                // Notify other plugins
                editor.trigger('openDatastore', builder_comp_model, component);

                // Refresh jQuery sortable on any repeater groups inside the view
                try { cached.view.$el.find('.uf-repeater-groups').sortable('refresh'); } catch (e) {}

                // Save as active
                editorConfig.activeDatastore = {
                    componentId: compId,
                    view: cached.view,
                    handler: cached.handler,
                    model: cached.model,
                    component: component
                };
                return;
            }

            // === No cache: First-time render ===
            // console.log('[viewCache] Creating new view for', compId);

            const GroupView = UltimateFields.Container.Group.View || UltimateFields.Container.Group.fullScreenView;
            const view = new GroupView({ model: builder_comp_model });

            // Clear wrapper
            $wrapper.empty();

            editor.trigger('openDatastore', builder_comp_model, component);

            try { 
                view.render();
                $wrapper.append(view.$el); 
                // Trigger resize to fix grid field widths
                window.dispatchEvent(new Event('resize'));
            } catch (er) { console.error(er); }

            // Debounced change handler
            const changeHandler = _.debounce(function () {
                try {
                    const group_builder_data = builder_comp_model.get('builder_data') ?? {};
                    const changed = builder_comp_model.datastore.changed || {};

                    // Ignore changes that only affect __tab (tab switching)
                    const keys = Object.keys(changed);
                    if (keys.length && keys[0] === '__tab') return;

                    // if custom_datastore_change_callback is set, skip default handling
                    if( group_builder_data.custom_datastore_change_callback ){
                        if( typeof component.view.custom_datastore_change_callback === 'function' ){
                            component.view.custom_datastore_change_callback(changed);
                        }
                        return;
                    }

                    // Avoid re-rendering if specified in builder_data
                    if( group_builder_data.avoid_rerender ){
                        return;
                    }

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
            }, 100);

            builder_comp_model.datastore.on('change', changeHandler);

            // Store in cache
            viewCache[compId] = {
                view: view,
                handler: changeHandler,
                model: builder_comp_model,
                component: component
            };

            // Save active instance reference
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

    // When a component is deselected: detach view (preserve in cache), pause handler
    editor.on('component:deselected', (component) => {
        try {
            const editorConfig = editor.getConfig();
            const active = editorConfig.activeDatastore;
            if (!active) return;

            if (component && active.componentId === component.attributes.__tempID) {
                // Pause change handler
                try { if (active.model && active.handler) active.model.datastore.off('change', active.handler); } catch (e) {}
                // Detach view DOM (keep in cache for re-attach)
                try { if (active.view && active.view.$el) active.view.$el.detach(); } catch (e) {}
                try { window.jQuery && window.jQuery('#component-settings').empty(); } catch (e) {}
                editorConfig.activeDatastore = null;
            }
        } catch (e) {}
    });

    
    // Validation helper for inline Ultimate Fields datastores using field.validate()
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