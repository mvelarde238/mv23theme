window['Toggle_Boxes'] = (function(){
    let instances = [];

    function Toggle_Box( el ){
        this.trigger = el;

        this.selector = el.dataset.selector;
        if(!this.selector) return;

        this.panel = document.querySelector( this.selector );
        if(!this.panel) return;

        // Set initial ARIA attributes on the panel element
        this.panel.setAttribute('hidden', 'true');
        const panelId = this.panel.id || `toggle-box-panel-${Math.random().toString(36).substr(2, 9)}`;
        this.panel.id = panelId;

        // Set ARIA attributes on the trigger element
        this.trigger.setAttribute('aria-expanded', 'false');
        this.trigger.setAttribute('aria-controls', panelId);

        this.scrollToBox = el.dataset.scrollToBox;

        // Bind all private methods
		for (var fn in this) {
			if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
				this[fn] = this[fn].bind(this);
			}
		}

        this._init();
    }
    
    Toggle_Box.prototype = {
        _init(){
            this._handleDisclosure();
        },
        _handleDisclosure(){
            Disclosure.create({
                trigger: this.trigger,
                panel: this.panel,
                focusFirstTabbable: true,
                tabFocusTrap: true,
                onOpenEnd: ()=>{
                    this.trigger.classList.add( 'active' );

                    if(this.scrollToBox != 0){
                        const targetY = this.panel.getBoundingClientRect().top + window.pageYOffset - MV23_GLOBALS.headerHeight;
                        animateScrollTo(targetY, 800);
                    }
                },
                onCloseEnd: ()=>{
                    this.trigger.classList.remove( 'active' );
                },
            });
        }
    };

    Toggle_Box.create = function( el ){
        let instance = new Toggle_Box(el);
        if (instance.panel) instances.push(instance);
		return instance;
    }

    Toggle_Box.init = function(){
        var trigger = document.querySelectorAll('.toggle-box');

        for (var i = 0; i < trigger.length; i++) {
            Toggle_Box.create( trigger[i] );
        }

        return instances;
    }

    Toggle_Box.getInstances = function(){
        return instances;
    }

    return Toggle_Box;
})();

document.addEventListener('DOMContentLoaded', function() {
    Toggle_Boxes.init();
});