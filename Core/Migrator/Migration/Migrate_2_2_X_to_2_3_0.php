<?php
namespace Core\Migrator\Migration;

use Core\Migrator\Core;

class Migrate_2_2_X_to_2_3_0{
    private static $instance = null;

    private $do_the_update = true;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct(){}

    public function migrate(){
        add_action( 'theme_migrator_display', array( $this, 'display') );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_migrator_scripts') );
        add_action( 'wp_ajax_process_typography_options', array($this, 'ajax_process_typography_options') );
    }

    public function display(){ ?>
        <div class="wrap">
            <div class="theme-migrator">
                <h3>―――― Migrate 2.2.X to 2.3.0 - Typography css properties implementation ( For version =< 2.2.5 )</h3>
                ――――― <button class="theme-migrator__init-2-3-0 button-primary" data-status="initial">
                    <span><i class="dashicons dashicons-migrate uf-button-icon"></i> INIT MIGRATION</span>
                    <span><i class="dashicons dashicons-admin-generic uf-button-icon"></i> PROCESSING</span>
                    <span><i class="dashicons dashicons-saved uf-button-icon"></i> MIGRATION COMPLETE</span>
                    <span><i class="dashicons dashicons-warning uf-button-icon"></i> MIGRATION FAILED</span>
                </button>
            </div>
        </div>
        <?php
    }

    public function enqueue_migrator_scripts( $hook ) {
        if ( 'admin_page_theme-migrator' != $hook ) return;

        $slug = Core::getInstance()->get_slug();

        wp_enqueue_script($slug.'-init-2-3-0', THEME_MIGRATOR_PATH . '/scripts/migrate-2-2-X-to-2-3-0.js', array('jquery'), '1.0', true);
    }

    public function ajax_process_typography_options() {
        check_ajax_referer('process_page_data_nonce', 'nonce');

        $control = [ 'old_data' => [], 'updates' => [] ];

        $old_options = ['paragraph','h1_heading','h2_heading','h3_heading','h4_heading','h5_heading','h6_heading','bold'];
        $typography_css_vars = [
            'base_font_size' => '',
            '--global-line-height' => '',
            '--text-blocks-spacing' => '',
            '--normal-font-weight' => '',
            '--bold-font-weight' => '',
            '--headings-font-weight' => '',
            '--headings-line-height' => '',
            '--heading-h1' => '',
            '--heading-h1-line-height' => '',
            '--heading-h2' => '',
            '--heading-h2-line-height' => '',
            '--heading-h3' => '',
            '--heading-h3-line-height' => '',
            '--heading-h4' => '',
            '--heading-h4-line-height' => '',
            '--heading-h5' => '',
            '--heading-h5-line-height' => '',
            '--heading-h6' => '',
            '--heading-h6-line-height' => '',
            '--text-xxs' => '',
            '--text-xs' => '',
            '--text-s' => '',
            '--text-m' => '',
            '--text-l' => '',
            '--text-xl' => '',
            '--text-xxl' => '',
            '--text-xxxl' => ''
        ];

        foreach ($old_options as $option) {
            $option_value = get_option( $option );
            $control['old_data'][$option] = $option_value;

            if( $option === 'paragraph' ){
                if( $option_value['font_size'] ) $typography_css_vars['base_font_size'] = $option_value['font_size'];
                if( $option_value['line_height'] ) $typography_css_vars['--global-line-height'] = $option_value['line_height'];
                if( $option_value['font_weight'] ) $typography_css_vars['--normal-font-weight'] = $option_value['font_weight'];

            }else if( $option === 'bold' ){
                if( $option_value['font_weight'] ) $typography_css_vars['--bold-font-weight'] = $option_value['font_weight'];

            } else {
                // is a heading option
                $heading_key = str_replace( '_heading','', $option);
                if( $option_value['font_size'] ) $typography_css_vars['--heading-'.$heading_key] = $option_value['font_size'];
                if( $option_value['line_height'] ) $typography_css_vars['--heading-'.$heading_key.'-line-height'] = $option_value['line_height'];
                if( $option_value['font_weight'] && $heading_key == 'h1' ) $typography_css_vars['--headings-font-weight'] = $option_value['font_weight'];
            }
        }

        $control['updates']['typography_css_vars'] = $typography_css_vars;

        if( $this->do_the_update ){
            update_option('typography_css_vars', $typography_css_vars, true);

            foreach ($old_options as $option) {
                delete_option( $option );
            }
        }
    
        wp_send_json_success(array(
            'complete' => true,
            'control' => $control
        ));
    }
}