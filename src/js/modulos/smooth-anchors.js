(function($,c){      
    $(function() {
        // ****************************************************************************************************
        // ANCLAS
        // ****************************************************************************************************
        var initial_url = window.location.href.split('#')[0];
        var pageLinks = $('a[href*="#"]');
        var headerHeight = MV23_GLOBALS.headerHeight;

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
            if ($(href).length > 0) {
                $("html, body").animate({ scrollTop: ($(href).offset().top - headerHeight) }, {duration: 800, queue: false, easing: 'easeOutCubic'});
            }
        });
        // ****************************************************************************************************
    });
})(jQuery,console.log);