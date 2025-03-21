<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Map extends Component {

    public function __construct() {
		parent::__construct(
			'map',
			__( 'Map', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'dashicons-location';
    }

    public static function get_title_template() {
		$template = '<% if ( location ){ %>
            <%= location.address %> | Zoom: <%= location.zoom %>
        <% } else { %>
            There isnt any location selected
        <%  } %>';
		
		return $template;
	}

	public static function get_fields() {
		$fields = array(
            Field::create( 'tab', __('Content','mv23theme')),
            Field::create( 'map', 'location' )->set_output_width( '100%' )->set_output_height( 280 ),
            Field::create( 'image', 'icono' )->set_width( 50 ),
            Field::create( 'complex', 'height')->add_fields(array(
                Field::create( 'number', 'height', 'Altura' )->set_width( 50 )->set_default_value( 280 ),
                Field::create( 'text', 'unit', 'Medida (px,%,vh..)' )->set_width( 50 )->set_default_value( 'px' ),
            ))->set_width( 50 ),
    
            Field::create( 'tab', 'Info Window'),
            Field::create( 'wysiwyg', 'info' )->hide_label()->set_rows( 20 ),
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
        
		$args['additional_classes'] = array('component');
        
        $info = (isset($args['info'])) ? $args['info'] : '';
        $lat = $location['latLng']['lat'];
        $lng = $location['latLng']['lng'];
        $zoom = $location['zoom'];
        $icono = $args['icono'];
        $icono = wp_get_attachment_url($icono);
        $height = (isset($args['height']) && $args['height'] && is_array($args['height'])) ? $args['height'] : array( 'height'=>280, 'unit'=>'px' );
        $height_style = 'style="height:'.$height['height'].$height['unit'].'"';

		ob_start();
        echo Template_Engine::component_wrapper('start', $args);
        if($lat && $lng) : 
            $map_id = uniqid('map_');
            ?>
            <div id="<?=$map_id?>" class="map__gmap" <?=$height_style?> 
                data-lat="<?=$lat?>" 
                data-lng="<?=$lng?>" 
                data-icon="<?=$icono?>" 
                data-zoom="<?=$zoom?>" 
                data-provider="<?=$provider?>">
                <?php if($info) echo '<template class="infowindow">'.do_shortcode(wpautop(oembed($info))).'</template>'; ?>
            </div>
        <?php endif;
        echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Map();