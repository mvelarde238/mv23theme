<?php 
namespace Core\Frontend;

class Taxonomy_Breadcrumbs{
    public function __construct(){}

    static function get_terms_ids($taxonomy = 'category'){
        global $post;

        // Obtener términos del post en la taxonomía dada
        $terms = get_the_terms($post->ID, $taxonomy);

        // Reorganizar términos en un array asociativo basado en IDs
        $terms_by_id = array();

        if ( !is_wp_error($terms) && is_array($terms) && count($terms)) {
            foreach ($terms as $term) {
                $terms_by_id[$term->term_id] = $term;
            }
        }

        return $terms_by_id;
    }

    static function get_root_term($taxonomy = 'category'){
        $terms_by_id = self::get_terms_ids($taxonomy);

        // Buscar el término de nivel más alto (sin padre)
        $root_terms = array_filter($terms_by_id, function ($term) {
            return $term->parent === 0;
        });

        if (empty($root_terms)) {
            return;
        }

        $root_term = reset($root_terms);
        return $root_term;
    }

    static function build_structure( $terms_breadcrumbs ){
        // Construir breadcrumb con enlaces
        $breadcrumb_links = array_map(function ($term) {
            return '<a href="' . get_term_link($term) . '">' . $term->name . '</a>';
        }, $terms_breadcrumbs);
    
        return implode(' <i class="bi bi-arrow-right-circle"></i> ', $breadcrumb_links);
    }

    static function generate_single_breadcrumbs($taxonomy = 'category') {
        $terms_by_id = self::get_terms_ids($taxonomy);
        if (empty($terms_by_id)) {
            return;
        }
    
        // Elegir el primer término raíz disponible
        $root_term = self::get_root_term($taxonomy);
        $terms_breadcrumbs = [$root_term];
    
        // Recorrer jerárquicamente hasta el último hijo más profundo
        $current_term = $root_term;
        while (true) {
            $child_term = null;
    
            foreach ($terms_by_id as $term) {
                if ($term->parent === $current_term->term_id) {
                    $child_term = $term;
                    break;
                }
            }
    
            if ($child_term) {
                $terms_breadcrumbs[] = $child_term;
                $current_term = $child_term;
            } else {
                break;
            }
        }
    
        echo self::build_structure( $terms_breadcrumbs );
    }

    static function generate_archive_breadcrumbs($taxonomy = 'category') {
        $current_term = get_queried_object();
        $terms_breadcrumbs = array();

        while ($current_term) {
            $terms_breadcrumbs[] = $current_term;
            $current_term = $current_term->parent ? get_term($current_term->parent, $taxonomy) : null;
        }

        echo self::build_structure( array_reverse($terms_breadcrumbs) );
    }

    static function display($taxonomy = 'category'){
        if (is_singular()){
            return self::generate_single_breadcrumbs($taxonomy);
        } else {
            return self::generate_archive_breadcrumbs($taxonomy);
        }
    }
}