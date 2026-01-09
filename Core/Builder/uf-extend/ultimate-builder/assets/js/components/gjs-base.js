window.gjsBase = function (editor) {
    const domc = editor.DomComponents;

    // Define the component
    domc.addType('comp-base', {
        model: {
            defaults: {
                tagName: 'div',
                classes: ['component'],
                draggable: true,
                droppable: false,
            }
        },
        view: {
            onRender({ el, model }) {
                const editorConfig = editor.getConfig(),
                    temporalCompStore = editorConfig.temporalCompStore || {},
                    componentId = this.model.attributes.__tempID;

                // On render show uf view template or a button to open datastore
                if (temporalCompStore[componentId]) {
                    let builder_comp_model = temporalCompStore[componentId];

                    const view_template = builder_comp_model.get('view_template');

                    if (view_template) {
                        const _view_template = _.template(view_template);
                        const datastore = temporalCompStore[componentId].datastore;

                        el.innerHTML = _view_template(datastore.toJSON());
                    } else {
                        const name = model.get('name');
                        const btn = document.createElement('button');
                        btn.classList.add('edit-btn');
                        btn.innerText = name;
                        el.appendChild(btn);
                    }
                }
            },
            events: {
                'click .edit-btn': 'onEditClick',
                // 'uf-sorted' : 'saveSort'
            },
            onEditClick: function (ev) {
                ev.stopPropagation();
                editor.select(this.model);
                editor.runCommand('open-datastore');
            },
            // saveSort: function () {
            //     var builder_comp_model = this.model.get('builder_comp_model');
            //     builder_comp_model.datastore.set('__index', $(this.el).index(), {
            //         silent: true
            //     });
            // }
        }
    });
}