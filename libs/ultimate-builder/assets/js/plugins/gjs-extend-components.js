window.gjsExtendComponents = function (editor) {
    const domc = editor.DomComponents;

    // =====================================================================
    // VIEW CACHE: Stores rendered GroupViews per compId so they are created
    // only once and re-attached on subsequent selects instead of recreated.
    // Each entry: { view, handler, model, component }
    // =====================================================================
    const viewCache = {};

    // Expose viewCache so other plugins (e.g. gjs-datastore-undo) can
    // invalidate entries when datastore values change externally.
    editor.getConfig().viewCache = viewCache;

    /**
     * Invalidate (destroy) a cached view for a given compId.
     * Cleans up the change handler, removes the DOM, and deletes the entry.
     */
    function invalidateCache(compId) {
        const cached = viewCache[compId];
        if (!cached) return;
        try {
            if (cached.handler && cached.handler.cancel) cached.handler.cancel();
            if (cached.model && cached.handler) cached.model.datastore.off('change', cached.handler);
        } catch (e) {}
        try { destroyMCEInView(cached.view && cached.view.$el); } catch (e) {}
        try { if (cached.view && cached.view.$el) cached.view.$el.remove(); } catch (e) {}
        delete viewCache[compId];
        // console.log('[viewCache] Invalidated cache for', compId);
    }

    // =====================================================================
    // TinyMCE lifecycle helpers
    // TinyMCE does not survive DOM detach/re-attach: its internal iframe
    // reference breaks causing broken editors, lost change events, and
    // cross-editor data contamination.  We destroy instances on detach
    // and reinitialize them on re-attach.
    // =====================================================================

    /**
     * Destroy all TinyMCE editor instances inside a jQuery element.
     * Must be called BEFORE the element is detached from the DOM.
     */
    function destroyMCEInView($el) {
        if (typeof tinymce === 'undefined' || !$el || !$el.length) return;
        $el.find('.wp-editor-wrap').each(function () {
            var mceId = window.jQuery(this).attr('data-mce-id');
            if (mceId) {
                var editorId = mceId + '_id';
                var mceEditor = tinymce.get(editorId);
                if (mceEditor) {
                    try { tinymce.remove(mceEditor); } catch (e) {}
                }
            }
        });
    }

    /**
     * Reinitialize TinyMCE editors inside a cached view after re-attaching.
     * Triggers the UF 'uf-sorted' event on each WYSIWYG field wrapper,
     * which causes the WYSIWYG field view to fully re-render (new ID,
     * new textarea, fresh TinyMCE init with correct closure references).
     * Uses triggerHandler to prevent event bubbling to the GroupView.
     */
    function reinitMCEInView($el) {
        if (!$el || !$el.length) return;
        $el.find('.wp-editor-wrap').each(function () {
            window.jQuery(this).closest('.uf-field').triggerHandler('uf-sorted');
        });
    }

    // =====================================================================
    // Extend gjs component connecting it with Ultimate Fields datastore
    // =====================================================================
    /**
     * Resolves the component that actually owns the datastore for the given component.
     * Some component types (e.g. flipbox-front / flipbox-back) declare
     * `builder_data.share_datastore_with: '<type>'` so they don't have (and don't save)
     * their own datastore, but instead reuse the datastore of the closest ancestor of
     * that type. This keeps a single datastore per group of components (e.g. one per
     * flipbox instead of one per flipbox + one per side).
     */
    function resolveDatastoreOwner(component, editorConfig) {
        const groups = editorConfig.groups || [];
        let current = component;

        while (current) {
            const groupData = groups.find(g => g.id === current.get('type'));
            const shareWith = groupData && groupData.builder_data && groupData.builder_data.share_datastore_with;
            if (!shareWith) break;

            const ancestor = current.closestType(shareWith);
            if (!ancestor) break;

            current = ancestor;
        }

        return current;
    }
    
    /**
    * When a component is created, check if it has a group associated with it
    * and create a model for it. The model will be used to render the group view
    * inside the component settings panel.
    */
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

            // generate a temporal id and assign it to gjs component to identify it
            // during the editing and save process (e.g. remove, clone)
            const generatedId = builderInstance.generateId();
            gjs_component.attributes.__tempID = generatedId;

            // Some component types (e.g. flipbox-front / flipbox-back) don't have their
            // own datastore: they share the datastore of an ancestor component instead, so
            // only one datastore ends up being saved per group of components. In that case,
            // skip creating a model/datastore for this component entirely.
            const shareDatastoreWith = groupData.builder_data && groupData.builder_data.share_datastore_with;
            if (shareDatastoreWith) {
                return;
            }

            editorConfig.temporalCompStore[generatedId] = {};

            // find the corresponding component dataStore using the builder instance method
            component_data = builderInstance.findComponentById(initial_components_data, gjs_component.get('__id'));

            // configure the data store
            if (component_data) {
                // Data exists, this component is loading from database
                __type = component_data.__type;
                datastore = new UltimateFields.Datastore(component_data);
            } else {
                // is a new component
                __type = groupData?.id;
                const defaults = gjs_component.get('datastoreDefaults') || {};
                datastore = new UltimateFields.Datastore(defaults);
                datastore.parent = uf_field_model.datastore;
            }
            datastore.set('__type', __type);

            // Allow arguments to be modified before creating the model, view and etc.
            args = {
                model: UltimateFields.Container.Group.Model,
                component: gjs_component,
                datastore: datastore,
                settings: groupData,
                silent: false
            };

            UltimateFields.applyFilters('before_group_create', args);

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
            clonedComponentId = clonedComponent.attributes.__tempID;

        // Resolve the original component: if the selected component delegates
        // its copy to a parent (e.g. flipbox-front → flipbox), follow the
        // delegate so we compare against the real cloned root.  Without this,
        // recursive component:clone events for inner children (like the back
        // face of a flip-box) would incorrectly use the *selected* face as
        // the original, cloning the active side's content into both sides.
        let originalComponent = editor.getSelected();
        if (originalComponent) {
            const copyDelegateFn = originalComponent.get('delegate')?.copy;
            if (copyDelegateFn) {
                const delegateTarget = copyDelegateFn(originalComponent);
                if (delegateTarget) {
                    originalComponent = delegateTarget;
                }
            }
        }

        const originalComponentId = originalComponent.attributes.__tempID;

        if ( temporalCompStore[originalComponentId] ) {
            if( originalComponent.getType() === clonedComponent.getType() ) {
                const ogAttributes = temporalCompStore[originalComponentId].datastore.attributes;
                const clonedData = { ...ogAttributes };
                delete clonedData.__id;

                // Create a fresh datastore with the cloned data and re-initialize
                // the group model so all fields (including complex/video/embed)
                // read the correct values from the start.
                const newDatastore = new UltimateFields.Datastore(clonedData);
                newDatastore.parent = editorConfig.temporalCompStore[clonedComponentId].datastore.parent;
                editorConfig.temporalCompStore[clonedComponentId].setDatastore(newDatastore);
            }
        }

        // Recursively copy children datastores from original to cloned component.
        // Without this, only the top-level datastore is cloned and children
        // (e.g. text-editor, icon-box inside a testimonial) get empty datastores
        // which causes their field defaults to override the actual content.
        function copyChildrenDatastores(original, cloned) {
            const origChildren = original.components();
            const clonedChildren = cloned.components();
            if (!origChildren || !clonedChildren) return;

            origChildren.each(function(origChild, index) {
                const clonedChild = clonedChildren.at(index);
                if (!clonedChild) return;

                // Remove __id from cloned child to avoid DB collisions
                delete clonedChild.attributes.__id;

                const origId = origChild.attributes.__tempID;
                const clonedId = clonedChild.attributes.__tempID;

                if (origId && clonedId && temporalCompStore[origId] && temporalCompStore[clonedId]) {
                    const ogData = { ...temporalCompStore[origId].datastore.attributes };
                    delete ogData.__id;
                    const newDs = new UltimateFields.Datastore(ogData);
                    newDs.parent = temporalCompStore[clonedId].datastore.parent;
                    temporalCompStore[clonedId].setDatastore(newDs);
                }

                copyChildrenDatastores(origChild, clonedChild);
            });
        }
        copyChildrenDatastores(originalComponent, clonedComponent);
    });

    // Invalidate cache and clean up temporalCompStore when a component is removed.
    // GrapeJS fires component:remove for each descendant, so children are handled
    // automatically. We just need to clean both stores for the removed component.
    editor.on('component:remove', (component) => {
        const compId = component.attributes && component.attributes.__tempID;
        if (compId) {
            invalidateCache(compId);
            delete editor.getConfig().temporalCompStore[compId];
        }
    });

    // When a component is selected, check if it has a temporal UF model
    // and render its Group view inside #component-settings (sidenav)
    editor.on('component:selected', (component) => {
        try {
            const editorConfig = editor.getConfig();

            // Resolve the component that actually owns the datastore (e.g. flipbox-front/back
            // share the datastore of their parent flipbox instead of having their own), so the
            // rest of this handler operates on the real owner.
            component = resolveDatastoreOwner(component, editorConfig);

            const compId = component.attributes && component.attributes.__tempID;
            const store = editorConfig.temporalCompStore || {};

            if (!compId || !store[compId]) return;

            // If already active for this component, do nothing
            if (editorConfig.activeDatastore && editorConfig.activeDatastore.componentId === compId) return;

            // Detach previous active view (don't destroy — it stays in cache)
            if (editorConfig.activeDatastore && editorConfig.activeDatastore.componentId !== compId) {
                const prev = editorConfig.activeDatastore;
                try {
                    // Cancel any pending debounced invocation to prevent stale writes
                    if (prev.handler && prev.handler.cancel) prev.handler.cancel();
                    // Pause the change handler (don't remove — reuse from cache)
                    if (prev.model && prev.handler) prev.model.datastore.off('change', prev.handler);
                } catch (e) {}
                // Destroy TinyMCE instances before detaching (they don't survive DOM detach)
                try { destroyMCEInView(prev.view && prev.view.$el); } catch (e) {}
                // Detach DOM without destroying the view
                try { if (prev.view && prev.view.$el) prev.view.$el.detach(); } catch (e) {}
                try { window.jQuery && window.jQuery('#component-settings').empty(); } catch (e) {}
                editorConfig.activeDatastore = null;
            }

            const builder_comp_model = store[compId];
            if (!builder_comp_model) return;

            const $wrapper = window.jQuery ? window.jQuery('#component-settings') : null;
            if (!$wrapper || !$wrapper.length) return;

            // Notify other plugins
            editor.trigger('beforeOpenDatastore', builder_comp_model, component);

            // Check if we have a cached view for this component
            if (viewCache[compId]) {
                const cached = viewCache[compId];
                // console.log('[viewCache] Re-attaching cached view for', compId);

                // Re-attach the cached DOM
                $wrapper.empty();
                $wrapper.append(cached.view.$el);

                // Reinitialize TinyMCE editors that were destroyed on detach
                reinitMCEInView(cached.view.$el);

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

            // Resolve to the real datastore owner, same as on component:selected
            if (component) component = resolveDatastoreOwner(component, editorConfig);

            const active = editorConfig.activeDatastore;
            if (!active) return;

            if (component && active.componentId === component.attributes.__tempID) {
                // Cancel any pending debounced invocation to prevent stale writes
                try { if (active.handler && active.handler.cancel) active.handler.cancel(); } catch (e) {}
                // Pause change handler
                try { if (active.model && active.handler) active.model.datastore.off('change', active.handler); } catch (e) {}
                // Destroy TinyMCE instances before detaching (they don't survive DOM detach)
                try { destroyMCEInView(active.view && active.view.$el); } catch (e) {}
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