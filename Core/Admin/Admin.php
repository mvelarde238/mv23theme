<?php
/**
 * Admin area specific functionality of this theme.
 */

namespace Core\Admin;

use Core\Includes\Theme_Header_Data;
use Core\Posttype\Footer;
use Core\Posttype\Megamenu;
use Core\Posttype\Portfolio;
use Core\Posttype\Document;
use Core\Posttype\MV23_Library;
use Core\Posttype\Reusable_Section_CPT;
use Core\Posttype\Archive_Page;
use Core\Theme_Options\Theme_Options;

class Admin extends Theme_Header_Data {

    public function __construct() { 
        parent::__construct();
    }
    
    /**
     * Sanitize post object before revision change detection
     * This runs immediately before the foreach loop that calls normalize_whitespace
     * By sanitizing the $post and $latest_revision objects here, we prevent array values from reaching normalize_whitespace
     * 
     * @param bool $check_for_changes Whether to check for changes
     * @param WP_Post $latest_revision The latest revision post object
     * @param WP_Post $post The current post object (passed by reference)
     * @return bool Original value
     */
    public function sanitize_post_before_revision_check( $check_for_changes, $latest_revision, $post ) {
        // Get all revisioned fields
        $fields = array_keys( _wp_post_revision_fields( $post ) );
        
        // Sanitize each field in the current post
        foreach ( $fields as $field ) {
            if ( isset( $post->$field ) && is_array( $post->$field ) ) {
                $post->$field = '';
            }
        }
        
        // Sanitize each field in the latest revision
        if ( $latest_revision ) {
            foreach ( $fields as $field ) {
                if ( isset( $latest_revision->$field ) && is_array( $latest_revision->$field ) ) {
                    $latest_revision->$field = '';
                }
            }
        }
        
        return $check_for_changes;
    }
    
    public function enqueue_scripts( $page ) {
        // if( $page == 'post.php' ) 

        // register theme styles and scripts for ultimate builder
        wp_register_style( $this->text_domain.'-styles', $this->theme_uri . '/assets/css/style.css', array(), $this->version, 'all' );
        wp_register_script( $this->text_domain.'-scripts', $this->theme_uri . '/assets/js/scripts.js', array('jquery'), $this->version, true );

        wp_enqueue_style( $this->text_domain.'-font-awesome', FONT_AWESOME, array(), $this->version );
        wp_enqueue_style( $this->text_domain.'-bootstrap-icons', BOOTSTRAP_ICONS, array(), $this->version );

        wp_enqueue_style( $this->text_domain.'-admin-styles', $this->theme_uri . '/assets/css/admin-styles.css', array(), $this->version);

        wp_register_script( $this->text_domain.'-admin-scripts', $this->theme_uri . '/assets/js/admin-scripts.js', array('jquery'), $this->version, false );
        wp_localize_script( $this->text_domain.'-admin-scripts', 'MV23_GLOBALS', array( 
            'ajaxUrl' => admin_url( 'admin-ajax.php' )
        ));
        wp_enqueue_script( $this->text_domain.'-admin-scripts' );
    }

    public function register_nav_menus() {
        register_nav_menus(
            array(
				'main-nav' => __( 'Main nav', $this->text_domain ),
				'mobile-header-buttons' => __( 'Mobile header buttons', $this->text_domain )
			)
        );
    }

