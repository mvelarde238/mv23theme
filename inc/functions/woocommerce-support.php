<?php
add_action('after_setup_theme', function(){
    add_theme_support('woocommerce');
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
});

remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

// main-content--sidebar-left
if(!function_exists('theme_woocommerce_before_main_content')){
    function theme_woocommerce_before_main_content(){ 
        $main_content_classes = array('main-content','container');
        if(is_archive() && is_active_sidebar('shop_sidebar')) array_push($main_content_classes,'main-content--sidebar-left');
        ?>
        <div id="content">
            <?php if( in_array('product', PAGE_HEADER_IN) ) get_template_part('inc/modulos/page-header'); ?>
            <div id="main-content" class="<?php echo implode(' ',$main_content_classes) ?>">
                <main class="main">
                    <div class="page-module">
                        <div class="componente">
    <?php
    }
}
add_action('woocommerce_before_main_content', 'theme_woocommerce_before_main_content', 10);

if(!function_exists('theme_woocommerce_after_main_content')){
    function theme_woocommerce_after_main_content(){ ?>
                        </div>
                    </div>
                </main>
                <?php if( is_archive() && is_active_sidebar('shop_sidebar') ): ?>
                    <div class="sidebar">
                        <?php dynamic_sidebar( 'shop_sidebar' ); ?>
                    </div>
                <?php endif; ?>
                <?php get_template_part('inc/modulos/modals/controlador'); ?>
            </div>
        </div>
        <?php
    }
}
add_action('woocommerce_after_main_content', 'theme_woocommerce_after_main_content', 10);

// remove_action('woocommerce_sidebar','woocommerce_get_sidebar', 10);

/**
 * Change number of products that are displayed per page (shop page)
 */
// add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

// function new_loop_shop_per_page( $cols ) {
//     $cols = get_option('posts_per_page');
//     return $cols;
// }