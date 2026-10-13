<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Component\Listing_Filter;

class Year_Filter extends Component {

    public function __construct() {
		parent::__construct(
			'year-filter',
			__( 'Year Filter', 'mv23theme' )
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
                ->set_placeholder( __('YEAR','mv23theme') ),
            Field::create( 'number', 'initial_value' )
                ->hide_label()->set_prefix(__('Initial value','mv23theme'))
                ->set_maximum(date('Y'))
                ->set_width(50),
            Field::create( 'number', 'first_year')
                ->hide_label()->set_prefix(__('First year','mv23theme'))
                ->set_maximum(date('Y'))
                ->set_default_value(2012)
                ->set_width(50),
            Field::create( 'text', 'all_text' )
                    ->hide_label()->set_prefix(__('"All" text','mv23theme'))
                    ->set_placeholder( __('All','mv23theme') ),
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

        $current_year = date('Y');
		$label = ($args['label'] && !empty($args['label'])) ? $args['label'] : __('YEAR','mv23theme');
        $all_text = ( isset( $args['all_text'] ) && $args['all_text'] !== '' ) ? $args['all_text'] : __( 'All', 'mv23theme' );
        $first_year = ($args['first_year'] == '') ? $current_year : $args['first_year'];
        $default_year = $args['initial_value'] ?? '';
		$request_year = isset( $_GET['year'] ) ? sanitize_text_field( wp_unslash( $_GET['year'] ) ) : '';
        $selected_year = $request_year !== '' ? $request_year : $default_year;

		if( !empty( $args['trigger_submit_on_change'] ) ) {
			$args['additional_classes'][] = 'trigger-submit-on-change';
		}

		ob_start();
        echo Template_Engine::component_wrapper('start', $args);
		if( empty( $args['hide_label'] ) ) {
        	echo '<span class="field-desc">'.$label.'</span>';
		}
        echo '<select name="year" class="listing-filter__year-select"'.Listing_Filter::get_reset_field_attributes( $default_year ).'>';
        echo '<option value="" '.selected( $selected_year, '', false ).'>'.esc_html( $all_text ).'</option>';
        for ($i=$current_year; $i >= $first_year ; $i--) {
            echo '<option value="'.$i.'" '.selected( $selected_year, $i, false).'>'.$i.'</option>';
        }
        echo '</select>';
        echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}

    public static function get_view_template() {
		$label = __('YEAR','mv23theme');
		$default_all = __('All','mv23theme');

        return '<%
        const renderFieldWrapper = window.UF_Editor?.renderFieldWrapper;
        const _label = (typeof hide_label !== "undefined" && hide_label) ? "" : ((typeof label !== "undefined" && label) ? label : "'.$label.'");
        const _all = (typeof all_text !== "undefined" && all_text) ? all_text : "'.$default_all.'";
        var yearItems = window.UF_Editor?.getListingFilterYearItems( 8 ) || [];
        const html = renderFieldWrapper( _label, window.UF_Editor?.renderSelect( "listing-filter__year-select", yearItems, true, _all ) );
        %>
        <%= html %>';
    }
}

new Year_Filter();