    public function register_widget_areas() {
        register_sidebar(array(
			'id' => 'header_widgets_1',
			'name' => 'Header Widgets',
			'description' => __('Widgets to be used in the header', $this->text_domain),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));

		register_sidebar(array(
			'id' => 'page_sidebar',
			'name' => 'Sidebar Widgets',
			'description' => __('Widgets to be used in the sidebar', $this->text_domain),
			'before_widget' => '<div id="%1$s" class="widget component %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));

		if(WOOCOMMERCE_IS_ACTIVE){
            register_sidebar(array(
                'id' => 'shop_header_sidebar',
                'name' => 'Shop Header',
                'description' => 'Widgets en el título de la tienda',
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h4 class="widgettitle">',
                'after_title' => '</h4>',
            ));
            
			register_sidebar(array(
                'id' => 'shop_sidebar',
				'name' => 'Shop Sidebar',
                'description' => 'Widgets en el sidebar de la tienda',
				'before_widget' => '<div id="%1$s" class="widget component %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widgettitle">',
				'after_title' => '</h4>',
			));
		}

        if(USE_PORTFOLIO_CPT){
			register_sidebar(array(
				'id' => 'portfolio_sidebar',
				'name' => 'Portfolio Sidebar',
				'before_widget' => '<div id="%1$s" class="widget component %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widgettitle">',
				'after_title' => '</h4>',
			));
		}
    }

    public function add_editor_style(){
        $theme_fonts = Theme_Options::getInstance()->get_theme_fonts();

        $styles = array_merge(
            array( $this->theme_uri . '/assets/css/editor-style.css?ver='.$this->version ),
            $theme_fonts['urls']
        );

        add_editor_style( $styles ); 
    }

    public function add_editor_inline_style( $settings ) {
        $settings['content_style'] = $settings['content_style'] ?? '';

        $theme_options = Theme_Options::getInstance();
        $theme_fonts = $theme_options->get_theme_fonts();
        if( !empty($theme_fonts['css']) ){
            $settings['content_style'] .= str_replace('body', 'body#tinymce.wp-editor', $theme_fonts['css']);
        }

        $properties = $theme_options->get_css_properties();
        if( !empty($properties) ){
            $css_properties = ':root {'.implode(';', $properties ).'}';
            $settings['content_style'] .= str_replace(':root', 'body#tinymce.wp-editor', $css_properties);
        }

        $html_properties = $theme_options->get_html_properties();
        if( !empty($html_properties) ){
            $html_properties_css = 'html {'.implode(';', $html_properties ).'}';
            $settings['content_style'] .= str_replace(':root', 'body#tinymce.wp-editor', $html_properties_css);
        }

        return $settings;
    }

    public function remove_rss_version() { 
        return ''; 
    }

    public function disable_default_dashboard_widgets() {
        global $wp_meta_boxes;
        // unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);    // Right Now Widget
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);        // Activity Widget
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // Comments Widget
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);  // Incoming Links Widget
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);         // Plugins Widget
    
        // unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);    // Quick Press Widget
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);     // Recent Drafts Widget
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);           //
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);         //
    
        // remove plugin dashboard boxes
        unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']);           // Yoast's SEO Plugin Widget
        unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);        // Gravity Forms Plugin Widget
        unset($wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now']);   // bbPress Plugin Widget
    }

    public function customize_login_css() {
        wp_enqueue_style( 'login_css', get_stylesheet_directory_uri() . '/assets/css/login.css', false, $this->version );
    }
    
    public function customize_login_url() {  
        return home_url(); 
    }
    
    public function customize_login_title() { 
        return get_option( 'blogname' ); 
    }

    public function register_custom_posttypes() {
        Footer::getInstance()->register_posttype();
        Megamenu::getInstance()->register_posttype();
        Reusable_Section_CPT::getInstance()->register_posttype();
        Archive_Page::getInstance()->register_posttype();

        if( USE_PORTFOLIO_CPT ) Portfolio::getInstance()->register_posttype();
        if( USE_DOCUMENT_CPT ) Document::getInstance()->register_posttype();
        if( is_admin() ) MV23_Library::getInstance()->register_posttype();
    }

    public function add_theme_my_login_multi_language_support($page_id){
        $page_id_translated = (IS_MULTILANGUAGE && function_exists('pll_get_post')) ? pll_get_post($page_id) : $page_id;
        return $page_id_translated;
    }

    public function contact_form_7_mail_template($contact_form){
        // get mail property
        $mail = $contact_form->prop('mail'); // returns array
        $mail_2 = $contact_form->prop('mail_2'); // returns array

        $use_html = $mail['use_html'];
        $use_html_2 = $mail_2['use_html'];

        if( $use_html || $use_html_2 ){
            // get header template from theme root
            ob_start();
            get_template_part('partials/mail-header');
            $header = ob_get_clean();

            // get footer template from theme root
            ob_start();
            get_template_part('partials/mail-footer');
            $footer = ob_get_clean();
        }

        if($use_html){
            $body = $mail['body'];
            $mail['body'] = $header . $body . $footer;
            $contact_form->set_properties(array('mail' => $mail));
        }

        if($mail_2['active'] && $use_html_2){
            $body_2 = $mail_2['body'];
            $mail_2['body'] = $header . $body_2 . $footer;
            $contact_form->set_properties(array('mail_2' => $mail_2));
        }
    }

    public function remove_meta_boxes() {
        remove_meta_box('postcustom','page','normal');
    }

    public function custom_mime_types_support( $mimes ) {
        $mimes['woff'] = 'font/woff';
        $mimes['woff2'] = 'font/woff2';
        
        return $mimes;
    }

    public function lower_yoast_metabox_priority( $priority ) {
        return 'core';
    }

    public function extend_nav_widget() {
        unregister_widget('WP_Nav_Menu_Widget');
        register_widget('Core\Admin\Extend_Nav_Menu_Widget');
    }
}
