<?php
/**
 * This class definse all hooks.
 */

namespace Core;

use Core\Includes\Theme_Header_Data;
use Core\Includes\Loader;
use Core\Frontend\Frontend;
use Core\Frontend\WooCommerce_Support;
use Core\Admin\Admin;
use Core\Cleanup\Cleanup;
use Core\Admin\Ajax_Load_Posts;
use Core\Admin\Hardening_WP;
use Core\Admin\TinyMCE;
use Core\Frontend\Page;
use Core\Theme_Options\Theme_Options;
use Core\Theme_Options\Manager;
use Core\Posttype\Archive_Page;
use Core\Posttype\MV23_Library;
use Core\Posttype\Accordion;
use Core\Posttype\Post;
use Core\Posttype\Menu_Item;
use Core\Posttype\Megamenu;
use Core\Builder\Core as Builder;
use Core\Offcanvas_Elements\Core as Offcanvas_Elements;
use Core\Migrator\Core as Migrator;

class Theme extends Theme_Header_Data {

    protected $loader;
    
    public function __construct() {
        parent::__construct();
        $this->loader = new Loader();
    }
    
    public function init(){
        // init migrator module
        Migrator::getInstance();
        
        $this->define_frontend_hooks();
        $this->define_admin_hooks();
        $this->define_cleanup_hooks();
    
        $this->loader->run();
    }

    private function define_frontend_hooks() {
        $frontend = new Frontend();

        // Enqueue styles and scripts.
        $this->loader->add_action( 'wp_enqueue_scripts', $frontend, 'enqueue_styles', 10 , 1 );
        $this->loader->add_action( 'wp_enqueue_scripts', $frontend, 'enqueue_scripts', 10 , 1  );

        // Add theme support.
        $this->loader->add_action( 'init', $frontend, 'add_theme_support', 1 , 1 );

        // Register thumbnail sizes.
        $this->loader->add_action( 'init', $frontend, 'register_thumbnail_sizes', 1 );

        // A better title
        $this->loader->add_filter( 'wp_title', $frontend, 'title_meta_tag', 10, 3 );
        $this->loader->add_filter( 'get_the_archive_title', $frontend, 'archive_title_meta_tag', 10, 1 );

        // Filter body class and style
        $this->loader->add_filter( 'body_class', $frontend, 'body_class');
        $this->loader->add_action( 'body_style', $frontend, 'body_style');

        // Responsive video
        $this->loader->add_filter( 'embed_oembed_html', $frontend, 'responsive_video', 10, 4 ); 

        // Include shortcodes
        $this->loader->add_action( 'after_setup_theme', $frontend, 'include_shortcodes', 1 );

        // Add custom meta tags
        $this->loader->add_action( 'wp_head', $frontend, 'add_custom_meta_tags');

        // Load theme's translated strings.
        // $this->loader->add_action( 'after_setup_theme', $frontend, 'load_theme_textdomain', 1 );

        $page = new Page();

        $this->loader->add_filter( 'the_content', $page, 'filter_the_content', 100 );
        $this->loader->add_filter( 'rest_prepare_page', $page, 'add_page_modules_to_rest_api', 10, 3 );

        if( WOOCOMMERCE_IS_ACTIVE ){
            $woocommerce_support = new WooCommerce_Support();
            $this->loader->add_action( 'after_setup_theme', $woocommerce_support, 'add_theme_support' );
            $this->loader->add_action( 'woocommerce_before_main_content', $woocommerce_support, 'before_main_content', 10);
            $this->loader->add_action( 'woocommerce_after_main_content', $woocommerce_support, 'after_main_content', 10);
            $this->loader->add_action( 'woocommerce_before_shop_loop', $woocommerce_support, 'show_shop_header_sidebar', 15);
            $this->loader->add_action( 'wp_ajax_get_cart_quantity', $woocommerce_support, 'get_cart_unique_items_count');
            $this->loader->add_action( 'wp_ajax_nopriv_get_cart_quantity', $woocommerce_support, 'get_cart_unique_items_count');
        }

        // Theme Options
        $theme_options = Theme_Options::getInstance();

        // load theme options frontend stuff
        $this->loader->add_action( 'wp_enqueue_scripts', $theme_options, 'add_theme_fonts' );
        $this->loader->add_action( 'wp_enqueue_scripts', $theme_options, 'add_css_properties' );
    }

