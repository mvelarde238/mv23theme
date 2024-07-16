window['Theme_Navbars'] = (function(){
    let instances = [],
        sub_menu_width = 180,
        menu_breakpoint = 1296;

    function Theme_Navbar( el ){
        this.el = el;
    
        // Bind all private methods
		for (var fn in this) {
			if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
				this[fn] = this[fn].bind(this);
			}
		}

        this._handle_toggle_submenu_button();
        
        if (el.classList.contains('horizontal-nav')){
            this._handle_menu_item_with_children_mouseover();    
        }
    }
    
    Theme_Navbar.prototype = {
        _handle_toggle_submenu_button(){
            let has_children = this.el.querySelectorAll('li.menu-item-has-children');
            for (let i = 0; i < has_children.length; i++) {
                const item_has_children = has_children[i];
                let toggle_submenu_button = item_has_children.querySelector(':scope > .toggle-submenu');
    
                toggle_submenu_button.addEventListener('click', (event)=>{
                    const expanded = toggle_submenu_button.getAttribute('aria-expanded') === 'true' || false;
                    if( expanded ){
                        toggle_submenu_button.setAttribute('aria-expanded', false);
                        this._hide_submenu(item_has_children);
                    } else {
                        toggle_submenu_button.setAttribute('aria-expanded', true);
                        this._show_submenu(item_has_children);
                    }
                });
            }
        },
        _handle_menu_item_with_children_mouseover(){
            let has_children = this.el.querySelectorAll('li.menu-item-has-children');

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
        _show_submenu( item_has_children ){
            let rect = item_has_children.getBoundingClientRect();
            let outOfBoundsOnX = ( document.body.clientWidth - (rect.x + sub_menu_width) ) < sub_menu_width;
            let openFromClass = (outOfBoundsOnX) ? 'open-from-right' : 'open-from-left';
            let sub_menu = item_has_children.querySelector(':scope > .sub-menu');
            sub_menu.classList.add(openFromClass);
            sub_menu.style.display = 'block';
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
            for (let i = 0; i < sub_menus.length; i++) {
                const button = toggle_submenu_buttons[i];
                button.setAttribute('aria-expanded', false);
            }
        },
        _handle_mouse_event( event, item_has_children, callback ){
            viewport = updateViewportDimensions();
            
            if( viewport.width < menu_breakpoint ) {
                event.stopPropagation();
                event.preventDefault();
                return;
            }
            callback.call(this, item_has_children);
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

    return Theme_Navbar;
})();

(function($,c){      
    document.addEventListener('DOMContentLoaded', function() {
        // ****************************************************************************************************
        // INIT MODULE
        // ****************************************************************************************************

        Theme_Navbars.init();
        
        // ****************************************************************************************************
    });
})(jQuery,console.log);