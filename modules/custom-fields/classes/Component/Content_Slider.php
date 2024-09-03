<?php
namespace Theme_Custom_Fields\Component;

use Content_Selector;
use Ultimate_Fields\Field;
use Theme_Custom_Fields\Component;
use Theme_Custom_Fields\Template_Engine;

class Content_Slider extends Component {

    public function __construct() {
		parent::__construct(
			'content_slider',
			__( 'Content Slider', 'default' )
		);
	}

	public static function get_icon() {
        return 'dashicons-grid-view';
    }

	public static function get_fields() {
		$fields = array(
            Field::create( 'tab', __('Contenido','default') ),
            Field::create( 'repeater', 'items' )
                ->set_add_text( __('Add Slide', 'default') )
                ->hide_label()
                ->add_group('Item', array(
                    'edit_mode' => 'popup',
                    'fields' => array(
                        Field::create( 'tab', __('Contenido','default') ),
                        Content_Selector::the_field( 'components', __('Components','default') ),
                        Field::create( 'tab', __('Settings','default') ),
                        Field::create( 'text', 'title', 'Título para el menú' ),
                        // Field::create( 'tab', 'Settings' ),
                        // Field::create( 'image', 'bgi', 'Imágen de Fondo' )->set_width( 25 ),
                        // Field::create( 'complex', 'bgi_options', '' )->set_width( 25 )->add_fields(array(
                        //     Field::create( 'select', 'size', 'Tamaño')->add_options( array(
                        //         'auto' => 'Automático',
                        //         'cover' => 'Cubrir Todo',
                        //     ) ),
                        //     Field::create( 'select', 'repeat', 'Repetir')->add_options( array(
                        //         'repeat' => 'Ambas direcciones',
                        //         'repeat-x' => 'Solo horizontal',
                        //         'repeat-y' => 'Solo en vertical',
                        //         'no-repeat' => 'No Repetir',
                        //     ) ),
                        //     Field::create( 'select', 'position_x', 'Posición Eje Horizontal')->add_options( array(
                        //         'left' => 'Izquierda',
                        //         'center' => 'Centro',
                        //         'right' => 'Derecha',
                        //     ) ),
                        //     Field::create( 'select', 'position_y', 'Posición Eje Vertical')->add_options( array(
                        //         'top' => 'Arriba',
                        //         'center' => 'Centro',
                        //         'bottom' => 'Abajo',
                        //     ) ),
                        // ))->add_dependency('bgi','0','>'),
                        // Field::create( 'complex', 'color_de_fondo' )->set_width( 25 )->add_fields(array(
                        //     Field::create( 'checkbox', 'add_bgc', 'Activar' )->set_width( 25 )->set_text('Activar')->hide_label(),
                        //     Field::create( 'color', 'bgc', 'Color' )->set_width( 25 )->add_dependency('add_bgc'),
                        // )),
                        // Field::create( 'select', 'color_scheme', 'Color del Texto' )->set_width( 25 )->add_options( array(
                        //     '' => 'Seleccionar',
                        //     'default-scheme' => 'Negro',
                        //     'dark-scheme' => 'Blanco',
                        // )),
                    )
            )),
        
            Field::create( 'tab', 'Navigation' ),
            Field::create( 'checkbox', 'show_nav' )->set_width( 25 )->set_text('Mostrar indicadores de página')->set_default_value('1'),
            Field::create( 'select', 'nav_position', 'Posición')->set_width( 25 )->add_options( array(
                'bottom' => 'Abajo',
                'top' => 'Arriba',
            ))->add_dependency('show_nav'),
            Field::create( 'checkbox', 'nav_show_title', 'Mostrar Títulos')->set_width( 25 )->set_text('Activar')->add_dependency('show_nav'),
            Field::create( 'checkbox', 'scroll_to_top', 'Scroll to top' )->set_width( 25 )->set_text('Activar'),
            Field::create( 'tab', 'Controles' ),
            Field::create( 'checkbox', 'show_controls' )->set_width( 50 )->set_text('Mostrar Flechas')->set_default_value('1'),
            Field::create( 'select', 'controls_position', 'Posición')->set_width( 50 )->add_options( array(
                'bottom' => 'Abajo',
                'center' => 'Al centro',
                'top' => 'Arriba',
            ))->add_dependency('show_controls'),
            Field::create( 'checkbox', 'extender_fondo', 'Aplicar el fondo de los items al componente' )->set_width( 25 )->set_text('Activar')
        );

		return $fields;
	}

