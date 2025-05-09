<?php
namespace Core\Migrator;

use Core\Migrator\Migration\Migrate_0_4_X_to_0_5_0;
use Core\Migrator\Migration\Migrate_1_5_X_to_2_0_1;
use Core\Migrator\Migration\Migrate_Gmaps_to_Leaflet;
use Core\Migrator\Migration\Migrate_ScrollMagic_to_GSAP;

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
        // if( !get_option('theme_version') ){
            Migrate_0_4_X_to_0_5_0::getInstance()->migrate();
            Migrate_1_5_X_to_2_0_1::getInstance()->migrate();
            Migrate_Gmaps_to_Leaflet::getInstance()->migrate();
            Migrate_ScrollMagic_to_GSAP::getInstance()->migrate();
        // }

        add_action( 'admin_menu', array($this, 'add_admin_page') );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_migrator_styles') );
    }

    public function add_admin_page(){
        $slug = self::$instance->get_slug();

        add_submenu_page(
            'theme-options',
            __('Theme Migrator', 'mv23theme'),
            __('Theme Migrator', 'mv23theme'),
            'manage_options',
            $slug,
            array($this, 'display'),
            60
        );

        $this->migrator_url = admin_url('admin.php?page='.$slug);
    }

    public function display(){
        do_action('theme_migrator_display');
    }

    public function enqueue_migrator_styles( $hook ) {
        if ( 'admin_page_theme-migrator' != $hook ) return;

        $slug = self::$instance->get_slug();
        wp_enqueue_style($slug.'-style', THEME_MIGRATOR_PATH . '/styles/styles.css', array(), '1.0');
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