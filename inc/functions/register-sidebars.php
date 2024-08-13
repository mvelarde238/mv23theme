<?php
/**
 * Register Sidebars & Widgetizes Areas
 *
 * @return void
 */
if (!function_exists('mv23_register_sidebars')) {
	function mv23_register_sidebars() {
		register_sidebar(array(
			'id' => 'header_widgets_1',
			'name' => 'Header Widgets',
			'description' => 'Widgets en el header',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));
		register_sidebar(array(
			'id' => 'page_sidebar',
			'name' => 'Sidebar para las páginas',
			'description' => 'La página tiene que tener activada la plantilla sidebar Left o Right',
			'before_widget' => '<div id="%1$s" class="widget component %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));
		if(WOOCOMMERCE_IS_ACTIVE){
			register_sidebar(array(
				'id' => 'shop_sidebar',
				'name' => 'Sidebar para la tienda',
				'before_widget' => '<div id="%1$s" class="widget component %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widgettitle">',
				'after_title' => '</h4>',
			));
			register_sidebar(array(
				'id' => 'minicart_sidebar',
				'name' => 'Sidebar Carrito de Compras',
				'description' => '',
				'before_widget' => '<div id="%1$s" class="widget %2$s component">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widgettitle">',
				'after_title' => '</h4>',
			));
		}
	}
}