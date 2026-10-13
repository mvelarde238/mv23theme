<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Submit_Filter extends Component {

    public function __construct() {
		parent::__construct(
			'submit-filter',
			__( 'Submit Filter', 'mv23theme' )
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
                ->hide_label()->set_prefix(__('Button text','mv23theme'))
                ->set_default_value(__('FILTER','mv23theme')),
            Field::create( 'checkbox', 'show_reset_button' )
                ->hide_label()->fancy()
                ->set_text(__('Show reset button','mv23theme'))
                ->set_default_value(true),
            Field::create( 'text', 'reset_text' )
                ->hide_label()->set_prefix(__('Reset button text','mv23theme'))
                ->set_default_value(__('RESET','mv23theme'))
                ->add_dependency( 'show_reset_button', true )
        );

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_restricted( $args ) ) return;

        $button_text = $args['label'] ?? __('FILTER','mv23theme');
        $show_reset_button = !empty( $args['show_reset_button'] );
        $reset_button_text = $args['reset_text'] ?? __('RESET','mv23theme');

		ob_start();
        echo Template_Engine::component_wrapper('start', $args);
        if( $show_reset_button ) {
            echo '<span class="field-desc">';
            echo '<button type="button" class="listing-filter__reset">'.esc_html( $reset_button_text ).' <i class="fa fa-undo"></i></button>';
            // echo '<button type="button" class="listing-filter__reset" data-reset-url="'.esc_url( $form_action_url ).'">'.esc_html( $reset_button_text ).' <i class="fa fa-undo"></i></button>';
            echo '</span>';
        }
        echo '<button type="submit" class="listing-filter__submit">'.$button_text.'</button>';
        echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}

    public static function get_view_template() {
        $filter_text = __('FILTRAR','mv23theme');
        $reset_text = __('RESET','mv23theme');

        return '<%
        var html = "";
        var submitHtml = "";

        var escapeHtml = window.UF_Editor?.escapeHtml || function( value ) { return _.escape( value == null ? "" : String( value ) ); };

        if ( show_reset_button ) {
            submitHtml += "<span class=\"field-desc\"><button type=\"button\" class=\"listing-filter__reset\">" + escapeHtml( reset_text || "'.$reset_text.'" ) +  " <i class=\"fa fa-undo\"></i></button></span>";
        }

        submitHtml += "<button type=\"submit\" class=\"listing-filter__submit\">" + escapeHtml( label || "'.$filter_text.'" ) + "</button>";
        html += "<div class=\"field-wrapper\">" + submitHtml + "</div>";
        %>
        <%= html %>';
    }
}

new Submit_Filter();