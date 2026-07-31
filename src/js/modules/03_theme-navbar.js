window['Theme_Navbars'] = (function(){
    let instances = [],
        sub_menu_width = 180,
        menu_breakpoint = MV23_GLOBALS.menu_breakpoint,
        liveRegion = null;

    function Theme_Navbar( el ){
        this.el = el;
        this.menu = this.el.querySelector('.menu');
        this.focusedItem = null;
        this.orientation = this.menu.classList.contains('horizontal-nav') ? 'horizontal' : 'vertical';
    
        // Bind all private methods
		for (var fn in this) {
			if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
				this[fn] = this[fn].bind(this);
			}
		}

        this._init();
    }
    
    Theme_Navbar.prototype = {
        _init(){
            // Create live region for screen reader announcements
            if (!liveRegion) { 
                liveRegion = this._create_live_region();
            }
        
            if ( this.orientation === 'horizontal' ) {
                this._handle_menu_item_with_children_mouseover();
            }
        
            if (this.orientation === 'vertical') {
                this._center_toggle_buttons();
                window.addEventListener('resize', this._center_toggle_buttons);
            }

            this._handle_toggle_submenu_button();
            this._handle_keyboard_navigation();
        },
        _handle_menu_item_with_children_mouseover(){
            let has_children = this.menu.querySelectorAll('li.menu-item-has-children');

            for (let i = 0; i < has_children.length; i++) {
                const item_has_children = has_children[i];

                item_has_children.addEventListener('mouseover', (event)=>{
                    this._handle_mouse_event( event, item_has_children, this._show_submenu );
                });
                item_has_children.addEventListener('mouseleave', (event)=>{
                    this._handle_mouse_event( event, item_has_children, this._hide_submenu );
                });
            }
        },
        _handle_mouse_event( event, item_has_children, callback ){
            const viewport = updateViewportDimensions();
            
            if( viewport.width < menu_breakpoint ) {
                event.stopPropagation();
                event.preventDefault();
                return;
            }
            callback.call(this, item_has_children);
        },
        _show_submenu( item_has_children ){
            let rect = item_has_children.getBoundingClientRect();
            let outOfBoundsOnX = ( document.body.clientWidth - (rect.x + sub_menu_width) ) < sub_menu_width;
            let openFromClass = (outOfBoundsOnX) ? 'open-from-right' : 'open-from-left';
            let sub_menu = item_has_children.querySelector(':scope > .sub-menu');
            sub_menu.classList.add(openFromClass);
            sub_menu.style.display = 'block';
            
            // Update aria-expanded
            const toggleBtn = item_has_children.querySelector(':scope > .toggle-submenu');
            if (toggleBtn) {
                toggleBtn.setAttribute('aria-expanded', 'true');
            }
            this._center_toggle_buttons();
        },
        _hide_submenu( item_has_children ){
            let sub_menus = item_has_children.querySelectorAll('.sub-menu');
            for (let i = 0; i < sub_menus.length; i++) {
                const sub_menu = sub_menus[i];
                sub_menu.classList.remove('open-from-right');
                sub_menu.classList.remove('open-from-left');
                sub_menu.style.display = 'none';
            }

            let toggle_submenu_buttons = item_has_children.querySelectorAll('.toggle-submenu');
            for (let i = 0; i < toggle_submenu_buttons.length; i++) {
                const button = toggle_submenu_buttons[i];
                button.setAttribute('aria-expanded', 'false');
            }
        },
        _center_toggle_buttons(){
            const items = this.menu.querySelectorAll('li:has(> .sub-menu)');
            for (let i = 0; i < items.length; i++) {
                const link = items[i].querySelector(':scope > a');
                const btn = items[i].querySelector(':scope > .toggle-submenu');
                if (link && btn && link.offsetHeight > 0) {
                    btn.style.top = (link.offsetHeight - btn.offsetHeight) / 2 + 'px';
                }
            }
        },
        _handle_toggle_submenu_button(){
            let has_children = this.menu.querySelectorAll('li.menu-item-has-children');
            for (let i = 0; i < has_children.length; i++) {
                const item_has_children = has_children[i];
                let toggle_submenu_button = item_has_children.querySelector(':scope > .toggle-submenu');

                if( ! toggle_submenu_button ) continue;

                toggle_submenu_button.addEventListener('click', (event)=>{
                    event.stopPropagation();
                    event.preventDefault();
                    const expanded = toggle_submenu_button.getAttribute('aria-expanded') === 'true' || false;
                    if( expanded ){
                        this._hide_submenu(item_has_children);
                    } else {
                        this._show_submenu(item_has_children);
                        // Focus first item in submenu
                        const firstItem = item_has_children.querySelector('.sub-menu a[role="menuitem"]');
                        if (firstItem) firstItem.focus();
                    }
                });
            }
        },

        // ***************************************
        // Accessibility and Keyboard Navigation
        // ***************************************

        /**
         * Handles keyboard navigation for the entire menu, including top-level menubar items and submenu items.
         * This method listens for keydown events and delegates to the appropriate handler based on the target element.
         */
        _handle_keyboard_navigation(){
            this.menu.addEventListener('keydown', (event) => {
                const target = event.target;
                const key = event.key;
                const isMenubar = target.closest('[role="menubar"]');
                const isMenu = target.closest('[role="menu"]');
                
                // Handle toggle buttons
                if (target.classList.contains('toggle-submenu')) {
                    this._handle_toggle_keydown(event, target);
                }
                // Handle submenu level navigation (check before menubar since submenus are inside menubar)
                else if (isMenu && target.getAttribute('role') === 'menuitem') {
                    this._handle_menu_keydown(event, target);
                }
                // Handle menubar level navigation
                else if (isMenubar && target.getAttribute('role') === 'menuitem') {
                    this._handle_menubar_keydown(event, target);
                }
            });
        },

        /**
         * Handles keyboard navigation for top-level menubar items.
         */
        _handle_menubar_keydown(event, target) {
            const menubar = this.menu;
            const topLevelItems = Array.from(menubar.querySelectorAll(':scope > li[role="none"] > a[role="menuitem"]'));
            const currentIndex = topLevelItems.indexOf(target);
            const isVertical = this.orientation === 'vertical';

            // In vertical menus, ArrowUp/Down move between items; ArrowRight opens submenu
            // In horizontal menus, ArrowLeft/Right move between items; ArrowDown opens submenu
            const nextKey = isVertical ? 'ArrowDown' : 'ArrowRight';
            const prevKey = isVertical ? 'ArrowUp' : 'ArrowLeft';
            const openKey = isVertical ? 'ArrowRight' : 'ArrowDown';
            const openKeyReverse = isVertical ? null : 'ArrowUp';

            switch(event.key) {
                case nextKey:
                    event.preventDefault();
                    const nextIndex = (currentIndex + 1) % topLevelItems.length;
                    topLevelItems[nextIndex].focus();
                    break;

                case prevKey:
                    event.preventDefault();
                    const prevIndex = (currentIndex - 1 + topLevelItems.length) % topLevelItems.length;
                    topLevelItems[prevIndex].focus();
                    break;

                case openKey:
                    event.preventDefault();
                    const parentLi = target.closest('li:has(> .sub-menu)');
                    if (parentLi) {
                        this._show_submenu(parentLi);
                        const firstSubmenuItem = parentLi.querySelector('.sub-menu a[role="menuitem"]');
                        if (firstSubmenuItem) firstSubmenuItem.focus();
                    }
                    break;

                case openKeyReverse:
                    event.preventDefault();
                    const parentLiUp = target.closest('li:has(> .sub-menu)');
                    if (parentLiUp) {
                        this._show_submenu(parentLiUp);
                        const submenuItems = parentLiUp.querySelectorAll('.sub-menu a[role="menuitem"]');
                        if (submenuItems.length > 0) {
                            submenuItems[submenuItems.length - 1].focus();
                        }
                    }
                    break;

                case 'Home':
                    event.preventDefault();
                    topLevelItems[0].focus();
                    break;

                case 'End':
                    event.preventDefault();
                    topLevelItems[topLevelItems.length - 1].focus();
                    break;

                case 'Escape':
                    this._close_all_submenus();
                    break;
            }
        },

        /**
         * Handles keyboard navigation for submenu items, including nested submenus.
         */
        _handle_menu_keydown(event, target) {
            const menu = target.closest('[role="menu"]');
            // Only select direct children menu items (not nested submenu items)
            const menuItems = Array.from(menu.querySelectorAll(':scope > li > a[role="menuitem"]'));
            const currentIndex = menuItems.indexOf(target);
            const isVertical = this.orientation === 'vertical';
            // The <li> that is the direct parent of the current <a>
            const currentLi = target.closest('li');
            // Check if THIS item has a nested submenu
            const hasNestedSubmenu = currentLi && currentLi.querySelector(':scope > .sub-menu');
            // The parent <li> that OWNS this menu (start from parent to skip current <li>)
            const parentLi = currentLi && currentLi.parentElement ? currentLi.parentElement.closest('li:has(> .sub-menu)') : null;
            
            switch(event.key) {
                case 'ArrowDown':
                    event.preventDefault();
                    const nextIndex = (currentIndex + 1) % menuItems.length;
                    menuItems[nextIndex].focus();
                    break;
                    
                case 'ArrowUp':
                    event.preventDefault();
                    const prevIndex = (currentIndex - 1 + menuItems.length) % menuItems.length;
                    menuItems[prevIndex].focus();
                    break;
                    
                case 'ArrowRight':
                    event.preventDefault();
                    if (hasNestedSubmenu) {
                        this._show_submenu(currentLi);
                        const firstSubmenuItem = currentLi.querySelector(':scope > .sub-menu a[role="menuitem"]');
                        if (firstSubmenuItem) firstSubmenuItem.focus();
                    } else if (!isVertical) {
                        this._move_to_parent_menu_item(target, 'next');
                    }
                    break;
                    
                case 'ArrowLeft':
                    event.preventDefault();
                    // If in a submenu, close it and focus parent
                    if (parentLi) {
                        this._hide_submenu(parentLi);
                        const parentLink = parentLi.querySelector(':scope > a[role="menuitem"]');
                        if (parentLink) parentLink.focus();
                    }
                    break;
                    
                case 'Home':
                    event.preventDefault();
                    menuItems[0].focus();
                    break;
                    
                case 'End':
                    event.preventDefault();
                    menuItems[menuItems.length - 1].focus();
                    break;
                    
                case 'Escape':
                    event.preventDefault();
                    if (parentLi) {
                        this._hide_submenu(parentLi);
                        const parentLink = parentLi.querySelector(':scope > a[role="menuitem"]');
                        if (parentLink) parentLink.focus();
                    }
                    break;
                    
                case 'Tab':
                    // Allow tab to close submenu and move to next focusable element
                    this._close_all_submenus();
                    break;
            }
        },

        _handle_toggle_keydown(event, target) {
            const parentLi = target.closest('li:has(> .sub-menu)');
            
            switch(event.key) {
                case 'Enter':
                case ' ':
                    event.preventDefault();
                    const expanded = target.getAttribute('aria-expanded') === 'true';
                    if (expanded) {
                        this._hide_submenu(parentLi);
                        const parentLink = parentLi.querySelector(':scope > a[role="menuitem"]');
                        if (parentLink) parentLink.focus();
                    } else {
                        this._show_submenu(parentLi);
                        const firstItem = parentLi.querySelector('.sub-menu a[role="menuitem"]');
                        if (firstItem) firstItem.focus();
                    }
                    break;
                    
                case 'ArrowDown':
                    event.preventDefault();
                    this._show_submenu(parentLi);
                    const firstItem = parentLi.querySelector('.sub-menu a[role="menuitem"]');
                    if (firstItem) firstItem.focus();
                    break;
                    
                case 'Escape':
                    event.preventDefault();
                    this._hide_submenu(parentLi);
                    const parentLink = parentLi.querySelector(':scope > a[role="menuitem"]');
                    if (parentLink) parentLink.focus();
                    break;
            }
        },

        _move_to_parent_menu_item(currentItem, direction) {
            const currentMenu = currentItem.closest('[role="menu"]');
            if (!currentMenu) return;
            
            const parentLi = currentMenu.closest('li:has(> .sub-menu)');
            if (!parentLi) return;
            
            const menubar = parentLi.closest('[role="menubar"]');
            if (!menubar) return;
            
            const topLevelItems = Array.from(menubar.querySelectorAll(':scope > li[role="none"] > a[role="menuitem"]'));
            const parentLink = parentLi.querySelector(':scope > a[role="menuitem"]');
            const parentIndex = topLevelItems.indexOf(parentLink);
            
            if (direction === 'next') {
                const nextIndex = (parentIndex + 1) % topLevelItems.length;
                topLevelItems[nextIndex].focus();
            } else {
                const prevIndex = (parentIndex - 1 + topLevelItems.length) % topLevelItems.length;
                topLevelItems[prevIndex].focus();
            }
        },

        _close_all_submenus() {
            const openMenus = this.el.querySelectorAll('li:has(> .sub-menu)');
            openMenus.forEach(li => {
                const toggleBtn = li.querySelector(':scope > .toggle-submenu');
                if (toggleBtn && toggleBtn.getAttribute('aria-expanded') === 'true') {
                    this._hide_submenu(li);
                }
            });
        },

        _create_live_region() {
            const region = document.createElement('div');
            region.setAttribute('aria-live', 'polite');
            region.setAttribute('aria-atomic', 'true');
            region.classList.add('sr-only');
            document.body.appendChild(region);
            return region;
        },

        _announce(message) {
            if (liveRegion) {
                liveRegion.textContent = '';
                // Small delay to ensure screen readers pick up the change
                setTimeout(() => {
                    liveRegion.textContent = message;
                }, 100);
            }
        }
    }

    Theme_Navbar.create = function( el ){
        let instance = new Theme_Navbar(el);
        if (instance.el) instances.push(instance);
		return instance;
    }

    Theme_Navbar.init = function(){
        var navs = document.getElementsByClassName('menu-comp');

        for (var i = 0; i < navs.length; i++) {
            Theme_Navbar.create( navs[i] );
        }

        return instances;
    }

    Theme_Navbar.getInstances = function(){
        return instances;
    }

    /**
     * Initializes the global ScrollSpy using GSAP ScrollTrigger.
     * Detects sections with the `scrollspy` class and activates
     * the parent `li` of any link pointing to that section.
     * 
     * Links are expected to have an `href` attribute with a hash corresponding to the section's ID (e.g., `href="...#section-id"`).
     */
    Theme_Navbar.initScrollSpy = function(){
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined' || !gsap.registerPlugin) return;
        if (!window.MV23_gsap_scrolltrigger_registered) {
            gsap.registerPlugin(ScrollTrigger);
            window.MV23_gsap_scrolltrigger_registered = true;
        }

        const sections = document.querySelectorAll('.scrollspy');
        if (!sections.length) return;

        function activateLinksForId(id){
            // remove all current-menu-item globally
            const actives = document.querySelectorAll('.current-menu-item');
            actives.forEach(a => a.classList.remove('current-menu-item'));

            const links = document.querySelectorAll('a[href*="#"]:not([href="#"])');
            links.forEach(link => {
                if (link.hash === '#' + id) {
                    const li = link.closest('li');
                    if (li) li.classList.add('current-menu-item');
                }
            });
        }

        function deactivateLinksForId(id){
            const links = document.querySelectorAll('a[href*="#"]:not([href="#"])');
            links.forEach(link => {
                if (link.hash === '#' + id) {
                    const li = link.closest('li');
                    if (li) li.classList.remove('current-menu-item');
                }
            });
        }

        sections.forEach(target => {
            const id = target.id;
            if (!id) return;

            ScrollTrigger.create({
                trigger: target,
                start: 'top center',
                end: 'bottom center',
                onEnter: () => activateLinksForId(id),
                onEnterBack: () => activateLinksForId(id),
                onLeave: () => deactivateLinksForId(id),
                onLeaveBack: () => deactivateLinksForId(id)
            });
        });
    }

    return Theme_Navbar;
})();

// ****************************************************************************************************
// INIT MODULE
// ****************************************************************************************************
document.addEventListener('DOMContentLoaded', function() {
    Theme_Navbars.init();
    Theme_Navbars.initScrollSpy();
});