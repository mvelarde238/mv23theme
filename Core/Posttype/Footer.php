<?php
namespace Core\Posttype;

use Core\Utils\CPT;

class Footer {

    private static $instance = null;

	public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Footer();

            add_filter( 'pll_get_post_types', array( self::$instance, 'add_footer_cpt_to_pll'), 10, 2);
            add_action( 'admin_action_save_as_theme_footer_post', array( self::$instance, 'save_as_theme_footer'));
            add_filter( 'post_row_actions', array( self::$instance, 'add_action_in_admin_list'), 10, 2 );
        }
        return self::$instance;
    }

    private function __construct(){}

	public function register_posttype(){
        $footers = new CPT(
            'footer',
            array(
                'show_in_menu' => 'theme-options-menu',
                'show_in_nav_menus' => false,
                'exclude_from_search' => true,
                'supports' => array('title')
            )
        );

        $footers->columns(array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Title'),
            'is_theme_footer' => __('Use'),
            'date' => __('Date')
        ));

        $footers->populate_column('is_theme_footer', array($this, 'handle_is_theme_footer_admin_column'));
    }

    /*
     * Add CPT to Polylang
     */
    public function add_footer_cpt_to_pll($post_types, $hide) {
        if ($hide){
            // hides 'footer' from the list of custom post types in Polylang settings
            unset($post_types['footer']);
        } else {
            // enables language and translation management for 'footer'
            $post_types['footer'] = 'footer';
        }
        return $post_types;
    }

    public function handle_is_theme_footer_admin_column($column_name, $post) {
        $theme_footer_post = get_option('theme_footer_post');
    
        if( $theme_footer_post == 'post_'.$post->ID ){
            echo '<span class="dashicons dashicons-yes-alt" style="color:green"></span>';
        } else {
            $url = add_query_arg(
                [
                  'post' => $post->ID,
                  'action' => 'save_as_theme_footer_post',
                ],
                admin_url( 'post.php' )
            );
            echo '<a href="'.esc_url($url).'"><span class="dashicons dashicons-marker" style="color:silver"></span></a>';
        }
    }

    /*
     * Action to update active theme footer
     */
    public function save_as_theme_footer() {
        $post_id = $_REQUEST['post'];
        update_option('theme_footer_post', 'post_'.$post_id);
        wp_redirect( $_SERVER['HTTP_REFERER'] );
        exit();
    }

    /**
     * Add link in admin list to update active theme footer
     */
    public function add_action_in_admin_list($actions, $post) {
        global $post;

    	if ( $post->post_type != 'footer' ) {
    		return $actions;
    	}

        if ( ! current_user_can( 'manage_options', $post->ID ) ) {
            return $actions;
    	}

    	$url = add_query_arg(
    		[
    		  'post' => $post->ID,
    		  'action' => 'save_as_theme_footer_post',
    		],
    		admin_url( 'post.php' )
    	);

    	$actions['save_as_theme_footer_post'] = sprintf(
    		'<a href="%1$s">%2$s</a>',
    		$url,
    		'Use as theme footer'
    	);

        return $actions;
    }
}