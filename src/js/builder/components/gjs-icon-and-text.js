window.gjsIconAndText = function(editor) {
    const domc = editor.DomComponents;

    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const compName = __('Icon and Text');

    const compClass = `icon-and-text`;
    const unwantedProps = [
        'removable', 'copyable', 'draggable', 'selectable',
        'badgable', 'propagate', 'resizable', 'droppable', 'delegate'
    ];
    const defaultComponents = [
        { 
            type: 'icon-wrapper',
            components: [
                { 
                    type: 'icon-box',
                    removable: false,
                    draggable: false,
                    copyable: false
                }
            ]
        },
        {
            type: 'components-wrapper', 
            removable: false,
            draggable: false,
            copyable: false,
            selectable: true,
        }
    ];

    // Define the icon wrapper component
    domc.addType('icon-wrapper', {
        model: {
            defaults: {
                tagName: 'div',
                name: __('Icon Wrapper'),
                classes: ['icon-wrapper'],
                draggable: false,
                droppable: false,
                selectable: false,
                hoverable: false,
                stylable: false
            }
        },
    });

    // Define the component
    domc.addType(compClass, {
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: compName,
                tagName: 'div',
                classes: [compClass,'component','icon--left'],
                droppable: false,
                components: defaultComponents,
                __needsSetup: true,
            },
        },
        view: {
            onRender({ el, model }) {
                // Ensure structure + reset stale props from old JSON
                editor.ensureComponentStructure(model, defaultComponents, unwantedProps);

                // Ensure content-wrapper class on the components-wrapper element
                const componentsWrapper = model.findType('components-wrapper')[0];
                if (componentsWrapper) {
                    componentsWrapper.getEl().classList.add('content-wrapper');
                }

                if (model.get('__needsSetup')) {
                    this.initialSetup({ model });
                }
                
                // get datastore values and update position/alignment styles
                const datastore = editor.getComponentDatastore(model);
                if (datastore) {
                    const { icon_position, icon_alignment, horizontal_alignment, content_alignment } = datastore.toJSON();

                    // Set position class
                    el.classList.remove('icon--left', 'icon--top', 'icon--right');
                    el.classList.add(`icon--${icon_position}`);

                    // Set icon alignment styles
                    const iconWrapper = model.findType('icon-wrapper')[0];
                    const aligment_prop = icon_position === 'top' ? 'justifyContent' : 'alignItems';
                    iconWrapper.getEl().style[aligment_prop] = icon_alignment;

                    // Set content alignment
                    if( icon_position !== 'top' ) el.style.alignItems = content_alignment;

                    // Set Horizontal alignment
                    if (horizontal_alignment) {
                        el.classList.add( `${horizontal_alignment}-all` );
                    }
                }
            },
            initialSetup({ model }) {
                // Add text-editor inside components-wrapper
                const contentWrapper = model.findType('components-wrapper')[0];
                if (contentWrapper && !contentWrapper.findType('text-editor').length) {
                    contentWrapper.append({ type: 'text-editor' });
                }

                model.set({ __needsSetup: false });
            },
            events: {
                dblclick: 'onActive'
            },
            onActive() {
                editor.runCommand('open-datastore');
            }
        }
    });

    // Clean behavioral props from JSON on save
    UltimateFields.addFilter('builder_component_cleanup', function(data) {
        if (data.component.type === compClass) {
            const cleanupNestedComponents = (obj) => {
                if (!obj || !Array.isArray(obj.components)) return;
                obj.components.forEach(child => {
                    unwantedProps.forEach(prop => delete child[prop]);
                    cleanupNestedComponents(child);
                });
            };
            cleanupNestedComponents(data.builderComponent);
        }
    });
};