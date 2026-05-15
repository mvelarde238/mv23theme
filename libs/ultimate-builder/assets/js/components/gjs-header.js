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

    // Switch CSS rule for the currently selected component based on sticky editing state
    function switchSelectedComponentCssRule(header) {
        const selected = editor.getSelected();
        if (!selected) return;

        const isSticky = header.get('__temp_sticky_header_editing');
        const id = selected.getId();
        let ruleArgs = createCssRuleArgs();
        let selector;

        if (isSticky) {
            selector = (selected.get('type') !== 'header') ? `.header--sticky #${id}` : `#${id}.header--sticky`;
        } else {
            selector = `#${id}`;
        }

        const cssRule = editor.Css.setRule(selector, {}, ruleArgs);
        editor.Selectors.select(cssRule);
    }

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
                    return [
                        {
                            type: 'button',
                            command: 'add-header-logo',
                            label: 'ADD DYNAMIC LOGO'
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

        // Get the main container inside the wrapper
        const wrapper = editor.getWrapper();
        let main_container = null;
        wrapper.get('components').each( component => {
            if( component.get('type') === 'container' ){
                main_container = component;
            }
        });
        if ( !main_container ) {
            console.error('Main container not found in wrapper, cannot initialize header component properly.');
            return;
        }

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

        // Scroll-driven sticky header editing: sync __temp_sticky_header_editing flag
        // with the actual scroll position in the canvas, so CSS rules automatically
        // switch between normal and .header--sticky selectors.
        const canvasWindow = editor.Canvas.getWindow();
        const breakpoint = BUILDER_GLOBALS.stickyHeaderBreakpoint;

        canvasWindow.addEventListener('scroll', () => {
            const header = wrapper.findType('header')[0];
            if (!header) return;

            const scrollTop = canvasWindow.pageYOffset;
            const isSticky = header.get('__temp_sticky_header_editing');

            if (scrollTop > breakpoint && !isSticky) {
                header.set('__temp_sticky_header_editing', true, { noUndo: true });
                switchSelectedComponentCssRule(header);
            }
            if (scrollTop <= breakpoint && isSticky) {
                header.set('__temp_sticky_header_editing', false, { noUndo: true });
                switchSelectedComponentCssRule(header);
            }
        });

        // On component:selected, if sticky editing is active, select the sticky CSS rule
        editor.on('component:selected', (component) => {
            const header = wrapper.findType('header')[0];
            if (!header) return;

            const isEditingStickyHeader = header.get('__temp_sticky_header_editing');
            if (!isEditingStickyHeader) return;

            // Delay to ensure component is fully selected and styles panel is updated
            setTimeout(() => {
                switchSelectedComponentCssRule(header);
            }, 100);
        });

        // On device change, re-apply sticky CSS rule if in sticky editing mode
        editor.on('change:device', () => {
            const header = wrapper.findType('header')[0];
            if (!header) return;

            const isSticky = header.get('__temp_sticky_header_editing');
            if (!isSticky) return;

            setTimeout(() => {
                switchSelectedComponentCssRule(header);
            }, 100);
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