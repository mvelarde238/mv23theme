<?php
add_action( 'uf.init', 'theme_register_fields' );

// if (!is_admin()) {}
require_once( 'utils/oembed.php' );
require_once( 'utils/print-modules.php' );

function theme_register_fields() {
	require_once( 'utils/get-secciones-reusables.php' );
	require_once( 'utils/get-color-scheme.php' );
	require_once( 'utils/get-background-styles.php' );
	require_once( 'utils/video-background.php' );
	require_once( 'utils/get-margenes.php' );
	require_once( 'utils/get-border-styles.php' );
	require_once( 'utils/get-box-shadow.php' );
	require_once( 'utils/id-and-class-attributes.php' );
	require_once( 'utils/animation-attributes.php' );

	require_once( 'componentes/index.php' );

	require_once( 'containers/theme-options.php' );
	get_template_part( 'inc/ultimate-fields/containers/page-header' );
	require_once( 'containers/page-content.php' );
	require_once( 'containers/content-blocks.php' );
	get_template_part( 'inc/ultimate-fields/containers/menu-item-data' );
	require_once( 'containers/seccion-reusable.php' );
	// require_once( 'containers/shortcode-icon.php' );
	require_once( 'containers/accordion-options.php' );
	require_once( 'containers/archive-options.php' );
	require_once( 'containers/mv23-library.php' );
	require_once( 'containers/page-settings.php' );
}