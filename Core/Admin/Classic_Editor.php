<?php
namespace Core\Admin;

/**
 * Light Classic Editor integration for mv23theme.
 *
 * Replaces the "classic-editor-and-classic-widgets" plugin for sites that
 * only need:
 *   - Gutenberg disabled globally (all post types)
 *   - Classic Widgets (Appearance > Widgets)
 *   - (Optional) remove Gutenberg block CSS from the frontend
 *
 * Toggled via Theme Options → Global Settings → Classic Editor.
 *
 * When the standalone plugin is active (GRIM_CEW\Gutenberg class exists)
 * this class does nothing to avoid double-hooking.
 */
class Classic_Editor {

    public function __construct() {
        // Defer to the standalone plugin if it is active.
        if ( class_exists( 'GRIM_CEW\Gutenberg' ) ) {
            return;
        }

        if ( ! get_option( 'activate_classic_editor', false ) ) {
            return;
        }

        // ---------- Disable block editor for every post type ----------
        add_filter( 'use_block_editor_for_post_type', '__return_false', 100 );

        // Remove the "Try Gutenberg" dashboard panel (WP < 5.0 remnant).
        remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel' );

        // ---------- Classic Widgets ----------
        // gutenberg_use_widgets_block_editor  → Gutenberg plugin (< WP 5.8)
        // use_widgets_block_editor            → Core WP 5.8+
        add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
        add_filter( 'use_widgets_block_editor', '__return_false' );

        // ---------- Optional: strip block CSS from frontend ----------
        if ( get_option( 'disable_gutenberg_frontend_styles', false ) ) {
            add_action( 'wp_enqueue_scripts', array( $this, 'disable_block_styles' ) );
        }
    }

    /**
     * Dequeue Gutenberg / block-editor stylesheets from the frontend.
     */
    public function disable_block_styles() {
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
        wp_dequeue_style( 'global-styles' ); // WP 5.9+ full-site editing stylesheet

        // WooCommerce block styles
        if ( class_exists( 'woocommerce' ) ) {
            wp_dequeue_style( 'wc-blocks-style' );
            wp_dequeue_style( 'wc-all-blocks-style' );
            wp_dequeue_style( 'wc-blocks-vendors-style' );
            wp_deregister_style( 'wc-blocks-style' );
            wp_deregister_style( 'wc-all-blocks-style' );
            wp_deregister_style( 'wc-blocks-vendors-style' );
        }
    }
}
