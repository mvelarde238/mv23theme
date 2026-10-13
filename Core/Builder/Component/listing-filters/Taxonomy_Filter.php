<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Component\Listing_Filter;
use Core\Builder\Core;

class Taxonomy_Filter extends Component {

    public function __construct() {
		parent::__construct(
			'taxonomy-filter',
			__( 'Taxonomy Filter', 'mv23theme' )
		);
	}

	public static function get_builder_data() {
        return array(
			'display_gjs_block' => false
		);
    }

	public static function get_fields() {
		$fields = array();

        $fields[] = Field::create( 'text', 'label' )
            ->hide_label()->set_prefix(__('Label','mv23theme'))
            ->set_placeholder( __('TAXONOMY','mv23theme') );

        $posttypes = Core::get_post_types();
        $taxonomies = array();
        foreach( $posttypes as $cpt_slug => $cpt_name ) {
            $cpt_taxonomies = get_object_taxonomies( $cpt_slug, 'objects' );
            foreach( $cpt_taxonomies as $tax_slug => $tax_object ) {
                if ( !$tax_object->public || !$tax_object->show_ui ) continue;
                $taxonomies[$tax_slug] = $tax_object->label;
            }
        }
        $fields[] = Field::create( 'select', 'taxonomy' )
            ->hide_label()->set_prefix(__('Taxonomy','mv23theme'))
            ->add_options( $taxonomies );

        $fields[] = Field::create( 'text', 'initial_value' )
            ->hide_label()->set_prefix(__('Initial value','mv23theme'))
            ->set_description(__('Initial value can be term ID, slug or term name','mv23theme'));
        $fields[] = Field::create( 'select', 'display_type' )
            ->hide_label()->set_prefix(__('Display type','mv23theme'))
            ->set_default_value('select')
            ->add_options(array(
                'select' => __('Select','mv23theme'),
                'radio' => __('Radio buttons','mv23theme'),
                'checkboxes' => __('Checkboxes','mv23theme'),
            ));
        $fields[] = Field::create( 'text', 'all_text' )
            ->hide_label()->set_prefix(__('"All" text','mv23theme'))
            ->set_placeholder( __('All','mv23theme') )
            ->add_dependency('display_type', array('select','radio'), 'IN');
        $fields[] = Field::create( 'checkbox', 'hide_empty' )
            ->hide_label()->set_prefix(__('Hide empty terms','mv23theme'))
            ->fancy();
        $fields[] = Field::create( 'checkbox', 'trigger_submit_on_change' )
            ->hide_label()->fancy()
            ->set_text(__('Trigger submit on change','mv23theme'));
        $fields[] = Field::create( 'checkbox', 'hide_label' )
            ->hide_label()->fancy()
            ->set_text(__('Hide label','mv23theme'));

		return $fields;
	}

    // initial value can be term ID, slug or term name
    private static function get_initial_value( $args ) {
        $initial_value = $args['initial_value'] ?? '';
        if ( is_numeric( $initial_value ) ) {
            return absint( $initial_value );
        }
        $tax_slug = $args['taxonomy'] ?? '';
        $term = get_term_by( 'slug', $initial_value, $tax_slug );
        if ( $term ) {
            return $term->term_id;
        }
        $term = get_term_by( 'name', $initial_value, $tax_slug );
        if ( $term ) {
            return $term->term_id;
        }
        return '';
    }

