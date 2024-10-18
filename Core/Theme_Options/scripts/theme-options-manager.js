jQuery(document).ready(function($) {
    const exportButton = $('#export-options');
    const importButton = $('#import-options');
    const importFile = $('#import-file');
    const statusMessage = $('#status-message');

    // Handle export
    exportButton.on('click', function(e) {
        e.preventDefault();
        statusMessage.text('Exporting options...');

        $.ajax({
            url: ajaxurl,  // WordPress global for ajax calls
            method: 'POST',
            data: {
                action: 'export_theme_options',
                nonce: window.theme_options_manager_nonce
            },
            success: function(response) {
                if (response.success) {
                    const blob = new Blob([response.data], { type: "application/json" });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    const project_name = window.project_name.replace(' ','-').toLowerCase();
                    a.download = project_name + '-theme-options.json';
                    document.body.appendChild(a);
                    a.click();
                    statusMessage.text('Options exported successfully.');
                } else {
                    statusMessage.text('Failed to export options.');
                }
            }
        });
    });

    // Handle import
    importButton.on('click', function(e) {
        e.preventDefault();
        const file = importFile[0].files[0];
        if (!file) {
            statusMessage.text('Please select a file.');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const optionsData = e.target.result;
            statusMessage.text('Importing options...');

            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'import_theme_options',
                    nonce: window.theme_options_manager_nonce,
                    options: optionsData
                },
                success: function(response) {
                    if (response.success) {
                        statusMessage.text(response.data);
                    } else {
                        statusMessage.text('Failed to import options.');
                    }
                }
            });
        };
        reader.readAsText(file);
    });
});
