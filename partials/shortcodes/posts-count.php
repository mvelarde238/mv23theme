<?php
/**
 * Shortcode: [posts_count slug="post_type_slug"]
 * Devuelve el número de posts publicados de un post type dado.
 */

if ( ! function_exists( 'posts_count_shortcode' ) ) {
	function posts_count_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'slug'   => 'post',
			'class'  => 'posts-count',
			'before' => '',
			'after'  => '',
		), $atts, 'posts_count' );

		$post_type = sanitize_key( $atts['slug'] );

		if ( ! post_type_exists( $post_type ) ) {
			return '';
		}

		$counts    = wp_count_posts( $post_type );
		$published = isset( $counts->publish ) ? intval( $counts->publish ) : 0;

		$output  = '<span class="' . esc_attr( $atts['class'] ) . '">';
		if ( $atts['before'] ) {
			$output .= esc_html( $atts['before'] );
		}
		$output .= '(' . number_format_i18n( $published ) . ')';
		if ( $atts['after'] ) {
			$output .= esc_html( $atts['after'] );
		}
		$output .= '</span>';

		return $output;
	}
	add_shortcode( 'posts_count', 'posts_count_shortcode' );
}
