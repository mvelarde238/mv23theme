(function($,c){
    document.addEventListener('DOMContentLoaded', function() {

        var toggle_buttons = document.querySelectorAll('.toggle-box');
        var headerHeight = MV23_GLOBALS.headerHeight;

        for (var i = 0; i < toggle_buttons.length; i++) {
            var selector = toggle_buttons[i].dataset.selector;
            
            if(selector){
                var toggle_boxes = document.querySelectorAll(selector);
                for (var ind = 0; ind < toggle_boxes.length; ind++) {
                    toggle_boxes[ind].style.display = 'none';
                }
            }
        }

        $('body').on('click', '.toggle-box', function(ev){
            var selector = this.dataset.selector;
            var scrollToBox = this.dataset.scrollToBox;
            if(selector){
                ev.preventDefault();
                var boxes = document.querySelectorAll(selector);
                for (var ind = 0; ind < boxes.length; ind++) {
                    if (boxes[ind].style.display === 'none' || boxes[ind].style.display === '') {
                        $(this).parent().addClass('active');
                        $(boxes[ind]).slideDown();
                        
                        if(scrollToBox){
                            $("html, body").animate({ scrollTop: ($(boxes[ind]).offset().top - headerHeight) }, {duration: 800, queue: false});
                        }
                    } else {
                        $(this).parent().removeClass('active');
                        $(boxes[ind]).slideUp();
                    }
                }
            }
        });
    });
})(jQuery,console.log); 