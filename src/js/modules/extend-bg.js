(function($,c){      
    document.addEventListener('DOMContentLoaded', function() {
        
        function get_container_width_breakpoint() {
            // Get --container-width CSS variable value
            var containerWidth = getComputedStyle(document.documentElement)
                .getPropertyValue('--container-width')
                .trim();
            
            // Parse the value (remove 'px' and convert to number)
            return parseInt(containerWidth) || 1024; // fallback to 1024
        }
        
        function store_original_values(element) {
            var $element = $(element);
            
            // Store original values only if not already stored
            if (!$element.data('extend-bg-initialized')) {
                $element.data('extend-bg-initialized', true);
                $element.data('original-padding-left', $element.css('padding-left'));
                $element.data('original-padding-right', $element.css('padding-right'));
                $element.data('original-margin-left', $element.css('margin-left'));
                $element.data('original-margin-right', $element.css('margin-right'));
                $element.data('original-box-sizing', $element.css('box-sizing'));
            }
        }
        
        function reset_element(element) {
            var $element = $(element);
            
            // Remove inline styles to restore CSS-defined values
            $element.css({
                'width': '',
                'padding-left': $element.data('original-padding-left'),
                'padding-right': $element.data('original-padding-right'),
                'margin-left': $element.data('original-margin-left'),
                'margin-right': $element.data('original-margin-right'),
                'box-sizing': $element.data('original-box-sizing')
            });
        }
        
        function extend_bg(element, key) {
            var $element = $(element);
            
            var browser_side_distance = (key === 'left') ?
                $element.offset().left :
                $(window).width() - ($element.offset().left + $element.width());
            
            // Get current width in pixels for calculation
            var element_width = $element.outerWidth();
            
            // Build CSS object
            var styles = {
                'box-sizing': 'border-box'
            };
            styles['padding-' + key] = browser_side_distance + 'px';
            styles['margin-' + key] = (browser_side_distance * -1) + 'px';
            styles['width'] = 'calc(' + element_width + 'px + ' + browser_side_distance + 'px)';
            
            // Apply styles
            $element.css(styles);
        }
        
        function do_extend_bg() {
            var breakpoint = get_container_width_breakpoint();
            
            // Disable when viewport is smaller than container-width
            if (window.innerWidth < breakpoint) {
                // Reset all elements to original state
                $('.extend-bg-to-left, .extend-bg-to-right').each(function() {
                    if ($(this).data('extend-bg-initialized')) {
                        reset_element(this);
                    }
                });
                return;
            }
            
            // Process left and right extensions
            ['left', 'right'].forEach(function(key) {
                var $extend_elements = $('.extend-bg-to-' + key);
                
                if ($extend_elements.length) {
                    $extend_elements.each(function() {
                        // Store original values on first run
                        store_original_values(this);
                        
                        // Reset before recalculating
                        reset_element(this);
                        
                        // Apply extension
                        extend_bg(this, key);
                    });
                }
            });
        }
        
        // Initial execution
        do_extend_bg();
        
        // Add throttled resize handler using Materialize throttle utility
        if (typeof M !== 'undefined' && M.throttle) {
            var throttledResize = M.throttle(function() {
                do_extend_bg();
            }, 200);
            
            window.addEventListener('resize', throttledResize);
        } else {
            // Fallback if Materialize is not available
            window.addEventListener('resize', do_extend_bg);
        }
        
    });
})(jQuery,console.log);