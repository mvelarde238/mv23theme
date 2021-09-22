$_GET = {};
if(document.location.toString().indexOf('?') !== -1) {
	var query = document.location
		.toString()
		// get the query string
		.replace(/^.*?\?/, '')
		// and remove any existing hash string (thanks, @vrijdenker)
		.replace(/#.*$/, '')
		.split('&');
	for(var i=0, l=query.length; i<l; i++) {
		var aux = decodeURIComponent(query[i]).split('=');
		$_GET[aux[0]] = aux[1];
	}
}

(function( $,c ) {
	$(function(){
		// ******************************************************************************************************************
		// scripts for archive page posytype edit screen
		// ******************************************************************************************************************
		if ($_GET['post_type'] == 'archive_page') {
			var metaboxes = {
				'category': '#categorydiv',
				'post_tag' : '#tagsdiv-post_tag'
			};
	
			// hide metaboxes
			function hide_metaboxes(){
				$('#categorydiv').hide();
				$('#tagsdiv-post_tag').hide();
			}
			hide_metaboxes();
	
			// show metabox deppending on post meta
			var appears_on = $('.uf-field-name-appears_on').find('select').val();
			if (appears_on == 'taxonomy') {
				var connected_taxonomy = $('.uf-field-name-connected_taxonomy').find('input[type=radio]:checked').val();
				if(connected_taxonomy != ''){
					$(metaboxes[connected_taxonomy]).show();
				}
			}
	
			// show metabox on seleting meta box
			$('.uf-field-name-connected_taxonomy').find('input[type=radio]').change(function(){
				hide_metaboxes();
				var connected_taxonomy = $(this).val();
				if(connected_taxonomy != ''){
					$(metaboxes[connected_taxonomy]).show();
				}
			});
		}
		// ******************************************************************************************************************
		// ******************************************************************************************************************
	});
})( jQuery, console.log );