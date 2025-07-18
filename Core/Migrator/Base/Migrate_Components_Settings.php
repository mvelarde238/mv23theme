<?php
namespace Core\Migrator\Base;

use Core\Migrator\Core;

abstract class Migrate_Components_Settings{

    private $batch_size;
    private $do_the_update;
    private $title;
    private $slug;

    public function __construct( $batch_size = 3, $do_the_update = false, $title = '', $slug = '' ) {
        $this->batch_size = $batch_size;
        $this->do_the_update = $do_the_update;
        $this->title = $title;
        $this->slug = $slug;
    }

    /* abstract */ public function migrate(){
        add_action( 'theme_migrator_display', array( $this, 'display') );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_migrator_scripts') );
        add_action( 'wp_ajax_process_'.str_replace('-', '_', $this->slug), array($this, 'ajax_process_page_data') );
        add_action( 'wp_ajax_after_'.str_replace('-', '_', $this->slug), array($this, 'ajax_after_data_migration') );
    }

    /* abstract */ public function display(){
        ?>
        <div class="wrap">
            <div class="theme-migrator">
                <h3><?php echo esc_html( $this->title ); ?></h3>
                ――――― <button class="theme-migrator__init-<?php echo esc_attr( $this->slug ); ?> button-primary" data-status="initial">
                    <span><i class="dashicons dashicons-migrate uf-button-icon"></i> INIT MIGRATION</span>
                    <span><i class="dashicons dashicons-admin-generic uf-button-icon"></i> PROCESSING</span>
                    <span><i class="dashicons dashicons-saved uf-button-icon"></i> MIGRATION COMPLETE</span>
                    <span><i class="dashicons dashicons-warning uf-button-icon"></i> MIGRATION FAILED</span>
                </button>
            </div>
        </div>
        <?php
    }

    /* abstract */ public function enqueue_migrator_scripts( $hook ) {
        if ( 'admin_page_theme-migrator' != $hook ) return;

        $slug = Core::getInstance()->get_slug();

        wp_enqueue_script($slug.'-'.$this->slug, THEME_MIGRATOR_PATH . '/scripts/'.$this->slug.'.js', array('jquery'), '1.0', true);
    }

    /* abstract */ public function ajax_process_page_data() {
        check_ajax_referer('process_page_data_nonce', 'nonce');
        
        $batch_size = $this->batch_size;
        $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    
        // Procesar un lote de datos
        $processed = $this->process_page_data_batch($batch_size, $offset);
    
        wp_send_json_success(array(
            'control' => $processed['control'],
            'processed' => $processed['quantity'],
            'offset' => $offset + $processed['quantity'],
            'complete' => $processed['quantity'] < $batch_size // Completo si se procesaron menos de batch_size elementos
        ));
    }

    /* abstract */ public function process_page_data_batch($batch_size, $offset) {
        global $wpdb;
    
        // Obtener un lote de páginas a procesar
        $pages = $wpdb->get_results($wpdb->prepare(
            "SELECT pm.meta_id, pm.post_id, pm.meta_key, pm.meta_value, p.post_type
            FROM {$wpdb->postmeta} pm
            JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            WHERE pm.meta_key IN ('page_modules','components','offcanvas_element_content', 'blocks_layout','page_header_settings')
            AND p.post_type != 'revision'
            LIMIT %d OFFSET %d",
            $batch_size,
            $offset
        ));

        $general_control = array();
        $do_the_update = $this->do_the_update;
    
        foreach ($pages as $page) {
            $old_data = maybe_unserialize($page->meta_value);

            $page_control = array(
                'title' => get_the_title( $page->post_id ),
                'id' => $page->post_id,
                'posttype' => $page->post_type,
                'meta' => $page->meta_key,
                'old_data' => $old_data
            );

            if( $page->meta_key == 'page_modules' ){
                $new_page_modules_data = $this->migrate_page_modules_data($old_data);
                if($do_the_update) update_post_meta( $page->post_id, 'page_modules', $new_page_modules_data );
                $page_control['new_data'] = $new_page_modules_data;
            }
            // reusable_section
            if( $page->meta_key == 'components' ){
                $new_reusable_section_data = $this->migrate_seccion_reusable_components_data($old_data);
                if($do_the_update) update_post_meta( $page->post_id, 'components', $new_reusable_section_data );
                $page_control['new_data'] = $new_reusable_section_data;
            }
            // OCE
            if( $page->meta_key == 'offcanvas_element_content' ){
                $new_blocks_layout_data = $this->migrate_content_layout_data($old_data);
                if($do_the_update) update_post_meta($page->post_id, 'offcanvas_element_content', $new_blocks_layout_data);
                $page_control['new_data'] = $new_blocks_layout_data;
            }
            // PAGES WITH BLOCKS LAYOUT (HAVE columns COMP)
            if( $page->meta_key == 'blocks_layout' ){
                $new_blocks_layout_data = $this->migrate_content_layout_data($old_data);
                if($do_the_update) update_post_meta($page->post_id, 'blocks_layout', $new_blocks_layout_data);
                $page_control['new_data'] = $new_blocks_layout_data;
            }

            if( $page->meta_key == 'page_header_settings' ){
                $new_page_header_settings_data = $this->migrate_settings_data($old_data);
                if($do_the_update) update_post_meta($page->post_id, 'page_header_settings', $new_page_header_settings_data);
                $page_control['new_data'] = $new_page_header_settings_data;
            }

            $general_control[] = $page_control;
        }
    
        return array(
            'quantity' => count($pages), // Retorna el número de páginas procesadas
            'control' => $general_control
        );
    }
    
