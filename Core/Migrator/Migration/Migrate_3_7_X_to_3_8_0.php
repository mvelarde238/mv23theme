<?php
/**
 * Migration class for migrating from version 3.7.X to 3.8.0
 * Converts old "testimonials" (plural, repeater-based) components into
 * a carousel-wrapper containing individual "testimonial" (singular, composite) components.
 *
 * Text testimonials: author image + author info + comment text.
 * Video testimonials: no image, "Testimonio" as author info, video-component in body.
 */
namespace Core\Migrator\Migration;

use Core\Migrator\Base\Migrate_Components_Settings_v3;

class Migrate_3_7_X_to_3_8_0 extends Migrate_Components_Settings_v3 {
    private static $instance = null;

    /**
     * Collects attribute IDs of migrated carousel-wrappers for post-processing styles.
     * @var array
     */
    private $carousel_attr_ids = array();

    public static function getInstance() {
        if ( self::$instance == null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $batch_size      = 3;
        $do_the_update   = true;
        $delete_old_data = false;
        $title           = 'Migrate 3.7.X to 3.8.0 ( Testimonials to Carousel + Testimonial )';
        $slug            = 'migrate_3_7_x_to_3_8_0';
        $is_top_level    = false;

        parent::__construct( $batch_size, $do_the_update, $title, $slug, $is_top_level, null, $delete_old_data );
    }

    protected function get_target_component_types() {
        return array( 'testimonials' );
    }

    protected function migrate_component( &$component, &$datastore ) {
        $cmp_id = isset( $component['__id'] ) ? $component['__id'] : null;
        if ( ! $cmp_id || ! isset( $datastore[ $cmp_id ] ) ) {
            return;
        }

        // Collect the attribute ID for style injection later
        if ( isset( $component['attributes']['id'] ) ) {
            $this->carousel_attr_ids[] = $component['attributes']['id'];
        }

        $old_entry = $datastore[ $cmp_id ];
        $old_testimonials = isset( $old_entry['testimonials'] ) ? $old_entry['testimonials'] : array();

        if ( ! is_array( $old_testimonials ) || empty( $old_testimonials ) ) {
            return;
        }

        // Build carousel-items from each testimonial in the repeater
        $carousel_items = array();
        foreach ( $old_testimonials as $testimonial_data ) {
            $carousel_items[] = $this->build_carousel_item( $testimonial_data, $datastore );
        }

        // Build carousel inner node (contains all carousel-items)
        $carousel_id = $this->generate_cmp_id();
        $carousel_node = array(
            'type'       => 'carousel',
            '__id'       => $carousel_id,
            'classes'    => array( 'carousel__slider' ),
            'components' => $carousel_items,
        );

        // Overwrite the old testimonials component in-place to become a carousel-wrapper
        $component['type']       = 'carousel-wrapper';
        $component['classes']    = isset( $component['classes'] ) ? $component['classes'] : array();
        $component['classes'][]  = 'carousel';
        $component['classes'][]  = 'component';
        $component['components'] = array( $carousel_node );

        // Replace old datastore entry with carousel-wrapper settings
        $datastore[ $cmp_id ] = array(
            '__type'            => 'carousel-wrapper',
            'carousel_type'     => 'slider',
            'carousel_theme'    => 'theme1',
            'items'             => array(
                'desktop' => '4',
                'laptop'  => '3',
                'tablet'  => '2',
                'mobile'  => '1',
            ),
            'gutter'            => array(
                'desktop' => '20',
                'laptop'  => '20',
                'tablet'  => '20',
                'mobile'  => '20',
            ),
            'controls_settings' => array(
                'show'     => 1,
                'position' => 'center',
            ),
            'nav_settings'      => array(
                'show'     => 0,
                'position' => 'bottom',
            ),
            'autoplay_settings' => array(
                'active'  => 0,
                'timeout' => 5000,
            ),
            'carousel_mode'     => array(
                'active' => false,
                'mode'   => 'carousel',
                'axis'   => 'horizontal',
                'speed'  => 450,
            ),
            'auto_height'       => 0,
            'touch'             => 0,
            'slider_uid'        => '',
            'customize_icons'   => array(
                'active'    => false,
                'prev_icon' => 'fa-angle-left',
                'next_icon' => 'fa-angle-right',
            ),
        );
    }

    /**
     * Extends parent to inject carousel-item margin styles after the tree walk.
     */
    public function migrate_page_content_data( $old_data, $old_datastore = array() ) {
        $this->carousel_attr_ids = array();

        $result = parent::migrate_page_content_data( $old_data, $old_datastore );

        if ( ! empty( $this->carousel_attr_ids ) ) {
            $content_structure = $result['page_content'];
            if ( ! isset( $content_structure['styles'] ) || ! is_array( $content_structure['styles'] ) ) {
                $content_structure['styles'] = array();
            }

            foreach ( $this->carousel_attr_ids as $attr_id ) {
                $content_structure['styles'][] = array(
                    'selectors'    => array(),
                    'selectorsAdd' => '#' . $attr_id . ' .carousel__item',
                    'style'        => array(
                        'margin' => '4px 4px 4px 4px',
                    ),
                );
            }

            $result['page_content'] = $content_structure;
        }

        return $result;
    }

    /**
     * Builds a carousel-item node wrapping a single testimonial component.
     */
    private function build_carousel_item( $testimonial_data, &$datastore ) {
        $type = isset( $testimonial_data['type'] ) ? $testimonial_data['type'] : 'text';

        if ( $type === 'video' ) {
            $testimonial_node = $this->build_video_testimonial( $testimonial_data, $datastore );
        } else {
            $testimonial_node = $this->build_text_testimonial( $testimonial_data, $datastore );
        }

        $item_id = $this->generate_cmp_id();
        return array(
            'type'       => 'carousel-item',
            '__id'       => $item_id,
            'components' => array( $testimonial_node ),
        );
    }

    /**
     * Builds a testimonial component tree for a text-type testimonial.
     */
    private function build_text_testimonial( $data, &$datastore ) {
        $author_img = isset( $data['author_img'] ) ? $data['author_img'] : null;
        $author     = isset( $data['author'] ) ? $data['author'] : '';
        $comment    = isset( $data['comment'] ) ? $data['comment'] : '';

        // Image component (author photo)
        $image_id   = $this->generate_cmp_id();
        $image_node = array(
            'type'    => 'image-component',
            '__id'    => $image_id,
            'classes' => array( 'testimonial__image' ),
        );
        $datastore[ $image_id ] = array(
            '__type'       => 'image-component',
            'image_source' => 'selfhosted',
            'image'        => $author_img ? $author_img : '',
            'aspect_ratio' => '1/1',
        );

        // Author info text-editor
        $info_text_id   = $this->generate_cmp_id();
        $info_text_node = array(
            'type'    => 'text-editor',
            '__id'    => $info_text_id,
        );
        $datastore[ $info_text_id ] = array(
            '__type'  => 'text-editor',
            'content' => $author,
        );

        // Body text-editor (comment)
        $body_text_id   = $this->generate_cmp_id();
        $body_text_node = array(
            'type'    => 'text-editor',
            '__id'    => $body_text_id,
        );
        $datastore[ $body_text_id ] = array(
            '__type'  => 'text-editor',
            'content' => $comment,
        );

        return $this->build_testimonial_shell( $image_node, $info_text_node, $body_text_node, $datastore );
    }

    /**
     * Builds a testimonial component tree for a video-type testimonial.
     */
    private function build_video_testimonial( $data, &$datastore ) {
        $video_data = isset( $data['video'] ) ? $data['video'] : array();

        // Image component (empty — no author photo for video testimonials)
        $image_id   = $this->generate_cmp_id();
        $image_node = array(
            'type'    => 'image-component',
            '__id'    => $image_id,
            'classes' => array( 'testimonial__image' ),
        );
        $datastore[ $image_id ] = array(
            '__type'       => 'image-component',
            'image_source' => 'selfhosted',
            'image'        => '',
            'aspect_ratio' => '1/1',
        );

        // Author info text-editor ("Testimonio")
        $info_text_id   = $this->generate_cmp_id();
        $info_text_node = array(
            'type'    => 'text-editor',
            '__id'    => $info_text_id,
        );
        $datastore[ $info_text_id ] = array(
            '__type'  => 'text-editor',
            'content' => 'Testimonio',
        );

        // Video component in the body
        $video_id   = $this->generate_cmp_id();
        $video_node = array(
            'type'    => 'video-component',
            '__id'    => $video_id,
        );
        $datastore[ $video_id ] = array(
            '__type'        => 'video-component',
            'video_source'  => 'selfhosted',
            'video'         => $video_data,
            'video_settings' => array(
                'controls' => 1,
                'autoplay' => 0,
                'muted'    => 0,
                'loop'     => 0,
                'bgc'      => '#000000',
                'opacity'  => 100,
            ),
            'aspect_ratio'  => 'default',
            'expand_on_click' => 0,
        );

        return $this->build_testimonial_shell( $image_node, $info_text_node, $video_node, $datastore );
    }

    /**
     * Builds the shared testimonial component shell (header + body) used by both text and video types.
     *
     * @param array $image_node     The image-component node (for testimonial__image).
     * @param array $info_text_node The text-editor node (for testimonial__info).
     * @param array $body_content   The content node for the body (text-editor or video-component).
     * @param array &$datastore     The full datastore (by reference).
     * @return array The complete testimonial GJS component node.
     */
    private function build_testimonial_shell( $image_node, $info_text_node, $body_content, &$datastore ) {
        // Icon box (quote icon)
        $icon_id   = $this->generate_cmp_id();
        $icon_node = array(
            'type'    => 'icon-box',
            '__id'    => $icon_id,
            'classes' => array( 'testimonial__quote-icon' ),
        );
        $datastore[ $icon_id ] = array(
            '__type' => 'icon-box',
            'source' => 'icon',
            'icon'   => 'fa-quote-right',
        );

        // Figure wrapper for image
        $figure_id   = $this->generate_cmp_id();
        $figure_node = array(
            'type'       => 'figure',
            '__id'       => $figure_id,
            'classes'    => array( 'testimonial__image-wrapper' ),
            'components' => array( $image_node ),
        );

        // Author info wrapper
        $info_wrapper_id   = $this->generate_cmp_id();
        $info_wrapper_node = array(
            'type'       => 'components-wrapper',
            '__id'       => $info_wrapper_id,
            'classes'    => array( 'components-wrapper', 'testimonial__info' ),
            'components' => array( $info_text_node ),
        );

        // Testimonial header
        $header_id   = $this->generate_cmp_id();
        $header_node = array(
            'type'       => 'testimonial-header',
            '__id'       => $header_id,
            'classes'    => array( 'testimonial__header' ),
            'components' => array( $figure_node, $info_wrapper_node, $icon_node ),
        );

        // Testimonial body wrapper
        $body_wrapper_id   = $this->generate_cmp_id();
        $body_wrapper_node = array(
            'type'       => 'components-wrapper',
            '__id'       => $body_wrapper_id,
            'classes'    => array( 'components-wrapper', 'testimonial__body' ),
            'components' => array( $body_content ),
        );

        // Testimonial root
        $testimonial_id   = $this->generate_cmp_id();
        $testimonial_node = array(
            'type'           => 'testimonial',
            '__id'           => $testimonial_id,
            '__needsSetup'   => false,
            'classes'        => array( 'testimonial', 'component' ),
            'components'     => array( $header_node, $body_wrapper_node ),
        );
        $datastore[ $testimonial_id ] = array(
            '__type'            => 'testimonial',
            'testimonial_style' => 'style1',
        );

        return $testimonial_node;
    }
}
