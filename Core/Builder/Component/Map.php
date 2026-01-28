<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Map extends Component {

    public function __construct() {
		parent::__construct(
			'map-component',
			__( 'Map', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'dashicons-location';
    }

	public static function get_fields() {
		$fields = array(
            Field::create( 'tab', __('Content','mv23theme')),
            Field::create( 'map', 'location' )->set_output_width( '100%' )->set_output_height( 280 ),
            
            Field::create( 'tab', __('Marker','mv23theme')),
            Field::create( 'complex', 'icon_data')->hide_label()->add_fields(array(
                Field::create( 'image', 'icon' )->set_attr( 'style', 'width:33%; min-width:initial;' ),
                Field::create( 'number', 'width' )->set_default_value( 38 )->set_attr( 'style', 'width:33%; min-width:initial;' ),
                Field::create( 'number', 'height' )->set_default_value( 38 )->set_attr( 'style', 'width:33%; min-width:initial;' )
            )),
    
            Field::create( 'tab', 'Info Window'),
            Field::create( 'wysiwyg', 'info_window_content' )->hide_label()->set_rows( 15 ),
        );

		return $fields;
	}
    
    public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;

        $location = $args['location'];
        if (!is_array($location)) return '';
        if (!isset($location['latLng'])) return '';
        
        $provider = $location['provider'] ?? '';
        if($provider == 'google' && !GM_IS_ACTIVE) return; 
        if($provider == 'leaflet' && !LEAFLET_IS_ACTIVE) return; 
        
		$args['additional_classes'][] = 'map';
		$args['additional_classes'][] = 'component';

        $info_window_content = (isset($args['info_window_content'])) ? $args['info_window_content'] : '';
        $lat = $location['latLng']['lat'];
        $lng = $location['latLng']['lng'];
        $zoom = $location['zoom'];

        $icon_url = '';
        $icon_size = [38, 38];
        $icon_data = $args['icon_data'];
        if( is_array($icon_data) && isset($icon_data['icon']) && !empty($icon_data['icon']) ) {
            $icon_url = wp_get_attachment_url( $icon_data['icon'] );
            $width = isset($icon_data['width']) ? intval($icon_data['width']) : 38;
            $height = isset($icon_data['height']) ? intval($icon_data['height']) : 38;
            $icon_size = [$width, $height];
        }

		ob_start();
        echo Template_Engine::component_wrapper('start', $args);
        if($lat && $lng) : 
            $map_id = uniqid('map_');
            ?>
            <div id="<?=$map_id?>" class="map__gmap" 
                data-lat="<?=$lat?>" 
                data-lng="<?=$lng?>" 
                data-icon="<?=$icon_url?>" 
                data-icon-width="<?=$icon_size[0]?>" 
                data-icon-height="<?=$icon_size[1]?>"
                data-zoom="<?=$zoom?>" 
                data-provider="<?=$provider?>">
                <?php if($info_window_content) echo '<template class="infowindow">'.do_shortcode(wpautop(oembed($info_window_content))).'</template>'; ?>
            </div>
        <?php endif;
        echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Map();