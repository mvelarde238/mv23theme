<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
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

	public static function get_fields() {
        $tab_styles_array = array();
        $tab_styles_quantity = 7;
        for ($i=1; $i <= $tab_styles_quantity ; $i++) { 
            $tab_styles_array['tab-style'.$i] = array(
                'label' => 'Tab style '.$i,
                'image' => BUILDER_PATH . '/assets/images/tab-style-'.$i.'.png'
            );
        }

        $tab_styles = apply_filters(
            'filter_tab_styles_for_accordion_component',
            $tab_styles_array
        );

        $accordion_styles = apply_filters(
            'filter_accordion_styles_for_accordion_component',
            array(
                'accordion-style1'  => array(
                    'label' => 'Accordion style 1',
                    'image' => BUILDER_PATH . '/assets/images/accordion-style-1.png'
                ),
                'accordion-style2'  => array(
                    'label' => 'Accordion style 2',
                    'image' => BUILDER_PATH . '/assets/images/accordion-style-2.png'
                )
            )
        );

        $fields = array(
            Field::create( 'select', 'template', __('Template','mv23theme') )->add_options( array(    
                'accordion' => 'Accordion',
                'tab' => 'Tab',
            ))->set_default_value('tab'),
            Field::create( 'image_select', 'tab_style', __('Style','mv23theme') )
                ->set_attr( 'class', 'image-select-3-cols' )
                ->add_options( $tab_styles )->show_label()->add_dependency('template','tab','='),
            Field::create( 'image_select', 'accordion_style', __('Style','mv23theme') )
                ->set_attr( 'class', 'image-select-3-cols' )
                ->add_options( $accordion_styles )->show_label()->add_dependency('template','accordion','=')
        );

		return $fields;
	}

	public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
		$args['additional_classes'] = array('component');

        // classes for the togglebox
        $togglebox_classes = array('v23-togglebox');
        
        // data breakpoints
        $breakpoints = $args['__gjs_data_breakpoints'] ?? '';
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);

        if( isset($args['components']) && is_array($args['components']) ){
            $the_accordion = $args['components'][0]; // accordion is inside a accordion wrapper
            $the_accordion_id = $args['__id'];

            $the_accordion_nav = $the_accordion['components'][0] ?? array();
            $the_accordion_items_wrapper = $the_accordion['components'][1] ?? array();

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
            ?>
            <div class="<?php echo implode(' ', $togglebox_classes) ?>" 
                data-breakpoints="<?php echo $breakpoints ?>">
                <?php
                $nav = '<div class="v23-togglebox__nav">';
                $itemsbox = '<div class="v23-togglebox__items">';
                $slugs = [];
                
                $count = 1;
                foreach ($the_accordion_buttons as $button){
                    $slug = $the_accordion_id.'-item-'.$count;
                    $count_str = ($count < 10) ? '0'.$count : $count;
                    $button['count'] = $count_str;
                    $itemid = (!empty($button['itemid'])) ? $button['itemid'] : $slug;
                    $button['itemid'] = $itemid;
                    $slugs[] = $itemid;
                    $nav .= Template_Engine::getInstance()->handle( $button['__type'], $button );
                    $count++;
                }

                $count = 0;
                foreach ($the_accordion_items as $item){
                    $slug = $slugs[$count];
                    $itemsbox .= '<div id="'.$slug.'" class="v23-togglebox__item">';
                    if( isset($item['components']) && is_array($item['components']) ){
                        foreach ($item['components'] as $component) {
                            $itemsbox .= Template_Engine::getInstance()->handle( $component['__type'], $component );
                        }
                    }
                    $itemsbox .= '</div>';
                    $count++;
                }

                $nav .= '</div>';
                $itemsbox .= '</div>';
                echo $nav . $itemsbox;
                ?>
            </div>
            <?php
		}

		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Accordion();