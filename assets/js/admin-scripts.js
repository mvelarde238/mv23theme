// ****************************************************************************************************
// MODAL
// ****************************************************************************************************
var Modal_v23 = function () {
    // "use strict";

    var _initialized = false,
        _isActive = false,
        _modalClass = "modalv23",
        _closeModalClass = "modalv23__close",
        _modalContentClass = "modalv23__content",
        _scrollPositionOnOpen = 0,
        _DOM = {
            modal: null,
            modalContent: null,
            close: null
        };

    function getStatus() { return _isActive; }
    function _changeStatus() { _isActive = !_isActive; }
    function _printStatus() { console.log(_isActive); }

    function _buildModal() {
        var modal = document.createElement("div");
            modal.className = _modalClass;
            modal.setAttribute("style", "display:none;");
        var modalContent = document.createElement("div");
            modalContent.className = _modalContentClass;
        var close = document.createElement("span");
            close.className = _closeModalClass;
        document.body.appendChild(modal);
        modal.appendChild(modalContent);
        modal.appendChild(close);
        _DOM.modal = modal;
        _DOM.modalContent = modalContent;
        _DOM.close = close;
    }

    function _suscribeEvents() {
        _DOM.modal.addEventListener("click", function (e) {
            if (e.target === _DOM.modal) close();
        });
    
        _DOM.close.addEventListener("click", function (e) {
            close();
        });
    
        document.addEventListener("keyup", function (e) {
            if (e.keyCode === 27) close();
        });
    }

    function open(modalClass, callback) {
        if (getStatus() == false) {
            _changeStatus();
            _setScrollPosition();
            _DOM.modal.style.display = null;

            if (modalClass != "") _DOM.modal.className += " " + modalClass;
            if (typeof callback === "function") callback();
        }
    }

    function close() {
        _DOM.modal.style.display = "none";
        _DOM.modal.className = _modalClass;
        _DOM.modalContent.innerHTML = ""; // TODO: create efficent way to clean modal content when filled
        // with ajax function
        _changeStatus();
        _returnWindowToOriginalPosition();
    }

    function resetModalContent(modalClass) {
        _DOM.modalContent.innerHTML = "";
        _DOM.modal.className = _modalClass;

        if (modalClass != "") {
            _DOM.modal.className += " " + modalClass;
        }
    }

    function fillWithInlineContent(id, callback) {
        var inlineContent = document.getElementById(id),
            inlineContentToString;

        if (inlineContent.outerHTML) {
            inlineContentToString = inlineContent.outerHTML;
        } else if (XMLSerializer) {
            inlineContentToString = new XMLSerializer().serializeToString(inlineContent);
        }

        _DOM.modalContent.innerHTML = inlineContentToString;

        if (typeof callback === "function") callback();
    }

    function fillWithHTMLContent(etiquetasHtml, callback) {
        _DOM.modalContent.innerHTML = etiquetasHtml;
        if (typeof callback === "function") callback();
    }

    function addClass(clase) {
        if (clase != "") _DOM.modal.className += " " + clase;
    }

    function getModalContent() {
        return _DOM.modalContent;
    }

    function _setScrollPosition() {
        _scrollPositionOnOpen = window.pageYOffset;
    }

    function _getScrollPosition() {
        return _scrollPositionOnOpen;
    }

    function _returnWindowToOriginalPosition() {
        var syp = _getScrollPosition(); // scroll y position
        window.scrollTo(0, syp);
    }

    function init() {
        if (!_initialized) {
            _buildModal();
            _suscribeEvents();
            _initialized = true;
        }
    }

    return {
        getStatus: getStatus,
        open: open,
        close: close,
        resetModalContent: resetModalContent,
        fillWithInlineContent: fillWithInlineContent,
        fillWithHTMLContent: fillWithHTMLContent,
        addClass: addClass,
        getModalContent: getModalContent,
        init: init
    };
}();Modal_v23.init();

$_GET = {};
if(document.location.toString().indexOf('?') !== -1) {
	var query = document.location
		.toString()
		// get the query string
		.replace(/^.*?\?/, '')
		// and remove any existing hash string (thanks, @vrijdenker)
		.replace(/#.*$/, '')
		.split('&');
	for(var i=0, l=query.length; i<l; i++) {
		var aux = decodeURIComponent(query[i]).split('=');
		$_GET[aux[0]] = aux[1];
	}
}

(function( $,c ) {
	$(function(){
		// ******************************************************************************************************************
		// scripts for archive page posytype edit screen
		// ******************************************************************************************************************
		if ($_GET['post_type'] == 'archive_page') {
			var metaboxes = {
				'category': '#categorydiv',
				'post_tag' : '#tagsdiv-post_tag'
			};
	
			// hide metaboxes
			function hide_metaboxes(){
				$('#categorydiv').hide();
				$('#tagsdiv-post_tag').hide();
			}
			hide_metaboxes();
	
			// show metabox deppending on post meta
			var appears_on = $('.uf-field-name-appears_on').find('select').val();
			if (appears_on == 'taxonomy') {
				var connected_taxonomy = $('.uf-field-name-connected_taxonomy').find('input[type=radio]:checked').val();
				if(connected_taxonomy != ''){
					$(metaboxes[connected_taxonomy]).show();
				}
			}
	
			// show metabox on seleting meta box
			$('.uf-field-name-connected_taxonomy').find('input[type=radio]').change(function(){
				hide_metaboxes();
				var connected_taxonomy = $(this).val();
				if(connected_taxonomy != ''){
					$(metaboxes[connected_taxonomy]).show();
				}
			});
		}
		// ******************************************************************************************************************
		// ******************************************************************************************************************
	});
})( jQuery, console.log );