<?php
/**
 * TODO: Handlebars - parsing features to implement
 *
 * [x] 0. Contexto estructurado + dot-notation  →  get_context() retorna array anidado; resolve_path() navega rutas.
 *         Filter hook: 'ultimate_builder_handlebars_context'  |  JS usa BUILDER_GLOBALS.context
 *
 * [x] 1. Filtros/modificadores  →  {{post.title|uppercase}}, {{post.meta.precio|number_format}}, {{post.meta.desc|truncate:120}}
 *         Filtros escalares: uppercase, lowercase, capitalize, capitalize_words, truncate:N, number_format, slug, nl2br, spans[:class], join.
 *         Filtros array-aware: spans[:class[:sep]]  →  cada item en <span>; join[:sep]  →  implode con separador arbitrario.
 *
 * [x] 2. Fallback / valor por defecto  →  {{post.meta.subtitulo ?? post.title}}, {{post.meta.tel ?? 'Sin teléfono'}}
 *         Soporta ruta ?? ruta y ruta ?? 'literal'. Se evalúa en el mismo pase que los tokens simples.
 *
 * [x] 3. Condicionales simples  →  {{#if post.meta.precio}}Precio: {{post.meta.precio}}{{/if}}
 *         Soporta {{#if path}}...{{else}}...{{/if}} y anidamiento mediante iteración.
 * 
 * [x] 4. Condicionales con comparación  →  {{#if post.meta.precio >/==/<=/etc. 100}}Caro{{else}}Barato{{/if}}
 *
 * [ ] 5. Loops sobre post meta arrays  →  {{#each post.meta.galeria}}<img src="{{this.url}}">{{/each}}
 *         Útil con ACF/UF repeaters. Requiere parser más elaborado.
 */
namespace Ultimate_Fields\Ultimate_Builder;

use Core\Utils\Helpers;

class Handlebars{

	public static function get_context( $post_id = null ){
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		// If the current post is a single_template or postcard, resolve the connected post type,
		// and use a real post of that type as context source for meta fields.
		$context_post_id = $post_id;
		$resolve_post_types = array( 'single_template', 'postcard' );
		$post_type = get_post_type( $post_id );
		if ( in_array( $post_type, $resolve_post_types ) ) {
			$connected_posttype = get_post_meta( $post_id, 'connected_posttype', true );

			if ( $connected_posttype ) {
				// On the frontend, use the queried object (the real post being viewed).?
				// On Loops, use the queried object (the real post being iterated).?
				// In admin/builder, grab the most recent post of the connected type as sample.
				$queried = get_queried_object_id();
				if ( $queried && get_post_type( $queried ) === $connected_posttype ) {
					$context_post_id = $queried;
				} else {
					$sample = get_posts( array(
						'post_type'      => $connected_posttype,
						'posts_per_page' => 1,
						'post_status'    => 'publish',
						'orderby'        => 'rand',
						'fields'         => 'ids',
					) );
					if ( ! empty( $sample ) ) {
						$context_post_id = $sample[0];
					}
				}
			}
		}

		$post_type = get_post_type( $context_post_id );
		$no_thumbnail = get_stylesheet_directory_uri() . '/assets/images/nothumb.jpg';

		$post_excerpt = get_post_field( 'post_excerpt', $context_post_id );
		if ( empty( $post_excerpt ) ) {
			$post_content = get_post_field( 'post_content', $context_post_id );
			$post_excerpt = wp_trim_words( wp_strip_all_tags( $post_content ), 55, '...' );
		}

		$context = array(
			'post' => array(
				'id'        => $context_post_id,
				'title'      => get_the_title( $context_post_id ),
				'excerpt'    => $post_excerpt,
				'permalink'  => get_permalink( $context_post_id ),
				'posttype'   => $post_type,
				'thumbnail'  => get_the_post_thumbnail_url( $context_post_id, 'full' ) ?: $no_thumbnail,
				'date'       => get_the_date( '', $context_post_id ),
				'meta'       => array(),
				'taxonomies' => array(),
			),
			'site' => array(
				'title'   => get_bloginfo( 'name' ),
				'tagline' => get_bloginfo( 'description' ),
				'url'     => home_url( '/' ),
			),
			'current_year' => date( 'Y' ),
		);

		// Add post meta under post.meta.*
		$post_meta = get_post_meta( $context_post_id, '', true );
		if ( $post_meta ) {
			foreach ( $post_meta as $key => $value ) {
				if ( strpos( $key, '_' ) === 0 || strpos( $key, 'page_content' ) === 0 ) {
					continue;
				}
				// Skip single_template config keys (connected_*) if we fell back to the template itself
				if ( $context_post_id === $post_id && strpos( $key, 'connected_' ) === 0 ) {
					continue;
				}
				$context['post']['meta'][ $key ] = maybe_unserialize( $value[0] );
			}
		}

		// Add taxonomies under post.taxonomies.{taxonomy_name} as array of term names (always an array)
		$taxonomies = get_object_taxonomies( get_post_type( $context_post_id ) );
		foreach ( $taxonomies as $taxonomy ) {
			$context['post']['taxonomies'][ $taxonomy ] = Helpers::get_terms_names( $context_post_id, $taxonomy );
		}

		// Add main and secondary taxonomy terms under post.main/secondary_terms as array of term names (empty if no terms available)
		$main_taxonomy = Helpers::get_main_taxonomy( $post_type );
		$context['post']['main_terms'] = Helpers::get_terms_names( $context_post_id, $main_taxonomy );

		$secondary_taxonomy = Helpers::get_secondary_taxonomy( $post_type );
		$context['post']['secondary_terms'] = Helpers::get_terms_names( $context_post_id, $secondary_taxonomy );

		/**
		 * Allows child themes or plugins to register additional context data.
		 *
		 * @param array $context Nested associative array of context data.
		 *
		 * Example (in child theme's functions.php):
		 *
		 * add_filter( 'filter_ultimate_builder_handlebars_context', function( $context ) {
		 *     $context['empresa'] = array(
		 *         'telefono' => get_option('empresa_tel'),
		 *         'email'    => get_option('empresa_email'),
		 *     );
		 *     return $context;
		 * });
		 * // then in template: {{empresa.telefono}}
		 */
		return apply_filters( 'filter_ultimate_builder_handlebars_context', $context );
	}

