<?php
namespace Core\Builder;

/**
 * Builder post duplication handler.
 *
 * Hooks into mv_after_duplicate_post (fired by Core\Admin\Duplicate_Page)
 * and regenerates all builder IDs stored in page_content and
 * page_content_datastore of the duplicated post, so that when several posts
 * are rendered on the same page their IDs never collide.
 *
 * Pass the post types that should be handled to the constructor:
 *
 *   new Duplicate( ['offcanvas_element', 'postcard'] )
 *
 * IDs replaced:
 *  - cmp_* component IDs  — datastore keys, __id values, attributes.id values
 *  - oce_uid values        — unique identifier consumed by the frontend JS
 *  - HTML element IDs      — short ids used in CSS selectors and attributes.id
 *  - Frame / page random IDs (16-char strings in the GJS structure)
 */
class Duplicate {

    /** @var string[] Post type slugs that should have their IDs regenerated. */
    private array $post_types;

    /**
     * @param string[] $post_types Post type slugs to handle.
     */
    public function __construct( array $post_types ) {
        $this->post_types = $post_types;
    }

    // -------------------------------------------------------------------------
    // Public hook callback
    // -------------------------------------------------------------------------

    /**
     * Entry point — registered on mv_after_duplicate_post via Loader.
     *
     * @param int      $new_post_id  ID of the newly created duplicate.
     * @param int      $original_id  ID of the original post.
     * @param string   $post_type    Post type slug.
     * @param \WP_Post $original     The original post object.
     */
    public function on_duplicate( int $new_post_id, int $original_id, string $post_type, \WP_Post $original ): void {
        if ( ! in_array( $post_type, $this->post_types, true ) ) {
            return;
        }

        $this->regenerate_ids( $new_post_id );
    }

    // -------------------------------------------------------------------------
    // Regeneration orchestration
    // -------------------------------------------------------------------------

    /**
     * Read page_content and page_content_datastore from the duplicate post,
     * remap every builder ID to a fresh one, and save them back.
     *
     * @param int $post_id The duplicate post ID.
     */
    private function regenerate_ids( int $post_id ): void {
        $page_content = get_post_meta( $post_id, 'page_content', true );
        $datastore    = get_post_meta( $post_id, 'page_content_datastore', true );

        if ( ! is_array( $page_content ) || ! is_array( $datastore ) ) {
            return;
        }

        $id_map = $this->build_id_map( $page_content, $datastore );

        if ( empty( $id_map ) ) {
            return;
        }

        $new_page_content = $this->apply_id_map( $page_content, $id_map );

        // Datastore: rename top-level cmp_* keys AND remap values inside each entry
        $new_datastore = [];
        foreach ( $datastore as $cmp_key => $entry ) {
            $new_key               = $id_map[ $cmp_key ] ?? $cmp_key;
            $new_datastore[$new_key] = $this->apply_id_map( $entry, $id_map );
        }

        update_post_meta( $post_id, 'page_content', $new_page_content );
        update_post_meta( $post_id, 'page_content_datastore', $new_datastore );
    }

    // -------------------------------------------------------------------------
    // ID map builder
    // -------------------------------------------------------------------------

