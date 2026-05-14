<?php
/**
 * Migration class for migrating from version 3.11.X to 3.12.0
 * Migrates the monolithic "typography_css_vars" option into four separate options:
 *   - typography_settings  → base typography CSS vars
 *   - headings_settings    → heading CSS vars
 *   - links_settings       → link decoration CSS vars
 * Also renames the legacy "containers_width" option to "containers_settings".
 */
namespace Core\Migrator\Migration;

use Core\Migrator\Core;

class Migrate_3_11_X_to_3_12_0 {

    private static $instance = null;

    /** Set to false to do a dry-run without writing to the database. */
    private $do_the_update = true;

    public static function getInstance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {}

    public function migrate() {
        add_action( 'theme_migrator_display',    array( $this, 'display' ) );
        add_action( 'admin_enqueue_scripts',     array( $this, 'enqueue_migrator_scripts' ) );
        add_action( 'wp_ajax_process_typography_css_vars_split', array( $this, 'ajax_process' ) );
    }

    public function display() { ?>
        <div class="wrap">
            <div class="theme-migrator">
                <h3>―――― Migrate 3.11.X to 3.12.0 – Split <code>typography_css_vars</code> into separate settings &amp; rename <code>containers_width</code></h3>
                ――――― <button class="theme-migrator__init-3-12-0 button-primary" data-status="initial">
                    <span><i class="dashicons dashicons-migrate uf-button-icon"></i> INIT MIGRATION</span>
                    <span><i class="dashicons dashicons-admin-generic uf-button-icon"></i> PROCESSING</span>
                    <span><i class="dashicons dashicons-saved uf-button-icon"></i> MIGRATION COMPLETE</span>
                    <span><i class="dashicons dashicons-warning uf-button-icon"></i> MIGRATION FAILED</span>
                </button>
            </div>
        </div>
    <?php }

    public function enqueue_migrator_scripts( $hook ) {
        if ( 'admin_page_theme-migrator' !== $hook ) return;

        $slug = Core::getInstance()->get_slug();
        wp_enqueue_script(
            $slug . '-init-3-12-0',
            THEME_MIGRATOR_PATH . '/scripts/migrate-3-11-x-to-3-12-0.js',
            array( $slug . '-scripts' ),
            '1.0',
            true
        );
    }

    public function ajax_process() {
        check_ajax_referer( 'process_page_data_nonce', 'nonce' );

        $control = array(
            'old_data' => array(),
            'updates'  => array(),
            'skipped'  => array(),
        );

        // ── Read old option ────────────────────────────────────────────────────
        $typography_css_vars = get_option( 'typography_css_vars', null );
        $control['old_data']['typography_css_vars'] = $typography_css_vars;

        if ( $typography_css_vars === null || ! is_array( $typography_css_vars ) ) {
            wp_send_json_success( array(
                'complete' => true,
                'message'  => 'typography_css_vars not found – nothing to migrate.',
                'control'  => $control,
            ) );
            return;
        }

        // ── Key mapping ────────────────────────────────────────────────────────

        $typography_keys = array(
            'base_font_size',
            '--global-line-height',
            '--normal-font-weight',
            '--bold-font-weight',
            '--components-spacing',
        );

        $headings_keys = array(
            '--headings-font-weight',
            '--headings-line-height',
            '--heading-h1',
            '--heading-h1-line-height',
            '--heading-h2',
            '--heading-h2-line-height',
            '--heading-h3',
            '--heading-h3-line-height',
            '--heading-h4',
            '--heading-h4-line-height',
            '--heading-h5',
            '--heading-h5-line-height',
            '--heading-h6',
            '--heading-h6-line-height',
        );

        $links_keys = array(
            '--links-decoration',
            '--links-hover-decoration',
            '--links-decoration-thickness',
            '--links-decoration-offset',
        );

        // ── Build new settings arrays ──────────────────────────────────────────

        $typography_settings = array();
        foreach ( $typography_keys as $key ) {
            if ( array_key_exists( $key, $typography_css_vars ) ) {
                $typography_settings[ $key ] = $typography_css_vars[ $key ];
            }
        }

        $headings_settings = array();
        foreach ( $headings_keys as $key ) {
            if ( array_key_exists( $key, $typography_css_vars ) ) {
                $headings_settings[ $key ] = $typography_css_vars[ $key ];
            }
        }

        $links_settings = array();
        foreach ( $links_keys as $key ) {
            if ( array_key_exists( $key, $typography_css_vars ) ) {
                $links_settings[ $key ] = $typography_css_vars[ $key ];
            }
        }

        $control['updates']['typography_settings'] = $typography_settings;
        $control['updates']['headings_settings']   = $headings_settings;
        $control['updates']['links_settings']      = $links_settings;

        // ── containers_width → containers_settings ────────────────────────────
        $containers_width = get_option( 'containers_width', null );
        $control['old_data']['containers_width'] = $containers_width;

        if ( $containers_width !== null ) {
            // tweak the old data structure: rename 'width' → 'max_width' inside each repeater item
            foreach ( $containers_width as &$item ) {
                if ( isset( $item['width'] ) ) {
                    $item['max_width'] = $item['width'];
                    $item['width'] = ''; // we want to keep the field but just clear its value, since the new structure has both width and max_width fields
                }
            }
            unset( $item );

            $control['updates']['containers_settings'] = $containers_width;
        }

        // ── Persist ───────────────────────────────────────────────────────────
        if ( $this->do_the_update ) {
            update_option( 'typography_settings', $typography_settings, true );
            update_option( 'headings_settings',   $headings_settings,   true );
            update_option( 'links_settings',      $links_settings,      true );

            if ( $containers_width !== null ) {
                $existing_containers = get_option( 'containers_settings' );
                if ( ! $existing_containers ) {
                    update_option( 'containers_settings', $containers_width, true );
                } else {
                    $control['skipped'][] = 'containers_settings (already set – containers_width was NOT copied over)';
                }
                delete_option( 'containers_width' );
            }

            delete_option( 'typography_css_vars' );
        }

        wp_send_json_success( array(
            'complete' => true,
            'control'  => $control,
        ) );
    }
}
