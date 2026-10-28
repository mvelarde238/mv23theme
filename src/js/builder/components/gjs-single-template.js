window.gjsSingleTemplate = function (editor, options) {
    const domc = editor.DomComponents;

    // Labels for the ui, using the editor's translator for internationalization.
    const __ = editor.createTranslator(editor, 'ultimate_builder');
    const dragPlaceholderText = __('Drag a component here','drag_a_component_here');

    // add .is-dimmed class to all components inside the single-template container but keep the editable areas focused
    function focusEditableArea(el) {
        // Dim all components inside the single-template container
        const allComponents = el.querySelectorAll('.component');
        allComponents.forEach(component => component.classList.add('is-dimmed'));

        // Keep the editable areas focused (remove dimming)
        const editableAreas = el.querySelectorAll('.editable-area-slot');
        editableAreas.forEach(area => {
            let currentElement = area;
            while (currentElement && !currentElement.classList.contains('single-template')) {
                if (currentElement.classList.contains('component')) {
                    currentElement.classList.remove('is-dimmed');
                }
                currentElement = currentElement.parentElement;
            }
        });
    }

    domc.addType('single-template', {
        extend: 'async-component-abstract',
        model: {
            defaults: {
                name: 'Single Template',
                tagName: 'div',
                classes: ['single-template'],
                stylable: false,
                hoverable: false,
                droppable: false,
                savable: false,
                __onSuccessCallback: (response, model, editor, datastore) => {
                    const el = model.getEl();                        
                    el.innerHTML = response.data;

                    // Focus the editable areas within the single-template container
                    focusEditableArea(el);
                    // Handle datastore data after focusing editable areas
                    model.getView().handle_datastore_data();
                    
                    const editableAreas = editor.getWrapper().findType('editable-area');
                    const slots = Array.from(el.querySelectorAll('.editable-area-slot'));
                    const pageContent = editor.getWrapper().findType('page-content')[0];

                    // 1. Insert every editable-area into its corresponding slot, 
                    // or move it to page-content if the slot doesn't exist
                    editableAreas.forEach(area => {
                        const slotId = area.get('slotId');
                        const slot = slots.find(s => s.getAttribute('data-slot-id') === slotId);
                        let areaEl = area.getEl();
                        
                        if (slot) {
                            slot.appendChild(areaEl);
                        } else if (pageContent) {
                            pageContent.append(area, { noUndo: true });
                            areaEl = area.getEl();
                            areaEl.classList.add('discarded-area','highlight-inner-message');
                        }
                        areaEl.setAttribute('data-slot-id', slotId);
                    });
                
                    // 2. Verify that each slot has an editable-area; if not, create a new one.
                    // noUndo keeps the undo stack clean, but changesCount (used by beforeunload)
                    // is still bumped asynchronously, so it is pinned to its previous value.
                    const em = editor.getModel();
                    const baseCount = em.get('changesCount') || 0;
                    const pinCount = () => {
                        if ((em.get('changesCount') || 0) > baseCount) em.set('changesCount', baseCount);
                    };
                    em.on('change:changesCount', pinCount);

                    slots.forEach(slot => {
                        if (slot.querySelector('.editable-area')) return;

                        const slotId = slot.getAttribute('data-slot-id');
                        const [newArea] = model.append({
                            type: 'editable-area',
                            slotId: slotId
                        }, { noUndo: true });
                        const newAreaEl = newArea.getEl();
                        newAreaEl.setAttribute('data-slot-id', slotId);
                        slot.appendChild(newAreaEl);
                    });

                    pinCount();
                    setTimeout(() => {
                        pinCount();
                        em.off('change:changesCount', pinCount);
                    }, 300);
                }
            },
        },
        view: {
            custom_datastore_change_callback(changed) {
                const model = this.model;
                
                // Ignore changes that only affect __tab (tab switching)
                const changed_keys = Object.keys(changed);
                if (changed_keys.length && changed_keys[0] === '__tab') return;

                this.handle_datastore_data();
            },
            handle_datastore_data() {
                const model = this.model;

                const datastore = editor.getComponentDatastore(model);
                if (datastore) {
                    const data = datastore.toJSON();
                    // if full focus mode is active, add a class to handle full focus mode
                    if( data.full_focus_mode ){
                        model.getEl().classList.add('full-focus-mode');
                    } else {
                        model.getEl().classList.remove('full-focus-mode');
                    }
                }
            }
        }
    });

    domc.addType('editable-area-slot', {
        extend: 'comp-base',
        model: {
            defaults: {
                name: 'Editable Area Slot',
                tagName: 'div',
                classes: ['editable-area-slot'],
                stylable: false,
                copyable: false,
                droppable: false,
                savable: false
            },
        },
    });

    domc.addType('editable-area', {
        model: {
            defaults: {
                name: 'Editable Area',
                tagName: 'div',
                classes: ['editable-area','components-wrapper'],
                stylable: false,
                removable: false,
                draggable: false,
                copyable: false,
                selectable: false,
                savable: false
            },
        },
        view: {
            onRender({ el, model }) {
                this.showPlaceholder();
            },
            showPlaceholder() {
                const dragPlaceholder = document.createElement('div');
                dragPlaceholder.classList.add('drag-placeholder');
                dragPlaceholder.textContent = dragPlaceholderText;
                
                this.el.appendChild(dragPlaceholder);
            },
        }
    });

    // On builder loaded, customize the canvas
    editor.on('builder:loaded', () => {
        if( BUILDER_GLOBALS.use_single_template_structure ){
            const wrapper = editor.getWrapper();
            const page_content = wrapper.findType('page-content')[0];
            const single_template = wrapper.findType('single-template');

            if( !single_template.length ){
                page_content.append({ type: 'single-template' });
            }

            // Make page-content non-droppable and non-selectable
            // in order to work only within single-template
            const non_editable_attrs = { droppable: false, selectable: false, hoverable: false };
            wrapper.set(non_editable_attrs);
            page_content.set(non_editable_attrs);
        }
    });

    // Before saving the editor, remove any empty editable-area
    editor.on('builder:before-save-editor', () => {
        const wrapper = editor.getWrapper();
        const editableAreas = wrapper.findType('editable-area');
        editableAreas.forEach(area => {
            const innerComponents = area.components();
            if (!innerComponents.length) {
                area.remove({silent: true});
            }
        });
    });
};