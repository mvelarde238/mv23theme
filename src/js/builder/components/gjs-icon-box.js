window.gjsIconBox = function(editor) {
    const domc = editor.DomComponents;

    const __ = editor.createTranslator(editor);
    const compName = __('Icon Box');
    const compClass = 'icon-box';

    domc.addType(compClass, {
        isComponent: el => el.classList && el.classList.contains('icon-box'),
        model: {
            defaults: {
                name: compName,
                tagName: 'div',
                classes: ['icon-box', 'component'],
                droppable: false,
                badgable: false,
            },
        },
        view: {
            onRender({ el, model }) {
                const datastore = editor.getComponentDatastore(model);
                if (datastore) {
                    const { source, icon, image } = datastore.toJSON();

                    if (source === 'icon' && icon) {
                        const iconPrefix = (icon && icon.startsWith('bi-')) ? 'bi' : 'fa';
                        el.innerHTML = `<i class="icon-box__icon ${iconPrefix} ${icon}"></i>`;
                    }
                    else if (source === 'image' && image) {
                        const prepared_file_object = editor.getPreparedFileObject(image);
                        if (prepared_file_object) {
                            const image_url = prepared_file_object.get("url");
                            el.innerHTML = `<img class="icon-box__icon" src="${image_url}" alt="" />`;
                        } else {
                            editor.getPreparedFileObjectAsync(image).then(file_object => {
                                if (file_object) {
                                    const image_url = file_object.get("url");
                                    el.innerHTML = `<img class="icon-box__icon" src="${image_url}" alt="" />`;
                                }
                            });
                        }
                    }
                }
            },
            // events: {
                // dblclick: 'onActive'
            // },
            // onActive() {
                // const datastore = editor.getComponentDatastore(this.model);
                // if (datastore) {
                    // const source = datastore.get('source');
                    // const builderCompModel = editor.getBuilderCompModel(this.model);
                    // const fields = builderCompModel.get('fields') || {};
	                // const fields_models = fields.models || [];
	                // const field_model = fields_models.find(fm => fm.get('name') == source);
	                // if (field_model) {
                    //     console.log(field_model);
                    //     if (source === 'icon') {
                            
                    //     } else if (source === 'image') {
                            
                    //     }
                    // }
                // }
            // }
        }
    });
};