<?php
namespace Core\Migrator\Migration;

use Core\Migrator\Base\Migrate_Components_Settings;

class Migrate_Carrusel_Comp_To_Carousel extends Migrate_Components_Settings {
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
        $title = '―――― Migrate Carusel Comp. to Carousel Comp. ( To level up to versions >= 2.8.0 )';
        $slug = 'new_carousel_components_name';

        parent::__construct( $batch_size, $do_the_update, $title, $slug );
    }

    public function migrate_component_data( $component ){
        $new_component = $this->loop_inner_components( $component );

        if( $component['__type'] == 'carrusel' ){
            $new_component['__type'] = 'carousel';
        }

        return $new_component;
    }
}