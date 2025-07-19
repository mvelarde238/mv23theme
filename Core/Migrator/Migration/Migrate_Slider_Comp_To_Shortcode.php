<?php
namespace Core\Migrator\Migration;

use Core\Migrator\Base\Migrate_Components_Settings;

class Migrate_Slider_Comp_To_Shortcode extends Migrate_Components_Settings {
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct(){
        $batch_size = 3;
        $do_the_update = true;
        $title = '―――― Migrate Slider Comp. to Shortcode Comp. ( To level up to versions >= 2.8.0 )';
        $slug = 'new_slider_components_settings';

        parent::__construct( $batch_size, $do_the_update, $title, $slug );
    }

    public function migrate_component_data( $component ){
        $new_component = $this->loop_inner_components( $component );

        if( $component['__type'] == 'slider' ){
            $new_component['__type'] = 'shortcode';

            $new_component['desktop'] = $component['slider_desktop'];
            $new_component['mobile'] = $component['slider_movil'];
        }

        return $new_component;
    }
}