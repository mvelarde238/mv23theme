<?php
namespace Core\Migrator\Migration;

use Core\Migrator\Base\Migrate_Components_Settings;

class Migrate_Inner_Components extends Migrate_Components_Settings {
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
        $title = '―――― Migrate Inner Components Settings ( To level up to versions >= 2.8.0 )';
        $slug = 'new_inner_components_settings';

        parent::__construct( $batch_size, $do_the_update, $title, $slug );
    }

    public function migrate_component_data( $component ){
        $new_component = $this->loop_inner_components( $component );

        if( $component['__type'] == 'inner_wrapper' ){
            $new_component['__type'] = 'components_wrapper';
        } 
        if( $component['__type'] == 'inner_row' ){
            $new_component['__type'] = 'row';
        }

        return $new_component;
    }
}