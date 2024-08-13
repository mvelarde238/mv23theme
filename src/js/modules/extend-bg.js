(function($,c){      
    document.addEventListener('DOMContentLoaded', function() {

		function extend_bg( element, key ) {
			var $div = $('<div/>');
			$div.attr({
				'style' : $(element).attr('style'),
				'class' : 'extended-bg'
			});
			
			var contrary_side = (key == 'left') ? 'right' : 'left';
			var browser_side_distance = ( key == 'left' ) ?
				$(element).offset().left :
				$(window).width() - ($(element).offset().left + $(element).width());

			$div.css({
				[key] : browser_side_distance * -1,
				[contrary_side] : 0
			});
			$div.appendTo(element);
		}

		function do_extend_bg(){
			['right','left'].forEach(key => {
				var $extend_element = $('.extend-bg-to-'+key);
				if ($extend_element.length){
					$.each( $extend_element, function(i,e){
						extend_bg(e,key);
					});
				}
			});
		}

		do_extend_bg();
    });
})(jQuery,console.log); 