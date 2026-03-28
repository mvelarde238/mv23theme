<?php
/**
 * TODO: Handlebars - parsing features to implement
 *
 * [x] 0. Contexto estructurado + dot-notation  →  get_context() retorna array anidado; resolve_path() navega rutas.
 *         Filter hook: 'ultimate_builder_handlebars_context'  |  JS usa BUILDER_GLOBALS.context
 *
 * [ ] 1. Filtros/modificadores  →  {{post.title|uppercase}}, {{post.meta.precio|number_format}}, {{post.meta.desc|truncate:120}}
 *         Implementar con preg_replace_callback capturando nombre y filtro.
 *
 * [ ] 2. Fallback / valor por defecto  →  {{post.meta.subtitulo ?? post.title}}, {{post.meta.tel ?? 'Sin teléfono'}}
 *         Resolver con regex; evita huecos en el HTML cuando un meta está vacío.
 *
 * [ ] 3. Condicionales simples  →  {{#if post.meta.precio}}Precio: {{post.meta.precio}}{{/if}}
 *         Requiere un segundo pase de regex o mini-parser para ocultar bloques vacíos.
 *
 * [ ] 4. Loops sobre post meta arrays  →  {{#each post.meta.galeria}}<img src="{{this.url}}">{{/each}}
 *         Útil con ACF/UF repeaters. Requiere parser más elaborado.
 */
namespace Ultimate_Fields\Ultimate_Builder;

class Handlebars{

	public static function get_context( $post_id = null ){
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$context = array(
			'post' => array(
				'title'     => get_the_title( $post_id ),
				'thumbnail' => get_the_post_thumbnail_url( $post_id, 'full' ) ?: '',
				'meta'      => array(),
			),
			'site' => array(
				'title'   => get_bloginfo( 'name' ),
				'tagline' => get_bloginfo( 'description' ),
				'url'     => home_url( '/' ),
			),
			'current_year' => date( 'Y' ),
		);

		// Add post meta under post.meta.*
		$post_meta = get_post_meta( $post_id, '', true );
		if ( $post_meta ) {
			foreach ( $post_meta as $key => $value ) {
				if ( strpos( $key, '_' ) === 0 || strpos( $key, 'page_content' ) === 0 ) {
					continue;
				}
				$context['post']['meta'][ $key ] = maybe_unserialize( $value[0] );
			}
		}

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
		return is_scalar( $value ) ? (string) $value : '';
	}

	/**
	 * Returns the __handlebars() JS function as an inline script string.
	 * Intended to be registered via wp_add_inline_script().
	 */
	public static function get_js(){
		return <<<'JS'
			__handlebars = (string) => {
			    const context = BUILDER_GLOBALS.context;

			    const resolvePath = (path, ctx) => {
			        return path.split('.').reduce((acc, key) => {
			            return (acc !== null && acc !== undefined && typeof acc === 'object') ? acc[key] : undefined;
			        }, ctx);
			    };

			    return string.replace(/\{\{([^}]+)\}\}/g, (match, token) => {
			        const value = resolvePath(token.trim(), context);
			        return (value !== undefined && value !== null) ? String(value) : match;
			    });
			};
			JS;
	}

	public static function parse( $content ){
		$context = self::get_context();
		$content = preg_replace_callback(
			'/\{\{([^}]+)\}\}/',
			function( $matches ) use ( $context ) {
				return self::resolve_path( trim( $matches[1] ), $context );
			},
			$content
		);
		return $content;
	}
}