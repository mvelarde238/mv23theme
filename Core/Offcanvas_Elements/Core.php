<?php
namespace Core\Offcanvas_Elements;

use Core\Offcanvas_Elements\Settings;
use Core\Builder\Blocks_Layout;
use Core\Utils\CPT;
use Core\Builder\Template_Engine;

define ('OFFCANVAS_ELEMENTS_DIR', __DIR__);
define ('OFFCANVAS_ELEMENTS_PATH', get_template_directory_uri() . '/Core/Offcanvas_Elements');

class Core{
	private static $instance = null;

    private $slug = 'offcanvas_element';

    private $elements = array();

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Core();
        }
        return self::$instance;
    }
    
    // Constructor privado para evitar la creación directa de la instancia
    private function __construct(){}

    public function enqueue_scripts(){
        $theme = wp_get_theme();
        $text_domain = $theme->get( 'TextDomain' );

        $this->set_elements();
        wp_localize_script( $text_domain . '-scripts', strtoupper( $this->get_plural_slug() ), $this->get_elements() );
    }

    public function get_slug(){
        return $this->slug;
    }

    private function get_plural_slug(){
        return $this->slug.'s';
    }

	public function register_post_type(){
		$offcanvas_pt = new CPT(
			array(
				'post_type_name' => $this->slug,
				'plural' => 'Off-Canvas Elements',
			), 
			array(
				'show_in_menu' => 'theme-options-menu',
				'show_in_nav_menus' => false,
                'exclude_from_search' => true,
				'show_ui' => true,
				'supports' => array('title')
			)
		);
	}

    function register_settings(){
        Settings::instance();
    }

    private function check_the_restrictions( $offcanvas_element_post_id ){
        $is_restricted = false;

        if( $offcanvas_element_post_id ){
            $restrictions = get_post_meta( $offcanvas_element_post_id, $this->slug.'_restrictions', true );
            $all_restrictions = array();

            if( is_array($restrictions) && count($restrictions) > 0 ){

                $restrictions_classes_map = array();
                $restrictions_classes = Settings::get_classes_for( 'restrictions' );
		        foreach( $restrictions_classes as $class_name ) {
                    $type = $class_name::get_type();
			        $restrictions_classes_map[$type] = $class_name;
                    $all_restrictions[$type] = array();
		        }

                foreach ($restrictions as $restriction) {
                    $type = $restriction['__type'];
                    $all_restrictions[$type][] = array(
                        'type' => $type,
                        'is_restricted' => $restrictions_classes_map[$type]::check_restrictions( $restriction )
                    );
                }
            } else {
                // there are no restrictions
                $all_restrictions['none'] = array( array( 'type' => 'none', 'is_restricted' => false ) );
            }

            $restrictions_check_in = array();
            foreach ($all_restrictions as $restrictions_by_type) {
                if( !empty($restrictions_by_type) ){
                    $is_restricted_in_type = !in_array( false, array_column( $restrictions_by_type, 'is_restricted' ), true );
                    $restrictions_check_in[] = $is_restricted_in_type;
                }
            }

            $is_restricted = in_array( true, $restrictions_check_in, true );
        }

        return $is_restricted;
    }

    private function set_elements(){
        $args = array( 'post_type' => $this->slug, 'fields' => 'ids', 'numberposts' => -1,  );
        $posts = get_posts($args);

        foreach ( $posts as $post_id ) {
            $is_restricted = $this->check_the_restrictions($post_id);
            if(!$is_restricted){
                $type = get_post_meta( $post_id, $this->slug.'_type', true );
                $content_type = get_post_meta( $post_id, $this->slug.'_content_type', true );
                $content = ( $content_type == 'async' ) ? null : get_post_meta( $post_id, $this->slug.'_content', true );
    
                $kebab_cased_slug = str_replace('_','-',$this->slug);
                $settings = get_post_meta( $post_id, $this->slug.'_settings', true );
                if( !is_array($settings) ) $settings = array();

                if( isset($settings['main_attributes']) && isset($settings['main_attributes']['id']) ) {
                    $element_id = $settings['main_attributes']['id'];
                } else {
                    $element_id = $kebab_cased_slug.'-'.$post_id;
                    $settings['main_attributes']['id'] = $element_id;
                }

                $element_classes = [ $kebab_cased_slug, str_replace('_','-',$type) ];
                if( $type === 'bottom_sheet' ) $element_classes[] = 'modal';
    
                $trigger_events = get_post_meta( $post_id, $this->slug.'_trigger_events', true );
                $async_settings = get_post_meta( $post_id, $this->slug.'_async_settings', true );
                $oce_settings = get_post_meta( $post_id, $this->slug.'_'.$type.'_settings', true );

                $this->elements[] = array(
                    'id' => $element_id,
                    'is_restricted' => $is_restricted,
                    'title' => get_the_title($post_id),
                    'additional_classes' => $element_classes,
                    'type' => $type,
                    'content' => $content,
                    'content_type' => $content_type,
                    'oce_settings' => $oce_settings,
                    'async_settings' => $async_settings,
                    'trigger_events' => $trigger_events,
                    'settings' => $settings
                );
            }
        }
    }

    function get_elements(){
        return $this->elements;
    }

    function print_elements(){
        foreach ( $this->get_elements() as $element_args ) { 

            $modal_content_args = array();
            if (array_key_exists('padding', $element_args['settings'])) {
                $modal_content_args['settings']['padding'] = $element_args['settings']['padding'];
            }
            unset($element_args['settings']['padding']);
            $attributes = Template_Engine::generate_attributes( $element_args );
            $content_attributes = Template_Engine::generate_attributes( $modal_content_args );

            echo '<div '.$attributes.'>';
            echo '<div class="modal-content" '.$content_attributes.'>';
            if($element_args['content']) echo Blocks_Layout::the_content($element_args['content']);
            echo '</div>';
            if( $element_args['type'] === 'sidenav' ){
                echo '<a href="#!" class="sidenav-close"></a>';
            } else {
                if( $element_args['oce_settings']['dismissible'] ) echo '<a href="#!" class="modal-close"></a>';
            }
            echo '</div>';
        }
    }

    /**
     * Delete the post meta that is not related to the element type selected
     */
    public function handle_save_post_hook( $post_id, $post, $update ){
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        if ( get_post_type( $post_id ) !== $this->slug ) return;

        $type = get_post_meta( $post_id, $this->slug.'_type', true );
        $types = array( 'modal','sidenav','bottom_sheet' );
        foreach ($types as $t) {
            if( $t != $type ) delete_post_meta( $post_id, $this->slug.'_'.$t.'_settings' );
        }
    }
}