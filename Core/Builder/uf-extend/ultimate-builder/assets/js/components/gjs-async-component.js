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
                __onSuccessCallback: null,
            },
        },
        view: {
            onRender({el, model}) {
                const asyncData = {},
                    datastore = editor.getComponentDatastore(model);
                
				if (datastore) {
                    Object.assign(asyncData, datastore.toJSON());
                }

                asyncData['type'] = model.get('type');
                asyncData['action'] = model.get('__action');
                Object.assign(asyncData, model.get('__additionalData'));
                
                // additional data via callback, must return an object
                if (typeof model.get('__additionalDataCallback') === 'function') {
                    const callbackData = model.get('__additionalDataCallback')(model, editor);
                    Object.assign(asyncData, callbackData);
                }

                jQuery.ajax({
                    type: "POST",
                    dataType: "json",
                    url: BUILDER_GLOBALS.ajax_url,
                    data: asyncData,
                    success: function(response) {
                        if (typeof model.get('__onSuccessCallback') === 'function') {
                            model.get('__onSuccessCallback')(response, model, editor);
                        } else {
                            el.innerHTML = response.data;
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(`Error loading ${model.get('name')} component view:`, error);
                    }
                });
            },
        },
    });
}