<?php
namespace Core\Offcanvas_Elements;

use Core\Offcanvas_Elements\Settings;
use Core\Utils\CPT;
use Core\Builder\Template_Engine;
use Core\Frontend\Page;

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
                $uf_component = null;
                $page_content = get_post_meta( $post_id, 'page_content_components', true );
                if (is_array($page_content)) :
		        	// wrapper > components > container > components:
		        	$container_components = $page_content[0]['components'][0]['components'] ?? [];
		        	if (is_array($container_components) && !empty($container_components)) :
		        		foreach ($container_components as $component) :
                            if ( $component['__type'] === 'offcanvas_element' ) {
                                $uf_component = $component;
                                break; // Exit the loop once we find the oce-element
                            }
		        		endforeach;
		        	endif;
		        else: 
		        	return '';
		        endif;

                if ( !$uf_component ) {
                    continue; // Skip to the next post if no oce-element component is found
                }

                $type = $uf_component['oce_type'];
                $content = $uf_component['components'] ?? [];
                $styles = get_post_meta( $post_id, 'page_content_styles', true );
                $settings = $uf_component['settings'] ?? array();
                if( !is_array( $settings ) ) $settings = array();
                
                $kebab_cased_slug = str_replace('_','-',$this->slug);
                if( isset($uf_component['__gjsAttributes']) && isset($uf_component['__gjsAttributes']['id']) ) {
                    $element_id = $uf_component['__gjsAttributes']['id'];
                }
                elseif( isset($settings['id']) && $settings['id'] != '' ) {
                    $element_id = $settings['id'];
                } else {
                    $element_id = $kebab_cased_slug.'-'.$post_id;
                    $settings['id'] = $element_id;
                }

                $element_classes = [ $kebab_cased_slug, str_replace('_','-',$type) ];
                if( $type === 'bottom_sheet' ) $element_classes[] = 'modal';
    
                $trigger_events = get_post_meta( $post_id, $this->slug.'_trigger_events', true );
                $oce_settings = array(
                    'position' => $uf_component['position'] ?? '',
                    'dismissible' => $uf_component['dismissible'] ?? true,
                    'close_on_click' => $uf_component['close_on_click'] ?? true,
                    'max_width' => $uf_component['max_width'] ?? '',
                    'max_height' => $uf_component['max_height'] ?? '',
                    'overlay_color' => $uf_component['overlay_color'] ?? [],
                    'remove_modal_content_padding' => $uf_component['remove_modal_content_padding'] ?? false,
                );

                $this->elements[] = array(
                    'id' => $element_id,
                    'post_id' => $post_id,
                    'is_restricted' => $is_restricted,
                    'title' => get_the_title($post_id),
                    'additional_classes' => $element_classes,
                    'type' => $type,
                    'content' => $content,
                    'styles' => $styles,
                    'oce_settings' => $oce_settings,
                    'trigger_events' => $trigger_events,
                    'settings' => $settings,
                    '__gjsAttributes' => array( 'id' => $element_id )
                );
            }
        }
    }

    function get_elements(){
        return $this->elements;
    }

    function print_elements(){
        foreach ( $this->get_elements() as $element_args ) { 
            $attributes = Template_Engine::generate_attributes( $element_args );

            if( !empty( $element_args['styles'] ) ) {
                echo '<style>'.$element_args['styles'].'</style>';
            }

            echo '<div '.$attributes.'>';
            echo '<div class="modal-content">';
            if($element_args['content']){
                foreach ( $element_args['content'] as $component ) :
                    echo Template_Engine::getInstance()->handle( $component['__type'], $component );
                endforeach;
            } 
            echo '</div>';

            if( $element_args['type'] === 'sidenav' ){
                echo '<a href="#!" class="sidenav-close"></a>';
            } else {
                if( $element_args['oce_settings']['dismissible'] ) echo '<a href="#!" class="modal-close"></a>';
            }
            echo '</div>';
        }
    }
}