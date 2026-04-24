window.gjsIconList = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'icon-list';

    /**
     * TODO:
     * - Consider using ul/li structure for the list and implementing the different list styles (numbered, bulleted, etc.) using CSS classes.
     * - Consider allowing users to choose from different preset list styles (e.g. numbered, bulleted, etc.)
     */

    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const compName = __('Icon List');

    // Main component definition
    domc.addType(compClass, {
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: compName,
                tagName: 'div',
                classes: [compClass, 'component'],
                droppable: '[data-gjs-type="icon-and-text"]', // Only allow icon-and-text components to be dropped inside the list
                __needsSetup: true, // Flag to track whether initial scaffolding (text-editors, icon defaults) is needed
            }
        },
        view: {
            onRender({el, model}) {
                if(model.get('__needsSetup')) {
                    this.extra_customizations_on_initial_render({el, model});
                }
                this.appendComponentActions(el);
            },
            extra_customizations_on_initial_render({el, model}) {
                // On the initial render after a component is added, scaffold the default content for the icon list 
                // by adding three icon-and-text components as children of the list.
                model.append({type: 'icon-and-text'});
                model.append({type: 'icon-and-text'});
                model.append({type: 'icon-and-text'});

                // After the initial setup, disable the flag so scaffolding
                // doesn't run again on subsequent renders.
                model.set({__needsSetup: false});
            },
            appendComponentActions(el){
                const actionDiv = document.createElement("div");
                actionDiv.classList = 'component__actions';

                [
                    { htmlTag: 'button', classList: 'cmp-action remove-item-btn', title: __('Remove last item'), text: '-' },
                    { htmlTag: 'button', classList: 'cmp-action select-cmp', title: __('Select component'), text: 'L' },
                    { htmlTag: 'button', classList: 'cmp-action toggle-multiple-edit', title: __('Multiple Edit'), text: __('ME', 'multiple_edit_short') },
                    { htmlTag: 'button', classList: 'cmp-action add-item-btn', title: __('Add item'), text: '+' },
                ].map( ({htmlTag, classList, title, text}) => {
                    const btn = document.createElement(htmlTag);
                    btn.classList = classList;
                    btn.setAttribute('title', title);
                    btn.appendChild(document.createTextNode(text));
                    actionDiv.appendChild(btn);
                });

                el.appendChild(actionDiv);
            },
            events: {
                'click .add-item-btn': 'addItem',
                'click .remove-item-btn': 'removeLastItem',
                'click .toggle-multiple-edit': 'toggleMultipleEdit',
            },
            addItem(){
                this.model.append({ type: 'icon-and-text' });
            },
            removeLastItem(){
                let confirm = window.confirm(__('Are you sure you want to remove the last item?', 'confirm_remove_last_item'));
                if(!confirm) return;
                const items = this.model.components();
                if(items.length > 0){
                    const lastItem = items.at(items.length - 1);
                    lastItem.remove();
                }
            },
            toggleMultipleEdit(){
                const multipleEditActive = this.model.get('__temp_multipleEdit') || false;
                this.model.set('__temp_multipleEdit', !multipleEditActive);

                if(!multipleEditActive) {
                    this.el.classList.add('multiple-edit-active');
                } else {
                    this.el.classList.remove('multiple-edit-active');
                }
            }
        }
    });

    // When an icon-and-text component is selected, if its parent icon list has multiple edit mode active, 
    // select all inner components of the same type as well so they can be edited together.
    editor.on('component:selected', (model) => {
        const iconListParent = model.closestType('icon-list');
        if(!iconListParent) return;
        if(iconListParent.get('__temp_multipleEdit')) {
            const sameTypeComponents = iconListParent.findType(model.get('type'));
            editor.select(sameTypeComponents);
        }
    });

    // When a datastore value changes for a component inside an icon list with multiple edit mode active, 
    // apply the same change to all inner components of the same type so they stay in sync.
    editor.on('datastoreChanged', (builder_comp_model, component) => {
        const iconListParent = component.closestType('icon-list');
        if(!iconListParent) return;
        if(iconListParent.get('__temp_multipleEdit')) {
            const sameTypeComponents = iconListParent.findType(component.get('type'));
            const datastoreChanged = builder_comp_model.datastore.changed;
            sameTypeComponents.forEach( cmp => {
                if(cmp.get('type') === component.get('type') && cmp !== component) {
                    const cmpDatastore = editor.getComponentDatastore(cmp);
                    Object.keys(datastoreChanged).forEach( key => {
                        // Update the datastore value for this component
                        cmpDatastore.set(key, datastoreChanged[key]);
                        // Manually trigger a re-render of the component 
                        // to reflect the updated datastore value
                        cmp.view.render();
                        // Clear the group view cache for this component 
                        // to ensure the updated datastore values are reflected in the sidenav
                        var vc = editorConfig.viewCache;
                        const compId = cmp.attributes.__tempID;
                        if (vc && vc[compId]) delete vc[compId];
                    });
                }
            });
        }
    });

    // When an icon-and-text component is added inside an icon list, set it to be draggable only within the context of the list.
    editor.on('component:add', (model) => {
        if (model.parent() && model.parent().getClasses().includes('icon-list')) {
            const iconAndText = model;
            iconAndText.set('draggable', '[data-gjs-type="icon-list"]');

            // Delegate the move event to the icon list to move around the list as a whole.
            // While this works, it breaks the ability to reorder icon-and-text components within the list:
            // iconAndText.set('delegate', { move: (cmp) => cmp.closestType('icon-list') });
        }
    });

    // Set default values for nested components when they are created
    UltimateFields.addFilter('before_group_create', function(args) {
        const comp = args.component;
        if (!comp) return;

        const type = comp.get('type');

        // Set default icon aligment for icon-and-text
        if (type === 'icon-and-text' && comp.parent()?.getClasses().includes('icon-list')) {
            if (!args.datastore.get('icon_alignment')) {
                args.datastore.set({icon_alignment: 'center', content_alignment: 'center'});
            }
        }

        // Set default icon for icon-box inside icon-and-text components
        if (type === 'icon-box' && comp.parent()?.parent()?.parent()?.getClasses().includes('icon-list')) {
            if (!args.datastore.get('icon')) {
                const iconList = comp.closestType('icon-list');
                const iconListDatastore = editor.getComponentDatastore(iconList);
                const defaultIcon = iconListDatastore.get('default_icon') || 'bi-check-lg';
                args.datastore.set({icon: defaultIcon});
            }
        }

        // Set default content for text-editor inside icon list items
        if (type === 'text-editor' && comp.parent()?.parent()?.parent()?.getClasses().includes('icon-list')) {
            if (!args.datastore.get('content')) {
                args.datastore.set({content: 'Lorem ipsum dolor sit amet'});
            }
        }
    });
}