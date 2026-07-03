window.gjsListingFilter = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'listing-filter';

    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const compName = __('Listing Filter');

    // Define the component
    domc.addType(compClass, {
        extend: 'async-component-abstract',
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: compName,
                tagName: 'div',
                classes: [compClass,'component'],
                __onSuccessCallback: (response, model, editor, datastore) => {
                    const el = model.getEl();
                    el.classList.remove('highlight-on-empty');
                    el.innerHTML = response.data;
                }
            },
        },
        view: {
            custom_datastore_change_callback(changed) {
                const model = this.model;
                
                // Ignore changes that only affect __tab (tab switching)
                const changed_keys = Object.keys(changed);
                if (changed_keys.length && changed_keys[0] === '__tab') return;

                const rerender_component_on_change = [
                    'filters',
                    'template',
                ];
                if ( rerender_component_on_change.includes( changed_keys[0] ) ) {
                    this.render();
                }

                // if (changed_keys[0] === 'posttype'){ ... }
                    
                // Update the taxonomy tags in the repeater based on the current post type
                const datastore = editor.getComponentDatastore(model);
                const { posttype } = datastore.toJSON();
                this.toggle_group_types_visibility(posttype);
            },
            toggle_group_types_visibility(posttype) {
                const model = this.model;
                const builder_comp_model = editor.getBuilderCompModel(model);
                if (builder_comp_model) {
                    const fields_models = builder_comp_model.get('fields') || {};
                    const filters_repeater = fields_models.find(fm => fm.get('name') == 'filters');
                    if (filters_repeater) {
                        const groupTypes = filters_repeater.groupTypes || [];
                        groupTypes.forEach(group => {
                            const group_id = group.get('id');
                            if (group_id.startsWith('taxfilter__')) {
                                const group_posttype = group_id.split('__')[1];
                                if (group_posttype !== posttype) {
                                    group.set('can_be_added', false);
                                } else {
                                    group.set('can_be_added', true);
                                }
                            }
                        });
                        filters_repeater.trigger('value-replaced');
                    }
                }
            }
        },
    });

    // on openDatastore, check if the component is a listing-filter and if so, call toggle_group_types_visibility with the current posttype
    editor.on('openDatastore', (builder_comp_model, component) => {
        if (component && component.get('type') === compClass) {
            const datastore = editor.getComponentDatastore(component);
            if (datastore) {
                const { posttype } = datastore.toJSON();
                setTimeout(() => {
                    component.view.toggle_group_types_visibility(posttype); 
                }, 0);
            }
        }
    });
}