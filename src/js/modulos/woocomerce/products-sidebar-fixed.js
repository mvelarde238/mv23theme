(function($,c){      
    $(function() {
        // ****************************************************************************************************
        // SIDEBAR FIXED
        // ****************************************************************************************************

        var $pb = $('.products-box:first-of-type'),
            $pb_sidebar = $('.products-box__sidebar:first-of-type');

        if ($pb_sidebar.length > 0 && viewport.width > 768 ) {
            var pb_sidebar_top = $pb_sidebar.offset().top;
            var pb_sidebar_bottom = $('#footer').offset().top;
            var header_height = 80; /*px*/

            new Waypoint({
                element: $pb,
                handler: function(direction) {
                    if (direction === 'down') {
                        $pb_sidebar.addClass('fixed');
                    }
                    if (direction === 'up') {
                        $pb_sidebar.removeClass('fixed');
                    }
                },
                offset: header_height + 'px',
            })


            var $pb_base = $('#products-box__waypoint-base');

            new Waypoint({
                element: $pb_base,
                handler: function(direction) {
                    if (direction === 'down') {
                        $pb_sidebar.removeClass('fixed');
                    }
                    if (direction === 'up') {
                        $pb_sidebar.addClass('fixed');
                    }
                },
                offset: function(){
                    return (header_height + $pb_sidebar.height());
                },
            })
        }

        // ****************************************************************************************************
        // ****************************************************************************************************
    });
})(jQuery,console.log);