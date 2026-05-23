<?php
namespace Core\Admin;

/**
 * Duplicate Post functionality.
 *
 * Adds a "Duplicate" row action to all post types.
 * The duplicate is created as a draft with the suffix "- Copy" (translatable).
 * On save/publish the slug regenerates from the new title because post_name
 * is intentionally left empty for the new draft.
 *
 * Hook available after duplication:
 *   do_action( 'mv_after_duplicate_post', int $new_id, int $original_id, string $post_type, WP_Post $original )
 *
 * The suffix can be filtered:
 *   apply_filters( 'mv_duplicate_post_suffix', string $suffix, WP_Post $original )
 */
class Duplicate_Page {

    const ACTION      = 'mv_duplicate_post';
    const NONCE_BASE  = 'mv_duplicate_post_';

    // -------------------------------------------------------------------------
    // Hook callbacks registered by Theme.php via Loader
    // -------------------------------------------------------------------------

    /**
     * Add "Duplicate" to post row actions (all CPTs).
     *
     * @param array    $actions Existing row actions.
     * @param \WP_Post $post    Current post object.
     * @return array
     */
    public function add_duplicate_link( array $actions, \WP_Post $post ): array {
        if ( ! current_user_can( 'edit_post', $post->ID ) ) {
            return $actions;
        }

        $url = wp_nonce_url(
            admin_url( 'admin.php?action=' . self::ACTION . '&post=' . $post->ID ),
            self::NONCE_BASE . $post->ID
        );

        $actions['mv_duplicate'] = sprintf(
            '<a href="%s">%s</a>',
            esc_url( $url ),
            esc_html__( 'Duplicate', 'mv23theme' )
        );

        return $actions;
    }

    /**
     * Handle the duplication request fired by admin_action_{action}.
     */
    public function handle_duplicate_request(): void {
        $post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;

        if ( ! $post_id ) {
            wp_die( esc_html__( 'No post ID supplied.', 'mv23theme' ) );
        }

        $nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : '';

        if ( ! wp_verify_nonce( $nonce, self::NONCE_BASE . $post_id ) ) {
            wp_die( esc_html__( 'Security check failed, please try again.', 'mv23theme' ) );
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            wp_die( esc_html__( 'You are not allowed to duplicate this post.', 'mv23theme' ) );
        }

        $original = get_post( $post_id );

        if ( ! $original instanceof \WP_Post ) {
            wp_die( esc_html__( 'Original post not found.', 'mv23theme' ) );
        }

        $new_post_id = $this->create_duplicate( $original );

        if ( is_wp_error( $new_post_id ) ) {
            wp_die( esc_html( $new_post_id->get_error_message() ) );
        }

        $this->copy_taxonomies( $post_id, $new_post_id, $original->post_type );
        $this->copy_meta( $post_id, $new_post_id );

        /**
         * Fires after a post has been duplicated.
         *
         * @param int      $new_post_id  ID of the newly created duplicate.
         * @param int      $post_id      ID of the original post.
         * @param string   $post_type    Post type slug.
         * @param \WP_Post $original     The original post object.
         */
        do_action( 'mv_after_duplicate_post', $new_post_id, $post_id, $original->post_type, $original );

        wp_safe_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
        exit;
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Insert the duplicate post record.
     *
     * @param \WP_Post $original
     * @return int|\WP_Error New post ID or WP_Error.
     */
    private function create_duplicate( \WP_Post $original ) {
        /**
         * Filter the suffix appended to the duplicate's title.
         *
         * @param string   $suffix   Default "- Copy".
         * @param \WP_Post $original The original post.
         */
        $suffix = apply_filters( 'mv_duplicate_post_suffix', __( '- Copy', 'mv23theme' ), $original );

        return wp_insert_post( [
            'post_title'     => $original->post_title . ' ' . $suffix,
            'post_content'   => $original->post_content,
            'post_excerpt'   => $original->post_excerpt,
            'post_status'    => 'draft',
            'post_type'      => $original->post_type,
            'post_author'    => get_current_user_id(),
            'post_parent'    => $original->post_parent,
            // Empty post_name: WordPress auto-generates the slug from the title
            // on first publish, so renaming the post before publishing updates the slug.
            'post_name'      => '',
            'comment_status' => $original->comment_status,
            'ping_status'    => $original->ping_status,
            'menu_order'     => $original->menu_order,
            'post_password'  => $original->post_password,
            'to_ping'        => $original->to_ping,
        ], true );
    }

    /**
     * Copy all taxonomy terms from the original post to the duplicate.
     *
     * @param int    $from_id   Original post ID.
     * @param int    $to_id     Duplicate post ID.
     * @param string $post_type Post type slug.
     */
    private function copy_taxonomies( int $from_id, int $to_id, string $post_type ): void {
        $taxonomies = get_object_taxonomies( $post_type );

        foreach ( $taxonomies as $taxonomy ) {
            $term_ids = wp_get_object_terms( $from_id, $taxonomy, [ 'fields' => 'ids' ] );

            if ( ! is_wp_error( $term_ids ) && ! empty( $term_ids ) ) {
                wp_set_object_terms( $to_id, $term_ids, $taxonomy );
            }
        }
    }

    /**
     * Copy all custom meta from the original post to the duplicate.
     * Skips internal WordPress lock/last-edit keys.
     *
     * @param int $from_id Original post ID.
     * @param int $to_id   Duplicate post ID.
     */
    private function copy_meta( int $from_id, int $to_id ): void {
        $skip_keys = [ '_edit_lock', '_edit_last' ];
        $meta_keys  = get_post_custom_keys( $from_id );

        if ( empty( $meta_keys ) ) {
            return;
        }

        foreach ( $meta_keys as $meta_key ) {
            if ( in_array( $meta_key, $skip_keys, true ) ) {
                continue;
            }

            $values = get_post_meta( $from_id, $meta_key );

            foreach ( $values as $value ) {
                add_post_meta( $to_id, $meta_key, wp_slash( $value ) );
            }
        }
    }
}
