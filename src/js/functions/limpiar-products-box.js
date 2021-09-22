function limpiar_products_box(){
	$pb_contents.empty();
	$.each($pb_contents,function(i,e){
		if ($(e).hasClass('isotope-initialized')) {
			$(e).isotope('destroy');
			$(e).removeClass('isotope-initialized');
		}
	});
	$pb_categories.removeClass('in-view');
	$pb_items.attr('data-status','');
	$pb_filtro_checkboxes.prop('checked', false);
	Waypoint.refreshAll();
}