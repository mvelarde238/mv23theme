jQuery(document).ready(function($) {
    var offset = 0;
    var $migration_btn = null;

    function processNextBatch() {
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'process_page_data',
                nonce: THEME_MIGRATOR_GLOBALS.nonce,
                offset: offset
            },
            success: function(response) {
                if (response.success) {
                    offset = response.data.offset;

                    console.log( response.data.processed+' pages was migrated', response.data.control );
                    
                    if (!response.data.complete) {
                        processNextBatch();
                    } else {
                        offset = 0;
                        console.log('Migration Complete');
                        deleteOrphanedPostMeta();
                    }
                } else {
                    $migration_btn.attr('data-status','failed');
                    console.error('Error in the migration process');
                }
            },
            error: function() {
                $migration_btn.attr('data-status','failed');
                console.error('processNextBatch() AJAX Error');
            }
        });
    }

    function deleteOrphanedPostMeta() {
        console.log('Deleting orphaned postmeta...');
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'delete_orphaned_postmeta',
                nonce: THEME_MIGRATOR_GLOBALS.nonce
            },
            success: function(response) {
                if (response.success) {
                    if (response.data.complete) {
                        console.log('Orphaned postmeta deleted');
                        $migration_btn.attr('data-status','complete');
                    }
                } else {
                    $migration_btn.attr('data-status','failed');
                    console.error('Error deleting orphaned postmeta');
                }
            },
            error: function() {
                $migration_btn.attr('data-status','failed');
                console.error('deleteOrphanedPostMeta() AJAX Error');
            }
        });
    }

    // Init the process
    $(document).on('click', '.theme-migrator__init[data-status="initial"]', function(){
        $migration_btn = $(this);
        $migration_btn.attr('data-status','processing');
        processNextBatch();
    });
});
