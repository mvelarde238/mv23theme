<?php
/**
 * WooCommerce Support
 */
namespace Core\Frontend;

use Core\Builder\Component\Aside;
use Core\Posttype\Single_Template as Single_Template_Posttype;
use Core\Frontend\Frontend;

class WooCommerce_Support{

    public function add_theme_support(){
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );

        remove_action('woocommerce_sidebar','woocommerce_get_sidebar', 10);
        remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
        remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
    }

    public function before_main_content(){ 
        $main_content_classes = array('main-content');
        if(is_archive() && is_active_sidebar('shop_sidebar')) $main_content_classes[] = 'main-content--sidebar-left';
        ?>
        <div id="content">
            <section class="page-module">
                <div class="container">
                    <div class="<?php echo esc_attr(implode(' ', $main_content_classes)); ?>">
                        <main class="main">
        <?php
    }

    public function after_main_content(){ ?>
                        </main>
                        <?php
                        if(is_archive() && is_active_sidebar('shop_sidebar')){
                            echo Aside::display(array(
                                'components' => array(
                                    array(
                                        'type' => 'components-wrapper',
                                        'additional_classes' => ['shop-sidebar','sticky'],
                                        'components' => array(
                                            array( 'type' => 'sidebar', 'sidebar' => 'shop_sidebar' )
                                        )
                                    )
                                )
                            ));
                        }
                        ?>
                    </div>
                </div>
            </section>
        </div>
        <?php
    }

    public function show_shop_header_sidebar(){
        if (is_active_sidebar( 'shop_header_sidebar' )):
            echo '<div class="shop-header-sidebar">';
            dynamic_sidebar( 'shop_header_sidebar' );
            echo '</div>';
        endif;
    }

    public function get_cart_unique_items_count() {
        if ( ! WC()->cart ) {
            echo 0;
            wp_die();
        }
    
        $cart_items = WC()->cart->get_cart();
    
        $unique_items_count = count( $cart_items );
    
        echo $unique_items_count;
        wp_die();
    }

    // Add WooCommerce-related globals to the MV23_GLOBALS array for frontend scripts.
    public function add_woocommerce_globals($mv23_globals) {
        if (WOOCOMMERCE_IS_ACTIVE) {
            $mv23_globals['woocommerce_is_active'] = true;
            $mv23_globals['items_in_cart'] = WC()->cart->get_cart_contents_count();
        }
        return $mv23_globals;
    }

    // Initialize WooCommerce-related components for the builder
    public function init_components() {
        $components = array(
            'Woo_Breadcrumbs',
            'Woo_Product_Wrapper',
            'Woo_Product_Sale_Flash',
            'Woo_Product_Images',
            'Woo_Product_Summary',
            'Woo_Product_Rating',   
            'Woo_Product_Price',
            'Woo_Short_Description',
            'Woo_Add_To_Cart',
            'Woo_Product_Meta',
            'Woo_Product_Share',
            'Woo_Product_Tabs',
            'Woo_Product_Upsells',
            'Woo_Related_Products',
        );

        foreach ($components as $componentName) {
            // Try category subfolder first, then fall back to flat Component/ directory.
            // The class file must keep namespace Core\Builder\Component regardless of location.
            $category_path = 'Core/Builder/Component/woocommerce/' . $componentName . '.php';
            $flat_path     = 'Core/Builder/Component/' . $componentName . '.php';

            if( locate_template( $category_path ) ) {
                locate_template( $category_path, true, true );
            } else {
                locate_template( $flat_path, true, true );
            }
        }
    }

    // Switch the product template to the custom single template if available
    public function switch_product_to_single_template($template) {
        if (is_singular('product')) {
            // Return the default template if no single template is connected to the product post type
            $single_template_id = Single_Template_Posttype::getInstance()->get_single_template_id( 'product' );
            if ( !$single_template_id ) {
                return $template;
            }

            // Remove default WooCommerce templates to use custom ones
            remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
            remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
            remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
            remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
            remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
            remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

            // Locate the theme's native single.php file
            $single_template_file = locate_template(array('single.php'));

            if (!empty($single_template_file)) {
                return $single_template_file;
            }
        }
        return $template;
    }

    // Set up the global $product variable on single product pages
    // needed when using custom single product templates that rely on the global $product variable
    public function set_up_global_product_on_single() {
        if ( is_singular( 'product' ) && have_posts() ) {
            the_post();
            global $product;
            $product = wc_get_product( get_the_ID() );
            rewind_posts();
        }
    }

    // Register WooCommerce styles for the builder context
    public function add_styles() {
        if( is_admin() ){
            $wc_css = WC()->plugin_url() . '/assets/css/';
            $wc_ver = defined( 'WC_VERSION' ) ? WC_VERSION : $this->version;
            Frontend::add_style( 'woocommerce-layout', $wc_css . 'woocommerce-layout.css', array(), $wc_ver, 'all' );
            Frontend::add_style( 'woocommerce-smallscreen', $wc_css . 'woocommerce-smallscreen.css', array( 'woocommerce-layout' ), $wc_ver, 'only screen and (max-width: 768px)' );
            Frontend::add_style( 'woocommerce-general', $wc_css . 'woocommerce.css', array(), $wc_ver, 'all' );
        }
    }

    /**
     * Add product meta to the Ultimate Builder context.
     * 
     * We need this on the **admin** side when editing a product single template.
     *
     * @param array $context The context array.
     * @return array Modified context array.
     */
    public function add_product_meta_to_context($context) {
        if (is_admin() && !wp_doing_ajax() && $context['post']['posttype'] === 'product') {

            $product_id = $context['post']['id'];
            $product = wc_get_product($product_id);
            
            if($product){
                $product_meta = array();
                
                $regular_price = $product->get_regular_price();
                $product_meta['regular_price'] = $regular_price;
                $product_meta['price'] = $product->get_price() ?? $regular_price;

                $is_variable = $product->is_type('variable');
                $product_meta['is_variable'] = $is_variable;
                if( $is_variable ) {
                    $product_meta['price'] = '--Variable Price--';
                }
                
                $is_on_sale = $product->is_on_sale();
                $product_meta['is_on_sale'] = $is_on_sale;
                if( $is_on_sale ) {
                    $product_meta['sale_price'] = $product->get_sale_price();
                }
                    
                $context['post']['product_meta'] = $product_meta;
            }
        }
        return $context;
    }
}