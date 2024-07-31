<?php
namespace Theme_Custom_Fields\Component;

use Content_Selector;
use Ultimate_Fields\Field;
use Theme_Custom_Fields\Component;
use Theme_Custom_Fields\Common_Settings;
use Theme_Custom_Fields\Template_Engine;
use Ultimate_Fields\Container\Repeater_Group;

class Items_Grid extends Component {

    public function __construct() {
		parent::__construct(
			'Grid de Items',
			__( 'Items Grid', 'default' )
		);
	}

	public static function get_icon() {
        return 'dashicons-grid-view';
    }

	public static function get_fields() {
        $content_group = new Repeater_Group( 'Item' );
		$content_group->set_edit_mode('popup')
            ->set_title_template( '<% if ( componentes.length ){ %>
                    <% if ( componentes[0].__type == "editor-de-texto" ){ %>
                        <%= componentes[0].content.replace(/<[^>]+>/ig, "") %>
                    <% } else { %>
                        <%= "First item type: "+componentes[0][0].__type %>
                    <% } %>
                <% } else { %>
                    This item is empty
                <% } %>') 
			->add_fields(array(
                Field::create( 'tab', 'Contenido' ),
                Content_Selector::the_field('componentes', __('Components','default'), array( 'exclude' => array('columnas-internas') ) ),
                Field::create( 'tab', __('Settings','default') )
            ))
            ->add_fields( Common_Settings::get_fields('main') )
            ->add_fields( Common_Settings::get_fields('background-image') )
            ->add_fields( Common_Settings::get_fields('margins') )
            ->add_fields( Common_Settings::get_fields('borders') )
            ->add_fields( Common_Settings::get_fields('animation') )
            ->add_fields( Common_Settings::get_fields('box-shadow') );

		$fields = array(
            Field::create( 'tab', 'Contenido' ),
            Field::create( 'repeater', 'grid_items', '' )
                ->set_add_text('Agregar')
                ->add_group( $content_group ),

            Field::create( 'tab', 'Items por fila' ),
            Field::create( 'number', 'items_in_desktop', 'En desktop' )->set_width( 33 )->enable_slider( 1, 12 )->set_default_value(3),
            Field::create( 'number', 'items_in_tablet', 'En tablet' )->set_width( 33 )->enable_slider( 1, 12 )->set_default_value(2),
            Field::create( 'number', 'items_in_mobile', 'En móviles' )->set_width( 33 )->enable_slider( 1, 12 )->set_default_value(1),
        
            Field::create( 'tab', 'Márgenes' ),
            Field::create( 'number', 'components_margin', 'Márgenes de los componentes internos' )->enable_slider( 0, 20 )->set_default_value(0)
        );

		return $fields;
	}

	public static function display( $args ){
        $args['additional_classes'] = array('items-grid');

        $grid_items = $args['grid_items'];
        $nth_items = count($grid_items);
        if ($nth_items < 1) return; 

        $items_in_desktop = (!empty($args['items_in_desktop'])) ? $args['items_in_desktop'] : $nth_items;
        $items_in_tablet = (!empty($args['items_in_tablet'])) ? $args['items_in_tablet'] : $nth_items;
        $items_in_mobile = (!empty($args['items_in_mobile'])) ? $args['items_in_mobile'] : $nth_items;

        $components_margin = (!empty($args['components_margin'])) ? $args['components_margin'] : null;
        if ( $components_margin && $components_margin != 20) $args['additional_attributes'] =  'data-setmargin="'.$components_margin.'"';
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
        
        echo '<div class="items-grid__list l'.$items_in_desktop.' m'.$items_in_tablet.' s'.$items_in_mobile.'">';
		foreach ($grid_items as $item_args): 
			$item_args['__type'] = 'grid__item'; 
            $item_args['additional_classes'] = array('items-grid__list-item');
            
            echo Template_Engine::component_wrapper('start', $item_args);
            foreach ($item_args['components'] as $component_inside) {
                $component_inside['layout'] = 'layout1';
                echo Template_Engine::getInstance()->handle( $component_inside['__type'], $component_inside );
            }
			echo Template_Engine::component_wrapper('end', $item_args);

		endforeach;
	    echo '</div>';

		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}