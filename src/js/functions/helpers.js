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