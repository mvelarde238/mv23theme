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
        $title = '―――― Migrate Accordion Settings ( To level up to versions >= 2.7.0 )';
        $slug = 'new_accordion_settings';
        
        parent::__construct( $batch_size, $do_the_update, $title, $slug );
    }

    public function migrate_component_data( $component ){
        $new_component = $this->loop_inner_components( $component );

        if( $component['__type'] == 'accordion' ){
            $tab_style = $component['tab_style'] ?? 'style1';
            $acc_style = $component['accordion_style'] ?? 'style1';

            $new_tab_style = ( $tab_style == 'style1' || $tab_style == 'style2' ) ? 'tab-' . $tab_style : $tab_style; 
            $new_acc_style = ( $acc_style == 'style1' ) ? 'accordion-' . $acc_style : $acc_style; 

            // migrate styles
            $new_component['desktop_tab_style'] = $new_tab_style;
            $new_component['mobile_tab_style'] = $new_tab_style;

            $new_component['desktop_accordion_style'] = $new_acc_style;
            $new_component['mobile_accordion_style'] = $new_tab_style;

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