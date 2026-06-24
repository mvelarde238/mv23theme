<?php
namespace Core\Utils;

class Helpers{
    public static function hexToRgb($hex, $alpha = false) {
        $hex      = str_replace('#', '', $hex);
        $length   = strlen($hex);
        $rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
        $rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
        $rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
        $values = $rgb['r'].','.$rgb['g'].','.$rgb['b'];
        if ( $alpha ) {
           $float_alpha = ($alpha / 100);
           $values .= ','.$float_alpha;
        }
        return $values;
    }

    public static function get_main_taxonomy( $post_type ) {
        $main_taxonomy_list = apply_filters('filter_main_taxonomy_list', array(
            'post' => 'category',
            'product' => 'product_cat'
        ));

        if( isset($main_taxonomy_list[$post_type]) ){
            return $main_taxonomy_list[$post_type];
        } else {
            if( taxonomy_exists($post_type.'-cat') ){
                return $post_type.'-cat';
            }
            if( taxonomy_exists($post_type.'_cat') ){
                return $post_type.'_cat';
            }
        }

        return false;
    }

    public static function get_secondary_taxonomy( $post_type ) {
        $secondary_taxonomy_list = apply_filters('filter_secondary_taxonomy_list', array(
            'post' => 'post_tag',
            'product' => 'product_tag'
        ));

        if( isset($secondary_taxonomy_list[$post_type]) ){
            return $secondary_taxonomy_list[$post_type];
        } else {
            if( taxonomy_exists($post_type.'-tag') ){
                return $post_type.'-tag';
            }
            if( taxonomy_exists($post_type.'_tag') ){
                return $post_type.'_tag';
            }
        }

        return false;
    }

    /**
	 * Return an array of term names for a given post and taxonomy.
	 * Always returns an array (empty if no terms or on error).
	 */
	public static function get_terms_names( $post_id, $taxonomy ) {
		if ( ! $taxonomy ) {
			return array();
		}
		$terms = get_the_terms( $post_id, $taxonomy );
		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return array();
		}
		return wp_list_pluck( $terms, 'name' );
	}
}