    /* abstract */ public function migrate_page_modules_data($page_modules_data) {
        $new_page_modules_data = array();

        foreach ($page_modules_data as $module) {
            if( $module['__type'] == 'page_module' ){

                // Migrate the inner components of the page module
                if( isset($module['components']) && is_array($module['components']) && !empty($module['components']) ){

                    $migrated_components = array();

                    foreach ($module['components'] as $component) {
                        $migrated = $this->migrate_component_data( $component );
                        $migrated_components[] = $migrated;
                    }
                    
                    $module['components'] = $migrated_components;
                }
            }

            array_push( $new_page_modules_data, $module );
        }

        return $new_page_modules_data;
    }

    /* abstract */ public function migrate_seccion_reusable_components_data($old_components_data){
        $new_reusable_section_data = array();

        if( is_array($old_components_data) && !empty($old_components_data) ){

            $migrated_components = array();

            foreach ($old_components_data as $component) {
                $migrated = $this->migrate_component_data( $component );
                $migrated_components[] = $migrated;
            }
                    
            $new_reusable_section_data = $migrated_components;
        }

        return $new_reusable_section_data;
    }

    /* abstract */ public function migrate_content_layout_data($old_data) {
        $new_data = array();

        $row_count = 0;
        foreach ($old_data as $row ) {
            $new_data[$row_count] = array();
            foreach ($row as $layout_comp) {
                $new_data[$row_count][] = $this->migrate_component_data( $layout_comp );
            }
            $row_count++;
        }

        return $new_data;
    }

    /* abstract */ public function loop_inner_components( $component ){
        // FIRST MIGRATE THE INNER WRAPPERS COMPONENTS
        $has_inner_components = $this->has_inner_components( $component['__type'] );
        if( $has_inner_components['check'] ) {
            if( is_array($has_inner_components['where']) ){
                foreach ($has_inner_components['where'] as $meta) {
                    $migrated_components = array();
                    foreach ($component[$meta] as $inner_component ) {
                        $migrated = $this->migrate_component_data( $inner_component );
                        $migrated_components[] = $migrated;
                    }
                    $component[$meta] = $migrated_components;
                }
            } else {
                if( $has_inner_components['where'] == 'in-row-content' ){
                    $column_count = 0;
                    foreach ($component['row']['content'] as $column) {
                        $migrated_components = array();
                        foreach ($column as $inner_component ) {
                            $migrated = $this->migrate_component_data( $inner_component );
                            $migrated_components[] = $migrated;
                        }
                        $component['row']['content'][$column_count] = $migrated_components;
                        $column_count++;
                    }
                }
                if( $has_inner_components['where'] == 'in-accordion-content' ){
                    $item_count = 0;
                    foreach ($component['accordion'] as $item) {
                        $migrated = $this->migrate_content_layout_data( $item['blocks_layout'] );
                        $component['accordion'][$item_count]['blocks_layout'] = $migrated;
                        $item_count++;
                    }
                }
            }
        }
        // END

        return $component;
    }

    /* abstract */ public function migrate_component_data( $component ){
        $new_component = $this->loop_inner_components( $component );

        if( $component['__type'] == 'some-component' ){
            // unset old settings
            unset( $new_component['an_old_setting'] );
        }

        $new_component['settings'] = $this->migrate_settings_data( $component['settings'] );

        return $new_component;
    }

    /* abstract */ public function migrate_settings_data( $old_settings ){
        $new_settings = $old_settings;

        // process the old settings and migrate them to the new format

        return $new_settings;
    }

    /* abstract */ public function has_inner_components( $component_type ){
        $has_inner_components = apply_filters('has_inner_components_filter', array(
            // 'row' => array( 'column_1', 'column_2', 'column_3', 'column_4' ), // old way
            // 'inner_row' => array( 'column_1', 'column_2', 'column_3', 'column_4' ) // old way
            'row' => 'in-row-content',
            'inner_row' => 'in-row-content',
            'accordion' => 'in-accordion-content'
        ));

        $check = isset( $has_inner_components[$component_type] );
        $where = ($check) ? $has_inner_components[$component_type] : array();

        return array(
            'check' => $check,
            'where' => $where
        );
    }

    /* abstract */ public function ajax_after_data_migration() {
        check_ajax_referer('process_page_data_nonce', 'nonce');

        // ...

        wp_send_json_success(array(
            'complete' => true
        ));
    }
}