    private function define_admin_hooks() {	
        $admin = new Admin();

        // Enqueue styles and scripts.
        $this->loader->add_action( 'admin_head', $admin, 'enqueue_scripts' );

        // Register navigational menus.
        $this->loader->add_action( 'init', $admin, 'register_nav_menus' );

        // Register widgert areas.
        $this->loader->add_action( 'widgets_init', $admin, 'register_widget_areas' );

        // Allow editor style.
        $this->loader->add_action( 'after_setup_theme', $admin, 'add_editor_style', 1 );
        $this->loader->add_filter( 'tiny_mce_before_init', $admin, 'add_editor_inline_style' );

        // remove WP version from RSS
        $this->loader->add_filter( 'the_generator', $admin, 'remove_rss_version' );

        // removing the dashboard widgets
        $this->loader->add_action( 'wp_dashboard_setup', $admin, 'disable_default_dashboard_widgets' );

        // customize login page
        $this->loader->add_action( 'login_enqueue_scripts', $admin, 'customize_login_css', 10 );
        $this->loader->add_filter( 'login_headerurl', $admin, 'customize_login_url' );
        $this->loader->add_filter( 'login_headertext', $admin, 'customize_login_title' );

        // Init custom posttypes
        $this->loader->add_action( 'after_setup_theme', $admin, 'register_custom_posttypes', 4 );

        // add theme my login plugin multi language support
        $this->loader->add_filter( 'tml_page_id', $admin, 'add_theme_my_login_multi_language_support' );

        // add a brand wrapper around contact form 7 mails
        if( CF7_USE_EMAIL_TEMPLATE ){
            $this->loader->add_action('wpcf7_before_send_mail', $admin, 'contact_form_7_mail_template');
        }

        // remove custom fields meta box
        $this->loader->add_action( 'add_meta_boxes', $admin, 'remove_meta_boxes');

        // add support for fonts mime types
        $this->loader->add_filter( 'upload_mimes', $admin, 'custom_mime_types_support' );

        // ajax callback to load posts in listing component
        $ajax_load_posts = new Ajax_Load_Posts();

        $this->loader->add_action( "wp_ajax_load_posts", $ajax_load_posts, "load_posts" );
        $this->loader->add_action( "wp_ajax_nopriv_load_posts", $ajax_load_posts, "load_posts" );

        // Extend TinyMCE
        $tinymce = new TinyMCE;
        
        $this->loader->add_filter( 'mce_buttons', $tinymce, 'filter_buttons_in_first_row' );
        $this->loader->add_filter( 'mce_buttons_2', $tinymce, 'filter_buttons_in_second_row' );
        $this->loader->add_filter( 'tiny_mce_before_init', $tinymce, 'add_font_sizes' );
        $this->loader->add_filter( 'tiny_mce_before_init', $tinymce, 'add_font_families' );
        $this->loader->add_filter( 'tiny_mce_before_init', $tinymce, 'add_style_formats' );
        $this->loader->add_filter( 'tiny_mce_before_init', $tinymce, 'add_theme_colors' );
        $this->loader->add_filter( 'mce_external_plugins', $tinymce, 'add_table_plugin' );
        $this->loader->add_filter( 'mce_external_plugins', $tinymce, 'add_icon_plugin' );

        // Hardening WordPress
        $hardening_wp = new Hardening_WP;

        // Redirect back to homepage and not allow access to  admin for Subscribers.
        $this->loader->add_action( 'admin_init', $hardening_wp, 'redirect_admin' );

        // Disable admin bar on the frontend of your website for subscribers.
        $this->loader->add_action( 'after_setup_theme', $hardening_wp, 'disable_admin_bar' );

        // Theme Options
        $theme_options = Theme_Options::getInstance();
        $theme_options_manager = Manager::getInstance();
        
        // add options page
        $this->loader->add_action( 'uf.init', $theme_options, 'init_options_page' );

        // re arrange the theme options sub menu
        $this->loader->add_filter( 'custom_menu_order', $theme_options, 'rearrange_submenu_order' );

        // Add theme colors in color picker
        $this->loader->add_action( 'admin_enqueue_scripts', $theme_options, 'enqueue_admin_scripts' );

        // int scripts for the customizer
        $this->loader->add_action( 'customize_preview_init', $theme_options, 'enqueue_uf_customize_preview_script' );

        // show post types count
        $this->loader->add_action( 'init', $theme_options, 'show_cpt_count', 999 );

        // add theme options export / import manager
        $this->loader->add_action( 'admin_menu', $theme_options_manager, 'register_metabox' );
        $this->loader->add_action( 'admin_enqueue_scripts', $theme_options_manager, 'enqueue_script' );
        $this->loader->add_action( 'wp_ajax_export_theme_options', $theme_options_manager, 'export_options_via_ajax' );
        $this->loader->add_action( 'wp_ajax_import_theme_options', $theme_options_manager, 'import_options_via_ajax' );

        // Builder
        $builder = Builder::getInstance();

        // $this->loader->add_action( 'after_setup_theme', $builder, 'init_components', 15 ); // theme launch at 10
        $this->loader->add_action( 'uf.init', $builder, 'init_components');
        $this->loader->add_action( 'uf.init', $builder, 'add_meta_boxes');
        $this->loader->add_action( 'init', $builder, 'hide_editor');

        // Off Canvas Elements
        $offcanvas_elements = Offcanvas_Elements::getInstance();

        $this->loader->add_action( 'after_setup_theme', $offcanvas_elements, 'register_post_type', 4 );
        $this->loader->add_action( 'uf.init', $offcanvas_elements, 'register_settings' );
        $this->loader->add_action( 'wp_enqueue_scripts', $offcanvas_elements, 'enqueue_scripts', 1000);
        $this->loader->add_action( 'footer_code', $offcanvas_elements, 'print_elements' );
        $this->loader->add_action( 'save_post', $offcanvas_elements, 'handle_save_post_hook', 99, 3 );

        // Archive Pages
        $archive_pages = Archive_Page::getInstance();

        // Add the meta boxes
        $this->loader->add_action( 'uf.init', $archive_pages, 'add_meta_boxes' );

        // redirect single archive page to connected posttype / taxonomy / term
        $this->loader->add_action( 'template_redirect', $archive_pages, 'redirect_single' );

        // MV23 Library
        $mv23_library = MV23_Library::getInstance();

        // Add the meta boxes
        $this->loader->add_action( 'uf.init', $mv23_library, 'add_meta_boxes' );

        // ajax functions for MV23 library CPT
        $this->loader->add_action( 'wp_ajax_mv23_library_save_item', $mv23_library, 'save_item' );
		$this->loader->add_action( 'wp_ajax_load_mv23_library_gallery', $mv23_library, 'load_gallery' );
		$this->loader->add_action( 'wp_ajax_mv23_library_action', $mv23_library, 'library_action' );

        // Accordion
        $accordion = Accordion::getInstance();

        // Add the meta boxes
        $this->loader->add_action( 'uf.init', $accordion, 'add_meta_boxes' );

        // Post
        $post = Post::getInstance();

        // Add the meta boxes
        $this->loader->add_action( 'uf.init', $post, 'add_meta_boxes' );

        // Menu_Item
        $menu_item = Menu_Item::getInstance();

        // Add the meta boxes
        $this->loader->add_action( 'uf.init', $menu_item, 'add_meta_boxes' );

        // Megamenu
        $megamenu = Megamenu::getInstance();

        // Add the meta boxes
        $this->loader->add_action( 'uf.init', $megamenu, 'add_meta_boxes' );
    }

    private function define_cleanup_hooks() {
        $cleanup = new Cleanup();

        // Remove emoji's header.
        $this->loader->add_action( 'init', $cleanup, 'disable_emoji_dequeue_script' );

        // Remove junk from header.
        $this->loader->add_action( 'init', $cleanup, 'clean_up_header' );

        // Remove wpembed scripts.
        $this->loader->add_action( 'wp_footer', $cleanup, 'remove_wpembed_scripts' );

        // remove pesky injected css for recent comments widget
        $this->loader->add_filter( 'wp_head', $cleanup, 'remove_wp_widget_recent_comments_style', 1 );

        // clean up comment styles in the head
        $this->loader->add_action( 'wp_head', $cleanup, 'remove_recent_comments_style', 1 );

        // clean up gallery output in wp
        $this->loader->add_filter( 'gallery_style', $cleanup, 'clean_gallery_style' );

        // cleaning up excerpt
        $this->loader->add_filter( 'excerpt_more', $cleanup, 'clean_excerpt_more' );

        // clean styles
        $this->loader->add_action( 'wp_print_styles', $cleanup, 'clean_head_styles', 100 );
    }
}
