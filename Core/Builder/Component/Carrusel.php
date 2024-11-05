<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Blocks_Layout;
use Ultimate_Fields\Container\Repeater_Group;

class Carrusel extends Component {

    public function __construct() {
		parent::__construct(
			'carrusel',
			__( 'Carrusel', 'default' )
		);
	}

    public static function get_icon() {
        return 'dashicons-slides';
    }

    public static function get_layout(){
        return 'grid';
    }

    public static function get_title_template() {
		$template = '<% if ( items.length ){ %>
            Show <%= items.length %> items in <%= items_in_desktop %> columns
        <% } else { %>
            This component is empty
        <% } %>';
		
		return $template;
	}

	public static function get_fields() {
        $content_group = new Repeater_Group( 'Content' );
		$content_group->set_title( __('Contenido','default') )
            ->set_edit_mode('popup')
            ->set_title_template( '<% if ( blocks_layout.length ){ %>
                    <% if ( blocks_layout[0][0].__type == "editor-de-texto" ){ %>
                        <%= blocks_layout[0][0].content.replace(/<[^>]+>/ig, "") %>
                    <% } else { %>
                        <%= "First item type: "+blocks_layout[0][0].__type %>
                    <% } %>
                <% } else { %>
                    This item is empty
                <% } %>') 
			->add_fields(array(
                Field::create( 'tab', __('Contenido','default') ),
                Blocks_Layout::the_field(),
                Field::create( 'tab', __('Settings','default') ),
                Field::create( 'common_settings_control', 'settings' )->set_container( 'common_settings_container' ),
                Field::create( 'common_settings_control', 'actions_settings' )->set_container( 'actions_container' )
            ));
        
		$fields = array(
            Field::create( 'tab', __('Contenido','default') ),
            Field::create( 'repeater', 'items', '' )
                ->set_add_text('Agregar')
                ->set_chooser_type( 'dropdown' )
                ->add_group( $content_group )
                ->add_group('Item', array(
                    'title' => 'Imágen',
                    'fields' => array(
                        Field::create( 'image', 'imagen' )->set_width( 25 ),
                        Field::create( 'complex', 'enlace' )->rows_layout()->add_fields(array(
                            Field::create( 'radio', 'url_type','Seleccione que contenido se abrirá al hacer clic:')->set_orientation( 'horizontal' )->add_options( array(
                                    '' => 'Ninguno',
                                    'interna' => 'Página Interna',
                                    'externa' => 'Página Externa',
                                    'popup' => 'Mostrar la imágen en un PopUp',
                            )),
                            Field::create( 'wp_object', 'post', 'URL Interna' )->add( 'posts' )->set_button_text( 'Selecciona la página' )->add_dependency('url_type','interna','='),
                            Field::create( 'text', 'url', 'URL Externa' )->add_dependency('url_type','externa','='),
                            Field::create( 'checkbox', 'new_tab' )->set_text( 'Abrir en una nueva ventana.' )->hide_label()->add_dependency('url_type','externa','='),
                        ))->set_width( 75 )
                    )
                )),
            
            Field::create( 'tab', 'Carrusel' ),
            Field::create( 'checkbox', 'show_controls' )->set_text('Mostrar Flechas')->set_width( 10 ),
            Field::create( 'checkbox', 'show_nav' )->set_text('Mostrar indicadores de página')->set_width( 10 ),
            Field::create( 'select', 'nav_position' )->add_options( array(
                        'bottom' => 'Abajo',
                        'top' => 'Arriba',
                    ))->add_dependency('show_nav')->set_width( 10 ),
            Field::create( 'checkbox', 'autoplay' )->set_text('Empezar automáticamente')->set_width( 10 ),
            Field::create( 'checkbox', 'auto_height' )->set_text('Activar')->set_width( 10 ),
            Field::create( 'select', 'mode', 'Mode' )->add_options( array(
                'carousel' => 'Carrusel',
                'gallery' => 'Fade',
            ))->set_width( 10 ),
            Field::create( 'select', 'axis', 'Axis' )->add_options( array(
                'horizontal' => 'Horizontal',
                'vertical' => 'Vertical',
            ))->add_dependency('mode','carousel','=')->set_width( 10 ),
        
            Field::create( 'section','lel','Cantidad de Items visibles'),
            Field::create( 'number', 'items_in_desktop', 'Items en desktop' )->set_default_value( '4' )->set_width( 25 ),
            Field::create( 'number', 'items_in_laptop', 'Items en laptop' )->set_default_value( '3' )->set_width( 25 ),
            Field::create( 'number', 'items_in_tablet', 'Items en tablet' )->set_default_value( '2' )->set_width( 25 ),
            Field::create( 'number', 'items_in_mobile', 'Items en móviles' )->set_default_value( '1' )->set_width( 25 ),
        
            Field::create( 'section','gat-betwwen-items','Espacio entre items'),
            Field::create( 'number', 'gutter_in_desktop', 'Gutter en desktop' )->set_default_value( '0' )->set_width( 25 ),
            Field::create( 'number', 'gutter_in_laptop', 'Gutter en laptop' )->set_default_value( '0' )->set_width( 25 ),
            Field::create( 'number', 'gutter_in_tablet', 'Gutter en tablet' )->set_default_value( '0' )->set_width( 25 ),
            Field::create( 'number', 'gutter_in_mobile', 'Gutter en móviles' )->set_default_value( '0' )->set_width( 25 ),
    
            Field::create( 'section','tamanio-de-imagenes','Tamaño de imágenes'),
            Field::create( 'select', 'imgs_height', 'Seleccionar:' )->add_options( array(
                'auto' => 'Automático',
                'custom' => 'Personalizar',
            ))->set_width( 25 ),
            Field::create( 'number', 'img_max_height', 'Tamaño de alto máximo en pixeles' )->set_default_value( '60' )->add_dependency('imgs_height','custom','=')->set_width( 25 ),
        );

		return $fields;
	}

	public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
		$args['additional_classes'] = array();

        $items = $args['items'];
        $show_controls = (isset($args['show_controls']) && !empty($args['show_controls'])) ? $args['show_controls'] : 0;
        $show_nav = (isset($args['show_nav']) && !empty($args['show_nav'])) ? $args['show_nav'] : 0;
        $nav_position = (isset($args['nav_position']) && !empty($args['nav_position'])) ? $args['nav_position'] : 'bottom';
        $autoplay = (isset($args['autoplay']) && !empty($args['autoplay'])) ? $args['autoplay'] : 0;
        $auto_height = (isset($args['auto_height']) && !empty($args['auto_height'])) ? $args['auto_height'] : 0;
        $axis = (isset($args['axis']) && !empty($args['axis'])) ? $args['axis'] : 'horizontal';
        $mode = (isset($args['mode']) && !empty($args['mode'])) ? $args['mode'] : 'carousel';

        $items_in_mobile = $args['items_in_mobile'];
        $items_in_tablet = $args['items_in_tablet'];
        $items_in_laptop = $args['items_in_laptop'];
        $items_in_desktop = $args['items_in_desktop'];

        $img_styles = '';
        $imgs_height = (isset($args['imgs_height'])) ? $args['imgs_height'] :'auto';
        if($imgs_height == 'custom') {
            $img_max_height = $args['img_max_height'] . 'px';
            $img_styles = 'style="max-height:'.$img_max_height.'"'; 
        }

        $gutter_in_mobile = (isset($args['gutter_in_mobile'])) ? $args['gutter_in_mobile'] : 0;
        $gutter_in_tablet = (isset($args['gutter_in_tablet'])) ? $args['gutter_in_tablet'] : 0;
        $gutter_in_laptop = (isset($args['gutter_in_laptop'])) ? $args['gutter_in_laptop'] : 0;
        $gutter_in_desktop = (isset($args['gutter_in_desktop'])) ? $args['gutter_in_desktop'] : 0;

        $args['additional_classes'][] = 'nav-position-'.$nav_position;
        if( !$show_nav ) $args['additional_classes'][] = 'without-navigation';
        $args['additional_attributes'] = array( 'data-controls-position="center"' );
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args); ?>

        <div class="carrusel__slider" 
            data-show-controls="<?=$show_controls?>" 
            data-show-nav="<?=$show_nav?>"
            data-nav-position="<?=$nav_position?>"
            data-mobile="<?=$items_in_mobile?>"
            data-tablet="<?=$items_in_tablet?>"
            data-laptop="<?=$items_in_laptop?>"
            data-desktop="<?=$items_in_desktop?>"
            data-mobile-gutter="<?=$gutter_in_mobile?>"
            data-tablet-gutter="<?=$gutter_in_tablet?>"
            data-laptop-gutter="<?=$gutter_in_laptop?>"
            data-desktop-gutter="<?=$gutter_in_desktop?>"
            data-autoplay="<?=$autoplay?>"
            data-auto-height="<?=$auto_height?>"
            data-axis="<?=$axis?>"
            data-mode="<?=$mode?>">
    
            <?php for ($i=0; $i < count($items); $i++) { 
                $type = $items[$i]['__type'];

                if( $type == 'item' ){
                    $imagen = $items[$i]['imagen'];
                    $bgi = wp_get_attachment_url($imagen);
                
                    $link = NULL;
                    $enlace = $items[$i]['enlace'];
                    switch ($enlace['url_type']) {
                        case 'externa':
                            $link = $enlace['url'];
                            break;
                        
                        case 'interna':
                            $link = get_permalink( str_replace('post_','',$enlace['post']) );
                            break;
                        
                        case 'popup':
                            $link = $bgi;
                            break;
                    }

                    $lightbox_class = ( $enlace['url_type'] == 'popup' ) ? 'zoom' : '';
                    ?>
                    <div class="carrusel__item carrusel__item--image">
                        <div class="component">
                            <img src="<?=$bgi?>" <?=$img_styles?> alt="Carrusel Item">
                            <?php if ($link != NULL): ?>
                                <?php $target = ($enlace['new_tab'] == 1) ? '_blank' : '';  ?>
                                <a class="carrusel__item__link <?=$lightbox_class?>" href="<?=$link?>" target="<?=$target?>"></a>
                                <?php endif ?>
                            </div>
                    </div>
                    <?php
                }
            
                if( $type == 'content' ){
                    $blocks_layout = $items[$i]['blocks_layout'];
                    if (is_array($blocks_layout) && count($blocks_layout) > 0) :
                        $item_attributes = Template_Engine::generate_attributes( $items[$i] );    
                        echo '<div class="carrusel__item carrusel__item--content">';
                        echo '<div '.$item_attributes.'>';
                        echo Blocks_Layout::the_content($blocks_layout);
                        echo Template_Engine::check_actions( $items[$i] );
                        echo '</div>';
                        echo '</div>';
                    endif;
                }
            
            }; ?>
        </div>
        <?php echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Carrusel();