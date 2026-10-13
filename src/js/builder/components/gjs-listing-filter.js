window.gjsListingFilter = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'listing-filter';
    
    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const compName = __('Listing Filter');

    // Define filters components 
    ['search','month','year','postmeta','taxonomy','submit'].forEach(function(filter) {
        let filterType = filter+'-filter';
        let filterName = filter.charAt(0).toUpperCase() + filter.slice(1) + ' Filter';

        domc.addType(filterType, {
            extend: 'comp-base',
            isComponent: el => el.classList && el.classList.contains(filterType),
            model: {
                defaults: {
                    name: __(filterName),
                    tagName: 'div',
                    classes: [filterType,'field-wrapper'],
                    // draggable: '[data-gjs-type="listing-filter"]' // esto no me permite crear estructuras dentro de columnas
                },
            },
        });
    });

    // Define the component
    domc.addType(compClass, {
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: compName,
                tagName: 'div',
                classes: [compClass,'component'],
                droppable: true,
                __needsSetup: true,
                styles: `
                    .${compClass} {
                        display: flex;
                        flex-wrap: wrap;
                        flex-direction: row;
                        gap: 6px;
                    }
                `,
            },
        },
        view: {
            onRender({ el, model }) {
                if (model.get('__needsSetup')) {
                    this.initialSetup({ model });
                }
            },
            initialSetup({ model }) {
                // If listing filter is empty, add a search and submit filter by default
                if (!model.components().length) {
                    model.append({ type: 'search-filter' });
                    model.append({ type: 'submit-filter' });
                }

                model.set({ __needsSetup: false });
            },
        }
    });

    // Remove the component styles before saving as they are only needed to be presented in the style manager
    editor.on('builder:before-save-editor', () => {
        const css = editor.Css;
        css.remove(`.${compClass}`);
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

    ufEditor.renderSelect = function( selectClass, options, includeAll, allLabel ) {
        var html = '<select class="' + selectClass + '">';

        if ( includeAll ) {
            var defaultAll = ( typeof includeAll === 'string' ) ? includeAll : 'All';
            var allText = allLabel || defaultAll;
            html += '<option value="">' + ufEditor.escapeHtml( allText ) + '</option>';
        }

        _.each( options, function( option ) {
            html += '<option value="' + ufEditor.escapeHtml( option.value ) + '">' + ufEditor.escapeHtml( option.label ) + '</option>';
        } );

        html += '</select>';
        return html;
    }

    ufEditor.renderChoices = function( inputType, inputName, options, allLabel ) {
        var html = '<div class="tags-wrapper">';

        if ( inputType === 'radio' && allLabel !== false ) {
            var allText = allLabel || 'All';
            html += '<label class="tag-label">';
            html += '<input type="radio" name="' + ufEditor.escapeHtml( inputName ) + '" value="" checked>';
            html += ' <span>' + ufEditor.escapeHtml( allText ) + '</span>';
            html += '</label>';
        }

        _.each( options, function( option, index ) {
            var checked = ( inputType === 'radio' && allLabel === false && index === 0 ) ? ' checked' : '';
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
        var html = '';

        if ( label ) {
            html += '<span class="field-desc">' + ufEditor.escapeHtml( label ) + '</span>';
        }

        html += content;

        return html;
    }

    ufEditor.renderListingFilterTaxonomyField = function( filterData ) {
        var taxSlug = filterData.taxonomy;
        var displayType = filterData.display_type || 'select';
        var allText = filterData.all_text || 'All';

        var label = filterData.hide_label ? '' : ( filterData.label || ufEditor.slugToLabel( taxSlug, 'Taxonomy' ).toUpperCase() );
        var items = ufEditor.getListingFilterOptionItems();

        if ( displayType === 'radio' ) {
            return ufEditor.renderFieldWrapper( label, ufEditor.renderChoices( 'radio', 'taxonomies[' + ( taxSlug || 'taxonomy' ) + ']', items, allText ) );
        }

        if ( displayType === 'checkboxes' ) {
            return ufEditor.renderFieldWrapper( label, ufEditor.renderChoices( 'checkbox', 'taxonomies[' + ( taxSlug || 'taxonomy' ) + ']', items ) );
        }

        return ufEditor.renderFieldWrapper( label, ufEditor.renderSelect( 'listing-filter__term-select', items, true, allText ) );
    }

    ufEditor.renderListingFilterPostMeta = function( filterData ) {
        var displayType = filterData.display_type || 'text';
        var metaKey = filterData.meta_key || 'custom_field';
        var allText = filterData.all_text || 'All';
        var label = filterData.hide_label ? '' : ( filterData.label || ufEditor.slugToLabel( metaKey, 'POST META' ) );
        var items = ufEditor.getListingFilterOptionItems( filterData.options );

        if ( displayType === 'number_range' ) {
            return ufEditor.renderFieldWrapper( label, '<div class="number-range-wrapper"><input type="number" class="listing-filter__number-input" placeholder="Min"><input type="number" class="listing-filter__number-input" placeholder="Max"></div>' );
        }

        if ( displayType === 'select' ) {
            return ufEditor.renderFieldWrapper( label, ufEditor.renderSelect( 'listing-filter__custom-select', items, true, allText ) );
        }

        if ( displayType === 'radio' ) {
            return ufEditor.renderFieldWrapper( label, ufEditor.renderChoices( 'radio', 'custom[' + metaKey + ']', items, allText ) );
        }

        if ( displayType === 'checkboxes' ) {
            return ufEditor.renderFieldWrapper( label, ufEditor.renderChoices( 'checkbox', 'custom[' + metaKey + ']', items ) );
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
};