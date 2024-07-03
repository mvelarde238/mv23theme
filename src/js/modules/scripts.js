(function($,c){      
    $(function() {
        // ****************************************************************************************************
        // SCRIPT PARA MOVER EL RECAPTCHA A UN WRAPPER
        // ****************************************************************************************************

        $(window).load(function(e) {
            setTimeout(function(){            
                $('.grecaptcha-badge').appendTo('.grecaptcha-badge-wrapper');
            }, 3500 );
	    });        

        // *********************************************************************
        // REMOVE ACTIVE IN MENU ITEMS WITH ANCHOR
        // *********************************************************************
        var menu_items_links = $(".main-nav li a");
        menu_items_links.each(function () {
            if ($(this).is('[href*="#"')) {
                $(this).parent().removeClass('current-menu-item current-menu-ancestor');
                // $(this).click(function () {
                    // var current_index = $(this).parent().index(),
                        // parent_element = $(this).closest('ul');
                        // parent_element.find('li').not(':eq(' + current_index + ')').removeClass('current-menu-item current-menu-ancestor');
                    // $(this).parent().addClass('current-menu-item current-menu-ancestor');
                // })
            }
        })

        // ****************************************************************************************************
        // ****************************************************************************************************
        $('.cover-all').parent().css('position','relative');
        $('.share-modal').appendTo('#share-modal-wrapper');
        // --------------------------------------------------------------------------------------------------------------
    });
})(jQuery,console.log);