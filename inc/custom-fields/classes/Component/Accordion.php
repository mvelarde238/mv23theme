<?php
namespace Theme_Custom_Fields\Component;

use Ultimate_Fields\Field;
use Theme_Custom_Fields\Component;
use Theme_Custom_Fields\Template_Engine;
use Content_Layout;
use Page;

class Accordion extends Component {

    public function __construct() {
		parent::__construct(
			'Accordion',
			__( 'Accordion', 'default' )
		);
	}

    public static function get_icon() {
        return 'dashicons-list-view';
    }

    public static function get_title_template() {
		$template = '<% if ( accordion.length ){ %>
            <%= accordion.length %> Items 
            | Desktop template: <%= desktop_template %> | Mobile template: <%= mobile_template %>
        <% } else { %>
            This component is empty
        <% } %>';
		
		return $template;
	}

	public static function get_fields() {
        $fields = array(
            Field::create( 'tab', 'Contenido' ),
            Field::create( 'repeater', 'accordion' )
                ->set_add_text('Agregar Item')
                ->add_group('Item', array(
                    'edit_mode' => 'popup',
                    'fields' => array(
                        Field::create( 'tab', 'Contenido' ),
                        Field::create( 'text', 'titulo', 'Título' )->set_width( 30 )->set_attr( 'style', 'background: #eeee; width: 15%;' ),
                        Field::create( 'radio', 'identificador','Seleccione que mostrar antes del título:')->set_orientation( 'horizontal' )->add_options( array(
                                '' => 'Nada',
                                'icono' => 'Icono',
                                'imagen' => 'Imagen',
                        ))->set_width( 15 )->set_attr( 'style', 'background: #eeee; width: 15%;' ),
                        Field::create( 'icon', 'icon', 'Icono' )->add_set( 'font-awesome' )->add_dependency('identificador','icono','=')->set_width( 15 )->set_attr( 'style', 'background: #eeee; width: 15%;' ),
                        Field::create( 'image', 'image', 'Imágen' )->add_dependency('identificador','imagen','=')->set_width( 15 )->set_attr( 'style', 'background: #eeee; width: 15%;' ),
                        Field::create( 'select', 'image_size', 'Tamaño de la imágen' )
                            ->add_dependency('identificador','imagen','=')
                            ->add_dependency('../tab_style','style1','=')
                            ->add_options(array(
                                'iconsize' => 'Pequeño',
                                'auto' => 'Automático'
                        ))->set_width( 15 )->set_attr( 'style', 'background: #eeee; width: 15%;' ),
                        
                        Field::create( 'section', 'Contenido del Item:' ),
                        Field::create( 'wysiwyg', 'content', 'Contenido' )->add_dependency('content_element','texto','=')->hide_label()->set_rows( 30 ),
                        Content_Layout::the_field( array() )->add_dependency('content_element','layout','='),
                        Field::create( 'wp_objects', 'page', 'Página' )->add( 'posts', 'page' )->set_button_text( 'Selecciona la página' )->add_dependency('content_element','pagina','=')->hide_label(),
                        Field::create( 'select', 'seccion_reusable', 'Seleccionar Sección Reusable' )
                            ->add_options( get_reusable_sections() )
                            ->add_dependency('content_element','seccion_reusable','=')
                            ->hide_label(),
    
                        Field::create( 'tab', 'Otros' ),
                        Field::create( 'text', 'itemid', 'ID' ),
                        Field::create( 'radio', 'content_element','Seleccione que mostrar como Contenido:')->set_orientation( 'horizontal' )->add_options( array(
                            'layout' => 'Editor',
                            'texto' => 'Texto',
                            'pagina' => 'Página',
                            'seccion_reusable' => 'Seccion Reusable',
                        ))->set_default_value('layout'),
                    )
                )
            ),
            Field::create( 'tab', 'Desktop' ),
            Field::create( 'select', 'desktop_template', 'Apariencia en Desktop' )->add_options( array(    
                'accordion' => 'Accordion',
                'tab' => 'Tab',
            ))->set_default_value('accordion')->set_width(25),
            Field::create( 'image_select', 'tab_style', 'Apariencia' )->add_options(array(
                'style1'  => array(
                    'label' => 'Estilo 1',
                    'image' => THEME_CUSTOM_FIELDS_PATH . '/images/tab-style-1.png'
                ),
                'style2'  => array(
                    'label' => 'Estilo 2',
                    'image' => THEME_CUSTOM_FIELDS_PATH . '/images/tab-style-2.png'
                )
            ))->set_width(25)->add_dependency('desktop_template','tab','='),
            Field::create('complex','tab_settings')->add_fields(array(
                Field::create('checkbox','close_first_tab')->set_text('Cerrar primer tab')->hide_label()
            ))->set_width(25)->add_dependency('desktop_template','tab','='),
            // Field::create( 'color', 'accent_color' )->set_width( 25 )->add_dependency('desktop_template','tab','=')->set_default_value(get_main_color()),
        
            Field::create( 'tab', 'Mobile' ),
            Field::create( 'select', 'mobile_template', 'Apariencia en Móviles' )->add_options( array(    
                'accordion' => 'Accordion',
                'tab' => 'Tab',
            ))->set_default_value('accordion')->set_width(33)
        );

		return $fields;
	}

	public static function display( $args ){
		$args['additional_classes'] = array('componente');

        $items = $args['accordion'];

        $data_attributes = '';
        if($args['desktop_template'] == 'accordion') $data_attributes .= 'data-template="accordion" ';
        if($args['mobile_template'] == 'tab') $data_attributes .= 'data-breakpoints="768|tab" ';
        $tab_settings = (isset($args['tab_settings'])) ? $args['tab_settings'] : array('close_first_tab'=>0);
        if( $tab_settings['close_first_tab'] == 1 ) $data_attributes .= 'data-openfirsttab="false" ';

        $tab_style = (isset($args['tab_style'])) ? $args['tab_style'] : 'style1';
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);

        if (is_array($items) && count($items)>0): ?>
            <div class="v23-togglebox <?php echo 'tab-'.$tab_style ?>" <?=$data_attributes?>>
                <?php
                $nav = '<div class="v23-togglebox__nav">';
                $itemsbox = '<div class="v23-togglebox__items">';
                $count = 0;
                foreach ($items as $item): 
                    $titulo = $item['titulo'];
                    $itemid = (isset($item['itemid'])) ? $item['itemid'] : false;
                    $slug = ($itemid) ? $itemid : sanitize_title($titulo);
                    if( preg_match('@[0-9]@i',$slug) ) $slug = 'tab-'.$slug;
    
                    $identificador = $item['identificador'];
    
                    switch ($identificador) {
                        case 'imagen':
                            $image = wp_get_attachment_url($item['image']);
                            $style = (isset($item['image_size']) && $item['image_size'] == 'auto') ? 'style="height:auto;width:auto;"' : '';
                            $icon_html = ($image) ? '<img '.$style.' src="'.$image .'" />' : '';
                            break;
                        
                        case 'icono':
                            $icon = $item['icon'];
                            $icon_html = ($icon) ? '<span class="fa '.$icon.'"></span>' : '';
                            break;
    
                        default:
                            $icon_html = '';
                            break;
                    };
    
                    $content_element = $item['content_element'];
                    $contenido = '';
    
                    if ($content_element == 'pagina' && $item['page']) {

                        $post_data = $item['page'][0]; 
                        $post_id = str_replace('post_', '', $post_data );
                        if($post_id){
                            ob_start();
                            echo Page::getInstance()->the_content( $post_id );
                            $contenido = ob_get_clean();
                        }
    
                    } else if( $content_element == 'layout' ) {

                        $content_layout = $item['content_layout'];
                        if (is_array($content_layout) && count($content_layout) > 0) :
                            ob_start();
                            echo Content_Layout::the_content($content_layout);
                            $contenido = ob_get_clean();
                        endif;
    
                    } else if( $content_element == 'seccion_reusable' ) {
                    
                        ob_start();
                        echo Template_Engine::getInstance()->handle( 'modulos-reusables', $item );    
                        $contenido = ob_get_clean();
                        
                    } else {
                        $contenido = '<div class="componente">'.do_shortcode(wpautop(oembed($item['content']))).'</div>';
                    }
    
                    $nav .= '<p class="v23-togglebox__btn" data-boxid="#'.$slug.'">'.$icon_html.$titulo.'</p>';
                    $itemsbox .= '<div id="'.$slug.'" class="v23-togglebox__item">'.$contenido.'</div>';
                    $count++;
                endforeach; 
                $nav .= '</div>';
                $itemsbox .= '</div>';
                echo $nav . $itemsbox;
                ?>
            </div>
        <?php endif;

		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Accordion();