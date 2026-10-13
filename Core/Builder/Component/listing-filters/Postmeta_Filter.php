<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Component\Listing_Filter;

class Postmeta_Filter extends Component {

    public function __construct() {
		parent::__construct(
			'postmeta-filter',
			__( 'Postmeta Filter', 'mv23theme' )
		);
	}

	public static function get_builder_data() {
        return array(
			'display_gjs_block' => false
		);
    }

	public static function get_fields() {
		$fields = array(
            Field::create( 'text', 'label' )
                ->hide_label()->set_prefix(__('Label','mv23theme'))
                ->set_placeholder(__('POST META','mv23theme')),
            Field::create( 'text', 'meta_key' )
                ->hide_label()->set_prefix(__('Meta Key','mv23theme')),
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
            Field::create( 'text', 'all_text' )
                ->hide_label()->set_prefix(__('"All" text','mv23theme'))
                ->set_placeholder( __('All','mv23theme') )
                ->add_dependency('display_type', array('select','radio'), 'IN'),
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
            Field::create( 'checkbox', 'trigger_submit_on_change' )
                ->hide_label()->fancy()
                ->set_text(__('Trigger submit on change','mv23theme')),
            Field::create( 'checkbox', 'hide_label' )
                ->hide_label()->fancy()
                ->set_text(__('Hide label','mv23theme')),
        );

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_restricted( $args ) ) return;

		$cf_meta_key = sanitize_key( $args['meta_key'] ?? '' );
        $cf_label    = ($args['label'] && !empty($args['label'])) ? $args['label'] : strtoupper($cf_meta_key);
        $cf_display  = $args['display_type'] ?? 'text';
        $cf_options  = $args['options'] ?? array();
        if( !$cf_meta_key ) return;

        $request_custom = ( isset( $_GET['custom'] ) && is_array( $_GET['custom'] ) ) ? wp_unslash( $_GET['custom'] ) : array();

		if( !empty( $args['trigger_submit_on_change'] ) ) {
			$args['additional_classes'][] = 'trigger-submit-on-change';
		}

        $all_text = ( isset( $args['all_text'] ) && $args['all_text'] !== '' ) ? $args['all_text'] : __( 'All', 'mv23theme' );

		ob_start();
        echo Template_Engine::component_wrapper('start', $args);

        if( empty( $args['hide_label'] ) && $cf_label ) echo '<span class="field-desc">'.esc_html($cf_label).'</span>';

        if( $cf_display === 'number_range' ){
            $range_value = isset( $request_custom[ $cf_meta_key ] ) && is_array( $request_custom[ $cf_meta_key ] ) ? $request_custom[ $cf_meta_key ] : array();
            $range_min = isset( $range_value['min'] ) ? sanitize_text_field( $range_value['min'] ) : '';
            $range_max = isset( $range_value['max'] ) ? sanitize_text_field( $range_value['max'] ) : '';
            echo '<div class="number-range-wrapper">';
            echo '<input type="number" name="custom['.$cf_meta_key.'][min]" class="listing-filter__number-input" placeholder="Min" value="'.esc_attr( $range_min ).'"'.Listing_Filter::get_reset_field_attributes( '' ).'>';
            echo '<input type="number" name="custom['.$cf_meta_key.'][max]" class="listing-filter__number-input" placeholder="Max" value="'.esc_attr( $range_max ).'"'.Listing_Filter::get_reset_field_attributes( '' ).'>';
            echo '</div>';

        } elseif( $cf_display === 'select' ){
            $selected_custom_value = isset( $request_custom[ $cf_meta_key ] ) && !is_array( $request_custom[ $cf_meta_key ] ) ? sanitize_text_field( $request_custom[ $cf_meta_key ] ) : '';
            echo '<select name="custom['.$cf_meta_key.']" class="listing-filter__custom-select"'.Listing_Filter::get_reset_field_attributes( '' ).'>';
            echo '<option value="" '.selected( $selected_custom_value, '', false ).'>'.esc_html( $all_text ).'</option>';
            foreach( $cf_options as $opt ){
                $opt_value = esc_attr( $opt['value'] ?? '' );
                $opt_label = esc_html( $opt['label'] ?? $opt_value );
                echo '<option value="'.$opt_value.'" '.selected( $selected_custom_value, $opt_value, false ).'>'.$opt_label.'</option>';
            }
            echo '</select>';

        } elseif( $cf_display === 'radio' ){
            $selected_custom_value = isset( $request_custom[ $cf_meta_key ] ) && !is_array( $request_custom[ $cf_meta_key ] ) ? sanitize_text_field( $request_custom[ $cf_meta_key ] ) : '';
            echo '<div class="tags-wrapper">';
            echo '<label class="tag-label"><input type="radio" name="custom['.$cf_meta_key.']" value="" '.checked( $selected_custom_value, '', false ).Listing_Filter::get_reset_field_attributes( '', true ).'> <span>'.esc_html( $all_text ).'</span></label>';
            foreach( $cf_options as $opt ){
                $opt_value = esc_attr( $opt['value'] ?? '' );
                $opt_label = esc_html( $opt['label'] ?? $opt_value );
                echo '<label class="tag-label"><input type="radio" name="custom['.$cf_meta_key.']" value="'.$opt_value.'" '.checked( $selected_custom_value, $opt_value, false ).Listing_Filter::get_reset_field_attributes( $opt_value, false ).'> <span>'.$opt_label.'</span></label>';
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
                echo '<label class="tag-label"><input type="checkbox" name="custom['.$cf_meta_key.'][]" value="'.$opt_value.'" '.checked( in_array( $opt_value, $selected_custom_values, true ), true, false ).Listing_Filter::get_reset_field_attributes( $opt_value, false ).'> <span>'.$opt_label.'</span></label>';
            }
            echo '</div>';

        } else { // text (default)
            $custom_text_value = isset( $request_custom[ $cf_meta_key ] ) && !is_array( $request_custom[ $cf_meta_key ] ) ? sanitize_text_field( $request_custom[ $cf_meta_key ] ) : '';
            echo '<input type="hidden" name="custom_compare['.$cf_meta_key.']" value="LIKE">';
            echo '<input type="text" name="custom['.$cf_meta_key.']" class="listing-filter__custom-text-input" value="'.esc_attr( $custom_text_value ).'"'.Listing_Filter::get_reset_field_attributes( '' ).'>';
        }

        echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}

    public static function get_view_template() {
        $label = __('POST META','mv23theme');
        $default_all = __('All','mv23theme');

        return '<%
        const _label = (typeof label !== "undefined" && label) ? label : "'.$label.'";

        const filterData = {
            "meta_key": typeof meta_key !== "undefined" ? meta_key : "",
            "label": _label,
            "hide_label": typeof hide_label !== "undefined" ? hide_label : false,
            "display_type": typeof display_type !== "undefined" ? display_type : "text",
            "options": typeof options !== "undefined" ? options : [],
            "all_text": (typeof all_text !== "undefined" && all_text) ? all_text : "'.$default_all.'",
            "__type": "postmeta"
        };

        const html = window.UF_Editor?.renderListingFilterPostMeta( filterData );
        %>
        <%= html %>';
    }
}

new Postmeta_Filter();