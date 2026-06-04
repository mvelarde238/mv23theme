<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;
use Core\Frontend\Taxonomy_Breadcrumbs;

class Breadcrumbs extends Component {

    public function __construct() {
        parent::__construct(
            'breadcrumbs',
            __( 'Breadcrumbs', 'mv23theme' )
        );
    }

    public static function get_builder_data() {
        return array(
            'posttypes' => array('single_template')
        );
    }

    public static function get_icon() {
        return 'bi-signpost-split';
    }

    public static function get_fields() {
        $fields = array(
            Field::create( 'icon', 'separator', __('Separator', 'mv23theme') )
                ->add_set( 'bootstrap-icons' )
                ->add_set( 'font-awesome' )
                ->set_default_value( 'bi-chevron-right' ),

            Field::create( 'checkbox', 'show_home', __('Show Home', 'mv23theme') )
                ->set_text( __('Enable', 'mv23theme') )
                ->set_default_value( true ),

            Field::create( 'text', 'home_text', __('Home Text', 'mv23theme') )
                ->set_default_value( __('Home', 'mv23theme') )
                ->set_prefix( __('Text: ', 'mv23theme') )
                ->add_dependency( 'show_home' ),

            Field::create( 'icon', 'home_icon', __('Home Icon', 'mv23theme') )
                ->add_set( 'bootstrap-icons' )
                ->add_set( 'font-awesome' )
                ->set_description( __('If set, replaces the text', 'mv23theme') )
                ->add_dependency( 'show_home' ),

            Field::create( 'checkbox', 'show_current', __('Show Current Post', 'mv23theme') )
                ->set_text( __('Enable', 'mv23theme') ),
        );
        return $fields;
    }

    /**
     * Render the component
     */
    public static function display( $args = array() ) {
        global $post;

        $defaults = array(
            'separator'    => 'bi-chevron-right',
            'show_home'    => true,
            'home_text'    => __( 'Home', 'mv23theme' ),
            'home_icon'    => '',
            'show_current' => true,
        );
        $atts = wp_parse_args( $args, $defaults );

        $post_id = isset( $args['post_id'] ) ? $args['post_id'] : $post->ID;
        if ( ! $post_id ) return '';

        $post_obj = get_post( $post_id );
        $posttype = $post_obj->post_type;

        // CPT → main taxonomy. Can be filtered with 'filter_breadcrumbs_posttypes' (e.g. 'post' => 'category', 'product' => 'product_cat')
        $breadcrumbs_posttypes = apply_filters( 'filter_breadcrumbs_posttypes', array(
            'post'      => 'category',
            'portfolio' => 'portfolio-cat',
            'product'   => 'product_cat',
        ) );

        $taxonomy = isset( $breadcrumbs_posttypes[ $posttype ] ) ? $breadcrumbs_posttypes[ $posttype ] : null;

        $items = array();

        // Item: Home
        $show_home = self::fix_boolean_on_ajax_calls( $atts['show_home'] );
        if ( $show_home ) {
            if ( ! empty( $atts['home_icon'] ) ) {
                $icon_prefix = str_starts_with( $atts['home_icon'], 'fa' ) ? 'fa' : 'bi';
                $home_label = '<i class="' . $icon_prefix . ' ' . esc_attr( $atts['home_icon'] ) . '" aria-label="' . esc_attr__( 'Home', 'mv23theme' ) . '"></i>';
            } else {
                $home_label = esc_html( $atts['home_text'] ?: __( 'Home', 'mv23theme' ) );
            }
            $items[] = array( 'label' => $home_label, 'url' => home_url( '/' ), 'type' => 'home', 'raw' => true );
        }

        // Items: hierarchical chain of terms
        if ( $taxonomy ) {
            $terms_by_id = Taxonomy_Breadcrumbs::get_terms_ids( $taxonomy, $post_id );
            $root_term   = Taxonomy_Breadcrumbs::get_root_term( $taxonomy, $post_id );

            if ( $root_term && ! empty( $terms_by_id ) ) {
                $terms_chain = array( $root_term );
                $current     = $root_term;

                while ( true ) {
                    $child = null;
                    foreach ( $terms_by_id as $term ) {
                        if ( $term->parent === $current->term_id ) {
                            $child = $term;
                            break;
                        }
                    }
                    if ( $child ) {
                        $terms_chain[] = $child;
                        $current       = $child;
                    } else {
                        break;
                    }
                }

                foreach ( $terms_chain as $term ) {
                    $items[] = array( 'label' => $term->name, 'url' => get_term_link( $term ), 'type' => 'term' );
                }
            }
        }

        // Item: current post title (without link)
        $show_current = self::fix_boolean_on_ajax_calls( $atts['show_current'] );
        if ( $show_current ) {
            $items[] = array( 'label' => get_the_title( $post_id ), 'url' => '', 'type' => 'current' );
        }

        /**
         * Filter the breadcrumb items before rendering.
         *
         * Each item is an array with keys: 'label', 'url', 'type' ('home'|'term'|'current'),
         * and optionally 'raw' => true (label is already escaped HTML).
         *
         * @param array  $items    Breadcrumb items.
         * @param int    $post_id  Current post ID.
         * @param string $posttype Current post type.
         */
        $items = apply_filters( 'filter_breadcrumbs_items', $items, $post_id, $posttype );

        if ( empty( $items ) ) return '';

        $sep_class   = esc_attr( $atts['separator'] );
        $sep_prefix  = str_starts_with( $atts['separator'], 'fa' ) ? 'fa' : 'bi';
        $separator   = ' <i class="' . $sep_prefix . ' ' . $sep_class . '"></i> ';

        $links       = array();

        foreach ( $items as $i => $item ) {
            $label = ! empty( $item['raw'] ) ? $item['label'] : esc_html( $item['label'] );
            if ( ! empty( $item['url'] ) ) {
                $links[] = '<a href="' . esc_url( $item['url'] ) . '">' . $label . '</a>';
            } else {
                $links[] = '<span class="breadcrumb-current">' . $label . '</span>';
            }
        }

        $output  = Template_Engine::component_wrapper( 'start', $args );
        $output .= '<nav class="breadcrumbs-wrapper" aria-label="' . esc_attr__( 'Breadcrumb', 'mv23theme' ) . '">';
        $output .= '<p class="breadcrumbs">' . implode( $separator, $links ) . '</p>';
        $output .= '</nav>';
        $output .= Template_Engine::component_wrapper( 'end', $args );

        return $output;
    }

    private static function fix_boolean_on_ajax_calls( $value ) {
        if( $value === 'true' ) return true;
        if( $value === 'false' ) return false;
        return $value;
    }
}

new Breadcrumbs();