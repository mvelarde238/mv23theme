window.gjsIconAndText = function(editor) {
    const domc = editor.DomComponents;
    const compClass = `icon-and-text`;
    const defaultComponents = [
        { 
            type: 'icon-wrapper',
            classes: ['icon-wrapper'],
            components: [
                { type: 'comp_icon' }
            ]
        },
        {
            type: 'comp-wrapper', 
            classes: ['comp-wrapper','content-wrapper'],
            selectable: false,
            removable: false,
            draggable: false,
            copyable: false,
            components: [
                { type: 'comp_text_editor' },
            ]
        }
    ];

    // Define the icon wrapper component
    domc.addType('icon-wrapper', {
        model: {
            defaults: {
                tagName: 'div',
                name: 'Icon Wrapper',
                classes: ['icon-wrapper'],
                draggable: false,
                droppable: false,
                selectable: false,
                hoverable: false,
                stylable: false
            }
        },
    });

    // Define the icon component
    domc.addType('comp_icon', {
        model: {
            defaults: {
                tagName: 'div',
                name: 'Icon',
                classes: ['icon-cmp'],
                draggable: false,
                droppable: false,
                selectable: false,
                hoverable: false
            }
        },
    });

    // Define the component
    domc.addType(compClass, {
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: 'Icon and Text',
                tagName: 'div',
                classes: [compClass,'component','icon--left'],
                components: defaultComponents,
            },
        },
        view: {
            onRender({ el, model }) {
                const datastore = editor.getComponentDatastore(model);
                if (datastore) {
                    const { isource, iposition, ialignment, horizontal_alignment, content_alignment } = datastore.toJSON();
                    const iconCmp = model.findType('comp_icon')[0];

                    if (isource === 'icon') {
                        iconCmp.getEl().innerHTML = `<i class="${datastore.get('iname')}"></i>`;
                    }
                    else if (isource === 'image') {
                        const _image_id = datastore.get('iimage');
                        const prepared_file_object = window.UF_Editor?.getPreparedFileObject(_image_id);
                        const image_url = prepared_file_object ? prepared_file_object.get("url") : "";
                        iconCmp.getEl().innerHTML = `<img src="${image_url}" alt="" />`;
                    }

                    // Set position class
                    el.classList.remove('icon--left', 'icon--top', 'icon--right');
                    el.classList.add(`icon--${iposition}`);

                    // Set icon alignment styles
                    const iconWrapper = model.findType('icon-wrapper')[0];
                    const aligment_prop = iposition === 'top' ? 'justifyContent' : 'alignItems';
                    iconWrapper.getEl().style[aligment_prop] = ialignment;

                    // Set content alignment
                    if( iposition !== 'top' ) el.style.alignItems = content_alignment;

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