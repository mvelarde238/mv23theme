<?php
namespace Core\Migrator\Migration;

use Core\Migrator\Base\Migrate_Components_Settings;

class Migrate_Heading_Settings extends Migrate_Components_Settings {
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
        $title = '―――― Migrate Heading ( To level up to versions >= 2.9.0 )';
        $slug = 'add_heading_tagline_checkbox';

        parent::__construct( $batch_size, $do_the_update, $title, $slug );
    }

    public function migrate_component_data( $component ){
        $new_component = $this->loop_inner_components( $component );

        if( $component['__type'] == 'heading' ){
            if( !empty( $component['tagline']['content'] ) ){
                $new_component['add_tagline'] = true;
            }
        }

        return $new_component;
    }
}