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
        
        function do_extend_bg() {
            var breakpoint = get_container_width_breakpoint();
            
            // Disable when viewport is smaller than container-width
            if (window.innerWidth < breakpoint) {
                $('.extend-bg-to-left, .extend-bg-to-right').each(function() {
                    if ($(this).data('extend-bg-initialized')) {
                        reset_element(this);
                    }
                });
                return;
            }
            
            // Process each element once, handling both sides together
            $('.extend-bg-to-left, .extend-bg-to-right').each(function() {
                var $element = $(this);
                var hasLeft  = $element.hasClass('extend-bg-to-left');
                var hasRight = $element.hasClass('extend-bg-to-right');
                
                store_original_values(this);
                
                // Reset once before measuring to get accurate natural positions
                reset_element(this);
                
                var leftDist    = hasLeft  ? $element.offset().left : 0;
                var rightDist   = hasRight ? $(window).width() - ($element.offset().left + $element.outerWidth()) : 0;
                var elementWidth = $element.outerWidth();
                
                // Build all styles in a single pass
                var styles = {
                    'box-sizing': 'border-box',
                    'width': 'calc(' + elementWidth + 'px + ' + leftDist + 'px + ' + rightDist + 'px)'
                };
                
                if (hasLeft) {
                    styles['padding-left'] = leftDist + 'px';
                    styles['margin-left']  = (leftDist * -1) + 'px';
                }
                if (hasRight) {
                    styles['padding-right'] = rightDist + 'px';
                    styles['margin-right']  = (rightDist * -1) + 'px';
                }
                
                $element.css(styles);
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