window.gjsSingleTemplate = function (editor, options) {
    const domc = editor.DomComponents;

    // add custom css to canvasCss
    let config = editor.getConfig();
    config.canvasCss = config.canvasCss || '';
    config.canvasCss += `
        .single-template .component.is-dimmed { opacity: 0.4; }
        .discarded-area{ --placeholder-color: color-mix(in srgb, rgb(255 17 0) 18%, transparent); }
    `;
    editor.canvasCss = config.canvasCss;

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
                selectable: false,
                hoverable: false,
                __additionalData: {
                    post_id: BUILDER_GLOBALS.context.post.id || null
                },
                __onSuccessCallback: (response, model, editor, datastore) => {
                    const el = model.getEl();                        
                    el.innerHTML = response.data;
                    focusEditableArea(el);
                    
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
                            pageContent.append(area);
                            areaEl = area.getEl();
                            areaEl.classList.add('discarded-area','highlight-inner-message');
                        }
                        areaEl.setAttribute('data-slot-id', slotId);
                    });
                
                    // 2. Verify that each slot has an editable-area; if not, create a new one
                    slots.forEach(slot => {
                        if (slot.querySelector('.editable-area')) return;
                    
                        const slotId = slot.getAttribute('data-slot-id');
                        const [newArea] = model.append({
                            type: 'editable-area',
                            slotId: slotId
                        });
                        const newAreaEl = newArea.getEl();
                        newAreaEl.setAttribute('data-slot-id', slotId);
                        slot.appendChild(newAreaEl);
                    });
                }
            },
        },
    });

    domc.addType('editable-area-slot', {
        extend: 'comp-base',
        model: {
            defaults: {
                name: 'Editable Area Slot',
                tagName: 'div',
                classes: ['editable-area-slot'],
                stylable: false,
                droppable: false
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
                selectable: false
            },
        },
    });

    // On builder loaded, customize the canvas
    editor.on('builder:loaded', () => {
        if( BUILDER_GLOBALS.use_single_template_structure ){
            // alert('Managing single template structure');
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