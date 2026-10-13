<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Component\Listing_Filter;

class Month_Filter extends Component {

    public function __construct() {
		parent::__construct(
			'month-filter',
			__( 'Month Filter', 'mv23theme' )
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
                ->set_placeholder( __('MONTH','mv23theme') ),
            Field::create( 'text', 'all_text' )
                ->hide_label()->set_prefix(__('"All" text','mv23theme'))
                ->set_placeholder( __('All','mv23theme') ),
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

		$months = array(
            'es' => array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'),
            'en' => array('January','February','March','April','May','June','July','August','September','October','November','December'),
        );
		$current_lang = (function_exists('pll_current_language')) ? pll_current_language() : 'es';

		$label = ($args['label'] && !empty($args['label'])) ? $args['label'] : __('MONTH','mv23theme');
		$all_text = ( isset( $args['all_text'] ) && $args['all_text'] !== '' ) ? $args['all_text'] : __( 'All', 'mv23theme' );
		$request_month = isset( $_GET['month'] ) ? sanitize_text_field( wp_unslash( $_GET['month'] ) ) : '';

		if( !empty( $args['trigger_submit_on_change'] ) ) {
			$args['additional_classes'][] = 'trigger-submit-on-change';
		}

		ob_start();
        echo Template_Engine::component_wrapper('start', $args);
		if( empty( $args['hide_label'] ) ) {
        	echo '<span class="field-desc">'.$label.'</span>';
		}
        echo '<select name="month" class="listing-filter__month-select"'.Listing_Filter::get_reset_field_attributes( '' ).'>';
        echo '<option value="" '.selected( $request_month, '', false ).'>'.esc_html( $all_text ).'</option>';
        for ($i=0; $i < count($months[$current_lang]); $i++) {
            $value = ( $i < 10 ) ? '0'.($i+1) : $i+1;  
            echo '<option value="'.$value.'" '.selected( $request_month, $value, false ).'>'.$months[$current_lang][$i].'</option>';
        }
        echo '</select>';
        echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}

    public static function get_view_template() {
		$label = __('MONTH','mv23theme');
		$default_all = __('All','mv23theme');

        return '<%
        const renderFieldWrapper = window.UF_Editor?.renderFieldWrapper;
        const _label = (typeof hide_label !== "undefined" && hide_label) ? "" : ((typeof label !== "undefined" && label) ? label : "'.$label.'");
        const _all = (typeof all_text !== "undefined" && all_text) ? all_text : "'.$default_all.'";
        var sampleItems = [
			{ value: "january", label: "January" },
			{ value: "february", label: "February" },
			{ value: "march", label: "March" },
			{ value: "april", label: "April" },
			{ value: "may", label: "May" },
			{ value: "june", label: "June" },
			{ value: "july", label: "July" },
			{ value: "august", label: "August" },
			{ value: "september", label: "September" },
			{ value: "october", label: "October" },
			{ value: "november", label: "November" },
			{ value: "december", label: "December" }
		];
        const html = renderFieldWrapper( _label, window.UF_Editor?.renderSelect( "listing-filter__month-select", sampleItems, true, _all ) );
        %>
        <%= html %>';
    }
}

new Month_Filter();