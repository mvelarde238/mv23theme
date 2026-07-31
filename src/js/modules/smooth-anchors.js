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

        function maybe_fix_scroll_position_inside_togglebox( element_id ) {
            // if element is inside a .togglebox
            // we need to consider the height of the togglebox button
            var togglebox_button_height = 0;

            var togglebox = $(element_id).parents('.togglebox');            
            if( togglebox.length > 0 ){
                var styles = MV23_GLOBALS.maybeFixScrollPositionStyles;
                var togglebox_style = togglebox.data('style');
                
                if( styles.includes(togglebox_style) ){
                    togglebox_button_height = $(element_id).closest('.togglebox').find('.togglebox__btn[data-boxid="'+element_id+'"]').outerHeight();
                }
            }

            return togglebox_button_height;
        }

        $('a[href^="#"]:not(.no-smooth-scroll)').click(function (event) {
            event.preventDefault();
            var href = $(this).attr('href');
            if ( href != '#!' ) {
                history.pushState({},null,href);
                window.dispatchEvent(new Event('hashchange'));
                var e = new Event('mv23ReplaceState');
			    window.dispatchEvent(e);

                if( $(href).length > 0 ){
                    let elementPosition = $(href).offset().top;
                    let newPosition = elementPosition;
                    if( !MV23_GLOBALS.disableHeaderHeightCalculationOnAnchors ){
                        var bodyStyles = window.getComputedStyle(document.body);
                        if( href != '#content' ){
                            var sticky_header_height = bodyStyles.getPropertyValue('--sticky-header-height');
                            newPosition = elementPosition - parseInt(sticky_header_height);
                        
                            // .maybe-fix-scroll-position implementation
                            var togglebox_button_height = maybe_fix_scroll_position_inside_togglebox(href);
                            newPosition = newPosition - togglebox_button_height;
                        
                        } else {
                            var static_header_height = bodyStyles.getPropertyValue('--static-header-height');
                            newPosition = elementPosition - parseInt(static_header_height);
                        }
                    }
                    $("html, body").animate({ 
                        scrollTop: newPosition
                    }, {
                        duration: 800, 
                        queue: false, 
                        // easing: 'easeOutCubic'
                        // complete: function() {
                        //     console.log('Smooth Anchors: scroll complete, focusing on ' + href);
                        //     $(href).attr('tabindex', '-1').focus();
                        // }
                    });
                }
            }
        });
        // ****************************************************************************************************
    });
})(jQuery,console.log);