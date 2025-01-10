<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Blocks_Layout;
use Core\Frontend\Page;
use Core\Posttype\Reusable_Section_CPT;

class Accordion extends Component {

    public function __construct() {
		parent::__construct(
			'accordion',
			__( 'Accordion', 'mv23theme' )
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
        $tab_styles = apply_filters(
            'filter_tab_styles_for_accordion_component',
            array(
                'style1'  => array(
                    'label' => 'Tab style 1',
                    'image' => BUILDER_PATH . '/assets/images/tab-style-1.png'
                ),
                'style2'  => array(
                    'label' => 'Tab style 2',
                    'image' => BUILDER_PATH . '/assets/images/tab-style-2.png'
                )
            )
        );

        $fields = array(
            Field::create( 'tab', __('Content','mv23theme') ),
            Field::create( 'repeater', 'accordion' )
                ->set_add_text('Agregar Item')
                ->add_group('Item', array(
                    'edit_mode' => 'popup',
                    'fields' => array(
                        Field::create( 'tab', __('Content','mv23theme') ),
                        Field::create( 'text', 'title', 'Título' )->set_width( 30 )->set_attr( 'style', 'background: #eeee; width: 15%;' ),
                        Field::create( 'radio', 'identifier','Seleccione que mostrar antes del título:')->set_orientation( 'horizontal' )->add_options( array(
                                '' => 'Nada',
                                'icon' => 'Icono',
                                'image' => 'Imagen',
                        ))->set_width( 15 )->set_attr( 'style', 'background: #eeee; width: 15%;' ),
                        Field::create( 'icon', 'icon', 'Icono' )
                            ->add_set( 'bootstrap-icons' )
                            ->add_set( 'font-awesome' )
                            ->add_dependency('identifier','icon','=')->set_width( 15 )->set_attr( 'style', 'background: #eeee; width: 15%;' ),
                        Field::create( 'image', 'image', 'Imágen' )->add_dependency('identifier','image','=')->set_width( 15 )->set_attr( 'style', 'background: #eeee; width: 15%;' ),
                        Field::create( 'select', 'image_size', 'Tamaño de la imágen' )
                            ->add_dependency('identifier','image','=')
                            ->add_dependency('../tab_style','style1','=')
                            ->add_options(array(
                                'iconsize' => 'Pequeño',
                                'auto' => 'Automático'
                        ))->set_width( 15 )->set_attr( 'style', 'background: #eeee; width: 15%;' ),
                        
                        Field::create( 'section', 'Contenido del Item:' ),
                        Field::create( 'wysiwyg', 'content', __('Content','mv23theme') )->add_dependency('content_element','text','=')->hide_label()->set_rows( 30 ),
                        Blocks_Layout::the_field( array() )->add_dependency('content_element','layout','='),
                        Field::create( 'wp_objects', 'page', 'Página' )->add( 'posts', 'page' )->set_button_text( 'Selecciona la página' )->add_dependency('content_element','page','=')->hide_label(),
                        Field::create( 'select', 'reusable_section', 'Seleccionar Sección Reusable' )
                            ->add_options( Reusable_Section_CPT::getInstance()->get_reusable_sections() )
                            ->add_dependency('content_element','reusable_section','=')
                            ->hide_label(),
    
                        Field::create( 'tab', 'Otros' ),
                        Field::create( 'text', 'itemid', 'ID' ),
                        Field::create( 'radio', 'content_element','Seleccione que mostrar como Contenido:')->set_orientation( 'horizontal' )->add_options( array(
                            'layout' => __('Layout','mv23theme'),
                            'text' => __('Text','mv23theme'),
                            'page' => __('Page','mv23theme'),
                            'reusable_section' => __( 'Reusable Section', 'mv23theme' ),
                        ))->set_default_value('layout'),
                    )
                )
            ),
            Field::create( 'tab', 'Desktop' ),
            Field::create( 'select', 'desktop_template', 'Apariencia en Desktop' )->add_options( array(    
                'accordion' => 'Accordion',
                'tab' => 'Tab',
            ))->set_default_value('accordion')->set_width(25),
            Field::create( 'image_select', 'tab_style', 'Apariencia' )->add_options( $tab_styles )->show_label()->add_dependency('desktop_template','tab','=')->set_width(25),
            Field::create('complex','tab_settings')->add_fields(array(
                Field::create('checkbox','close_first_tab')->set_text('Cerrar primer tab')->hide_label()
            ))->set_width(25)->add_dependency('desktop_template','tab','='),
        
            Field::create( 'tab', 'Mobile' ),
            Field::create( 'select', 'mobile_template', 'Apariencia en Móviles' )->add_options( array(    
                'accordion' => 'Accordion',
                'tab' => 'Tab',
            ))->set_default_value('accordion')->set_width(33)
        );

		return $fields;
	}

	public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
		$args['additional_classes'] = array('component');

        $items = $args['accordion'];

        $data_attributes = '';
        if($args['desktop_template'] == 'accordion') $data_attributes .= 'data-template="accordion" ';
        if($args['mobile_template'] == 'tab') $data_attributes .= 'data-breakpoints="768|tab" ';
        $tab_settings = (isset($args['tab_settings'])) ? $args['tab_settings'] : array('close_first_tab'=>0);
        if( $tab_settings['close_first_tab'] == 1 ) $data_attributes .= 'data-openfirsttab="false" ';

        $tab_style = (isset($args['tab_style'])) ? $args['tab_style'] : 'style1';
        if($tab_style == 'style1' || $tab_style == 'style2') $tab_style = 'tab-'.$tab_style;
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);