	/**
	 * Resolves a dot-notation path and returns the raw PHP value (null if not found).
	 */
	private static function resolve_path_raw( $path, $context ) {
		$keys  = explode( '.', $path );
		$value = $context;
		foreach ( $keys as $key ) {
			if ( is_array( $value ) && array_key_exists( $key, $value ) ) {
				$value = $value[ $key ];
			} else {
				return null;
			}
		}
		return $value;
	}

	/**
	 * Returns true if the value is considered non-empty (truthy).
	 */
	private static function is_truthy( $value ) {
		if ( $value === null || $value === false || $value === '' || $value === 0 || $value === '0' ) {
			return false;
		}
		if ( is_array( $value ) && empty( $value ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Applies a named filter to a string value.
	 * Supported: uppercase, lowercase, capitalize, capitalize_words, truncate:N, 
	 * number_format[:decimals[:dec_point[:thousands_sep]]], slug, nl2br.
	 */
	private static function apply_filter( $value, $filter_expr ) {
		$colon = strpos( $filter_expr, ':' );
		$name  = $colon !== false ? substr( $filter_expr, 0, $colon ) : $filter_expr;
		$arg   = $colon !== false ? substr( $filter_expr, $colon + 1 ) : null;

		switch ( trim( $name ) ) {
			case 'uppercase':
				return mb_strtoupper( $value );
			case 'lowercase':
				return mb_strtolower( $value );
			case 'capitalize':
				// Capitalize only the first character of the string
				if ( $value === '' ) return $value;
				$first = mb_substr( $value, 0, 1 );
				$rest  = mb_substr( $value, 1 );
				return mb_strtoupper( $first ) . $rest;
			case 'capitalize_words':
				// Capitalize the first letter of every word
				return mb_convert_case( $value, MB_CASE_TITLE );
			case 'truncate':
				$len = $arg !== null ? (int) $arg : 100;
				return mb_strlen( $value ) > $len ? mb_substr( $value, 0, $len ) . '...' : $value;
			case 'number_format':
				$parts    = $arg !== null ? explode( ':', $arg ) : array();
				$decimals = isset( $parts[0] ) && $parts[0] !== '' ? (int) $parts[0] : 0;
				$dec_sep  = isset( $parts[1] ) && $parts[1] !== '' ? $parts[1] : ',';
				$thou_sep = isset( $parts[2] ) && $parts[2] !== '' ? $parts[2] : '.';
				return number_format( (float) $value, $decimals, $dec_sep, $thou_sep );
			case 'slug':
				return sanitize_title( $value );
			case 'nl2br':
				return nl2br( $value );
			case 'spans':
				// spans[:class[:separator]] — scalar version wraps the value in a single <span>
				$args      = $arg !== null ? explode( ':', $arg, 2 ) : array();
				$css_class = isset( $args[0] ) && $args[0] !== '' ? $args[0] : null;
				$attr      = $css_class ? ' class="' . esc_attr( $css_class ) . '"' : '';
				return '<span' . $attr . '>' . esc_html( $value ) . '</span>';
			case 'join':
				// join on a scalar is a no-op (nothing to join)
				return $value;
			default:
				return $value;
		}
	}

	/**
	 * Resolves {{path|filter}} and {{path|filter:arg}} tokens.
	 */
	private static function resolve_with_filter( $token, $context ) {
		$pipe  = strpos( $token, '|' );
		$path  = trim( substr( $token, 0, $pipe ) );
		$filter_expr = trim( substr( $token, $pipe + 1 ) );

		$value = self::resolve_path_raw( $path, $context );
		if ( ! self::is_truthy( $value ) ) {
			return '';
		}

		// Array-aware filters: intercept before implode so each item stays separate.
		if ( is_array( $value ) ) {
			$colon       = strpos( $filter_expr, ':' );
			$filter_name = trim( $colon !== false ? substr( $filter_expr, 0, $colon ) : $filter_expr );
			$filter_args = $colon !== false ? substr( $filter_expr, $colon + 1 ) : null;

			if ( $filter_name === 'spans' ) {
				// spans[:class[:separator]]
				$args      = $filter_args !== null ? explode( ':', $filter_args, 2 ) : array();
				$css_class = isset( $args[0] ) && $args[0] !== '' ? $args[0] : null;
				$separator = isset( $args[1] ) ? $args[1] : '';
				$attr      = $css_class ? ' class="' . esc_attr( $css_class ) . '"' : '';
				$items     = array_filter( array_map( 'strval', $value ) );
				return implode( $separator, array_map(
					fn( $t ) => '<span' . $attr . '>' . esc_html( $t ) . '</span>',
					$items
				) );
			}

			if ( $filter_name === 'join' ) {
				// join[:separator]  — default separator is empty string (not ", ")
				$separator = $filter_args !== null ? $filter_args : '';
				return implode( $separator, array_filter( array_map( 'strval', $value ) ) );
			}

			$value = implode( ', ', array_filter( array_map( 'strval', $value ) ) );
		} else {
			$value = (string) $value;
		}
		return self::apply_filter( $value, $filter_expr );
	}

	/**
	 * Handles {{primary ?? fallback}} tokens.
	 * The fallback can be a quoted string literal or another dot-notation path.
	 */
	private static function resolve_fallback( $token, $context ) {
		$parts    = array_map( 'trim', explode( '??', $token, 2 ) );
		$primary  = $parts[0];
		$fallback = $parts[1];

		$value = self::resolve_path_raw( $primary, $context );
		if ( self::is_truthy( $value ) ) {
			if ( is_scalar( $value ) ) return (string) $value;
			if ( is_array( $value ) ) return implode( ', ', array_filter( array_map( 'strval', $value ) ) );
			return '';
		}

		// Quoted string literal: 'text' or "text"
		if ( preg_match( "/^['\"](.*)['\"]\s*$/", $fallback, $m ) ) {
			return $m[1];
		}

		// Another dot-notation path
		$fb_value = self::resolve_path_raw( $fallback, $context );
		if ( self::is_truthy( $fb_value ) ) {
			if ( is_scalar( $fb_value ) ) return (string) $fb_value;
			if ( is_array( $fb_value ) ) return implode( ', ', array_filter( array_map( 'strval', $fb_value ) ) );
		}
		return '';
	}

	/**
	 * Evaluates a condition expression against the context.
	 * Supports simple truthiness (path) and comparisons: >, >=, <, <=, ==, !=
	 * Right-hand side may be a quoted string literal, a numeric literal, or a dot-notation path.
	 *
	 * Examples:
	 *   post.meta.precio >= 100
	 *   post.meta.status == 'activo'
	 *   post.meta.stock != 0
	 */
	private static function evaluate_condition( $expr, $context ) {
		// Multi-char operators must come before single-char ones to avoid partial matches.
		$operators = array( '>=', '<=', '!=', '==', '>', '<' );
		foreach ( $operators as $op ) {
			$pos = strpos( $expr, $op );
			if ( $pos === false ) {
				continue;
			}
			$left_path  = trim( substr( $expr, 0, $pos ) );
			$right_expr = trim( substr( $expr, $pos + strlen( $op ) ) );

			$left = self::resolve_path_raw( $left_path, $context );

			// Right side: quoted string literal, numeric literal, or another path.
			if ( preg_match( "/^['\"](.*)['\"]\$/", $right_expr, $m ) ) {
				$right = $m[1];
			} elseif ( is_numeric( $right_expr ) ) {
				$right = $right_expr + 0;
				$left  = is_numeric( $left ) ? $left + 0 : $left;
			} else {
				$right = self::resolve_path_raw( $right_expr, $context );
			}

			switch ( $op ) {
				case '>':  return $left > $right;
				case '>=': return $left >= $right;
				case '<':  return $left < $right;
				case '<=': return $left <= $right;
				case '==': return $left == $right; // phpcs:ignore WordPress.PHP.StrictComparisons
				case '!=': return $left != $right; // phpcs:ignore WordPress.PHP.StrictComparisons
			}
		}

		// No operator found — fall back to simple truthy check.
		return self::is_truthy( self::resolve_path_raw( $expr, $context ) );
	}

	/**
	 * Public wrapper — evaluates a condition expression against the Handlebars context.
	 *
	 * Designed for external callers such as visibility rules that need to test an
	 * expression string (e.g. "post.posttype == 'post'", "post.meta.price > 100",
	 * "post.meta.my_field") without having access to the private evaluate_condition().
	 *
	 * @param  string     $expr    The condition expression to evaluate.
	 * @param  array|null $context Optional pre-built context; defaults to get_context().
	 * @return bool
	 */
	public static function evaluate_expression( $expr, $context = null ) {
		if ( $context === null ) {
			$context = self::get_context();
		}
		return self::evaluate_condition( $expr, $context );
	}

	/**
	 * Processes {{#if expr}}...{{else}}...{{/if}} blocks iteratively to support nesting.
	 * The expression may be a plain path (truthy check) or a comparison:
	 *   {{#if post.meta.precio >= 100}}Expensive{{else}}Cheap{{/if}}
	 */
	private static function parse_conditionals( $content, $context ) {
		$pattern    = '/\{\{#if\s+([^}]+)\}\}(.*?)(?:\{\{else\}\}(.*?))?\{\{\/if\}\}/s';
		$max_passes = 10;
		$i          = 0;
		do {
			$prev    = $content;
			$content = preg_replace_callback(
				$pattern,
				function( $matches ) use ( $context ) {
					$expr       = trim( $matches[1] );
					$if_block   = $matches[2];
					$else_block = isset( $matches[3] ) ? $matches[3] : '';
					return self::evaluate_condition( $expr, $context ) ? $if_block : $else_block;
				},
				$content
			);
		} while ( $content !== $prev && ++$i < $max_passes );
		return $content;
	}

	/**
	 * Resolves a dot-notation path against the context array.
	 * Returns the original {{path}} token if the path cannot be resolved.
	 */
	private static function resolve_path( $path, $context ){
		$keys  = explode( '.', $path );
		$value = $context;
		foreach ( $keys as $key ) {
			if ( is_array( $value ) && array_key_exists( $key, $value ) ) {
				$value = $value[ $key ];
			} else {
				return '{{' . $path . '}}';
			}
		}
		if ( is_scalar( $value ) ) {
			return (string) $value;
		}
		if ( is_array( $value ) ) {
			return implode( ', ', array_filter( array_map( 'strval', $value ) ) );
		}
		return '';
	}

	public static function parse( $content ){
		$context = self::get_context();
		$content = self::parse_conditionals( $content, $context );
		$content = preg_replace_callback(
			'/\{\{([^}]+)\}\}/',
			function( $matches ) use ( $context ) {
				$token = trim( $matches[1] );
				if ( strpos( $token, '??' ) !== false ) {
					return self::resolve_fallback( $token, $context );
				}
				if ( strpos( $token, '|' ) !== false ) {
					return self::resolve_with_filter( $token, $context );
				}
				return self::resolve_path( $token, $context );
			},
			$content
		);
		return $content;
	}
}