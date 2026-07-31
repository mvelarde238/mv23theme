// ****************************************************************************************************
// INIT GLOBAL VAR FOR ALL MODULES
// ****************************************************************************************************
var viewport = updateViewportDimensions(),
	$_GET = {},
	is_inicio = document.body.classList.contains('home'),
	is_checkout = document.body.classList.contains('woocommerce-checkout');

const DEBUG = MV23_GLOBALS.debug || false;

document.addEventListener('DOMContentLoaded', function() {
	do_get_implementation();
	remove_empty_paragraphs();
	register_scroll_trigger_plugin();
	move_all_megamenu_to_wrapper();
});
