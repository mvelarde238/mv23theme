(function($,c){      
    var header_height = parseInt( MV23_GLOBALS.headerHeight );
    
    $(function() {
        // var header_height = $('.header__content').height();

        // ****************************************************************************************************
        // INIT MATERIALIZE SCRIPTS
        // ****************************************************************************************************

        $('.modal').modal({ dismissible:true,  opacity:.6, inDuration:300,  
            outDuration: MV23_GLOBALS.modal.outDuration, startingTop: '2%', endingTop: '5%', 
            ready: function(modal, trigger) { $(trigger).css('z-index','initial'); },
            complete: function(modal, trigger) {
                var empty_on_close = $(modal).hasClass('empty-on-close');
                if(empty_on_close) $(modal).find('.modal-content').empty();
                $('#video-modal .video-responsive').html(''); 
            } 
        });
        $('.modal-trigger').modal();
        $('.modal-trigger').css('z-index',25);
        // $('.select2').select2();

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
        // INIT TOGGLEBOXES SCRIPT
        // ****************************************************************************************************

        var toggleboxes = document.getElementsByClassName('v23-togglebox');

        for (var i = 0; i < toggleboxes.length; i++) {
            var el = toggleboxes[i],
                options = { headerHeight : MV23_GLOBALS.headerHeight };

            V23_ToggleBox.create( el, options );
        }
        
        // ****************************************************************************************************
        // MOVE SCROLL IF HASH IN URL SCRIPT
        // ****************************************************************************************************

        function move_scroll_if_hash_in_url(){
            var url = document.location.toString();
            var hash = url.split('#')[1];

            if (hash) {
                var top = window.pageYOffset || document.documentElement.scrollTop;
                var elementTop = top - header_height;
                $("html, body").animate({ scrollTop: elementTop }, 100);
            }
        }

        $(window).load(function(e) {
            if (!MV23_GLOBALS.isMobile) {
                move_scroll_if_hash_in_url();
            }
        });

        // ****************************************************************************************************
        // SCRIPT PARA MOVER EL RECAPTCHA A UN WRAPPER
        // ****************************************************************************************************

        $(window).load(function(e) {
            setTimeout(function(){            
                $('.grecaptcha-badge').appendTo('.grecaptcha-badge-wrapper');
            }, 3500 );
	    });

        // ****************************************************************************************************
        // ****************************************************************************************************
        
        Fancybox.bind("[data-fancybox], .zoom", {
            Carousel: {
                transition: "classic",
            },
            Toolbar: {
                display: {
                    left: ["infobar"],
                    middle: [
                        "zoomIn",
                        "zoomOut",
                        "toggle1to1",
                        "rotateCCW",
                        "rotateCW",
                        "flipX",
                        "flipY",
                    ],
                    right: ["slideshow", "thumbs", "close"],
                },
            },
        });

        // ****************************************************************************************************
        // ****************************************************************************************************

        setTimeout(function(){
            $('.theme-gallery--masonry').masonry({
                itemSelector: '.theme-gallery__item',
                columnWidth: '.theme-gallery__item-sizer',
                percentPosition: true
            });
        }, 1);

        // ****************************************************************************************************
        // ****************************************************************************************************

        $('.cover-all').parent().css('position','relative');

        // ****************************************************************************************************
        // CONVERTIR ENLACES A PDF EN PDF
        // ****************************************************************************************************
        var is_single = $('body').hasClass('single');
        
        function convertir_links_en_pdf(links){
            if (links.length > 0) {
                for (var i = 0; i < links.length; i++) {
                    var href = $(links[i]).attr('href');
                    $(links[i]).append(' <i class="fa fa-level-down"></i>');
                    $('<div class="pdf-responsive"><embed src="' + href + '" width="670" height="500" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html"></div>').insertAfter($(links[i]).parent());
                }
            }
        }
        if (is_single) {
            var links = $('.main').find('a[href*=".pdf"]');
            convertir_links_en_pdf(links);
        }

        // ****************************************************************************************************
        // CONVERTIR ENLACES A DOCUMENTOS EN UN VISOR
        // ****************************************************************************************************
        function convertir_docs(links){
            if (links.length > 0) {
                for (var i = 0; i < links.length; i++) {
                    var href = $(links[i]).attr('href');
                    $(links[i]).append(' <i class="fa fa-level-down"></i>');
                    $('<div class="pdf-responsive"><iframe src="https://view.officeapps.live.com/op/embed.aspx?src='+href+'" width="100%" height="565px" frameborder="0"></iframe></div>').insertAfter($(links[i]).parent());
                }
            }
        }
        if (is_single) {
            var links = $('.main').find('a[href*=".docx"], a[href*=".pptx"], a[href*=".xlsxs"]');
            convertir_docs(links);
        }

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

        $('.share-modal').appendTo('#share-modal-wrapper');
        // --------------------------------------------------------------------------------------------------------------
    });

    $(window).load(function(){
        // ****************************************************************************************************
        // ****************************************************************************************************

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