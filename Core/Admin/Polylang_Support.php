<?php
namespace Core\Admin;

class Polylang_Support {

    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Polylang_Support();
        }
        return self::$instance;
    }

    private function __construct(){}

    public function add_posttypes_to_pll($post_types, $is_translatable){
        $builtin_posttypes = array( 'postcard', 'header', 'footer', 'single_template', 'archive_template', 'reusable_section', 'megamenu' );

        if ($is_translatable) {
            // hides cpt from the list of custom post types in Polylang settings
            foreach ($builtin_posttypes as $cpt) {
                unset($post_types[$cpt]);
            }
        } else {
            // enables language and translation management for cpt
            foreach ($builtin_posttypes as $cpt) {
                $post_types[$cpt] = $cpt;
            }
        }
        return $post_types;
    }
}