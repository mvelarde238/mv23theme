<?php
namespace Core\Migrator\Migration;

use Core\Migrator\Base\Migrate_Components_Settings;

class Migrate_Gallery_Settings extends Migrate_Components_Settings {
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct(){
        $batch_size = 3;
        $do_the_update = false;
        $title = '―――― Migrate Gallery Settings ( To level up to versions >= 2.4.0 )';
        $slug = 'new_gallery_setting';
        
        parent::__construct( $batch_size, $do_the_update, $title, $slug );
    }

    public function migrate_component_data( $component ){
        $new_component = $this->loop_inner_components( $component );

        if( $component['__type'] == 'gallery' ){
            // simplify complex gallery settings
            $new_component['display'] = $component['wp_media_folder_settings']['display'];
            $new_component['link'] = $component['wp_media_folder_settings']['link'];
            $new_component['size'] = $component['wp_media_folder_settings']['size'];
            $new_component['targetsize'] = $component['wp_media_folder_settings']['targetsize'];

            // add gutter settings
            $new_component['gutter_in_desktop'] = $component['items_gap']['l_gap'];
            $new_component['gutter_in_laptop'] = $component['items_gap']['l_gap'];
            $new_component['gutter_in_tablet'] = $component['items_gap']['t_gap'];
            $new_component['gutter_in_mobile'] = $component['items_gap']['m_gap'];

            // add columns settings
            $new_component['items_in_desktop'] = $component['wp_media_folder_settings']['columns'];
            $new_component['items_in_laptop'] = $component['wp_media_folder_settings']['columns'];
            $new_component['items_in_tablet'] = 3;
            $new_component['items_in_mobile'] = 2;

            // unset old settings
            unset( $new_component['items_gap'] );
            unset( $new_component['wp_media_folder_settings'] );
        }

        return $new_component;
    }
}