    /**
     * Scan both structures and produce an old-ID → new-ID mapping.
     *
     * @param array $page_content
     * @param array $datastore
     * @return array<string,string>
     */
    private function build_id_map( array $page_content, array $datastore ): array {
        $map = [];

        // 1. cmp_* component IDs and oce_uid from the datastore
        foreach ( $datastore as $cmp_id => $entry ) {
            if ( strpos( $cmp_id, 'cmp_' ) === 0 && ! isset( $map[ $cmp_id ] ) ) {
                $map[ $cmp_id ] = self::new_cmp_id();
            }
            if ( isset( $entry['oce_uid'] ) && is_string( $entry['oce_uid'] ) && $entry['oce_uid'] !== '' ) {
                $old_uid = $entry['oce_uid'];
                if ( ! isset( $map[ $old_uid ] ) ) {
                    $map[ $old_uid ] = self::new_oce_uid();
                }
            }
        }

        // 2. HTML element IDs referenced in CSS selectors (format: "#iXXXX")
        foreach ( $page_content['styles'] ?? [] as $style ) {
            foreach ( $style['selectors'] ?? [] as $selector ) {
                if ( is_string( $selector ) && strpos( $selector, '#' ) === 0 ) {
                    $elem_id = substr( $selector, 1 );
                    if ( ! isset( $map[ $elem_id ] ) ) {
                        $map[ $elem_id ] = self::new_element_id();
                    }
                }
            }
        }

        // 3. Frame/page random IDs and attributes.id from the component tree
        foreach ( $page_content['pages'] ?? [] as $page ) {
            if ( isset( $page['id'] ) && ! isset( $map[ $page['id'] ] ) ) {
                $map[ $page['id'] ] = self::new_random_id( 16 );
            }
            foreach ( $page['frames'] ?? [] as $frame ) {
                if ( isset( $frame['id'] ) && ! isset( $map[ $frame['id'] ] ) ) {
                    $map[ $frame['id'] ] = self::new_random_id( 16 );
                }
                $this->collect_component_attr_ids( $frame['component'] ?? [], $map );
            }
        }

        return $map;
    }

    /**
     * Recursively collect HTML element IDs from component attributes.id
     * that have not already been mapped.
     *
     * @param array                $component GJS component node.
     * @param array<string,string> &$map      Map being built.
     */
    private function collect_component_attr_ids( array $component, array &$map ): void {
        if ( isset( $component['attributes']['id'] ) ) {
            $attr_id = $component['attributes']['id'];
            // cmp_* IDs are already collected from the datastore
            if ( strpos( $attr_id, 'cmp_' ) !== 0 && ! isset( $map[ $attr_id ] ) ) {
                $map[ $attr_id ] = self::new_element_id();
            }
        }

        foreach ( $component['components'] ?? [] as $child ) {
            $this->collect_component_attr_ids( $child, $map );
        }
    }

    // -------------------------------------------------------------------------
    // ID map applicator
    // -------------------------------------------------------------------------

    /**
     * Recursively walk a value and replace every string that is an exact match
     * for an ID in $map, or a CSS selector of the form "#oldId".
     * Array keys matching the map are also renamed.
     *
     * @param mixed                $data
     * @param array<string,string> $map
     * @return mixed
     */
    private function apply_id_map( $data, array $map ) {
        if ( is_array( $data ) ) {
            $result = [];
            foreach ( $data as $key => $value ) {
                $new_key          = $map[ $key ] ?? $key;
                $result[$new_key] = $this->apply_id_map( $value, $map );
            }
            return $result;
        }

        if ( is_string( $data ) ) {
            // Exact match: covers __id, oce_uid, attributes.id, frame id, page id
            if ( isset( $map[ $data ] ) ) {
                return $map[ $data ];
            }
            // CSS selector: "#oldId" → "#newId"
            if ( strpos( $data, '#' ) === 0 ) {
                $elem_id = substr( $data, 1 );
                if ( isset( $map[ $elem_id ] ) ) {
                    return '#' . $map[ $elem_id ];
                }
            }
        }

        return $data;
    }

    // -------------------------------------------------------------------------
    // ID generators
    // -------------------------------------------------------------------------

    /** cmp_* component ID — matches the builder's generate_cmp_id() pattern. */
    private static function new_cmp_id(): string {
        return 'cmp_' . substr( md5( uniqid( '', true ) ), 0, 8 );
    }

    /** oce_uid — matches uniqid('oce_') used by the Offcanvas_Element component field. */
    private static function new_oce_uid(): string {
        return uniqid( 'oce_' );
    }

    /**
     * Short HTML element ID — "i" + 4 hex chars, matching the builder format
     * (e.g. i6ta, i3at7).
     */
    private static function new_element_id(): string {
        return 'i' . substr( md5( uniqid( '', true ) ), 0, 4 );
    }

    /** Random alphanumeric ID of $length chars — used for frame / page IDs. */
    private static function new_random_id( int $length ): string {
        return substr( md5( uniqid( '', true ) ), 0, $length );
    }
}