	public static function display( $args ){
        $args['additional_classes'] = array('component');

        $items = $args['items'];
        $extender_fondo = $args['extender_fondo'];
        $scroll_to_top = (isset($args['scroll_to_top'])) ? $args['scroll_to_top'] : 0;

        $show_nav = (isset($args['show_nav']) && !empty($args['show_nav'])) ? $args['show_nav'] : 0;
        $nav_position = (isset($args['nav_position']) && !empty($args['nav_position'])) ? $args['nav_position'] : 0;
        $nav_show_title = (isset($args['nav_show_title']) && !empty($args['nav_show_title'])) ? $args['nav_show_title'] : 0;

        $show_controls = (isset($args['show_controls']) && !empty($args['show_controls'])) ? $args['show_controls'] : 0;
        $controls_position = (isset($args['controls_position']) && !empty($args['controls_position'])) ? $args['controls_position'] : 0;

        $args['additional_attributes'] = array(
            'data-extended-bgi="'.$extender_fondo.'"',
            'data-show-title="'.$nav_show_title.'"',
            'data-controls-position="'.$controls_position.'"',
            'data-scroll-to-top="'.$scroll_to_top.'"',
        );

		$attributes = Template_Engine::generate_attributes( $args );

		ob_start();
        echo '<div '.$attributes.'>';
        echo Template_Engine::check_video_background( $args );
        echo Template_Engine::check_layout('start', $args);

        if (is_array($items) && count($items)>0): ?>
            <div class="content-slider__slider"
                data-nav-position="<?=$nav_position?>" 
                data-controls-position="<?=$controls_position?>" 
                data-show-title="<?=$nav_show_title?>" 
                data-show-controls="<?=$show_controls?>" 
                data-show-nav="<?=$show_nav?>">
                <?php foreach ($items as $item): 
                    $components = $item['components'];
    
                    $title = (array_key_exists('title', $item) && $item['title']) ? $item['title'] : '';
    
                    $clases = '';
                    $text_color = '';
                    $color_scheme = (array_key_exists('color_scheme', $item) && $item['color_scheme']) ? $item['color_scheme'] : '';
                    switch ($color_scheme) {
                        case 'dark-scheme':
                            $text_color = 'text-color-2';
                            break;
                        
                        case 'default-scheme':
                            $text_color = 'text-color-1';
                            break;
                    }
                    $clases = ($color_scheme != 'default-scheme') ? 'class="'.$text_color.'"' : '';
                    
                    $style = '';
                    // $bgi = wp_get_attachment_url($item['bgi']);
                    // if (array_key_exists('color_de_fondo', $item)) {
                    //     $style .= ($item['color_de_fondo']['add_bgc']) ? 'background-color: '.$item['color_de_fondo']['bgc'].';' : '';
                    // }
                    // $style .= ($bgi) ? 'background-image: url('.$bgi.');' : '';
                    // $style .= ($bgi && $item['bgi_options']['repeat'] != 'repeat') ? 'background-repeat: '.$item['bgi_options']['repeat'].';' : '';
                    // $style .= ($bgi && $item['bgi_options']['size'] != 'auto') ? 'background-size: '.$item['bgi_options']['size'].';' : '';
                    // $style .= ($bgi && $item['bgi_options']['position_x'] != 'left') ? 'background-position-x: '.$item['bgi_options']['position_x'].';' : '';
                    // $style .= ($bgi && $item['bgi_options']['position_y'] != 'top') ? 'background-position-y: '.$item['bgi_options']['position_y'].';' : '';
    
                    // if ($style && $extender_fondo) {
                    //     $style = 'data-style="'.$style.'"';
                    // } else {
                    //     $style = 'style="'.$style.'"';
                    // }
                    ?>
                    <div <?=$clases?> <?=$style?> data-title="<?=$title?>">
                        <?php 
                        foreach ($components as $component ) { 
                            echo Template_Engine::getInstance()->handle( $component['__type'], $component );
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif;
		
        echo Template_Engine::check_layout('end', $args);
        echo '</div>';
		return ob_get_clean();
	}
}