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
			Field::create( 'wysiwyg', 'content' )
				->hide_label()->set_rows( 20 )->required()
				->set_default_value( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed nonne merninisti licere mihi ista probare, quae sunt a te dicta? Quid de Pythagora? Etiam habebis sem dicantur magna mollis euismod.' )
		);

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_private( $args ) ) return;

		$args['additional_classes'][] = 'component';
	
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		if($args['content']) echo '<div>'.do_shortcode(wpautop(oembed( $args['content'] ))).'</div>';
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}

	public static function get_view_template() {
		$template = '<%= wp.editor && wp.editor.autop ? wp.editor.autop(content) : content %>';
		return $template;
	}
}

new Text_Editor();