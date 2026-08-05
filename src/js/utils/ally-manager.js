window['AllyManager'] = (function() {

    const AllyManager = {}

    // Tracks the ally.js handles created per panel so they can all be disengaged together
    const disclosureHandles = new WeakMap();

    // Disengages and forgets any ally.js handles previously registered for this panel
    function disengageHandles(panel) {
        const handles = disclosureHandles.get(panel);
        if (handles) {
            Object.values(handles).forEach(handle => handle && handle.disengage && handle.disengage());
            disclosureHandles.delete(panel);
        }
    }

    /**
     * Handle disclosure click event
     * @param {Object} opt - Options object
     * @param {HTMLElement} opt.trigger - The trigger element
     * @param {HTMLElement} opt.panel - The panel element
     * @param {HTMLElement[]} [opt.allTriggers] - Optional array of all trigger elements
     * @param {Function} [opt.onStart] - Optional callback function before processing the panel
     * @param {Function} [opt.onEnd] - Optional callback function after processing the panel
     */
    AllyManager.handleDisclosureTriggerClick = function( opt = {} ) {
        const { trigger, panel } = opt;
        if (!trigger || !panel) return;

        trigger.addEventListener('click', function(e) {
            e.preventDefault();

            const isExpanded = trigger.getAttribute('aria-expanded') === 'true';

            if (opt.onStart) opt.onStart(isExpanded);

            if( opt.allTriggers && opt.allTriggers.length ){
                opt.allTriggers.forEach( _trigger => _trigger.setAttribute('aria-expanded', 'false'));
            }

            trigger.setAttribute('aria-expanded', String(!isExpanded));
            panel.hidden = isExpanded;

            if (opt.onEnd) opt.onEnd(!isExpanded);
        });
    };

    /**
     * Activate disclosure by focusing the first tabbable element in the panel
     * @param {Object} opt - Options object
     * @param {HTMLElement} opt.panel - The panel element
     * @param {HTMLElement} opt.trigger - The trigger element
     */
    AllyManager.activateDisclosure = function( opt = {} ) {
        const { panel, trigger } = opt;
        if (!panel || !trigger) return;

        // Reusing the same panel for a different trigger without closing first would
        // otherwise stack duplicate key/focus-trap listeners on top of the old ones
        disengageHandles(panel);

        // Focus the first tabbable element in the panel when it becomes visible
        const visibleAreaHandle = ally.when.visibleArea({
            context: panel,
            callback: function(element) {
                var firstTabbable = ally.query.firstTabbable({
                    context: panel,
                    defaultToContext: true,
                });
                if (firstTabbable) {
                    firstTabbable.focus();
                }
            },
        });

        const keyHandle = ally.when.key({
            context: panel,
            // Close the panel when Escape key is pressed
            escape: function(event, disengage) {
                AllyManager.closeDisclosure({ trigger, panel });
                disengage(); // Stop listening for Escape key
            },
            // Close the panel when Shift+Tab is pressed on the first tabbable element
            ['shift+tab']: function(event, disengage) {
                const firstTabbable = ally.query.firstTabbable({
                    context: panel,
                    defaultToContext: true,
                });
                if (firstTabbable && event.target === firstTabbable) {
                    event.preventDefault();
                    AllyManager.closeDisclosure({ trigger, panel });
                    disengage();
                }
            },
        });

        const tabFocusHandle = ally.maintain.tabFocus({
            context: panel,
        });

        disclosureHandles.set(panel, { visibleAreaHandle, keyHandle, tabFocusHandle });
    };

    /**
     * Close disclosure and return focus to the trigger
     * @param {Object} opt - Options object
     * @param {HTMLElement} opt.trigger - The trigger element
     * @param {HTMLElement} opt.panel - The panel element
     */
    AllyManager.closeDisclosure = function( opt = {} ) {
        const { trigger, panel } = opt;
        if (!trigger || !panel) return;

        trigger.focus();
        trigger.setAttribute('aria-expanded', 'false');
        panel.hidden = true;

        disengageHandles(panel);
    };

    /**
     * Set aria-labelledby attribute on the panel element
     * @param {Object} opt - Options object
     * @param {HTMLElement} opt.panel - The panel element
     * @param {HTMLElement} opt.title - The title element to reference
     */
    AllyManager.setAriaLabelledBy = function(opt = {}) {
        const { panel, title } = opt;
        if (!panel || !title) return;

        if (!title.id) {
            title.id = 'title-' + Math.floor(Math.random() * 1000000);
        }
        panel.setAttribute('aria-labelledby', title.id);
    };

    return AllyManager;
})();