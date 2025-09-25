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
            components_data: []
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

            const gjsSection = window["gjsSection"];
            const gjsCompWrapper = window["gjsCompWrapper"];
            const gjsExtendComponents = window["gjsExtendComponents"];

            const defaultComponentTypes = this.get_component_types();

            // INIT THE BUILDER
            React_Builder.init( this.$el.find('#app')[0], {
                blocks: ['text'],
                uf_field_model: this.args.uf_field_model,
                components_data: this.args.components_data,
                groups: this.args.groups,
                // Map component types to groups datastore
                typesControl: { ...defaultComponentTypes,
                    'flipbox': { group: 'flip_box' },
                    'row2': { group: 'row' },
                    'togglebox-wrapper': { group: 'accordion' },
                    'carousel-wrapper': { group: 'carousel' },
                    'comp-wrapper': { group: 'components_wrapper' }
                },
                // Control the blocks that will be rendered
                // pass render: false to disable
                // pass type to use a different component type
                blocksControl: {
                    'flip_box': { render: false },
                    'row': { render: false },
                    'accordion': { render: false },
                    'components_wrapper': { type: 'comp-wrapper' },
                    'carousel': { type: 'carousel-wrapper' }
                },
                builderInstance: that,
                plugins: [
                    rowsAndColsPlugin,
                    contextMenuPlugin,
                    toggleboxPlugin,
                    gjsCompWrapper,
                    gjsSection,
                    gjsExtendComponents,
                    gjsFlipboxPlugin,
                    gjsCarouselPlugin
                ],
                onEditor: function(editor) {
                    editor.runCommand('core:component-outline');
                    that.on_editor_load(editor);
                }
            });
        },
        on_editor_load: function(editor) {
            const that = this;
            editor.setStyle('body{background-color: #333;color: silver;}');

            this.add_custom_components_and_blocks(editor);
            this.add_existing_content(editor);

            // DELETE
            editor.on(`component:remove`, (model) => {
                if (model.getType() === 'group-component') {
                    let builder_comp_model = model.get('builder_comp_model');
                    
                    if (builder_comp_model) {
                        // Clean up the datastore without calling destroy()
                        if (builder_comp_model.datastore && typeof builder_comp_model.datastore.clear === 'function') {
                            builder_comp_model.datastore.clear();
                            builder_comp_model.datastore.parent = null;
                        }
                        
                        // Clean up the model without calling destroy()
                        if (typeof builder_comp_model.clear === 'function') {
                            builder_comp_model.clear();
                        }
                        
                        // Remove event listeners
                        builder_comp_model.off();
                        
                        // Clear the reference
                        model.unset('builder_comp_model');
                    }
                }
            });

            // UPDATE
            editor.on('update', () => { 
                const raw_project_data = editor.getProjectData(),
                    uf_field_model = this.args.uf_field_model;
    
                const values = that.separate_project_data(raw_project_data);
                console.log('raw_project_data', raw_project_data);
                console.log('editor update', values);

                uf_field_model.datastore.set(
                    uf_field_model.get('name'),
                    {
                        builder_data: values.builder_data,
                        components_data: values.components_data
                    },
                    { silent: false }
                );
            });
        },
        get_component_types: function() {
            const groups = this.args.groups,
                componentTypes = {};

            // Map each custom component with a group key 
            _.each(groups, function (group) {
                componentTypes['comp_' + group.id] = { group: group.id };
            });

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
            editor.loadProjectData(this.args.builder_data);
        },
        add_custom_components_and_blocks: function (editor) {
            const that = this,
                groups = this.args.groups,
                blocksControl = editor.getConfig().blocksControl || {};

            _.each(groups, function (group) {
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
                        onRender({ el }) {
                            const name = this.model.get('name');
                            const btn = document.createElement('button');
                            btn.classList.add('edit-btn');
                            btn.innerText = name;
                            el.appendChild(btn);
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
        separate_project_data: function (raw_project_data) {
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
                    const generateId = component.attributes?.id ?? component.__id ?? this.generateId();
                    builderComponent.__id = generateId;

                    // datastore will store: component type, unique id, datastore
                    const componentDataStore = {
                        __cmp: component.type,
                        __id: generateId
                    };

                    // Add datastore if it exists
                    if (component.datastore) {
                        Object.assign(componentDataStore, component.datastore);
                    }
                            
                    // Clean the builder component by removing unwanted keys
                    delete builderComponent.groupData;
                    delete builderComponent.builder_comp_model;
                    delete builderComponent.datastore;

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

                            if (frame.component && frame.component.components) {
                                if (!builderFrame.component) {
                                    builderFrame.component = {};
                                }
                                if (!builderFrame.component.components) {
                                    builderFrame.component.components = [];
                                }
                                processComponents(frame.component.components, builderFrame.component.components, true);
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
        }
    });

    $.fn.builder = function (args) {
        return this.each(function () {
            new builder.Core(this, args);
        });
    }

})(jQuery);