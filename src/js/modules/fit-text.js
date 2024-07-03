(function($,c){      
    $(function() {
        // ****************************************************************************************************
        // FIT TEXT
        // ****************************************************************************************************

        var fitText = document.getElementsByClassName('fit-text');

        for (var i = 0; i < fitText.length; i++) {
            var el = fitText[i],
                fontSize = $(el).css("fontSize"),
                maxSize = fontSize ? parseInt(fontSize) : 300, 
                options = { maxSize : maxSize };
            fitty(el, options);
        }

        // ****************************************************************************************************
    });
})(jQuery,console.log);