        if (is_array($items) && count($items)>0): ?>
            <div class="v23-togglebox <?php echo $tab_style ?>" <?=$data_attributes?>>
                <?php
                $nav = '<div class="v23-togglebox__nav">';
                $itemsbox = '<div class="v23-togglebox__items">';
                $count = 0;
                foreach ($items as $item): 
                    $title = $item['title'];
                    $itemid = (isset($item['itemid'])) ? $item['itemid'] : false;
                    $slug = ($itemid) ? $itemid : sanitize_title($title);
                    if( preg_match('@[0-9]@i',$slug) ) $slug = 'tab-'.$slug;
    
                    $identifier = $item['identifier'] ?? '';
    
                    switch ($identifier) {
                        case 'image':
                            $image = wp_get_attachment_url($item['image']);
                            $style = (isset($item['image_size']) && $item['image_size'] == 'auto') ? 'style="height:auto;width:auto;"' : '';
                            $icon_html = ($image) ? '<img '.$style.' src="'.$image .'" />' : '';
                            break;
                        
                        case 'icon':
                            $icon = $item['icon'];
                            $icon_prefix = (str_starts_with($icon,'fa')) ? 'fa' : 'bi';
                            $icon_html = ($icon) ? '<span class="'.$icon_prefix.' '.$icon.'"></span>' : '';
                            break;
    
                        default:
                            $icon_html = '';
                            break;
                    };
    
                    $content_element = $item['content_element'] ?? '';
                    $contenido = '';
    
                    if ($content_element == 'page' && $item['page']) {

                        $post_data = $item['page'][0]; 
                        $post_id = str_replace('post_', '', $post_data );
                        if($post_id){
                            ob_start();
                            $page = new Page();
                            echo $page->the_content( $post_id );
                            $contenido = ob_get_clean();
                        }
    
                    } else if( $content_element == 'layout' ) {

                        $blocks_layout = $item['blocks_layout'];
                        if (is_array($blocks_layout) && count($blocks_layout) > 0) :
                            ob_start();
                            echo Blocks_Layout::the_content($blocks_layout);
                            $contenido = ob_get_clean();
                        endif;
    
                    } else if( $content_element == 'reusable_section' ) {
                    
                        ob_start();
                        echo Template_Engine::getInstance()->handle( 'reusable_section', $item );    
                        $contenido = ob_get_clean();
                        
                    } else {
                        $contenido = '<div class="component">'.do_shortcode(wpautop($item['content'])).'</div>';
                    }
    
                    $nav .= '<p class="v23-togglebox__btn" data-boxid="#'.$slug.'">'.$icon_html.$title.'</p>';
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