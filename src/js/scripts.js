(function($,c){      
    var header_height = parseInt( MV23_GLOBALS.headerHeight );
    
    $(function() {
        // ****************************************************************************************************
        // INIT MATERIALIZE MODAL SCRIPT
        // ****************************************************************************************************

        var modals = document.getElementsByClassName('modal');

        for (var i = 0; i < modals.length; i++) {
            var el = modals[i],
                closeOnClick = el.dataset['closeOnClick'];

            $(el).modal({ dismissible:true, opacity:.6, inDuration:300,
                outDuration: MV23_GLOBALS.modal.outDuration, startingTop: '2%', endingTop: '5%', 
                ready: function(modal, trigger) { $(trigger).css('z-index','initial'); },
                complete: function(modal, trigger) {
                    var empty_on_close = $(modal).hasClass('empty-on-close');
                    if(empty_on_close) $(modal).find('.modal-content').empty();
                    $('#video-modal .video-responsive').html(''); 
                } 
            });
    
            if( closeOnClick ){
                $(el).find('a').click(function(){
                    $(el).modal('close');
                });
            }
        }

        $('.modal-trigger').modal();
        $('.modal-trigger').css('z-index',25);

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

        var toolbar_middle_buttons = [ "zoomIn", "zoomOut", "toggle1to1" ];
        if( !MV23_GLOBALS.isMobile ) toolbar_middle_buttons.push("rotateCCW","rotateCW","flipX", "flipY");

        var fancybox_options = {
            Hash: false,
            Carousel: {
                infinite: false,
                transition: "classic",
            },
            Toolbar: {
                display: {
                    left: ["infobar"],
                    middle: toolbar_middle_buttons,
                    right: ["fullscreen", "slideshow", "thumbs", "close"],
                },
            },
        };

        Fancybox.bind("[data-fancybox], .zoom", fancybox_options);

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
        // OPEN FANCYBOX GALLERY BY SLUG WITH A LINK: 
        // .show-gallery--{gallery_id} 
        // ****************************************************************************************************

        var prefijo = 'show-gallery--';

        $(document).on('click','a[class*='+prefijo+']',function(event) {
            // event.preventDefault();
            const clases = event.target.classList;

            // Iterar sobre todas las clases
            for (let i = 0; i < clases.length; i++) {
                const clase = clases[i];
                // Verificar si la clase comienza con el prefijo
                if (clase.startsWith(prefijo)) {
                    const gallerySlug = clase.substring(prefijo.length);
                    Fancybox.fromSelector('[data-fancybox="'+gallerySlug+'"]');
                }
            }
        });

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

            $('.disable-link-to-embed-conversion a').addClass('normal-link');

            var links = $('.main').find('a[href*=".pdf"]:not(.normal-link)');
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

            $('.disable-link-to-embed-conversion a').addClass('normal-link');

            var links = $('.main').find('a[href*=".docx"]:not(.normal-link), a[href*=".pptx"]:not(.normal-link), a[href*=".xlsxs"]:not(.normal-link)');
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