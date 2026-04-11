window.gjsExtendEditor = function (editor) {

    // extend editor with a mehtod to get component datastore
    editor.getComponentDatastore = function(component) {
        const componentId = component.attributes.__tempID,
            editorConfig = editor.getConfig(),
            temporalCompStore = editorConfig.temporalCompStore || {};
        return temporalCompStore[componentId]?.datastore || null;
    }

    editor.getBuilderCompModel = function(component) {
        const componentId = component.attributes.__tempID,
            editorConfig = editor.getConfig(),
            temporalCompStore = editorConfig.temporalCompStore || {};
        return temporalCompStore[componentId] || null;
    }

    editor.updateBuilderCompModelField = function(builder_comp_model, fieldName) {
        const fields = builder_comp_model.get('fields') || {};
	    const fields_models = fields.models || [];
	    const field_model = fields_models.find(fm => fm.get('name') == fieldName);
	    if (field_model) {
	        field_model.trigger('update-views');
        }
    }

    editor.getPreparedFileObject = function( file_id ) {
        if ( typeof UltimateFields === 'undefined' || !UltimateFields.Field || !UltimateFields.Field.File ) {
            console.warn('UltimateFields or UltimateFields.Field.File is not available.');
            return null;
        }

        return UltimateFields.Field.File.Cache.get(file_id);
    }

    editor.getComponentStyle = function(component, styleProperty, defaultValue = '' ) {
        const propertyValue = component.getStyle(styleProperty);
        if ( typeof propertyValue === 'object') return defaultValue;
        return propertyValue || defaultValue;
    }

    // Recursively ensure the component structure exists.
    // If unwantedProps is provided, any prop in that list that is NOT explicitly defined
    // in the componentDef will be reset to its GrapeJS model default on the existing component.
    // This prevents stale properties saved in old JSON from overriding intended runtime behavior.
    editor.ensureComponentStructure = (parent, structureArray, unwantedProps, _root) => {
        // _root tracks the top-level composite component for __movable searches.
        // On the first call _root is undefined; we set it to parent.
        const root = _root || parent;

        // Helper: find a child among a list of component models, matching by type + classes.
        const findByDef = (models, componentDef) => {
            const { type } = componentDef;
            const defClasses = componentDef.classes;

            if (defClasses && defClasses.length) {
                return models.find(c => {
                    const cType = typeof c.get === 'function' ? c.get('type') : c.type;
                    if (cType !== type) return false;
                    const cClasses = typeof c.getClasses === 'function' ? c.getClasses() : [];
                    return defClasses.every(cls => cClasses.includes(cls));
                }) || null;
            }
            return models.find(c =>
                (typeof c.get === 'function' ? c.get('type') : c.type) === type
            ) || null;
        };

        structureArray.forEach((componentDef, index) => {
            const { type, components } = componentDef;
            const isMovable = componentDef.__movable;

            // Find an existing child component matching this definition.
            // If the definition includes classes, match by type + all classes to disambiguate
            // components that share the same type (e.g. multiple 'components-wrapper' children).
            let existingComponent = null;
            try {
                if (isMovable) {
                    // __movable: the component can live anywhere in the composite tree (it was
                    // dragged to a different parent). Search the entire tree from root using
                    // findType + class filtering, instead of only direct children.
                    const candidates = root.findType ? root.findType(type) : [];
                    const defClasses = componentDef.classes;
                    if (defClasses && defClasses.length) {
                        existingComponent = candidates.find(c => {
                            const cClasses = typeof c.getClasses === 'function' ? c.getClasses() : [];
                            return defClasses.every(cls => cClasses.includes(cls));
                        }) || null;
                    } else {
                        existingComponent = candidates[0] || null;
                    }
                } else {
                    const children = parent.components ? parent.components().models : [];
                    existingComponent = findByDef(children, componentDef);
                }
            } catch (e) {
                existingComponent = parent.findType ? parent.findType(type)[0] : null;
            }

            // __movable components are never auto-created — they may simply have been removed.
            // For regular components, create if missing.
            if (!isMovable && !existingComponent) {
                console.log(`Creating missing component: ${type}`);
                parent.append({ type }, { at: index });
                existingComponent = parent.findType(type)[0];
            }

            // Apply simple properties from the structure definition to the existing component.
            // This handles flags like removable, copyable, draggable, selectable, classes, name, etc.
            if (existingComponent) {
                const propsToApply = ['removable', 'copyable', 'draggable', 'selectable', 'badgable', 'propagate', 'resizable', 'name', 'classes', 'tagName', 'droppable', 'delegate'];
                propsToApply.forEach(prop => {
                    if (Object.prototype.hasOwnProperty.call(componentDef, prop)) {
                        try {
                            existingComponent.set(prop, componentDef[prop]);
                        } catch (e) {
                            // Some properties may be managed by collections or require specific APIs; ignore failures
                        }
                    }
                });

                // Reset stale props: if unwantedProps is provided, any prop in that list
                // that is NOT explicitly defined in componentDef gets reset to the model default.
                // This handles the case where a prop was removed from defaultComponents but still
                // exists in old saved JSON (e.g. selectable: false was removed, should revert to true).
                if (unwantedProps && unwantedProps.length) {
                    const modelDefaults = existingComponent.constructor?.getDefaults?.() || {};
                    unwantedProps.forEach(prop => {
                        if (!Object.prototype.hasOwnProperty.call(componentDef, prop)) {
                            try {
                                const defaultVal = prop in modelDefaults ? modelDefaults[prop] : undefined;
                                if (defaultVal !== undefined) {
                                    existingComponent.set(prop, defaultVal);
                                } else {
                                    existingComponent.unset(prop);
                                }
                            } catch (e) { /* ignore */ }
                        }
                    });
                }
            }
            
            // If it has child components, call recursively
            if (components && components.length > 0 && existingComponent) {
                editor.ensureComponentStructure(existingComponent, components, unwantedProps, root);
            }
        });
    };

    // Helpers for I18n
    const toKey = (value) => String(value)
        .trim()
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '_')
        .replace(/^_+|_+$/g, '');

    editor.createTranslator = function(editor, domain = 'ultimate_builder') {
        const i18n = editor && editor.I18n;
        const t = (key, fallback) => {
            if (!i18n) return fallback;
            const fullKey = `${domain}.${key}`;
            const res = i18n.t(fullKey);
            if (res === undefined || res === null || res === '' || res === fullKey || res === key) {
                return fallback;
            }
            return res;
        };

        return (text, key) => {
            if (text === null || text === undefined || text === '') return text;
            const resolvedKey = key || toKey(text);
            return t(resolvedKey, text);
        };
    };
}