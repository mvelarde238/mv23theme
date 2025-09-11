(function ($) {
    var container = UltimateFields.Container,
        group = container.Group;

    var builder = window.builder || {};

    // Create a new component type
    const groupComponent = (editor) => {
        editor.DomComponents.addType('group-component', {
            isComponent: (el) => {
                if (el.tagName === 'DIV' && el.classList?.contains('group-component')) {
                    return { type: 'group-component' };
                }
            },
            model: {
                defaults: {
                    tagName: 'div',
                    draggable: true,
                    droppable: false,
                    groupData: null,
                    attributes: { class: 'group-component' },
                },
                init() {
                    const groupData = this.get('groupData'),
                        comp_name = this.get('name');

                    // update the component name
                    this.set('name', groupData?.title || comp_name);

                    // Create group model and datastore
                    this.addGroup();
                },
                // CREATE
                addGroup: function () {
                    const components_data = editor.getConfig().components_data,
                        uf_field_model = editor.getConfig().uf_field_model,
                        groups = editor.getConfig().groups;

                    let component_id = this.ccid, 
                        groupData, datastore, component_data, __type;
                        
                    // find the corresponding component data
                    component_data = components_data.find(c => c.id === component_id);
                    if( component_data ){
                        // this component is loading from database
                        __type = component_data?.__type;
                        datastore = new UltimateFields.Datastore(component_data);
                        groupData = groups.find(g => g.id === __type) || {};
                        this.set('groupData', groupData);
                    } else {
                        // is a non saved component
                        groupData = this.get('groupData'); // this come from the block
                        __type = groupData.id;
                        datastore = new UltimateFields.Datastore({});
                        datastore.parent = uf_field_model.datastore;
                    }
                    
                    datastore.set('__type', __type);
                    this.set('datastore', datastore);

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
                    this.set('builder_comp_model', builder_comp_model);

                    builder_comp_model.on( 'stateSaved', function() {
                        editor.trigger('update');
                    });
                }
            },
            view: {
                events: {
                    'click .edit-btn': 'onEditClick',
                    'uf-sorted' : 'saveSort'
                },
                onRender({ el }) {
                    const groupData = this.model.get('groupData');

                    // Create the edit button
                    const btn = document.createElement('button');
                    btn.classList.add('edit-btn');
                    btn.innerText = 'Edit ' + groupData?.title;
                    el.appendChild(btn);
                },
                onEditClick: function (ev) {
                    ev.stopPropagation();
                    this.openPopup();
                },
                openPopup: function () {
                    var builder_comp_model = this.model.get('builder_comp_model'),
                        view;

                    // Save the state of the datastore
                    builder_comp_model.backupState();

                    view = new group.fullScreenView({
                        model: builder_comp_model
                    });

                    UltimateFields.Overlay.show({
                        view: view,
                        title: builder_comp_model.datastore.get('title') || builder_comp_model.get('title'),
                        buttons: view.getbuttons()
                    });
                },
                saveSort: function () {
                    var builder_comp_model = this.model.get('builder_comp_model');
                    builder_comp_model.datastore.set('__index', $(this.el).index(), {
				        silent: true
			        });
                }
            }
        });
    };

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
            const rowsAndCols = window["gjs-row-and-cols"];
            const rowsAndColsPlugin = rowsAndCols?.default || rowsAndCols;

            var editor = (window.createIsolatedGrapesJS || window.grapesjs.init)({
                container: this.$el.find('#gjs')[0],
                height: '300px',
                blocks: ['text'],
                storageManager: false,
                telemetry: false,
                uf_field_model: this.args.uf_field_model,
                components_data: this.args.components_data,
                groups: this.args.groups,
                plugins: [
                    groupComponent, 
                    rowsAndColsPlugin
                ],
            });

            editor.setStyle('body{background-color: #333;color: silver;}');

            this.add_existing_content(editor);
            this.add_all_types_as_blocks(editor);

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
                console.log(values);

                uf_field_model.setValue({
                    'builder_data': values.builder_data,
                    'components_data': values.components_data
                });

                uf_field_model.trigger('value-saved');
            });
        },
        // READ
        add_existing_content: function (editor) {
            editor.loadProjectData(this.args.builder_data);
        },
        add_all_types_as_blocks: function (editor) {
            const groups = this.args.groups;

            _.each(groups, function (group) {
                editor.BlockManager.add(group.id, {
                    label: group.title,
                    category: 'Basic',
                    content: {
                        type: 'group-component',
                        groupData: group,
                        style: { padding: '10px' },
                        activeOnRender: 1
                    }
                });
            });
        },
        separate_project_data: function (raw_project_data) {
            const components_data = [];
            const builder_data = JSON.parse(JSON.stringify(raw_project_data)); // Deep clone

            // Recursive function to process components
            const processComponents = (components, builderComponents) => {
                if (!Array.isArray(components) || !Array.isArray(builderComponents)) {
                    return;
                }

                for (let i = 0; i < components.length; i++) {
                    const component = components[i];
                    const builderComponent = builderComponents[i];

                    // If it's a group-component, extract datastore and clean builder data
                    if (component.type === 'group-component' && component.datastore && component.attributes?.id) {
                        // Add to components_data with the component ID
                        components_data.push({
                            id: component.attributes.id,
                            ...component.datastore
                        });

                        // Clean the builder component by removing unwanted keys
                        delete builderComponent.groupData;
                        delete builderComponent.builder_comp_model;
                        delete builderComponent.datastore;
                    }

                    // Process nested components recursively
                    if (component.components && Array.isArray(component.components)) {
                        if (!builderComponent.components) {
                            builderComponent.components = [];
                        }
                        processComponents(component.components, builderComponent.components);
                    }
                }
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
                                processComponents(frame.component.components, builderFrame.component.components);
                            }
                        }
                    }
                }
            }

            return {
                builder_data,
                components_data
            };
        }
    });

    $.fn.builder = function (args) {
        return this.each(function () {
            new builder.Core(this, args);
        });
    }
})(jQuery);