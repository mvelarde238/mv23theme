<?php
namespace Ultimate_Fields\Ultimate_Builder;

use Ultimate_Fields\Template;
use Ultimate_Fields\Ultimate_Builder\Editor;

/**
 * A base class for the extension, which adds and overwrites all necessary classes.
 *
 * @since 1.0
 */
class Ultimate_Builder {
	/**
	 * Static instance of the class.
	 *
	 * @since 1.0
	 * @var Ultimate_Builder
	 */
	private static $instance;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * Holds the path of the plugin file in order to load assets properly.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected $plugin_file;

	/**
	 * The version of the plugin, used for assets.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected $version;

	/**
	 * Holds the registered GJS plugins.
	 *
	 * @since 1.0
	 * @var array
	 */
	private $gjs_plugins = array(
		[ 'name' => 'gjsHoverLayer', 'handler' => 'gjs-hover-layer', 'isComponent' => false ],
		[ 'name' => 'gjsCommands', 'handler' => 'gjs-commands', 'isComponent' => false ],
		[ 'name' => 'gjsExtendComponents', 'handler' => 'gjs-extend-components', 'isComponent' => false ],
		[ 'name' => 'handleCommonSettings', 'handler' => 'handle-common-settings', 'isComponent' => false ],
		// components
		[ 'name' => 'gjsBase', 'handler' => 'gjs-base', 'isComponent' => true ],
		[ 'name' => 'gjsWrapper', 'handler' => 'gjs-wrapper', 'isComponent' => true ],
		[ 'name' => 'gjsCompWrapper', 'handler' => 'gjs-comp-wrapper', 'isComponent' => true ],
		[ 'name' => 'gjsListing', 'handler' => 'gjs-listing', 'isComponent' => true ],
		[ 'name' => 'gjsGallery', 'handler' => 'gjs-gallery', 'isComponent' => true ],
		[ 'name' => 'gjsMenu', 'handler' => 'gjs-menu', 'isComponent' => true ],
		[ 'name' => 'gjsSpacer', 'handler' => 'gjs-spacer', 'isComponent' => true ],
		[ 'name' => 'gjsReusableSection', 'handler' => 'gjs-reusable-section', 'isComponent' => true ],
		[ 'name' => 'gjsContainer', 'handler' => 'gjs-container', 'isComponent' => true ],
		[ 'name' => 'gjsSection', 'handler' => 'gjs-section', 'isComponent' => true ],
		[ 'name' => 'gjsMap', 'handler' => 'gjs-map', 'isComponent' => true ],
		[ 'name' => 'gjsOceComponents', 'handler' => 'gjs-oce-components', 'isComponent' => true ],
		// external components
		[ 'name' => 'gjsContextMenu', 'handler' => 'gjs-context-menu', 'isExternal' => true ],
		[ 'name' => 'gjsRowAndCols', 'handler' => 'gjs-row-and-cols', 'isExternal' => true ],
		[ 'name' => 'gjsTogglebox', 'handler' => 'gjs-togglebox', 'isExternal' => true ],
		[ 'name' => 'gjsFlipbox', 'handler' => 'gjs-flip-box', 'isExternal' => true ],
		[ 'name' => 'gjsCarousel', 'handler' => 'gjs-carousel', 'isExternal' => true ],
		[ 'name' => 'gjsImages', 'handler' => 'gjs-images', 'isExternal' => true ],
		[ 'name' => 'gjsVideo', 'handler' => 'gjs-video', 'isExternal' => true ],
	);

	/**
	 * Class constructor, instantiates all necessary functionality.
	 *
	 * @since 1.0
	 * @param string $plugin_file The path to the main plugin file.
	 * @param string $version     A version to be used for assets and etc.
	 */
	public function __construct( $plugin_file, $version ) {
		self::$instance = $this;
		
		$this->plugin_name = 'ultimate-builder';
		$this->plugin_file = $plugin_file;
		$this->version     = $version;

		Template::instance()->add_path( dirname( $plugin_file ) . '/templates/' );

		add_filter( 'uf.field.class', array( $this, 'generate_field_class' ), 10, 2 );
		add_action( 'uf.register_scripts', array( $this, 'register_scripts' ) );
		add_action( 'post_action_ultimate-builder', array( $this, 'prepare_admin_for_builder' ) );
	}

	/**
	 * Allows the class that should be used for a field to be generated.
	 *
	 * @since 1.0
	 *
	 * @param string $class_name The class name that would be used for the field.
	 * @param string $type       The expected field type (ex. `text`).
	 * @return string
	 */
	public function generate_field_class( $class_name, $type ) {
		if( 'ultimate_builder' === strtolower( $type ) ) {
			return Field::class;
		} else {
			return $class_name;
		}
	}

