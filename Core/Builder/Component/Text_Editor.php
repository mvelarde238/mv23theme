<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Text_Editor extends Component {

    public function __construct() {
		parent::__construct(
			'text_editor',
			__( 'Text editor', 'mv23theme' )
		);
	}

	public static function get_icon() {
        return 'dashicons-editor-textcolor';
    }

	public static function get_title_template() {
		$template = '<%= content.replace(/<[^>]+>/ig, "") %>';
		
		return $template;
	}

	public static function get_fields() {
		$fields = array( 
			Field::create( 'tab', __('Content','mv23theme') ),
			Field::create( 'wysiwyg', 'content' )->hide_label()->set_rows( 20 ),
			Field::create( 'checkbox', 'add_responsive' )
				->set_text( __('Change text alignment on mobile devices','mv23theme') )
				->hide_label(),
			Field::create( 'select', 'tablet_text_align',__('Text alignment on tablet','mv23theme'))->add_options( array(
				'' => __('Select','mv23theme'),
				'left' => __('Left','mv23theme'),
				'center' => __('Center','mv23theme'),
				'right' => __('Right','mv23theme')
			))->set_width(50)->add_dependency('add_responsive'),
			Field::create( 'select', 'mobile_text_align',__('Text alignment on mobile','mv23theme'))->add_options( array(
				'' => __('Select','mv23theme'),
				'left' => __('Left','mv23theme'),
				'center' => __('Center','mv23theme'),
				'right' => __('Right','mv23theme')
			))->set_width(50)->add_dependency('add_responsive'),
		);

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_private( $args ) ) return;

		$args['additional_classes'] = array('component');

		if ( isset($args['tablet_text_align']) && $args['tablet_text_align'] != '' ){
			$args['additional_classes'][] = $args['tablet_text_align'].'-on-tablet';
		} 
		if ( isset($args['mobile_text_align']) && $args['mobile_text_align'] != ''){
			$args['additional_classes'][] = $args['mobile_text_align'].'-on-mobile';
		} 
	
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		if($args['content']) echo '<div>'.do_shortcode(wpautop(oembed( $args['content'] ))).'</div>';
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Text_Editor();