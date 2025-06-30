jQuery(document).ready(function($) {
    var offset = 0;
    var $migration_btn = null;

    function processNextBatch() {
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'process_new_video_settings',
                nonce: THEME_MIGRATOR_GLOBALS.nonce,
                offset: offset
            },
            success: function(response) {
                if (response.success) {
                    offset = response.data.offset;

                    console.log( offset, response.data.control );
                    
                    if (!response.data.complete) {
                        processNextBatch();
                    } else {
                        offset = 0;
                        console.log('Data migration complete');
                        afterDataMigration();
                    }
                } else {
                    $migration_btn.attr('data-status','failed');
                    console.error('Error in components data migration process');
                }
            },
            error: function() {
                $migration_btn.attr('data-status','failed');
                console.error('processNextBatch() AJAX Error');
            }
        });
    }

    function afterDataMigration() {
        console.log('Updating Database...');
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'after_new_video_settings',
                nonce: THEME_MIGRATOR_GLOBALS.nonce
            },
            success: function(response) {
                if (response.success) {
                    if (response.data.complete) {
                        console.log('Database Updated');
                        $migration_btn.attr('data-status','complete');
                    }
                } else {
                    $migration_btn.attr('data-status','failed');
                    console.error('Error updating database');
                }
            },
            error: function() {
                $migration_btn.attr('data-status','failed');
                console.error('afterDataMigration() AJAX Error');
            }
        });
    }

    // Init the process
    $(document).on('click', '.theme-migrator__init-new-video-settings[data-status="initial"]', function(){
        $migration_btn = $(this);
        $migration_btn.attr('data-status','processing');
        console.log('Migrating Video Settings data...');
        processNextBatch();
    });
});
