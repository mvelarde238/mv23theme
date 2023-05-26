(function(tinymce) {
tinymce.PluginManager.add('button_manager', function( editor, url ) {
    var $ = window.jQuery;

    if ( $ ) {
        $( document ).triggerHandler( 'tinymce-editor-setup', [ editor ] );
    }

    editor.addButton('button_manager', {
    	text: 'Botón',
    	icon: false,
    	onclick: function() {
    		editor.insertContent('[button text="CTA Button" style="btn btn--main-color" href="#" fullwidth="false" alignment=""]');
    	}
    });
});

})(window.tinymce);