// ****************************************************************************************************
// INIT GLOBAL VAR FOR ALL MODULES
// ****************************************************************************************************
var viewport = updateViewportDimensions(),
	$_GET = {},
	is_inicio = jQuery('body').hasClass('home'),
	is_checkout = jQuery('body').hasClass('woocommerce-checkout');

do_get_implementation();
remove_empty_paragraphs();

document.addEventListener("DOMContentLoaded", () => {
	if (MV23_GLOBALS.adjustScrollPosition){
		setTimeout(() => {
			const hash = window.location.hash;
			if (hash) {
				adjustScrollPosition(hash);
			}
		}, 400);
	}
});