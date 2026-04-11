window.gjsTestimonial = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'testimonial';

    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const compName = __('Testimonial');
    const defaultInfo = __('<strong>John Doe</strong><br>CEO, Company', 'default_testimonial_info');

    // Behavioral properties that should be controlled by code, not persisted in saved JSON.
    // Used by both ensureComponentStructure (to reset stale values) and builder_component_cleanup (to strip on save).
    const unwantedProps = ['removable', 'copyable', 'draggable', 'selectable', 'badgable', 'propagate', 'resizable', 'droppable', 'delegate'];

    // Default components for the testimonial, including a header with an image and info, and a body with a text editor
    const defaultComponents = [
        {
            type: 'testimonial-header',
            components: [
                {
                    type: 'figure',
                    classes: ['testimonial__image-wrapper'],
                    draggable: false,
                    removable: false,
                    copyable: false,
                    badgable: false,
                    propagate: ['draggable', 'removable', 'copyable', 'badgable'],
                    // delegate select to inner image component to avoid selecting the wrapper when trying to select the image
                    delegate: { select: (cmp) => cmp.findType('image-component')[0] },
                    components: [
                        {
                            type: 'image-component',
                            classes: ['testimonial__image'],
                            resizable: false,
                            badgable: false,
                            removable: false,
                            copyable: false,
                            draggable: false,
                        }
                    ]
                },
                {
                    type: 'components-wrapper',
                    name: 'Author Info',
                    removable: false,
                    copyable: false,
                    classes: ['components-wrapper', 'testimonial__info'],
                    draggable: '.testimonial__header, .testimonial',
                    // This component can be dragged between .testimonial__header and .testimonial root,
                    // so it may not be a direct child of its original parent. __movable tells
                    // ensureComponentStructure to search the entire composite tree instead of only
                    // direct children, and to never auto-create a duplicate if not found.
                    __movable: true, 
                },
                {
                    type: 'icon-box',
                    name: 'Quote Icon',
                    classes: ['testimonial__quote-icon'],
                    removable: false,
                    copyable: false,
                    draggable: false,
                    badgable: false,
                }
            ]
        },
        {
            type: 'components-wrapper',
            classes: ['components-wrapper', 'testimonial__body'],
            draggable: false,
            removable: false,
            copyable: false,
            components: [
                { type: 'text-editor' },
            ]
        }
    ];

    // Define the testimonial header component, which is a child of the main testimonial component
    domc.addType('testimonial-header', {
        isComponent: el => el.classList.contains('testimonial__header'),
        model: {
            defaults: {
                name: 'Header',
                tagName: 'div',
                classes: ['testimonial__header'],
                droppable: '.testimonial__info',
                removable: false,
                copyable: false,
                draggable: '.testimonial',
                __rendered: 0,
            },
        },
        view: {
            onRender({el, model}) {
                if (model.get('__rendered')) return;
                model.set('__rendered', 1);

                // get the image component and set default styles for it
                const image = model.findType('image-component')[0];
                if (image) {
                    editor.getComponentDatastore(image)?.set({aspect_ratio: '1/1'});
                }

                // get the quote icon and set a default icon for it
                const quoteIcon = model.findType('icon-box')[0];
                if (quoteIcon) {
                    editor.getComponentDatastore(quoteIcon)?.set({icon: 'fa-quote-right'});
                    quoteIcon.getView().render();
                }

                // get the info wrapper and add a text editor with default content
                const infoWrapper = model.findType('components-wrapper')[0];
                if (infoWrapper) {
                    const textEditor = infoWrapper.append({type: 'text-editor'});
                    editor.getComponentDatastore(textEditor[0])?.set({content: defaultInfo});
                    textEditor[0].getView().render();
                }
            }
        }
    });

    // Main component definition
    domc.addType(compClass, {
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: compName,
                tagName: 'div',
                droppable: '.testimonial__info, .testimonial__header',
                classes: [compClass, 'component'],
                components: defaultComponents
            }
        },
        view: {
            onRender({el, model}) {
                // Ensure the default structure is in place for the testimonial component.
                // Pass unwantedProps so stale behavioral props from old saved JSON get reset to GrapeJS defaults.
                editor.ensureComponentStructure(model, defaultComponents, unwantedProps);

                // Ensure the testimonial is rendered with the correct style
                const datastore = editor.getComponentDatastore(model);
                if (datastore) {
                    const { testimonial_style } = datastore.toJSON();
                    el.setAttribute('data-style', testimonial_style);
                }
            }
        }
    });

    editor.on('component:add', (model) => {
        if (model.parent() && model.parent().getClasses().includes('testimonial__info')) {
            model.set('delegate', { move: (cmp) => cmp.closestType('components-wrapper') });
        }
    });

    UltimateFields.addFilter('builder_component_cleanup', function(data) {
        if (data.component.type === compClass) {
            // Recursively strip behavioral props from nested children so they are never persisted in JSON.
            // These props are re-applied at runtime by ensureComponentStructure from defaultComponents.
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
}