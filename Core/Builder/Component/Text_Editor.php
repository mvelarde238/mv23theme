<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Ultimate_Builder\Handlebars;

class Text_Editor extends Component {

    public function __construct() {
		parent::__construct(
			'text-editor',
			__( 'Text editor', 'mv23theme' )
		);
	}

	public static function get_icon() {
        return 'bi bi-fonts';
    }

	public static function get_title_template() {
		$template = '<%= content.replace(/<[^>]+>/ig, "") %>';
		
		return $template;
	}

	public static function get_fields() {
		$fields = array( 
			Field::create( 'wysiwyg', 'content' )
				->add_dynamic_data_selector()
				->hide_label()->set_rows( 20 )->required()
				->set_default_value( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed nonne merninisti licere mihi ista probare, quae sunt a te dicta? Quid de Pythagora? Etiam habebis sem dicantur magna mollis euismod.' )
		);

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_restricted( $args ) ) return;

		$args['additional_classes'][] = 'component';
		$content = Handlebars::parse($args['content']) ?? '';
	
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		if($content) echo '<div>'.do_shortcode(wpautop(oembed( $content ))).'</div>';
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}

	public static function get_view_template() {
		return '<% 
		filtered_content = Handlebars.parse(content) 
		%>
		<%= wp.editor && wp.editor.autop ? wp.editor.autop(filtered_content) : filtered_content %>';
	}
}

new Text_Editor();