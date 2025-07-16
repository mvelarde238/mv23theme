document.addEventListener('DOMContentLoaded', function() {
    var event = new CustomEvent('theme_document_ready');
    setTimeout(function() {
        document.body.dispatchEvent(event);
    }, 1);
});