(function ($, c) {
    document.addEventListener('DOMContentLoaded', function () {

        $(document).on('click', '.like-count-js', function () {
            let action_key = 'post_like',
                post_id = null,
                postcard = null,
                count_wrapper = null;

            if (MV23_GLOBALS.isSingle) {
                post_id = MV23_GLOBALS.pageID;
                count_wrapper = $('.post-likes-count');
            } else { // is postcard
                postcard = $(this).closest('.postcard');
                post_id = postcard.data('id');
                count_wrapper = postcard.find('.post-likes-count');
            }
            do_post_action(action_key, post_id, postcard, count_wrapper);
        });


        $(document).on('click', '.previsualization-count-js', function() {
            let action_key = 'previsualization_count',
                post_id = null,
                postcard = null,
                count_wrapper = null;

            if( MV23_GLOBALS.isSingle ){
                post_id = MV23_GLOBALS.pageID;
                count_wrapper = $('.post-previsualization-count');
            } else{ // is postcard
                postcard = $(this).closest('.postcard');
                post_id = postcard.data('id');
                count_wrapper = postcard.find('.post-previsualization-count');
            }
            do_post_action(action_key, post_id, postcard, count_wrapper);
        });


        $(document).on('click', '.download-count-js', function() {
            let action_key = 'download_count',
                post_id = null,
                postcard = null,
                count_wrapper = null;

            if( MV23_GLOBALS.isSingle ){
                post_id = MV23_GLOBALS.pageID;
                count_wrapper = $('.post-download-count');
            } else{ // is postcard
                postcard = $(this).closest('.postcard');
                post_id = postcard.data('id');
                count_wrapper = postcard.find('.post-download-count');
            }
            do_post_action(action_key, post_id, postcard, count_wrapper, true);
        });

    });
})(jQuery, console.log); 