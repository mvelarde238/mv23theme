var Repeater_Button = (function(c){
	
	function init( params ){
		var botones = document.getElementsByClassName(params.buttons.replace('.',''));
		var _that = this;

		for (var i = 0; i < botones.length; i++) {
			var boton = botones[i];

			boton.addEventListener('click', function(event){
				event.preventDefault();
				
				var itemsWrapper = document.getElementById(this.dataset.wrapperid),
					id_prefix = this.dataset.idprefix,
					name_prefix = this.dataset.nameprefix,
					newindex = this.dataset.newindex,
					newNode = itemsWrapper.lastElementChild.cloneNode(true);

				itemsWrapper.appendChild(newNode);
				_clear_inputs(newNode);
				_change_name_attr(newNode, newindex, name_prefix);
				_change_id_attr(newNode, newindex, id_prefix);

				this.dataset.newindex = parseInt(newindex)+1;

				_that.do_callback(params.callback, newNode);
			});
		}
	};

	function _clear_inputs(node){
		var inputs = node.getElementsByTagName('input');
		for (var i = 0; i < inputs.length; i++) {
			inputs[i].value = '';
		}

		var textareas = node.getElementsByTagName('textarea');
		for (var i = 0; i < textareas.length; i++) {
			textareas[i].value = '';
		}
	};

	function _change_name_attr(node, newindex, name_prefix){
		var inputs = node.getElementsByTagName('input'),
			textareas = node.getElementsByTagName('textarea'),
			regex = /\[(.+?)\]/g; // get all strings in brackets: [*]

		for (var i = 0; i < inputs.length; i++) {
			var matches = inputs[i].name.match(regex) || [];

			if (matches.length>0) {
				var lastTextinBrackets =  matches[matches.length - 1];
				inputs[i].setAttribute('name', name_prefix+'['+newindex+']'+lastTextinBrackets);
			}
		}

		for (var i = 0; i < textareas.length; i++) {
			var matches = textareas[i].name.match(regex) || [];

			if (matches.length>0) {
				var lastTextinBrackets =  matches[matches.length - 1];
				textareas[i].setAttribute('name', name_prefix+'['+newindex+']'+lastTextinBrackets);
			}
		}
	};
	
	function _change_id_attr(node, newindex, id_prefix){
		var inputs = node.getElementsByTagName('input'), 
			textareas = node.getElementsByTagName('textarea'),
			strToRemoveFromId = id_prefix + '-'+(parseInt(newindex) - 1)+'--';

		for (var i = 0; i < inputs.length; i++) {
			var mainStr = inputs[i].id.replace(strToRemoveFromId,'');
			inputs[i].setAttribute('id', id_prefix+'-'+newindex+'--'+mainStr);
		}
		for (var i = 0; i < textareas.length; i++) {
			var mainStr = textareas[i].id.replace(strToRemoveFromId,'');
			textareas[i].setAttribute('id', id_prefix+'-'+newindex+'--'+mainStr);
		}  
	};

	function do_callback(func_callback, newNode){
		if (typeof func_callback === 'function') func_callback(newNode);
	};

	return {
		init : init,
		do_callback : do_callback
	}

})(console.log);