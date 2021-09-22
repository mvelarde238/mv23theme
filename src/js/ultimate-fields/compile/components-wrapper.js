(function($,c){      
    $(function() {

    	var setMarginElems = $('[data-setmargin]');

    	if (setMarginElems.length > 0) {

    		for (var i = 0; i < setMarginElems.length; i++) {
    			var wrapper = setMarginElems[i];
                var margin = wrapper.dataset.setmargin;
                if (margin!='20') {
                    $(wrapper).find('.componente').css('margin',margin+'px');
                }
    		}
    	}
 
 	});
})(jQuery,console.log);