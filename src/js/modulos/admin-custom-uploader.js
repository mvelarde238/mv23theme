(function( $,c ) {
	var custom_uploader, attachment;
		
	function get_extension( url ){
		return url.substr((url.lastIndexOf('.') + 1));
	}

	$(document).on('click', '.open_wp_uploader', function(event){
		event.preventDefault();

		var IDinput = $(this).parent().find('.uploader_input'),
			typeinput = $(this).attr('data-typeinput'),
			format = $(this).attr('data-format'),
			box_preview = $(this).parent().find('.box-preview'),
			html = "",
			type = (format === 'pdf') ? 'application/pdf' : 'image';

		//Extend the wp.media object
		custom_uploader = wp.media.frames.file_frame = wp.media({
			title: 'Selecciona un archivo',
			button: {text: 'Seleccionar'},
			multiple: false,
			library: { type: type }
		});

		//When a file is selected, grab the URL and set it as the text field's value
		custom_uploader.on('select', function() {
			attachment = custom_uploader.state().get('selection').first().toJSON();
			if (attachment.mime=='image/jpeg' || attachment.mime=='image/png' || attachment.mime=='image/gif' || attachment.mime=='image/svg+xml') {
				$(IDinput).val(attachment.id);
				html = '<img src="' + attachment.url + '" height="80">';
				$(typeinput).val('image');
			}
			if (attachment.mime=='video/mp4') {
				$(IDinput).val(attachment.id);
				html = '<video controls><source src="' + attachment.url + '" type="video/' + get_extension( attachment.url ) + '" /></video>';
				$(typeinput).val('video');
			}
			if (attachment.mime=='application/pdf') {
				$(IDinput).val(attachment.id);
				html = '<p class="truncate">&nbsp;&nbsp;&nbsp;' + attachment.filename +'</p>';
			}
			$(box_preview).empty();
			$(box_preview).append(html);
		});

		//Open the uploader dialog
		custom_uploader.open();
	});
})( jQuery, console.log );