<?php
namespace Core\Posttype;

use Core\Utils\CPT;
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Core\Builder\Core as Builder_Core;

class Header {

    private static $instance = null;

	public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Header();

            add_action( 'admin_action_save_as_theme_header_post', array( self::$instance, 'save_as_theme_header'));
            add_filter( 'post_row_actions', array( self::$instance, 'add_action_in_admin_list'), 10, 2 );
        }
        return self::$instance;
    }

    private function __construct(){}

	public function register_posttype(){
        $headers = new CPT(
            array(
                'post_type_name' => 'header',
                'singular' => __('Header', 'mv23theme'),
                'plural' => __('Headers', 'mv23theme')
            ), 
            array(
                'show_in_menu' => 'theme-options-menu',
                'show_in_nav_menus' => false,
                'exclude_from_search' => true,
                'supports' => array('title','revisions'),
                'public' => false,
                'show_ui' => true,
                'show_in_admin_bar' => false,
            )
        );

        $headers->columns(array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Title'),
            'is_theme_header' => __('Activate','mv23theme'),
            'date' => __('Date')
        ));

        $headers->populate_column('is_theme_header', array($this, 'handle_is_theme_header_admin_column'));
    }

    public function add_meta_boxes(){
        $datastore = new \Ultimate_Fields\Datastore\Options;

        $location = new \Ultimate_Fields\Location\Post_Type('header');
	    $location->overwrite_datastore( $datastore );
        $location->context = 'side';

        Container::create( 'page_header' ) 
            ->add_location( $location )
            ->add_fields(array(
                Field::create( 'wp_object', 'theme_header_post', __('Header','mv23theme') )
                    ->add( 'posts', 'post_type=header' )
                    ->hide_label()
            ));

        Container::create( 'header_settings' )
            ->add_location( 'post_type', 'header' )
            ->set_description_position('label')
		    ->set_title('Header Settings')
		    ->add_fields(array(
                Field::create( 'ultimate_builder', 'page_content', __('Content','mv23theme') )
                    ->add_groups( Builder_Core::getInstance()->get_groups_for_builder() )
            ));
    }

    public function handle_is_theme_header_admin_column($column_name, $post) {
        $theme_header_post = get_option('theme_header_post');
    
        if( $theme_header_post == 'post_'.$post->ID ){
            echo '<span class="dashicons dashicons-yes-alt" style="color:green"></span>';
        } else {
            $url = add_query_arg(
                [
                  'post' => $post->ID,
                  'action' => 'save_as_theme_header_post',
                ],
                admin_url( 'post.php' )
            );
            echo '<a href="'.esc_url($url).'"><span class="dashicons dashicons-marker" style="color:silver"></span></a>';
        }
    }

    /*
     * Action to update active theme header
     */
    public function save_as_theme_header() {
        $post_id = $_REQUEST['post'];
        update_option('theme_header_post', 'post_'.$post_id);
        wp_redirect( $_SERVER['HTTP_REFERER'] );
        exit();
    }

    /**
     * Add link in admin list to update active theme header
     */
    public function add_action_in_admin_list($actions, $post) {
        global $post;

    	if ( $post->post_type != 'header' ) {
    		return $actions;
    	}

        if ( ! current_user_can( 'manage_options', $post->ID ) ) {
            return $actions;
    	}

    	$url = add_query_arg(
    		[
    		  'post' => $post->ID,
    		  'action' => 'save_as_theme_header_post',
    		],
    		admin_url( 'post.php' )
    	);

    	$actions['save_as_theme_header_post'] = sprintf(
    		'<a href="%1$s">%2$s</a>',
    		$url,
    		__('Activate Theme Header','mv23theme')
    	);

        return $actions;
    }

    /**
     * Filter default single content to hide the sidebar and post title for header post type
     */
    public function filter_default_single_content($default_content) {
        if ( is_singular('header') ) {
            $default_content = array(
                'template' => 'main-content--sidebarless',
                'components' => array(
                    array( 
                        'type' => 'main', 
                        'components' => array(
                            array( 'type' => 'post-content' )
                        )
                    ),
                )
            );
        }
        return $default_content;
    }
}