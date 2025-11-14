window.gjsSection = function(editor) {
    const domc = editor.DomComponents;

    // Make classes private
    const privateCls = ['.page-module', '.page-module--layout1', '.page-module--layout2', '.page-module--layout3'];
    editor.on('selector:add', selector => privateCls.indexOf(selector.getFullName()) >= 0 && selector.set('private', 1));

    // Command to update section layout
    // editor.Commands.add('update-section-layout', (editor, sender, options = {}) => {
    //     let component = options.component,
    //         layout = options.layout;
    //     component.set({ layout: layout });
    // });

    // Layout options for the section
    // const layoutOptions = [
    //     { id: 'layout1', name: 'Default' },
    //     { id: 'layout2', name: 'Full Width' },
    //     { id: 'layout3', name: 'Full Width Stretched' }
    // ];

    // Add the section type to the DomComponents
    domc.addType('section', {
        isComponent: el => el.classList && el.classList.contains('page-module'),
        model: {
            defaults: {
                name: 'Section',
                tagName: 'div',
                draggable: function (target, destination) {
                    console.log('target', destination);
                    return (destination.attributes.type) == 'container';
                },
                // layout: 'layout1',
                attributes: {
                    class: 'page-module',
                },
                // styles:`
                //     .page-module{
                //         padding: 40px 0px 40px 0px;
                //     }
                // `,
                // traits: [
                //     'id',
                //     {
                //         type: 'select',
                //         label: 'Layout',
                //         name: 'layout',
                //         changeProp: 1,
                //         options: layoutOptions,
                //     }
                // ],
                // contextMenu: function (component) {
                //     let layoutActions = {
                //         type: 'options',
                //         title: 'LAYOUT',
                //         options: []
                //     };
                //     layoutOptions.forEach((l) => {
                //         layoutActions.options.push({
                //             type: 'button',
                //             command: 'update-section-layout',
                //             args: { layout: l.id },
                //             label: l.name.toUpperCase(),
                //             class: () => {
                //                 const layout = component.get('layout');
                //                 return (l.id == layout) ? 'active' : '';
                //             }
                //         });
                //     });

                //     return [layoutActions];
                // }
            },
            // init: function () {
            //     this.on('change:layout', this.handleLayoutChange);
            // },
            // handleLayoutChange: function () {
            //     this.addAttributes({ 'class': `page-module page-module--${this.get('layout')}` });
            // }
        },
        view: {
            onRender({el, model}) {
                const editorConfig = editor.getConfig(),
                	temporalCompStore = editorConfig.temporalCompStore || {},
					__tempID = model.get('__tempID');

				if (__tempID && temporalCompStore[__tempID]) {
					const datastore = temporalCompStore[__tempID].datastore;
					if (datastore) {
                        const common_settings_wrapper = datastore.get('common_settings_wrapper') || {};
                        const settings = common_settings_wrapper.settings || {};
                        
                        // Apply common settings styles
                        if (settings.layout && settings.layout.use) {
                            const layout = settings.layout.key;
                            if (layout === 'layout2' || layout === 'layout3') {
                                el.classList.add('full-width');
                            } else {
                                el.classList.remove('full-width');
                            }
                            el.classList.add(layout);
                        }
                    }
                }
            }
        }
    });

    // Add a block in structure category
    editor.Blocks.add('section', {
        label: 'Section',
        media: '<i class="dashicons dashicons-align-wide"></i>',
        category: 'Structure',
        content: { type: 'section' }
    });
}