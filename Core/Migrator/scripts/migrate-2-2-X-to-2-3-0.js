jQuery(document).ready(function($) {
    var $migration_btn = null;

    // Init the process
    $(document).on('click', '.theme-migrator__init-2-3-0[data-status="initial"]', function(){
        $migration_btn = $(this);
        $migration_btn.attr('data-status','processing');
        console.log('Migrating theme options...');
        
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'process_typography_options',
                nonce: THEME_MIGRATOR_GLOBALS.nonce
            },
            success: function(response) {
                if (response.success) {
                    console.log(response.data.control);
                    
                    if (response.data.complete) {
                        console.log('Database Updated');
                        $migration_btn.attr('data-status','complete');
                    }
                } else {
                    $migration_btn.attr('data-status','failed');
                    console.error('Error in data migration process');
                }
            },
            error: function() {
                $migration_btn.attr('data-status','failed');
                console.error('AJAX Error');
            }
        });
    });
});
