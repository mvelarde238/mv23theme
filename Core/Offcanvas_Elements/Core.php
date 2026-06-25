<?php
namespace Core\Offcanvas_Elements;

use Core\Offcanvas_Elements\Settings;
use Core\Utils\CPT;
use Core\Builder\Template_Engine;
use Core\Builder\Conditional_Rendering;
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
				'supports' => array('title'),
                'public' => false,
			)
		);
	}

    function register_settings(){
        Settings::instance();
    }

    private function set_elements(){
        $args = array( 'post_type' => $this->slug, 'fields' => 'ids', 'numberposts' => -1,  );
        $posts = get_posts($args);

        foreach ( $posts as $post_id ) {
            $oce_element_comp = null;

            $page_content = get_post_meta( $post_id, 'page_content', true );
            $page_content_datastore = get_post_meta( $post_id, 'page_content_datastore', true );
            $page_content = Page::consolidate_content( $page_content, $page_content_datastore );

            if ( is_array( $page_content ) ) :

                $wrapper = $page_content['pages'][0]['frames'][0]['component'] ?? null;
                if ( !$wrapper['type'] === 'wrapper' ) return '';

                $container = null;
                foreach ( $wrapper['components'] as $component ) {
                    if ( $component['type'] === 'container' ) {
                        $container = $component;
                        break;
                    }
                }
                $container_components = ( $container ) ? $container['components'] : [];

                if ( is_array( $container_components ) && !empty( $container_components ) ) :
                    foreach ( $container_components as $component ) :
                        if ( $component['type'] === 'oce-element' ) {
                            $oce_element_comp = $component;
                            break;
                        }
                    endforeach;
                endif;
            else :
                return '';
            endif;

            if ( !$oce_element_comp ) {
                continue;
            }

            // look for modal content and close button components inside the oce element component
            $oce_modal_content_comp = null;
            $oce_modal_close_comp = null;

            foreach ( $oce_element_comp['components'] ?? [] as $component ) {
                if ( $component['type'] === 'oce-modal-content' ) {
                    $oce_modal_content_comp = $component;
                }
                if ( $component['type'] === 'icon-box' ) {
                    $oce_modal_close_comp = $component;
                }
            }

            // Check visibility rules stored in the component's datastore.
            if ( Conditional_Rendering::instance()->should_hide_element( $oce_element_comp['visibility_settings'] ?? array() ) ) {
                continue;
            }

            $type    = $oce_element_comp['oce_type'] ?? '';
            $content = $oce_modal_content_comp;
            $close_button = $oce_modal_close_comp;
            $styles  = Page::compile_styles_to_css( $page_content['styles'] ?? [] );
            $styles .= Page::compile_components_custom_css( $page_content );
            $settings = $oce_element_comp['settings'] ?? array();
            if ( !is_array( $settings ) ) $settings = array();

            $kebab_cased_slug = str_replace( '_', '-', $this->slug );

            if ( isset( $oce_element_comp['attributes'] ) && isset( $oce_element_comp['attributes']['id'] ) ) {
                $element_id = $oce_element_comp['attributes']['id'];
            } elseif ( isset( $settings['id'] ) && $settings['id'] != '' ) {
                $element_id = $settings['id'];
            } else {
                $element_id    = $kebab_cased_slug . '-' . $post_id;
                $settings['id'] = $element_id;
            }

            $oce_uid = $oce_element_comp['oce_uid'] ?? null;
            // if ( $oce_uid ) $element_id = $oce_uid;

            $element_classes = [ $kebab_cased_slug, str_replace( '_', '-', $type ) ];
            if ( $type === 'bottom_sheet' ) $element_classes[] = 'modal';

            $trigger_events = get_post_meta( $post_id, $this->slug . '_trigger_events', true );
            $oce_settings = array(
                'position'                    => $oce_element_comp['position'] ?? '',
                'dismissible'                 => $oce_element_comp['dismissible'] ?? true,
                'close_on_click'              => $oce_element_comp['close_on_click'] ?? true,
                'max_width'                   => $oce_element_comp['max_width'] ?? '',
                'max_height'                  => $oce_element_comp['max_height'] ?? '',
                'overlay_color'               => $oce_element_comp['overlay_color'] ?? [],
                'remove_modal_content_padding' => $oce_element_comp['remove_modal_content_padding'] ?? false,
            );

            $this->elements[] = array(
                'uid'                => $oce_uid ?? $element_id,
                'post_id'            => $post_id,
                'title'              => get_the_title( $post_id ),
                'additional_classes' => $element_classes,
                'type'               => $type,
                'content'            => $content,
                'close_button'       => $close_button,
                'styles'             => $styles,
                'oce_settings'       => $oce_settings,
                'trigger_events'     => $trigger_events,
                'settings'           => $settings,
                'attributes'         => array(
                    'id' => $element_id
                ),
                'additional_attributes' => array(
                    'data-oce-uid' => $oce_uid
                )
            );
        }
    }

    function get_elements(){
        return $this->elements;
    }

    private function print_close_button( array $element_args ): void {
        $is_sidenav = $element_args['type'] === 'sidenav';

        if ( !$is_sidenav && !( $element_args['oce_settings']['dismissible'] ?? false ) ) {
            return;
        }

        if ( $element_args['close_button'] ) {
            echo Template_Engine::getInstance()->handle( $element_args['close_button'] );
        } else {
            $close_class = $is_sidenav ? 'sidenav-close' : 'modal-close';
            echo '<a href="#!" class="' . $close_class . '"></a>';
        }
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
                echo Template_Engine::check_components( $element_args['content'] );
            } 
            echo '</div>';

            $this->print_close_button( $element_args );
            echo '</div>';
        }
    }
}