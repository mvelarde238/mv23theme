window.gjsOceComponents = ( editor ) => {
    const domc = editor.DomComponents;

    // Make classes private
    const privateCls = ['.oce-overlay', '.oce-element', '.oce-dynamic-content'];
    editor.on('selector:add', selector => privateCls.indexOf(selector.getFullName()) >= 0 && selector.set('private', 1));

    // Command to add dynamic content component
    editor.Commands.add('oce-add-dynamic-content', (editor, sender, options = {}) => {
        let component = options.component;
        const modalContent = component.findType('oce-modal-content')[0];
        if (!modalContent) return;
        modalContent.append({
            type: 'oce-dynamic-content',
        });

        editor.runCommand('gcm-close'); // Close the gjs context menu
    });

    // Define the overlay component
    domc.addType('oce-overlay', {
        model: {
            defaults: {
                tagName: 'div',
                name: 'Overlay',
                classes: ['oce-overlay'],
                draggable: false,
                droppable: false,
                selectable: false,
                hoverable: false,
                stylable: false
            }
        },
    });

    // Define the dynamic content component
    domc.addType('oce-dynamic-content', {
        model: {
            defaults: {
                tagName: 'div',
                name: 'Dynamic Content',
                classes: ['oce-dynamic-content'],
                droppable: false,
                stylable: false
            }
        },
        view: {
            onRender({ el, model }) {
                const editorConfig = editor.getConfig(),
                	temporalCompStore = editorConfig.temporalCompStore || {},
					__tempID = model.get('__tempID');

				if (__tempID && temporalCompStore[__tempID]) {
					const datastore = temporalCompStore[__tempID].datastore;
					if (datastore) {
                        const aspect_ratio = datastore?.get('aspect_ratio') || '1/1';
                        model.addStyle({ height: '' }); // Reset height
                        
                        if (aspect_ratio != 'custom') {
							model.addStyle({'aspect-ratio': aspect_ratio });
                        } else {
                            const custom_aspect_ratio = datastore?.get('custom_aspect_ratio');
                            if (custom_aspect_ratio) {
								model.addStyle({'aspect-ratio': custom_aspect_ratio });
                            }
                        }
                    }
                }
            }
        }
    });

    // Define the modal content component
    domc.addType('oce-modal-content', {
        model: {
            defaults: {
                tagName: 'div',
                name: 'Modal Content',
                classes: ['oce-modal-content'],
                selectable: false,
                hoverable: false,
                stylable: false,
                draggable: false,
                droppable: true
            }
        },
    });

    // Define the offcanvas component
    domc.addType('oce-element', {
        model: {
            defaults: {
                tagName: 'div',
                classes: ['oce-element'],
                removable: false,
                draggable: false,
                droppable: false,
                copyable: false,
                components: [
                    { type: 'oce-modal-content' }
                ],
                contextMenu: function (component) {
                    return [
                        {
                            type: 'button',
                            command: 'oce-add-dynamic-content',
                            // args: { foo: 'bar' },
                            label: 'ADD DYNAMIC CONTENT'
                        }
                    ];
                }
            }
        },
        view: {
            onRender({ el, model }) {
                const editorConfig = editor.getConfig(),
                	temporalCompStore = editorConfig.temporalCompStore || {},
					__tempID = model.get('__tempID');

				if (__tempID && temporalCompStore[__tempID]) {
					const datastore = temporalCompStore[__tempID].datastore;
					if (datastore) {
                        const type = datastore.get('oce_type');
                        el.classList.add(`oce-element--${type}`);

                        if (type === 'sidenav') {
                            const position = datastore.get('position') || 'left';
                            el.classList.add(`oce-element--sidenav-${position}`);
                        }

                        if (type !== 'bottom_sheet') {
                            const maxWidth = datastore.get('max_width');
                            if (maxWidth) {
                                el.style.maxWidth = isNaN(maxWidth) ? maxWidth : `${maxWidth}px`;
                            }
                        } else {
                            const maxHeight = datastore.get('max_height');
                            if (maxHeight) {
                                el.style.maxHeight = isNaN(maxHeight) ? maxHeight : `${maxHeight}px`;
                            }
                        }

                        const overlay_color = datastore.get('overlay_color');
                        if (overlay_color.use){
                            const color = overlay_color.color || '#000000'; // color is always hex
                            const alpha = overlay_color.alpha || 50; // alpha is always a number 0-100
                            const finalColor = `rgba(${parseInt(color.slice(1, 3), 16)}, ${parseInt(color.slice(3, 5), 16)}, ${parseInt(color.slice(5, 7), 16)}, ${alpha / 100})`;
                            const overlay = editor.getWrapper().findType('oce-overlay')[0]?.view.el;
                            console.log('Overlay element found:', overlay);
                            if (overlay) {
                                overlay.style.backgroundColor = finalColor;
                            }
                        }

                        const removeModalContentPadding = datastore.get('remove_modal_content_padding');
                        if (removeModalContentPadding) {
                            const modalContent = el.querySelector('.oce-modal-content');
                            if (modalContent) {
                                modalContent.style.padding = '0';
                            }
                        }
                    }
                }
            }
        }
    });

    // On builder loaded, customize the canvas
    editor.on('builder:loaded', () => {
        if ( BUILDER_GLOBALS.posttype !== 'offcanvas_element' ) return;

        const wrapper = editor.getWrapper();
        const container = wrapper.findType('container')[0];

        // add some demo elements to container: menu, image, heading, paragraph
        container.append({ 
            type: 'section',
            selectable: false,
            hoverable: false,
            droppable: false,
            propagate: [ 'selectable', 'hoverable', 'droppable' ],
            classes: ['demo-section'],
            components: [
                { type: 'menu' },
                { 
                    type: 'image2',
                    style: {
                        'aspect-ratio': '4 / 1',
                    }
                },
                { type: 'comp_heading' },
                { 
                    type: 'row2',
                    components: [
                        { type: 'column', components: [ { type: 'comp_text_editor' }, ] },
                        { type: 'column', components: [ { type: 'comp_text_editor' }, ] },
                        { type: 'column', components: [ { type: 'comp_text_editor' }, ] }
                    ]
                },
            ]
        });

        // Add oce-overlay and oce-element to the canvas if not present
        if (!wrapper.findType('oce-overlay').length) {
            container.append({ type: 'oce-overlay' });
        }
        if (!wrapper.findType('oce-element').length) {
            container.append({ type: 'oce-element' });
        }

        // gjs wrapper shouldn't be selectable/removable
        wrapper.set({ 
            selectable: false, 
            removable: false,
            hoverable: false
        });
        container.set({
            droppable: false,
            selectable: false,
        });
    });

    // Before saving remove demo data that shouldn't be saved
    editor.on('builder:before-save-editor', () => {
        if ( BUILDER_GLOBALS.posttype !== 'offcanvas_element' ) return;

        const wrapper = editor.getWrapper();
        const container = wrapper.findType('container')[0];
        const sections = container.findType('section');
        sections.forEach(section => {
            if (section.getEl().classList.contains('demo-section')) {
                section.remove({silent:true});
            }
        });
    });
}