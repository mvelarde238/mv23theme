(function ($, c) {
    $(function () {
        // Subscribe to Continue implementation
        $('body').on('click', '[href*="&action=subscribe-to"]', function (e) {
            e.preventDefault();

            if ( !MV23_GLOBALS.postsSubscription ) return;

            const button = $(this),
                href = button.attr('href'),
                urlParams = new URLSearchParams(href.split('?')[1]),
                dataId = urlParams.get('id'),
                dataAction = urlParams.get('action');

            if (!dataId) return;

            $.ajax({
                url: MV23_GLOBALS.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'check_subscription_to_continue',
                    data_id: dataId,
                    data_action: dataAction
                },
                beforeSend: function () {
                    button.addClass('processing');
                },
                success: function (response) {
                    button.removeClass('processing');
                    var evt = document.createEvent("CustomEvent");
                    evt.initCustomEvent("stc_continue", true, true, response);

                    if (response.success) {
                        document.dispatchEvent(evt);
                    } else {
                        switch (response.errorType) {
                            case 'subjectCantContinue':
                                const subscriptionPopUp = M.Modal.getInstance(document.getElementById('stc-modal'));
                                subscriptionPopUp.$el.find('input[name=data-id]').val(dataId);
                                subscriptionPopUp.$el.find('input[name=data-action]').val(dataAction);
                                subscriptionPopUp.open();
                                break;

                            default:
                                const errorType = (response.errorType) ? response.errorType : 'unknown';
                                console.log('There was an error. Please try again. ERROR CODE: ' + errorType);
                                document.dispatchEvent(evt);
                                break;
                        }
                    }
                },
                error: function () {
                    alert('There was an error. Please try again. ERROR CODE: networkError');
                },
            });
        });

        document.addEventListener('wpcf7mailsent', function (event) {
            if (event.detail.apiResponse.hasOwnProperty('subscriptionIsOk')) {

                if (event.detail.apiResponse.success){
                    const subscriptionPopUp = M.Modal.getInstance(document.getElementById('stc-modal'));
                    subscriptionPopUp.close();
                }

                var evt = document.createEvent("CustomEvent");
                evt.initCustomEvent("stc_continue", true, true, event.detail.apiResponse);
                document.dispatchEvent(evt);
            }
        }, false);

        // SUBSCRIBE TO PREVIEW IMPLEMENTATION
        document.addEventListener("stc_continue", function(evt) {
            const response = evt.detail;
            if( response.data_action != 'subscribe-to-preview' ) return;

            if (response.success) {
                if(MV23_GLOBALS.trackPostsData) do_post_action('previsualization_count', response.data_id);

                if(response.file_url){
                    new Fancybox([
                            { src: response.file_url }
                        ],
                        {
                            hideScrollbar: true,
                            Toolbar: {
                                display: {
                                    left: ["infobar"],
                                    middle: [ "zoomIn", "zoomOut", "toggle1to1" ],
                                    right: ["fullscreen", "slideshow", "thumbs", "close"],
                                },
                            },
                        }
                    );
                }
            } else {
                switch (response.errorType) {
                    default:
                        const errorType = (response.errorType) ? response.errorType : 'unknown';
                        alert('There was an error. Please try again. ERROR CODE: '+errorType);
                        break;
                }
            }
        }, false);

        // SUBSCRIBE TO DOWNLOAD IMPLEMENTATION
        document.addEventListener("stc_continue", function(evt) {
            const response = evt.detail;
            if( response.data_action != 'subscribe-to-download' ) return;

            if (response.success) {
                if(MV23_GLOBALS.trackPostsData) do_post_action('download_count', response.data_id);
                if(response.file_url) window.location.href = response.file_url+'&nonce='+ cf7_nonce_data.nonce;
            } else {
                switch (response.errorType) {
                    default:
                        const errorType = (response.errorType) ? response.errorType : 'unknown';
                        alert('There was an error. Please try again. ERROR CODE: '+errorType);
                        break;
                }
            }
        }, false);

        // SUBSCRIBE TO REDIRECT IMPLEMENTATION
        document.addEventListener("stc_continue", function(evt) {
            const response = evt.detail;
            if( response.data_action != 'subscribe-to-redirect' ) return;

            if (response.success) {
                if(response.redirect_url) window.location.href = response.redirect_url;
            } else {
                switch (response.errorType) {
                    default:
                        const errorType = (response.errorType) ? response.errorType : 'unknown';
                        alert('There was an error. Please try again. ERROR CODE: '+errorType);
                        break;
                }
            }
        }, false);

    });
})(jQuery, console.log);