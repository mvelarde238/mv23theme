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
add_action('woocommerce_before_main_content', function(){ ?>
    <div id="content">
        <?php get_template_part('inc/modulos/page-header'); ?>
        <div class="main-content container">
            <main class="main">
                <div class="page-module">
                    <div class="componente">
    <?php
}, 10);

add_action('woocommerce_after_main_content', function() { ?>
                    </div>
                </div>
            </main>
            <!-- <div class="sidebar"><div class="page-module"><?php //dynamic_sidebar( 'sidebar_shop' ); ?></div></div> -->
            <?php get_template_part('inc/modulos/modals/controlador'); ?>
        </div>
    </div>
<?php
}, 10);

// remove_action('woocommerce_sidebar','woocommerce_get_sidebar', 10);

/**
 * Change number of products that are displayed per page (shop page)
 */
add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

function new_loop_shop_per_page( $cols ) {
    $cols = get_option('posts_per_page');
    return $cols;
}