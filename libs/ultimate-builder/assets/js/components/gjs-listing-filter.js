window.gjsListingFilter = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'listing-filter';
    
    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const compName = __('Listing Filter');

    // Define the component
    domc.addType(compClass, {
        extend: 'comp-base',
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: compName,
                tagName: 'div',
                classes: [compClass,'component'],
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

                // if (changed_keys[0] === 'xxx'){ ... }
                    
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

    // ***********************************************************************************
    // Utility functions for the listing filter component
    // ***********************************************************************************

    const ufEditor = window.UF_Editor || editor;
    window.UF_Editor = ufEditor;

    ufEditor.escapeHtml = function( value ) {
        return _.escape( value == null ? '' : String( value ) );
    }

    ufEditor.slugToLabel = function( value, fallback ) {
        if ( !value ) {
            return fallback;
        }

        return String( value )
            .replace( /[-_]+/g, ' ' )
            .replace( /\b\w/g, function( char ) {
                return char.toUpperCase();
            } );
    }

    ufEditor.getListingFilterSampleItems = function( count = 8 ) {
        const sampleItems = [];

        for ( let sampleIndex = 1; sampleIndex <= count; sampleIndex++ ) {
            sampleItems.push({
                value: sampleIndex,
                label: 'Item ' + sampleIndex,
            });
        }

        return sampleItems;
    }

    ufEditor.getListingFilterOptionItems = function( options, count = 8 ) {
        const items = [];
        const sampleItems = ufEditor.getListingFilterSampleItems(count);

        if ( Array.isArray( options ) ) {
            _.each( options, function( option ) {
                if ( items.length >= count ) {
                    return;
                }

                const optionLabel = option && ( option.label || option.value );
                if ( optionLabel ) {
                    items.push({
                        value: items.length + 1,
                        label: optionLabel,
                    });
                }
            } );
        }

        while ( items.length < count ) {
            items.push( sampleItems[items.length] );
        }

        return items;
    }

    ufEditor.renderSelect = function( selectClass, options, includeAll ) {
        var html = '<select class="' + selectClass + '">';

        if ( includeAll ) {
            html += '<option value="">All</option>';
        }

        _.each( options, function( option ) {
            html += '<option value="' + ufEditor.escapeHtml( option.value ) + '">' + ufEditor.escapeHtml( option.label ) + '</option>';
        } );

        html += '</select>';
        return html;
    }

    ufEditor.renderChoices = function( inputType, inputName, options ) {
        var html = '<div class="tags-wrapper">';

        _.each( options, function( option, index ) {
            var checked = inputType === 'radio' && index === 0 ? ' checked' : '';
            var name = inputType === 'checkbox' ? inputName + '[]' : inputName;
            html += '<label class="tag-label">';
            html += '<input type="' + inputType + '" name="' + ufEditor.escapeHtml( name ) + '" value="' + ufEditor.escapeHtml( option.value ) + '"' + checked + '>';
            html += ' <span>' + ufEditor.escapeHtml( option.label ) + '</span>';
            html += '</label>';
        } );

        html += '</div>';
        return html;
    }

    ufEditor.renderFieldWrapper = function( label, content ) {
        var html = '<div class="field-wrapper">';

        if ( label ) {
            html += '<span class="field-desc">' + ufEditor.escapeHtml( label ) + '</span>';
        }

        html += content;
        html += '</div>';

        return html;
    }

    ufEditor.renderListingFilterTaxonomyField = function( filterGroup, currentPosttype ) {
        var parts = String( filterGroup.__type || '' ).split( '__' );
        var cptSlug = parts[1] || '';
        var taxSlug = parts[2] || '';
        var displayType = filterGroup.display_type || 'select';

        if ( cptSlug && currentPosttype && cptSlug !== currentPosttype ) {
            return '';
        }

        var label = ufEditor.slugToLabel( taxSlug, 'Taxonomy' ).toUpperCase() + ':';
        var items = ufEditor.getListingFilterOptionItems();

        if ( displayType === 'radio' ) {
            return ufEditor.renderFieldWrapper( label, ufEditor.renderChoices( 'radio', 'taxonomy_' + taxSlug, items ) );
        }

        if ( displayType === 'checkboxes' ) {
            return ufEditor.renderFieldWrapper( label, ufEditor.renderChoices( 'checkbox', 'taxonomy_' + taxSlug, items ) );
        }

        return ufEditor.renderFieldWrapper( label, ufEditor.renderSelect( 'listing-filter__term-select', items, true ) );
    }

    ufEditor.renderListingFilterCustomField = function( filterGroup ) {
        var displayType = filterGroup.display_type || 'text';
        var metaKey = filterGroup.meta_key || 'custom_field';
        var label = filterGroup.label || ufEditor.slugToLabel( metaKey, 'Custom Field' );
        var items = ufEditor.getListingFilterOptionItems( filterGroup.options );

        if ( displayType === 'number_range' ) {
            return ufEditor.renderFieldWrapper( label, '<div class="number-range-wrapper"><input type="number" class="listing-filter__number-input" placeholder="Min"><input type="number" class="listing-filter__number-input" placeholder="Max"></div>' );
        }

        if ( displayType === 'select' ) {
            return ufEditor.renderFieldWrapper( label, ufEditor.renderSelect( 'listing-filter__custom-select', items, true ) );
        }

        if ( displayType === 'radio' ) {
            return ufEditor.renderFieldWrapper( label, ufEditor.renderChoices( 'radio', metaKey, items ) );
        }

        if ( displayType === 'checkboxes' ) {
            return ufEditor.renderFieldWrapper( label, ufEditor.renderChoices( 'checkbox', metaKey, items ) );
        }

        return ufEditor.renderFieldWrapper( label, '<input type="text" class="listing-filter__custom-text-input" value="" />' );
    }

    ufEditor.getListingFilterYearItems = function( count = 8 ) {
        var yearItems = [];
        var currentYear = new Date().getFullYear();

        for ( var yearIndex = 0; yearIndex < count; yearIndex++ ) {
            yearItems.push({
                value: currentYear - yearIndex,
                label: currentYear - yearIndex,
            });
        }

        return yearItems;
    }
}