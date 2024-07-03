(function ($, c) {
    // ****************************************************************************************************
    // MOVE SCROLL IF HASH IN URL SCRIPT
    // ****************************************************************************************************

    var header_height = parseInt(MV23_GLOBALS.headerHeight);

    function move_scroll_if_hash_in_url() {
        var url = document.location.toString();
        var hash = url.split('#')[1];

        console.log(hash);
        if (hash) {
            let elementPosition = $('#'+hash).offset().top;
            let newPosition = elementPosition - header_height;
            $("html, body").animate({ scrollTop: newPosition }, 100);
        }
    }

    $(window).load(function (e) {
        if (!MV23_GLOBALS.isMobile) {
            move_scroll_if_hash_in_url();
        }
    });

    // ****************************************************************************************************
})(jQuery, console.log);