document.addEventListener('DOMContentLoaded', function () {

    // ****************************************************************************************************
    // STICKY HEADER IMPLEMENTATION
    // ****************************************************************************************************
    const header = document.querySelector('.header');
    stickyHeader.init(
        header, 
        window, 
        MV23_GLOBALS.stickyHeaderBreakpoint
    );

    var event = new CustomEvent('theme_document_ready');
    setTimeout(function () {
        document.body.dispatchEvent(event);
    }, 1);
});