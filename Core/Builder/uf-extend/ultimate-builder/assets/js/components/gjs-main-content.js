window.gjsMainContent = function (editor, options) {
    const domc = editor.DomComponents;

    domc.addType('main', {
        model: {
            defaults: {
                name: 'Main',
                tagName: 'main',
                classes: ['main','components-wrapper'],
                stylable: false,
                removable: false,
                copyable: false,
                draggable: false,
                badgable: false,
                highlightable: false,
                selectable: false,
                hoverable: false,
            },
        },
    });

    domc.addType('aside', {
        model: {
            defaults: {
                name: 'Aside',
                tagName: 'aside',
                classes: ['aside','components-wrapper'],
                stylable: false,
                removable: false,
                copyable: false,
                draggable: false,
                badgable: false,
                highlightable: false,
                selectable: false,
                hoverable: false,
            },
        },
    });

    domc.addType('main-content', {
        model: {
            defaults: {
                name: 'Main Content',
                tagName: 'div',
                classes: ['main-content','main-content--sidebar-right'],
                components: [
                    { type: 'main' },
                    { type: 'aside' }
                ]
            },
        },
        view: {
            onRender({el, model}) {
                // Initial handling of datastore data
                this.handle_datastore_data();
            },
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

                    const aside = model.findType('aside')[0];
                    
                    // Handle sidebar visibility
                    const aside_display = (data['template'] === 'main-content--sidebarless' ) ? 'none' : 'flex';
                    if(aside) aside.setStyle({ display: aside_display });

                    // Handle template
                    model.set('classes', ['main-content', data['template']]);
                }
            }
        }
    });
}