	public static function display( $args ){
		if( Template_Engine::is_restricted( $args ) ) return;

        $tax_slug = $args['taxonomy'] ?? '';
        $tax_object = get_taxonomy( $tax_slug );
        if( $tax_object ) {
            $hide_empty = $args['hide_empty'] ?? false;
            $terms = get_terms( array(
                'taxonomy' => $tax_slug,
                'hide_empty' => !empty($hide_empty),
            ));
            $default_term = self::get_initial_value( $args );
            $display_type = $args['display_type'] ?? 'select';
            $request_taxonomies = ( isset( $_GET['taxonomies'] ) && is_array( $_GET['taxonomies'] ) ) ? wp_unslash( $_GET['taxonomies'] ) : array();
            $request_term = $request_taxonomies[ $tax_slug ] ?? null;
            $selected_term = is_array( $request_term )
                ? array_map( 'absint', $request_term )
                : ( null !== $request_term ? absint( $request_term ) : absint( $default_term ) );
        } else {
            return;
        }

        $label = ($args['label'] && !empty($args['label'])) ? $args['label'] : strtoupper($tax_object->label);
        $all_text = ( isset( $args['all_text'] ) && $args['all_text'] !== '' ) ? $args['all_text'] : __( 'All', 'mv23theme' );

		if( !empty( $args['trigger_submit_on_change'] ) ) {
			$args['additional_classes'][] = 'trigger-submit-on-change';
		}

		ob_start();
        echo Template_Engine::component_wrapper('start', $args);
		if( empty( $args['hide_label'] ) ) {
        	echo '<span class="field-desc">'.$label.'</span>';
		}
        if( $display_type == 'radio' || $display_type == 'checkboxes' ) {
            $input_type = ($display_type == 'radio') ? 'radio' : 'checkbox';
            echo '<div class="tags-wrapper">';
            if( $input_type == 'radio' ) {
                $radio_term = is_array( $selected_term ) ? '' : $selected_term;
                echo '<label class="tag-label"><input type="'.$input_type.'" name="taxonomies['.$tax_slug.']" value="" '.checked( $radio_term, '', false ).Listing_Filter::get_reset_field_attributes( '', empty( $default_term ) ).'> <span>'.esc_html( $all_text ).'</span></label>';
            }
            foreach( $terms as $term ) {
                $input_name = ($input_type == 'checkbox') ? 'taxonomies['.$tax_slug.'][]' : 'taxonomies['.$tax_slug.']';
                $is_checked = ( 'checkbox' === $input_type )
                    ? in_array( (int) $term->term_id, is_array( $selected_term ) ? $selected_term : array(), true )
                    : (string) $selected_term === (string) $term->term_id;
                $reset_checked = (string) $default_term === (string) $term->term_id;
                echo '<label class="tag-label"><input type="'.$input_type.'" name="'.$input_name.'" value="'.$term->term_id.'" '.checked( $is_checked, true, false ).Listing_Filter::get_reset_field_attributes( $term->term_id, $reset_checked ).'> <span>'.$term->name.'</span></label>';
            }
            echo '</div>';
        } else {
            echo '<select name="taxonomies['.$tax_slug.']" class="listing-filter__term-select"'.Listing_Filter::get_reset_field_attributes( $default_term ).'>';
            $selected_select_term = is_array( $selected_term ) ? '' : $selected_term;
            echo '<option value="" '.selected( $selected_select_term, '', false ).'>'.esc_html( $all_text ).'</option>';
            foreach( $terms as $term ) {
                echo '<option value="'.$term->term_id.'" '.selected( $selected_select_term, $term->term_id, false ).'>'.$term->name.'</option>';
            }
            echo '</select>';
        }
        echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}

    public static function get_view_template() {
        $default_all = __('All','mv23theme');

        return '<%
        const renderFieldWrapper = window.UF_Editor?.renderFieldWrapper;
        const filterData = {
            "label": typeof label !== "undefined" ? label : "",
            "hide_label": typeof hide_label !== "undefined" ? hide_label : false,
            "taxonomy": typeof taxonomy !== "undefined" ? taxonomy : "",
            "initial_value": typeof initial_value !== "undefined" ? initial_value : "",
            "hide_empty": typeof hide_empty !== "undefined" ? hide_empty : false,
            "display_type": typeof display_type !== "undefined" ? display_type : "select",
            "all_text": (typeof all_text !== "undefined" && all_text) ? all_text : "'.$default_all.'",
            "__type": "taxonomy"
        };
        const html = window.UF_Editor?.renderListingFilterTaxonomyField( filterData );
        %>
        <%= html %>';
    }
}

new Taxonomy_Filter();