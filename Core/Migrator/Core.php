<?php
namespace Core\Migrator;

use Core\Migrator\Migration\Migrate_0_4_X_to_0_5_0;

define ('THEME_MIGRATOR_DIR', __DIR__);
define ('THEME_MIGRATOR_PATH', get_template_directory_uri() . '/Core/Migrator');

class Core{

    private static $instance = null;

    private $slug;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Core();
        }
        return self::$instance;
    }
    
    private function __construct(){
        $this->slug = 'theme-migrator';

        // if( $this->theme_version_is_less( THEME_VERSION, '0.5.0' ) ){
        if( !get_option('theme_version') ){
            Migrate_0_4_X_to_0_5_0::getInstance()->migrate();
        }
    }

    public function theme_version_is_less($current_version, $other_version) {
        // transform to array
        $_current = explode('.', $current_version);
        $_other = explode('.', $other_version);
    
        // mutate to integers
        $_current = array_map('intval', $_current);
        $_other = array_map('intval', $_other);
    
        // Compare
        for ($i = 0; $i < count($_current); $i++) {
            if ($_current[$i] < $_other[$i]) {
                return true;
            } elseif ($_current[$i] > $_other[$i]) {
                return false;
            }
        }
    
        // is the same version
        return false;
    }

    public function get_slug(){
        return $this->slug;
    }
}