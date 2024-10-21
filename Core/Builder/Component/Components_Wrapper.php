<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Blocks_Layout;
use Core\Builder\Blocks_Layout_Settings;

class Components_Wrapper extends Component {

    public function __construct() {
		parent::__construct(
			'components_wrapper',
			__( 'Components Wrapper', 'default' )
		);
	}

	public static function get_icon() {
        return 'dashicons-text';
    }

	public static function get_title_template() {
		$template = '<% if ( blocks_layout.length < 1 ){ %>
            This item is empty
        <% } else { %>
            <% if ( blocks_layout[0][0].__type == "text_editor" ){ %>
                <%= blocks_layout[0][0].content.replace(/<[^>]+>/ig, "") %>
            <% } else { %>
                <%= "First item type: "+blocks_layout[0][0].__type %>
            <% } %>
        <% } %>';
		
		return $template;
	}

	public static function get_fields() {
		$fields = array( 
            Field::create( 'tab', __('Contenido','default') ),
            Blocks_Layout::the_field( array( 'exclude' => array('inner_columns') ) ),
			Blocks_Layout_Settings::the_field()
        );

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_private( $args ) ) return;
		
		$args['additional_classes'] = array('component');
        $blocks_layout_data = $args['blocks_layout'];
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
        echo Blocks_Layout::the_content( $blocks_layout_data, array('component_args' => $args) );
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}