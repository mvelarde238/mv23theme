(function($,c){      
    $(function() {
        // var header_height = $('.header__content').height();
        var header_height = 118;

        // ****************************************************************************************************
        // INIT MATERIALIZE SCRIPTS
        // ****************************************************************************************************

        // $(".iframe").colorbox({iframe:true, width:"80%", height:"80%"}); 
        // $('.dropdown-button').dropdown({constrainWidth: false, alignment: 'right', hover: true});
        $('.modal').modal({ dismissible:true,  opacity:.6,  inDuration:300,  outDuration:200, startingTop: '2%', endingTop: '5%', 
            ready: function(modal, trigger) { $(trigger).css('z-index','initial'); },
            complete: function(modal, trigger) { $('#video-modal__video').trigger('pause').empty(); } 
        });
        $('.modal-trigger').modal();
        // $('select').material_select();
        // $('.carousel').carousel();

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
                options = { headerHeight : 118 };

            if (el.dataset.desktoptemplate != undefined) options.desktopTemplate = el.dataset.desktoptemplate; 
            if (el.dataset.moviltemplate != undefined) options.movilTemplate = el.dataset.moviltemplate;

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
        // Mostrar el formulario de registro o login si hay errores
        // ****************************************************************************************************

        if ($_GET['instance']) {
            switch ($_GET['instance']){
                case '1': // Formulario de login, mostrar megamenu abierto:
                    $('#menu-item-22 .megamenu-link').addClass('is-active');
                    $('.v23-togglebox__btn[data-boxid="#login-panel"]').click();
                    break;
                case '2': // Formulario de registro, mostrar megamenu abierto:
                    $('#menu-item-22 .megamenu-link').addClass('is-active');
                    $('.v23-togglebox__btn[data-boxid="#register-panel"]').click();
                    break;
                // case '3':
                    // $('#notify-availability-modal').modal('open');
                    // break;
            }
        }

        // ****************************************************************************************************
        // ****************************************************************************************************

        $(document).on("click", ".js-procesando", function(ev) {
            // ev.preventDefault();
            $('.procesando-modal').css('display','flex');
        });
        
        // ****************************************************************************************************
        // ****************************************************************************************************

        // $('body').on('click', '.zoom', function(ev){
        //     ev.preventDefault();
        //     $('#image-modal').modal('open');
        //     var fondo = $(this).attr('href'); 
        //     $('.image-modal__box').attr('style','background-image: url('+fondo+')');
        // });

        function colorbox_group(){
            $(".zoom").colorbox({
                rel:'group1', 
                maxHeight:"96%", 
                maxWidth: "96%",
                onComplete: function(){
                    // $('#cboxLoadedContent').zoom({ on:'click' });
                }
            });
        }
        colorbox_group();

        // ****************************************************************************************************
        // ****************************************************************************************************

        $('.cover-all').parent().css('position','relative');

        // ****************************************************************************************************
        // ****************************************************************************************************

        if (viewport.width > 768) {
            $('.pinned-block').each(function() {
                var $this = $(this);
                var $target = $this.parent();
    
                $this.css('width',$target.css('width'));
    
                $this.pushpin({
                    top: $target.offset().top,
                    bottom: $target.offset().top + $target.outerHeight() - $this.height(),
                    offset: header_height
                });
            });
        }

        // ****************************************************************************************************
        // ****************************************************************************************************
    });
})(jQuery,console.log);