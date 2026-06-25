<?php
namespace Core\Posttype;

use Core\Utils\CPT;
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Core\Builder\Core as Builder_Core;

class Footer {

    private static $instance = null;

	public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Footer();

            add_action( 'admin_action_save_as_theme_footer_post', array( self::$instance, 'save_as_theme_footer'));
            add_filter( 'post_row_actions', array( self::$instance, 'add_action_in_admin_list'), 10, 2 );
        }
        return self::$instance;
    }

    private function __construct(){}

	public function register_posttype(){
        $footers = new CPT(
            array(
                'post_type_name' => 'footer',
                'singular' => __('Footer', 'mv23theme'),
                'plural' => __('Footers', 'mv23theme')
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

        $footers->columns(array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Title'),
            'is_theme_footer' => __('Activate','mv23theme'),
            'date' => __('Date')
        ));

        $footers->populate_column('is_theme_footer', array($this, 'handle_is_theme_footer_admin_column'));
    }

    public function add_meta_boxes(){
        $datastore = new \Ultimate_Fields\Datastore\Options;

        $location = new \Ultimate_Fields\Location\Post_Type('footer');
	    $location->overwrite_datastore( $datastore );
        $location->context = 'side';

        Container::create( 'page_footer' ) 
            ->add_location( $location )
            ->add_fields(array(
                Field::create( 'wp_object', 'theme_footer_post', __('Footer','mv23theme') )
                    ->add( 'posts', 'post_type=footer' )
                    ->hide_label()
            ));

        Container::create( 'footer_settings' )
            ->add_location( 'post_type', 'footer' )
            ->set_description_position('label')
		    ->set_title('Footer Settings')
		    ->add_fields(array(
                Field::create( 'ultimate_builder', 'page_content', __('Content','mv23theme') )
                    ->add_groups( Builder_Core::getInstance()->get_groups_for_builder() )
            ));
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
    		__('Activate Theme Footer','mv23theme')
    	);

        return $actions;
    }
}