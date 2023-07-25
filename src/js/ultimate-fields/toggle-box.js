(function($,c){
    document.addEventListener('DOMContentLoaded', function() {

        var toggle_buttons = document.querySelectorAll('.toggle-box');

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
            if(selector){
                var boxes = document.querySelectorAll(selector);
                for (var ind = 0; ind < boxes.length; ind++) {
                    if (boxes[ind].style.display === 'none') {
                        $(this).parent().addClass('active');
                        $(boxes[ind]).slideDown();
                    } else {
                        $(this).parent().removeClass('active');
                        $(boxes[ind]).slideUp();
                    }
                }
            }
        });
    });
})(jQuery,console.log); 