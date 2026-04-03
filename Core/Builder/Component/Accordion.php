<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Frontend\Page;
use Core\Posttype\Reusable_Section_CPT;
use Core\Builder\Core as Builder_Core;

class Accordion extends Component {

    public function __construct() {
		parent::__construct(
			'togglebox',
			__( 'Accordion', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'dashicons-list-view';
    }

    public static function get_builder_data() {
        return array(
            'display_gjs_block' => false
		);
    }

	public static function get_fields() {
        // tab styles
        $tab_styles_array = array();
        $tab_styles_quantity = 7;
        for ($i=1; $i <= $tab_styles_quantity ; $i++) { 
            $tab_styles_array['tab-style'.$i] = array(
                'label' => 'Tab style '.$i,
                'image' => BUILDER_PATH . '/assets/images/toggleboxes/tab-style-'.$i.'.png'
            );
        }
        $tab_styles_array['horizontal-tabs'] = array(
            'label' => 'Horizontal tabs',
            'image' => BUILDER_PATH . '/assets/images/toggleboxes/horizontal-tabs.png'
        );
        $tab_styles_array['vertical-tabs'] = array(
            'label' => 'Vertical tabs',
            'image' => BUILDER_PATH . '/assets/images/toggleboxes/vertical-tabs.png'
        );
        $tab_styles = apply_filters(
            'filter_tab_styles_for_accordion_component',
            $tab_styles_array
        );

        // accordion styles
        $accordion_styles = apply_filters(
            'filter_accordion_styles_for_accordion_component',
            array(
                'accordion-style1'  => array(
                    'label' => 'Accordion style 1',
                    'image' => BUILDER_PATH . '/assets/images/toggleboxes/accordion-style-1.png'
                ),
                'accordion-style2'  => array(
                    'label' => 'Accordion style 2',
                    'image' => BUILDER_PATH . '/assets/images/toggleboxes/accordion-style-2.png'
                ),
                'vertical-accordion'  => array(
                    'label' => 'Vertical accordion',
                    'image' => BUILDER_PATH . '/assets/images/toggleboxes/vertical-accordion.png'
                )
            )
        );

        // fields
        $fields = array(
            Field::create( 'select', 'template', __('Template','mv23theme') )
                ->set_default_value('tab')
                ->add_options( array(    
                    'accordion' => 'Accordion',
                    'tab' => 'Tab',
                ))
                ->set_width( 50 ),
            Field::create( 'select', 'animation', __('Animation','mv23theme') )
                ->set_default_value('fadeIn')
                ->add_options( array(    
                    'none' => __('None','mv23theme'),
                    'fadeIn' => __('Fade In','mv23theme'),
                    'scaleIn' => __('Scale In','mv23theme'),
                    'leftToRight' => __('Left to Right','mv23theme'),
                    'rightToLeft' => __('Right to Left','mv23theme'),
                    'topToBottom' => __('Top to Bottom','mv23theme'),
                    'bottomToTop' => __('Bottom to Top','mv23theme'),
                ))
                ->set_width( 50 ),
            Field::create( 'image_select', 'tab_style', __('Style','mv23theme') )
                ->set_attr( 'class', 'image-select-2-cols' )
                ->add_options( $tab_styles )
                ->set_default_value('tab-style1')
                ->add_dependency('template','tab','='),
            Field::create( 'image_select', 'accordion_style', __('Style','mv23theme') )
                ->set_attr( 'class', 'image-select-2-cols' )
                ->add_options( $accordion_styles )->add_dependency('template','accordion','=')
        );

		return $fields;
	}

	public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
		$args['additional_classes'][] = 'component';
        
        // data breakpoints
        $breakpoints = '';
        if( isset($args['devicesControl']) && is_array($args['devicesControl']) ){
            $breakpoints_arr = array();
            foreach ($args['devicesControl'] as $device => $values) {
                $template = $values['template'] ?? '';
                $style = $values['style'] ?? '';
                $breakpoints_arr[] = "{$device}|{$template}|{$style}";
            }
            $breakpoints = implode(',', $breakpoints_arr);
        }
        if( !empty($breakpoints) ){
            $args['additional_attributes']['data-breakpoints'] = $breakpoints;
        }

        // animation
        $animation = $args['animation'] ?? 'fadeIn';
        if( $animation != 'fadeIn' ){
            $args['additional_attributes']['style'] = '--item-animation:'.$animation;
        }

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);

        if( isset($args['components']) && is_array($args['components']) ){
            $the_accordion_id = $args['__id'];

            $the_accordion_nav = $args['components'][0] ?? array();
            $the_accordion_items_wrapper = $args['components'][1] ?? array();

            if( isset($the_accordion_nav['components']) && is_array($the_accordion_nav['components']) ){
                // is a tab estructure
                $the_accordion_buttons = $the_accordion_nav['components'] ?? array();
                $the_accordion_items = $the_accordion_items_wrapper['components'] ?? array();
            } else {
                // is a accordion estructure, buttons and items are inside the items wrapper
                $the_accordion_buttons = array();
                $the_accordion_items = array();
                if( isset($the_accordion_items_wrapper['components']) && is_array($the_accordion_items_wrapper['components']) ){
                    foreach ($the_accordion_items_wrapper['components'] as $comp) {
                        if( isset($comp['title']) ){
                            $the_accordion_buttons[] = $comp;
                        } else{
                            $the_accordion_items[] = $comp;
                        }
                    }
                }
            }

            // generate slugs for buttons/items relationship
            $slugs = [];
            foreach ($the_accordion_items as $item){
                $slug = $the_accordion_id.'-item-'.uniqid();
                $id = (!empty($item['attributes']['id'])) ? $item['attributes']['id'] : $slug;
                $item['attributes']['id'] = $id;
                $slugs[] = $id;
            }

            $nav = '<div class="togglebox__nav">';
            $itemsbox = '<div class="togglebox__items">';
            
            // generate buttons and items html
            $count = 0;
            foreach ($the_accordion_buttons as $button){
                $count_str = ($count < 10) ? '0'.($count + 1) : ($count + 1);
                $button['count'] = $count_str;
                $button['itemid'] = $slugs[$count];
                $nav .= Template_Engine::getInstance()->handle( $button );
                $count++;
            }

            $count = 0;
            foreach ($the_accordion_items as $item){
                $slug = $slugs[$count];
                $id = (!empty($item['attributes']['id'])) ? $item['attributes']['id'] : $slug;
                $item['additional_attributes']['id'] = $id;
                $itemsbox .= Template_Engine::getInstance()->handle( $item );
                $count++;
            }

            $nav .= '</div>';
            $itemsbox .= '</div>';
            echo $nav . $itemsbox;
		}

		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Accordion();