(function($,c){      
    $(function() {
        // ****************************************************************************************************
        // SCROLLSPY
        // ****************************************************************************************************
        var header_height = parseInt( MV23_GLOBALS.headerHeight );

        $('.scrollspy').scrollSpy({ 
            activeClass:'is-inview', 
            throttle: header_height,
            // scrollOffset: 0,
        }); 

        // ****************************************************************************************************
    });
})(jQuery,console.log);