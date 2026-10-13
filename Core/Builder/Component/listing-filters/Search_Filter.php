<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Component\Listing_Filter;

class Search_Filter extends Component {

    public function __construct() {
		parent::__construct(
			'search-filter',
			__( 'Search Filter', 'mv23theme' )
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
                ->set_placeholder( __('SEARCH','mv23theme') ),
			Field::create( 'checkbox', 'trigger_submit_on_change' )
                ->hide_label()->fancy()
                ->set_text(__('Trigger submit on change','mv23theme')),
			Field::create( 'checkbox', 'hide_label' )
                ->hide_label()->fancy()
                ->set_text(__('Hide label','mv23theme'))
        );

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_restricted( $args ) ) return;

		$label = ($args['label'] && !empty($args['label'])) ? $args['label'] : __('SEARCH','mv23theme');
		$request_search = isset( $_GET['search'] ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) : '';

		$trigger_submit_on_change = isset( $args['trigger_submit_on_change'] ) ? $args['trigger_submit_on_change'] : false;
		if( $trigger_submit_on_change ) {
			$args['additional_classes'][] = 'trigger-submit-on-change';
		}

		ob_start();
        echo Template_Engine::component_wrapper('start', $args);
		if( empty( $args['hide_label'] ) ) {
        	echo '<span class="field-desc">'.$label.'</span>';
		}
        echo '<input type="text" name="search" class="listing-filter__search-input" value="'.esc_attr( $request_search ).'"'.Listing_Filter::get_reset_field_attributes( '' ).'>';
        echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}

    public static function get_view_template() {
		$label = __('SEARCH','mv23theme');

        return '<%
        const renderFieldWrapper = window.UF_Editor?.renderFieldWrapper;
        const _label = (typeof hide_label !== "undefined" && hide_label) ? "" : ((typeof label !== "undefined" && label) ? label : "'.$label.'");
        const html = renderFieldWrapper( _label, "<input type=\"text\" class=\"listing-filter__search-input\" value=\"\" />" );
        %>
        <%= html %>';
    }
}

new Search_Filter();