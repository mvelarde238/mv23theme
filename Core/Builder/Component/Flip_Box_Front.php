<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Flip_Box_Front extends Component {

    public function __construct() {
		parent::__construct(
			'flipbox-front',
			__( 'Flip Box Front', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'display_gjs_block' => false,
            // flipbox-front doesn't keep its own datastore: it shares the datastore
            // of the closest ancestor 'flipbox' component, so only one datastore
            // gets saved per flipbox (see gjs-extend-components.js resolveDatastoreOwner)
            'share_datastore_with' => 'flipbox'
		);
    }

	public static function get_icon() {
        return 'dashicons-image-flip-horizontal';
    }
    
	public static function get_fields() {
		return array();
	}

	public static function display( $args ){        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		echo Template_Engine::check_components( $args );
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Flip_Box_Front();