window.gjsCompWrapper = function(editor) {
    const domc = editor.DomComponents;
    const compClass = 'comp-wrapper';

    // Make classes private
    const privateCls = ['.comp-wrapper'];
    editor.on('selector:add', selector => privateCls.indexOf(selector.getFullName()) >= 0 && selector.set('private', 1));

    // Define the component
    domc.addType(compClass, {
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: 'Wrapper',
                tagName: 'div',
                attributes: {
                    class: compClass,
                },
            },
        },
        view: {
            onRender({el, model}) {
                // set a min height to make it visible in the editor
                el.style.minHeight = '50px'; 

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
}