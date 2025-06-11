/*
* Get Viewport Dimensions 
* returns object with viewport dimensions to match css in width and height properties 
* ( source: http://andylangton.co.uk/blog/development/get-viewport-size-width-and-height-javascript ) 
*/
function updateViewportDimensions() { 
	var w=window,
	d=document,
	e=d.documentElement,
	g=d.getElementsByTagName('body')[0],
	x=w.innerWidth||e.clientWidth||g.clientWidth,
	y=w.innerHeight||e.clientHeight||g.clientHeight;

	return { width:x,height:y };
};

// var waitForFinalEvent = (function () {
// 	var timers = {};
// 	return function (callback, ms, uniqueId) {
// 		if (!uniqueId) { uniqueId = "Don't call this twice without a uniqueId"; }
// 		if (timers[uniqueId]) { clearTimeout (timers[uniqueId]); }
// 		timers[uniqueId] = setTimeout(callback, ms);
// 	};
// })();

// var timeToWaitForLast = 100;

// function hasClass(elem, className) {
//     return new RegExp(' ' + className + ' ').test(' ' + elem.className + ' ');
// }

function hasClass(element, cls) {
	return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
};

function addClass(elem, className) {
	// TODO : ELEM IS ARRAY
	if (!hasClass(elem, className)) {
		elem.className += ' ' + className;
	}
};	

function removeClass(elem, className) {
	// TODO : ELEM IS ARRAY
	var newClass = ' ' + elem.className.replace( /[\t\r\n]/g, ' ') + ' ';
	if (hasClass(elem, className)) {
		while (newClass.indexOf(' ' + className + ' ') >= 0 ) {
			newClass = newClass.replace(' ' + className + ' ', ' ');
		}
		elem.className = newClass.replace(/^\s+|\s+$/g, '');
	}
};

function toggleClass(elem, className) {
	// TODO : ELEM IS ARRAY
	var newClass = ' ' + elem.className.replace( /[\t\r\n]/g, ' ' ) + ' ';
	if (hasClass(elem, className)) {
		while (newClass.indexOf(' ' + className + ' ') >= 0 ) {
			newClass = newClass.replace( ' ' + className + ' ' , ' ' );
		}
		elem.className = newClass.replace(/^\s+|\s+$/g, '');
	} else {
		elem.className += ' ' + className;
	}
};

function isNumber(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
};

function removeElementsByClass(className){
	var elements = document.getElementsByClassName(className);

	while(elements.length > 0){
		elements[0].parentNode.removeChild(elements[0]);
	}
};

function wrapAll(elementsArray, elementContainer){
	for (var ind = 0; ind < elementsArray.length; ind++) {
		elementContainer.append(elementsArray[0]);
	};
};


function idGenerator() {
	var S4 = function() {
		return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
	};
	return 'v23_'+(S4()+S4()+"_"+S4()+"_"+S4());
}


function myscrollTo(element, to, duration) { // https://gist.github.com/andjosh/6764939
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
}

// t = current time
// b = start value
// c = change in value
// d = duration
Math.easeInOutQuad = function (t, b, c, d) {
  t /= d/2;
    if (t < 1) return c/2*t*t + b;
    t--;
    return -c/2 * (t*(t-2) - 1) + b;
};

// ****************************************************************************************************
// SERIALIZE FORM TO OBJECT
// ****************************************************************************************************
$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

// ****************************************************************************************************
// CREATE TNS SLIDER
// ****************************************************************************************************

function create_tns_slider(slider){   
    var show_controls = slider.dataset['showControls'],
        nav_position = slider.dataset['navPosition'],
        show_nav = slider.dataset['showNav'],
        autoplay = slider.dataset['autoplay'],
        autoplay_timeout = parseInt(slider.dataset['autoplayTimeout']),
        speed = parseInt(slider.dataset['speed']),
        autoHeight = slider.dataset['autoHeight'],
        mobile = slider.dataset['mobile'],
        tablet = slider.dataset['tablet'],
        laptop = slider.dataset['laptop'],
        desktop = slider.dataset['desktop'],
        axis = slider.dataset['axis'],
        mode = slider.dataset['mode'],
        touch = slider.dataset['touch'],
        mobile_gutter = slider.dataset['mobileGutter'],
        tablet_gutter = slider.dataset['tabletGutter'],
        laptop_gutter = slider.dataset['laptopGutter'],
        desktop_gutter = slider.dataset['desktopGutter'];

    show_controls = ( show_controls == '1' ) ? true : false;
    show_nav = ( show_nav == '1' ) ? true : false;
    autoplay = ( autoplay == '1' ) ? true : false;
    autoHeight = ( autoHeight == '1' ) ? true : false;
    touch = ( touch == '1' ) ? true : false;
    mobile = ( mobile != '' ) ? mobile : 1;
    tablet = ( tablet != '' ) ? tablet : 2;
    laptop = ( laptop != '' ) ? laptop : 3;
    desktop = ( desktop != '' ) ? desktop : 4;
    axis = ( axis != '' ) ? axis : 'horizontal',
    mode = ( mode != '' ) ? mode : 'carousel';

    var slider_options = {  
        mode: mode, touch: touch, container: slider, autoplayButton: false, autoplay: autoplay, autoplayButtonOutput: false, loop: true, 
        speed: speed,
        autoplayTimeout: autoplay_timeout,
        axis:axis, controlsText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'], rewind: true, autoHeight: autoHeight,
        mouseDrag: true, controls: show_controls, nav: show_nav, navPosition: nav_position, responsive : {
            1401 : {items:desktop, slideBy:desktop, gutter: desktop_gutter},
            1025 : {items:laptop, slideBy:laptop, gutter: laptop_gutter},
            601 : {items:tablet, slideBy:tablet, gutter: tablet_gutter},
            100 : {items:mobile, slideBy:mobile, gutter: mobile_gutter},
        }
    };   
        
    return tns(slider_options);
}

// ****************************************************************************************************
// GET A COOKIE
// ****************************************************************************************************

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
}

// ****************************************************************************************************
// HEXADECIMAL COLOR TO RGBA
// ****************************************************************************************************

function hexToRgba(hex, alpha) {
    // Remover el símbolo '#' si está presente
    hex = hex.replace(/^#/, '');

    // Si el valor hexadecimal es de 3 dígitos, convertirlo a 6 dígitos
    if (hex.length === 3) {
        hex = hex.split('').map(char => char + char).join('');
    }

    // Convertir los valores hexadecimales a RGB
    const bigint = parseInt(hex, 16);
    const r = (bigint >> 16) & 255;
    const g = (bigint >> 8) & 255;
    const b = bigint & 255;

    // Convertir el valor de alpha de 0-100 a 0-1
    const a = alpha / 100;

    // Retornar el valor en formato rgba
    return `rgba(${r}, ${g}, ${b}, ${a})`;
}