	/**
	 * Registers the necessary assets.
	 *
	 * @since 1.0
	 */
	public function register_scripts() {
		// $assets = plugins_url( 'assets/', $this->plugin_file );
        $assets = BUILDER_PATH . '/uf-extend/ultimate-builder/assets/';
		$v      = $this->version;

		// FIELD SCRIPT
		wp_register_script( 'uf-field-ultimate-builder', $assets . 'js/field-ultimate-builder.js', array('uf-field-repeater'), $v );
		// wp_register_style( 'uf-field-ultimate-builder', $assets . 'css/field.css', array( 'ultimate-fields-css' ), $v );
	}

	public function prepare_admin_for_builder() {
		$screen = get_current_screen();
		if ( 
			in_array( $screen->base, [ 'post', 'post-new' ], true ) 
			&& isset( $_GET['action'] )
			&& isset( $_GET['meta'] )
			&& $_GET['action'] === 'ultimate-builder' ) 
		{
			$assets = BUILDER_PATH . '/uf-extend/ultimate-builder/assets/';
			$v      = $this->version;

			wp_register_style( 'gjs-context-menu-style', $assets . 'css/gjs-context-menu/style.css', array(), $v );
			wp_register_style( 'builder-admin-styles', $assets . 'css/builder-admin.css', array(), $v );
			wp_register_style( 'canvas-css', $assets . 'css/canvas.css', array(), $v );
			wp_register_style( 'builder-app-styles', 'http://builder.lo/react/my-react-app/dist/app.css', array(), $v ); 

			wp_register_script( 'builder-app', 'http://builder.lo/react/my-react-app/dist/app.js', array(), $v );
			wp_register_script( 'gjs-context-menu-options', $assets . 'js/context-menu-options.js', array(), $v );
			$this->register_gjs_plugins();
			wp_register_script( 'builder', $assets . 'js/builder.js', array(), $v );

			wp_localize_script( 'builder-app', 'BUILDER_GLOBALS', array(
				'posttype' => get_post_type()
			));

			$this->filter_admin_body_class();
			$this->clean_admin_assets();
			$this->print_editor();
		}
	}

	private function register_gjs_plugins(){
		$assets = BUILDER_PATH . '/uf-extend/ultimate-builder/assets/';
		$v = $this->version;

		foreach( $this->gjs_plugins as $plugin) {
			if( isset( $plugin['isExternal'] ) && $plugin['isExternal'] === true ) {
				$script_url = 'http://builder.lo/' . $plugin['handler'] . '/dist/index.js';
			} else {
				$folder = $plugin['isComponent'] ? 'components' : 'plugins';
				$script_url = $assets . 'js/' . $folder . '/' . $plugin['handler'] . '.js';
			}

			wp_register_script( $plugin['handler'], $script_url, array(), $v );
		}
	}

	/**
	 * Get the registered GJS plugins.
	 *
	 * @since 1.0
	 * @return array Array of plugin name => handle pairs
	 */
	public function get_gjs_plugins() {
		return $this->gjs_plugins;
	}

	/**
	 * Get the instance of Ultimate_Builder.
	 *
	 * @since 1.0
	 * @return Ultimate_Builder|null
	 */
	public static function instance() {
		return self::$instance;
	}

	public function filter_admin_body_class(){
		$editor_class = $this->plugin_name . '-editor';
		add_filter( 'admin_body_class', static function($classes) use ($editor_class){
			return "$classes is-fullscreen-mode " . $editor_class;
		}, 10, 1 );
	}

	private function clean_admin_assets(){
		/*
		 * Disable Emoji replacement
		 */
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );

		/*
		 * Disable menu for toggling Document Panels.
		 */
		add_filter( 'screen_options_show_screen', '__return_false' );
	}

	private function print_editor(){
        global $post;

		$post_meta_name = isset( $_GET['meta'] ) ? $_GET['meta'] : '';

		if( !empty( $post_meta_name ) ) {
			uf_head(array(
				'item' => 'post_'.$post->ID,
				'item_fields' => array( 
					$post_meta_name
				),
				'containers' => array( 'page_content_container' )
			)); 
	
			wp_enqueue_script( 'builder-app' );
			wp_enqueue_style( 'builder-app-styles' );
			wp_enqueue_style( 'builder-admin-styles' );
			require_once ABSPATH . 'wp-admin/admin-header.php';
	
			uf_form();
	
			require_once ABSPATH . 'wp-admin/admin-footer.php';
			exit;
		}
	}
}
