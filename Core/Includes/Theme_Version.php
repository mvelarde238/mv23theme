<?php
namespace Core\Includes;

/**
 * Tracks the installed theme version (wp_options) against the active theme's style.css version.
 */
class Theme_Version {

    const OPTION_NAME = 'mv23_theme_version';

    private static $instance = null;

    private $current_version = null;

    public static function getInstance() {
        if ( self::$instance == null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $wp_get_theme = wp_get_theme();
        $parent_theme = $wp_get_theme->parent();
        $theme = ( $parent_theme ) ? $parent_theme : $wp_get_theme;

        $this->current_version = $theme->get( 'Version' );
    }

    public function get_current_version() {
        return $this->current_version;
    }

    public function get_installed_version() {
        return get_option( self::OPTION_NAME, '0.0.0' );
    }

    public function needs_upgrade() {
        return version_compare( $this->get_installed_version(), $this->get_current_version(), '<' );
    }

    public function mark_upgraded() {
        update_option( self::OPTION_NAME, $this->get_current_version() );
    }

    // Fresh installs shouldn't trigger migrations meant for upgrades.
    public function stamp_on_theme_activation() {
        $this->mark_upgraded();
    }

    public function maybe_upgrade() {
        if ( $this->needs_upgrade() ) {
            do_action( 'mv23_theme_upgraded', $this->get_installed_version(), $this->get_current_version() );
        }
    }
}
