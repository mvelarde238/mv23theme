<?php
namespace Core\Builder\Component;

use Core\Builder\Content_Selector;
use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Container\Repeater_Group;

class Items_Grid extends Component {

    public function __construct() {
		parent::__construct(
			'items_grid',
			__( 'Items Grid', 'mv23theme' )
		);
	}

	public static function get_icon() {
        return 'dashicons-grid-view';
    }

	public static function get_fields() {
        $content_group = new Repeater_Group( 'Item' );
		$content_group->set_edit_mode('popup')
            ->set_title_template( '<% if ( components.length ){ %>
                    <% if ( components[0].__type == "editor-de-texto" ){ %>
                        <%= components[0].content.replace(/<[^>]+>/ig, "") %>
                    <% } else { %>
                        <%= "First item type: "+components[0][0].__type %>
                    <% } %>
                <% } else { %>
                    This item is empty
                <% } %>') 
			->add_fields(array(
                Field::create( 'tab', __('Content','mv23theme') ),
                Content_Selector::the_field('components', __('Components','mv23theme'), array( 'exclude' => array('inner_columns') ) ),
                Field::create( 'tab', __('Settings','mv23theme') ),
                Field::create( 'common_settings_control', 'settings' )->set_container( 'common_settings_container' )
            ));

		$fields = array(
            Field::create( 'tab', __('Content','mv23theme') ),
            Field::create( 'repeater', 'items', '' )
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
        if( Template_Engine::is_private( $args ) ) return;
        
        $args['additional_classes'] = array('items-grid');

        $items = $args['items'];
        $nth_items = count($items);
        if ($nth_items < 1) return; 

        $items_in_desktop = (!empty($args['items_in_desktop'])) ? $args['items_in_desktop'] : $nth_items;
        $items_in_tablet = (!empty($args['items_in_tablet'])) ? $args['items_in_tablet'] : $nth_items;
        $items_in_mobile = (!empty($args['items_in_mobile'])) ? $args['items_in_mobile'] : $nth_items;

        $components_margin = (!empty($args['components_margin'])) ? $args['components_margin'] : null;
        if ( $components_margin && $components_margin != 20) $args['additional_attributes'] =  'data-setmargin="'.$components_margin.'"';
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
        
        echo '<div class="items-grid__list l'.$items_in_desktop.' m'.$items_in_tablet.' s'.$items_in_mobile.'">';
		foreach ($items as $item_args): 
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