window['Adaptive_Navbars'] = (function(){
    let instances = [];

    function Adaptive_Navbar( el ){
        this.menu = el;
        this.menu.classList.add('adaptive-navbar');
        this.menuItems = this.menu.querySelectorAll(':scope > li');

        // Bind all private methods
		for (var fn in this) {
			if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
				this[fn] = this[fn].bind(this);
			}
		}

        this._init();
    }
    
    Adaptive_Navbar.prototype = {
        _init(){
            this._prepare_secondary_nav();
            this._doAdapt();
            window.addEventListener('resize', this._doAdapt);

            // Refresh ScrollTrigger breakpoints after content update
            refreshScrollTriggerBreakpoints();
        },
        _prepare_secondary_nav(){
            const moreText = {
                'en': 'More',
                'es': 'Más',
                'fr': 'Plus',
            };
        
            // insert "more" button and duplicate the list
            this.menu.insertAdjacentHTML('beforeend', `
                <li class="menu-item menu-item-has-children hidden-text adaptive-navbar__more" role="none">
                    <a href="#" role="menuitem" aria-haspopup="true">
                        <span class="menu-item__icon"><i class="bi bi-three-dots-vertical"></i></span>
                        <span class="menu-item__label">${moreText[MV23_GLOBALS.lang]}</span>
                    </a>
                    <ul class="sub-menu adaptive-navbar__secondary" role="menu" aria-label="${moreText[MV23_GLOBALS.lang]} submenu">
                        ${this.menu.innerHTML}
                    </ul>
                </li>
            `);

            this.secondaryNav = this.menu.querySelector('.adaptive-navbar__secondary');
            this.secondaryItems = this.secondaryNav.querySelectorAll(':scope > li');
            this.allItems = this.menu.querySelectorAll('li');
            this.moreItem = this.menu.querySelector('.adaptive-navbar__more');
        },
        _doAdapt(){
            let { allItems, moreItem, menu, menuItems, secondaryItems } = this;

            // reveal all items for the calculation
            allItems.forEach((item) => {
                item.classList.remove('--hidden')
            })
        
            // hide items that won't fit in the main menu
            let stopWidth = moreItem.offsetWidth;
            let hiddenItems = [];
            const primaryWidth = menu.offsetWidth;
            menuItems.forEach((item, i) => {
                let offsetWidth = item.offsetWidth;
                if(primaryWidth >= stopWidth + offsetWidth) {
                    stopWidth += offsetWidth;
                } else {
                    item.classList.add('--hidden');
                    hiddenItems.push(i);
                }
            });
          
            // toggle the visibility of More button and items in Secondary
            if (!hiddenItems.length) {
                moreItem.classList.add('--hidden');
            } else {
                secondaryItems.forEach((item, i) => {
                    if (!hiddenItems.includes(i)) {
                        item.classList.add('--hidden');
                    }
                })
            }
        }
    }

    Adaptive_Navbar.create = function( el ){
        let instance = new Adaptive_Navbar(el);
        if (instance.menu) instances.push(instance);
		return instance;
    }

    Adaptive_Navbar.init = function(){
        var navs = document.querySelectorAll('.horizontal-nav');

        for (var i = 0; i < navs.length; i++) {
            Adaptive_Navbar.create( navs[i] );
        }

        return instances;
    }

    Adaptive_Navbar.getInstances = function(){
        return instances;
    }

    return Adaptive_Navbar;
})();

document.addEventListener('DOMContentLoaded', function() {
    Adaptive_Navbars.init();
});