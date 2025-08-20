/**
 * Perform a post action via AJAX.
 * @param {*} action_key post_like, previsualization_count, download_count, etc.
 * @param {*} post_id 
 * @param {*} postcard 
 * @param {*} count_wrapper 
 * @param {*} restrict_in_session if true, check session storage to prevent multiple actions
 * @return void
 */

function do_post_action(action_key, post_id, postcard = null, count_wrapper = null, restrict_in_session = false) {
    const action_session = action_key + '-' + post_id,
        events_detail = { post_id: post_id, postcard: postcard };

    if (restrict_in_session && sessionStorage.getItem(action_session) == '1') {
        let forbidden_event = new CustomEvent(action_key + '_forbidden', { detail: events_detail });
        document.body.dispatchEvent(forbidden_event);
        return;
    }

    jQuery.ajax({
        type: 'POST',
        url: MV23_GLOBALS.ajaxUrl,
        data: {
            action: action_key,
            post_id: post_id,
            nonce: MV23_GLOBALS.nonce
        },
        beforeSend: function () {
            if (count_wrapper) $(count_wrapper).parent().addClass('processing');
            let loading_event = new CustomEvent(action_key + '_loading', { detail: events_detail });
            document.body.dispatchEvent(loading_event);
        },
        success: function (response) {
            $(count_wrapper).parent().removeClass('processing');
            if (response.success) {
                if (count_wrapper) $(count_wrapper).text(response.data);
                if (restrict_in_session) sessionStorage.setItem(action_session, '1');
                let success_event = new CustomEvent(action_key + '_success', { detail: events_detail });
                document.body.dispatchEvent(success_event);
            } else {
                console.log('Error', response.data);
                let error_event = new CustomEvent(action_key + '_error', { detail: events_detail });
                document.body.dispatchEvent(error_event);
            }
        }
    });
}