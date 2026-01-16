(function ($) {
    var container = UltimateFields.Container,
        group = container.Group;

    var builder = window.builder || {};

    builder.Core = function ($el, args) {
        this.$el = $($el);

        this.args = $.extend({
            groups: [],
            uf_field_model: null,
            builder_data: [],
            initial_components_data: []
        }, args);

        this.initialize();
    };

    $.extend(builder.Core.prototype, {
        initialize: function () {
            const that = this;
            const plugins = this.get_plugins();

            // INIT THE BUILDER
            React_Builder.init( this.$el.find('#app')[0], {
                clearStyles: true,
                componentFirst: true,
                showToolbar: false,
                uf_field_model: this.args.uf_field_model,
                initial_components_data: this.args.initial_components_data,
                groups: this.args.groups,
                // Map component types to groups datastore
                typesControl: {},
                // Control the blocks that will be rendered
                blocksControl: {},
                // Temporarily store datastores for each component
                temporalCompStore: {},
                builderInstance: that,
                plugins: [...plugins, ...React_Builder_Plugins],
                pluginsOpts: {
                    [window['gjs-context-menu'].default]: window['contextMenuOpts']
                },
                customTopbarButtonsAfter: [
                    {
                        id: 'builder:preview', 
                        iconClass: 'bi bi-eye',
                        options: { builder: that },
                        label: 'Preview',
                        className: 'secondary-button builder-preview-button'
                    },
                    {
                        id: 'builder:save-editor', 
                        iconClass: 'bi bi-floppy2',
                        options: { builder: that },
                        label: 'Save',
                        className: 'primary-button builder-save-button'
                    }
                ],
                customViewControlButtonsAfter: [
                    { 
                        id: 'exit-to-wp-admin', 
                        label: 'EXIT TO WP ADMIN',
                        iconClass: 'dashicons dashicons-wordpress',
                        href: BUILDER_GLOBALS.admin_url,
                        target: '_self'
                    },
                    { 
                        id: 'builder:log-data', 
                        label: 'LOG PROJECT DATA',
                        iconClass: 'dashicons dashicons-admin-generic',
                        options: { builder: that }
                    },
                ],
                onEditor: function(editor) {
                    window['UF_Editor'] = editor;
                    that.on_editor_load(editor);
                }
            });
        },
        on_editor_load: function(editor) {
            const that = this;

            // Set types control
            const editorConfig = editor.getConfig();
            editorConfig.typesControl = this.get_types_control(editor);

            // Set blocks control
            editorConfig.blocksControl = this.generate_blocks_control(editor);

            this.add_components_definition_and_blocks(editor);
            this.add_existing_content(editor);
            editor.trigger('builder:loaded');

            // Add theme styles and scripts after a short delay to ensure canvas is ready
            setTimeout( function() {
                that.add_theme_styles_and_scripts(editor);
            }, 500 );

            // display warning before leaving the page with unsaved changes
		    // window.addEventListener('beforeunload',function(e){
            //     if ( editor.getProjectData() !== that.args.builder_data ) {
            //         e.preventDefault();
            //         e.returnValue = '';
            //     }
            // });

            // DELETE
            // editor.on(`component:remove`, (model) => {
            //     if (model.getType() === 'group-component') {
            //         let builder_comp_model = model.get('builder_comp_model');
                    
            //         if (builder_comp_model) {
            //             // Clean up the datastore without calling destroy()
            //             if (builder_comp_model.datastore && typeof builder_comp_model.datastore.clear === 'function') {
            //                 builder_comp_model.datastore.clear();
            //                 builder_comp_model.datastore.parent = null;
            //             }
                        
            //             // Clean up the model without calling destroy()
            //             if (typeof builder_comp_model.clear === 'function') {
            //                 builder_comp_model.clear();
            //             }
                        
            //             // Remove event listeners
            //             builder_comp_model.off();
                        
            //             // Clear the reference
            //             model.unset('builder_comp_model');
            //         }
            //     }
            // });

            // UPDATE
            // editor.on('update', () => {});
        },
        get_plugins: function() {
            const plugins = [];

            const customPlugins = this.args.gjs_plugins || [];
            customPlugins.forEach( plugin => {
                if (plugin.isExternal) {
                    const externalPlugin = window[plugin.handle];
                    if (externalPlugin) {
                        plugins.push(externalPlugin?.default || externalPlugin);
                    }
                } else {
                    const pluginObj = window[plugin.name];
                    if (pluginObj) {
                        plugins.push(pluginObj);
                    }
                }
            });

            return plugins;
        },
        get_types_control: function(editor) {
            const groups = this.args.groups;
            let componentTypes = {};

            // Map each custom component with a group key 
            _.each(groups, function (group) {
                let connected_type = 'comp_' + group.id;

                const group_builder_data = group.builder_data || {};
                if ( group_builder_data.connected_gjs_type ) {
                    connected_type = group.builder_data.connected_gjs_type;
                } else {
                    editor.DomComponents.getTypes().forEach((compType) => {
                        if (compType.id === group.id) {
                            connected_type = group.id;
                        }
                    });
                }
                componentTypes[connected_type] = { group: group.id };
            });

            return componentTypes;
        },
        generate_blocks_control: function(editor) {
            const groups = this.args.groups;
            const editorConfig = editor.getConfig();
            const typesControl = editorConfig.typesControl || {};
            console.log('typesControl', typesControl);
            let blocksControl = {};

            _.each(groups, function (group) {
                const group_builder_data = group.builder_data || {};
                
                // Determine which component type will be rendered for this block
                let connected_type = group_builder_data.block_render_type ?? 
                    group_builder_data.connected_gjs_type ?? 
                    'comp_' + group.id;

                // Determine if block should be rendered
                let renderBlock = true;
                if ( group_builder_data.display_gjs_block === false ) {
                    renderBlock = false;
                }

                blocksControl[group.id] = {
                    type: connected_type,
                    render: renderBlock
                };
            });

            return blocksControl;
        },
        // Helper method to search component recursively
        findComponentById: function (data, id) {
            if (!Array.isArray(data)) return null;
            
            for (const item of data) {
                if (item.__id === id) {
                    return item;
                }
                
                // Search in nested components if they exist
                if (item.components && Array.isArray(item.components)) {
                    const found = this.findComponentById(item.components, id);
                    if (found) return found;
                }
            }
            
            return null;
        },
        // READ
        add_existing_content: function (editor) {
            if (!this.args.builder_data || !this.args.builder_data.pages) {
                editor.setComponents({type: 'container'});
            } else {
                editor.loadProjectData(this.args.builder_data);
            }
        },
        add_components_definition_and_blocks: function (editor) {
            const that = this,
                groups = this.args.groups,
                blocksControl = editor.getConfig().blocksControl || {};

            _.each(groups, function (group) {
                // Add gjs component type definition if not already defined
                let typeExists = false;
                editor.DomComponents.getTypes().forEach((compType) => {
                    if (compType.id === 'comp_' + group.id) {
                        typeExists = true;
                    }
                });
                if (!typeExists) {   
                    editor.DomComponents.addType( 'comp_' + group.id, {
                        extend: 'comp-base',
                        model: {
                            defaults: {
                                name: group.title
                            }
                        }
                    });
                }

                // Add block for this component type if not disabled
                if ( blocksControl[group.id] && blocksControl[group.id].render === false ) {
                    return;
                }

                const gjs_component_type = (blocksControl[group.id] && blocksControl[group.id].type) ?
                    blocksControl[group.id].type :
                    'comp_' + group.id;

                console.log('Adding block for component:', group.id, gjs_component_type);

                let icon_source = ''; 
                if ( group.icon ) {
                    if ( group.icon.startsWith('dashicons') ) icon_source = 'dashicons';
                    if ( group.icon.startsWith('bi') ) icon_source = 'bi';
                    if ( group.icon.startsWith('fa') ) icon_source = 'fa';
                }

                // Determine block category
                let block_category = 'Basic';
                const group_builder_data = group.builder_data || {};
                if ( group_builder_data.block_category ) {
                    block_category = group_builder_data.block_category;
                }

                editor.BlockManager.add(group.id, {
                    label: group.title,
                    category: block_category,
                    media: group.icon ? `<i class="${icon_source} ${group.icon}"></i>` : '',
                    content: {
                        type: gjs_component_type
                    }
                });
            });
        },
        prepare_project_data: function (raw_project_data, temporalCompStore, editor) {
            const components_data = [];
            const builder_data = JSON.parse(JSON.stringify(raw_project_data)); // Deep clone

            // Recursive function to process components
            const processComponents = (components, builderComponents, isTopLevel = true) => {
                if (!Array.isArray(components) || !Array.isArray(builderComponents)) {
                    return [];
                }

                const processedComponents = [];

                for (let i = 0; i < components.length; i++) {
                    const component = components[i];
                    let builderComponent = builderComponents[i];

                    // Generate a unique ID to connect builder component with datastore
                    const compId = component.__id ?? this.generateId();
                    builderComponent.__id = compId; // this is the connection between builder data and datastore

                    // datastore will store: component type, unique id, datastore and custom "selector attributes"
                    const typesControl = this.get_types_control(editor);
                    const __type = (typesControl[component.type]) ? typesControl[component.type].group : component.type;
                    let componentDataStore = {
                        __type: __type,
                        __id: compId,
                        __gjsAttributes: component.attributes
                    };

                    // Add datastore if it exists
                    const __tempID = component.__tempID;
                    if ( temporalCompStore[__tempID] ) {
                        const datastore = temporalCompStore[__tempID].datastore;
                        if (datastore) {
                            delete temporalCompStore[__tempID].datastore.__type;
                            let datastoreJSON = datastore.toJSON();
                            // Merge datastore JSON into componentDataStore
                            componentDataStore = {...datastoreJSON, ...componentDataStore}
                        }
                    }
                            
                    // Clean the builder component by removing unwanted keys
                    // if component has a property starting with "__temp", delete it
                    // e.g. __tempID, __temp_posts_cached
                    for (const key in builderComponent) {
                        if (key.startsWith('__temp')) {
                            delete builderComponent[key];
                        }
                    }
                    
                    // if uf component has a property starting with "__gjs_", copy it to datastore
                    // e.g. __gjs_data_breakpoints in togglebox-wrapper
                    for (const key in component) {
                        if (key.startsWith('__gjs_')) {
                            componentDataStore[key] = component[key];
                        }
                    }

                    /**
                     * Filter: builder_component_cleanup
                     * Allows external code to modify component data before saving.
                     * 
                     * @param {Object} data - Mutable object containing:
                     *   - componentDataStore: Data that will be saved to database
                     *   - builderComponent: GrapesJS component data
                     *   - component: Raw component from editor
                     *   - __type: Component type identifier
                     *   - isTopLevel: Boolean indicating if component is at root level
                     */
                    UltimateFields.applyFilters('builder_component_cleanup', {
                        componentDataStore: componentDataStore,
                        builderComponent: builderComponent,
                        component: component,
                        __type: __type,
                        isTopLevel: isTopLevel
                    });

                    // Add components array if it has nested components
                    if (component.components && Array.isArray(component.components) && component.components.length > 0) {
                        if (!builderComponent.components) {
                            builderComponent.components = [];
                        }
                        
                        // Process nested components recursively (not top level)
                        componentDataStore.components = processComponents(component.components, builderComponent.components, false);
                    }

                    // Add to the appropriate array
                    if (isTopLevel) {
                        components_data.push(componentDataStore);
                    } else {
                        processedComponents.push(componentDataStore);
                    }
                }

                return processedComponents;
            };

            // Process all pages and their frames
            if (builder_data.pages && Array.isArray(builder_data.pages)) {
                for (let pageIndex = 0; pageIndex < raw_project_data.pages.length; pageIndex++) {
                    const page = raw_project_data.pages[pageIndex];
                    const builderPage = builder_data.pages[pageIndex];

                    if (page.frames && Array.isArray(page.frames)) {
                        for (let frameIndex = 0; frameIndex < page.frames.length; frameIndex++) {
                            const frame = page.frames[frameIndex];
                            const builderFrame = builderPage.frames[frameIndex];

                            if (frame.component) {
                                if (!builderFrame.component) {
                                    builderFrame.component = {};
                                }
                                
                                // Process the wrapper/body component itself first
                                if (frame.component.type) {
                                    const wrapperComponent = {
                                        type: frame.component.type,
                                        attributes: frame.component.attributes || {},
                                        __id: frame.component.__id,
                                        __tempID: frame.component.__tempID,
                                        components: frame.component.components || []
                                    };
                                    
                                    let builderWrapperComponent = {
                                        type: frame.component.type,
                                        attributes: frame.component.attributes || {},
                                        components: builderFrame.component.components || []
                                    };
                                    
                                    // Process the wrapper as a top-level component
                                    processComponents([wrapperComponent], [builderWrapperComponent], true);
                                    
                                    // Update the builder frame component with processed data
                                    Object.assign(builderFrame.component, builderWrapperComponent);
                                // } else if (frame.component.components) {
                                    // Fallback: process only nested components if wrapper has no type
                                    // if (!builderFrame.component.components) {
                                        // builderFrame.component.components = [];
                                    // }
                                    // processComponents(frame.component.components, builderFrame.component.components, true);
                                }
                            }
                        }
                    }
                }
            }

            return {
                builder_data,
                components_data
            };
        },
        generateId: function() {
            return 'cmp_' + Math.random().toString(36).substr(2, 9);
        },
        add_theme_styles_and_scripts: function (editor) {
            const themeStyles = this.args.theme_styles || [];
            const themeScripts = this.args.theme_scripts || [];
            const canvas = editor.Canvas;

            // console.log('Adding theme styles and scripts:', themeStyles, themeScripts);

            if (canvas) {
                themeStyles.forEach( styleSrc => {
                    // if styleSrc starts with http or https, use as is, else treat as inline style
                    if ( /^https?:\/\//i.test(styleSrc) ) {
                        canvas.getDocument().head.insertAdjacentHTML(
                            'beforeend',
                            `<link rel="stylesheet" type="text/css" href="${styleSrc}">`
                        );
                    } else {
                        canvas.getDocument().head.insertAdjacentHTML(
                            'beforeend',
                            `<style>${styleSrc}</style>`
                        );
                    }
                });
                themeScripts.forEach( scriptSrc => {
                    canvas.getDocument().head.insertAdjacentHTML(
                        'beforeend',
                        `<script type="text/javascript" src="${scriptSrc}"></script>`
                    );
                });
            }
        }
    });

    $.fn.builder = function (args) {
        return this.each(function () {
            new builder.Core(this, args);
        });
    }

})(jQuery);