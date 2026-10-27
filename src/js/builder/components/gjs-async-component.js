window.gjsAsyncComponent = function (editor, options) {
    const domc = editor.DomComponents;

    domc.addType('async-component-abstract', {
        model: {
            defaults: {
                name: 'Async Component Abstract',
                tagName: 'div',
                classes: ['async-component-abstract'],
                droppable: false,
                __action: 'get_component_view',
                __additionalData: {},
                __additionalDataCallback: null,
                __onSuccessCallback: null,
                // true: only the inner content of the response root tag is loaded (avoids duplicating id/classes of the wrapper).
                // false: the full response is loaded as is.
                __contentOnly: true,
            },
        },
        view: {
            // Returns the html to inject in the component. Override it in the extending component's view to customize.
            // By default, only the inner content of the root tag of the response is used.
            getContent(html) {
                if (!this.model.get('__contentOnly') || typeof html !== 'string') return html;

                const tpl = document.createElement('template');
                tpl.innerHTML = html.trim();
                const root = tpl.content.children[0];
                const onlyRoot = tpl.content.children.length === 1 &&
                    Array.from(tpl.content.childNodes).every(n => n === root || n.nodeType === 8 || (n.nodeType === 3 && !n.textContent.trim()));

                return onlyRoot ? root.innerHTML : html;
            },

            onRender({el, model}) {
                const view = this;
                const asyncData = {},
                    datastore = editor.getComponentDatastore(model);
                
				if (datastore) {
                    Object.assign(asyncData, datastore.toJSON());
                }

                asyncData['type'] = model.get('type');
                asyncData['action'] = model.get('__action');
                const contextPostId = BUILDER_GLOBALS.context?.post?.id || BUILDER_GLOBALS.post_id;
                if (contextPostId) asyncData['post_id'] = contextPostId;
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
                    beforeSend: function() {
                        el.classList.add('is-loading');

                        if (typeof model.get('__beforeSendCallback') === 'function') {
                            model.get('__beforeSendCallback')(model, editor, datastore);
                        }
                    },
                    success: function(response) {
                        el.classList.remove('is-loading');
    
                        if (typeof model.get('__onSuccessCallback') === 'function') {
                            model.get('__onSuccessCallback')(response, model, editor, datastore);
                        } else {
                            el.innerHTML = view.getContent(response.data);
                        }
                    },
                    error: function(xhr, status, error) {
                        el.classList.remove('is-loading');
                        console.error(`Error loading ${model.get('name')} component view:`, error);
                    }
                });
            },
        },
    });
};