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

        // Add break group to allow users to break the filter into multiple rows
        $filters_repeater->add_group('break', array(
            'title' => __('Break','mv23theme'),
            'fields' => array()
        ));

        // Add submit button group
        $filters_repeater->add_group('submit', array(
            'title' => __('Filter button','mv23theme'),
            'fields' => array(
                Field::create( 'text', 'text' )
                    ->hide_label()->set_prefix(__('Button text','mv23theme'))
                    ->set_default_value(__('FILTER','mv23theme'))
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
        $months = array(
            'es' => array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'),
            'en' => array('January','February','March','April','May','June','July','August','September','October','November','December'),
        );
        $current_lang = (function_exists('pll_current_language')) ? pll_current_language() : 'es';

        ob_start();
        echo Template_Engine::component_wrapper('start', $args);
        echo '<form action="" method="GET">';
        
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
                    echo '<input type="text" name="search" class="listing-filter__search-input">';
                    echo '</div>';
                    break;

                case 'month':
                    echo '<div class="field-wrapper">';
                    echo '<span class="field-desc">'.$month[$current_lang].'</span>';
                    echo '<select name="month" class="listing-filter__month-select">';
                    echo '<option value="">'.$allm[$current_lang].'</option>';
                    for ($i=0; $i < count($months[$current_lang]); $i++) {
                        $value = ( $i < 10 ) ? '0'.($i+1) : $i+1;  
                        echo '<option value="'.$value.'">'.$months[$current_lang][$i].'</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                    break;

                case 'year':
                    $current_year = date('Y');
                    $first_year = ($filter_group['first_year'] == '') ? $current_year : $filter_group['first_year'];
                    $default_year = $filter_group['initial_value'] ?? '';
                    echo '<div class="field-wrapper">';
                    echo '<span class="field-desc">'.$year[$current_lang].'</span>';
                    echo '<select name="year" class="listing-filter__year-select">';
                    echo '<option value="">'.$allm[$current_lang].'</option>';
                    for ($i=$current_year; $i >= $first_year ; $i--) {
                        echo '<option value="'.$i.'" '.selected( $default_year, $i, true).'>'.$i.'</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                    break;

                case 'submit':
                    $button_text = $filter_group['text'] ?? $filter[$current_lang];
                    echo '<div class="field-wrapper">';
                    echo '<button type="submit" class="listing-filter__submit btn btn--main-color btn-block">'.$button_text.'</button>';
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

                                echo '<div class="field-wrapper">';
                                echo '<span class="field-desc">'.strtoupper($tax_object->label).':</span>';

                                if( $display_type == 'radio' || $display_type == 'checkboxes' ) {
                                    $input_type = ($display_type == 'radio') ? 'radio' : 'checkbox';
                                    echo '<div class="tags-wrapper">';
                                    if( $input_type == 'radio' ) {
                                        echo '<label class="tag-label"><input type="'.$input_type.'" name="taxonomies['.$tax_slug.']" value="" '.checked( $default_term, '', true).'> <span>'.$all[$current_lang].'</span></label>';
                                    }
                                    foreach( $terms as $term ) {
                                        echo '<label class="tag-label"><input type="'.$input_type.'" name="taxonomies['.$tax_slug.']" value="'.$term->term_id.'" '.checked( $default_term, $term->term_id, true).'> <span>'.$term->name.'</span></label>';
                                    }
                                    echo '</div>';
                                } else {
                                    echo '<select name="taxonomies['.$tax_slug.']" class="listing-filter__term-select">';
                                    echo '<option value="">'.$all[$current_lang].'</option>';
                                    foreach( $terms as $term ) {
                                        echo '<option value="'.$term->term_id.'" '.selected( $default_term, $term->term_id, true).'>'.$term->name.'</option>';
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
}

new Listing_Filter();