<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Ultimate_Fields\Field;
use Core\Builder\Template_Engine;

class Woo_Breadcrumbs extends Component {

    public function __construct() {
		parent::__construct(
			'woo-breadcrumbs',
			__( 'Breadcrumbs', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'posttypes' => array('single_template', 'archive_template'),
            'block_category' => 'woocommerce'
		);
    }

    public static function get_icon() {
        return 'bi-signpost-split';
    }

    public static function get_fields() {
        $templates = array();
        for($i = 0; $i <= 3; $i++) {
            $templates['template' . $i] = array(
                'label' => 'Template ' . $i,
                'image' => BUILDER_PATH . '/assets/images/breadcrumbs/template' . $i . '.png'
            );
        }

		$fields = array(
            Field::create( 'message', '__message' )->hide_label()
                ->set_description(__('This component displays the WooCommerce breadcrumbs.', 'mv23theme')),

            Field::create( 'section', '_template_section', 'Template' ),
            Field::create( 'image_select', 'template' )->hide_label()->add_options( $templates ),

            Field::create( 'section', '_delimiter_section', __('Delimiter', 'mv23theme') ),
            Field::create( 'radio', 'delimiter_type' )
                ->hide_label()->set_prefix(__('Delimiter type','mv23theme'))
                ->add_options( array(
                    'text' => __('Text', 'mv23theme'),
                    'icon' => __('Icon', 'mv23theme')
                ))->set_orientation( 'horizontal' ),
            Field::create( 'text', 'delimiter_text' )
                ->hide_label()->set_prefix(__('Delimiter text','mv23theme'))
                ->set_placeholder('/')
                ->add_dependency( 'delimiter_type', 'text', '=' ),
            Field::create( 'icon', 'delimiter_icon' )
                ->hide_label()
                ->add_set( 'bootstrap-icons' )
                ->add_set( 'font-awesome' )
                ->add_dependency( 'delimiter_type', 'icon', '=' ),

        );
		return $fields;
	}

    public static function display($args = array()) {
        if( WOOCOMMERCE_IS_ACTIVE ){
            $args['additional_classes'][] = 'component';

            $template = isset($args['template']) ? $args['template'] : '';
            if( $template != 'template0' ) {
                $args['additional_classes'][] = $template;
            }

            $delimiter = '/';
            $delimiter_type = $args['delimiter_type'] ?? 'text';
            $delimiter_text = $args['delimiter_text'] ?? '/';
            $delimiter_icon = $args['delimiter_icon'] ?? '';

            if( $delimiter_type === 'text' && $delimiter_text ) {
                $delimiter = $delimiter_text;
            } else if( $delimiter_type === 'icon' && $delimiter_icon ) {
                $iconPrefix = strpos($delimiter_icon, 'fa') === 0 ? 'fa' : 'bi';
                $delimiter = '<i class="' . $iconPrefix . ' ' . $delimiter_icon . '"></i>';
            }

            ob_start();
            echo Template_Engine::component_wrapper('start', $args);
            // woocommerce_breadcrumb(); default WooCommerce breadcrumb
            self::render_product_breadcrumbs( array( 'delimiter' => $delimiter ) );
            echo Template_Engine::component_wrapper('end', $args);
            return ob_get_clean();
        }
    }
    
    // Render the WooCommerce product breadcrumbs, fix the issue with the default WooCommerce breadcrumb 
    // where the current product and delimiter are displayed without proper HTML elements
    public static function render_product_breadcrumbs( array $args = array() ) {
        $delimiter = isset($args['delimiter']) ? $args['delimiter'] : '/';

        $args = apply_filters(
            'woocommerce_breadcrumb_defaults',
            [
                'delimiter'   => '<span class="breadcrumb-delimiter">' . $delimiter . '</span>',
                'wrap_before' => '<nav class="woocommerce-breadcrumb" aria-label="Breadcrumb">',
                'wrap_after'  => '</nav>',
                'before'      => '',
                'after'       => '',
                'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
            ]
        );

        $breadcrumbs = new \WC_Breadcrumb();

        if ( ! empty( $args['home'] ) ) {
            $breadcrumbs->add_crumb(
                $args['home'],
                apply_filters( 'woocommerce_breadcrumb_home_url', home_url() )
            );
        }

        $args['breadcrumb'] = $breadcrumbs->generate();

        // Maintain compatibility with WooCommerce breadcrumb schema.
        do_action( 'woocommerce_breadcrumb', $breadcrumbs, $args );

        if ( empty( $args['breadcrumb'] ) ) {
            return;
        }

        echo $args['wrap_before'];

        $last_key = array_key_last( $args['breadcrumb'] );

        foreach ( $args['breadcrumb'] as $key => $crumb ) {

            echo $args['before'];

            if ( is_product() && $key === $last_key ) {

                echo '<span class="breadcrumb-current" aria-current="page">';
                echo esc_html( $crumb[0] );
                echo '</span>';

            } elseif ( ! empty( $crumb[1] ) && $key !== $last_key ) {

                echo '<a href="' . esc_url( $crumb[1] ) . '">';
                echo esc_html( $crumb[0] );
                echo '</a>';

            } else {

                echo esc_html( $crumb[0] );

            }

            echo $args['after'];

            if ( $key !== $last_key ) {
                echo $args['delimiter'];
            }
        }

        echo $args['wrap_after'];
    }
    
    public static function get_view_template() {
		$template = '<%
        _d = "/";
        if( delimiter_type === "text" && delimiter_text ){
            _d = delimiter_text;
        } else if( delimiter_type === "icon" && delimiter_icon ){
            const iconPrefix = delimiter_icon.startsWith("fa") ? "fa" : "bi";
            _d = `<i class="${iconPrefix} ${delimiter_icon}"></i>`;
        }
        %><nav class="woocommerce-breadcrumb"><a href="#">Home</a><span class="breadcrumb-delimiter"><%= _d %></span><a href="#">Taxonomy</a><span class="breadcrumb-delimiter"><%= _d %></span><span class="breadcrumb-current" aria-current="page"><%= window["Handlebars"].parse("{{post.title}}") %></span></nav>';
        
        return $template;
    }
}

new Woo_Breadcrumbs();