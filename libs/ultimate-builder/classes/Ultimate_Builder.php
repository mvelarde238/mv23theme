<?php
namespace Ultimate_Fields\Ultimate_Builder;

use Ultimate_Fields\Template;
use Ultimate_Fields\Ultimate_Builder\Editor;
use Ultimate_Fields\Ultimate_Builder\Handlebars;

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
		[ 'name' => 'gjsI18n', 'handler' => 'gjs-i18n', 'isComponent' => false ],
		[ 'name' => 'gjsExtendEditor', 'handler' => 'gjs-extend-editor', 'isComponent' => false ],
		[ 'name' => 'gjsCommands', 'handler' => 'gjs-commands', 'isComponent' => false ],
		[ 'name' => 'gjsHoverLayer', 'handler' => 'gjs-hover-layer', 'isComponent' => false ],
		[ 'name' => 'gjsExtendComponents', 'handler' => 'gjs-extend-components', 'isComponent' => false ],
		// gjsDatastoreUndo: must load after gjsExtendComponents (depends on temporalCompStore)
		[ 'name' => 'gjsDatastoreUndo', 'handler' => 'gjs-datastore-undo', 'isComponent' => false ],
		/*
		* gjsDynamicDataSelector, this plugin depends on:
		- 'openDatastore' event, defined on gjsExtendComponents, to know when to attach the selector
		*/
		[ 'name' => 'gjsDynamicDataSelector', 'handler' => 'gjs-dynamic-data-selector', 'isComponent' => false ],
		[ 'name' => 'handleCommonSettings', 'handler' => 'handle-common-settings', 'isComponent' => false ],
		[ 'name' => 'gjsExtendSmProperties', 'handler' => 'gjs-extend-sm-properties', 'isComponent' => false ],
		[ 'name' => 'saveTemplateSystem', 'handler' => 'save-template-system', 'isComponent' => false ],
		// Shared resources: (must load before components that use it)
		[ 'name' => 'gjsSharedTemplates', 'handler' => 'gjs-shared-templates', 'isComponent' => false ],
		[ 'name' => 'handleThemeColors', 'handler' => 'handle-theme-colors', 'isComponent' => false ],
		// components
		[ 'name' => 'gjsBase', 'handler' => 'gjs-base', 'isComponent' => true ],
		[ 'name' => 'gjsAsyncComponent', 'handler' => 'gjs-async-component', 'isComponent' => true ],
		[ 'name' => 'gjsWrapper', 'handler' => 'gjs-wrapper', 'isComponent' => true ],
		[ 'name' => 'gjsHeader', 'handler' => 'gjs-header', 'isComponent' => true ],
		[ 'name' => 'gjsHeaderPreview', 'handler' => 'gjs-header-preview', 'isComponent' => true ],
		[ 'name' => 'gjsFooterPreview', 'handler' => 'gjs-footer-preview', 'isComponent' => true ],
		[ 'name' => 'gjsThemeOptions', 'handler' => 'gjs-theme-options', 'isComponent' => true ],
		[ 'name' => 'gjsGlobalStyles', 'handler' => 'gjs-global-styles', 'isComponent' => true ],
		[ 'name' => 'gjsCompWrapper', 'handler' => 'gjs-components-wrapper', 'isComponent' => true ],
		[ 'name' => 'gjsListing', 'handler' => 'gjs-listing', 'isComponent' => true ],
		[ 'name' => 'gjsGallery', 'handler' => 'gjs-gallery', 'isComponent' => true ],
		[ 'name' => 'gjsMenu', 'handler' => 'gjs-menu', 'isComponent' => true ],
		[ 'name' => 'gjsSpacer', 'handler' => 'gjs-spacer', 'isComponent' => true ],
		[ 'name' => 'gjsReusableSection', 'handler' => 'gjs-reusable-section', 'isComponent' => true ],
		[ 'name' => 'gjsContainer', 'handler' => 'gjs-container', 'isComponent' => true ],
		[ 'name' => 'gjsSection', 'handler' => 'gjs-section', 'isComponent' => true ],
		[ 'name' => 'gjsMap', 'handler' => 'gjs-map', 'isComponent' => true ],
		[ 'name' => 'gjsOceComponents', 'handler' => 'gjs-oce-components', 'isComponent' => true ],
		// [ 'name' => 'gjsSinglePageStructure', 'handler' => 'gjs-single-page-structure', 'isComponent' => true ],
		[ 'name' => 'gjsPostTitle', 'handler' => 'gjs-post-title', 'isComponent' => true ],
		[ 'name' => 'gjsSidebar', 'handler' => 'gjs-sidebar', 'isComponent' => true ],
		[ 'name' => 'gjsPostContent', 'handler' => 'gjs-post-content', 'isComponent' => true ],
		[ 'name' => 'gjsSocialShare', 'handler' => 'gjs-social-share', 'isComponent' => true ],
		[ 'name' => 'gjsRelatedPosts', 'handler' => 'gjs-related-posts', 'isComponent' => true ],
		[ 'name' => 'gjsCommentsArea', 'handler' => 'gjs-comments-area', 'isComponent' => true ],
		[ 'name' => 'gjsBreadcrumbs', 'handler' => 'gjs-breadcrumbs', 'isComponent' => true ],
		[ 'name' => 'gjsArchivePageStructure', 'handler' => 'gjs-archive-page-structure', 'isComponent' => true ],
		[ 'name' => 'gjsIconBox', 'handler' => 'gjs-icon-box', 'isComponent' => true ],
		[ 'name' => 'gjsIconAndText', 'handler' => 'gjs-icon-and-text', 'isComponent' => true ],
		[ 'name' => 'gjsFlipbox', 'handler' => 'gjs-flip-box', 'isComponent' => true ],
		[ 'name' => 'gjsCounter', 'handler' => 'gjs-counter', 'isComponent' => true ],
		[ 'name' => 'gjsHeading', 'handler' => 'gjs-heading', 'isComponent' => true ],
		[ 'name' => 'gjsButton', 'handler' => 'gjs-button', 'isComponent' => true ],
		[ 'name' => 'gjsCarouselParts', 'handler' => 'gjs-carousel-parts', 'isComponent' => true ],
		[ 'name' => 'gjsCarousel', 'handler' => 'gjs-carousel', 'isComponent' => true ],
		[ 'name' => 'gjsTemplatePlaceholder', 'handler' => 'gjs-template-placeholder', 'isComponent' => true ],
		[ 'name' => 'gjsMainContent', 'handler' => 'gjs-main-content', 'isComponent' => true ],
		[ 'name' => 'gjsTestimonial', 'handler' => 'gjs-testimonial', 'isComponent' => true ],
		[ 'name' => 'gjsIconList', 'handler' => 'gjs-icon-list', 'isComponent' => true ],
		// external components
		[ 'name' => 'gjsContextMenu', 'handler' => 'gjs-context-menu', 'isExternal' => true, 'hasCss' => true ],
		[ 'name' => 'gjsRowAndCols', 'handler' => 'gjs-row-and-cols', 'isExternal' => true ],
		[ 'name' => 'gjsTogglebox', 'handler' => 'gjs-togglebox', 'isExternal' => true ],
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
		add_action( 'wp_ajax_ultimate_builder_preview_save', array( Preview_Handler::class, 'ajax_preview_save' ) );
		add_action( 'wp_ajax_migrate_post_content_to_builder', array( $this, 'ajax_migrate_post_content' ) );
		add_action( 'init', array( Preview_Handler::class, 'maybe_apply_preview' ), 1 );
		add_action( 'init', array( $this, 'remove_plugins_support') );
	
		// Initialize screen helper for builder detection
		Screen_Helper::init();
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
        $assets = LIBS_PATH . '/ultimate-builder/assets/';
		$v      = $this->version;

		// FIELD SCRIPT
		wp_register_script( 'handlebars', $assets . 'js/handlebars.js', array(), $v );
		wp_register_script( 'uf-field-ultimate-builder', $assets . 'js/field-ultimate-builder.js', array('uf-field-repeater', 'handlebars'), $v );
		wp_register_style( 'uf-field-ultimate-builder', $assets . 'css/field.css', array(), $v );

		// localize the script with the builder globals
		$builder_globals = $this->get_builder_globals();
		wp_localize_script( 'uf-field-ultimate-builder', 'BUILDER_GLOBALS', $builder_globals );
	}

	private function get_builder_globals() {
		$user_id = get_current_user_id();
		$post_id = get_the_ID();
		$posttype = get_post_type();
		$is_singular = ( $posttype === 'single_template');
		$is_page_for_posts = get_option('page_for_posts') == $post_id;
		$is_shop = class_exists( 'WooCommerce' ) && ( $posttype === 'product' || $posttype === 'product_template' );

		$builder_globals = array(
			'locale' => substr( get_user_locale($user_id), 0, 2 ),
			'posttype' => $posttype,
			'page_title' => get_the_title() ?: '',
			'referer' => wp_get_referer(),
			'post_edit_url' => admin_url( 'post.php?post=' . $post_id . '&action=edit' ),
			'admin_url' => admin_url( 'edit.php?post_type=' . $posttype ),
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'ultimate_builder_preview' ),
			'post_id' => $post_id,
			'post_content' => get_post_field( 'post_content', $post_id ),
			'is_singular' => $is_singular,
			'theme_colors' => get_option( 'theme_colors', array() ),
			'stickyHeaderBreakpoint' => STICKY_HEADER_BREAKPOINT,
			'masonry_is_active' => MASONRY_IS_ACTIVE,
			'context' => Handlebars::get_context(),
			'togglebox_state_icon' => TOGGLEBOX_STATE_ICON,
			'prev_carousel_icon' => PREV_CAROUSEL_ICON,
			'next_carousel_icon' => NEXT_CAROUSEL_ICON,
			'is_page_for_posts' => $is_page_for_posts,
			'is_shop' => $is_shop,
		);

		// archive templates data
		$is_archive = ( $posttype === 'archive_template') || $is_page_for_posts;
		$archive_settings = array();
		if($is_archive){
			$connected_posttype = get_post_meta( $post_id, 'connected_posttype', true );
			if( $connected_posttype ){
				$archive_settings['connected_posttype'] = $connected_posttype;
				$connected_taxonomy = get_post_meta( $post_id, 'connected_'.$connected_posttype.'_taxonomy', true );
				if( $connected_taxonomy ){
					$archive_settings['connected_'.$connected_posttype.'_taxonomy'] = $connected_taxonomy;
					$connected_terms = get_post_meta( $post_id, 'connected_'.$connected_taxonomy.'_terms', true );
					if( $connected_terms ){
						$archive_settings['connected_'.$connected_taxonomy.'_terms'] = $connected_terms;
					}
				}
			} else {
				if( $is_page_for_posts ){
					$archive_settings['connected_posttype'] = 'post';
				} elseif( $is_shop ){
					$archive_settings['connected_posttype'] = 'product';
				} else {
					// default to 'post' if no connected posttype is set for the archive template
					$archive_settings['connected_posttype'] = 'post';
				}
			}
		}
		$builder_globals['is_archive'] = $is_archive;
		$builder_globals['archive_settings'] = $archive_settings;

		// Edit header/footer URLs
		$options = array('header', 'footer');
		foreach ($options as $option) {
			$meta_key = 'theme_' . $option . '_post';
			$post_meta = get_option($meta_key);
			if ($post_meta) {
				$post_id = str_replace('post_', '', $post_meta);
				if ( IS_MULTILANGUAGE && function_exists('pll_get_post') ) {
					$post_id = pll_get_post($post_id);
				}
				$edit_url = admin_url( 'post.php?post=' . $post_id . '&action=edit&action=ultimate-builder&meta=page_content' );
				$builder_globals['edit_' . $option . '_url'] = esc_url( $edit_url );
			} else {
				$builder_globals['edit_' . $option . '_url'] = false;
			}
		}

		return $builder_globals;
	}

	public function prepare_admin_for_builder() {
		$screen = get_current_screen();
		if ( 
			in_array( $screen->base, [ 'post', 'post-new' ], true ) 
			&& isset( $_GET['action'] )
			&& isset( $_GET['meta'] )
			&& $_GET['action'] === 'ultimate-builder' ) 
		{
			$assets = LIBS_PATH . '/ultimate-builder/assets/';
			$v      = $this->version;
			$app_js_path = BUILDER_DEV_MODE ? 'http://builder.lo/react/my-react-app/dist/' : $assets. 'js/';
			$app_css_path = BUILDER_DEV_MODE ? 'http://builder.lo/react/my-react-app/dist/' : $assets. 'css/';

			wp_register_style( 'builder-admin-styles', $assets . 'css/builder-admin.css', array('wp-codemirror'), $v );
			wp_register_style( 'canvas-css', $assets . 'css/canvas.css', array(), $v );
			wp_register_style( 'builder-app-styles', $app_css_path . 'app.css', array(), $v ); 
			wp_register_script( 'builder-app', $app_js_path . 'app.js', array('wp-codemirror'), $v );
			wp_register_script( 'gjs-context-menu-options', $assets . 'js/context-menu-options.js', array(), $v );
			$this->register_gjs_plugins();
			wp_register_script( 'builder', $assets . 'js/builder.js', array(), $v );

			$this->filter_admin_body_class();
			$this->clean_admin_assets();
			$this->print_editor();
		}
	}

	private function register_gjs_plugins(){
		$assets = LIBS_PATH . '/ultimate-builder/assets/';
		$v = $this->version;

		foreach( $this->get_gjs_plugins() as $plugin) {
			if( isset( $plugin['isExternal'] ) && $plugin['isExternal'] === true ) {
				if( BUILDER_DEV_MODE ){
					$script_url = 'http://builder.lo/' . $plugin['handler'] . '/dist/index.js';
				} else {
					$script_url = $assets . 'js/external-plugins/' . $plugin['handler'] . '.js';
				}
			} else {
				if(isset( $plugin['url'] ) ){
					$script_url = $plugin['url'];
				} else {
					$folder = $plugin['isComponent'] ? 'components' : 'plugins';
					$script_url = $assets . 'js/' . $folder . '/' . $plugin['handler'] . '.js';
				}
			}

			wp_register_script( $plugin['handler'], $script_url, array(), $v );
		}

		// TODO: register dinamically if the plugin "hasCss" is true:
		$gjs_cm_css = BUILDER_DEV_MODE ? 'http://builder.lo/gjs-context-menu/dist/style.css' : $assets. 'js/external-plugins/gjs-context-menu.css';
		wp_register_style( 'gjs-context-menu-style', $gjs_cm_css, array(), $v );
	}

	/**
	 * Get the registered GJS plugins.
	 *
	 * @since 1.0
	 * @return array The registered GJS plugins.
	 */
	public function get_gjs_plugins() {
		$plugins = apply_filters( 'ultimate_builder.gjs_plugins', $this->gjs_plugins );

		return $plugins;
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
		if( ! $post ) return;

		$post_meta_name = isset( $_GET['meta'] ) ? $_GET['meta'] : '';

		if( !empty( $post_meta_name ) ) {
			uf_head(array(
				'item' => 'post_'.$post->ID,
				'item_fields' => array( 
					$post_meta_name
				),
				'containers' => array( 'page_content_container' )
			)); 

			// Enqueue post lock scripts
			Post_Lock_Handler::enqueue_scripts();
	
			wp_enqueue_script( 'builder-app' );
			wp_enqueue_style( 'builder-app-styles' );
			wp_enqueue_style( 'builder-admin-styles' );
			
			// Ensure global variables are set for admin-header.php
			global $title, $parent_file, $submenu_file;
			$title = $post->post_title ?: __('Edit Page');
			
			require_once ABSPATH . 'wp-admin/admin-header.php';
			
			// Print post-lock dialog and required fields
			Post_Lock_Handler::print_post_lock_dialog();
	
			uf_form();
	
			require_once ABSPATH . 'wp-admin/admin-footer.php';
			exit;
		}
	}

	/**
	 * AJAX handler to migrate post content to builder.
	 *
	 * @since 1.0
	 */
	public function ajax_migrate_post_content() {
		// Verify nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ultimate_builder_preview' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		// Check if user has permission
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( 'Insufficient permissions' );
		}

		// Get post ID
		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		if ( ! $post_id ) {
			wp_send_json_error( 'Invalid post ID' );
		}

		// Clear the post content
		$result = wp_update_post( array(
			'ID'           => $post_id,
			'post_content' => ''
		) );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( $result->get_error_message() );
		}

		wp_send_json_success( array(
			'message' => __('Post content migrated successfully', 'mv23theme')
		) );
	}

	public function remove_plugins_support() {
		if ( 
			isset( $_GET['action'] )
			&& isset( $_GET['meta'] )
			&& $_GET['action'] === 'ultimate-builder' ) 
		{
			// Disable SEO analysis and remove meta boxes
			add_filter( 'wpseo_use_page_analysis', '__return_false' );
			add_action( 'add_meta_boxes', function() {
				$post_types = ['post', 'page'];
				foreach ($post_types as $type) {
                	remove_meta_box('wpseo_meta', $type, 'normal');
            	}
			}, 100000 );

			// Deregister Yoast SEO scripts to prevent conflicts
			wp_deregister_script( 'yoast-seo-post-edit-classic' );
			wp_register_script( 'yoast-seo-post-edit-classic', false, array(), $this->version );
		}
	}
}
