(function($,c){      
    $(function() {
        // ****************************************************************************************************
        // ANCLAS
        // ****************************************************************************************************
        var initial_url = window.location.href.split('#')[0];
        var pageLinks = $('a[href*="#"]');

        for (var i = 0; i < pageLinks.length; i++) {
            var href = $(pageLinks[i]).attr('href'),
                hash = href.split('#')[1];
            if (href.split('#')[0] == initial_url){
                $(pageLinks[i]).attr('href','#'+hash);
            }
        }

        $('a[href^="#"]').click(function (event) {
            event.preventDefault();
            var href = $(this).attr('href');
            if ( href != '#!' && $(href).length > 0 ) {
                history.pushState({},null,href);
                var e = new Event('mv23ReplaceState');
			    window.dispatchEvent(e);
                let elementPosition = $(href).offset().top;
                let newPosition = 0;
                var bodyStyles = window.getComputedStyle(document.body);
                if( href != '#content' ){
                    var sticky_header_height = bodyStyles.getPropertyValue('--sticky-header-height');
                    newPosition = elementPosition - parseInt(sticky_header_height);
                } else {
                    var static_header_height = bodyStyles.getPropertyValue('--static-header-height');
                    newPosition = elementPosition - parseInt(static_header_height);
                }
                $("html, body").animate({ 
                    scrollTop: newPosition
                }, {
                    duration: 800, 
                    queue: false, 
                    // easing: 'easeOutCubic'
                });
            }
        });
        // ****************************************************************************************************
    });
})(jQuery,console.log);