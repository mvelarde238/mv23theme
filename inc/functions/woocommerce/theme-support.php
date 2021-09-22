<?php
function mytheme_add_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
add_action('woocommerce_before_main_content', 'v23_wrapper_start', 10);
function v23_wrapper_start() {
  echo '<div id="content">';
  if (is_shop()) {
  	get_template_part('inc/modulos/page-header');
  }
  echo '<div class="main-content container">'; 
  // get_sidebar( 'shop' );
  echo '<main class="main"><div class="page-module"><div class="componente">';
}

remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action('woocommerce_after_main_content', 'v23_wrapper_end', 10);
function v23_wrapper_end() {
  echo '</div></div></main>';
  echo "</div></div>";
}

// remove_action('woocommerce_sidebar','woocommerce_get_sidebar', 10);


add_filter( 'woocommerce_show_page_title', 'not_a_shop_page' );
function not_a_shop_page() {
    return boolval(!is_shop());
}


add_filter( 'woocommerce_get_price_html', 'ocultar_precios' );
function ocultar_precios( $price ) {
    return '';
}


add_action( 'woocommerce_after_add_to_cart_form', 'replace_add_to_cart_button' );
// add_action( 'woocommerce_after_shop_loop_item', 'replace_add_to_cart_button' );
add_filter( 'woocommerce_loop_add_to_cart_link', 'replace_add_to_cart_button', 10, 3 );
 
function replace_add_to_cart_button(){ 
	get_template_part('inc/partials/add-to-quote-btn');
}



// add_action( 'woocommerce_init', 'force_non_logged_user_wc_session' );
// function force_non_logged_user_wc_session(){ 
//     if( is_user_logged_in() || is_admin() )
//        return;

//     if ( isset(WC()->session) && ! WC()->session->has_session() ) 
//        WC()->session->set_customer_session_cookie( true ); 
// }



// function my_remove_menu_pages() {
//   remove_menu_page( 'wc-admin&path=/marketing' );
// }
// add_action( 'admin_menu', 'my_remove_menu_pages', 100 );

function wc_disable_marketing_hub( $marketing_pages ) {
  return array();
}

add_filter( 'woocommerce_marketing_menu_items', 'wc_disable_marketing_hub' );