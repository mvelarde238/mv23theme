<?php
/**
 * Admin area specific functionality of this theme.
 */

namespace Core\Admin;

use Core\Includes\Theme as Theme;
use Core\Posttype\Footer;
use Core\Posttype\Accordion;
use Core\Posttype\Megamenu;
use Core\Posttype\Portfolio;
use Core\Posttype\MV23_Library;
use Core\Posttype\Reusable_Section_CPT;
use Core\Posttype\Archive_Page;

class Admin extends Theme {

    public function __construct() { 
        parent::__construct();
    }
    
    public function enqueue_scripts( $page ) {
        // if( $page == 'post.php' ) 
        wp_enqueue_style( 'font-awesome', FONT_AWESOME, array(), $this->version );
        wp_enqueue_style( $this->text_domain.'-admin-styles', $this->theme_path . '/assets/css/admin-styles.css', array(), $this->version);

        wp_register_script( $this->text_domain.'-admin-scripts', $this->theme_path . '/assets/js/admin-scripts.js', array('jquery'), $this->version, false );
        wp_localize_script( $this->text_domain.'-admin-scripts', 'MV23_GLOBALS', array( 
            'ajaxUrl' => admin_url( 'admin-ajax.php' )
        ));
        wp_enqueue_script( $this->text_domain.'-admin-scripts' );
    }

    public function register_nav_menus() {
        register_nav_menus(
            array(
				'main-nav' => __( 'Main nav', $this->text_domain ),
				'mobile-nav' => __( 'Mobile nav', $this->text_domain )
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
				'id' => 'shop_sidebar',
				'name' => 'Shop Sidebar',
				'before_widget' => '<div id="%1$s" class="widget component %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widgettitle">',
				'after_title' => '</h4>',
			));
		}
    }

    public function add_editor_style(){
        add_editor_style( $this->theme_path . '/assets/css/editor-style.css' );
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
        wp_enqueue_style( 'login_css', $this->theme_path . '/assets/css/login.css', false );
    }
    
    public function customize_login_url() {  
        return home_url(); 
    }
    
    public function customize_login_title() { 
        return get_option( 'blogname' ); 
    }

    public function show_cpt_count() {
        global $wp_post_types;
    
        $custom_posts = array(
            array( 'slug'=>'archive_page', 'name'=>'Archive Pages' ),
            array( 'slug'=>'megamenu', 'name'=>'Megamenú' ),
            array( 'slug'=>'v23accordion', 'name'=>'Accordions' ),
            array( 'slug'=>'reusable_section', 'name'=>__('Reusable Sections') )
        );
    
        foreach($custom_posts as $cpt){
            $slug = $cpt['slug'];
            $name = $cpt['name'];
    
            $notification_count = wp_count_posts( $slug )->publish;
            $labels = $wp_post_types[$slug]->labels;
            $labels->all_items = $notification_count ? sprintf('%s <span class="awaiting-mod">%d</span>', $name, $notification_count) : $name;   
        }
    }

    public function register_custom_posttypes() {
        Footer::getInstance()->register_posttype();
        Accordion::getInstance()->register_posttype();
        Megamenu::getInstance()->register_posttype();
        Reusable_Section_CPT::getInstance()->register_posttype();
        Archive_Page::getInstance()->register_posttype();

        if( USE_PORTFOLIO_CPT ) Portfolio::getInstance()->register_posttype();
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
}
