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
			'name' => 'Widgets arriba del header',
			'description' => 'Widgets arriba del header (se alinean a la izquierda)',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));
		register_sidebar(array(
			'id' => 'page_sidebar',
			'name' => 'Sidebar para las páginas',
			'description' => 'La página tiene que tener activada la plantilla sidebar Left o Right',
			'before_widget' => '<div id="%1$s" class="widget componente %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));
	}
}