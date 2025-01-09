<?php
namespace Core\Includes;

class Theme_Header_Data {

    protected $theme_name = null;
    protected $version = null;
    protected $text_domain = null;
    protected $theme_path = null;
    protected $theme_uri = null;
    private $template = null;

    public function __construct() {
        $wp_get_theme = wp_get_theme();
        $parent_theme = $wp_get_theme->parent();
        $theme = ( $parent_theme ) ? $parent_theme : $wp_get_theme;

        $this->theme_name = $theme->get( 'Name' );
        $this->version = $theme->get( 'Version' );
        $this->text_domain = $theme->get( 'TextDomain' );
        $this->theme_path = get_template_directory();
        $this->theme_uri = get_template_directory_uri();
    }

    public function get_template() {
        if( $this->template ) {
            return $this->template;
        } else {
            global $template;
            return $this->template = basename( $template );   
        }
    }
}