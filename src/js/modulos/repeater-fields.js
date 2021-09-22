/**!
 * Repeater Fields
 * @author	Mvelarde   <miguel@velarde23.com>
 * @license MIT
 */

(function repeaterFieldsModule(factory) {
	"use strict";

	if (typeof define === "function" && define.amd) {
		define(factory);
	}
	else if (typeof module != "undefined" && typeof module.exports != "undefined") {
		module.exports = factory();
	}
	else {
		/* jshint sub:true */
		window["Repeater_Fields"] = factory();
	}
})(function repeaterFieldsFactory() { 
	"use strict";

	var instances = [],
		version = '1.8.23';

	/**
	 * @class  Repeater_Fields
	 * @param  {HTMLElement}  el
	 * @param  {Object}       [options]
	 */
	function Repeater_Fields(el, options) {
		if (!(el && el.nodeType && el.nodeType === 1)) {
			console.log( 'Repeater Fields Error: `el` must be HTMLElement, and not ' + {}.toString.call(el) );
			return;
		}

		if (el.children.length < 1) return;
		
		for (var i = 0; i < instances.length; i++) {
			if (instances[i] === el) {
				console.log('Repeater Fields Error: el elemento id:'+el.id+' | class:'+el.className+' solo puede ser instanciado una vez.');
				return;
			}
		}
		instances.push(el);

		this.el = el; // root element
		this.childrenLength = this.el.children.length;
		this.options = options = _extend({}, options);

		// Default options
		var defaults = {
			addButtonClass: 'repeater-btn',
			addButtonText: 'Agregar fila',
			deleteButtonClass: 'delete-btn',
			deleteButtonText: 'Eliminar fila',
			addButtonPosition: 'after',
			onEnd: null,
			onSort: null
		};

		// Set default options
		for (var name in defaults) {
			!(name in options) && (options[name] = defaults[name]);
		}

		// Bind all private methods
		for (var fn in this) {
			if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
				this[fn] = this[fn].bind(this);
			}
		}

		var addBtn = this._createAddButton();
		this.addBtn = addBtn;
		var deleteBtn = this._createDeleteButton();

		_on(addBtn, 'click', this._cloneNode);
		_on(el, 'click', this._removeNode);

		this._init_draggable_functions();
	};

	Repeater_Fields.prototype = {
		constructor: Repeater_Fields,

		_cloneNode : function(e){
			e.preventDefault();

			var el = this.el,
				newNode = el.lastElementChild.cloneNode(true),
				newindex = this.childrenLength;

			this._change_name_and_id_attr(newNode, newindex);
			this._clear_fields(newNode);
			el.appendChild(newNode);
			this.childrenLength++;
			this._focus_first_field(newNode);

			this._do_callback_after_clone(newNode);
		},

		_removeNode : function(e){
			if (e.target) {
				if(e.target.nodeName == 'BUTTON' && e.target.dataset.key === 'delete-repeated-field' ) {
					e.preventDefault();

					var confirmar = confirm("¿Estas seguro de ejecutar esta acción? toda la información será eliminada.");

					if (confirmar) {
						var list = this.el,
						node = e.target.parentNode;

						node.remove();
						this.childrenLength--;
						if ( this.childrenLength > 0 ) {	
							for (var i = 0; i < this.childrenLength; i++) {
								this._change_name_and_id_attr(list.children[i], i);
							}
						} else {
							this.addBtn.remove();
						}
					}
				}
			}
		},

		_createAddButton : function(){
			var el = this.el,
				options = this.options,
				addButtonPosition = options.addButtonPosition,
				btn = _createButtonElement( options.addButtonText , options.addButtonClass );

			switch(addButtonPosition) {
				case 'before':
					el.insertAdjacentElement('beforebegin', btn);
					break;
				case 'after':
					el.insertAdjacentElement('afterend', btn);
					break;
				default:
					el.insertAdjacentElement('afterend', btn);
			}
			return btn;
		},

		_createDeleteButton : function(){
			var el = this.el,
				options = this.options;

			for (var i = 0; i < el.children.length; i++) {
				var btn = _createButtonElement( options.deleteButtonText , options.deleteButtonClass );
				btn.dataset.key = 'delete-repeated-field';
				el.children[i].appendChild(btn);
			}	
		},

		_change_name_and_id_attr : function (newNode, newindex){
			var fields = newNode.querySelectorAll('input, textarea, select'),
				regex = /\[(.+?)\]/g; // get all strings in brackets: [*]

			for (var i = 0; i < fields.length; i++) {
				var matches = fields[i].name.match(regex) || [];
	
				if (matches.length == 2) { // bidimensional Array
					var newName = fields[i].name.replace( matches[0],"["+newindex+"]" ),
						oldID =  fields[i].id,
						newID =  newName.replace(/\[/g, '-').replace(/]/g, '-');
	
					fields[i].setAttribute('name', newName); 
					fields[i].setAttribute('id', newID);
					this._change_label_for_attr(newNode, oldID, newID);
				}
			}
		},

		_change_label_for_attr : function (newNode, oldID, newID){
			var label = newNode.querySelectorAll('label[for="'+oldID+'"]');
			if (label.length > 0) {
				label[0].setAttribute('for',newID);
			}
		},

		_clear_fields : function (newNode){
			var fields = newNode.querySelectorAll('input, textarea, select');	

			for (var i = 0; i < fields.length; i++) {
				switch (fields[i].type) {
					case 'text':
					case 'tel':
					case 'email':
					case 'hidden':
					case 'password':
					case 'button':
					case 'reset':
					case 'submit':
						fields[i].value = '';
						break;
					case 'checkbox':
					case 'radio':                                            
						// fields[i].checked = false;
						break;
				}
			}
		},

		_focus_first_field : function (newNode){
			var focusable = newNode.querySelectorAll('input, textarea, select');
			focusable[0].focus();
		},

		_init_draggable_functions : function (){
			var that = this,
				list = this.el;

			if(typeof Sortable === 'function'){
				addClass(list, 'is-sortable');

				Sortable.create(list,{
					onEnd: function (evt) {
						for (var i = 0; i < that.childrenLength; i++) {
							that._change_name_and_id_attr(list.children[i], i);
							that._do_callback_after_sort();
						}
					}
				});
			}
		},

		_do_callback_after_clone : function (newNode){
			if (typeof this.options.onEnd === 'function') this.options.onEnd(newNode);
		},

		_do_callback_after_sort : function (){
			if (typeof this.options.onSort === 'function') this.options.onSort();
		},
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

	function _createButtonElement(text, className){
		var button = document.createElement('button'),
			buttonText = document.createTextNode(text);
		
		button.appendChild(buttonText);    
		button.className = className;

		return button;
	};

	function _hasClass(element, cls) {
		return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
	};

	function addClass(elem, className) {
		// TODO : ELEM IS ARRAY
		if (!_hasClass(elem, className)) {
			elem.className += ' ' + className;
		}
	};	

	/**
	 * Create repeaterFields instance
	 * @param {HTMLElement}  el
	 * @param {Object}      [options]
	 */
	Repeater_Fields.create = function (el, options) {
		return new Repeater_Fields(el, options);
	};

	Repeater_Fields.v = function () {
		console.log( version );
	};

	// Export
	return Repeater_Fields;
});