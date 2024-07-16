window['Adaptive_Navbars'] = (function(){
    let instances = [];

    function Adaptive_Navbar( el ){
        this.container = el;
        this.container.classList.add('adaptive-navbar');
        this.primary = this.container.querySelector('ul');
        this.primary.classList.add('-primary');
        this.primaryItems = this.primary.querySelectorAll(':scope > li:not(.-more)');
        this.container.classList.add('--jsfied');
        
        // insert "more" button and duplicate the list
        this.primary.insertAdjacentHTML('beforeend', `
            <li class="-more hidden-text">
                <a href="#" type="button" aria-haspopup="true" aria-expanded="false">
                    <span class="menu-item__icon">&darr;</span>
                    <span class="menu-item__label">More</span>
                </a>
                <ul class="-secondary">
                  ${this.primary.innerHTML}
                </ul>
            </li>
        `);
    
        this.secondary = this.container.querySelector('.-secondary');
        this.secondaryItems = this.secondary.querySelectorAll(':scope > li');
        this.allItems = this.container.querySelectorAll('li');
        this.moreLi = this.primary.querySelector('.-more');
        this.moreBtn = this.moreLi.querySelector('a[type=button]')
        
        this.moreBtn.addEventListener('click', (e) => {
            e.preventDefault();
            this.container.classList.toggle('--show-secondary');
            this.moreBtn.setAttribute('aria-expanded', this.container.classList.contains('--show-secondary'))
        });

        // Bind all private methods
		for (var fn in this) {
			if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
				this[fn] = this[fn].bind(this);
			}
		}

        this._doAdapt();
        // hide Secondary on the outside click
        this._outsideclick();

        window.addEventListener('resize', this._doAdapt);
    }
    
    Adaptive_Navbar.prototype = {
        _doAdapt(){
            let { allItems, moreBtn, primary, primaryItems, moreLi, container, secondaryItems } = this;

            // reveal all items for the calculation
            allItems.forEach((item) => {
                item.classList.remove('--hidden')
            })
        
            // hide items that won't fit in the Primary
            let stopWidth = moreLi.offsetWidth;
            let hiddenItems = [];
            const primaryWidth = primary.offsetWidth;
            primaryItems.forEach((item, i) => {
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
                moreLi.classList.add('--hidden');
                container.classList.remove('--show-secondary');
                moreBtn.setAttribute('aria-expanded', false);
            } else {
                secondaryItems.forEach((item, i) => {
                    if (!hiddenItems.includes(i)) {
                        item.classList.add('--hidden');
                    }
                })
            }
        },
        _outsideclick(){
            let { secondary, moreBtn, container } = this;

            document.addEventListener('click', (e) => {
                let el = e.target
                while (el) {
                    if (el === secondary || el === moreBtn) {
                        return;
                    }
                    el = el.parentNode
                }
                container.classList.remove('--show-secondary')
                moreBtn.setAttribute('aria-expanded', false)
            })
        }
    }

    Adaptive_Navbar.create = function( el ){
        let instance = new Adaptive_Navbar(el);
        if (instance.container) instances.push(instance);
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

(function($,c){      
    document.addEventListener('DOMContentLoaded', function() {
        // ****************************************************************************************************
        // INIT MODULE
        // ****************************************************************************************************

        Adaptive_Navbars.init();
        
        // ****************************************************************************************************
    });
})(jQuery,console.log);