jQuery(document).ready(function($) {
    function processNextBatch(button, action, offset) {
        $.ajax({
            url: THEME_MIGRATOR_GLOBALS.ajax_url,
            method: 'POST',
            data: {
                action: 'process_'+action,
                nonce: THEME_MIGRATOR_GLOBALS.nonce,
                offset: offset
            },
            success: function(response) {
                if (response.success) {
                    offset = response.data.offset;

                    console.log( offset, response.data.control );
                    
                    if (!response.data.complete) {
                        processNextBatch( button, action, offset );
                    } else {
                        offset = 0;
                        console.log('Data migration complete', action);
                        afterDataMigration( button, action );
                    }
                } else {
                    button.attr('data-status','failed');
                    console.error('Error in components data migration process', action);
                }
            },
            error: function() {
                button.attr('data-status','failed');
                console.error('processNextBatch() AJAX Error', action);
            }
        });
    }

    function afterDataMigration(button, action) {
        console.log('Updating Database...');
        $.ajax({
            url: THEME_MIGRATOR_GLOBALS.ajax_url,
            method: 'POST',
            data: {
                action: 'after_' + action,
                nonce: THEME_MIGRATOR_GLOBALS.nonce
            },
            success: function(response) {
                if (response.success) {
                    if (response.data.complete) {
                        console.log('Database Updated');
                        button.attr('data-status','complete');
                    }
                } else {
                    button.attr('data-status','failed');
                    console.error('Error updating database');
                }
            },
            error: function() {
                button.attr('data-status','failed');
                console.error('afterDataMigration() AJAX Error');
            }
        });
    }
    
    $('.theme-migrator__init-process').on('click', function(e) {
        e.preventDefault();
        var offset = 0;
        var button = $(this);
        var action = button.data('action');

        // Handle the migration process
        button.attr('data-status','processing');
        console.log('Migrating data...', action);
        processNextBatch(button, action, offset);
    });
});