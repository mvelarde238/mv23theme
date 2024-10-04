<?php
namespace Core\Builder;

class Common_Settings{
	private static $instance = null;

    private static $settings = array();

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Common_Settings();
        }
        return self::$instance;
    }
    
    // Constructor privado para evitar la creación directa de la instancia
    private function __construct(){
        $settings__ = array(
            'actions',
            'id-and-class',
            'background-image',
            'margins',
            'borders',
            'box-shadow',
            'animation',
            'scroll-animations',
            'video-background',
            'row',
            'columns',
            'main',
            // 'all'
        );
        foreach ($settings__ as $s) {
            self::$settings[$s] = require( BUILDER_DIR.'/common-settings/'.$s.'.php' );
        }
    }

    public static function get_fields( $setting_name ){
        $fields = array();

        if( isset( self::$settings[$setting_name] ) ) {
            $fields = self::$settings[$setting_name]->get_fields();
        }

        return $fields;
    }
}