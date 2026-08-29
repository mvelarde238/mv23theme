window.gjsTestimonial = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'testimonial';

    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const compName = __('Testimonial');
    const defaultInfo = __('<strong>John Doe</strong><br>CEO, Company', 'default_testimonial_info');

    // Behavioral properties that should be controlled by code, not persisted in saved JSON.
    // Used by both ensureComponentStructure (to reset stale values) and builder_component_cleanup (to strip on save).
    const unwantedProps = ['removable', 'copyable', 'draggable', 'selectable', 'badgable', 'propagate', 'resizable', 'droppable', 'delegate', 'name'];

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
                draggable: '.testimonial'
            },
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
                components: defaultComponents,
                __needsSetup: true, // Flag to track whether initial scaffolding (text-editors, icon defaults) is needed
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

                if(model.get('__needsSetup')) {
                    this.extra_customizations_on_initial_render({el, model});
                }
            },
            extra_customizations_on_initial_render({el, model}) {
                const header = model.findType('testimonial-header')[0];
                if (header){
                    // add a text editor to the info wrapper
                    const infoWrapper = header.findType('components-wrapper')[0];
                    if (infoWrapper) {
                        infoWrapper.append({type: 'text-editor'});
                    }
                }

                // get testimonial body and add a text editor
                const body = model.find('.testimonial__body')[0];
                if (body) {
                    body.append({type: 'text-editor'});
                }

                // After the initial setup, disable the flag so scaffolding
                // doesn't run again on subsequent renders.
                model.set({__needsSetup: false});
            }
        }
    });

    editor.on('component:add', (model) => {
        if (model.parent() && model.parent().getClasses().includes('testimonial__info')) {
            model.set('delegate', { move: (cmp) => cmp.closestType('components-wrapper') });
        }
    });

    UltimateFields.addFilter('before_group_create', function(args) {
        const comp = args.component;
        if (!comp) return;

        const type = comp.get('type');

        // Set default icon for quote icon inside testimonial
        if (type === 'icon-box' && comp.getClasses().includes('testimonial__quote-icon')) {
            if (!args.datastore.get('icon')) {
                args.datastore.set('icon', 'fa-quote-right');
            }
        }

        // Set default content for text-editor inside testimonial__info
        if (type === 'text-editor' && comp.parent()?.getClasses().includes('testimonial__info')) {
            if (!args.datastore.get('content')) {
                const testimonial = comp.closestType(compClass);
                if (testimonial && testimonial.get('__needsSetup')) {
                    args.datastore.set('content', defaultInfo);
                } else {
                    args.datastore.set('content', 'Lorem ipsum dolor sit amet.');
                }
            }
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
};