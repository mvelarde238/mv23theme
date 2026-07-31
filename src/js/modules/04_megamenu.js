window['MegaMenus'] = (function() {
    let instances = [];

    function MegaMenu(el) {
        this.triggerItem = el;
        
        const megamenuId = this.triggerItem.querySelector('a').getAttribute('data-activates');
        if (!megamenuId) return;
        
        this.el = this.triggerItem.closest('.menu-comp').querySelector('#' + megamenuId);
        if (!this.el) return;

        this.header = document.querySelector('.header');
        this.timeout = null;
        this.bodyWidth = document.body.clientWidth;
        this.menu = this.triggerItem.closest('.menu');
        this.orientation = this.menu.classList.contains('horizontal-nav') ? 'horizontal' : 'vertical';

        // Bind all private methods
		for (var fn in this) {
			if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
				this[fn] = this[fn].bind(this);
			}
		}

        this._init();
    }

    MegaMenu.prototype = {
        _init() {
            if ( this.orientation === 'vertical' ) {
                // Todo: Implement vertical megamenu behavior if needed
                return;
            }

            this._setupEventListeners();
            this._handle_keyboard_navigation();
        },

        _setupEventListeners() {
            if ( this.orientation === 'horizontal' ) {
                // hover on any sibling of triggerItem → hide immediately
                const siblings = this.triggerItem.parentElement.children;
                for (let i = 0; i < siblings.length; i++) {
                    if (siblings[i] !== this.triggerItem) {
                        siblings[i].addEventListener('mouseover', this._hideMainMegamenu);
                    }
                }

                this.triggerItem.addEventListener('mouseover', this._handleMouseOver);
                this.triggerItem.addEventListener('mouseleave', this._handleMouseLeave);

                // Click outside of megamenu → hide
                document.addEventListener('click', this._handleDocumentClick);
            }

            // Click on close button → hide
            const closeButtons = this.el.querySelectorAll('.megamenu-close');
            for (let i = 0; i < closeButtons.length; i++) {
                closeButtons[i].addEventListener('click', this._handleCloseClick);
            }
        },

        _handleMouseOver(event) {
            clearTimeout(this.timeout);
            const target = event.currentTarget;

            this.timeout = setTimeout(() => {
                this._showMegamenu();
            }, 250);
        },

        _handleMouseLeave() {
            clearTimeout(this.timeout);
        },

        _showMegamenu() {
            // Disable Scrolling
            document.body.style.width = this.bodyWidth + 'px';
            if (this.header) {
                this.header.style.width = this.bodyWidth + 'px';
            }

            // Remove active class from all megamenus
            const allMegamenus = this.el.querySelectorAll('.megamenu');
            for (let i = 0; i < allMegamenus.length; i++) {
                allMegamenus[i].classList.remove('is-active');
            }

            // Add active class to trigger and corresponding megamenu
            this.triggerItem.classList.add('is-active');
            this.el.classList.add('is-active');
        },

        _hideMainMegamenu() {
            // Re-enable scrolling
            document.body.style.width = '';
            
            if (this.header) {
                this.header.style.width = '';
            }

            // Remove active classes
            this.triggerItem.classList.remove('is-active');
            this.el.classList.remove('is-active');
        },

        _handleCloseClick() {
            // return focus to the trigger item
            const triggerLink = this.triggerItem.querySelector('a');
            if (triggerLink) {
                triggerLink.focus();
            }

            this._hideMainMegamenu();
        },

        _handleDocumentClick(event) {
            const target = event.target;
            if (!target.closest('.has-megamenu') && !target.closest('.megamenu')) {
                this._hideMainMegamenu();
            }
        },

        // ***************************************
        // Accessibility and Keyboard Navigation
        // ***************************************

        _handle_keyboard_navigation(){
            this.triggerItem.addEventListener('keydown', (event) => {
                const target = event.target;
                this._handle_trigger_keydown(event, target);
            });

            this.el.addEventListener('keydown', (event) => {
                const target = event.target;
                this._handle_megamenu_keydown(event, target);
            });
        },
        /**
         * Handles 
         */
        _handle_trigger_keydown(event, target) {
            const menubar = target.closest('[role="menubar"]');
            const isVertical = this.orientation === 'vertical';

            // In vertical menus, ArrowUp/Down move between items (handled by theme navbar); ArrowRight (should?) opens megamenu
            // In horizontal menus, ArrowLeft/Right move between items (handled by theme navbar); ArrowDown opens megamenu
            const nextKey = isVertical ? 'ArrowDown' : 'ArrowRight';
            const prevKey = isVertical ? 'ArrowUp' : 'ArrowLeft';
            const openKey = isVertical ? 'ArrowRight' : 'ArrowDown';
            const openKeyReverse = isVertical ? null : 'ArrowUp';

            switch(event.key) {
                case openKey:
                    event.preventDefault();
                    this._showMegamenu();
                    const firstFocusable = this.el.querySelector('a, button, input, [tabindex]:not([tabindex="-1"])');
                    if (firstFocusable) {
                        firstFocusable.focus();
                    }
                    break;
            }
        },
        _handle_megamenu_keydown(event, target) {
            const megamenu = this.el;

            switch(event.key) {
                case 'Escape':
                    event.preventDefault();

                    if(target.closest('.menu-item')) return;

                    this._hideMainMegamenu();

                    const triggerLink = this.triggerItem.querySelector('a');
                    if (triggerLink) {
                        triggerLink.focus();
                    }
                    break;
                
                case 'Tab':
                    const focusableElements = megamenu.querySelectorAll('a, button, input, [tabindex]:not([tabindex="-1"])');

                    // If Shift+Tab is pressed and the focus is on the first focusable element, hide the megamenu
                    if (event.shiftKey) {
                        const firstFocusable = focusableElements[0];
                        if (firstFocusable && target === firstFocusable) {
                            this._hideMainMegamenu();
                        }
                    } else {
                        // If Tab is pressed and the focus is on the last focusable element, hide the megamenu and return focus to the trigger item
                        const lastFocusable = focusableElements[focusableElements.length - 1];
                        if (lastFocusable && target === lastFocusable) {
                            this._hideMainMegamenu();
                            this.triggerItem.querySelector('a').focus();
                        }
                    }
                    break;
            }
        }
    };

    MegaMenu.create = function(el) {
        const instance = new MegaMenu(el);
        if (instance.el) {
            instances.push(instance);
        }
        return instance;
    };

    MegaMenu.init = function() {
        const megamenuContainers = document.querySelectorAll('.has-megamenu');
        for (let i = 0; i < megamenuContainers.length; i++) {
            MegaMenu.create(megamenuContainers[i]);
        }
        return instances;
    };

    MegaMenu.getInstances = function() {
        return instances;
    };

    MegaMenu.hideAll = function() {
        for (let i = 0; i < instances.length; i++) {
            instances[i]._hideMainMegamenu();
        }
    };

    return MegaMenu;
})();

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    MegaMenus.init();
});