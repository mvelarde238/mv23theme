<?php
namespace Core\Builder\Component;

use Core\Builder\Content_Selector;
use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Card extends Component {

    public function __construct() {
		parent::__construct(
			'card',
			__( 'Card', 'mv23theme' )
		);
	}

	public static function get_icon() {
        return 'dashicons-editor-table';
    }

	public static function get_fields() {
		$fields = array( 
            Field::create( 'tab', __('Content','mv23theme') ),
            Field::create('radio', 'content_type')->set_orientation('horizontal')->add_options(array(
                'components' => 'Componentes',
                'simple' => 'Simple'
            ))->hide_label(),
            Field::create( 'wysiwyg', 'content')->hide_label()->add_dependency('content_type','simple','='),

            Content_Selector::the_field()->add_dependency('content_type','components','='),
    
            Field::create( 'tab', 'Tamaño' ),
            Field::create( 'image_select', 'aspect_ratio' )->add_options(array(
                'aspect-ratio-default'  => array(
                    'label' => 'mv23theme',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-default.png'
                ),
                'aspect-ratio-4-3'  => array(
                    'label' => '4:3',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-4-3.png'
                ),
                'aspect-ratio-1-1'  => array(
                    'label' => '1:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-1-1.png'
                ),
                'aspect-ratio-16-9'  => array(
                    'label' => '16:9',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-16-9.png'
                ),
                'aspect-ratio-2-1'  => array(
                    'label' => '16:9',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-2-1.png'
                ),
                'aspect-ratio-2_5-1'  => array(
                    'label' => '2.5:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-2_5-1.png'
                ),
                'aspect-ratio-4-1'  => array(
                    'label' => '4:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-4-1.png'
                ),
                'aspect-ratio-3-4'  => array(
                    'label' => '3:4',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-3-4.png'
                ),
                'aspect-ratio-9-16'  => array(
                    'label' => '9:16',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-9-16.png'
                ),
                'aspect-ratio-1-2'  => array(
                    'label' => '1:2',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-1-2.png'
                ),
                'aspect-ratio-1-2_5'  => array(
                    'label' => '1:2.5',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-1-2_5.png'
                ),
            )),
            Field::create( 'select', 'content_alignment','Alineación del Contenido')->add_options( array(
                'flex-start' => 'Arriba',
                'center' => 'Al centro',
                'flex-end' => 'Abajo',
                'space-between' => 'Distribuir'
            ))->add_dependency('aspect_ratio','aspect-ratio-default','!='),
            
            Field::create( 'tab', 'Otros' ),
            Field::create( 'number', 'components_margin', 'Márgenes de los componentes internos' )
                ->enable_slider( 0, 20 )
                ->set_default_value(20)
        );

		return $fields;
	}

    public static function get_common_settings() {
		return array( 'all' );
	}

	public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;

        $args['additional_classes'] = array('component');

        $content_type = (isset( $args['content_type'] )) ? $args['content_type'] : 'components';

        if ( isset($args['aspect_ratio']) && $args['aspect_ratio'] != 'aspect-ratio-default' ) $args['additional_classes'][] = $args['aspect_ratio'];

        $components_margin = (!empty($args['components_margin'])) ? $args['components_margin'] : null;
        if ( $components_margin && $components_margin != 20) $args['additional_attributes'] = array( 'data-setmargin="'.$components_margin.'"' );

        if( isset($args['content_alignment']) && $args['content_alignment'] != 'flex-start' && !empty($args['content_alignment']) ) $args['additional_classes'][] = 'alignment-'.$args['content_alignment'];
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);

        echo '<div class="card__components">';
		if( $content_type == 'simple' ):
			if( $args['content'] ){
				echo '<div class="editor-de-texto componente">';
				echo do_shortcode(wpautop($args['content']));
				echo '</div>';
			};
		else: 
			$components = $args['components'];
			foreach ($components as $component ) { 
				$component['layout'] = 'layout1';
                echo Template_Engine::getInstance()->handle( $component['__type'], $component );
			} 
		endif; 
	    echo '</div>';

		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}