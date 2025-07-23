<?php
namespace Ultimate_Fields\Helper;

class Hierarchical_Terms {
	protected $taxonomy;
	protected $terms = [];

	public function __construct( $taxonomy ) {
		$this->taxonomy = $taxonomy;
	}

	public function get_terms_array() {
		$all_terms = get_terms([
			'taxonomy'   => $this->taxonomy,
			'hide_empty' => false,
		]);

		if ( is_wp_error( $all_terms ) || empty( $all_terms ) ) {
			return [];
		}

		// Reorganiza por parent
		$terms_by_parent = [];
		foreach ( $all_terms as $term ) {
			$terms_by_parent[ $term->parent ][] = $term;
		}

		// Construye el array jerárquico
		$this->build_hierarchy( 0, $terms_by_parent );

		return $this->terms;
	}

	protected function build_hierarchy( $parent_id, $terms_by_parent, $depth = 0 ) {
		if ( empty( $terms_by_parent[ $parent_id ] ) ) {
			return;
		}

		foreach ( $terms_by_parent[ $parent_id ] as $term ) {
			$indent = str_repeat( '— ', $depth );
			$this->terms[ $term->term_id ] = $indent . $term->name;

			// Recurse
			$this->build_hierarchy( $term->term_id, $terms_by_parent, $depth + 1 );
		}
	}
}
