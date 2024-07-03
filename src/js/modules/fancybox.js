(function($,c){
    $(function() {
        // ****************************************************************************************************
        // ****************************************************************************************************

        var toolbar_middle_buttons = [ "zoomIn", "zoomOut", "toggle1to1" ];
        if( !MV23_GLOBALS.isMobile ) toolbar_middle_buttons.push("rotateCCW","rotateCW","flipX", "flipY");

        var fancybox_options = {
            Hash: false,
            Carousel: {
                infinite: false,
                transition: "classic",
            },
            Toolbar: {
                display: {
                    left: ["infobar"],
                    middle: toolbar_middle_buttons,
                    right: ["fullscreen", "slideshow", "thumbs", "close"],
                },
            },
        };

        Fancybox.bind("[data-fancybox], .zoom", fancybox_options);

        // ****************************************************************************************************
    });
})(jQuery,console.log);