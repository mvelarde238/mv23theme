(function($,c){      
    $(function() {
        // ****************************************************************************************************
        // BOTÓN SUBIR
        // When the user scrolls down 1100px from the top of the document, show the button
        // ****************************************************************************************************

        window.onscroll = function() {scrollFunction()};
        function scrollFunction() {
            if (document.body.scrollTop > 1100 || document.documentElement.scrollTop > 1100) {
                $('.subir-btn').attr('data-state','')
            } else {
                $('.subir-btn').attr('data-state','hidden')
            }
        }
    });
})(jQuery,console.log);