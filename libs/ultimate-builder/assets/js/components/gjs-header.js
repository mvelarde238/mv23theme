window.gjsHeader = function (editor, options) {
    const domc = editor.DomComponents;
    const compClass = 'header';

    // Command to add dynamic header logo component
    editor.Commands.add('add-header-logo', (editor, sender, options = {}) => {
        let component = options.component;
        const headerContent = component.findType('header-content')[0];
        if (!headerContent) return;
        headerContent.append({
            type: 'header-logo',
        });

        editor.runCommand('gcm-close'); // Close the gjs context menu
    });

    // Function to create CSS rule arguments, including handling media queries for non-desktop devices
    function createCssRuleArgs(){
        // If the rule exists already, merge passed styles instead of replacing them.
        let ruleArgs = { addStyles: true };

        // Handle media queries for non-desktop devices
        const currentDevice = editor.Devices.getSelected();
        if (currentDevice && currentDevice.get('id') !== 'desktop') {
            ruleArgs.atRuleType = 'media';
            ruleArgs.atRuleParams = `(max-width: ${currentDevice.get('widthMedia')})`;
        }
        return ruleArgs;
    }

    // Command to edit sticky header CSS rules
    editor.Commands.add('edit-sticky-header-toggle', (editor, sender, options = {}) => {
        let component = options.component;

        // Toggle editing state
        const isEditingStickyHeader = component.get('__temp_sticky_header_editing');
        component.set('__temp_sticky_header_editing', !isEditingStickyHeader);
        
        // Get the component ID and prepare to create/select CSS rule
        const id = editor.getSelected().getId();
        let cssRule = null;
        let ruleArgs = createCssRuleArgs();

        // Create or get the CSS rule for the sticky header, depending on the editing state
        if (!isEditingStickyHeader) {
            cssRule = editor.Css.setRule(`#${id}.header--sticky`, {}, ruleArgs);
        } else {
            cssRule = editor.Css.setRule(`#${id}`, {}, ruleArgs);
        }

        // Select the rule in the CSS editor
        editor.Selectors.select(cssRule);
    });

    let notSelectableComponent = {
        tagName: 'div',
        droppable: false,
        stylable: false,
        removable: false,
        copyable: false,
        draggable: false,
        badgable: false,
        highlightable: false,
        selectable: false,
        hoverable: false,
    };

    domc.addType('header-logo', {
        extend: 'comp-base',
        model: {
            defaults: {
                name: 'Header Logo',
                tagName: 'div',
                classes: ['header-logo'],
                stylable: false,
                resizable: {
                    ratioDefault: true,
                    currentUnit: 1,
                    keepAutoWidth: true,
                    cl: false,
                    cr: false,
                    tl: false,
                    tc: false,
                    tr: false,
                    bl: false,
                    bc: true,
                    br: false,
                    maxDim: null,
                    minDim: 15
                },
            }
        },
    });

    domc.addType('header-content', {
        model: {
            defaults: Object.assign({}, notSelectableComponent, {
                name: 'Header Content',
                tagName: 'div',
                classes: ['header-content', 'container'],
                droppable: true,
            }),
        },
    });

    domc.addType(compClass, {
        model: {
            defaults: Object.assign({}, notSelectableComponent, {
                name: 'Header',
                tagName: 'section',
                classes: [compClass],
                stylable: true,
                selectable: true,
                __temp_sticky_header_editing: false,
                components: [
                    { type: 'header-content' }
                ],
                contextMenu: function (component) {
                    const isEditingStickyHeader = component.get('__temp_sticky_header_editing');
                    const editStickyHeaderLabel = isEditingStickyHeader ? 'TURN OFF STICKY HEADER EDITING' : 'TURN ON STICKY HEADER EDITING';

                    return [
                        {
                            type: 'button',
                            command: 'add-header-logo',
                            label: 'ADD DYNAMIC LOGO'
                        },
                        {
                            type: 'button',
                            command: 'edit-sticky-header-toggle',
                            class: ()=>{
                                const isEditingStickyHeader = component.get('__temp_sticky_header_editing');
                                return (isEditingStickyHeader) ? 'active' : '';
                            },
                            label: editStickyHeaderLabel,
                            rerender: { full:true },
                        }
                    ];
                }
            }),
        },
        view: {
            onRender({ el, model }) {
                // Init sticky header
                setTimeout(() => {
                    stickyHeader.init(
                        el,
                        editor.Canvas.getWindow(), 
                        BUILDER_GLOBALS.stickyHeaderBreakpoint
                    );
                }, 100);
            }
        }
    });

    // On builder loaded, append to wrapper component and customize the canvas
    editor.on('builder:loaded', () => {
        if ( BUILDER_GLOBALS.posttype !== 'header' ) return;

        const wrapper = editor.getWrapper();
        const main_container = wrapper.findType('container')[0];

        // add some demo elements to container: menu, image, heading, paragraph
        main_container.append({ 
            type: 'section',
            selectable: false,
            hoverable: false,
            droppable: false,
            layerable: false,
            propagate: [ 'selectable', 'hoverable', 'droppable', 'layerable' ],
            classes: ['page-module','demo-section'],
            components: [
                { type: 'oce-overlay' },
                { 
                    type: 'image-component',
                    classes: ['full-width'],
                    style: {
                        'aspect-ratio': '9/3',
                        width: '100vw',
                        'max-width': '100vw',
                    }
                },
                {
                    type: 'spacer',
                    style: {
                        height: '20px',
                    }
                },
                { type: 'heading' },
                { 
                    type: 'row-component',
                    components: [
                        { type: 'column', components: [ { type: 'text-editor' }, ] },
                        { type: 'column', components: [ { type: 'text-editor' }, ] },
                        { type: 'column', components: [ { type: 'text-editor' }, ] }
                    ]
                },
            ],
            style: {
                padding: '0px 0px 40px 0px',
                opacity: '0.5'
            }
        });

        // gjs wrapper shouldn't be selectable/removable
        wrapper.set({ 
            selectable: false, 
            removable: false,
            hoverable: false
        });
        main_container.set({
            droppable: false,
            selectable: false,
        });

        // Append header component if not exists
        const header_exists = wrapper.findType('header')[0];
        if (!header_exists) {
            wrapper.append({ type: 'header' }, { at: 0 });
        }

        // on component:selected check header __temp_sticky_header_editing property 
        // if is active add `.header--sticky #${id}` css rule to edit inner elements styles
        editor.on('component:selected', (component) => {
            const header = wrapper.findType('header')[0];
            if (!header) return;

            const isEditingStickyHeader = header.get('__temp_sticky_header_editing');
            if (!isEditingStickyHeader) return;

            const id = component.getId();
            let cssRule = null;
            let ruleArgs = createCssRuleArgs();
            const selector = (component.get('type') !== 'header') ? `.header--sticky #${id}` : `#${id}.header--sticky`;

            // Select the rule in the CSS editor
            setTimeout(() => {
                cssRule = editor.Css.setRule(selector, {}, ruleArgs);
                editor.Selectors.select(cssRule);
            }, 100); // delay to ensure component is fully selected and styles panel is updated
        });
    });

    // Before saving remove demo data that shouldn't be saved
    editor.on('builder:before-save-editor', () => {
        if ( BUILDER_GLOBALS.posttype !== 'header' ) return;

        const wrapper = editor.getWrapper();
        const container = wrapper.findType('container')[0];
        const sections = container.findType('section');
        editor.getModel().skip(() => {
            sections.forEach(section => {
                if (section.getEl().classList.contains('demo-section')) {
                    section.remove();
                }
            });
        });
    });
}