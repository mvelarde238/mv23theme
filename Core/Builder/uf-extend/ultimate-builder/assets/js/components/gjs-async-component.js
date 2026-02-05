window.gjsAsyncComponent = function (editor, options) {
    const domc = editor.DomComponents;

    domc.addType('async-component-abstract', {
        model: {
            defaults: {
                name: 'Async Component Abstract',
                tagName: 'div',
                classes: ['async-component-abstract'],
                __action: 'get_component_view',
                __additionalData: {},
                __additionalDataCallback: null,
            },
        },
        view: {
            onRender({el, model}) {
                const datastore = editor.getComponentDatastore(model);
                
				if (datastore) {
                    const data = datastore.toJSON();
                    data['action'] = model.get('__action');
                    Object.assign(data, model.get('__additionalData'));
                    if (typeof model.get('__additionalDataCallback') === 'function') {
                        const callbackData = model.get('__additionalDataCallback')(model, editor);
                        Object.assign(data, callbackData);
                    }

                    jQuery.ajax({
                        type: "POST",
                        dataType: "json",
                        url: MV23_GLOBALS.ajaxUrl,
                        data: data,
                        success: function(response) {
                            el.innerHTML = response.data;
                        },
                        error: function(xhr, status, error) {
                            console.error(`Error loading ${model.get('name')} component view:`, error);
                        }
                    });
                }
            },
        },
    });
}