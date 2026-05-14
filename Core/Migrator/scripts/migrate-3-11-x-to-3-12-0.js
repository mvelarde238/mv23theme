jQuery(document).ready(function($) {
    var $migration_btn = null;

    $(document).on('click', '.theme-migrator__init-3-12-0[data-status="initial"]', function() {
        $migration_btn = $(this);
        $migration_btn.attr('data-status', 'processing');
        console.log('[3.12.0] Splitting typography_css_vars...');

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'process_typography_css_vars_split',
                nonce: THEME_MIGRATOR_GLOBALS.nonce
            },
            success: function(response) {
                if (response.success) {
                    console.log('[3.12.0] Result:', response.data.control);
                    if (response.data.message) {
                        console.warn('[3.12.0]', response.data.message);
                    }
                    if (response.data.complete) {
                        console.log('[3.12.0] Database updated successfully.');
                        $migration_btn.attr('data-status', 'complete');
                    }
                } else {
                    $migration_btn.attr('data-status', 'failed');
                    console.error('[3.12.0] Migration failed:', response);
                }
            },
            error: function(xhr, status, error) {
                $migration_btn.attr('data-status', 'failed');
                console.error('[3.12.0] AJAX error:', status, error);
            }
        });
    });
});
