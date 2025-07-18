<?php
namespace Core\Migrator\Migration;

use Core\Migrator\Base\Migrate_Components_Settings;

class Migrate_Accordion_Settings extends Migrate_Components_Settings {
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
        $title = '―――― Migrate Accordion Settings ( For version >= 2.7.0 )';
        $slug = 'new_accordion_settings';
        
        parent::__construct( $batch_size, $do_the_update, $title, $slug );
    }

    public function migrate_component_data( $component ){
        $new_component = $this->loop_inner_components( $component );

        if( $component['__type'] == 'accordion' ){
            $tab_style = $component['tab_style'] ?? 'style1';
            $acc_style = $component['accordion_style'] ?? 'style1';

            // migrate styles
            $new_component['desktop_tab_style'] = 'tab-' . $tab_style;
            $new_component['desktop_accordion_style'] = 'accordion-' . $acc_style;
            $new_component['mobile_tab_style'] = 'tab-' . $tab_style;
            $new_component['mobile_accordion_style'] = 'accordion-' . $acc_style;

            // unset old settings
            unset( $new_component['tab_style'] );
            unset( $new_component['accordion_style'] );
        }

        // $new_component['settings'] = $this->migrate_settings_data( $component['settings'] );

        return $new_component;
    }

    // public function migrate_settings_data( $old_settings ){
    //     $new_settings = $old_settings;

    //     return $new_settings;
    // }
}