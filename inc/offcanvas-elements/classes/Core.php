<?php
namespace Offcanvas_Elements;

use Offcanvas_Elements\Settings;
use \Blocks_Layout;
use \CPT;

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
    private function __construct(){
        $core_directory = trailingslashit( dirname( __DIR__ ) );
        require_once( __DIR__ . '/Autoloader.php' );
		new Autoloader( 'Offcanvas_Elements', $core_directory . DIRECTORY_SEPARATOR . 'classes' );

        $this->register_post_type();
        $this->add_action( 'uf.init', array($this, 'register_settings') );
        $this->add_action( 'wp_enqueue_scripts', function(){
            $this->set_elements();
            wp_localize_script( 'mv23-scripts', strtoupper( $this->get_plural_slug() ), $this->get_elements() );
        },1000);
        $this->add_action( 'footer_code', array($this, 'print_elements') );
        $this->add_action( 'save_post', array($this, 'handle_save_post_hook'), 99, 3 );
    }

    /**
     * Helper function to add add_action WordPress filters.
     */
    private function add_action( $action, $function, $priority = 10, $accepted_args = 1 ) {
        add_action( $action, $function, $priority, $accepted_args );
    }  

    public function get_slug(){
        return $this->slug;
    }

    private function get_plural_slug(){
        return $this->slug.'s';
    }

	private function register_post_type(){
		$offcanvas_pt = new CPT(
			array(
				'post_type_name' => $this->slug,
				'plural' => 'Off-Canvas Elements',
			), 
			array(
				'show_in_menu' => 'theme-options-menu',
				'show_in_nav_menus' => false,
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
                $element_id = $kebab_cased_slug.'-'.$post_id;
                $element_classes = [ $kebab_cased_slug, str_replace('_','-',$type) ];
                if( $type === 'bottom_sheet' ) $element_classes[] = 'modal';
    
                $trigger_events = get_post_meta( $post_id, $this->slug.'_trigger_events', true );
                $async_settings = get_post_meta( $post_id, $this->slug.'_async_settings', true );
                $settings = get_post_meta( $post_id, $this->slug.'_'.$type.'_settings', true );

                $this->elements[] = array(
                    'id' => $element_id,
                    'is_restricted' => $is_restricted,
                    'title' => get_the_title($post_id),
                    'class' => implode(' ',$element_classes),
                    'type' => $type,
                    'content' => $content,
                    'content_type' => $content_type,
                    'settings' => $settings,
                    'async_settings' => $async_settings,
                    'trigger_events' => $trigger_events
                );
            }
        }
    }

    function get_elements(){
        return $this->elements;
    }

    function print_elements(){
        foreach ( $this->get_elements() as $element ) { ?>
            <div id="<?=$element['id']?>" class="<?=$element['class']?>">

                <div class="modal-content">
                    <?php if($element['content']) echo Blocks_Layout::the_content($element['content']); ?>
                </div>

                <?php if( $element['type'] === 'sidenav' ){
                    echo '<a href="#!" class="sidenav-close"></a>';
                } else {
                    if( $element['settings']['dismissible'] ) echo '<a href="#!" class="modal-close"></a>';
                } ?>
            </div>
            <?php
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