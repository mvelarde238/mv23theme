<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Core;

class Listing_Filter extends Component {

    public function __construct() {
		parent::__construct(
			'listing-filter',
			__( 'Listing Filter', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'custom_datastore_change_callback' => true
		);
    }

    public static function get_icon() {
        return 'bi-filter';
    }

	public static function get_fields() {
		$fields = array();

        $posttypes = Core::get_post_types();

        $fields[] = Field::create( 'tab', '_content_tab', __('Content','mv23theme') );
        $fields[] = Field::create( 'radio', 'posttype', __('Select a post type','mv23theme') )
            ->set_default_value('post')
            ->set_orientation( 'horizontal' )
            ->add_options( $posttypes );

        // Build filters repeater
        $filters_repeater = Field::create( 'repeater', 'filters' )
            ->set_chooser_type('tags')
            ->set_add_text( __('Add filter','mv23theme') )
            ->hide_label()
            ->set_default_value(array(
                array('__type' => 'search', '__hidden' => true),
                array('__type' => 'submit', '__hidden' => true),
            ))
            ->add_group('search', array(
                'title' => __('Search','mv23theme'),
                'fields' => array(
                    Field::create( 'text', 'label' )
                        ->hide_label()->set_prefix(__('Label','mv23theme'))
                        ->set_default_value(__('SEARCH:','mv23theme'))
                        ->set_placeholder(__('SEARCH:','mv23theme'))
                )
            ))
            ->add_group('month', array(
                'title' => __('Month','mv23theme'),
                'fields' => array()
            ))
            ->add_group('year', array(
                'title' => __('Year','mv23theme'),
                'fields' => array(
                    Field::create( 'number', 'initial_value' )
                        ->hide_label()->set_prefix(__('Initial value','mv23theme'))
                        ->set_maximum(date('Y'))
                        ->set_width(50),
                    Field::create( 'number', 'first_year')
                        ->hide_label()->set_prefix(__('First year','mv23theme'))
                        ->set_maximum(date('Y'))
                        ->set_default_value(2012)
                        ->set_width(50),
                )
            ));

        // Add taxonomy filters for each public post type
        foreach( $posttypes as $cpt_slug => $cpt_name ) {
            $taxonomies = get_object_taxonomies( $cpt_slug, 'objects' );
            foreach( $taxonomies as $tax_slug => $tax_object ) {
                if ( !$tax_object->public || !$tax_object->show_ui ) continue;
                $filters_repeater->add_group( 'taxfilter__'. $cpt_slug .'__'. $tax_slug, array(
                    'title'  => $tax_object->label,
                    'fields' => array(
                        Field::create( 'select', 'initial_value' )
                            ->hide_label()->set_prefix(__('Initial value','mv23theme'))
                            ->add_terms( $tax_slug )
                            ->set_width(50),
                        Field::create( 'select', 'display_type' )
                            ->hide_label()->set_prefix(__('Display type','mv23theme'))
                            ->add_options(array(
                                'select' => __('Select','mv23theme'),
                                'radio' => __('Radio buttons','mv23theme'),
                                'checkboxes' => __('Checkboxes','mv23theme'),
                            ))
                            ->set_width(50)
                    )
                ));
            }
        }

        // Add custom field filter group
        $filters_repeater->add_group('customfield', array(
            'title' => __('Custom Field','mv23theme'),
            'fields' => array(
                Field::create( 'text', 'meta_key' )
                    ->hide_label()->set_prefix(__('Meta Key','mv23theme')),
                Field::create( 'text', 'label' )
                    ->hide_label()->set_prefix(__('Label','mv23theme')),
                Field::create( 'select', 'display_type' )
                    ->hide_label()->set_prefix(__('Display type','mv23theme'))
                    ->add_options(array(
                        'text'         => __('Text input','mv23theme'),
                        'select'       => __('Select','mv23theme'),
                        'radio'        => __('Radio buttons','mv23theme'),
                        'checkboxes'   => __('Checkboxes','mv23theme'),
                        'number_range' => __('Number range','mv23theme'),
                    ))
                    ->set_default_value('text')
                    ->set_width(50),
                Field::create( 'repeater', 'options' )
                    ->set_add_text( __('Add option','mv23theme') )
                    ->hide_label()
                    ->add_dependency('display_type', array('select','radio','checkboxes'), 'IN')
                    ->add_group('option', array(
                        'fields' => array(
                            Field::create( 'text', 'value' )
                                ->hide_label()->set_prefix(__('Value','mv23theme'))->set_width(50),
                            Field::create( 'text', 'label' )
                                ->hide_label()->set_prefix(__('Label','mv23theme'))->set_width(50),
                        )
                    )),
            )
        ));

        // Add break group to allow users to break the filter into multiple rows
        $filters_repeater->add_group('break', array(
            'title' => __('Break','mv23theme'),
            'fields' => array()
        ));

        // Add submit button group
        $filters_repeater->add_group('submit', array(
            'title' => __('Submit','mv23theme'),
            'fields' => array(
                Field::create( 'text', 'text' )
                    ->hide_label()->set_prefix(__('Button text','mv23theme'))
                    ->set_default_value(__('FILTER','mv23theme')),
                Field::create( 'checkbox', 'show_reset_button' )
                    ->hide_label()
                    ->set_text(__('Show reset button','mv23theme')),
                Field::create( 'text', 'reset_text' )
                    ->hide_label()->set_prefix(__('Reset button text','mv23theme'))
                    ->set_default_value(__('RESET','mv23theme'))
                    ->add_dependency( 'show_reset_button', true )
            )
        ));

        $fields[] = $filters_repeater;

        // Add listing UID field
        $fields[] = Field::create('text', 'listing_uid')
            ->set_prefix(__('Listing UID','mv23theme'))
            ->set_description(__('Enter the Listing UID to interact with.','mv23theme'))
            ->hide_label()
            ->required();

        // Template tab
        $fields[] = Field::create( 'tab', '_template_tab', __('Template','mv23theme') );
        $fields[] = Field::create( 'radio', 'template' )
            ->set_description(__('Select a template for the filter.','mv23theme'))
            ->add_options(array(
                'horizontal' => __('Horizontal','mv23theme'),
                'vertical' => __('Vertical','mv23theme'),
            ))
            ->set_orientation( 'horizontal' )
            ->set_default_value('horizontal');

		return $fields;
	}

    /**
     * Get the form action URL based on the post type.
     * 
     * If there is not a listing connected, route the form submission to the appropriate archive page for the selected post type.
     * Core\Frontend\Archive_Search::customize_main_query() will handle the query modifications based on the filter settings.
     *
     * @param string $posttype The post type slug.
     * @return string The form action URL.
     */
    private static function get_form_action_url( $posttype ){
        $action_url = home_url();

        $post_type_obj = get_post_type_object($posttype);
        if($post_type_obj && $post_type_obj->has_archive){
            $action_url .= '/'.$posttype.'/';
        }

        if(WOOCOMMERCE_IS_ACTIVE && $posttype === 'product'){
            $action_url = get_permalink( wc_get_page_id( 'shop' ) );
        }

        if( $posttype === 'post' ){
            $posts_page_id = get_option('page_for_posts');
            if($posts_page_id){
                $action_url = get_permalink($posts_page_id);
            }
        }

        return $action_url;
    }

    private static function get_reset_field_attributes( $value = '', $checked = null ) {
        $attributes = ' data-reset-value="' . esc_attr( (string) $value ) . '"';

        if ( null !== $checked ) {
            $attributes .= ' data-reset-checked="' . ( $checked ? '1' : '0' ) . '"';
        }

        return $attributes;
    }
    
    public static function display( $args ) {
        if( Template_Engine::is_restricted( $args ) ) return;

        $the_filters = $args['filters'] ?? array();
        if( !is_array($the_filters) ) $the_filters = array();
        if( empty($the_filters) ) return;

        $posttype = $args['posttype'] ?? 'post';

        $listing_uid = $args['listing_uid'] ?? 'listing-'.uniqid();
        $args['additional_attributes']['data-listing-uid'] = esc_attr( $listing_uid );

        $template = $args['template'] ?? 'horizontal';
        $args['additional_attributes']['data-template'] = esc_attr( $template );

        $search = array( 'es' => 'BUSCAR:', 'en' => 'SEARCH:' );
        $all = array( 'es' => 'Todas', 'en' => 'All' );
        $allm = array( 'es' => 'Todos', 'en' => 'All' );
        $month = array( 'es' => 'MES:', 'en' => 'MONTH:' );
        $year = array( 'es' => 'AÑO:', 'en' => 'YEAR:' );
        $filter = array( 'es' => 'FILTRAR', 'en' => 'FILTER' );
        $reset = array( 'es' => 'REINICIAR', 'en' => 'RESET' );
        $months = array(
            'es' => array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'),
            'en' => array('January','February','March','April','May','June','July','August','September','October','November','December'),
        );
        $current_lang = (function_exists('pll_current_language')) ? pll_current_language() : 'es';

        // Get request values for filters
        $request_search = isset( $_GET['search'] ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) : '';
        $request_month = isset( $_GET['month'] ) ? sanitize_text_field( wp_unslash( $_GET['month'] ) ) : '';
        $request_year = isset( $_GET['year'] ) ? sanitize_text_field( wp_unslash( $_GET['year'] ) ) : '';
        $request_taxonomies = ( isset( $_GET['taxonomies'] ) && is_array( $_GET['taxonomies'] ) ) ? wp_unslash( $_GET['taxonomies'] ) : array();
        $request_custom = ( isset( $_GET['custom'] ) && is_array( $_GET['custom'] ) ) ? wp_unslash( $_GET['custom'] ) : array();

        ob_start();
        echo Template_Engine::component_wrapper('start', $args);
        $form_action_url = self::get_form_action_url( $posttype );
        echo '<form action="'.esc_url($form_action_url).'" method="GET">';
        
        echo '<div class="fields-row">';
        foreach( $the_filters as $filter_group ) {
            $filter_type = $filter_group['__type'] ?? '';
            switch ($filter_type) {
                case 'break':
                    echo '</div><div class="fields-row">';
                    break;

                case 'search':
                    $label = $filter_group['label'] ?? $search[$current_lang];
                    echo '<div class="field-wrapper">';
                    echo '<span class="field-desc">'.$label.'</span>';
                    echo '<input type="text" name="search" class="listing-filter__search-input" value="'.esc_attr( $request_search ).'"'.self::get_reset_field_attributes( '' ).'>';
                    echo '</div>';
                    break;

                case 'month':
                    $selected_month = $request_month;
                    echo '<div class="field-wrapper">';
                    echo '<span class="field-desc">'.$month[$current_lang].'</span>';
                    echo '<select name="month" class="listing-filter__month-select"'.self::get_reset_field_attributes( '' ).'>';
                    echo '<option value="" '.selected( $selected_month, '', false ).'>'.$allm[$current_lang].'</option>';
                    for ($i=0; $i < count($months[$current_lang]); $i++) {
                        $value = ( $i < 10 ) ? '0'.($i+1) : $i+1;  
                        echo '<option value="'.$value.'" '.selected( $selected_month, $value, false ).'>'.$months[$current_lang][$i].'</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                    break;

                case 'year':
                    $current_year = date('Y');
                    $first_year = ($filter_group['first_year'] == '') ? $current_year : $filter_group['first_year'];
                    $default_year = $filter_group['initial_value'] ?? '';
                    $selected_year = $request_year !== '' ? $request_year : $default_year;
                    echo '<div class="field-wrapper">';
                    echo '<span class="field-desc">'.$year[$current_lang].'</span>';
                    echo '<select name="year" class="listing-filter__year-select"'.self::get_reset_field_attributes( $default_year ).'>';
                    echo '<option value="" '.selected( $selected_year, '', false ).'>'.$allm[$current_lang].'</option>';
                    for ($i=$current_year; $i >= $first_year ; $i--) {
                        echo '<option value="'.$i.'" '.selected( $selected_year, $i, false).'>'.$i.'</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                    break;

                case 'submit':
                    $button_text = $filter_group['text'] ?? $filter[$current_lang];
                    $show_reset_button = ! empty( $filter_group['show_reset_button'] );
                    $reset_button_text = $filter_group['reset_text'] ?? $reset[$current_lang];
                    echo '<div class="field-wrapper">';
                    if( $show_reset_button ) {
                        echo '<span class="field-desc">';
                        echo '<button type="button" class="listing-filter__reset" data-reset-url="'.esc_url( $form_action_url ).'">'.esc_html( $reset_button_text ).' <i class="fa fa-undo"></i></button>';
                        echo '</span>';
                    }
                    echo '<button type="submit" class="listing-filter__submit btn btn--main-color btn-block">'.$button_text.'</button>';
                    echo '</div>';
                    break;

                case 'customfield':
                    $cf_meta_key = sanitize_key( $filter_group['meta_key'] ?? '' );
                    $cf_label    = $filter_group['label'] ?? '';
                    $cf_display  = $filter_group['display_type'] ?? 'text';
                    $cf_options  = $filter_group['options'] ?? array();
                    if( !$cf_meta_key ) break;

                    echo '<div class="field-wrapper">';
                    if( $cf_label ) echo '<span class="field-desc">'.esc_html($cf_label).'</span>';

                    if( $cf_display === 'number_range' ){
                        $range_value = isset( $request_custom[ $cf_meta_key ] ) && is_array( $request_custom[ $cf_meta_key ] ) ? $request_custom[ $cf_meta_key ] : array();
                        $range_min = isset( $range_value['min'] ) ? sanitize_text_field( $range_value['min'] ) : '';
                        $range_max = isset( $range_value['max'] ) ? sanitize_text_field( $range_value['max'] ) : '';
                        echo '<div class="number-range-wrapper">';
                        echo '<input type="number" name="custom['.$cf_meta_key.'][min]" class="listing-filter__number-input" placeholder="Min" value="'.esc_attr( $range_min ).'"'.self::get_reset_field_attributes( '' ).'>';
                        echo '<input type="number" name="custom['.$cf_meta_key.'][max]" class="listing-filter__number-input" placeholder="Max" value="'.esc_attr( $range_max ).'"'.self::get_reset_field_attributes( '' ).'>';
                        echo '</div>';

                    } elseif( $cf_display === 'select' ){
                        $selected_custom_value = isset( $request_custom[ $cf_meta_key ] ) && !is_array( $request_custom[ $cf_meta_key ] ) ? sanitize_text_field( $request_custom[ $cf_meta_key ] ) : '';
                        echo '<select name="custom['.$cf_meta_key.']" class="listing-filter__custom-select"'.self::get_reset_field_attributes( '' ).'>';
                        echo '<option value="" '.selected( $selected_custom_value, '', false ).'>'.$all[$current_lang].'</option>';
                        foreach( $cf_options as $opt ){
                            $opt_value = esc_attr( $opt['value'] ?? '' );
                            $opt_label = esc_html( $opt['label'] ?? $opt_value );
                            echo '<option value="'.$opt_value.'" '.selected( $selected_custom_value, $opt_value, false ).'>'.$opt_label.'</option>';
                        }
                        echo '</select>';

                    } elseif( $cf_display === 'radio' ){
                        $selected_custom_value = isset( $request_custom[ $cf_meta_key ] ) && !is_array( $request_custom[ $cf_meta_key ] ) ? sanitize_text_field( $request_custom[ $cf_meta_key ] ) : '';
                        echo '<div class="tags-wrapper">';
                        echo '<label class="tag-label"><input type="radio" name="custom['.$cf_meta_key.']" value="" '.checked( $selected_custom_value, '', false ).self::get_reset_field_attributes( '', true ).'> <span>'.$all[$current_lang].'</span></label>';
                        foreach( $cf_options as $opt ){
                            $opt_value = esc_attr( $opt['value'] ?? '' );
                            $opt_label = esc_html( $opt['label'] ?? $opt_value );
                            echo '<label class="tag-label"><input type="radio" name="custom['.$cf_meta_key.']" value="'.$opt_value.'" '.checked( $selected_custom_value, $opt_value, false ).self::get_reset_field_attributes( $opt_value, false ).'> <span>'.$opt_label.'</span></label>';
                        }
                        echo '</div>';

                    } elseif( $cf_display === 'checkboxes' ){
                        $selected_custom_values = isset( $request_custom[ $cf_meta_key ] ) && is_array( $request_custom[ $cf_meta_key ] )
                            ? array_map( 'sanitize_text_field', $request_custom[ $cf_meta_key ] )
                            : array();
                        echo '<div class="tags-wrapper">';
                        foreach( $cf_options as $opt ){
                            $opt_value = esc_attr( $opt['value'] ?? '' );
                            $opt_label = esc_html( $opt['label'] ?? $opt_value );
                            echo '<label class="tag-label"><input type="checkbox" name="custom['.$cf_meta_key.'][]" value="'.$opt_value.'" '.checked( in_array( $opt_value, $selected_custom_values, true ), true, false ).self::get_reset_field_attributes( $opt_value, false ).'> <span>'.$opt_label.'</span></label>';
                        }
                        echo '</div>';

                    } else { // text (default)
                        $custom_text_value = isset( $request_custom[ $cf_meta_key ] ) && !is_array( $request_custom[ $cf_meta_key ] ) ? sanitize_text_field( $request_custom[ $cf_meta_key ] ) : '';
                        echo '<input type="hidden" name="custom_compare['.$cf_meta_key.']" value="LIKE">';
                        echo '<input type="text" name="custom['.$cf_meta_key.']" class="listing-filter__custom-text-input" value="'.esc_attr( $custom_text_value ).'"'.self::get_reset_field_attributes( '' ).'>';
                    }

                    echo '</div>';
                    break;
                
                default:
                    // if filter type is a taxonomy filter, display the taxonomy select
                    if( strpos($filter_type, 'taxfilter__') === 0 ) {
                        $parts = explode('__', $filter_type);
                        $cpt_slug = $parts[1] ?? '';
                        $tax_slug = $parts[2] ?? '';
                        if( $cpt_slug && $tax_slug && $cpt_slug == $posttype ) {
                            $tax_object = get_taxonomy( $tax_slug );
                            if( $tax_object ) {
                                $terms = get_terms( array(
                                    'taxonomy' => $tax_slug,
                                    'hide_empty' => false,
                                ));
                                $default_term = $filter_group['initial_value'] ?? '';
                                $display_type = $filter_group['display_type'] ?? 'select';
                                $request_term = $request_taxonomies[ $tax_slug ] ?? null;
                                $selected_term = is_array( $request_term )
                                    ? array_map( 'absint', $request_term )
                                    : ( null !== $request_term ? absint( $request_term ) : absint( $default_term ) );

                                echo '<div class="field-wrapper">';
                                echo '<span class="field-desc">'.strtoupper($tax_object->label).':</span>';

                                if( $display_type == 'radio' || $display_type == 'checkboxes' ) {
                                    $input_type = ($display_type == 'radio') ? 'radio' : 'checkbox';
                                    echo '<div class="tags-wrapper">';
                                    if( $input_type == 'radio' ) {
                                        $radio_term = is_array( $selected_term ) ? '' : $selected_term;
                                        echo '<label class="tag-label"><input type="'.$input_type.'" name="taxonomies['.$tax_slug.']" value="" '.checked( $radio_term, '', false ).self::get_reset_field_attributes( '', empty( $default_term ) ).'> <span>'.$all[$current_lang].'</span></label>';
                                    }
                                    foreach( $terms as $term ) {
                                        $input_name = ($input_type == 'checkbox') ? 'taxonomies['.$tax_slug.'][]' : 'taxonomies['.$tax_slug.']';
                                        $is_checked = ( 'checkbox' === $input_type )
                                            ? in_array( (int) $term->term_id, is_array( $selected_term ) ? $selected_term : array(), true )
                                            : (string) $selected_term === (string) $term->term_id;
                                        $reset_checked = (string) $default_term === (string) $term->term_id;
                                        echo '<label class="tag-label"><input type="'.$input_type.'" name="'.$input_name.'" value="'.$term->term_id.'" '.checked( $is_checked, true, false ).self::get_reset_field_attributes( $term->term_id, $reset_checked ).'> <span>'.$term->name.'</span></label>';
                                    }
                                    echo '</div>';
                                } else {
                                    echo '<select name="taxonomies['.$tax_slug.']" class="listing-filter__term-select"'.self::get_reset_field_attributes( $default_term ).'>';
                                    $selected_select_term = is_array( $selected_term ) ? '' : $selected_term;
                                    echo '<option value="" '.selected( $selected_select_term, '', false ).'>'.$all[$current_lang].'</option>';
                                    foreach( $terms as $term ) {
                                        echo '<option value="'.$term->term_id.'" '.selected( $selected_select_term, $term->term_id, false ).'>'.$term->name.'</option>';
                                    }
                                    echo '</select>';
                                }
                                
                                echo '</div>';
                            }
                        }

                    } else {
                        echo '<div class="field-wrapper">';
                        echo '<span class="field-desc">'.$filter_group['__type'].'</span>';
                        echo '</div>';
                    }
                    break;
            }
        }
        echo '</div>'; // end fields-row

        echo '</form>';

        echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
    }

    public static function get_view_template() {
		$template = <<<'BACKBONE'
<%
var filterItems = Array.isArray( filters ) ? filters : [];
var layout = template || 'horizontal';
var selectedPosttype = posttype || 'post';
var sampleItems = window.UF_Editor?.getListingFilterSampleItems( 8 ) || [];
var renderFieldWrapper = window.UF_Editor?.renderFieldWrapper;
var escapeHtml = window.UF_Editor?.escapeHtml || function( value ) {
    return _.escape( value == null ? '' : String( value ) );
};

var html = '<div class="listing-filter" data-template="' + escapeHtml( layout ) + '">';
html += '<form action="#" method="GET">';
html += '<div class="fields-row">';

if ( ! filterItems.length ) {
    html += renderFieldWrapper( 'SEARCH:', '<input type="text" class="listing-filter__search-input" value="" />' );
    html += '<div class="field-wrapper"><button type="button" class="listing-filter__submit btn btn--main-color btn-block">FILTER</button></div>';
} else {
    _.each( filterItems, function( filterGroup ) {
        var filterType = filterGroup.__type || '';

        if ( filterType === 'break' ) {
            html += '</div><div class="fields-row">';
            return;
        }

        if ( filterType === 'search' ) {
            html += renderFieldWrapper( filterGroup.label || 'SEARCH:', '<input type="text" class="listing-filter__search-input" value="" />' );
            return;
        }

        if ( filterType === 'month' ) {
            html += renderFieldWrapper( 'MONTH:', window.UF_Editor?.renderSelect( 'listing-filter__month-select', sampleItems, true ) );
            return;
        }

        if ( filterType === 'year' ) {
            var yearItems = window.UF_Editor?.getListingFilterYearItems( 8 ) || [];
            html += renderFieldWrapper( 'YEAR:', window.UF_Editor?.renderSelect( 'listing-filter__year-select', yearItems, true ) );
            return;
        }

        if ( filterType === 'submit' ) {
            var submitHtml = '';

            if ( filterGroup.show_reset_button ) {
                submitHtml += '<span class="field-desc"><button type="button" class="listing-filter__reset">' + escapeHtml( filterGroup.reset_text || 'RESET' ) + ' <i class="fa fa-undo"></i></button></span>';
            }

            submitHtml += '<button type="submit" class="listing-filter__submit btn btn--main-color btn-block">' + escapeHtml( filterGroup.text || 'FILTER' ) + '</button>';
            html += '<div class="field-wrapper">' + submitHtml + '</div>';
            return;
        }

        if ( filterType === 'customfield' ) {
            html += window.UF_Editor?.renderListingFilterCustomField( filterGroup );
            return;
        }

        if ( filterType.indexOf( 'taxfilter__' ) === 0 ) {
            html += window.UF_Editor?.renderListingFilterTaxonomyField( filterGroup, selectedPosttype );
            return;
        }

        html += renderFieldWrapper( window.UF_Editor?.slugToLabel( filterType, 'Filter' ), '<input type="text" value="" />' );
    } );
}

html += '</div>';
html += '</form>';
html += '</div>';
%>
<%= html %>
BACKBONE;

        return $template;
    }
}

new Listing_Filter();