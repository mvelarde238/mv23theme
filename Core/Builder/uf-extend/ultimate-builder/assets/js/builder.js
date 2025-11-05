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
            const contextMenu = window["gjs-context-menu"];
            const contextMenuPlugin = contextMenu?.default || contextMenu;

            const rowsAndCols = window["gjs-row-and-cols"];
            const rowsAndColsPlugin = rowsAndCols?.default || rowsAndCols;

            const togglebox = window["gjs-togglebox"];
            const toggleboxPlugin = togglebox?.default || togglebox;

            const gjsFlipbox = window["gjs-flip-box"];
            const gjsFlipboxPlugin = gjsFlipbox?.default || gjsFlipbox;

            const gjsCarousel = window["gjs-carousel"];
            const gjsCarouselPlugin = gjsCarousel?.default || gjsCarousel;

            const gjsImages = window["gjs-images"];
            const gjsImagesPlugin = gjsImages?.default || gjsImages;

            const gjsVideo = window["gjs-video"];
            const gjsVideoPlugin = gjsVideo?.default || gjsVideo;

            const gjsContainer = window["gjsContainer"];
            const gjsSection = window["gjsSection"];
            const gjsCompWrapper = window["gjsCompWrapper"];
            const gjsExtendComponents = window["gjsExtendComponents"];
            const gjsBuilderCommands = window["gjsBuilderCommands"];
            const gjsMap = window["gjsMap"];
            const gjsListing = window["gjsListing"];
            const gjsGallery = window["gjsGallery"];
            const gjsMenu = window["gjsMenu"];
            const gjsReusableSection = window["gjsReusableSection"];
            const gjsSpacer = window["gjsSpacer"];

            const typesControl = this.get_types_control();

            // INIT THE BUILDER
            React_Builder.init( this.$el.find('#app')[0], {
                clearStyles: true,
                componentFirst: true,
                uf_field_model: this.args.uf_field_model,
                initial_components_data: this.args.initial_components_data,
                groups: this.args.groups,
                // Map component types to groups datastore
                typesControl: typesControl,
                // Control the blocks that will be rendered
                // pass render: false to disable
                // pass type to use a different component type
                blocksControl: {
                    'flip_box': { render: false },
                    'row': { render: false },
                    'accordion': { render: false },
                    'accordion_button': { render: false },
                    'components_wrapper': { type: 'comp-wrapper' },
                    'listing': { type: 'listing' },
                    'gallery': { type: 'gallery' },
                    'menu': { type: 'menu' },
                    'reusable_section': { type: 'reusable-section' },
                    'carousel': { render: false },
                    'column': { render: false },
                    'figure': { render: false },
                    'image': { type: 'image2' },
                    'video': { type: 'video2' },
                    'map': { type: 'map2' },
                    'spacer': { type: 'spacer' }
                },
                // Temporarily store datastores and models for each component
                temporalCompStore: {},
                builderInstance: that,
                plugins: [
                    rowsAndColsPlugin,
                    contextMenuPlugin,
                    toggleboxPlugin,
                    gjsCompWrapper,
                    gjsSection,
                    gjsExtendComponents,
                    gjsFlipboxPlugin,
                    gjsCarouselPlugin,
                    gjsContainer,
                    gjsBuilderCommands,
                    gjsImagesPlugin,
                    gjsVideoPlugin,
                    gjsMap,
                    gjsListing,
                    gjsGallery,
                    gjsMenu,
                    gjsReusableSection,
                    gjsSpacer
                ],
                pluginsOpts: {
                    [contextMenuPlugin]: window.contextMenuOpts
                },
                customTopbarButtonsAfter: [
                    { 
                        id: 'builder:log-data', 
                        iconClass: 'dashicons dashicons-admin-generic',
                        options: { builder: that }
                    },
                    {
                        id: 'builder:save-editor', 
                        iconClass: 'dashicons dashicons-update',
                        options: { builder: that }
                    }
                ],
                canvasCss: `
                    .container{
                        margin:0 auto;
                        width:98%;
                        min-height:100vh;
                        max-width:1220px;
                    }
                    .page-module{
                        min-height: 100px;
                    }
                    .page-module--layout2,
                    .page-module--layout3{
                        width: 100vw !important;
                        position: relative;
                        left: 50%;
                        right: 50%;
                        margin-left: -50vw !important;
                        margin-right: -50vw !important;
                    }
                    .page-module--layout3 > div{
                        width: 98%;
                        max-width: 1220px;
                        margin: 0 auto;
                    }
                    .comp-wrapper{
                        padding: 5px;
                        min-height: 50px;
                    }
                    img{
                        max-width: 100%;
                        height: auto;
                        object-fit: cover;
                    }
                `,
                onEditor: function(editor) {
                    editor.runCommand('core:component-outline');
                    that.on_editor_load(editor);
                }
            });
        },
        on_editor_load: function(editor) {
            const that = this;

            this.add_custom_components_and_blocks(editor);
            this.add_existing_content(editor);

            setTimeout( function() {
                that.add_theme_styles_and_scripts(editor);
            }, 500 );

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
        get_types_control: function() {
            const groups = this.args.groups,
                componentTypes = {};

            // Map each custom component with a group key 
            _.each(groups, function (group) {
                componentTypes['comp_' + group.id] = { group: group.id };
            });

            componentTypes['flipbox'] = { group: 'flip_box' };
            componentTypes['row2'] = { group: 'row' };
            componentTypes['column'] = { group: 'column' };
            componentTypes['togglebox-wrapper'] = { group: 'accordion' };
            componentTypes['togglebox-button'] = { group: 'accordion_button' };
            componentTypes['carousel-wrapper'] = { group: 'carousel' };
            componentTypes['comp-wrapper'] = { group: 'components_wrapper' };
            componentTypes['listing'] = { group: 'listing' };
            componentTypes['section'] = { group: 'section' };
            componentTypes['image2'] = { group: 'image' };
            componentTypes['video2'] = { group: 'video' };
            componentTypes['map2'] = { group: 'map' };
            componentTypes['gallery'] = { group: 'gallery' };
            componentTypes['menu'] = { group: 'menu' };
            componentTypes['reusable-section'] = { group: 'reusable_section' };
            componentTypes['spacer'] = { group: 'spacer' };

            return componentTypes;
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
        add_custom_components_and_blocks: function (editor) {
            const that = this,
                groups = this.args.groups,
                blocksControl = editor.getConfig().blocksControl || {};

            _.each(groups, function (group) {
                // Add a component definition
                editor.DomComponents.addType( 'comp_' + group.id, {
                    model: {
                        defaults: {
                            tagName: 'div',
                            draggable: true,
                            droppable: false,
                            name: group.title
                        }
                    },
                    view: {
                        onRender({ el, model }) {
                            const editorConfig = editor.getConfig(),
                                temporalCompStore = editorConfig.temporalCompStore || {},
                                componentId = this.model.attributes.__tempID;

                            // On render show uf view template or a button to open datastore
                            if ( temporalCompStore[componentId] ) {                                
                                let builder_comp_model = temporalCompStore[componentId];

                                const view_template = builder_comp_model.get('view_template');

                                if ( view_template) {
                                    const _view_template = _.template( view_template );
                                    const datastore = temporalCompStore[componentId].datastore;

                                    el.innerHTML = _view_template( datastore.toJSON() );
                                } else {
                                    const name = model.get('name');
                                    const btn = document.createElement('button');
                                    btn.classList.add('edit-btn');
                                    btn.innerText = name;
                                    el.appendChild(btn);
                                }
                            }

                            // set min height for better testing
                            if ( !el.style.minHeight ) {
                                el.style.minHeight = '50px';
                            }
                        },
                        events: {
                            'click .edit-btn': 'onEditClick',
                            // 'uf-sorted' : 'saveSort'
                        },
                        onEditClick: function (ev) {
                            ev.stopPropagation();
                            editor.select(this.model);
                            editor.runCommand('open-datastore');
                        },
                        // saveSort: function () {
                        //     var builder_comp_model = this.model.get('builder_comp_model');
                        //     builder_comp_model.datastore.set('__index', $(this.el).index(), {
				        //         silent: true
			            //     });
                        // }
                    }
                });

                // Add block for this component type if not disabled
                if ( blocksControl[group.id] && blocksControl[group.id].render === false ) {
                    return;
                }

                const gjs_component_type = (blocksControl[group.id] && blocksControl[group.id].type) ?
                    blocksControl[group.id].type :
                    'comp_' + group.id;

                editor.BlockManager.add(group.id, {
                    label: group.title,
                    category: 'Basic',
                    media: group.icon ? `<i class="dashicons ${group.icon}"></i>` : '',
                    content: {
                        type: gjs_component_type
                    }
                });
            });
        },
        prepare_project_data: function (raw_project_data, temporalCompStore) {
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
                    const typesControl = this.get_types_control();
                    const __type = (typesControl[component.type]) ? typesControl[component.type].group : component.type;
                    const componentDataStore = {
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
                            Object.assign(componentDataStore, datastore.toJSON());
                        }
                    }
                            
                    // Clean the builder component by removing unwanted keys
                    delete builderComponent.__tempID;

                    // if component has a property starting with "__gjs_", copy it to datastore
                    // e.g. __gjs_data_breakpoints in togglebox-wrapper
                    for (const key in component) {
                        if (key.startsWith('__gjs_')) {
                            componentDataStore[key] = component[key];
                        }
                    }

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
                    canvas.getDocument().head.insertAdjacentHTML(
                        'beforeend',
                        `<link rel="stylesheet" type="text/css" href="${styleSrc}">`
                    );
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