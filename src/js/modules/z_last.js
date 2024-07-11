document.addEventListener('DOMContentLoaded', function() {
    var event = new Event('theme_document_ready');
    document.body.dispatchEvent(event);
});