<?php
namespace Core\Migrator\Migration;

use Core\Migrator\Base\Migrate_Components_Settings;

class Migrate_Video_Settings extends Migrate_Components_Settings {
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
        $title = '―――― Migrate Video Settings ( To level up to versions >= 2.5.0 )';
        $slug = 'new_video_settings';

        parent::__construct( $batch_size, $do_the_update, $title, $slug );
    }

    public function migrate_component_data( $component ){
        $new_component = $this->loop_inner_components( $component );

        if( $component['__type'] == 'video' ){
            $video_type = isset($component['video_type']) ? $component['video_type'] : 'playable';

            // migrate 'popable' and 'playable' video types to new settings
            $new_component['expand_on_click'] = ($video_type == 'popable') ? true : false;
            $new_component['video_settings']['controls'] = ($video_type == 'playable') ? true : false;

            // unset old settings
            unset( $new_component['video_type'] );
        }

        $new_componnent['settings'] = $this->migrate_settings_data( $component['settings'] );

        return $new_component;
    }

    public function migrate_settings_data( $old_settings ){
        $new_settings = $old_settings;

        if( is_array($old_settings) && !empty($old_settings) ){
            if( isset($old_settings['video_background']) ){
                $old_video_settings = $old_settings['video_background']['video_settings'];

                // migrate video settings
                $old_video_settings['controls'] = false;
                $old_video_settings['bgc'] = $old_video_settings['background_color'] ?? '';
                unset( $old_video_settings['background_color'] );
                $new_settings['video_background']['video_settings'] = $old_video_settings;
            }
        }

        return $new_settings;
    }
}