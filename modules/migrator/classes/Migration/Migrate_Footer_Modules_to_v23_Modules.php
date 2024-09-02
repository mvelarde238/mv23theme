<?php
namespace Theme_Migrator\Migration;

class Migrate_Footer_Modules_to_v23_Modules{
    public function __construct(){}

    public function migrate(){
        $main_footer_id = $this->manage_footer_option_to_post( 'footer_modules' );
        // asignar como theme footer
        if($main_footer_id) add_option('theme_footer_post', 'post_'.$main_footer_id);

        if (IS_MULTILANGUAGE) {
            $lang_footers = array(
                'es' => $main_footer_id,
                'en' => null,
                'pt' => null
            );

            foreach ($lang_footers as $lang => $footer_id) {
                if($lang != 'es'){
                    $lang_footer_id = $this->manage_footer_option_to_post( 'footer_modules_'.$lang, 'Pie de página ('.$lang.')' );	                
                    if ($lang_footer_id && function_exists('pll_set_post_language')) {
                        pll_set_post_language($lang_footer_id, $lang);
                        $lang_footers[$lang] = $lang_footer_id;
                    }
                } else {
                    pll_set_post_language($main_footer_id, $lang);
                }
            }

            if (function_exists('pll_save_post_translations')) pll_save_post_translations($lang_footers);
        }
    }

    private function manage_footer_option_to_post( $footer_option_name, $post_title = 'Pie de página' ){
        $old_footer_meta = get_option( $footer_option_name );
        $post_id = null;

        if( $old_footer_meta ){
            $post_data = array(
                'post_title'    => $post_title,
                'post_status'   => 'publish',
                'post_type'     => 'footer',
            );
        
            $post_id = wp_insert_post($post_data);
        
            if (!is_wp_error($post_id)) {
                add_post_meta( $post_id, 'v23_modulos', $old_footer_meta );
                delete_option( $footer_option_name );
            }
        }
        return $post_id;
    }
}