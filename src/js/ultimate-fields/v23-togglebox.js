/**!
 * V23 ToggleBox
 * @author	Mvelarde   <miguel@velarde23.com>
 * @license MIT
 */

 (function v23ToggleBoxModule(factory) {
	"use strict";

	if (typeof define === "function" && define.amd) {
		define(factory);
	}
	else if (typeof module != "undefined" && typeof module.exports != "undefined") {
		module.exports = factory();
	}
	else {
		/* jshint sub:true */
		window["V23_ToggleBox"] = factory();
	}
})(function v23ToggleBoxFactory() { 
	"use strict";

	var instances = [],
		version = '5.8.23',
		timers = {};

	/**
	 * @class  V23_ToggleBox
	 * @param  {HTMLElement}  el
	 * @param  {Object}       [options]
	 */
	function V23_ToggleBox(el, options) {
		if (!(el && el.nodeType && el.nodeType === 1)) {
			console.log( 'V23 ToggleBox Error: `el` must be HTMLElement, and not ' + {}.toString.call(el) );
			return;
		}

		if (!this._createInstance(el)) return;
		
		this.el = el; // root element
		this.currentTemplate = null;
		this._handleOptions(options);

		// Bind all private methods
		for (var fn in this) {
			if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
				this[fn] = this[fn].bind(this);
			}
		}

		this.itemsBox = this.el.getElementsByClassName('v23-togglebox__items')[0];

		this.items = [];
		this._saveItems();
		if (this.items.length > 0) {
			this._handle_template();
			this._handle_active_class();
			this._attach_click_events();
			this._change_active_tab_if_hash_in_url();
			this._attach_resize_events();	
		}
	};

	V23_ToggleBox.prototype = {
		_handleOptions( options ){
			// options configured as data-attributes
			var dataOptions = {},
				dataHeaderHeight = this.el.dataset.headerheight,
				dataDesktopTemplate = this.el.dataset.desktoptemplate,
				dataMovilTemplate = this.el.dataset.moviltemplate,
				dataOpenfirstTab = this.el.dataset.openfirsttab;

			if (dataDesktopTemplate != undefined) dataOptions.desktopTemplate = dataDesktopTemplate; 
            if (dataMovilTemplate != undefined) dataOptions.movilTemplate = dataMovilTemplate;
            if (dataHeaderHeight != undefined) dataOptions.headerHeight = dataHeaderHeight;
            if (dataOpenfirstTab != undefined) dataOptions.openFirstTab = dataOpenfirstTab;

            // dataOptions are overriddden if options arg is passed
			this.options = options = _extend(dataOptions, options);

			// defaults if no options are passed
			var defaults = {
				desktopTemplate : 'tab',
				movilTemplate : 'accordion',
				breakpoint : 768,
				headerHeight : 0,
				openFirstTab : true
			};
	
			// Set default options
			for (var name in defaults) {
				!(name in options) && (options[name] = defaults[name]);
			}
		},
		_createInstance(el){
			for (var i = 0; i < instances.length; i++) {
				if (instances[i].el === el) {
					console.log('V23 ToggleBox Error: el elemento id:'+el.id+' | class:'+el.className+' solo puede ser instanciado una vez.');
					return false;
				}
			}
			return true;
		},
		_saveItems(){
			var nav = this.el.getElementsByClassName('v23-togglebox__nav')[0];
			var btns = nav.getElementsByClassName('v23-togglebox__btn');
			for (var i = 0; i < btns.length; i++) {
				var boxid = btns[i].dataset.boxid;

				if (boxid) {
					var boxEl = this.el.querySelector(boxid);
					if ( boxEl && boxEl.nodeType && boxEl.nodeType === 1 ) {
						this.items.push({ btn: btns[i], box: boxEl });
					}
						
				}
			}
		},
		_attach_click_events(){
			for (var i = 0; i < this.items.length; i++) {
				_on(this.items[i].btn,'click', this._open_tab);
			}
		},
		_open_tab(event){
			event.preventDefault();
			this._handle_active_class(event.target);
		},
		_handle_active_class(btn){
			if (btn) { // method is triggered by a user click event
				for (var i = 0; i < this.items.length; i++) {
					if ( this.items[i].btn === btn ) {
						if (this.currentTemplate === 'accordion'){
							_toggleClass(btn, 'active');
							_toggleClass(this.items[i].box, 'active');
							// _scrollTo(document.documentElement, (btn.offsetTop - this.options.headerHeight), 500);
						} else {
							_addClass(btn, 'active');
							_addClass(this.items[i].box, 'active');	
						}
					} else {
						_removeClass(this.items[i].btn, 'active');
						_removeClass(this.items[i].box, 'active');	
					}
				};				
			} else { // method is triggered on init or on resize
				for (var i = 0; i < this.items.length; i++) {
					_removeClass(this.items[i].btn, 'active');
					_removeClass(this.items[i].box, 'active');	
				};				

				if (this.currentTemplate === 'tab' && String(this.options.openFirstTab) === 'true') {
					_addClass(this.items[0].btn, 'active');
					_addClass(this.items[0].box, 'active');	
				}
			}
		},
		_change_active_tab_if_hash_in_url(){
			if(window.location.hash) {
            	var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
            	var cleaned_hash = _cleanHash(hash);
            	for (var i = 0; i < this.items.length; i++) {
					if ( this.items[i].box.id === cleaned_hash ) this.items[i].btn.click();
				}
    	    } 
		},
		_handle_template(){
			var options = this.options,
				breakpoint = options.breakpoint;

			if( !(typeof breakpoint === 'number') ) return;

      		if ( _getViewportDimensions().width <= breakpoint) {
      			this.el.dataset.template = this.currentTemplate = options.movilTemplate;
      		} else {
      			this.el.dataset.template = this.currentTemplate = options.desktopTemplate;
      		};
			
      		switch(this.currentTemplate){
				case 'tab': 
					for (var i = 0; i < this.items.length; i++) {
						this.itemsBox.appendChild(this.items[i].box);
					};
					break;
				case 'accordion':
					for (var it = 0; it < this.items.length; it++) {
						_insertAfter(this.items[it].box, this.items[it].btn);
					};
					break;
				default:
					break;
      		}
		},
		_attach_resize_events(){
			var timeToWaitForLast = 100, 
				that = this,
				options = this.options,
				id = "v23ToggleBox"+instances.length;

			window.addEventListener('resize', function(){
				_waitForFinalEvent( function() {
					if( options.movilTemplate == 'tab' && options.desktopTemplate == 'tab') return;
					// if( options.movilTemplate === options.desktopTemplate) return;
					that._handle_template();
					that._handle_active_class();
				}, timeToWaitForLast, id);
			}, true);
		}
	};

	function _extend(dst, src) {
		if (dst && src) {
			for (var key in src) {
				if (src.hasOwnProperty(key)) {
					dst[key] = src[key];
				}
			}
		}

		return dst;
	};

	function _on(el, event, fn) {
		el.addEventListener(event, fn, false);
	};

	function _hasClass(element, cls) {
		return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
	};

	function _addClass(elem, className) {
		// TODO : ELEM IS ARRAY
		if (!_hasClass(elem, className)) {
			elem.className += ' ' + className;
		}
	};	

	function _removeClass(elem, className) {
		var newClass = ' ' + elem.className.replace( /[\t\r\n]/g, ' ') + ' ';
		if (_hasClass(elem, className)) {
			while (newClass.indexOf(' ' + className + ' ') >= 0 ) {
				newClass = newClass.replace(' ' + className + ' ', ' ');
			}
			elem.className = newClass.replace(/^\s+|\s+$/g, '');
		}
	};

	function _toggleClass(element, cls) {
		if ( _hasClass(element,cls) ) { _removeClass(element,cls);
		} else { _addClass(element,cls); }
	};

	function _getViewportDimensions() { 
		var w=window,
		d=document,
		e=d.documentElement,
		g=d.getElementsByTagName('body')[0],
		x=w.innerWidth||e.clientWidth||g.clientWidth,
		y=w.innerHeight||e.clientHeight||g.clientHeight;

		return { width:x,height:y };
	};

	function _scrollTo(element, to, duration) {
    	var start = element.scrollTop,
        	change = to - start,
        	currentTime = 0,
        	increment = 20;
	        
    	var animateScroll = function(){        
        	currentTime += increment;
        	var val = Math.easeInOutQuad(currentTime, start, change, duration);
        	element.scrollTop = val;
        	if(currentTime < duration) {
            	setTimeout(animateScroll, increment);
        	}
    	};
    	animateScroll();
	};

	Math.easeInOutQuad = function (t, b, c, d) {
		//t = current time
		//b = start value
		//c = change in value
		//d = duration
  		t /= d/2;
    		if (t < 1) return c/2*t*t + b;
    		t--;
    		return -c/2 * (t*(t-2) - 1) + b;
	};

	function _cleanHash(hash){
		// remove query vars
		var index = hash.indexOf("?");
		var result;
		if (index < 0) {
		    result = hash;
		} else {
		    result = hash.substr(0, index);
		}
		return result;
	};

	function _insertAfter(newNode, referenceNode) {
    	referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
	};

	function _waitForFinalEvent(callback, ms, uniqueId) {
		if (timers[uniqueId]) { clearTimeout (timers[uniqueId]); }
			timers[uniqueId] = setTimeout(callback, ms);
	};

	/**
	 * Create v23ToggleBox instance
	 * @param {HTMLElement}  el
	 * @param {Object}      [options]
	 */
	V23_ToggleBox.create = function (el, options) {
		var options = (options) ? options : {},
			togglebox = new V23_ToggleBox(el, options);

		if (togglebox.el) {
			instances.push(togglebox);
		}
		return togglebox;
	};

	V23_ToggleBox.v = function () {
		console.log( version );
	};

	V23_ToggleBox.init = function () {
		var toggleboxes = document.getElementsByClassName('v23-togglebox');

        for (var i = 0; i < toggleboxes.length; i++) {
            V23_ToggleBox.create( toggleboxes[i] );
        }

        return instances;
	};

	// Export
	return V23_ToggleBox;
});