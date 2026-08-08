window['Disclosure'] = (function(){
    let instances = [];

    function Disclosure( opt ){
        this.options = opt;

        const { panel, trigger } = this.options;
        if (!panel && !trigger) return;

        // Bind all private methods
		for (var fn in this) {
			if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
				this[fn] = this[fn].bind(this);
			}
		}

        this._init( opt );
    }
    
    Disclosure.prototype = {
        _init(){
            this._handleEventListener();
        },
        _handleEventListener(){
            const { trigger, panel } = this.options;

            trigger.addEventListener('click', (e)=>{
                e.preventDefault();

                const isExpanded = panel.getAttribute('hidden') === 'false' || panel.hidden === false;

                if( !isExpanded ) this._openDisclosure();
                if( isExpanded ) this._closeDisclosure();
            });
        },
        /**
         * Open disclosure and optionally focus the first tabbable element in the panel and/or trap focus within the panel
         */
        _openDisclosure(){
            const { trigger, panel, focusFirstTabbable, tabFocusTrap, allTriggers } = this.options;
        
            if( this.options.onOpenStart ) this.options.onOpenStart();
        
            if( allTriggers && Array.isArray(allTriggers) ) {
                allTriggers.forEach(t => {
                    if (t !== trigger) {
                        t.setAttribute('aria-expanded', 'false');
                    } else {
                        t.setAttribute('aria-expanded', 'true');
                    }
                });
            }
        
            panel.hidden = false;
        
            this._handleKeyboardEvents();

            if (focusFirstTabbable) this._focusFirstTabbable();
            if (tabFocusTrap) this._activateTabFocusTrap();
        
            if( this.options.onOpenEnd ) this.options.onOpenEnd();
        },
        /**
         * Close disclosure and return focus to the trigger
         */
        _closeDisclosure() {
            const { trigger, panel } = this.options;

            if( this.options.onCloseStart ) this.options.onCloseStart();

            if( this.tabFocusHandle ) {
                this.tabFocusHandle.disengage();
                this.tabFocusHandle = null;
            }
            if( this.keyHandle ) {
                this.keyHandle.disengage();
                this.keyHandle = null;
            }

            trigger.focus();
            trigger.setAttribute('aria-expanded', 'false');
            panel.hidden = true;

            if( this.options.onCloseEnd ) this.options.onCloseEnd();
        },
        _handleKeyboardEvents(){
            const { trigger, panel } = this.options;

            this.keyHandle = ally.when.key({
                context: panel,
                // Handle Escape key to close the disclosure
                escape: (event) => {
                    event.preventDefault();
                    this._closeDisclosure();
                },
                // Close the panel when Shift+Tab is pressed on the first tabbable element
                ['shift+tab']: (event, disengage) => {
                    const firstTabbable = ally.query.firstTabbable({
                        context: panel,
                        defaultToContext: true,
                    });
                    if (firstTabbable && event.target === firstTabbable) {
                        event.preventDefault();
                        this._closeDisclosure();
                    }
                },
            });
        },
        _focusFirstTabbable(){
            const { panel } = this.options;

            var firstTabbable = ally.query.firstTabbable({
                context: panel,
                defaultToContext: true
            });

            if (firstTabbable) {
                firstTabbable.focus();
            }
        },
        _activateTabFocusTrap(){
            const { panel } = this.options;

            this.tabFocusHandle = ally.maintain.tabFocus({
                context: panel
            });
        }
    };

    /**
     * Activate disclosure behavior for a trigger and panel
     * @param {Object} opt - Options object
     * @param {HTMLElement} opt.trigger - The trigger element
     * @param {HTMLElement} opt.panel - The panel element
     * @param {HTMLElement[]} [opt.allTriggers] - Optional array of all trigger elements
     * @param {boolean} [opt.focusFirstTabbable=false] - Whether to focus the first tabbable element in the panel
     * @param {boolean} [opt.tabFocusTrap=false] - Whether to trap focus within the panel when it's open
     */
    Disclosure.create = function( opt ){
        let instance = new Disclosure(opt);
        instances.push(instance);
		return instance;
    }

    /**
     * Close the disclosure associated with a specific trigger
     * @param {HTMLElement} trigger - The trigger element associated with the disclosure to close
     */
    Disclosure.close = function( trigger ){
        const instance = Disclosure.getInstance(trigger);
        if (instance) {
            instance._closeDisclosure();
        }
    }

    Disclosure.getInstances = function(){
        return instances;
    }

    Disclosure.getInstance = function( trigger ){
        return instances.find(inst => inst.options.trigger === trigger);
    }

    return Disclosure;
})();