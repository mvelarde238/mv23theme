(function($,c){      
    $(window).load(function(){
        // ****************************************************************************************************
        // ****************************************************************************************************
        var header_height = parseInt( MV23_GLOBALS.headerHeight );

        var $pinnedBlocks = $('.pinned-block');

        if (viewport.width > 1024) {
            $pinnedBlocks.each(function(){
                var $this = $(this),
                    $target = $this.parent();
    
                $this.css('width',$target.css('width'));

                if( $target.height() > $this.height() ){
                    setTimeout(function(){
                        $this.pushpin({
                            top: $target.offset().top,
                            bottom: $target.offset().top + $target.outerHeight() - $this.height(),
                            offset: header_height
                        });
                    },1000);
                }
            });
        }

        window.addEventListener('resize', function(event){
            $pinnedBlocks.each(function(){
                var $this = $(this),
                    $target = $this.parent();
                
                if (window.innerWidth > 1024) {
                    $this.css('width',$target.css('width'));
                } else {
                    $this.pushpin('remove');
                    $this.css('width', '100%');
                }
            });
        });

        // ****************************************************************************************************
        // ****************************************************************************************************
    });
})(jQuery,console.log);