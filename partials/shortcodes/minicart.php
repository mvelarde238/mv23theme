<?php
function custom_woocommerce_cart_shortcode() {
    ob_start();

    if ( class_exists( 'WooCommerce' ) ) {
        ?>
        <div id="woocommerce_widget_cart-23" class="widget woocommerce widget_shopping_cart">
            <div class="widget_shopping_cart_content">
                <?php woocommerce_mini_cart(); ?>
            </div>
        </div>
        <?php
    } else {
        echo 'WooCommerce no está activo.';
    }
    
    return ob_get_clean();
}
add_shortcode( 'minicart', 'custom_woocommerce_cart_shortcode' );