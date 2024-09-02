<?php
/**
 * WooCommerce Support
 */

namespace Core\FrontEnd;

class WooCommerce_Support{

    public function add_theme_support(){
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );

        remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
        remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
    }

    public function before_main_content(){ 
        $main_content_classes = array('main-content','container');
        if(is_archive() && is_active_sidebar('shop_sidebar')) array_push($main_content_classes,'main-content--sidebar-left');
        ?>
        <div id="content">
            <?php if( in_array('product', PAGE_HEADER_IN) ) get_template_part('partials/page-header'); ?>
            <div id="main-content" class="<?php echo implode(' ',$main_content_classes) ?>">
                <main class="main">
                    <div class="page-module">
                        <div class="component">
    <?php
    }

    public function after_main_content(){ ?>
                        </div>
                    </div>
                </main>
                <?php if( is_archive() && is_active_sidebar('shop_sidebar') ): ?>
                    <div class="sidebar">
                        <?php dynamic_sidebar( 'shop_sidebar' ); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}