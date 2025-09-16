(function($) {
    $(document).ready(function() {
        // Restore WordPress media toolbar functionality
        if (wp && wp.media && wp.media.view && wp.media.view.Toolbar) {
            var originalGet = wp.media.view.Toolbar.prototype.get;
            wp.media.view.Toolbar.prototype.get = function(id) {
                var item = originalGet.call(this, id);
                // Ensure the show method exists
                if (item && typeof item.show !== 'function') {
                    item.show = function() {
                        this.$el.show();
                        return this;
                    };
                }
                return item;
            };
        }
    });
})(jQuery);