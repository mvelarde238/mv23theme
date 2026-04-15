window.gjsIconAndText = function(editor) {
    const domc = editor.DomComponents;

    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const compName = __('Icon and Text');

    const compClass = `icon-and-text`;
    const defaultComponents = [
        { 
            type: 'icon-wrapper',
            classes: ['icon-wrapper'],
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
            classes: ['components-wrapper','content-wrapper'],
            removable: false,
            draggable: false,
            copyable: false,
            components: [
                { type: 'text-editor' },
            ]
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
                components: defaultComponents,
            },
        },
        view: {
            onRender({ el, model }) {
                // get components-wrapper and ensure it has the proper classes and properties
                const componentsWrapper = model.findType('components-wrapper')[0];
                if (componentsWrapper) {
                    componentsWrapper.getEl().classList.add('content-wrapper');
                    componentsWrapper.set('removable', false);
                    componentsWrapper.set('draggable', false);
                    componentsWrapper.set('copyable', false);
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
            events: {
                dblclick: 'onActive'
            },
            onActive() {
                editor.runCommand('open-datastore');
            }
        }
    });
}