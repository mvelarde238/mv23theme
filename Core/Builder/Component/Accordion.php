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

        $accordion_styles = apply_filters(
            'filter_accordion_styles_for_accordion_component',
            array(
                'style1'  => array(
                    'label' => 'Accordion style 1',
                    'image' => BUILDER_PATH . '/assets/images/accordion-style-1.png'
                )
            )
        );

        $blocks_layout_args = array();
        // restrict components by posttype
		$posttype = $_GET['post_type'] ?? get_post_type( $_GET['post'] ?? null);
		if( $posttype && is_array(CONTENT_BUILDER_SETTINGS) && isset(CONTENT_BUILDER_SETTINGS[$posttype]) ){
            $blocks_layout_args = wp_parse_args( CONTENT_BUILDER_SETTINGS[$posttype], $blocks_layout_args );
        }

        $fields = array(
            Field::create( 'tab', __('Content','mv23theme') ),
            Field::create( 'repeater', 'accordion' )
                ->set_add_text(  __('Add Item','mv23theme') )
                ->add_group('Item', array(
                    'edit_mode' => 'popup',                    
                    'title_template' => '<% if ( title ){ %><%= title %> <% } %><% if ( subtitle ){ %> - <%= subtitle %><% } %>',
                    'layout' => 'rows',
                    'fields' => array(
                        Field::create( 'tab', __('Content','mv23theme') ),
                        Field::create( 'text', 'title' )
                            ->set_attr( 'style', 'background-color: #f0f0f0;' ),
                        Field::create( 'text', 'subtitle' ),
                        Field::create( 'complex', '__identifier-wrapper', __('Element before the title','mv23theme'))->merge()->add_fields(array(
                            Field::create( 'radio', 'identifier' )->set_orientation( 'horizontal' )->hide_label()->add_options( array(
                                '' => __('None','mv23theme'),
                                'icon' => __('Icon','mv23theme'),
                                'image' => __('Image','mv23theme'),
                            ))->set_width( 15 ),
                            Field::create( 'icon', 'icon', __('Icon','mv23theme') )
                                ->hide_label()
                                ->add_set( 'bootstrap-icons' )
                                ->add_set( 'font-awesome' )
                                ->add_dependency('identifier','icon','=')->set_width( 15 ),
                            Field::create( 'image', 'image', __('Image','mv23theme') )
                                ->hide_label()
                                ->add_dependency('identifier','image','=')
                                ->set_width( 15 ),
                            Field::create( 'select', 'image_size' )
                                ->hide_label()
                                ->add_dependency('identifier','image','=')
                                ->set_prefix(__('Image Size','mv23theme'))
                                ->add_options(array(
                                    'iconsize' => __('Small','mv23theme'),
                                    'auto' => __('Automatic','mv23theme')
                            ))->set_width( 15 )
                        )),

                        Field::create( 'section', __('Item content:','mv23theme') ),
                        Field::create( 'wysiwyg', 'content', __('Content','mv23theme') )->add_dependency('content_element','text','=')->hide_label()->set_rows( 30 ),
                        Blocks_Layout::the_field( $blocks_layout_args )->add_dependency('content_element','layout','='),
                        Field::create( 'wp_objects', 'page', 'Página' )->add( 'posts', 'page' )->set_button_text( 'Selecciona la página' )->add_dependency('content_element','page','=')->hide_label(),
                        Field::create( 'select', 'reusable_section', 'Seleccionar Sección Reusable' )
                            ->add_options( Reusable_Section_CPT::getInstance()->get_reusable_sections() )
                            ->add_dependency('content_element','reusable_section','=')
                            ->hide_label(),

                        Field::create( 'tab', __('Other','mv23theme') ),
                        Field::create( 'text', 'itemid', 'ID' ),
                        Field::create( 'radio', 'content_element', __('Select what to display as Content:','mv23theme'))->set_orientation( 'horizontal' )->add_options( array(
                            'layout' => __('Layout','mv23theme'),
                            'text' => __('Text','mv23theme'),
                            'page' => __('Page','mv23theme'),
                            'reusable_section' => __( 'Reusable Section', 'mv23theme' ),
                        ))->set_default_value('layout')
                    )
                )
            ),
            Field::create( 'tab', 'Desktop' ),
            Field::create( 'select', 'desktop_template', __('Desktop Appearance','mv23theme') )->add_options( array(    
                'accordion' => 'Accordion',
                'tab' => 'Tab',
            ))->set_default_value('accordion')->set_width(25),
            Field::create( 'image_select', 'tab_style', __('Appearance','mv23theme') )->add_options( $tab_styles )->show_label()->add_dependency('desktop_template','tab','=')->set_width(25),
            Field::create( 'image_select', 'accordion_style', __('Appearance','mv23theme') )->add_options( $accordion_styles )->show_label()->add_dependency('desktop_template','accordion','=')->set_width(25),
            Field::create('complex','tab_settings')->add_fields(array(
                Field::create('checkbox','close_first_tab')->set_text(__('Close first tab','mv23theme'))->hide_label()
            ))->set_width(25)->add_dependency('desktop_template','tab','='),
        
            Field::create( 'tab', 'Mobile' ),
            Field::create( 'select', 'mobile_template', __('Mobile Appearance','mv23theme') )->add_options( array(    
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

        $togglebox_attributes = $args['togglebox_attributes'] ?? array();
        if($args['desktop_template'] == 'accordion') $togglebox_attributes[] = 'data-template="accordion"';
        if($args['mobile_template'] == 'tab') $togglebox_attributes[] = 'data-breakpoints="768|tab"';
        $tab_settings = (isset($args['tab_settings'])) ? $args['tab_settings'] : array('close_first_tab'=>0);
        if( $tab_settings['close_first_tab'] == 1 ) $togglebox_attributes[] = 'data-openfirsttab="false"';

        $tab_style = (isset($args['tab_style'])) ? $args['tab_style'] : 'style1';
        if($tab_style == 'style1' || $tab_style == 'style2') $tab_style = 'tab-'.$tab_style;

        $accordion_style = (isset($args['accordion_style'])) ? $args['accordion_style'] : 'style1';
        if($accordion_style == 'style1' ) $accordion_style = 'acc-'.$accordion_style;

        // .maybe-fix-scroll-position implementation
        if( 
            ( $args['desktop_template'] == 'accordion' || $args['mobile_template'] == 'accordion' ) ||
            ( ($args['desktop_template'] == 'tab' || $args['mobile_template'] == 'tab') && $args['tab_style'] == 'style1' )
        ) {
            $accordion_style .= ' maybe-fix-scroll-position';
        }
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);

        if (is_array($items) && count($items)>0): ?>
            <div class="v23-togglebox <?php echo $tab_style .' '. $accordion_style ?>" <?php echo implode(' ',$togglebox_attributes) ?>>
                <?php
                $nav = '<div class="v23-togglebox__nav">';
                $itemsbox = '<div class="v23-togglebox__items">';
                $count = 1;
                foreach ($items as $item): 
                    $title = '<span class="v23-togglebox__title">'.$item['title'].'</span>';
                    $subtitle = (isset($item['subtitle']) && $item['subtitle']) ? '<span class="v23-togglebox__subtitle">'.$item['subtitle'].'</span>' : '';
                    $itemid = (isset($item['itemid'])) ? $item['itemid'] : false;
                    $slug = ($itemid) ? $itemid : sanitize_title($title);
                    if( preg_match('@^[0-9]@',$slug) ) $slug = 'item-'.$slug;
    
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
                            $icon_html = ($icon) ? '<i class="'.$icon_prefix.' '.$icon.'"></i>' : '';
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

                    $count_str = ($count < 10) ? '0'.$count : $count;
                    $nav .= '<p class="v23-togglebox__btn" data-boxid="#'.$slug.'" data-count="'.$count_str.'">'.$icon_html.$title.$subtitle.'</p>';
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