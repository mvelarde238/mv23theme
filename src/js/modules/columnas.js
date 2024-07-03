(function($,c){      
    document.addEventListener('DOMContentLoaded', function() {

		// var $columnas_laterales_extendidas = $('.columnas.layout4').find('div[class*=columnas-]');
		var $columnas_laterales_extendidas = $('.columnas.layout4>div>div');
		if ($columnas_laterales_extendidas.length){

			$.each( $columnas_laterales_extendidas, function(i,e){
				var $first = $(e).children().first();
				var $div = $('<div/>');
				$first.append($div);
				$div.attr({
					'style' : $first.attr('style'),
					'class' : 'extended-bg-first'
				});

				var $last = $(e).children().last();
				var $div = $('<div/>');
				$last.append($div);
				$div.attr({
					'style' : $last.attr('style'),
					'class' : 'extended-bg-last'
				});
			});

		}
    });
})(jQuery,console.log); 