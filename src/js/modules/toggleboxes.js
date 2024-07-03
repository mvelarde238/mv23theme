(function($,c){      
    $(function() {
        // ****************************************************************************************************
        // INIT TOGGLEBOXES SCRIPT
        // ****************************************************************************************************
        var toggleboxes = document.getElementsByClassName('v23-togglebox');

        for (var i = 0; i < toggleboxes.length; i++) {
            var el = toggleboxes[i],
                options = { headerHeight : MV23_GLOBALS.headerHeight };

            V23_ToggleBox.create( el, options );
        }
        
        // ****************************************************************************************************
    });
})(jQuery,console.log);