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
            initial_components_data: [],
            theme_fonts: [],
            theme_colors: []
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
                theme_fonts: this.args.theme_fonts,
                groups: this.args.groups,
                // Control the blocks that will be rendered
                blocksControl: {},
                // Temporarily store datastores for each component
                temporalCompStore: {},
                builderInstance: that,
                // Exclude certain component types from spacing spots to avoid layout issues
                spacingSpots: {
                    excludeComponentTypes: [
                        'wrapper', 'spacer', 'shortcode', 'image-component', 'text-editor', 'code', 'menu', 'video-component', 'button'
                    ]
                },
                plugins: [...plugins, ...React_Builder_Plugins],
                pluginsOpts: {
                    [window['gjs-context-menu'].default]: window['contextMenuOpts']
                },
                customTopbarButtonsAfter: [
                    {
                        id: 'builder:preview', 
                        iconClass: 'bi bi-eye',
                        label: 'PREVIEW',
                        className: 'secondary-button builder-preview-button'
                    },
                    {
                        id: 'builder:save-editor', 
                        iconClass: 'bi bi-floppy2',
                        label: 'SAVE',
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
                        iconClass: 'dashicons dashicons-admin-generic'
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

            // Set blocks control
            editorConfig.blocksControl = this.generate_blocks_control(editor);

            this.add_theme_fonts(editor);
            this.add_components_definition_and_blocks(editor);
            this.add_existing_content(editor);
            editor.trigger('builder:loaded');
            
            // Add theme styles and scripts after a short delay to ensure canvas is ready
            setTimeout( function() {
                editor.runCommand('show-preloader', { text: 'Editor loaded' });
                that.add_theme_styles_and_scripts(editor);
                editor.runCommand('hide-preloader');    
            }, 500 );

            // Edit device settings to set canvas mobile width to 375px. Default values are:
            // Device           | Width for media query | Canvas width
            // Tablet           | 992px                 | 770px
            // Mobile landscape | 768px                 | 568px
            // Mobile portrait  | 480px                 | 320px*
            const deviceManager = editor.Devices;
            const device = deviceManager.get('Mobile portrait');
            device.set('width', '375px');

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
        add_theme_fonts: function(editor) {
            if ( !this.args.theme_fonts || this.args.theme_fonts.length === 0 ) {
                return;
            }

            const styleManager = editor.StyleManager;
            const fontFamilyProp = styleManager.getProperty('typography', 'font-family');
        
            if (fontFamilyProp) {
                const currentDatalist = fontFamilyProp.get('datalist') || [];
                const customFonts = this.args.theme_fonts;
          
                // Formate options for datalist (default groups quantity is 3, so we will group fonts in groups of 3 for better display)
                let groupSize = 3;
                if (customFonts.length <= groupSize) groupSize = 2;

                const options = [];
                for (let i = 0; i < customFonts.length; i += groupSize) {
                    options.push(customFonts.slice(i, i + groupSize));
                }
          
                // Add a new group of fonts to the datalist
                fontFamilyProp.set('datalist', [
                    {
                        title: 'THEME FONTS',
                        options: options
                    },
                    ...currentDatalist
                ]);
            }
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
        generate_blocks_control: function(editor) {
            const groups = this.args.groups;
            const editorConfig = editor.getConfig();
            let blocksControl = {};

            _.each(groups, function (group) {
                const group_builder_data = group.builder_data || {};
                
                // Determine which component type will be rendered for this block
                let connected_type = group_builder_data.block_render_type ?? group.id;

                // Determine if block should be rendered
                let renderBlock = true;
                if ( group_builder_data.display_gjs_block === false ) {
                    renderBlock = false;
                }
                if ( group_builder_data.posttypes && Array.isArray(group_builder_data.posttypes) ) {
                    const currentPostType = BUILDER_GLOBALS.posttype;
                    if ( !group_builder_data.posttypes.includes(currentPostType) ) {
                        renderBlock = false;
                    }
                }

                blocksControl[group.id] = {
                    type: connected_type,
                    render: renderBlock
                };
            });

            return blocksControl;
        },
        // Helper method to find component in flat data structure (__id => data)
        findComponentById: function (data, id) {
            return data?.[id] ?? null;
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
                    if (compType.id === group.id) {
                        typeExists = true;
                    }
                });
                if (!typeExists) {   
                    editor.DomComponents.addType( group.id, {
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
                    group.id;

                let icon_source = ''; 
                if ( group.icon ) {
                    if ( group.icon.startsWith('dashicons') ) icon_source = 'dashicons';
                    if ( group.icon.startsWith('bi') ) icon_source = 'bi';
                    if ( group.icon.startsWith('fa') ) icon_source = 'fa';
                }

                // Determine block category
                const __ = editor.createTranslator(editor);
                let block_category = __('Content');
                const group_builder_data = group.builder_data || {};
                if ( group_builder_data.block_category ) {
                    block_category = __(group_builder_data.block_category);
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
            const components_data = {}; // Flat object: __id => data
            const builder_data = JSON.parse(JSON.stringify(raw_project_data)); // Deep clone

            // Recursive function to process components
            const processComponents = (components, builderComponents) => {
                if (!Array.isArray(components) || !Array.isArray(builderComponents)) {
                    return;
                }

                for (let i = 0; i < components.length; i++) {
                    const component = components[i];
                    let builderComponent = builderComponents[i];

                    // Generate a unique ID to connect builder component with datastore
                    const compId = component.__id ?? this.generateId();
                    builderComponent.__id = compId; // this is the connection between builder data and datastore

                    // datastore will store: component type, unique id, datastore
                    const __type = component.type;
                    let componentDataStore = {};

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

                    /**
                     * Filter: builder_component_cleanup
                     * Allows external code to modify component data before saving.
                     * 
                     * @param {Object} data - Mutable object containing:
                     *   - componentDataStore: Data that will be saved to database
                     *   - builderComponent: GrapesJS component data
                     *   - component: Raw component from editor
                     *   - __type: Component type identifier
                     */
                    UltimateFields.applyFilters('builder_component_cleanup', {
                        componentDataStore: componentDataStore,
                        builderComponent: builderComponent,
                        component: component,
                        __type: __type
                    });

                    // Add to components_data with __id as key if it
                    if(componentDataStore.__type) components_data[compId] = componentDataStore;

                    // Process nested components recursively
                    if (component.components && Array.isArray(component.components) && component.components.length > 0) {
                        if (!builderComponent.components) {
                            builderComponent.components = [];
                        }
                        processComponents(component.components, builderComponent.components);
                    }
                }
            };

            // Process all gjs pages and their frames
            if (builder_data.pages && Array.isArray(builder_data.pages)) {
                for (let pageIndex = 0; pageIndex < raw_project_data.pages.length; pageIndex++) {
                    const page = raw_project_data.pages[pageIndex];
                    const builderPage = builder_data.pages[pageIndex];

                    if (page.frames && Array.isArray(page.frames)) {
                        for (let frameIndex = 0; frameIndex < page.frames.length; frameIndex++) {
                            const frame = page.frames[frameIndex];
                            const builderFrame = builderPage.frames[frameIndex];

                            if (frame.component?.type) {
                                if (!builderFrame.component) {
                                    builderFrame.component = {};
                                }
                                processComponents([frame.component], [builderFrame.component]);
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

                // these scripts are not working when added to the canvas, 
                // probably because they are added after the canvas is loaded. 
                // We need to find a way to add them before the canvas is loaded or to re-initialize the canvas after adding them.
                // themeScripts.forEach( scriptSrc => {
                //     canvas.getDocument().body.insertAdjacentHTML(
                //         'beforeend',
                //         `<script type="text/javascript" src="${scriptSrc}"></script>`
                //     );
                // });
            }
        }
    });

    $.fn.builder = function (args) {
        return this.each(function () {
            new builder.Core(this, args);
        });
    }

})(jQuery);