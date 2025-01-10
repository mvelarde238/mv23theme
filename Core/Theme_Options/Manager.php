<?php
namespace Core\Theme_Options;

define ('THEME_OPTIONS_PATH', get_template_directory_uri() . '/Core/Theme_Options');

class Manager {
    private static $instance = null;

    private function __construct() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function register_metabox() {
        add_submenu_page(
            'theme-options',
            __('Export/Import','mv23theme'),
            __('Export/Import Options','mv23theme'),
            'manage_options',              
            'theme-options-manager', 
            array($this, 'render_metabox'),
            60                            
        );
    }

    public function render_metabox() {
        // Generar nonce para las solicitudes AJAX
        $nonce = wp_create_nonce('theme_options_manager_nonce');
        $site_title = get_bloginfo( 'name' );
        ?>
        <div class="wrap">
            <h1>Theme Options Manager</h1>
            <div id="status-message"></div>
            <form id="export-import-form" method="post">
                <h2>Export Theme Options</h2>
                <button id="export-options" class="button button-primary">Export Options</button>
                <br>
                <br>
                <h2>Import Theme Options</h2>
                <input type="file" id="import-file" accept=".json" />
                <br>
                <button id="import-options" class="button button-secondary">Import Options</button>
            </form>
        </div>
        <script type="text/javascript">
            window.theme_options_manager_nonce = '<?php echo $nonce; ?>';
            window.project_name = '<?php echo $site_title; ?>';
        </script>
        <?php
    }

    public function enqueue_script() {
        wp_enqueue_script(
            'theme-options-manager',
            THEME_OPTIONS_PATH . '/scripts/theme-options-manager.js',
            array('jquery'),
            '1.0',
            true
        );
    }

    // Export options using AJAX
    public function export_options_via_ajax() {
        check_ajax_referer('theme_options_manager_nonce', 'nonce');

        $options = array();
        $options_list = array('primary_color','secondary_color','font_color','headings_color','colorpicker_palette','light_primary_color_percentage','lighter_primary_color_percentage','dark_primary_color_percentage','light_secondary_color_percentage','lighter_secondary_color_percentage','dark_secondary_color_percentage','fonts','paragraph','h1_heading','h2_heading','h3_heading','h4_heading','h5_heading','h6_heading','static_header_logo_height','static_header_bgc','static_header_color_scheme','sticky_header_logo_height','sticky_header_bgc','sticky_header_color_scheme','containers_width','single_pages_settings');

        foreach ($options_list as $opttion_name) {
            $options[$opttion_name] = get_option($opttion_name);
        }

        wp_send_json_success(json_encode($options));
    }

    // Import options using AJAX
    public function import_options_via_ajax() {
        check_ajax_referer('theme_options_manager_nonce', 'nonce');

        if (isset($_POST['options'])) {
            $options = json_decode(stripslashes($_POST['options']), true);

            if (is_array($options)) {
                foreach ($options as $key => $value) {
                    update_option($key, $value);
                }
                wp_send_json_success('Options imported successfully.');
            }
        }
        wp_send_json_error('Failed to import options.');
    }
}