// ****************************************************************************************************
// INIT GLOBAL VAR FOR ALL MODULES
// ****************************************************************************************************
var viewport = updateViewportDimensions(),
	$_GET = {},
	is_inicio = jQuery('body').hasClass('home'),
	is_checkout = jQuery('body').hasClass('woocommerce-checkout');

do_get_implementation();
remove_empty_paragraphs();