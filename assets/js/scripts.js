"use strict";

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }
function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }
function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }
function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e70) { throw _e70; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e71) { didErr = true; err = _e71; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
function _get() { if (typeof Reflect !== "undefined" && Reflect.get) { _get = Reflect.get.bind(); } else { _get = function _get(target, property, receiver) { var base = _superPropBase(target, property); if (!base) return; var desc = Object.getOwnPropertyDescriptor(base, property); if (desc.get) { return desc.get.call(arguments.length < 3 ? target : receiver); } return desc.value; }; } return _get.apply(this, arguments); }
function _superPropBase(object, property) { while (!Object.prototype.hasOwnProperty.call(object, property)) { object = _getPrototypeOf(object); if (object === null) break; } return object; }
function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); Object.defineProperty(subClass, "prototype", { writable: false }); if (superClass) _setPrototypeOf(subClass, superClass); }
function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf ? Object.setPrototypeOf.bind() : function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }
function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }
function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } else if (call !== void 0) { throw new TypeError("Derived constructors may only return object or undefined"); } return _assertThisInitialized(self); }
function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }
function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }
function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf.bind() : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
/*! cash-dom 1.3.5, https://github.com/kenwheeler/cash @license MIT */
(function (factory) {
  window.cash = factory();
})(function () {
  var doc = document,
    win = window,
    ArrayProto = Array.prototype,
    slice = ArrayProto.slice,
    _filter = ArrayProto.filter,
    push = ArrayProto.push;
  var noop = function noop() {},
    isFunction = function isFunction(item) {
      // @see https://crbug.com/568448
      return _typeof(item) === _typeof(noop) && item.call;
    },
    isString = function isString(item) {
      return _typeof(item) === _typeof("");
    };
  var idMatch = /^#[\w-]*$/,
    classMatch = /^\.[\w-]*$/,
    htmlMatch = /<.+>/,
    singlet = /^\w+$/;
  function _find(selector, context) {
    context = context || doc;
    var elems = classMatch.test(selector) ? context.getElementsByClassName(selector.slice(1)) : singlet.test(selector) ? context.getElementsByTagName(selector) : context.querySelectorAll(selector);
    return elems;
  }
  var frag;
  function parseHTML(str) {
    if (!frag) {
      frag = doc.implementation.createHTMLDocument(null);
      var base = frag.createElement("base");
      base.href = doc.location.href;
      frag.head.appendChild(base);
    }
    frag.body.innerHTML = str;
    return frag.body.childNodes;
  }
  function onReady(fn) {
    if (doc.readyState !== "loading") {
      fn();
    } else {
      doc.addEventListener("DOMContentLoaded", fn);
    }
  }
  function Init(selector, context) {
    if (!selector) {
      return this;
    }

    // If already a cash collection, don't do any further processing
    if (selector.cash && selector !== win) {
      return selector;
    }
    var elems = selector,
      i = 0,
      length;
    if (isString(selector)) {
      elems = idMatch.test(selector) ?
      // If an ID use the faster getElementById check
      doc.getElementById(selector.slice(1)) : htmlMatch.test(selector) ?
      // If HTML, parse it into real elements
      parseHTML(selector) :
      // else use `find`
      _find(selector, context);

      // If function, use as shortcut for DOM ready
    } else if (isFunction(selector)) {
      onReady(selector);
      return this;
    }
    if (!elems) {
      return this;
    }

    // If a single DOM element is passed in or received via ID, return the single element
    if (elems.nodeType || elems === win) {
      this[0] = elems;
      this.length = 1;
    } else {
      // Treat like an array and loop through each item.
      length = this.length = elems.length;
      for (; i < length; i++) {
        this[i] = elems[i];
      }
    }
    return this;
  }
  function cash(selector, context) {
    return new Init(selector, context);
  }
  var fn = cash.fn = cash.prototype = Init.prototype = {
    // jshint ignore:line
    cash: true,
    length: 0,
    push: push,
    splice: ArrayProto.splice,
    map: ArrayProto.map,
    init: Init
  };
  Object.defineProperty(fn, "constructor", {
    value: cash
  });
  cash.parseHTML = parseHTML;
  cash.noop = noop;
  cash.isFunction = isFunction;
  cash.isString = isString;
  cash.extend = fn.extend = function (target) {
    target = target || {};
    var args = slice.call(arguments),
      length = args.length,
      i = 1;
    if (args.length === 1) {
      target = this;
      i = 0;
    }
    for (; i < length; i++) {
      if (!args[i]) {
        continue;
      }
      for (var key in args[i]) {
        if (args[i].hasOwnProperty(key)) {
          target[key] = args[i][key];
        }
      }
    }
    return target;
  };
  function _each(collection, callback) {
    var l = collection.length,
      i = 0;
    for (; i < l; i++) {
      if (callback.call(collection[i], collection[i], i, collection) === false) {
        break;
      }
    }
  }
  function matches(el, selector) {
    var m = el && (el.matches || el.webkitMatchesSelector || el.mozMatchesSelector || el.msMatchesSelector || el.oMatchesSelector);
    return !!m && m.call(el, selector);
  }
  function getCompareFunction(selector) {
    return (/* Use browser's `matches` function if string */
      isString(selector) ? matches : /* Match a cash element */
      selector.cash ? function (el) {
        return selector.is(el);
      } : /* Direct comparison */
      function (el, selector) {
        return el === selector;
      }
    );
  }
  function unique(collection) {
    return cash(slice.call(collection).filter(function (item, index, self) {
      return self.indexOf(item) === index;
    }));
  }
  cash.extend({
    merge: function merge(first, second) {
      var len = +second.length,
        i = first.length,
        j = 0;
      for (; j < len; i++, j++) {
        first[i] = second[j];
      }
      first.length = i;
      return first;
    },
    each: _each,
    matches: matches,
    unique: unique,
    isArray: Array.isArray,
    isNumeric: function isNumeric(n) {
      return !isNaN(parseFloat(n)) && isFinite(n);
    }
  });
  var uid = cash.uid = "_cash" + Date.now();
  function getDataCache(node) {
    return node[uid] = node[uid] || {};
  }
  function setData(node, key, value) {
    return getDataCache(node)[key] = value;
  }
  function getData(node, key) {
    var c = getDataCache(node);
    if (c[key] === undefined) {
      c[key] = node.dataset ? node.dataset[key] : cash(node).attr("data-" + key);
    }
    return c[key];
  }
  function _removeData(node, key) {
    var c = getDataCache(node);
    if (c) {
      delete c[key];
    } else if (node.dataset) {
      delete node.dataset[key];
    } else {
      cash(node).removeAttr("data-" + name);
    }
  }
  fn.extend({
    data: function data(name, value) {
      if (isString(name)) {
        return value === undefined ? getData(this[0], name) : this.each(function (v) {
          return setData(v, name, value);
        });
      }
      for (var key in name) {
        this.data(key, name[key]);
      }
      return this;
    },
    removeData: function removeData(key) {
      return this.each(function (v) {
        return _removeData(v, key);
      });
    }
  });
  var notWhiteMatch = /\S+/g;
  function getClasses(c) {
    return isString(c) && c.match(notWhiteMatch);
  }
  function _hasClass2(v, c) {
    return v.classList ? v.classList.contains(c) : new RegExp("(^| )" + c + "( |$)", "gi").test(v.className);
  }
  function _addClass2(v, c, spacedName) {
    if (v.classList) {
      v.classList.add(c);
    } else if (spacedName.indexOf(" " + c + " ")) {
      v.className += " " + c;
    }
  }
  function _removeClass2(v, c) {
    if (v.classList) {
      v.classList.remove(c);
    } else {
      v.className = v.className.replace(c, "");
    }
  }
  fn.extend({
    addClass: function addClass(c) {
      var classes = getClasses(c);
      return classes ? this.each(function (v) {
        var spacedName = " " + v.className + " ";
        _each(classes, function (c) {
          _addClass2(v, c, spacedName);
        });
      }) : this;
    },
    attr: function attr(name, value) {
      if (!name) {
        return undefined;
      }
      if (isString(name)) {
        if (value === undefined) {
          return this[0] ? this[0].getAttribute ? this[0].getAttribute(name) : this[0][name] : undefined;
        }
        return this.each(function (v) {
          if (v.setAttribute) {
            v.setAttribute(name, value);
          } else {
            v[name] = value;
          }
        });
      }
      for (var key in name) {
        this.attr(key, name[key]);
      }
      return this;
    },
    hasClass: function hasClass(c) {
      var check = false,
        classes = getClasses(c);
      if (classes && classes.length) {
        this.each(function (v) {
          check = _hasClass2(v, classes[0]);
          return !check;
        });
      }
      return check;
    },
    prop: function prop(name, value) {
      if (isString(name)) {
        return value === undefined ? this[0][name] : this.each(function (v) {
          v[name] = value;
        });
      }
      for (var key in name) {
        this.prop(key, name[key]);
      }
      return this;
    },
    removeAttr: function removeAttr(name) {
      return this.each(function (v) {
        if (v.removeAttribute) {
          v.removeAttribute(name);
        } else {
          delete v[name];
        }
      });
    },
    removeClass: function removeClass(c) {
      if (!arguments.length) {
        return this.attr("class", "");
      }
      var classes = getClasses(c);
      return classes ? this.each(function (v) {
        _each(classes, function (c) {
          _removeClass2(v, c);
        });
      }) : this;
    },
    removeProp: function removeProp(name) {
      return this.each(function (v) {
        delete v[name];
      });
    },
    toggleClass: function toggleClass(c, state) {
      if (state !== undefined) {
        return this[state ? "addClass" : "removeClass"](c);
      }
      var classes = getClasses(c);
      return classes ? this.each(function (v) {
        var spacedName = " " + v.className + " ";
        _each(classes, function (c) {
          if (_hasClass2(v, c)) {
            _removeClass2(v, c);
          } else {
            _addClass2(v, c, spacedName);
          }
        });
      }) : this;
    }
  });
  fn.extend({
    add: function add(selector, context) {
      return unique(cash.merge(this, cash(selector, context)));
    },
    each: function each(callback) {
      _each(this, callback);
      return this;
    },
    eq: function eq(index) {
      return cash(this.get(index));
    },
    filter: function filter(selector) {
      if (!selector) {
        return this;
      }
      var comparator = isFunction(selector) ? selector : getCompareFunction(selector);
      return cash(_filter.call(this, function (e) {
        return comparator(e, selector);
      }));
    },
    first: function first() {
      return this.eq(0);
    },
    get: function get(index) {
      if (index === undefined) {
        return slice.call(this);
      }
      return index < 0 ? this[index + this.length] : this[index];
    },
    index: function index(elem) {
      var child = elem ? cash(elem)[0] : this[0],
        collection = elem ? this : cash(child).parent().children();
      return slice.call(collection).indexOf(child);
    },
    last: function last() {
      return this.eq(-1);
    }
  });
  var camelCase = function () {
    var camelRegex = /(?:^\w|[A-Z]|\b\w)/g,
      whiteSpace = /[\s-_]+/g;
    return function (str) {
      return str.replace(camelRegex, function (letter, index) {
        return letter[index === 0 ? "toLowerCase" : "toUpperCase"]();
      }).replace(whiteSpace, "");
    };
  }();
  var getPrefixedProp = function () {
    var cache = {},
      doc = document,
      div = doc.createElement("div"),
      style = div.style;
    return function (prop) {
      prop = camelCase(prop);
      if (cache[prop]) {
        return cache[prop];
      }
      var ucProp = prop.charAt(0).toUpperCase() + prop.slice(1),
        prefixes = ["webkit", "moz", "ms", "o"],
        props = (prop + " " + prefixes.join(ucProp + " ") + ucProp).split(" ");
      _each(props, function (p) {
        if (p in style) {
          cache[p] = prop = cache[prop] = p;
          return false;
        }
      });
      return cache[prop];
    };
  }();
  cash.prefixedProp = getPrefixedProp;
  cash.camelCase = camelCase;
  fn.extend({
    css: function css(prop, value) {
      if (isString(prop)) {
        prop = getPrefixedProp(prop);
        return arguments.length > 1 ? this.each(function (v) {
          return v.style[prop] = value;
        }) : win.getComputedStyle(this[0])[prop];
      }
      for (var key in prop) {
        this.css(key, prop[key]);
      }
      return this;
    }
  });
  function compute(el, prop) {
    return parseInt(win.getComputedStyle(el[0], null)[prop], 10) || 0;
  }
  _each(["Width", "Height"], function (v) {
    var lower = v.toLowerCase();
    fn[lower] = function () {
      return this[0].getBoundingClientRect()[lower];
    };
    fn["inner" + v] = function () {
      return this[0]["client" + v];
    };
    fn["outer" + v] = function (margins) {
      return this[0]["offset" + v] + (margins ? compute(this, "margin" + (v === "Width" ? "Left" : "Top")) + compute(this, "margin" + (v === "Width" ? "Right" : "Bottom")) : 0);
    };
  });
  function registerEvent(node, eventName, callback) {
    var eventCache = getData(node, "_cashEvents") || setData(node, "_cashEvents", {});
    eventCache[eventName] = eventCache[eventName] || [];
    eventCache[eventName].push(callback);
    node.addEventListener(eventName, callback);
  }
  function removeEvent(node, eventName, callback) {
    var events = getData(node, "_cashEvents"),
      eventCache = events && events[eventName],
      index;
    if (!eventCache) {
      return;
    }
    if (callback) {
      node.removeEventListener(eventName, callback);
      index = eventCache.indexOf(callback);
      if (index >= 0) {
        eventCache.splice(index, 1);
      }
    } else {
      _each(eventCache, function (event) {
        node.removeEventListener(eventName, event);
      });
      eventCache = [];
    }
  }
  fn.extend({
    off: function off(eventName, callback) {
      return this.each(function (v) {
        return removeEvent(v, eventName, callback);
      });
    },
    on: function on(eventName, delegate, callback, runOnce) {
      // jshint ignore:line
      var originalCallback;
      if (!isString(eventName)) {
        for (var key in eventName) {
          this.on(key, delegate, eventName[key]);
        }
        return this;
      }
      if (isFunction(delegate)) {
        callback = delegate;
        delegate = null;
      }
      if (eventName === "ready") {
        onReady(callback);
        return this;
      }
      if (delegate) {
        originalCallback = callback;
        callback = function callback(e) {
          var t = e.target;
          while (!matches(t, delegate)) {
            if (t === this || t === null) {
              return t = false;
            }
            t = t.parentNode;
          }
          if (t) {
            originalCallback.call(t, e);
          }
        };
      }
      return this.each(function (v) {
        var _finalCallback = callback;
        if (runOnce) {
          _finalCallback = function finalCallback() {
            callback.apply(this, arguments);
            removeEvent(v, eventName, _finalCallback);
          };
        }
        registerEvent(v, eventName, _finalCallback);
      });
    },
    one: function one(eventName, delegate, callback) {
      return this.on(eventName, delegate, callback, true);
    },
    ready: onReady,
    /**
     * Modified
     * Triggers browser event
     * @param String eventName
     * @param Object data - Add properties to event object
     */
    trigger: function trigger(eventName, data) {
      if (document.createEvent) {
        var evt = document.createEvent('HTMLEvents');
        evt.initEvent(eventName, true, false);
        evt = this.extend(evt, data);
        return this.each(function (v) {
          return v.dispatchEvent(evt);
        });
      }
    }
  });
  function encode(name, value) {
    return "&" + encodeURIComponent(name) + "=" + encodeURIComponent(value).replace(/%20/g, "+");
  }
  function getSelectMultiple_(el) {
    var values = [];
    _each(el.options, function (o) {
      if (o.selected) {
        values.push(o.value);
      }
    });
    return values.length ? values : null;
  }
  function getSelectSingle_(el) {
    var selectedIndex = el.selectedIndex;
    return selectedIndex >= 0 ? el.options[selectedIndex].value : null;
  }
  function getValue(el) {
    var type = el.type;
    if (!type) {
      return null;
    }
    switch (type.toLowerCase()) {
      case "select-one":
        return getSelectSingle_(el);
      case "select-multiple":
        return getSelectMultiple_(el);
      case "radio":
        return el.checked ? el.value : null;
      case "checkbox":
        return el.checked ? el.value : null;
      default:
        return el.value ? el.value : null;
    }
  }
  fn.extend({
    serialize: function serialize() {
      var query = "";
      _each(this[0].elements || this, function (el) {
        if (el.disabled || el.tagName === "FIELDSET") {
          return;
        }
        var name = el.name;
        switch (el.type.toLowerCase()) {
          case "file":
          case "reset":
          case "submit":
          case "button":
            break;
          case "select-multiple":
            var values = getValue(el);
            if (values !== null) {
              _each(values, function (value) {
                query += encode(name, value);
              });
            }
            break;
          default:
            var value = getValue(el);
            if (value !== null) {
              query += encode(name, value);
            }
        }
      });
      return query.substr(1);
    },
    val: function val(value) {
      if (value === undefined) {
        return getValue(this[0]);
      }
      return this.each(function (v) {
        return v.value = value;
      });
    }
  });
  function insertElement(el, child, prepend) {
    if (prepend) {
      var first = el.childNodes[0];
      el.insertBefore(child, first);
    } else {
      el.appendChild(child);
    }
  }
  function insertContent(parent, child, prepend) {
    var str = isString(child);
    if (!str && child.length) {
      _each(child, function (v) {
        return insertContent(parent, v, prepend);
      });
      return;
    }
    _each(parent, str ? function (v) {
      return v.insertAdjacentHTML(prepend ? "afterbegin" : "beforeend", child);
    } : function (v, i) {
      return insertElement(v, i === 0 ? child : child.cloneNode(true), prepend);
    });
  }
  fn.extend({
    after: function after(selector) {
      cash(selector).insertAfter(this);
      return this;
    },
    append: function append(content) {
      insertContent(this, content);
      return this;
    },
    appendTo: function appendTo(parent) {
      insertContent(cash(parent), this);
      return this;
    },
    before: function before(selector) {
      cash(selector).insertBefore(this);
      return this;
    },
    clone: function clone() {
      return cash(this.map(function (v) {
        return v.cloneNode(true);
      }));
    },
    empty: function empty() {
      this.html("");
      return this;
    },
    html: function html(content) {
      if (content === undefined) {
        return this[0].innerHTML;
      }
      var source = content.nodeType ? content[0].outerHTML : content;
      return this.each(function (v) {
        return v.innerHTML = source;
      });
    },
    insertAfter: function insertAfter(selector) {
      var _this = this;
      cash(selector).each(function (el, i) {
        var parent = el.parentNode,
          sibling = el.nextSibling;
        _this.each(function (v) {
          parent.insertBefore(i === 0 ? v : v.cloneNode(true), sibling);
        });
      });
      return this;
    },
    insertBefore: function insertBefore(selector) {
      var _this2 = this;
      cash(selector).each(function (el, i) {
        var parent = el.parentNode;
        _this2.each(function (v) {
          parent.insertBefore(i === 0 ? v : v.cloneNode(true), el);
        });
      });
      return this;
    },
    prepend: function prepend(content) {
      insertContent(this, content, true);
      return this;
    },
    prependTo: function prependTo(parent) {
      insertContent(cash(parent), this, true);
      return this;
    },
    remove: function remove() {
      return this.each(function (v) {
        if (!!v.parentNode) {
          return v.parentNode.removeChild(v);
        }
      });
    },
    text: function text(content) {
      if (content === undefined) {
        return this[0].textContent;
      }
      return this.each(function (v) {
        return v.textContent = content;
      });
    }
  });
  var docEl = doc.documentElement;
  fn.extend({
    position: function position() {
      var el = this[0];
      return {
        left: el.offsetLeft,
        top: el.offsetTop
      };
    },
    offset: function offset() {
      var rect = this[0].getBoundingClientRect();
      return {
        top: rect.top + win.pageYOffset - docEl.clientTop,
        left: rect.left + win.pageXOffset - docEl.clientLeft
      };
    },
    offsetParent: function offsetParent() {
      return cash(this[0].offsetParent);
    }
  });
  fn.extend({
    children: function children(selector) {
      var elems = [];
      this.each(function (el) {
        push.apply(elems, el.children);
      });
      elems = unique(elems);
      return !selector ? elems : elems.filter(function (v) {
        return matches(v, selector);
      });
    },
    closest: function closest(selector) {
      if (!selector || this.length < 1) {
        return cash();
      }
      if (this.is(selector)) {
        return this.filter(selector);
      }
      return this.parent().closest(selector);
    },
    is: function is(selector) {
      if (!selector) {
        return false;
      }
      var match = false,
        comparator = getCompareFunction(selector);
      this.each(function (el) {
        match = comparator(el, selector);
        return !match;
      });
      return match;
    },
    find: function find(selector) {
      if (!selector || selector.nodeType) {
        return cash(selector && this.has(selector).length ? selector : null);
      }
      var elems = [];
      this.each(function (el) {
        push.apply(elems, _find(selector, el));
      });
      return unique(elems);
    },
    has: function has(selector) {
      var comparator = isString(selector) ? function (el) {
        return _find(selector, el).length !== 0;
      } : function (el) {
        return el.contains(selector);
      };
      return this.filter(comparator);
    },
    next: function next() {
      return cash(this[0].nextElementSibling);
    },
    not: function not(selector) {
      if (!selector) {
        return this;
      }
      var comparator = getCompareFunction(selector);
      return this.filter(function (el) {
        return !comparator(el, selector);
      });
    },
    parent: function parent() {
      var result = [];
      this.each(function (item) {
        if (item && item.parentNode) {
          result.push(item.parentNode);
        }
      });
      return unique(result);
    },
    parents: function parents(selector) {
      var last,
        result = [];
      this.each(function (item) {
        last = item;
        while (last && last.parentNode && last !== doc.body.parentNode) {
          last = last.parentNode;
          if (!selector || selector && matches(last, selector)) {
            result.push(last);
          }
        }
      });
      return unique(result);
    },
    prev: function prev() {
      return cash(this[0].previousElementSibling);
    },
    siblings: function siblings(selector) {
      var collection = this.parent().children(selector),
        el = this[0];
      return collection.filter(function (i) {
        return i !== el;
      });
    }
  });
  return cash;
});
var Component = /*#__PURE__*/function () {
  /**
   * Generic constructor for all components
   * @constructor
   * @param {Element} el
   * @param {Object} options
   */
  function Component(classDef, el, options) {
    _classCallCheck(this, Component);
    // Display error if el is valid HTML Element
    if (!(el instanceof Element)) {
      console.error(Error(el + ' is not an HTML Element'));
    }

    // If exists, destroy and reinitialize in child
    var ins = classDef.getInstance(el);
    if (!!ins) {
      ins.destroy();
    }
    this.el = el;
    this.$el = cash(el);
  }

  /**
   * Initializes components
   * @param {class} classDef
   * @param {Element | NodeList | jQuery} els
   * @param {Object} options
   */
  _createClass(Component, null, [{
    key: "init",
    value: function init(classDef, els, options) {
      var instances = null;
      if (els instanceof Element) {
        instances = new classDef(els, options);
      } else if (!!els && (els.jquery || els.cash || els instanceof NodeList)) {
        var instancesArr = [];
        for (var i = 0; i < els.length; i++) {
          instancesArr.push(new classDef(els[i], options));
        }
        instances = instancesArr;
      }
      return instances;
    }
  }]);
  return Component;
}(); // Required for Meteor package, the use of window prevents export by Meteor
(function (window) {
  if (window.Package) {
    M = {};
  } else {
    window.M = {};
  }

  // Check for jQuery
  M.jQueryLoaded = !!window.jQuery;
})(window);

// AMD
if (typeof define === 'function' && define.amd) {
  define('M', [], function () {
    return M;
  });

  // Common JS
} else if (typeof exports !== 'undefined' && !exports.nodeType) {
  if (typeof module !== 'undefined' && !module.nodeType && module.exports) {
    exports = module.exports = M;
  }
  exports["default"] = M;
}
M.version = '1.0.0';
M.keys = {
  TAB: 9,
  ENTER: 13,
  ESC: 27,
  ARROW_UP: 38,
  ARROW_DOWN: 40
};

/**
 * TabPress Keydown handler
 */
M.tabPressed = false;
M.keyDown = false;
var docHandleKeydown = function docHandleKeydown(e) {
  M.keyDown = true;
  if (e.which === M.keys.TAB || e.which === M.keys.ARROW_DOWN || e.which === M.keys.ARROW_UP) {
    M.tabPressed = true;
  }
};
var docHandleKeyup = function docHandleKeyup(e) {
  M.keyDown = false;
  if (e.which === M.keys.TAB || e.which === M.keys.ARROW_DOWN || e.which === M.keys.ARROW_UP) {
    M.tabPressed = false;
  }
};
var docHandleFocus = function docHandleFocus(e) {
  if (M.keyDown) {
    document.body.classList.add('keyboard-focused');
  }
};
var docHandleBlur = function docHandleBlur(e) {
  document.body.classList.remove('keyboard-focused');
};
document.addEventListener('keydown', docHandleKeydown, true);
document.addEventListener('keyup', docHandleKeyup, true);
document.addEventListener('focus', docHandleFocus, true);
document.addEventListener('blur', docHandleBlur, true);

/**
 * Initialize jQuery wrapper for plugin
 * @param {Class} plugin  javascript class
 * @param {string} pluginName  jQuery plugin name
 * @param {string} classRef  Class reference name
 */
M.initializeJqueryWrapper = function (plugin, pluginName, classRef) {
  jQuery.fn[pluginName] = function (methodOrOptions) {
    // Call plugin method if valid method name is passed in
    if (plugin.prototype[methodOrOptions]) {
      var params = Array.prototype.slice.call(arguments, 1);

      // Getter methods
      if (methodOrOptions.slice(0, 3) === 'get') {
        var instance = this.first()[0][classRef];
        return instance[methodOrOptions].apply(instance, params);
      }

      // Void methods
      return this.each(function () {
        var instance = this[classRef];
        instance[methodOrOptions].apply(instance, params);
      });

      // Initialize plugin if options or no argument is passed in
    } else if (_typeof(methodOrOptions) === 'object' || !methodOrOptions) {
      plugin.init(this, arguments[0]);
      return this;
    }

    // Return error if an unrecognized  method name is passed in
    jQuery.error("Method ".concat(methodOrOptions, " does not exist on jQuery.").concat(pluginName));
  };
};

/**
 * Automatically initialize components
 * @param {Element} context  DOM Element to search within for components
 */
M.AutoInit = function (context) {
  // Use document.body if no context is given
  var root = !!context ? context : document.body;
  var registry = {
    Autocomplete: root.querySelectorAll('.autocomplete:not(.no-autoinit)'),
    Carousel: root.querySelectorAll('.carousel:not(.no-autoinit)'),
    Chips: root.querySelectorAll('.chips:not(.no-autoinit)'),
    Collapsible: root.querySelectorAll('.collapsible:not(.no-autoinit)'),
    Datepicker: root.querySelectorAll('.datepicker:not(.no-autoinit)'),
    Dropdown: root.querySelectorAll('.dropdown-trigger:not(.no-autoinit)'),
    Materialbox: root.querySelectorAll('.materialboxed:not(.no-autoinit)'),
    Modal: root.querySelectorAll('.modal:not(.no-autoinit)'),
    Parallax: root.querySelectorAll('.parallax:not(.no-autoinit)'),
    Pushpin: root.querySelectorAll('.pushpin:not(.no-autoinit)'),
    ScrollSpy: root.querySelectorAll('.scrollspy:not(.no-autoinit)'),
    FormSelect: root.querySelectorAll('select:not(.no-autoinit)'),
    Sidenav: root.querySelectorAll('.sidenav:not(.no-autoinit)'),
    Tabs: root.querySelectorAll('.tabs:not(.no-autoinit)'),
    TapTarget: root.querySelectorAll('.tap-target:not(.no-autoinit)'),
    Timepicker: root.querySelectorAll('.timepicker:not(.no-autoinit)'),
    Tooltip: root.querySelectorAll('.tooltipped:not(.no-autoinit)'),
    FloatingActionButton: root.querySelectorAll('.fixed-action-btn:not(.no-autoinit)')
  };
  for (var pluginName in registry) {
    var plugin = M[pluginName];
    plugin.init(registry[pluginName]);
  }
};

/**
 * Generate approximated selector string for a jQuery object
 * @param {jQuery} obj  jQuery object to be parsed
 * @returns {string}
 */
M.objectSelectorString = function (obj) {
  var tagStr = obj.prop('tagName') || '';
  var idStr = obj.attr('id') || '';
  var classStr = obj.attr('class') || '';
  return (tagStr + idStr + classStr).replace(/\s/g, '');
};

// Unique Random ID
M.guid = function () {
  function s4() {
    return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
  }
  return function () {
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
  };
}();

/**
 * Escapes hash from special characters
 * @param {string} hash  String returned from this.hash
 * @returns {string}
 */
M.escapeHash = function (hash) {
  return hash.replace(/(:|\.|\[|\]|,|=|\/)/g, '\\$1');
};
M.elementOrParentIsFixed = function (element) {
  var $element = $(element);
  var $checkElements = $element.add($element.parents());
  var isFixed = false;
  $checkElements.each(function () {
    if ($(this).css('position') === 'fixed') {
      isFixed = true;
      return false;
    }
  });
  return isFixed;
};

/**
 * @typedef {Object} Edges
 * @property {Boolean} top  If the top edge was exceeded
 * @property {Boolean} right  If the right edge was exceeded
 * @property {Boolean} bottom  If the bottom edge was exceeded
 * @property {Boolean} left  If the left edge was exceeded
 */

/**
 * @typedef {Object} Bounding
 * @property {Number} left  left offset coordinate
 * @property {Number} top  top offset coordinate
 * @property {Number} width
 * @property {Number} height
 */

/**
 * Escapes hash from special characters
 * @param {Element} container  Container element that acts as the boundary
 * @param {Bounding} bounding  element bounding that is being checked
 * @param {Number} offset  offset from edge that counts as exceeding
 * @returns {Edges}
 */
M.checkWithinContainer = function (container, bounding, offset) {
  var edges = {
    top: false,
    right: false,
    bottom: false,
    left: false
  };
  var containerRect = container.getBoundingClientRect();
  // If body element is smaller than viewport, use viewport height instead.
  var containerBottom = container === document.body ? Math.max(containerRect.bottom, window.innerHeight) : containerRect.bottom;
  var scrollLeft = container.scrollLeft;
  var scrollTop = container.scrollTop;
  var scrolledX = bounding.left - scrollLeft;
  var scrolledY = bounding.top - scrollTop;

  // Check for container and viewport for each edge
  if (scrolledX < containerRect.left + offset || scrolledX < offset) {
    edges.left = true;
  }
  if (scrolledX + bounding.width > containerRect.right - offset || scrolledX + bounding.width > window.innerWidth - offset) {
    edges.right = true;
  }
  if (scrolledY < containerRect.top + offset || scrolledY < offset) {
    edges.top = true;
  }
  if (scrolledY + bounding.height > containerBottom - offset || scrolledY + bounding.height > window.innerHeight - offset) {
    edges.bottom = true;
  }
  return edges;
};
M.checkPossibleAlignments = function (el, container, bounding, offset) {
  var canAlign = {
    top: true,
    right: true,
    bottom: true,
    left: true,
    spaceOnTop: null,
    spaceOnRight: null,
    spaceOnBottom: null,
    spaceOnLeft: null
  };
  var containerAllowsOverflow = getComputedStyle(container).overflow === 'visible';
  var containerRect = container.getBoundingClientRect();
  var containerHeight = Math.min(containerRect.height, window.innerHeight);
  var containerWidth = Math.min(containerRect.width, window.innerWidth);
  var elOffsetRect = el.getBoundingClientRect();
  var scrollLeft = container.scrollLeft;
  var scrollTop = container.scrollTop;
  var scrolledX = bounding.left - scrollLeft;
  var scrolledYTopEdge = bounding.top - scrollTop;
  var scrolledYBottomEdge = bounding.top + elOffsetRect.height - scrollTop;

  // Check for container and viewport for left
  canAlign.spaceOnRight = !containerAllowsOverflow ? containerWidth - (scrolledX + bounding.width) : window.innerWidth - (elOffsetRect.left + bounding.width);
  if (canAlign.spaceOnRight < 0) {
    canAlign.left = false;
  }

  // Check for container and viewport for Right
  canAlign.spaceOnLeft = !containerAllowsOverflow ? scrolledX - bounding.width + elOffsetRect.width : elOffsetRect.right - bounding.width;
  if (canAlign.spaceOnLeft < 0) {
    canAlign.right = false;
  }

  // Check for container and viewport for Top
  canAlign.spaceOnBottom = !containerAllowsOverflow ? containerHeight - (scrolledYTopEdge + bounding.height + offset) : window.innerHeight - (elOffsetRect.top + bounding.height + offset);
  if (canAlign.spaceOnBottom < 0) {
    canAlign.top = false;
  }

  // Check for container and viewport for Bottom
  canAlign.spaceOnTop = !containerAllowsOverflow ? scrolledYBottomEdge - (bounding.height - offset) : elOffsetRect.bottom - (bounding.height + offset);
  if (canAlign.spaceOnTop < 0) {
    canAlign.bottom = false;
  }
  return canAlign;
};
M.getOverflowParent = function (element) {
  if (element == null) {
    return null;
  }
  if (element === document.body || getComputedStyle(element).overflow !== 'visible') {
    return element;
  }
  return M.getOverflowParent(element.parentElement);
};

/**
 * Gets id of component from a trigger
 * @param {Element} trigger  trigger
 * @returns {string}
 */
M.getIdFromTrigger = function (trigger) {
  var id = trigger.getAttribute('data-target');
  if (!id) {
    id = trigger.getAttribute('href');
    if (id) {
      id = id.slice(1);
    } else {
      id = '';
    }
  }
  return id;
};

/**
 * Multi browser support for document scroll top
 * @returns {Number}
 */
M.getDocumentScrollTop = function () {
  return window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
};

/**
 * Multi browser support for document scroll left
 * @returns {Number}
 */
M.getDocumentScrollLeft = function () {
  return window.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft || 0;
};

/**
 * @typedef {Object} Edges
 * @property {Boolean} top  If the top edge was exceeded
 * @property {Boolean} right  If the right edge was exceeded
 * @property {Boolean} bottom  If the bottom edge was exceeded
 * @property {Boolean} left  If the left edge was exceeded
 */

/**
 * @typedef {Object} Bounding
 * @property {Number} left  left offset coordinate
 * @property {Number} top  top offset coordinate
 * @property {Number} width
 * @property {Number} height
 */

/**
 * Get time in ms
 * @license https://raw.github.com/jashkenas/underscore/master/LICENSE
 * @type {function}
 * @return {number}
 */
var getTime = Date.now || function () {
  return new Date().getTime();
};

/**
 * Returns a function, that, when invoked, will only be triggered at most once
 * during a given window of time. Normally, the throttled function will run
 * as much as it can, without ever going more than once per `wait` duration;
 * but if you'd like to disable the execution on the leading edge, pass
 * `{leading: false}`. To disable execution on the trailing edge, ditto.
 * @license https://raw.github.com/jashkenas/underscore/master/LICENSE
 * @param {function} func
 * @param {number} wait
 * @param {Object=} options
 * @returns {Function}
 */
M.throttle = function (func, wait, options) {
  var context, args, result;
  var timeout = null;
  var previous = 0;
  options || (options = {});
  var later = function later() {
    previous = options.leading === false ? 0 : getTime();
    timeout = null;
    result = func.apply(context, args);
    context = args = null;
  };
  return function () {
    var now = getTime();
    if (!previous && options.leading === false) previous = now;
    var remaining = wait - (now - previous);
    context = this;
    args = arguments;
    if (remaining <= 0) {
      clearTimeout(timeout);
      timeout = null;
      previous = now;
      result = func.apply(context, args);
      context = args = null;
    } else if (!timeout && options.trailing !== false) {
      timeout = setTimeout(later, remaining);
    }
    return result;
  };
};

/*
 v2.2.0
 2017 Julian Garnier
 Released under the MIT license
*/
var $jscomp = {
  scope: {}
};
$jscomp.defineProperty = "function" == typeof Object.defineProperties ? Object.defineProperty : function (e, r, p) {
  if (p.get || p.set) throw new TypeError("ES3 does not support getters and setters.");
  e != Array.prototype && e != Object.prototype && (e[r] = p.value);
};
$jscomp.getGlobal = function (e) {
  return "undefined" != typeof window && window === e ? e : "undefined" != typeof global && null != global ? global : e;
};
$jscomp.global = $jscomp.getGlobal(void 0);
$jscomp.SYMBOL_PREFIX = "jscomp_symbol_";
$jscomp.initSymbol = function () {
  $jscomp.initSymbol = function () {};
  $jscomp.global.Symbol || ($jscomp.global.Symbol = $jscomp.Symbol);
};
$jscomp.symbolCounter_ = 0;
$jscomp.Symbol = function (e) {
  return $jscomp.SYMBOL_PREFIX + (e || "") + $jscomp.symbolCounter_++;
};
$jscomp.initSymbolIterator = function () {
  $jscomp.initSymbol();
  var e = $jscomp.global.Symbol.iterator;
  e || (e = $jscomp.global.Symbol.iterator = $jscomp.global.Symbol("iterator"));
  "function" != typeof Array.prototype[e] && $jscomp.defineProperty(Array.prototype, e, {
    configurable: !0,
    writable: !0,
    value: function value() {
      return $jscomp.arrayIterator(this);
    }
  });
  $jscomp.initSymbolIterator = function () {};
};
$jscomp.arrayIterator = function (e) {
  var r = 0;
  return $jscomp.iteratorPrototype(function () {
    return r < e.length ? {
      done: !1,
      value: e[r++]
    } : {
      done: !0
    };
  });
};
$jscomp.iteratorPrototype = function (e) {
  $jscomp.initSymbolIterator();
  e = {
    next: e
  };
  e[$jscomp.global.Symbol.iterator] = function () {
    return this;
  };
  return e;
};
$jscomp.array = $jscomp.array || {};
$jscomp.iteratorFromArray = function (e, r) {
  $jscomp.initSymbolIterator();
  e instanceof String && (e += "");
  var p = 0,
    m = {
      next: function next() {
        if (p < e.length) {
          var u = p++;
          return {
            value: r(u, e[u]),
            done: !1
          };
        }
        m.next = function () {
          return {
            done: !0,
            value: void 0
          };
        };
        return m.next();
      }
    };
  m[Symbol.iterator] = function () {
    return m;
  };
  return m;
};
$jscomp.polyfill = function (e, r, p, m) {
  if (r) {
    var p = $jscomp.global;
    e = e.split(".");
    if (!p) p = new Array();
    for (m = 0; m < e.length - 1; m++) {
      var u = e[m];
      u in p || (p[u] = {});
      p = p[u];
    }
    e = e[e.length - 1];
    m = p[e];
    r = r(m);
    r != m && null != r && $jscomp.defineProperty(p, e, {
      configurable: !0,
      writable: !0,
      value: r
    });
  }
};
$jscomp.polyfill("Array.prototype.keys", function (e) {
  return e ? e : function () {
    return $jscomp.iteratorFromArray(this, function (e) {
      return e;
    });
  };
}, "es6-impl", "es3");
var $jscomp$this = void 0;
(function (r) {
  M.anime = r();
})(function () {
  function e(a) {
    if (!h.col(a)) try {
      return document.querySelectorAll(a);
    } catch (c) {}
  }
  function r(a, c) {
    for (var d = a.length, b = 2 <= arguments.length ? arguments[1] : void 0, f = [], n = 0; n < d; n++) if (n in a) {
      var k = a[n];
      c.call(b, k, n, a) && f.push(k);
    }
    return f;
  }
  function p(a) {
    return a.reduce(function (a, d) {
      return a.concat(h.arr(d) ? p(d) : d);
    }, []);
  }
  function m(a) {
    if (h.arr(a)) return a;
    h.str(a) && (a = e(a) || a);
    return a instanceof NodeList || a instanceof HTMLCollection ? [].slice.call(a) : [a];
  }
  function u(a, c) {
    return a.some(function (a) {
      return a === c;
    });
  }
  function C(a) {
    var c = {},
      d;
    for (d in a) c[d] = a[d];
    return c;
  }
  function D(a, c) {
    var d = C(a),
      b;
    for (b in a) d[b] = c.hasOwnProperty(b) ? c[b] : a[b];
    return d;
  }
  function z(a, c) {
    var d = C(a),
      b;
    for (b in c) d[b] = h.und(a[b]) ? c[b] : a[b];
    return d;
  }
  function T(a) {
    a = a.replace(/^#?([a-f\d])([a-f\d])([a-f\d])$/i, function (a, c, d, k) {
      return c + c + d + d + k + k;
    });
    var c = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(a);
    a = parseInt(c[1], 16);
    var d = parseInt(c[2], 16),
      c = parseInt(c[3], 16);
    return "rgba(" + a + "," + d + "," + c + ",1)";
  }
  function U(a) {
    function c(a, c, b) {
      0 > b && (b += 1);
      1 < b && --b;
      return b < 1 / 6 ? a + 6 * (c - a) * b : .5 > b ? c : b < 2 / 3 ? a + (c - a) * (2 / 3 - b) * 6 : a;
    }
    var d = /hsl\((\d+),\s*([\d.]+)%,\s*([\d.]+)%\)/g.exec(a) || /hsla\((\d+),\s*([\d.]+)%,\s*([\d.]+)%,\s*([\d.]+)\)/g.exec(a);
    a = parseInt(d[1]) / 360;
    var b = parseInt(d[2]) / 100,
      f = parseInt(d[3]) / 100,
      d = d[4] || 1;
    if (0 == b) f = b = a = f;else {
      var n = .5 > f ? f * (1 + b) : f + b - f * b,
        k = 2 * f - n,
        f = c(k, n, a + 1 / 3),
        b = c(k, n, a);
      a = c(k, n, a - 1 / 3);
    }
    return "rgba(" + 255 * f + "," + 255 * b + "," + 255 * a + "," + d + ")";
  }
  function y(a) {
    if (a = /([\+\-]?[0-9#\.]+)(%|px|pt|em|rem|in|cm|mm|ex|ch|pc|vw|vh|vmin|vmax|deg|rad|turn)?$/.exec(a)) return a[2];
  }
  function V(a) {
    if (-1 < a.indexOf("translate") || "perspective" === a) return "px";
    if (-1 < a.indexOf("rotate") || -1 < a.indexOf("skew")) return "deg";
  }
  function I(a, c) {
    return h.fnc(a) ? a(c.target, c.id, c.total) : a;
  }
  function E(a, c) {
    if (c in a.style) return getComputedStyle(a).getPropertyValue(c.replace(/([a-z])([A-Z])/g, "$1-$2").toLowerCase()) || "0";
  }
  function J(a, c) {
    if (h.dom(a) && u(W, c)) return "transform";
    if (h.dom(a) && (a.getAttribute(c) || h.svg(a) && a[c])) return "attribute";
    if (h.dom(a) && "transform" !== c && E(a, c)) return "css";
    if (null != a[c]) return "object";
  }
  function X(a, c) {
    var d = V(c),
      d = -1 < c.indexOf("scale") ? 1 : 0 + d;
    a = a.style.transform;
    if (!a) return d;
    for (var b = [], f = [], n = [], k = /(\w+)\((.+?)\)/g; b = k.exec(a);) f.push(b[1]), n.push(b[2]);
    a = r(n, function (a, b) {
      return f[b] === c;
    });
    return a.length ? a[0] : d;
  }
  function K(a, c) {
    switch (J(a, c)) {
      case "transform":
        return X(a, c);
      case "css":
        return E(a, c);
      case "attribute":
        return a.getAttribute(c);
    }
    return a[c] || 0;
  }
  function L(a, c) {
    var d = /^(\*=|\+=|-=)/.exec(a);
    if (!d) return a;
    var b = y(a) || 0;
    c = parseFloat(c);
    a = parseFloat(a.replace(d[0], ""));
    switch (d[0][0]) {
      case "+":
        return c + a + b;
      case "-":
        return c - a + b;
      case "*":
        return c * a + b;
    }
  }
  function F(a, c) {
    return Math.sqrt(Math.pow(c.x - a.x, 2) + Math.pow(c.y - a.y, 2));
  }
  function M(a) {
    a = a.points;
    for (var c = 0, d, b = 0; b < a.numberOfItems; b++) {
      var f = a.getItem(b);
      0 < b && (c += F(d, f));
      d = f;
    }
    return c;
  }
  function N(a) {
    if (a.getTotalLength) return a.getTotalLength();
    switch (a.tagName.toLowerCase()) {
      case "circle":
        return 2 * Math.PI * a.getAttribute("r");
      case "rect":
        return 2 * a.getAttribute("width") + 2 * a.getAttribute("height");
      case "line":
        return F({
          x: a.getAttribute("x1"),
          y: a.getAttribute("y1")
        }, {
          x: a.getAttribute("x2"),
          y: a.getAttribute("y2")
        });
      case "polyline":
        return M(a);
      case "polygon":
        var c = a.points;
        return M(a) + F(c.getItem(c.numberOfItems - 1), c.getItem(0));
    }
  }
  function Y(a, c) {
    function d(b) {
      b = void 0 === b ? 0 : b;
      return a.el.getPointAtLength(1 <= c + b ? c + b : 0);
    }
    var b = d(),
      f = d(-1),
      n = d(1);
    switch (a.property) {
      case "x":
        return b.x;
      case "y":
        return b.y;
      case "angle":
        return 180 * Math.atan2(n.y - f.y, n.x - f.x) / Math.PI;
    }
  }
  function O(a, c) {
    var d = /-?\d*\.?\d+/g,
      b;
    b = h.pth(a) ? a.totalLength : a;
    if (h.col(b)) {
      if (h.rgb(b)) {
        var f = /rgb\((\d+,\s*[\d]+,\s*[\d]+)\)/g.exec(b);
        b = f ? "rgba(" + f[1] + ",1)" : b;
      } else b = h.hex(b) ? T(b) : h.hsl(b) ? U(b) : void 0;
    } else f = (f = y(b)) ? b.substr(0, b.length - f.length) : b, b = c && !/\s/g.test(b) ? f + c : f;
    b += "";
    return {
      original: b,
      numbers: b.match(d) ? b.match(d).map(Number) : [0],
      strings: h.str(a) || c ? b.split(d) : []
    };
  }
  function P(a) {
    a = a ? p(h.arr(a) ? a.map(m) : m(a)) : [];
    return r(a, function (a, d, b) {
      return b.indexOf(a) === d;
    });
  }
  function Z(a) {
    var c = P(a);
    return c.map(function (a, b) {
      return {
        target: a,
        id: b,
        total: c.length
      };
    });
  }
  function aa(a, c) {
    var d = C(c);
    if (h.arr(a)) {
      var b = a.length;
      2 !== b || h.obj(a[0]) ? h.fnc(c.duration) || (d.duration = c.duration / b) : a = {
        value: a
      };
    }
    return m(a).map(function (a, b) {
      b = b ? 0 : c.delay;
      a = h.obj(a) && !h.pth(a) ? a : {
        value: a
      };
      h.und(a.delay) && (a.delay = b);
      return a;
    }).map(function (a) {
      return z(a, d);
    });
  }
  function ba(a, c) {
    var d = {},
      b;
    for (b in a) {
      var f = I(a[b], c);
      h.arr(f) && (f = f.map(function (a) {
        return I(a, c);
      }), 1 === f.length && (f = f[0]));
      d[b] = f;
    }
    d.duration = parseFloat(d.duration);
    d.delay = parseFloat(d.delay);
    return d;
  }
  function ca(a) {
    return h.arr(a) ? A.apply(this, a) : Q[a];
  }
  function da(a, c) {
    var d;
    return a.tweens.map(function (b) {
      b = ba(b, c);
      var f = b.value,
        e = K(c.target, a.name),
        k = d ? d.to.original : e,
        k = h.arr(f) ? f[0] : k,
        w = L(h.arr(f) ? f[1] : f, k),
        e = y(w) || y(k) || y(e);
      b.from = O(k, e);
      b.to = O(w, e);
      b.start = d ? d.end : a.offset;
      b.end = b.start + b.delay + b.duration;
      b.easing = ca(b.easing);
      b.elasticity = (1E3 - Math.min(Math.max(b.elasticity, 1), 999)) / 1E3;
      b.isPath = h.pth(f);
      b.isColor = h.col(b.from.original);
      b.isColor && (b.round = 1);
      return d = b;
    });
  }
  function ea(a, c) {
    return r(p(a.map(function (a) {
      return c.map(function (b) {
        var c = J(a.target, b.name);
        if (c) {
          var d = da(b, a);
          b = {
            type: c,
            property: b.name,
            animatable: a,
            tweens: d,
            duration: d[d.length - 1].end,
            delay: d[0].delay
          };
        } else b = void 0;
        return b;
      });
    })), function (a) {
      return !h.und(a);
    });
  }
  function R(a, c, d, b) {
    var f = "delay" === a;
    return c.length ? (f ? Math.min : Math.max).apply(Math, c.map(function (b) {
      return b[a];
    })) : f ? b.delay : d.offset + b.delay + b.duration;
  }
  function fa(a) {
    var c = D(ga, a),
      d = D(S, a),
      b = Z(a.targets),
      f = [],
      e = z(c, d),
      k;
    for (k in a) e.hasOwnProperty(k) || "targets" === k || f.push({
      name: k,
      offset: e.offset,
      tweens: aa(a[k], d)
    });
    a = ea(b, f);
    return z(c, {
      children: [],
      animatables: b,
      animations: a,
      duration: R("duration", a, c, d),
      delay: R("delay", a, c, d)
    });
  }
  function q(a) {
    function c() {
      return window.Promise && new Promise(function (a) {
        return p = a;
      });
    }
    function d(a) {
      return g.reversed ? g.duration - a : a;
    }
    function b(a) {
      for (var b = 0, c = {}, d = g.animations, f = d.length; b < f;) {
        var e = d[b],
          k = e.animatable,
          h = e.tweens,
          n = h.length - 1,
          l = h[n];
        n && (l = r(h, function (b) {
          return a < b.end;
        })[0] || l);
        for (var h = Math.min(Math.max(a - l.start - l.delay, 0), l.duration) / l.duration, w = isNaN(h) ? 1 : l.easing(h, l.elasticity), h = l.to.strings, p = l.round, n = [], m = void 0, m = l.to.numbers.length, t = 0; t < m; t++) {
          var x = void 0,
            x = l.to.numbers[t],
            q = l.from.numbers[t],
            x = l.isPath ? Y(l.value, w * x) : q + w * (x - q);
          p && (l.isColor && 2 < t || (x = Math.round(x * p) / p));
          n.push(x);
        }
        if (l = h.length) for (m = h[0], w = 0; w < l; w++) p = h[w + 1], t = n[w], isNaN(t) || (m = p ? m + (t + p) : m + (t + " "));else m = n[0];
        ha[e.type](k.target, e.property, m, c, k.id);
        e.currentValue = m;
        b++;
      }
      if (b = Object.keys(c).length) for (d = 0; d < b; d++) H || (H = E(document.body, "transform") ? "transform" : "-webkit-transform"), g.animatables[d].target.style[H] = c[d].join(" ");
      g.currentTime = a;
      g.progress = a / g.duration * 100;
    }
    function f(a) {
      if (g[a]) g[a](g);
    }
    function e() {
      g.remaining && !0 !== g.remaining && g.remaining--;
    }
    function k(a) {
      var k = g.duration,
        n = g.offset,
        w = n + g.delay,
        r = g.currentTime,
        x = g.reversed,
        q = d(a);
      if (g.children.length) {
        var u = g.children,
          v = u.length;
        if (q >= g.currentTime) for (var G = 0; G < v; G++) u[G].seek(q);else for (; v--;) u[v].seek(q);
      }
      if (q >= w || !k) g.began || (g.began = !0, f("begin")), f("run");
      if (q > n && q < k) b(q);else if (q <= n && 0 !== r && (b(0), x && e()), q >= k && r !== k || !k) b(k), x || e();
      f("update");
      a >= k && (g.remaining ? (t = h, "alternate" === g.direction && (g.reversed = !g.reversed)) : (g.pause(), g.completed || (g.completed = !0, f("complete"), "Promise" in window && (p(), m = c()))), l = 0);
    }
    a = void 0 === a ? {} : a;
    var h,
      t,
      l = 0,
      p = null,
      m = c(),
      g = fa(a);
    g.reset = function () {
      var a = g.direction,
        c = g.loop;
      g.currentTime = 0;
      g.progress = 0;
      g.paused = !0;
      g.began = !1;
      g.completed = !1;
      g.reversed = "reverse" === a;
      g.remaining = "alternate" === a && 1 === c ? 2 : c;
      b(0);
      for (a = g.children.length; a--;) g.children[a].reset();
    };
    g.tick = function (a) {
      h = a;
      t || (t = h);
      k((l + h - t) * q.speed);
    };
    g.seek = function (a) {
      k(d(a));
    };
    g.pause = function () {
      var a = v.indexOf(g);
      -1 < a && v.splice(a, 1);
      g.paused = !0;
    };
    g.play = function () {
      g.paused && (g.paused = !1, t = 0, l = d(g.currentTime), v.push(g), B || ia());
    };
    g.reverse = function () {
      g.reversed = !g.reversed;
      t = 0;
      l = d(g.currentTime);
    };
    g.restart = function () {
      g.pause();
      g.reset();
      g.play();
    };
    g.finished = m;
    g.reset();
    g.autoplay && g.play();
    return g;
  }
  var ga = {
      update: void 0,
      begin: void 0,
      run: void 0,
      complete: void 0,
      loop: 1,
      direction: "normal",
      autoplay: !0,
      offset: 0
    },
    S = {
      duration: 1E3,
      delay: 0,
      easing: "easeOutElastic",
      elasticity: 500,
      round: 0
    },
    W = "translateX translateY translateZ rotate rotateX rotateY rotateZ scale scaleX scaleY scaleZ skewX skewY perspective".split(" "),
    H,
    h = {
      arr: function arr(a) {
        return Array.isArray(a);
      },
      obj: function obj(a) {
        return -1 < Object.prototype.toString.call(a).indexOf("Object");
      },
      pth: function pth(a) {
        return h.obj(a) && a.hasOwnProperty("totalLength");
      },
      svg: function svg(a) {
        return a instanceof SVGElement;
      },
      dom: function dom(a) {
        return a.nodeType || h.svg(a);
      },
      str: function str(a) {
        return "string" === typeof a;
      },
      fnc: function fnc(a) {
        return "function" === typeof a;
      },
      und: function und(a) {
        return "undefined" === typeof a;
      },
      hex: function hex(a) {
        return /(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i.test(a);
      },
      rgb: function rgb(a) {
        return /^rgb/.test(a);
      },
      hsl: function hsl(a) {
        return /^hsl/.test(a);
      },
      col: function col(a) {
        return h.hex(a) || h.rgb(a) || h.hsl(a);
      }
    },
    A = function () {
      function a(a, d, b) {
        return (((1 - 3 * b + 3 * d) * a + (3 * b - 6 * d)) * a + 3 * d) * a;
      }
      return function (c, d, b, f) {
        if (0 <= c && 1 >= c && 0 <= b && 1 >= b) {
          var e = new Float32Array(11);
          if (c !== d || b !== f) for (var k = 0; 11 > k; ++k) e[k] = a(.1 * k, c, b);
          return function (k) {
            if (c === d && b === f) return k;
            if (0 === k) return 0;
            if (1 === k) return 1;
            for (var h = 0, l = 1; 10 !== l && e[l] <= k; ++l) h += .1;
            --l;
            var l = h + (k - e[l]) / (e[l + 1] - e[l]) * .1,
              n = 3 * (1 - 3 * b + 3 * c) * l * l + 2 * (3 * b - 6 * c) * l + 3 * c;
            if (.001 <= n) {
              for (h = 0; 4 > h; ++h) {
                n = 3 * (1 - 3 * b + 3 * c) * l * l + 2 * (3 * b - 6 * c) * l + 3 * c;
                if (0 === n) break;
                var m = a(l, c, b) - k,
                  l = l - m / n;
              }
              k = l;
            } else if (0 === n) k = l;else {
              var l = h,
                h = h + .1,
                g = 0;
              do m = l + (h - l) / 2, n = a(m, c, b) - k, 0 < n ? h = m : l = m; while (1e-7 < Math.abs(n) && 10 > ++g);
              k = m;
            }
            return a(k, d, f);
          };
        }
      };
    }(),
    Q = function () {
      function a(a, b) {
        return 0 === a || 1 === a ? a : -Math.pow(2, 10 * (a - 1)) * Math.sin(2 * (a - 1 - b / (2 * Math.PI) * Math.asin(1)) * Math.PI / b);
      }
      var c = "Quad Cubic Quart Quint Sine Expo Circ Back Elastic".split(" "),
        d = {
          In: [[.55, .085, .68, .53], [.55, .055, .675, .19], [.895, .03, .685, .22], [.755, .05, .855, .06], [.47, 0, .745, .715], [.95, .05, .795, .035], [.6, .04, .98, .335], [.6, -.28, .735, .045], a],
          Out: [[.25, .46, .45, .94], [.215, .61, .355, 1], [.165, .84, .44, 1], [.23, 1, .32, 1], [.39, .575, .565, 1], [.19, 1, .22, 1], [.075, .82, .165, 1], [.175, .885, .32, 1.275], function (b, c) {
            return 1 - a(1 - b, c);
          }],
          InOut: [[.455, .03, .515, .955], [.645, .045, .355, 1], [.77, 0, .175, 1], [.86, 0, .07, 1], [.445, .05, .55, .95], [1, 0, 0, 1], [.785, .135, .15, .86], [.68, -.55, .265, 1.55], function (b, c) {
            return .5 > b ? a(2 * b, c) / 2 : 1 - a(-2 * b + 2, c) / 2;
          }]
        },
        b = {
          linear: A(.25, .25, .75, .75)
        },
        f = {},
        e;
      for (e in d) f.type = e, d[f.type].forEach(function (a) {
        return function (d, f) {
          b["ease" + a.type + c[f]] = h.fnc(d) ? d : A.apply($jscomp$this, d);
        };
      }(f)), f = {
        type: f.type
      };
      return b;
    }(),
    ha = {
      css: function css(a, c, d) {
        return a.style[c] = d;
      },
      attribute: function attribute(a, c, d) {
        return a.setAttribute(c, d);
      },
      object: function object(a, c, d) {
        return a[c] = d;
      },
      transform: function transform(a, c, d, b, f) {
        b[f] || (b[f] = []);
        b[f].push(c + "(" + d + ")");
      }
    },
    v = [],
    B = 0,
    ia = function () {
      function a() {
        B = requestAnimationFrame(c);
      }
      function c(c) {
        var b = v.length;
        if (b) {
          for (var d = 0; d < b;) v[d] && v[d].tick(c), d++;
          a();
        } else cancelAnimationFrame(B), B = 0;
      }
      return a;
    }();
  q.version = "2.2.0";
  q.speed = 1;
  q.running = v;
  q.remove = function (a) {
    a = P(a);
    for (var c = v.length; c--;) for (var d = v[c], b = d.animations, f = b.length; f--;) u(a, b[f].animatable.target) && (b.splice(f, 1), b.length || d.pause());
  };
  q.getValue = K;
  q.path = function (a, c) {
    var d = h.str(a) ? e(a)[0] : a,
      b = c || 100;
    return function (a) {
      return {
        el: d,
        property: a,
        totalLength: N(d) * (b / 100)
      };
    };
  };
  q.setDashoffset = function (a) {
    var c = N(a);
    a.setAttribute("stroke-dasharray", c);
    return c;
  };
  q.bezier = A;
  q.easings = Q;
  q.timeline = function (a) {
    var c = q(a);
    c.pause();
    c.duration = 0;
    c.add = function (d) {
      c.children.forEach(function (a) {
        a.began = !0;
        a.completed = !0;
      });
      m(d).forEach(function (b) {
        var d = z(b, D(S, a || {}));
        d.targets = d.targets || a.targets;
        b = c.duration;
        var e = d.offset;
        d.autoplay = !1;
        d.direction = c.direction;
        d.offset = h.und(e) ? b : L(e, b);
        c.began = !0;
        c.completed = !0;
        c.seek(d.offset);
        d = q(d);
        d.began = !0;
        d.completed = !0;
        d.duration > b && (c.duration = d.duration);
        c.children.push(d);
      });
      c.seek(0);
      c.reset();
      c.autoplay && c.restart();
      return c;
    };
    return c;
  };
  q.random = function (a, c) {
    return Math.floor(Math.random() * (c - a + 1)) + a;
  };
  return q;
});
(function ($, anim) {
  'use strict';

  var _defaults = {
    opacity: 0.5,
    inDuration: 250,
    outDuration: 250,
    onOpenStart: null,
    onOpenEnd: null,
    onCloseStart: null,
    onCloseEnd: null,
    preventScrolling: true,
    dismissible: true,
    startingTop: '4%',
    endingTop: '10%'
  };

  /**
   * @class
   *
   */
  var Modal = /*#__PURE__*/function (_Component) {
    _inherits(Modal, _Component);
    var _super = _createSuper(Modal);
    /**
     * Construct Modal instance and set up overlay
     * @constructor
     * @param {Element} el
     * @param {Object} options
     */
    function Modal(el, options) {
      var _this3;
      _classCallCheck(this, Modal);
      _this3 = _super.call(this, Modal, el, options);
      _this3.el.M_Modal = _assertThisInitialized(_this3);

      /**
       * Options for the modal
       * @member Modal#options
       * @prop {Number} [opacity=0.5] - Opacity of the modal overlay
       * @prop {Number} [inDuration=250] - Length in ms of enter transition
       * @prop {Number} [outDuration=250] - Length in ms of exit transition
       * @prop {Function} onOpenStart - Callback function called before modal is opened
       * @prop {Function} onOpenEnd - Callback function called after modal is opened
       * @prop {Function} onCloseStart - Callback function called before modal is closed
       * @prop {Function} onCloseEnd - Callback function called after modal is closed
       * @prop {Boolean} [dismissible=true] - Allow modal to be dismissed by keyboard or overlay click
       * @prop {String} [startingTop='4%'] - startingTop
       * @prop {String} [endingTop='10%'] - endingTop
       */
      _this3.options = $.extend({}, Modal.defaults, options);

      /**
       * Describes open/close state of modal
       * @type {Boolean}
       */
      _this3.isOpen = false;
      _this3.id = _this3.$el.attr('id');
      _this3._openingTrigger = undefined;
      _this3.$overlay = $('<div class="modal-overlay"></div>');
      _this3.el.tabIndex = 0;
      _this3._nthModalOpened = 0;
      Modal._count++;
      _this3._setupEventHandlers();
      return _this3;
    }
    _createClass(Modal, [{
      key: "destroy",
      value:
      /**
       * Teardown component
       */
      function destroy() {
        Modal._count--;
        this._removeEventHandlers();
        this.el.removeAttribute('style');
        this.$overlay.remove();
        this.el.M_Modal = undefined;
      }

      /**
       * Setup Event Handlers
       */
    }, {
      key: "_setupEventHandlers",
      value: function _setupEventHandlers() {
        this._handleOverlayClickBound = this._handleOverlayClick.bind(this);
        this._handleModalCloseClickBound = this._handleModalCloseClick.bind(this);
        if (Modal._count === 1) {
          document.body.addEventListener('click', this._handleTriggerClick);
        }
        this.$overlay[0].addEventListener('click', this._handleOverlayClickBound);
        this.el.addEventListener('click', this._handleModalCloseClickBound);
      }

      /**
       * Remove Event Handlers
       */
    }, {
      key: "_removeEventHandlers",
      value: function _removeEventHandlers() {
        if (Modal._count === 0) {
          document.body.removeEventListener('click', this._handleTriggerClick);
        }
        this.$overlay[0].removeEventListener('click', this._handleOverlayClickBound);
        this.el.removeEventListener('click', this._handleModalCloseClickBound);
      }

      /**
       * Handle Trigger Click
       * @param {Event} e
       */
    }, {
      key: "_handleTriggerClick",
      value: function _handleTriggerClick(e) {
        var $trigger = $(e.target).closest('.modal-trigger');
        if ($trigger.length) {
          var modalId = M.getIdFromTrigger($trigger[0]);
          var modalInstance = document.getElementById(modalId).M_Modal;
          if (modalInstance) {
            modalInstance.open($trigger);
          }
          e.preventDefault();
        }
      }

      /**
       * Handle Overlay Click
       */
    }, {
      key: "_handleOverlayClick",
      value: function _handleOverlayClick() {
        if (this.options.dismissible) {
          this.close();
        }
      }

      /**
       * Handle Modal Close Click
       * @param {Event} e
       */
    }, {
      key: "_handleModalCloseClick",
      value: function _handleModalCloseClick(e) {
        var $closeTrigger = $(e.target).closest('.modal-close');
        if ($closeTrigger.length) {
          this.close();
        }
      }

      /**
       * Handle Keydown
       * @param {Event} e
       */
    }, {
      key: "_handleKeydown",
      value: function _handleKeydown(e) {
        // ESC key
        if (e.keyCode === 27 && this.options.dismissible) {
          this.close();
        }
      }

      /**
       * Handle Focus
       * @param {Event} e
       */
    }, {
      key: "_handleFocus",
      value: function _handleFocus(e) {
        // Only trap focus if this modal is the last model opened (prevents loops in nested modals).
        if (!this.el.contains(e.target) && this._nthModalOpened === Modal._modalsOpen) {
          this.el.focus();
        }
      }

      /**
       * Animate in modal
       */
    }, {
      key: "_animateIn",
      value: function _animateIn() {
        var _this4 = this;
        // Set initial styles
        $.extend(this.el.style, {
          display: 'block',
          opacity: 0
        });
        $.extend(this.$overlay[0].style, {
          display: 'block',
          opacity: 0
        });

        // Animate overlay
        anim({
          targets: this.$overlay[0],
          opacity: this.options.opacity,
          duration: this.options.inDuration,
          easing: 'easeOutQuad'
        });

        // Define modal animation options
        var enterAnimOptions = {
          targets: this.el,
          duration: this.options.inDuration,
          easing: 'easeOutCubic',
          // Handle modal onOpenEnd callback
          complete: function complete() {
            if (typeof _this4.options.onOpenEnd === 'function') {
              _this4.options.onOpenEnd.call(_this4, _this4.el, _this4._openingTrigger);
            }
          }
        };

        // Bottom sheet animation
        if (this.el.classList.contains('bottom-sheet')) {
          $.extend(enterAnimOptions, {
            bottom: 0,
            opacity: 1
          });
          anim(enterAnimOptions);

          // Normal modal animation
        } else {
          $.extend(enterAnimOptions, {
            top: [this.options.startingTop, this.options.endingTop],
            opacity: 1,
            scaleX: [0.8, 1],
            scaleY: [0.8, 1]
          });
          anim(enterAnimOptions);
        }
      }

      /**
       * Animate out modal
       */
    }, {
      key: "_animateOut",
      value: function _animateOut() {
        var _this5 = this;
        // Animate overlay
        anim({
          targets: this.$overlay[0],
          opacity: 0,
          duration: this.options.outDuration,
          easing: 'easeOutQuart'
        });

        // Define modal animation options
        var exitAnimOptions = {
          targets: this.el,
          duration: this.options.outDuration,
          easing: 'easeOutCubic',
          // Handle modal ready callback
          complete: function complete() {
            _this5.el.style.display = 'none';
            _this5.$overlay.remove();

            // Call onCloseEnd callback
            if (typeof _this5.options.onCloseEnd === 'function') {
              _this5.options.onCloseEnd.call(_this5, _this5.el);
            }
          }
        };

        // Bottom sheet animation
        if (this.el.classList.contains('bottom-sheet')) {
          $.extend(exitAnimOptions, {
            bottom: '-100%',
            opacity: 0
          });
          anim(exitAnimOptions);

          // Normal modal animation
        } else {
          $.extend(exitAnimOptions, {
            top: [this.options.endingTop, this.options.startingTop],
            opacity: 0,
            scaleX: 0.8,
            scaleY: 0.8
          });
          anim(exitAnimOptions);
        }
      }

      /**
       * Open Modal
       * @param {cash} [$trigger]
       */
    }, {
      key: "open",
      value: function open($trigger) {
        if (this.isOpen) {
          return;
        }
        this.isOpen = true;
        Modal._modalsOpen++;
        this._nthModalOpened = Modal._modalsOpen;

        // Set Z-Index based on number of currently open modals
        this.$overlay[0].style.zIndex = 1000 + Modal._modalsOpen * 2;
        this.el.style.zIndex = 1000 + Modal._modalsOpen * 2 + 1;

        // Set opening trigger, undefined indicates modal was opened by javascript
        this._openingTrigger = !!$trigger ? $trigger[0] : undefined;

        // onOpenStart callback
        if (typeof this.options.onOpenStart === 'function') {
          this.options.onOpenStart.call(this, this.el, this._openingTrigger);
        }
        if (this.options.preventScrolling) {
          document.body.style.overflow = 'hidden';
        }
        this.el.classList.add('open');
        this.el.insertAdjacentElement('afterend', this.$overlay[0]);
        if (this.options.dismissible) {
          this._handleKeydownBound = this._handleKeydown.bind(this);
          this._handleFocusBound = this._handleFocus.bind(this);
          document.addEventListener('keydown', this._handleKeydownBound);
          document.addEventListener('focus', this._handleFocusBound, true);
        }
        anim.remove(this.el);
        anim.remove(this.$overlay[0]);
        this._animateIn();

        // Focus modal
        this.el.focus();
        return this;
      }

      /**
       * Close Modal
       */
    }, {
      key: "close",
      value: function close() {
        if (!this.isOpen) {
          return;
        }
        this.isOpen = false;
        Modal._modalsOpen--;
        this._nthModalOpened = 0;

        // Call onCloseStart callback
        if (typeof this.options.onCloseStart === 'function') {
          this.options.onCloseStart.call(this, this.el);
        }
        this.el.classList.remove('open');

        // Enable body scrolling only if there are no more modals open.
        if (Modal._modalsOpen === 0) {
          document.body.style.overflow = '';
        }
        if (this.options.dismissible) {
          document.removeEventListener('keydown', this._handleKeydownBound);
          document.removeEventListener('focus', this._handleFocusBound, true);
        }
        anim.remove(this.el);
        anim.remove(this.$overlay[0]);
        this._animateOut();
        return this;
      }
    }], [{
      key: "defaults",
      get: function get() {
        return _defaults;
      }
    }, {
      key: "init",
      value: function init(els, options) {
        return _get(_getPrototypeOf(Modal), "init", this).call(this, this, els, options);
      }

      /**
       * Get Instance
       */
    }, {
      key: "getInstance",
      value: function getInstance(el) {
        var domElem = !!el.jquery ? el[0] : el;
        return domElem.M_Modal;
      }
    }]);
    return Modal;
  }(Component);
  /**
   * @static
   * @memberof Modal
   */
  Modal._modalsOpen = 0;

  /**
   * @static
   * @memberof Modal
   */
  Modal._count = 0;
  M.Modal = Modal;
  if (M.jQueryLoaded) {
    M.initializeJqueryWrapper(Modal, 'modal', 'M_Modal');
  }
})(cash, M.anime);
(function ($, anim) {
  'use strict';

  var _defaults = {
    edge: 'left',
    draggable: true,
    inDuration: 250,
    outDuration: 200,
    onOpenStart: null,
    onOpenEnd: null,
    onCloseStart: null,
    onCloseEnd: null,
    preventScrolling: true
  };

  /**
   * @class
   */
  var Sidenav = /*#__PURE__*/function (_Component2) {
    _inherits(Sidenav, _Component2);
    var _super2 = _createSuper(Sidenav);
    /**
     * Construct Sidenav instance and set up overlay
     * @constructor
     * @param {Element} el
     * @param {Object} options
     */
    function Sidenav(el, options) {
      var _this6;
      _classCallCheck(this, Sidenav);
      _this6 = _super2.call(this, Sidenav, el, options);
      _this6.el.M_Sidenav = _assertThisInitialized(_this6);
      _this6.id = _this6.$el.attr('id');

      /**
       * Options for the Sidenav
       * @member Sidenav#options
       * @prop {String} [edge='left'] - Side of screen on which Sidenav appears
       * @prop {Boolean} [draggable=true] - Allow swipe gestures to open/close Sidenav
       * @prop {Number} [inDuration=250] - Length in ms of enter transition
       * @prop {Number} [outDuration=200] - Length in ms of exit transition
       * @prop {Function} onOpenStart - Function called when sidenav starts entering
       * @prop {Function} onOpenEnd - Function called when sidenav finishes entering
       * @prop {Function} onCloseStart - Function called when sidenav starts exiting
       * @prop {Function} onCloseEnd - Function called when sidenav finishes exiting
       */
      _this6.options = $.extend({}, Sidenav.defaults, options);

      /**
       * Describes open/close state of Sidenav
       * @type {Boolean}
       */
      _this6.isOpen = false;

      /**
       * Describes if Sidenav is fixed
       * @type {Boolean}
       */
      _this6.isFixed = _this6.el.classList.contains('sidenav-fixed');

      /**
       * Describes if Sidenav is being draggeed
       * @type {Boolean}
       */
      _this6.isDragged = false;

      // Window size variables for window resize checks
      _this6.lastWindowWidth = window.innerWidth;
      _this6.lastWindowHeight = window.innerHeight;
      _this6._createOverlay();
      _this6._createDragTarget();
      _this6._setupEventHandlers();
      _this6._setupClasses();
      _this6._setupFixed();
      Sidenav._sidenavs.push(_assertThisInitialized(_this6));
      return _this6;
    }
    _createClass(Sidenav, [{
      key: "destroy",
      value:
      /**
       * Teardown component
       */
      function destroy() {
        this._removeEventHandlers();
        this._enableBodyScrolling();
        this._overlay.parentNode.removeChild(this._overlay);
        this.dragTarget.parentNode.removeChild(this.dragTarget);
        this.el.M_Sidenav = undefined;
        this.el.style.transform = '';
        var index = Sidenav._sidenavs.indexOf(this);
        if (index >= 0) {
          Sidenav._sidenavs.splice(index, 1);
        }
      }
    }, {
      key: "_createOverlay",
      value: function _createOverlay() {
        var overlay = document.createElement('div');
        this._closeBound = this.close.bind(this);
        overlay.classList.add('sidenav-overlay');
        overlay.addEventListener('click', this._closeBound);
        document.body.appendChild(overlay);
        this._overlay = overlay;
      }
    }, {
      key: "_setupEventHandlers",
      value: function _setupEventHandlers() {
        if (Sidenav._sidenavs.length === 0) {
          document.body.addEventListener('click', this._handleTriggerClick);
        }
        this._handleDragTargetDragBound = this._handleDragTargetDrag.bind(this);
        this._handleDragTargetReleaseBound = this._handleDragTargetRelease.bind(this);
        this._handleCloseDragBound = this._handleCloseDrag.bind(this);
        this._handleCloseReleaseBound = this._handleCloseRelease.bind(this);
        this._handleCloseTriggerClickBound = this._handleCloseTriggerClick.bind(this);
        this.dragTarget.addEventListener('touchmove', this._handleDragTargetDragBound);
        this.dragTarget.addEventListener('touchend', this._handleDragTargetReleaseBound);
        this._overlay.addEventListener('touchmove', this._handleCloseDragBound);
        this._overlay.addEventListener('touchend', this._handleCloseReleaseBound);
        this.el.addEventListener('touchmove', this._handleCloseDragBound);
        this.el.addEventListener('touchend', this._handleCloseReleaseBound);
        this.el.addEventListener('click', this._handleCloseTriggerClickBound);

        // Add resize for side nav fixed
        if (this.isFixed) {
          this._handleWindowResizeBound = this._handleWindowResize.bind(this);
          window.addEventListener('resize', this._handleWindowResizeBound);
        }
      }
    }, {
      key: "_removeEventHandlers",
      value: function _removeEventHandlers() {
        if (Sidenav._sidenavs.length === 1) {
          document.body.removeEventListener('click', this._handleTriggerClick);
        }
        this.dragTarget.removeEventListener('touchmove', this._handleDragTargetDragBound);
        this.dragTarget.removeEventListener('touchend', this._handleDragTargetReleaseBound);
        this._overlay.removeEventListener('touchmove', this._handleCloseDragBound);
        this._overlay.removeEventListener('touchend', this._handleCloseReleaseBound);
        this.el.removeEventListener('touchmove', this._handleCloseDragBound);
        this.el.removeEventListener('touchend', this._handleCloseReleaseBound);
        this.el.removeEventListener('click', this._handleCloseTriggerClickBound);

        // Remove resize for side nav fixed
        if (this.isFixed) {
          window.removeEventListener('resize', this._handleWindowResizeBound);
        }
      }

      /**
       * Handle Trigger Click
       * @param {Event} e
       */
    }, {
      key: "_handleTriggerClick",
      value: function _handleTriggerClick(e) {
        var $trigger = $(e.target).closest('.sidenav-trigger');
        if (e.target && $trigger.length) {
          var sidenavId = M.getIdFromTrigger($trigger[0]);
          var sidenavInstance = document.getElementById(sidenavId).M_Sidenav;
          if (sidenavInstance) {
            sidenavInstance.open($trigger);
          }
          e.preventDefault();
        }
      }

      /**
       * Set variables needed at the beggining of drag
       * and stop any current transition.
       * @param {Event} e
       */
    }, {
      key: "_startDrag",
      value: function _startDrag(e) {
        var clientX = e.targetTouches[0].clientX;
        this.isDragged = true;
        this._startingXpos = clientX;
        this._xPos = this._startingXpos;
        this._time = Date.now();
        this._width = this.el.getBoundingClientRect().width;
        this._overlay.style.display = 'block';
        this._initialScrollTop = this.isOpen ? this.el.scrollTop : M.getDocumentScrollTop();
        this._verticallyScrolling = false;
        anim.remove(this.el);
        anim.remove(this._overlay);
      }

      /**
       * Set variables needed at each drag move update tick
       * @param {Event} e
       */
    }, {
      key: "_dragMoveUpdate",
      value: function _dragMoveUpdate(e) {
        var clientX = e.targetTouches[0].clientX;
        var currentScrollTop = this.isOpen ? this.el.scrollTop : M.getDocumentScrollTop();
        this.deltaX = Math.abs(this._xPos - clientX);
        this._xPos = clientX;
        this.velocityX = this.deltaX / (Date.now() - this._time);
        this._time = Date.now();
        if (this._initialScrollTop !== currentScrollTop) {
          this._verticallyScrolling = true;
        }
      }

      /**
       * Handles Dragging of Sidenav
       * @param {Event} e
       */
    }, {
      key: "_handleDragTargetDrag",
      value: function _handleDragTargetDrag(e) {
        // Check if draggable
        if (!this.options.draggable || this._isCurrentlyFixed() || this._verticallyScrolling) {
          return;
        }

        // If not being dragged, set initial drag start variables
        if (!this.isDragged) {
          this._startDrag(e);
        }

        // Run touchmove updates
        this._dragMoveUpdate(e);

        // Calculate raw deltaX
        var totalDeltaX = this._xPos - this._startingXpos;

        // dragDirection is the attempted user drag direction
        var dragDirection = totalDeltaX > 0 ? 'right' : 'left';

        // Don't allow totalDeltaX to exceed Sidenav width or be dragged in the opposite direction
        totalDeltaX = Math.min(this._width, Math.abs(totalDeltaX));
        if (this.options.edge === dragDirection) {
          totalDeltaX = 0;
        }

        /**
         * transformX is the drag displacement
         * transformPrefix is the initial transform placement
         * Invert values if Sidenav is right edge
         */
        var transformX = totalDeltaX;
        var transformPrefix = 'translateX(-100%)';
        if (this.options.edge === 'right') {
          transformPrefix = 'translateX(100%)';
          transformX = -transformX;
        }

        // Calculate open/close percentage of sidenav, with open = 1 and close = 0
        this.percentOpen = Math.min(1, totalDeltaX / this._width);

        // Set transform and opacity styles
        this.el.style.transform = "".concat(transformPrefix, " translateX(").concat(transformX, "px)");
        this._overlay.style.opacity = this.percentOpen;
      }

      /**
       * Handle Drag Target Release
       */
    }, {
      key: "_handleDragTargetRelease",
      value: function _handleDragTargetRelease() {
        if (this.isDragged) {
          if (this.percentOpen > 0.2) {
            this.open();
          } else {
            this._animateOut();
          }
          this.isDragged = false;
          this._verticallyScrolling = false;
        }
      }

      /**
       * Handle Close Drag
       * @param {Event} e
       */
    }, {
      key: "_handleCloseDrag",
      value: function _handleCloseDrag(e) {
        if (this.isOpen) {
          // Check if draggable
          if (!this.options.draggable || this._isCurrentlyFixed() || this._verticallyScrolling) {
            return;
          }

          // If not being dragged, set initial drag start variables
          if (!this.isDragged) {
            this._startDrag(e);
          }

          // Run touchmove updates
          this._dragMoveUpdate(e);

          // Calculate raw deltaX
          var totalDeltaX = this._xPos - this._startingXpos;

          // dragDirection is the attempted user drag direction
          var dragDirection = totalDeltaX > 0 ? 'right' : 'left';

          // Don't allow totalDeltaX to exceed Sidenav width or be dragged in the opposite direction
          totalDeltaX = Math.min(this._width, Math.abs(totalDeltaX));
          if (this.options.edge !== dragDirection) {
            totalDeltaX = 0;
          }
          var transformX = -totalDeltaX;
          if (this.options.edge === 'right') {
            transformX = -transformX;
          }

          // Calculate open/close percentage of sidenav, with open = 1 and close = 0
          this.percentOpen = Math.min(1, 1 - totalDeltaX / this._width);

          // Set transform and opacity styles
          this.el.style.transform = "translateX(".concat(transformX, "px)");
          this._overlay.style.opacity = this.percentOpen;
        }
      }

      /**
       * Handle Close Release
       */
    }, {
      key: "_handleCloseRelease",
      value: function _handleCloseRelease() {
        if (this.isOpen && this.isDragged) {
          if (this.percentOpen > 0.8) {
            this._animateIn();
          } else {
            this.close();
          }
          this.isDragged = false;
          this._verticallyScrolling = false;
        }
      }

      /**
       * Handles closing of Sidenav when element with class .sidenav-close
       */
    }, {
      key: "_handleCloseTriggerClick",
      value: function _handleCloseTriggerClick(e) {
        var $closeTrigger = $(e.target).closest('.sidenav-close');
        if ($closeTrigger.length && !this._isCurrentlyFixed()) {
          this.close();
        }
      }

      /**
       * Handle Window Resize
       */
    }, {
      key: "_handleWindowResize",
      value: function _handleWindowResize() {
        // Only handle horizontal resizes
        if (this.lastWindowWidth !== window.innerWidth) {
          if (window.innerWidth > 992) {
            this.open();
          } else {
            this.close();
          }
        }
        this.lastWindowWidth = window.innerWidth;
        this.lastWindowHeight = window.innerHeight;
      }
    }, {
      key: "_setupClasses",
      value: function _setupClasses() {
        if (this.options.edge === 'right') {
          this.el.classList.add('right-aligned');
          this.dragTarget.classList.add('right-aligned');
        }
      }
    }, {
      key: "_removeClasses",
      value: function _removeClasses() {
        this.el.classList.remove('right-aligned');
        this.dragTarget.classList.remove('right-aligned');
      }
    }, {
      key: "_setupFixed",
      value: function _setupFixed() {
        if (this._isCurrentlyFixed()) {
          this.open();
        }
      }
    }, {
      key: "_isCurrentlyFixed",
      value: function _isCurrentlyFixed() {
        return this.isFixed && window.innerWidth > 992;
      }
    }, {
      key: "_createDragTarget",
      value: function _createDragTarget() {
        var dragTarget = document.createElement('div');
        dragTarget.classList.add('drag-target');
        document.body.appendChild(dragTarget);
        this.dragTarget = dragTarget;
      }
    }, {
      key: "_preventBodyScrolling",
      value: function _preventBodyScrolling() {
        var body = document.body;
        body.style.overflow = 'hidden';
      }
    }, {
      key: "_enableBodyScrolling",
      value: function _enableBodyScrolling() {
        var body = document.body;
        body.style.overflow = '';
      }
    }, {
      key: "open",
      value: function open() {
        if (this.isOpen === true) {
          return;
        }
        this.isOpen = true;

        // Run onOpenStart callback
        if (typeof this.options.onOpenStart === 'function') {
          this.options.onOpenStart.call(this, this.el);
        }

        // Handle fixed Sidenav
        if (this._isCurrentlyFixed()) {
          anim.remove(this.el);
          anim({
            targets: this.el,
            translateX: 0,
            duration: 0,
            easing: 'easeOutQuad'
          });
          this._enableBodyScrolling();
          this._overlay.style.display = 'none';

          // Handle non-fixed Sidenav
        } else {
          if (this.options.preventScrolling) {
            this._preventBodyScrolling();
          }
          if (!this.isDragged || this.percentOpen != 1) {
            this._animateIn();
          }
        }
      }
    }, {
      key: "close",
      value: function close() {
        if (this.isOpen === false) {
          return;
        }
        this.isOpen = false;

        // Run onCloseStart callback
        if (typeof this.options.onCloseStart === 'function') {
          this.options.onCloseStart.call(this, this.el);
        }

        // Handle fixed Sidenav
        if (this._isCurrentlyFixed()) {
          var transformX = this.options.edge === 'left' ? '-105%' : '105%';
          this.el.style.transform = "translateX(".concat(transformX, ")");

          // Handle non-fixed Sidenav
        } else {
          this._enableBodyScrolling();
          if (!this.isDragged || this.percentOpen != 0) {
            this._animateOut();
          } else {
            this._overlay.style.display = 'none';
          }
        }
      }
    }, {
      key: "_animateIn",
      value: function _animateIn() {
        this._animateSidenavIn();
        this._animateOverlayIn();
      }
    }, {
      key: "_animateSidenavIn",
      value: function _animateSidenavIn() {
        var _this7 = this;
        var slideOutPercent = this.options.edge === 'left' ? -1 : 1;
        if (this.isDragged) {
          slideOutPercent = this.options.edge === 'left' ? slideOutPercent + this.percentOpen : slideOutPercent - this.percentOpen;
        }
        anim.remove(this.el);
        anim({
          targets: this.el,
          translateX: ["".concat(slideOutPercent * 100, "%"), 0],
          duration: this.options.inDuration,
          easing: 'easeOutQuad',
          complete: function complete() {
            // Run onOpenEnd callback
            if (typeof _this7.options.onOpenEnd === 'function') {
              _this7.options.onOpenEnd.call(_this7, _this7.el);
            }
          }
        });
      }
    }, {
      key: "_animateOverlayIn",
      value: function _animateOverlayIn() {
        var start = 0;
        if (this.isDragged) {
          start = this.percentOpen;
        } else {
          $(this._overlay).css({
            display: 'block'
          });
        }
        anim.remove(this._overlay);
        anim({
          targets: this._overlay,
          opacity: [start, 1],
          duration: this.options.inDuration,
          easing: 'easeOutQuad'
        });
      }
    }, {
      key: "_animateOut",
      value: function _animateOut() {
        this._animateSidenavOut();
        this._animateOverlayOut();
      }
    }, {
      key: "_animateSidenavOut",
      value: function _animateSidenavOut() {
        var _this8 = this;
        var endPercent = this.options.edge === 'left' ? -1 : 1;
        var slideOutPercent = 0;
        if (this.isDragged) {
          slideOutPercent = this.options.edge === 'left' ? endPercent + this.percentOpen : endPercent - this.percentOpen;
        }
        anim.remove(this.el);
        anim({
          targets: this.el,
          translateX: ["".concat(slideOutPercent * 100, "%"), "".concat(endPercent * 105, "%")],
          duration: this.options.outDuration,
          easing: 'easeOutQuad',
          complete: function complete() {
            // Run onOpenEnd callback
            if (typeof _this8.options.onCloseEnd === 'function') {
              _this8.options.onCloseEnd.call(_this8, _this8.el);
            }
          }
        });
      }
    }, {
      key: "_animateOverlayOut",
      value: function _animateOverlayOut() {
        var _this9 = this;
        anim.remove(this._overlay);
        anim({
          targets: this._overlay,
          opacity: 0,
          duration: this.options.outDuration,
          easing: 'easeOutQuad',
          complete: function complete() {
            $(_this9._overlay).css('display', 'none');
          }
        });
      }
    }], [{
      key: "defaults",
      get: function get() {
        return _defaults;
      }
    }, {
      key: "init",
      value: function init(els, options) {
        return _get(_getPrototypeOf(Sidenav), "init", this).call(this, this, els, options);
      }

      /**
       * Get Instance
       */
    }, {
      key: "getInstance",
      value: function getInstance(el) {
        var domElem = !!el.jquery ? el[0] : el;
        return domElem.M_Sidenav;
      }
    }]);
    return Sidenav;
  }(Component);
  /**
   * @static
   * @memberof Sidenav
   * @type {Array.<Sidenav>}
   */
  Sidenav._sidenavs = [];
  M.Sidenav = Sidenav;
  if (M.jQueryLoaded) {
    M.initializeJqueryWrapper(Sidenav, 'sidenav', 'M_Sidenav');
  }
})(cash, M.anime);
(function ($, anim) {
  'use strict';

  var _defaults = {
    throttle: 100,
    scrollOffset: 200,
    // offset - 200 allows elements near bottom of page to scroll
    activeClass: 'active',
    getActiveElement: function getActiveElement(id) {
      return 'a[href="#' + id + '"]';
    }
  };

  /**
   * @class
   *
   */
  var ScrollSpy = /*#__PURE__*/function (_Component3) {
    _inherits(ScrollSpy, _Component3);
    var _super3 = _createSuper(ScrollSpy);
    /**
     * Construct ScrollSpy instance
     * @constructor
     * @param {Element} el
     * @param {Object} options
     */
    function ScrollSpy(el, options) {
      var _this10;
      _classCallCheck(this, ScrollSpy);
      _this10 = _super3.call(this, ScrollSpy, el, options);
      _this10.el.M_ScrollSpy = _assertThisInitialized(_this10);

      /**
       * Options for the modal
       * @member Modal#options
       * @prop {Number} [throttle=100] - Throttle of scroll handler
       * @prop {Number} [scrollOffset=200] - Offset for centering element when scrolled to
       * @prop {String} [activeClass='active'] - Class applied to active elements
       * @prop {Function} [getActiveElement] - Used to find active element
       */
      _this10.options = $.extend({}, ScrollSpy.defaults, options);

      // setup
      ScrollSpy._elements.push(_assertThisInitialized(_this10));
      ScrollSpy._count++;
      ScrollSpy._increment++;
      _this10.tickId = -1;
      _this10.id = ScrollSpy._increment;
      _this10._setupEventHandlers();
      _this10._handleWindowScroll();
      return _this10;
    }
    _createClass(ScrollSpy, [{
      key: "destroy",
      value:
      /**
       * Teardown component
       */
      function destroy() {
        ScrollSpy._elements.splice(ScrollSpy._elements.indexOf(this), 1);
        ScrollSpy._elementsInView.splice(ScrollSpy._elementsInView.indexOf(this), 1);
        ScrollSpy._visibleElements.splice(ScrollSpy._visibleElements.indexOf(this.$el), 1);
        ScrollSpy._count--;
        this._removeEventHandlers();
        $(this.options.getActiveElement(this.$el.attr('id'))).removeClass(this.options.activeClass);
        this.el.M_ScrollSpy = undefined;
      }

      /**
       * Setup Event Handlers
       */
    }, {
      key: "_setupEventHandlers",
      value: function _setupEventHandlers() {
        var throttledResize = M.throttle(this._handleWindowScroll, 200);
        this._handleThrottledResizeBound = throttledResize.bind(this);
        this._handleWindowScrollBound = this._handleWindowScroll.bind(this);
        if (ScrollSpy._count === 1) {
          window.addEventListener('scroll', this._handleWindowScrollBound);
          window.addEventListener('resize', this._handleThrottledResizeBound);
          document.body.addEventListener('click', this._handleTriggerClick);
        }
      }

      /**
       * Remove Event Handlers
       */
    }, {
      key: "_removeEventHandlers",
      value: function _removeEventHandlers() {
        if (ScrollSpy._count === 0) {
          window.removeEventListener('scroll', this._handleWindowScrollBound);
          window.removeEventListener('resize', this._handleThrottledResizeBound);
          document.body.removeEventListener('click', this._handleTriggerClick);
        }
      }

      /**
       * Handle Trigger Click
       * @param {Event} e
       */
    }, {
      key: "_handleTriggerClick",
      value: function _handleTriggerClick(e) {
        var $trigger = $(e.target);
        for (var i = ScrollSpy._elements.length - 1; i >= 0; i--) {
          var scrollspy = ScrollSpy._elements[i];
          if ($trigger.is('a[href="#' + scrollspy.$el.attr('id') + '"]')) {
            e.preventDefault();
            // let offset = scrollspy.$el.offset().top + 1;

            // anim({
            //   targets: [document.documentElement, document.body],
            //   scrollTop: offset - scrollspy.options.scrollOffset,
            //   duration: 400,
            //   easing: 'easeOutCubic'
            // });
            break;
          }
        }
      }

      /**
       * Handle Window Scroll
       */
    }, {
      key: "_handleWindowScroll",
      value: function _handleWindowScroll() {
        // unique tick id
        ScrollSpy._ticks++;

        // viewport rectangle
        var top = M.getDocumentScrollTop(),
          left = M.getDocumentScrollLeft(),
          right = left + window.innerWidth,
          bottom = top + window.innerHeight;

        // determine which elements are in view
        var intersections = ScrollSpy._findElements(top, right, bottom, left);
        for (var i = 0; i < intersections.length; i++) {
          var scrollspy = intersections[i];
          var lastTick = scrollspy.tickId;
          if (lastTick < 0) {
            // entered into view
            scrollspy._enter();
          }

          // update tick id
          scrollspy.tickId = ScrollSpy._ticks;
        }
        for (var _i = 0; _i < ScrollSpy._elementsInView.length; _i++) {
          var _scrollspy = ScrollSpy._elementsInView[_i];
          var _lastTick = _scrollspy.tickId;
          if (_lastTick >= 0 && _lastTick !== ScrollSpy._ticks) {
            // exited from view
            _scrollspy._exit();
            _scrollspy.tickId = -1;
          }
        }

        // remember elements in view for next tick
        ScrollSpy._elementsInView = intersections;
      }

      /**
       * Find elements that are within the boundary
       * @param {number} top
       * @param {number} right
       * @param {number} bottom
       * @param {number} left
       * @return {Array.<ScrollSpy>}   A collection of elements
       */
    }, {
      key: "_enter",
      value: function _enter() {
        ScrollSpy._visibleElements = ScrollSpy._visibleElements.filter(function (value) {
          return value.height() != 0;
        });
        if (ScrollSpy._visibleElements[0]) {
          $(this.options.getActiveElement(ScrollSpy._visibleElements[0].attr('id'))).removeClass(this.options.activeClass);
          if (ScrollSpy._visibleElements[0][0].M_ScrollSpy && this.id < ScrollSpy._visibleElements[0][0].M_ScrollSpy.id) {
            ScrollSpy._visibleElements.unshift(this.$el);
          } else {
            ScrollSpy._visibleElements.push(this.$el);
          }
        } else {
          ScrollSpy._visibleElements.push(this.$el);
        }
        $(this.options.getActiveElement(ScrollSpy._visibleElements[0].attr('id'))).addClass(this.options.activeClass);
      }
    }, {
      key: "_exit",
      value: function _exit() {
        var _this11 = this;
        ScrollSpy._visibleElements = ScrollSpy._visibleElements.filter(function (value) {
          return value.height() != 0;
        });
        if (ScrollSpy._visibleElements[0]) {
          $(this.options.getActiveElement(ScrollSpy._visibleElements[0].attr('id'))).removeClass(this.options.activeClass);
          ScrollSpy._visibleElements = ScrollSpy._visibleElements.filter(function (el) {
            return el.attr('id') != _this11.$el.attr('id');
          });
          if (ScrollSpy._visibleElements[0]) {
            // Check if empty
            $(this.options.getActiveElement(ScrollSpy._visibleElements[0].attr('id'))).addClass(this.options.activeClass);
          }
        }
      }
    }], [{
      key: "defaults",
      get: function get() {
        return _defaults;
      }
    }, {
      key: "init",
      value: function init(els, options) {
        return _get(_getPrototypeOf(ScrollSpy), "init", this).call(this, this, els, options);
      }

      /**
       * Get Instance
       */
    }, {
      key: "getInstance",
      value: function getInstance(el) {
        var domElem = !!el.jquery ? el[0] : el;
        return domElem.M_ScrollSpy;
      }
    }, {
      key: "_findElements",
      value: function _findElements(top, right, bottom, left) {
        var hits = [];
        for (var i = 0; i < ScrollSpy._elements.length; i++) {
          var scrollspy = ScrollSpy._elements[i];
          var currTop = top + scrollspy.options.scrollOffset || 200;
          if (scrollspy.$el.height() > 0) {
            var elTop = scrollspy.$el.offset().top,
              elLeft = scrollspy.$el.offset().left,
              elRight = elLeft + scrollspy.$el.width(),
              elBottom = elTop + scrollspy.$el.height();
            var isIntersect = !(elLeft > right || elRight < left || elTop > bottom || elBottom < currTop);
            if (isIntersect) {
              hits.push(scrollspy);
            }
          }
        }
        return hits;
      }
    }]);
    return ScrollSpy;
  }(Component);
  /**
   * @static
   * @memberof ScrollSpy
   * @type {Array.<ScrollSpy>}
   */
  ScrollSpy._elements = [];

  /**
   * @static
   * @memberof ScrollSpy
   * @type {Array.<ScrollSpy>}
   */
  ScrollSpy._elementsInView = [];

  /**
   * @static
   * @memberof ScrollSpy
   * @type {Array.<cash>}
   */
  ScrollSpy._visibleElements = [];

  /**
   * @static
   * @memberof ScrollSpy
   */
  ScrollSpy._count = 0;

  /**
   * @static
   * @memberof ScrollSpy
   */
  ScrollSpy._increment = 0;

  /**
   * @static
   * @memberof ScrollSpy
   */
  ScrollSpy._ticks = 0;
  M.ScrollSpy = ScrollSpy;
  if (M.jQueryLoaded) {
    M.initializeJqueryWrapper(ScrollSpy, 'scrollSpy', 'M_ScrollSpy');
  }
})(cash, M.anime);
(function ($) {
  'use strict';

  var _defaults = {
    top: 0,
    bottom: Infinity,
    offset: 0,
    onPositionChange: null
  };

  /**
   * @class
   *
   */
  var Pushpin = /*#__PURE__*/function (_Component4) {
    _inherits(Pushpin, _Component4);
    var _super4 = _createSuper(Pushpin);
    /**
     * Construct Pushpin instance
     * @constructor
     * @param {Element} el
     * @param {Object} options
     */
    function Pushpin(el, options) {
      var _this12;
      _classCallCheck(this, Pushpin);
      _this12 = _super4.call(this, Pushpin, el, options);
      _this12.el.M_Pushpin = _assertThisInitialized(_this12);

      /**
       * Options for the modal
       * @member Pushpin#options
       */
      _this12.options = $.extend({}, Pushpin.defaults, options);
      _this12.originalOffset = _this12.el.offsetTop;
      Pushpin._pushpins.push(_assertThisInitialized(_this12));
      _this12._setupEventHandlers();
      _this12._updatePosition();
      return _this12;
    }
    _createClass(Pushpin, [{
      key: "destroy",
      value:
      /**
       * Teardown component
       */
      function destroy() {
        this.el.style.top = null;
        this._removePinClasses();
        this._removeEventHandlers();

        // Remove pushpin Inst
        var index = Pushpin._pushpins.indexOf(this);
        Pushpin._pushpins.splice(index, 1);
      }
    }, {
      key: "_setupEventHandlers",
      value: function _setupEventHandlers() {
        document.addEventListener('scroll', Pushpin._updateElements);
      }
    }, {
      key: "_removeEventHandlers",
      value: function _removeEventHandlers() {
        document.removeEventListener('scroll', Pushpin._updateElements);
      }
    }, {
      key: "_updatePosition",
      value: function _updatePosition() {
        var scrolled = M.getDocumentScrollTop() + this.options.offset;
        if (this.options.top <= scrolled && this.options.bottom >= scrolled && !this.el.classList.contains('pinned')) {
          this._removePinClasses();
          this.el.style.top = "".concat(this.options.offset, "px");
          this.el.classList.add('pinned');

          // onPositionChange callback
          if (typeof this.options.onPositionChange === 'function') {
            this.options.onPositionChange.call(this, 'pinned');
          }
        }

        // Add pin-top (when scrolled position is above top)
        if (scrolled < this.options.top && !this.el.classList.contains('pin-top')) {
          this._removePinClasses();
          this.el.style.top = 0;
          this.el.classList.add('pin-top');

          // onPositionChange callback
          if (typeof this.options.onPositionChange === 'function') {
            this.options.onPositionChange.call(this, 'pin-top');
          }
        }

        // Add pin-bottom (when scrolled position is below bottom)
        if (scrolled > this.options.bottom && !this.el.classList.contains('pin-bottom')) {
          this._removePinClasses();
          this.el.classList.add('pin-bottom');
          this.el.style.top = "".concat(this.options.bottom - this.originalOffset, "px");

          // onPositionChange callback
          if (typeof this.options.onPositionChange === 'function') {
            this.options.onPositionChange.call(this, 'pin-bottom');
          }
        }
      }
    }, {
      key: "_removePinClasses",
      value: function _removePinClasses() {
        // IE 11 bug (can't remove multiple classes in one line)
        this.el.classList.remove('pin-top');
        this.el.classList.remove('pinned');
        this.el.classList.remove('pin-bottom');
      }
    }], [{
      key: "defaults",
      get: function get() {
        return _defaults;
      }
    }, {
      key: "init",
      value: function init(els, options) {
        return _get(_getPrototypeOf(Pushpin), "init", this).call(this, this, els, options);
      }

      /**
       * Get Instance
       */
    }, {
      key: "getInstance",
      value: function getInstance(el) {
        var domElem = !!el.jquery ? el[0] : el;
        return domElem.M_Pushpin;
      }
    }, {
      key: "_updateElements",
      value: function _updateElements() {
        for (var elIndex in Pushpin._pushpins) {
          var pInstance = Pushpin._pushpins[elIndex];
          pInstance._updatePosition();
        }
      }
    }]);
    return Pushpin;
  }(Component);
  /**
   * @static
   * @memberof Pushpin
   */
  Pushpin._pushpins = [];
  M.Pushpin = Pushpin;
  if (M.jQueryLoaded) {
    M.initializeJqueryWrapper(Pushpin, 'pushpin', 'M_Pushpin');
  }
})(cash);
!function (t, e) {
  "object" == (typeof exports === "undefined" ? "undefined" : _typeof(exports)) && "undefined" != typeof module ? e(exports) : "function" == typeof define && define.amd ? define(["exports"], e) : e((t = "undefined" != typeof globalThis ? globalThis : t || self).windowq = t.window || {});
}(void 0, function (t) {
  "use strict";

  var e = function e(t) {
      var e = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 1e4;
      return t = parseFloat(t + "") || 0, Math.round((t + Number.EPSILON) * e) / e;
    },
    i = function i(t) {
      if (!(t && t instanceof Element && t.offsetParent)) return !1;
      var e = t.scrollHeight > t.clientHeight,
        i = window.getComputedStyle(t).overflowY,
        n = -1 !== i.indexOf("hidden"),
        s = -1 !== i.indexOf("visible");
      return e && !n && !s;
    },
    n = function n(t) {
      var e = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : void 0;
      return !(!t || t === document.body || e && t === e) && (i(t) ? t : n(t.parentElement, e));
    },
    s = function s(t) {
      var e = new DOMParser().parseFromString(t, "text/html").body;
      if (e.childElementCount > 1) {
        for (var i = document.createElement("div"); e.firstChild;) i.appendChild(e.firstChild);
        return i;
      }
      return e.firstChild;
    },
    o = function o(t) {
      return "".concat(t || "").split(" ").filter(function (t) {
        return !!t;
      });
    },
    a = function a(t, e, i) {
      t && o(e).forEach(function (e) {
        t.classList.toggle(e, i || !1);
      });
    };
  var r = /*#__PURE__*/_createClass(function r(t) {
    _classCallCheck(this, r);
    Object.defineProperty(this, "pageX", {
      enumerable: !0,
      configurable: !0,
      writable: !0,
      value: void 0
    }), Object.defineProperty(this, "pageY", {
      enumerable: !0,
      configurable: !0,
      writable: !0,
      value: void 0
    }), Object.defineProperty(this, "clientX", {
      enumerable: !0,
      configurable: !0,
      writable: !0,
      value: void 0
    }), Object.defineProperty(this, "clientY", {
      enumerable: !0,
      configurable: !0,
      writable: !0,
      value: void 0
    }), Object.defineProperty(this, "id", {
      enumerable: !0,
      configurable: !0,
      writable: !0,
      value: void 0
    }), Object.defineProperty(this, "time", {
      enumerable: !0,
      configurable: !0,
      writable: !0,
      value: void 0
    }), Object.defineProperty(this, "nativePointer", {
      enumerable: !0,
      configurable: !0,
      writable: !0,
      value: void 0
    }), this.nativePointer = t, this.pageX = t.pageX, this.pageY = t.pageY, this.clientX = t.clientX, this.clientY = t.clientY, this.id = self.Touch && t instanceof Touch ? t.identifier : -1, this.time = Date.now();
  });
  var l = {
    passive: !1
  };
  var c = /*#__PURE__*/function () {
    function c(t, _ref) {
      var _ref$start = _ref.start,
        e = _ref$start === void 0 ? function () {
          return !0;
        } : _ref$start,
        _ref$move = _ref.move,
        i = _ref$move === void 0 ? function () {} : _ref$move,
        _ref$end = _ref.end,
        n = _ref$end === void 0 ? function () {} : _ref$end;
      _classCallCheck(this, c);
      Object.defineProperty(this, "element", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: void 0
      }), Object.defineProperty(this, "startCallback", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: void 0
      }), Object.defineProperty(this, "moveCallback", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: void 0
      }), Object.defineProperty(this, "endCallback", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: void 0
      }), Object.defineProperty(this, "currentPointers", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: []
      }), Object.defineProperty(this, "startPointers", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: []
      }), this.element = t, this.startCallback = e, this.moveCallback = i, this.endCallback = n;
      for (var _i2 = 0, _arr = ["onPointerStart", "onTouchStart", "onMove", "onTouchEnd", "onPointerEnd", "onWindowBlur"]; _i2 < _arr.length; _i2++) {
        var _t2 = _arr[_i2];
        this[_t2] = this[_t2].bind(this);
      }
      this.element.addEventListener("mousedown", this.onPointerStart, l), this.element.addEventListener("touchstart", this.onTouchStart, l), this.element.addEventListener("touchmove", this.onMove, l), this.element.addEventListener("touchend", this.onTouchEnd), this.element.addEventListener("touchcancel", this.onTouchEnd);
    }
    _createClass(c, [{
      key: "onPointerStart",
      value: function onPointerStart(t) {
        if (!t.buttons || 0 !== t.button) return;
        var e = new r(t);
        this.currentPointers.some(function (t) {
          return t.id === e.id;
        }) || this.triggerPointerStart(e, t) && (window.addEventListener("mousemove", this.onMove), window.addEventListener("mouseup", this.onPointerEnd), window.addEventListener("blur", this.onWindowBlur));
      }
    }, {
      key: "onTouchStart",
      value: function onTouchStart(t) {
        for (var _i3 = 0, _Array$from = Array.from(t.changedTouches || []); _i3 < _Array$from.length; _i3++) {
          var _e = _Array$from[_i3];
          this.triggerPointerStart(new r(_e), t);
        }
        window.addEventListener("blur", this.onWindowBlur);
      }
    }, {
      key: "onMove",
      value: function onMove(t) {
        var _this13 = this;
        var e = this.currentPointers.slice(),
          i = "changedTouches" in t ? Array.from(t.changedTouches || []).map(function (t) {
            return new r(t);
          }) : [new r(t)],
          n = [];
        var _iterator = _createForOfIteratorHelper(i),
          _step;
        try {
          var _loop = function _loop() {
            var t = _step.value;
            var e = _this13.currentPointers.findIndex(function (e) {
              return e.id === t.id;
            });
            e < 0 || (n.push(t), _this13.currentPointers[e] = t);
          };
          for (_iterator.s(); !(_step = _iterator.n()).done;) {
            _loop();
          }
        } catch (err) {
          _iterator.e(err);
        } finally {
          _iterator.f();
        }
        n.length && this.moveCallback(t, this.currentPointers.slice(), e);
      }
    }, {
      key: "onPointerEnd",
      value: function onPointerEnd(t) {
        t.buttons > 0 && 0 !== t.button || (this.triggerPointerEnd(t, new r(t)), window.removeEventListener("mousemove", this.onMove), window.removeEventListener("mouseup", this.onPointerEnd), window.removeEventListener("blur", this.onWindowBlur));
      }
    }, {
      key: "onTouchEnd",
      value: function onTouchEnd(t) {
        for (var _i4 = 0, _Array$from2 = Array.from(t.changedTouches || []); _i4 < _Array$from2.length; _i4++) {
          var _e2 = _Array$from2[_i4];
          this.triggerPointerEnd(t, new r(_e2));
        }
      }
    }, {
      key: "triggerPointerStart",
      value: function triggerPointerStart(t, e) {
        return !!this.startCallback(e, t, this.currentPointers.slice()) && (this.currentPointers.push(t), this.startPointers.push(t), !0);
      }
    }, {
      key: "triggerPointerEnd",
      value: function triggerPointerEnd(t, e) {
        var i = this.currentPointers.findIndex(function (t) {
          return t.id === e.id;
        });
        i < 0 || (this.currentPointers.splice(i, 1), this.startPointers.splice(i, 1), this.endCallback(t, e, this.currentPointers.slice()));
      }
    }, {
      key: "onWindowBlur",
      value: function onWindowBlur() {
        this.clear();
      }
    }, {
      key: "clear",
      value: function clear() {
        for (; this.currentPointers.length;) {
          var _t3 = this.currentPointers[this.currentPointers.length - 1];
          this.currentPointers.splice(this.currentPointers.length - 1, 1), this.startPointers.splice(this.currentPointers.length - 1, 1), this.endCallback(new Event("touchend", {
            bubbles: !0,
            cancelable: !0,
            clientX: _t3.clientX,
            clientY: _t3.clientY
          }), _t3, this.currentPointers.slice());
        }
      }
    }, {
      key: "stop",
      value: function stop() {
        this.element.removeEventListener("mousedown", this.onPointerStart, l), this.element.removeEventListener("touchstart", this.onTouchStart, l), this.element.removeEventListener("touchmove", this.onMove, l), this.element.removeEventListener("touchend", this.onTouchEnd), this.element.removeEventListener("touchcancel", this.onTouchEnd), window.removeEventListener("mousemove", this.onMove), window.removeEventListener("mouseup", this.onPointerEnd), window.removeEventListener("blur", this.onWindowBlur);
      }
    }]);
    return c;
  }();
  function h(t, e) {
    return e ? Math.sqrt(Math.pow(e.clientX - t.clientX, 2) + Math.pow(e.clientY - t.clientY, 2)) : 0;
  }
  function d(t, e) {
    return e ? {
      clientX: (t.clientX + e.clientX) / 2,
      clientY: (t.clientY + e.clientY) / 2
    } : t;
  }
  var u = function u(t) {
      return "object" == _typeof(t) && null !== t && t.constructor === Object && "[object Object]" === Object.prototype.toString.call(t);
    },
    p = function p(t) {
      var i = arguments.length <= 1 ? 0 : arguments.length - 1;
      for (var _n = 0; _n < i; _n++) {
        var _i5 = (_n + 1 < 1 || arguments.length <= _n + 1 ? undefined : arguments[_n + 1]) || {};
        Object.entries(_i5).forEach(function (_ref2) {
          var _ref3 = _slicedToArray(_ref2, 2),
            e = _ref3[0],
            i = _ref3[1];
          var n = Array.isArray(i) ? [] : {};
          t[e] || Object.assign(t, _defineProperty({}, e, n)), u(i) ? Object.assign(t[e], p(n, i)) : Array.isArray(i) ? Object.assign(t, _defineProperty({}, e, _toConsumableArray(i))) : Object.assign(t, _defineProperty({}, e, i));
        });
      }
      return t;
    },
    f = function f(t, e) {
      return t.split(".").reduce(function (t, e) {
        return "object" == _typeof(t) ? t[e] : void 0;
      }, e);
    };
  var g = /*#__PURE__*/function () {
    function g() {
      var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
      _classCallCheck(this, g);
      Object.defineProperty(this, "options", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: t
      }), Object.defineProperty(this, "events", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: new Map()
      }), this.setOptions(t);
      var _iterator2 = _createForOfIteratorHelper(Object.getOwnPropertyNames(Object.getPrototypeOf(this))),
        _step2;
      try {
        for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
          var _t4 = _step2.value;
          _t4.startsWith("on") && "function" == typeof this[_t4] && (this[_t4] = this[_t4].bind(this));
        }
      } catch (err) {
        _iterator2.e(err);
      } finally {
        _iterator2.f();
      }
    }
    _createClass(g, [{
      key: "setOptions",
      value: function setOptions(t) {
        this.options = t ? p({}, this.constructor.defaults, t) : {};
        for (var _i6 = 0, _Object$entries = Object.entries(this.option("on") || {}); _i6 < _Object$entries.length; _i6++) {
          var _Object$entries$_i = _slicedToArray(_Object$entries[_i6], 2),
            _t5 = _Object$entries$_i[0],
            _e3 = _Object$entries$_i[1];
          this.on(_t5, _e3);
        }
      }
    }, {
      key: "option",
      value: function option(t) {
        var _i7;
        var i = f(t, this.options);
        for (var _len = arguments.length, e = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
          e[_key - 1] = arguments[_key];
        }
        return i && "function" == typeof i && (i = (_i7 = i).call.apply(_i7, [this, this].concat(e))), i;
      }
    }, {
      key: "optionFor",
      value: function optionFor(t, e, i) {
        var _s;
        var s = f(e, t);
        var o;
        for (var _len2 = arguments.length, n = new Array(_len2 > 3 ? _len2 - 3 : 0), _key2 = 3; _key2 < _len2; _key2++) {
          n[_key2 - 3] = arguments[_key2];
        }
        "string" != typeof (o = s) || isNaN(o) || isNaN(parseFloat(o)) || (s = parseFloat(s)), "true" === s && (s = !0), "false" === s && (s = !1), s && "function" == typeof s && (s = (_s = s).call.apply(_s, [this, this, t].concat(n)));
        var a = f(e, this.options);
        return a && "function" == typeof a ? s = a.call.apply(a, [this, this, t].concat(n, [s])) : void 0 === s && (s = a), void 0 === s ? i : s;
      }
    }, {
      key: "cn",
      value: function cn(t) {
        var e = this.options.classes;
        return e && e[t] || "";
      }
    }, {
      key: "localize",
      value: function localize(t) {
        var _this14 = this;
        var e = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];
        t = String(t).replace(/\{\{(\w+).?(\w+)?\}\}/g, function (t, e, i) {
          var n = "";
          return i ? n = _this14.option("".concat(e[0] + e.toLowerCase().substring(1), ".l10n.").concat(i)) : e && (n = _this14.option("l10n.".concat(e))), n || (n = t), n;
        });
        for (var _i8 = 0; _i8 < e.length; _i8++) t = t.split(e[_i8][0]).join(e[_i8][1]);
        return t = t.replace(/\{\{(.*?)\}\}/g, function (t, e) {
          return e;
        });
      }
    }, {
      key: "on",
      value: function on(t, e) {
        var _this15 = this;
        var i = [];
        "string" == typeof t ? i = t.split(" ") : Array.isArray(t) && (i = t), this.events || (this.events = new Map()), i.forEach(function (t) {
          var i = _this15.events.get(t);
          i || (_this15.events.set(t, []), i = []), i.includes(e) || i.push(e), _this15.events.set(t, i);
        });
      }
    }, {
      key: "off",
      value: function off(t, e) {
        var _this16 = this;
        var i = [];
        "string" == typeof t ? i = t.split(" ") : Array.isArray(t) && (i = t), i.forEach(function (t) {
          var i = _this16.events.get(t);
          if (Array.isArray(i)) {
            var _t6 = i.indexOf(e);
            _t6 > -1 && i.splice(_t6, 1);
          }
        });
      }
    }, {
      key: "emit",
      value: function emit(t) {
        var _this17 = this;
        for (var _len3 = arguments.length, e = new Array(_len3 > 1 ? _len3 - 1 : 0), _key3 = 1; _key3 < _len3; _key3++) {
          e[_key3 - 1] = arguments[_key3];
        }
        _toConsumableArray(this.events.get(t) || []).forEach(function (t) {
          return t.apply(void 0, [_this17].concat(e));
        }), "*" !== t && this.emit.apply(this, ["*", t].concat(e));
      }
    }]);
    return g;
  }();
  Object.defineProperty(g, "version", {
    enumerable: !0,
    configurable: !0,
    writable: !0,
    value: "5.0.33"
  }), Object.defineProperty(g, "defaults", {
    enumerable: !0,
    configurable: !0,
    writable: !0,
    value: {}
  });
  var m = /*#__PURE__*/function (_g) {
    _inherits(m, _g);
    var _super5 = _createSuper(m);
    function m() {
      var _this18;
      var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
      _classCallCheck(this, m);
      _this18 = _super5.call(this, t), Object.defineProperty(_assertThisInitialized(_this18), "plugins", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: {}
      });
      return _this18;
    }
    _createClass(m, [{
      key: "attachPlugins",
      value: function attachPlugins() {
        var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
        var e = new Map();
        for (var _i9 = 0, _Object$entries2 = Object.entries(t); _i9 < _Object$entries2.length; _i9++) {
          var _Object$entries2$_i = _slicedToArray(_Object$entries2[_i9], 2),
            _i10 = _Object$entries2$_i[0],
            _n2 = _Object$entries2$_i[1];
          var _t7 = this.option(_i10),
            _s2 = this.plugins[_i10];
          _s2 || !1 === _t7 ? _s2 && !1 === _t7 && (_s2.detach(), delete this.plugins[_i10]) : e.set(_i10, new _n2(this, _t7 || {}));
        }
        var _iterator3 = _createForOfIteratorHelper(e),
          _step3;
        try {
          for (_iterator3.s(); !(_step3 = _iterator3.n()).done;) {
            var _step3$value = _slicedToArray(_step3.value, 2),
              _t8 = _step3$value[0],
              _i11 = _step3$value[1];
            this.plugins[_t8] = _i11, _i11.attach();
          }
        } catch (err) {
          _iterator3.e(err);
        } finally {
          _iterator3.f();
        }
      }
    }, {
      key: "detachPlugins",
      value: function detachPlugins(t) {
        t = t || Object.keys(this.plugins);
        var _iterator4 = _createForOfIteratorHelper(t),
          _step4;
        try {
          for (_iterator4.s(); !(_step4 = _iterator4.n()).done;) {
            var _e4 = _step4.value;
            var _t9 = this.plugins[_e4];
            _t9 && _t9.detach(), delete this.plugins[_e4];
          }
        } catch (err) {
          _iterator4.e(err);
        } finally {
          _iterator4.f();
        }
        return this.emit("detachPlugins"), this;
      }
    }]);
    return m;
  }(g);
  var v;
  !function (t) {
    t[t.Init = 0] = "Init", t[t.Error = 1] = "Error", t[t.Ready = 2] = "Ready", t[t.Panning = 3] = "Panning", t[t.Mousemove = 4] = "Mousemove", t[t.Destroy = 5] = "Destroy";
  }(v || (v = {}));
  var b = ["a", "b", "c", "d", "e", "f"],
    y = {
      PANUP: "Move up",
      PANDOWN: "Move down",
      PANLEFT: "Move left",
      PANRIGHT: "Move right",
      ZOOMIN: "Zoom in",
      ZOOMOUT: "Zoom out",
      TOGGLEZOOM: "Toggle zoom level",
      TOGGLE1TO1: "Toggle zoom level",
      ITERATEZOOM: "Toggle zoom level",
      ROTATECCW: "Rotate counterclockwise",
      ROTATECW: "Rotate clockwise",
      FLIPX: "Flip horizontally",
      FLIPY: "Flip vertically",
      FITX: "Fit horizontally",
      FITY: "Fit vertically",
      RESET: "Reset",
      TOGGLEFS: "Toggle fullscreen"
    },
    w = {
      content: null,
      width: "auto",
      height: "auto",
      panMode: "drag",
      touch: !0,
      dragMinThreshold: 3,
      lockAxis: !1,
      mouseMoveFactor: 1,
      mouseMoveFriction: .12,
      zoom: !0,
      pinchToZoom: !0,
      panOnlyZoomed: "auto",
      minScale: 1,
      maxScale: 2,
      friction: .25,
      dragFriction: .35,
      decelFriction: .05,
      click: "toggleZoom",
      dblClick: !1,
      wheel: "zoom",
      wheelLimit: 7,
      spinner: !0,
      bounds: "auto",
      infinite: !1,
      rubberband: !0,
      bounce: !0,
      maxVelocity: 75,
      transformParent: !1,
      classes: {
        content: "f-panzoom__content",
        isLoading: "is-loading",
        canZoomIn: "can-zoom_in",
        canZoomOut: "can-zoom_out",
        isDraggable: "is-draggable",
        isDragging: "is-dragging",
        inFullscreen: "in-fullscreen",
        htmlHasFullscreen: "with-panzoom-in-fullscreen"
      },
      l10n: y
    },
    x = '<circle cx="25" cy="25" r="20"></circle>',
    E = '<div class="f-spinner"><svg viewBox="0 0 50 50">' + x + x + "</svg></div>",
    S = function S(t) {
      return t && null !== t && t instanceof Element && "nodeType" in t;
    },
    P = function P(t, e) {
      t && o(e).forEach(function (e) {
        t.classList.remove(e);
      });
    },
    C = function C(t, e) {
      t && o(e).forEach(function (e) {
        t.classList.add(e);
      });
    },
    T = {
      a: 1,
      b: 0,
      c: 0,
      d: 1,
      e: 0,
      f: 0
    },
    M = 1e5,
    O = 1e4,
    A = "mousemove",
    L = "drag",
    z = "content";
  var R = null,
    k = null;
  var I = /*#__PURE__*/function (_m) {
    _inherits(I, _m);
    var _super6 = _createSuper(I);
    function I(t) {
      var _this19;
      var e = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
      var i = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
      _classCallCheck(this, I);
      var n;
      if (_this19 = _super6.call(this, e), Object.defineProperty(_assertThisInitialized(_this19), "pointerTracker", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this19), "resizeObserver", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this19), "updateTimer", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this19), "clickTimer", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this19), "rAF", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this19), "isTicking", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: !1
      }), Object.defineProperty(_assertThisInitialized(_this19), "ignoreBounds", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: !1
      }), Object.defineProperty(_assertThisInitialized(_this19), "isBouncingX", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: !1
      }), Object.defineProperty(_assertThisInitialized(_this19), "isBouncingY", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: !1
      }), Object.defineProperty(_assertThisInitialized(_this19), "clicks", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: 0
      }), Object.defineProperty(_assertThisInitialized(_this19), "trackingPoints", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: []
      }), Object.defineProperty(_assertThisInitialized(_this19), "pwt", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: 0
      }), Object.defineProperty(_assertThisInitialized(_this19), "cwd", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: 0
      }), Object.defineProperty(_assertThisInitialized(_this19), "pmme", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: void 0
      }), Object.defineProperty(_assertThisInitialized(_this19), "friction", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: 0
      }), Object.defineProperty(_assertThisInitialized(_this19), "state", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: v.Init
      }), Object.defineProperty(_assertThisInitialized(_this19), "isDragging", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: !1
      }), Object.defineProperty(_assertThisInitialized(_this19), "container", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: void 0
      }), Object.defineProperty(_assertThisInitialized(_this19), "content", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: void 0
      }), Object.defineProperty(_assertThisInitialized(_this19), "spinner", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this19), "containerRect", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: {
          width: 0,
          height: 0,
          innerWidth: 0,
          innerHeight: 0
        }
      }), Object.defineProperty(_assertThisInitialized(_this19), "contentRect", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: {
          top: 0,
          right: 0,
          bottom: 0,
          left: 0,
          fullWidth: 0,
          fullHeight: 0,
          fitWidth: 0,
          fitHeight: 0,
          width: 0,
          height: 0
        }
      }), Object.defineProperty(_assertThisInitialized(_this19), "dragStart", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: {
          x: 0,
          y: 0,
          top: 0,
          left: 0,
          time: 0
        }
      }), Object.defineProperty(_assertThisInitialized(_this19), "dragOffset", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: {
          x: 0,
          y: 0,
          time: 0
        }
      }), Object.defineProperty(_assertThisInitialized(_this19), "current", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: Object.assign({}, T)
      }), Object.defineProperty(_assertThisInitialized(_this19), "target", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: Object.assign({}, T)
      }), Object.defineProperty(_assertThisInitialized(_this19), "velocity", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: {
          a: 0,
          b: 0,
          c: 0,
          d: 0,
          e: 0,
          f: 0
        }
      }), Object.defineProperty(_assertThisInitialized(_this19), "lockedAxis", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: !1
      }), !t) throw new Error("Container Element Not Found");
      _this19.container = t, _this19.initContent(), _this19.attachPlugins(Object.assign(Object.assign({}, I.Plugins), i)), _this19.emit("attachPlugins"), _this19.emit("init");
      var o = _this19.content;
      if (o.addEventListener("load", _this19.onLoad), o.addEventListener("error", _this19.onError), _this19.isContentLoading) {
        if (_this19.option("spinner")) {
          t.classList.add(_this19.cn("isLoading"));
          var _e5 = s(E);
          !t.contains(o) || o.parentElement instanceof HTMLPictureElement ? _this19.spinner = t.appendChild(_e5) : _this19.spinner = (null === (n = o.parentElement) || void 0 === n ? void 0 : n.insertBefore(_e5, o)) || null;
        }
        _this19.emit("beforeLoad");
      } else queueMicrotask(function () {
        _this19.enable();
      });
      return _possibleConstructorReturn(_this19);
    }
    _createClass(I, [{
      key: "fits",
      get: function get() {
        return this.contentRect.width - this.contentRect.fitWidth < 1 && this.contentRect.height - this.contentRect.fitHeight < 1;
      }
    }, {
      key: "isTouchDevice",
      get: function get() {
        return null === k && (k = window.matchMedia("(hover: none)").matches), k;
      }
    }, {
      key: "isMobile",
      get: function get() {
        return null === R && (R = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent)), R;
      }
    }, {
      key: "panMode",
      get: function get() {
        return this.options.panMode !== A || this.isTouchDevice ? L : A;
      }
    }, {
      key: "panOnlyZoomed",
      get: function get() {
        var t = this.options.panOnlyZoomed;
        return "auto" === t ? this.isTouchDevice : t;
      }
    }, {
      key: "isInfinite",
      get: function get() {
        return this.option("infinite");
      }
    }, {
      key: "angle",
      get: function get() {
        return 180 * Math.atan2(this.current.b, this.current.a) / Math.PI || 0;
      }
    }, {
      key: "targetAngle",
      get: function get() {
        return 180 * Math.atan2(this.target.b, this.target.a) / Math.PI || 0;
      }
    }, {
      key: "scale",
      get: function get() {
        var _this$current = this.current,
          t = _this$current.a,
          e = _this$current.b;
        return Math.sqrt(t * t + e * e) || 1;
      }
    }, {
      key: "targetScale",
      get: function get() {
        var _this$target = this.target,
          t = _this$target.a,
          e = _this$target.b;
        return Math.sqrt(t * t + e * e) || 1;
      }
    }, {
      key: "minScale",
      get: function get() {
        return this.option("minScale") || 1;
      }
    }, {
      key: "fullScale",
      get: function get() {
        var t = this.contentRect;
        return t.fullWidth / t.fitWidth || 1;
      }
    }, {
      key: "maxScale",
      get: function get() {
        return this.fullScale * (this.option("maxScale") || 1) || 1;
      }
    }, {
      key: "coverScale",
      get: function get() {
        var t = this.containerRect,
          e = this.contentRect,
          i = Math.max(t.height / e.fitHeight, t.width / e.fitWidth) || 1;
        return Math.min(this.fullScale, i);
      }
    }, {
      key: "isScaling",
      get: function get() {
        return Math.abs(this.targetScale - this.scale) > 1e-5 && !this.isResting;
      }
    }, {
      key: "isContentLoading",
      get: function get() {
        var t = this.content;
        return !!(t && t instanceof HTMLImageElement) && !t.complete;
      }
    }, {
      key: "isResting",
      get: function get() {
        if (this.isBouncingX || this.isBouncingY) return !1;
        for (var _i12 = 0, _b = b; _i12 < _b.length; _i12++) {
          var _t10 = _b[_i12];
          var _e6 = "e" == _t10 || "f" === _t10 ? 1e-4 : 1e-5;
          if (Math.abs(this.target[_t10] - this.current[_t10]) > _e6) return !1;
        }
        return !(!this.ignoreBounds && !this.checkBounds().inBounds);
      }
    }, {
      key: "initContent",
      value: function initContent() {
        var t = this.container,
          e = this.cn(z);
        var i = this.option(z) || t.querySelector(".".concat(e));
        if (i || (i = t.querySelector("img,picture") || t.firstElementChild, i && C(i, e)), i instanceof HTMLPictureElement && (i = i.querySelector("img")), !i) throw new Error("No content found");
        this.content = i;
      }
    }, {
      key: "onLoad",
      value: function onLoad() {
        var t = this.spinner,
          e = this.container,
          i = this.state;
        t && (t.remove(), this.spinner = null), this.option("spinner") && e.classList.remove(this.cn("isLoading")), this.emit("afterLoad"), i === v.Init ? this.enable() : this.updateMetrics();
      }
    }, {
      key: "onError",
      value: function onError() {
        this.state !== v.Destroy && (this.spinner && (this.spinner.remove(), this.spinner = null), this.stop(), this.detachEvents(), this.state = v.Error, this.emit("error"));
      }
    }, {
      key: "getNextScale",
      value: function getNextScale(t) {
        var e = this.fullScale,
          i = this.targetScale,
          n = this.coverScale,
          s = this.maxScale,
          o = this.minScale;
        var a = o;
        switch (t) {
          case "toggleMax":
            a = i - o < .5 * (s - o) ? s : o;
            break;
          case "toggleCover":
            a = i - o < .5 * (n - o) ? n : o;
            break;
          case "toggleZoom":
            a = i - o < .5 * (e - o) ? e : o;
            break;
          case "iterateZoom":
            var _t11 = [1, e, s].sort(function (t, e) {
                return t - e;
              }),
              _r = _t11.findIndex(function (t) {
                return t > i + 1e-5;
              });
            a = _t11[_r] || 1;
        }
        return a;
      }
    }, {
      key: "attachObserver",
      value: function attachObserver() {
        var _this20 = this;
        var t;
        var e = function e() {
          var t = _this20.container,
            e = _this20.containerRect;
          return Math.abs(e.width - t.getBoundingClientRect().width) > .1 || Math.abs(e.height - t.getBoundingClientRect().height) > .1;
        };
        this.resizeObserver || void 0 === window.ResizeObserver || (this.resizeObserver = new ResizeObserver(function () {
          _this20.updateTimer || (e() ? (_this20.onResize(), _this20.isMobile && (_this20.updateTimer = setTimeout(function () {
            e() && _this20.onResize(), _this20.updateTimer = null;
          }, 500))) : _this20.updateTimer && (clearTimeout(_this20.updateTimer), _this20.updateTimer = null));
        })), null === (t = this.resizeObserver) || void 0 === t || t.observe(this.container);
      }
    }, {
      key: "detachObserver",
      value: function detachObserver() {
        var t;
        null === (t = this.resizeObserver) || void 0 === t || t.disconnect();
      }
    }, {
      key: "attachEvents",
      value: function attachEvents() {
        var t = this.container;
        t.addEventListener("click", this.onClick, {
          passive: !1,
          capture: !1
        }), t.addEventListener("wheel", this.onWheel, {
          passive: !1
        }), this.pointerTracker = new c(t, {
          start: this.onPointerDown,
          move: this.onPointerMove,
          end: this.onPointerUp
        }), document.addEventListener(A, this.onMouseMove);
      }
    }, {
      key: "detachEvents",
      value: function detachEvents() {
        var t;
        var e = this.container;
        e.removeEventListener("click", this.onClick, {
          passive: !1,
          capture: !1
        }), e.removeEventListener("wheel", this.onWheel, {
          passive: !1
        }), null === (t = this.pointerTracker) || void 0 === t || t.stop(), this.pointerTracker = null, document.removeEventListener(A, this.onMouseMove), document.removeEventListener("keydown", this.onKeydown, !0), this.clickTimer && (clearTimeout(this.clickTimer), this.clickTimer = null), this.updateTimer && (clearTimeout(this.updateTimer), this.updateTimer = null);
      }
    }, {
      key: "animate",
      value: function animate() {
        var _this21 = this;
        this.setTargetForce();
        var t = this.friction,
          e = this.option("maxVelocity");
        for (var _i13 = 0, _b2 = b; _i13 < _b2.length; _i13++) {
          var _i14 = _b2[_i13];
          t ? (this.velocity[_i14] *= 1 - t, e && !this.isScaling && (this.velocity[_i14] = Math.max(Math.min(this.velocity[_i14], e), -1 * e)), this.current[_i14] += this.velocity[_i14]) : this.current[_i14] = this.target[_i14];
        }
        this.setTransform(), this.setEdgeForce(), !this.isResting || this.isDragging ? this.rAF = requestAnimationFrame(function () {
          return _this21.animate();
        }) : this.stop("current");
      }
    }, {
      key: "setTargetForce",
      value: function setTargetForce() {
        for (var _i15 = 0, _b3 = b; _i15 < _b3.length; _i15++) {
          var _t12 = _b3[_i15];
          "e" === _t12 && this.isBouncingX || "f" === _t12 && this.isBouncingY || (this.velocity[_t12] = (1 / (1 - this.friction) - 1) * (this.target[_t12] - this.current[_t12]));
        }
      }
    }, {
      key: "checkBounds",
      value: function checkBounds() {
        var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
        var e = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
        var i = this.current,
          n = i.e + t,
          s = i.f + e,
          o = this.getBounds(),
          a = o.x,
          r = o.y,
          l = a.min,
          c = a.max,
          h = r.min,
          d = r.max;
        var u = 0,
          p = 0;
        return l !== 1 / 0 && n < l ? u = l - n : c !== 1 / 0 && n > c && (u = c - n), h !== 1 / 0 && s < h ? p = h - s : d !== 1 / 0 && s > d && (p = d - s), Math.abs(u) < 1e-4 && (u = 0), Math.abs(p) < 1e-4 && (p = 0), Object.assign(Object.assign({}, o), {
          xDiff: u,
          yDiff: p,
          inBounds: !u && !p
        });
      }
    }, {
      key: "clampTargetBounds",
      value: function clampTargetBounds() {
        var t = this.target,
          _this$getBounds = this.getBounds(),
          e = _this$getBounds.x,
          i = _this$getBounds.y;
        e.min !== 1 / 0 && (t.e = Math.max(t.e, e.min)), e.max !== 1 / 0 && (t.e = Math.min(t.e, e.max)), i.min !== 1 / 0 && (t.f = Math.max(t.f, i.min)), i.max !== 1 / 0 && (t.f = Math.min(t.f, i.max));
      }
    }, {
      key: "calculateContentDim",
      value: function calculateContentDim() {
        var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : this.current;
        var e = this.content,
          i = this.contentRect,
          n = i.fitWidth,
          s = i.fitHeight,
          o = i.fullWidth,
          a = i.fullHeight;
        var r = o,
          l = a;
        if (this.option("zoom") || 0 !== this.angle) {
          var _i16 = !(e instanceof HTMLImageElement) && ("none" === window.getComputedStyle(e).maxWidth || "none" === window.getComputedStyle(e).maxHeight),
            _c = _i16 ? o : n,
            _h = _i16 ? a : s,
            _d = this.getMatrix(t),
            _u = new DOMPoint(0, 0).matrixTransform(_d),
            _p = new DOMPoint(0 + _c, 0).matrixTransform(_d),
            _f = new DOMPoint(0 + _c, 0 + _h).matrixTransform(_d),
            _g2 = new DOMPoint(0, 0 + _h).matrixTransform(_d),
            _m2 = Math.abs(_f.x - _u.x),
            _v = Math.abs(_f.y - _u.y),
            _b4 = Math.abs(_g2.x - _p.x),
            _y = Math.abs(_g2.y - _p.y);
          r = Math.max(_m2, _b4), l = Math.max(_v, _y);
        }
        return {
          contentWidth: r,
          contentHeight: l
        };
      }
    }, {
      key: "setEdgeForce",
      value: function setEdgeForce() {
        if (this.ignoreBounds || this.isDragging || this.panMode === A || this.targetScale < this.scale) return this.isBouncingX = !1, void (this.isBouncingY = !1);
        var t = this.target,
          _this$checkBounds = this.checkBounds(),
          e = _this$checkBounds.x,
          i = _this$checkBounds.y,
          n = _this$checkBounds.xDiff,
          s = _this$checkBounds.yDiff;
        var o = this.option("maxVelocity");
        var a = this.velocity.e,
          r = this.velocity.f;
        0 !== n ? (this.isBouncingX = !0, n * a <= 0 ? a += .14 * n : (a = .14 * n, e.min !== 1 / 0 && (this.target.e = Math.max(t.e, e.min)), e.max !== 1 / 0 && (this.target.e = Math.min(t.e, e.max))), o && (a = Math.max(Math.min(a, o), -1 * o))) : this.isBouncingX = !1, 0 !== s ? (this.isBouncingY = !0, s * r <= 0 ? r += .14 * s : (r = .14 * s, i.min !== 1 / 0 && (this.target.f = Math.max(t.f, i.min)), i.max !== 1 / 0 && (this.target.f = Math.min(t.f, i.max))), o && (r = Math.max(Math.min(r, o), -1 * o))) : this.isBouncingY = !1, this.isBouncingX && (this.velocity.e = a), this.isBouncingY && (this.velocity.f = r);
      }
    }, {
      key: "enable",
      value: function enable() {
        var t = this.content,
          e = new DOMMatrixReadOnly(window.getComputedStyle(t).transform);
        for (var _i17 = 0, _b5 = b; _i17 < _b5.length; _i17++) {
          var _t13 = _b5[_i17];
          this.current[_t13] = this.target[_t13] = e[_t13];
        }
        this.updateMetrics(), this.attachObserver(), this.attachEvents(), this.state = v.Ready, this.emit("ready");
      }
    }, {
      key: "onClick",
      value: function onClick(t) {
        var _this22 = this;
        var e;
        "click" === t.type && 0 === t.detail && (this.dragOffset.x = 0, this.dragOffset.y = 0), this.isDragging && (null === (e = this.pointerTracker) || void 0 === e || e.clear(), this.trackingPoints = [], this.startDecelAnim());
        var i = t.target;
        if (!i || t.defaultPrevented) return;
        if (i.hasAttribute("disabled")) return t.preventDefault(), void t.stopPropagation();
        if (function () {
          var t = window.getSelection();
          return t && "Range" === t.type;
        }() && !i.closest("button")) return;
        var n = i.closest("[data-panzoom-action]"),
          s = i.closest("[data-panzoom-change]"),
          o = n || s,
          a = o && S(o) ? o.dataset : null;
        if (a) {
          var _e7 = a.panzoomChange,
            _i18 = a.panzoomAction;
          if ((_e7 || _i18) && t.preventDefault(), _e7) {
            var _t14 = {};
            try {
              _t14 = JSON.parse(_e7);
            } catch (t) {
              console && console.warn("The given data was not valid JSON");
            }
            return void this.applyChange(_t14);
          }
          if (_i18) return void (this[_i18] && this[_i18]());
        }
        if (Math.abs(this.dragOffset.x) > 3 || Math.abs(this.dragOffset.y) > 3) return t.preventDefault(), void t.stopPropagation();
        if (i.closest("[data-fancybox]")) return;
        var r = this.content.getBoundingClientRect(),
          l = this.dragStart;
        if (l.time && !this.canZoomOut() && (Math.abs(r.x - l.x) > 2 || Math.abs(r.y - l.y) > 2)) return;
        this.dragStart.time = 0;
        var c = function c(e) {
            _this22.option("zoom", t) && e && "string" == typeof e && /(iterateZoom)|(toggle(Zoom|Full|Cover|Max)|(zoomTo(Fit|Cover|Max)))/.test(e) && "function" == typeof _this22[e] && (t.preventDefault(), _this22[e]({
              event: t
            }));
          },
          h = this.option("click", t),
          d = this.option("dblClick", t);
        d ? (this.clicks++, 1 == this.clicks && (this.clickTimer = setTimeout(function () {
          1 === _this22.clicks ? (_this22.emit("click", t), !t.defaultPrevented && h && c(h)) : (_this22.emit("dblClick", t), t.defaultPrevented || c(d)), _this22.clicks = 0, _this22.clickTimer = null;
        }, 350))) : (this.emit("click", t), !t.defaultPrevented && h && c(h));
      }
    }, {
      key: "addTrackingPoint",
      value: function addTrackingPoint(t) {
        var e = this.trackingPoints.filter(function (t) {
          return t.time > Date.now() - 100;
        });
        e.push(t), this.trackingPoints = e;
      }
    }, {
      key: "onPointerDown",
      value: function onPointerDown(t, e, i) {
        var n;
        if (!1 === this.option("touch", t)) return !1;
        this.pwt = 0, this.dragOffset = {
          x: 0,
          y: 0,
          time: 0
        }, this.trackingPoints = [];
        var s = this.content.getBoundingClientRect();
        if (this.dragStart = {
          x: s.x,
          y: s.y,
          top: s.top,
          left: s.left,
          time: Date.now()
        }, this.clickTimer) return !1;
        if (this.panMode === A && this.targetScale > 1) return t.preventDefault(), t.stopPropagation(), !1;
        var o = t.composedPath()[0];
        if (!i.length) {
          if (["TEXTAREA", "OPTION", "INPUT", "SELECT", "VIDEO", "IFRAME"].includes(o.nodeName) || o.closest("[contenteditable],[data-selectable],[data-draggable],[data-clickable],[data-panzoom-change],[data-panzoom-action]")) return !1;
          null === (n = window.getSelection()) || void 0 === n || n.removeAllRanges();
        }
        if ("mousedown" === t.type) ["A", "BUTTON"].includes(o.nodeName) || t.preventDefault();else if (Math.abs(this.velocity.a) > .3) return !1;
        return this.target.e = this.current.e, this.target.f = this.current.f, this.stop(), this.isDragging || (this.isDragging = !0, this.addTrackingPoint(e), this.emit("touchStart", t)), !0;
      }
    }, {
      key: "onPointerMove",
      value: function onPointerMove(t, i, s) {
        if (!1 === this.option("touch", t)) return;
        if (!this.isDragging) return;
        if (i.length < 2 && this.panOnlyZoomed && e(this.targetScale) <= e(this.minScale)) return;
        if (this.emit("touchMove", t), t.defaultPrevented) return;
        this.addTrackingPoint(i[0]);
        var o = this.content,
          a = d(s[0], s[1]),
          r = d(i[0], i[1]);
        var l = 0,
          c = 0;
        if (i.length > 1) {
          var _t15 = o.getBoundingClientRect();
          l = a.clientX - _t15.left - .5 * _t15.width, c = a.clientY - _t15.top - .5 * _t15.height;
        }
        var u = h(s[0], s[1]),
          p = h(i[0], i[1]);
        var f = u ? p / u : 1,
          g = r.clientX - a.clientX,
          m = r.clientY - a.clientY;
        this.dragOffset.x += g, this.dragOffset.y += m, this.dragOffset.time = Date.now() - this.dragStart.time;
        var v = e(this.targetScale) === e(this.minScale) && this.option("lockAxis");
        if (v && !this.lockedAxis) if ("xy" === v || "y" === v || "touchmove" === t.type) {
          if (Math.abs(this.dragOffset.x) < 6 && Math.abs(this.dragOffset.y) < 6) return void t.preventDefault();
          var _e8 = Math.abs(180 * Math.atan2(this.dragOffset.y, this.dragOffset.x) / Math.PI);
          this.lockedAxis = _e8 > 45 && _e8 < 135 ? "y" : "x", this.dragOffset.x = 0, this.dragOffset.y = 0, g = 0, m = 0;
        } else this.lockedAxis = v;
        if (n(t.target, this.content) && (v = "x", this.dragOffset.y = 0), v && "xy" !== v && this.lockedAxis !== v && e(this.targetScale) === e(this.minScale)) return;
        t.cancelable && t.preventDefault(), this.container.classList.add(this.cn("isDragging"));
        var b = this.checkBounds(g, m);
        this.option("rubberband") ? ("x" !== this.isInfinite && (b.xDiff > 0 && g < 0 || b.xDiff < 0 && g > 0) && (g *= Math.max(0, .5 - Math.abs(.75 / this.contentRect.fitWidth * b.xDiff))), "y" !== this.isInfinite && (b.yDiff > 0 && m < 0 || b.yDiff < 0 && m > 0) && (m *= Math.max(0, .5 - Math.abs(.75 / this.contentRect.fitHeight * b.yDiff)))) : (b.xDiff && (g = 0), b.yDiff && (m = 0));
        var y = this.targetScale,
          w = this.minScale,
          x = this.maxScale;
        y < .5 * w && (f = Math.max(f, w)), y > 1.5 * x && (f = Math.min(f, x)), "y" === this.lockedAxis && e(y) === e(w) && (g = 0), "x" === this.lockedAxis && e(y) === e(w) && (m = 0), this.applyChange({
          originX: l,
          originY: c,
          panX: g,
          panY: m,
          scale: f,
          friction: this.option("dragFriction"),
          ignoreBounds: !0
        });
      }
    }, {
      key: "onPointerUp",
      value: function onPointerUp(t, e, i) {
        if (i.length) return this.dragOffset.x = 0, this.dragOffset.y = 0, void (this.trackingPoints = []);
        this.container.classList.remove(this.cn("isDragging")), this.isDragging && (this.addTrackingPoint(e), this.panOnlyZoomed && this.contentRect.width - this.contentRect.fitWidth < 1 && this.contentRect.height - this.contentRect.fitHeight < 1 && (this.trackingPoints = []), n(t.target, this.content) && "y" === this.lockedAxis && (this.trackingPoints = []), this.emit("touchEnd", t), this.isDragging = !1, this.lockedAxis = !1, this.state !== v.Destroy && (t.defaultPrevented || this.startDecelAnim()));
      }
    }, {
      key: "startDecelAnim",
      value: function startDecelAnim() {
        var t;
        var i = this.isScaling;
        this.rAF && (cancelAnimationFrame(this.rAF), this.rAF = null), this.isBouncingX = !1, this.isBouncingY = !1;
        for (var _i19 = 0, _b6 = b; _i19 < _b6.length; _i19++) {
          var _t16 = _b6[_i19];
          this.velocity[_t16] = 0;
        }
        this.target.e = this.current.e, this.target.f = this.current.f, P(this.container, "is-scaling"), P(this.container, "is-animating"), this.isTicking = !1;
        var n = this.trackingPoints,
          s = n[0],
          o = n[n.length - 1];
        var a = 0,
          r = 0,
          l = 0;
        o && s && (a = o.clientX - s.clientX, r = o.clientY - s.clientY, l = o.time - s.time);
        var c = (null === (t = window.visualViewport) || void 0 === t ? void 0 : t.scale) || 1;
        1 !== c && (a *= c, r *= c);
        var h = 0,
          d = 0,
          u = 0,
          p = 0,
          f = this.option("decelFriction");
        var g = this.targetScale;
        if (l > 0) {
          u = Math.abs(a) > 3 ? a / (l / 30) : 0, p = Math.abs(r) > 3 ? r / (l / 30) : 0;
          var _t17 = this.option("maxVelocity");
          _t17 && (u = Math.max(Math.min(u, _t17), -1 * _t17), p = Math.max(Math.min(p, _t17), -1 * _t17));
        }
        u && (h = u / (1 / (1 - f) - 1)), p && (d = p / (1 / (1 - f) - 1)), ("y" === this.option("lockAxis") || "xy" === this.option("lockAxis") && "y" === this.lockedAxis && e(g) === this.minScale) && (h = u = 0), ("x" === this.option("lockAxis") || "xy" === this.option("lockAxis") && "x" === this.lockedAxis && e(g) === this.minScale) && (d = p = 0);
        var m = this.dragOffset.x,
          v = this.dragOffset.y,
          y = this.option("dragMinThreshold") || 0;
        Math.abs(m) < y && Math.abs(v) < y && (h = d = 0, u = p = 0), (this.option("zoom") && (g < this.minScale - 1e-5 || g > this.maxScale + 1e-5) || i && !h && !d) && (f = .35), this.applyChange({
          panX: h,
          panY: d,
          friction: f
        }), this.emit("decel", u, p, m, v);
      }
    }, {
      key: "onWheel",
      value: function onWheel(t) {
        var e = [-t.deltaX || 0, -t.deltaY || 0, -t.detail || 0].reduce(function (t, e) {
          return Math.abs(e) > Math.abs(t) ? e : t;
        });
        var i = Math.max(-1, Math.min(1, e));
        if (this.emit("wheel", t, i), this.panMode === A) return;
        if (t.defaultPrevented) return;
        var n = this.option("wheel");
        "pan" === n ? (t.preventDefault(), this.panOnlyZoomed && !this.canZoomOut() || this.applyChange({
          panX: 2 * -t.deltaX,
          panY: 2 * -t.deltaY,
          bounce: !1
        })) : "zoom" === n && !1 !== this.option("zoom") && this.zoomWithWheel(t);
      }
    }, {
      key: "onMouseMove",
      value: function onMouseMove(t) {
        this.panWithMouse(t);
      }
    }, {
      key: "onKeydown",
      value: function onKeydown(t) {
        "Escape" === t.key && this.toggleFS();
      }
    }, {
      key: "onResize",
      value: function onResize() {
        this.updateMetrics(), this.checkBounds().inBounds || this.requestTick();
      }
    }, {
      key: "setTransform",
      value: function setTransform() {
        this.emit("beforeTransform");
        var t = this.current,
          i = this.target,
          n = this.content,
          s = this.contentRect,
          o = Object.assign({}, T);
        for (var _i20 = 0, _b7 = b; _i20 < _b7.length; _i20++) {
          var _n3 = _b7[_i20];
          var _s3 = "e" == _n3 || "f" === _n3 ? O : M;
          o[_n3] = e(t[_n3], _s3), Math.abs(i[_n3] - t[_n3]) < ("e" == _n3 || "f" === _n3 ? .51 : .001) && (t[_n3] = i[_n3]);
        }
        var a = o.a,
          r = o.b,
          l = o.c,
          c = o.d,
          h = o.e,
          d = o.f,
          u = "matrix(".concat(a, ", ").concat(r, ", ").concat(l, ", ").concat(c, ", ").concat(h, ", ").concat(d, ")"),
          p = n.parentElement instanceof HTMLPictureElement ? n.parentElement : n;
        if (this.option("transformParent") && (p = p.parentElement || p), p.style.transform === u) return;
        p.style.transform = u;
        var _this$calculateConten = this.calculateContentDim(),
          f = _this$calculateConten.contentWidth,
          g = _this$calculateConten.contentHeight;
        s.width = f, s.height = g, this.emit("afterTransform");
      }
    }, {
      key: "updateMetrics",
      value: function updateMetrics() {
        var _ref4;
        var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : !1;
        var i;
        if (!this || this.state === v.Destroy) return;
        if (this.isContentLoading) return;
        var n = Math.max(1, (null === (i = window.visualViewport) || void 0 === i ? void 0 : i.scale) || 1),
          s = this.container,
          o = this.content,
          a = o instanceof HTMLImageElement,
          r = s.getBoundingClientRect(),
          l = getComputedStyle(this.container);
        var c = r.width * n,
          h = r.height * n;
        var d = parseFloat(l.paddingTop) + parseFloat(l.paddingBottom),
          u = c - (parseFloat(l.paddingLeft) + parseFloat(l.paddingRight)),
          p = h - d;
        this.containerRect = {
          width: c,
          height: h,
          innerWidth: u,
          innerHeight: p
        };
        var f = this.option("width") || "auto",
          g = this.option("height") || "auto";
        "auto" === f && (f = parseFloat(o.dataset.width || "") || function (t) {
          var e = 0;
          return e = t instanceof HTMLImageElement ? t.naturalWidth : t instanceof SVGElement ? t.width.baseVal.value : Math.max(t.offsetWidth, t.scrollWidth), e || 0;
        }(o)), "auto" === g && (g = parseFloat(o.dataset.height || "") || function (t) {
          var e = 0;
          return e = t instanceof HTMLImageElement ? t.naturalHeight : t instanceof SVGElement ? t.height.baseVal.value : Math.max(t.offsetHeight, t.scrollHeight), e || 0;
        }(o));
        var m = o.parentElement instanceof HTMLPictureElement ? o.parentElement : o;
        this.option("transformParent") && (m = m.parentElement || m);
        var b = m.getAttribute("style") || "";
        m.style.setProperty("transform", "none", "important"), a && (m.style.width = "", m.style.height = ""), m.offsetHeight;
        var y = o.getBoundingClientRect();
        var w = y.width * n,
          x = y.height * n,
          E = 0,
          S = 0;
        a && (Math.abs(f - w) > 1 || Math.abs(g - x) > 1) && (_ref4 = function (t, e, i, n) {
          var s = i / n;
          return s > t / e ? (i = t, n = t / s) : (i = e * s, n = e), {
            width: i,
            height: n,
            top: .5 * (e - n),
            left: .5 * (t - i)
          };
        }(w, x, f, g), w = _ref4.width, x = _ref4.height, E = _ref4.top, S = _ref4.left, _ref4), this.contentRect = Object.assign(Object.assign({}, this.contentRect), {
          top: y.top - r.top + E,
          bottom: r.bottom - y.bottom + E,
          left: y.left - r.left + S,
          right: r.right - y.right + S,
          fitWidth: w,
          fitHeight: x,
          width: w,
          height: x,
          fullWidth: f,
          fullHeight: g
        }), m.style.cssText = b, a && (m.style.width = "".concat(w, "px"), m.style.height = "".concat(x, "px")), this.setTransform(), !0 !== t && this.emit("refresh"), this.ignoreBounds || (e(this.targetScale) < e(this.minScale) ? this.zoomTo(this.minScale, {
          friction: 0
        }) : this.targetScale > this.maxScale ? this.zoomTo(this.maxScale, {
          friction: 0
        }) : this.state === v.Init || this.checkBounds().inBounds || this.requestTick()), this.updateControls();
      }
    }, {
      key: "calculateBounds",
      value: function calculateBounds() {
        var _this$calculateConten2 = this.calculateContentDim(this.target),
          t = _this$calculateConten2.contentWidth,
          i = _this$calculateConten2.contentHeight,
          n = this.targetScale,
          s = this.lockedAxis,
          _this$contentRect = this.contentRect,
          o = _this$contentRect.fitWidth,
          a = _this$contentRect.fitHeight;
        var r = 0,
          l = 0,
          c = 0,
          h = 0;
        var d = this.option("infinite");
        if (!0 === d || s && d === s) r = -1 / 0, c = 1 / 0, l = -1 / 0, h = 1 / 0;else {
          var _s4 = this.containerRect,
            _d2 = this.contentRect,
            _u2 = e(o * n, O),
            _p2 = e(a * n, O),
            _f2 = _s4.innerWidth,
            _g3 = _s4.innerHeight;
          if (_s4.width === _u2 && (_f2 = _s4.width), _s4.width === _p2 && (_g3 = _s4.height), t > _f2) {
            c = .5 * (t - _f2), r = -1 * c;
            var _e9 = .5 * (_d2.right - _d2.left);
            r += _e9, c += _e9;
          }
          if (o > _f2 && t < _f2 && (r -= .5 * (o - _f2), c -= .5 * (o - _f2)), i > _g3) {
            h = .5 * (i - _g3), l = -1 * h;
            var _t18 = .5 * (_d2.bottom - _d2.top);
            l += _t18, h += _t18;
          }
          a > _g3 && i < _g3 && (r -= .5 * (a - _g3), c -= .5 * (a - _g3));
        }
        return {
          x: {
            min: r,
            max: c
          },
          y: {
            min: l,
            max: h
          }
        };
      }
    }, {
      key: "getBounds",
      value: function getBounds() {
        var t = this.option("bounds");
        return "auto" !== t ? t : this.calculateBounds();
      }
    }, {
      key: "updateControls",
      value: function updateControls() {
        var t = this,
          i = t.container,
          n = t.panMode,
          s = t.contentRect,
          o = t.targetScale,
          r = t.minScale;
        var l = r,
          c = t.option("click") || !1;
        c && (l = t.getNextScale(c));
        var h = t.canZoomIn(),
          d = t.canZoomOut(),
          u = n === L && !!this.option("touch"),
          p = d && u;
        if (u && (e(o) < e(r) && !this.panOnlyZoomed && (p = !0), (e(s.width, 1) > e(s.fitWidth, 1) || e(s.height, 1) > e(s.fitHeight, 1)) && (p = !0)), e(s.width * o, 1) < e(s.fitWidth, 1) && (p = !1), n === A && (p = !1), a(i, this.cn("isDraggable"), p), !this.option("zoom")) return;
        var f = h && e(l) > e(o),
          g = !f && !p && d && e(l) < e(o);
        a(i, this.cn("canZoomIn"), f), a(i, this.cn("canZoomOut"), g);
        var _iterator5 = _createForOfIteratorHelper(i.querySelectorAll("[data-panzoom-action]")),
          _step5;
        try {
          for (_iterator5.s(); !(_step5 = _iterator5.n()).done;) {
            var _t19 = _step5.value;
            var _e10 = !1,
              _i21 = !1;
            switch (_t19.dataset.panzoomAction) {
              case "zoomIn":
                h ? _e10 = !0 : _i21 = !0;
                break;
              case "zoomOut":
                d ? _e10 = !0 : _i21 = !0;
                break;
              case "toggleZoom":
              case "iterateZoom":
                h || d ? _e10 = !0 : _i21 = !0;
                var _n4 = _t19.querySelector("g");
                _n4 && (_n4.style.display = h ? "" : "none");
            }
            _e10 ? (_t19.removeAttribute("disabled"), _t19.removeAttribute("tabindex")) : _i21 && (_t19.setAttribute("disabled", ""), _t19.setAttribute("tabindex", "-1"));
          }
        } catch (err) {
          _iterator5.e(err);
        } finally {
          _iterator5.f();
        }
      }
    }, {
      key: "panTo",
      value: function panTo(_ref5) {
        var _ref5$x = _ref5.x,
          t = _ref5$x === void 0 ? this.target.e : _ref5$x,
          _ref5$y = _ref5.y,
          e = _ref5$y === void 0 ? this.target.f : _ref5$y,
          _ref5$scale = _ref5.scale,
          i = _ref5$scale === void 0 ? this.targetScale : _ref5$scale,
          _ref5$friction = _ref5.friction,
          n = _ref5$friction === void 0 ? this.option("friction") : _ref5$friction,
          _ref5$angle = _ref5.angle,
          s = _ref5$angle === void 0 ? 0 : _ref5$angle,
          _ref5$originX = _ref5.originX,
          o = _ref5$originX === void 0 ? 0 : _ref5$originX,
          _ref5$originY = _ref5.originY,
          a = _ref5$originY === void 0 ? 0 : _ref5$originY,
          _ref5$flipX = _ref5.flipX,
          r = _ref5$flipX === void 0 ? !1 : _ref5$flipX,
          _ref5$flipY = _ref5.flipY,
          l = _ref5$flipY === void 0 ? !1 : _ref5$flipY,
          _ref5$ignoreBounds = _ref5.ignoreBounds,
          c = _ref5$ignoreBounds === void 0 ? !1 : _ref5$ignoreBounds;
        this.state !== v.Destroy && this.applyChange({
          panX: t - this.target.e,
          panY: e - this.target.f,
          scale: i / this.targetScale,
          angle: s,
          originX: o,
          originY: a,
          friction: n,
          flipX: r,
          flipY: l,
          ignoreBounds: c
        });
      }
    }, {
      key: "applyChange",
      value: function applyChange(_ref6) {
        var _ref6$panX = _ref6.panX,
          t = _ref6$panX === void 0 ? 0 : _ref6$panX,
          _ref6$panY = _ref6.panY,
          i = _ref6$panY === void 0 ? 0 : _ref6$panY,
          _ref6$scale = _ref6.scale,
          n = _ref6$scale === void 0 ? 1 : _ref6$scale,
          _ref6$angle = _ref6.angle,
          s = _ref6$angle === void 0 ? 0 : _ref6$angle,
          _ref6$originX = _ref6.originX,
          o = _ref6$originX === void 0 ? -this.current.e : _ref6$originX,
          _ref6$originY = _ref6.originY,
          a = _ref6$originY === void 0 ? -this.current.f : _ref6$originY,
          _ref6$friction = _ref6.friction,
          r = _ref6$friction === void 0 ? this.option("friction") : _ref6$friction,
          _ref6$flipX = _ref6.flipX,
          l = _ref6$flipX === void 0 ? !1 : _ref6$flipX,
          _ref6$flipY = _ref6.flipY,
          c = _ref6$flipY === void 0 ? !1 : _ref6$flipY,
          _ref6$ignoreBounds = _ref6.ignoreBounds,
          h = _ref6$ignoreBounds === void 0 ? !1 : _ref6$ignoreBounds,
          _ref6$bounce = _ref6.bounce,
          d = _ref6$bounce === void 0 ? this.option("bounce") : _ref6$bounce;
        var u = this.state;
        if (u === v.Destroy) return;
        this.rAF && (cancelAnimationFrame(this.rAF), this.rAF = null), this.friction = r || 0, this.ignoreBounds = h;
        var p = this.current,
          f = p.e,
          g = p.f,
          m = this.getMatrix(this.target);
        var y = new DOMMatrix().translate(f, g).translate(o, a).translate(t, i);
        if (this.option("zoom")) {
          if (!h) {
            var _t20 = this.targetScale,
              _e11 = this.minScale,
              _i22 = this.maxScale;
            _t20 * n < _e11 && (n = _e11 / _t20), _t20 * n > _i22 && (n = _i22 / _t20);
          }
          y = y.scale(n);
        }
        y = y.translate(-o, -a).translate(-f, -g).multiply(m), s && (y = y.rotate(s)), l && (y = y.scale(-1, 1)), c && (y = y.scale(1, -1));
        for (var _i23 = 0, _b8 = b; _i23 < _b8.length; _i23++) {
          var _t21 = _b8[_i23];
          "e" !== _t21 && "f" !== _t21 && (y[_t21] > this.minScale + 1e-5 || y[_t21] < this.minScale - 1e-5) ? this.target[_t21] = y[_t21] : this.target[_t21] = e(y[_t21], O);
        }
        (this.targetScale < this.scale || Math.abs(n - 1) > .1 || this.panMode === A || !1 === d) && !h && this.clampTargetBounds(), u === v.Init ? this.animate() : this.isResting || (this.state = v.Panning, this.requestTick());
      }
    }, {
      key: "stop",
      value: function stop() {
        var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : !1;
        if (this.state === v.Init || this.state === v.Destroy) return;
        var e = this.isTicking;
        this.rAF && (cancelAnimationFrame(this.rAF), this.rAF = null), this.isBouncingX = !1, this.isBouncingY = !1;
        for (var _i24 = 0, _b9 = b; _i24 < _b9.length; _i24++) {
          var _e12 = _b9[_i24];
          this.velocity[_e12] = 0, "current" === t ? this.current[_e12] = this.target[_e12] : "target" === t && (this.target[_e12] = this.current[_e12]);
        }
        this.setTransform(), P(this.container, "is-scaling"), P(this.container, "is-animating"), this.isTicking = !1, this.state = v.Ready, e && (this.emit("endAnimation"), this.updateControls());
      }
    }, {
      key: "requestTick",
      value: function requestTick() {
        var _this23 = this;
        this.isTicking || (this.emit("startAnimation"), this.updateControls(), C(this.container, "is-animating"), this.isScaling && C(this.container, "is-scaling")), this.isTicking = !0, this.rAF || (this.rAF = requestAnimationFrame(function () {
          return _this23.animate();
        }));
      }
    }, {
      key: "panWithMouse",
      value: function panWithMouse(t) {
        var i = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : this.option("mouseMoveFriction");
        if (this.pmme = t, this.panMode !== A || !t) return;
        if (e(this.targetScale) <= e(this.minScale)) return;
        this.emit("mouseMove", t);
        var n = this.container,
          s = this.containerRect,
          o = this.contentRect,
          a = s.width,
          r = s.height,
          l = n.getBoundingClientRect(),
          c = (t.clientX || 0) - l.left,
          h = (t.clientY || 0) - l.top;
        var _this$calculateConten3 = this.calculateContentDim(this.target),
          d = _this$calculateConten3.contentWidth,
          u = _this$calculateConten3.contentHeight;
        var p = this.option("mouseMoveFactor");
        p > 1 && (d !== a && (d *= p), u !== r && (u *= p));
        var f = .5 * (d - a) - c / a * 100 / 100 * (d - a);
        f += .5 * (o.right - o.left);
        var g = .5 * (u - r) - h / r * 100 / 100 * (u - r);
        g += .5 * (o.bottom - o.top), this.applyChange({
          panX: f - this.target.e,
          panY: g - this.target.f,
          friction: i
        });
      }
    }, {
      key: "zoomWithWheel",
      value: function zoomWithWheel(t) {
        if (this.state === v.Destroy || this.state === v.Init) return;
        var i = Date.now();
        if (i - this.pwt < 45) return void t.preventDefault();
        this.pwt = i;
        var n = [-t.deltaX || 0, -t.deltaY || 0, -t.detail || 0].reduce(function (t, e) {
          return Math.abs(e) > Math.abs(t) ? e : t;
        });
        var s = Math.max(-1, Math.min(1, n)),
          o = this.targetScale,
          a = this.maxScale,
          r = this.minScale;
        var l = o * (100 + 45 * s) / 100;
        e(l) < e(r) && e(o) <= e(r) ? (this.cwd += Math.abs(s), l = r) : e(l) > e(a) && e(o) >= e(a) ? (this.cwd += Math.abs(s), l = a) : (this.cwd = 0, l = Math.max(Math.min(l, a), r)), this.cwd > this.option("wheelLimit") || (t.preventDefault(), e(l) !== e(o) && this.zoomTo(l, {
          event: t
        }));
      }
    }, {
      key: "canZoomIn",
      value: function canZoomIn() {
        return this.option("zoom") && (e(this.contentRect.width, 1) < e(this.contentRect.fitWidth, 1) || e(this.targetScale) < e(this.maxScale));
      }
    }, {
      key: "canZoomOut",
      value: function canZoomOut() {
        return this.option("zoom") && e(this.targetScale) > e(this.minScale);
      }
    }, {
      key: "zoomIn",
      value: function zoomIn() {
        var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 1.25;
        var e = arguments.length > 1 ? arguments[1] : undefined;
        this.zoomTo(this.targetScale * t, e);
      }
    }, {
      key: "zoomOut",
      value: function zoomOut() {
        var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : .8;
        var e = arguments.length > 1 ? arguments[1] : undefined;
        this.zoomTo(this.targetScale * t, e);
      }
    }, {
      key: "zoomToFit",
      value: function zoomToFit(t) {
        this.zoomTo("fit", t);
      }
    }, {
      key: "zoomToCover",
      value: function zoomToCover(t) {
        this.zoomTo("cover", t);
      }
    }, {
      key: "zoomToFull",
      value: function zoomToFull(t) {
        this.zoomTo("full", t);
      }
    }, {
      key: "zoomToMax",
      value: function zoomToMax(t) {
        this.zoomTo("max", t);
      }
    }, {
      key: "toggleZoom",
      value: function toggleZoom(t) {
        this.zoomTo(this.getNextScale("toggleZoom"), t);
      }
    }, {
      key: "toggleMax",
      value: function toggleMax(t) {
        this.zoomTo(this.getNextScale("toggleMax"), t);
      }
    }, {
      key: "toggleCover",
      value: function toggleCover(t) {
        this.zoomTo(this.getNextScale("toggleCover"), t);
      }
    }, {
      key: "iterateZoom",
      value: function iterateZoom(t) {
        this.zoomTo("next", t);
      }
    }, {
      key: "zoomTo",
      value: function zoomTo() {
        var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 1;
        var _ref7 = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {},
          _ref7$friction = _ref7.friction,
          e = _ref7$friction === void 0 ? "auto" : _ref7$friction,
          _ref7$originX = _ref7.originX,
          i = _ref7$originX === void 0 ? "auto" : _ref7$originX,
          _ref7$originY = _ref7.originY,
          n = _ref7$originY === void 0 ? "auto" : _ref7$originY,
          s = _ref7.event;
        if (this.isContentLoading || this.state === v.Destroy) return;
        var o = this.targetScale,
          a = this.fullScale,
          r = this.maxScale,
          l = this.coverScale;
        if (this.stop(), this.panMode === A && (s = this.pmme || s), s || "auto" === i || "auto" === n) {
          var _t22 = this.content.getBoundingClientRect(),
            _e13 = this.container.getBoundingClientRect(),
            _o = s ? s.clientX : _e13.left + .5 * _e13.width,
            _a = s ? s.clientY : _e13.top + .5 * _e13.height;
          i = _o - _t22.left - .5 * _t22.width, n = _a - _t22.top - .5 * _t22.height;
        }
        var c = 1;
        "number" == typeof t ? c = t : "full" === t ? c = a : "cover" === t ? c = l : "max" === t ? c = r : "fit" === t ? c = 1 : "next" === t && (c = this.getNextScale("iterateZoom")), c = c / o || 1, e = "auto" === e ? c > 1 ? .15 : .25 : e, this.applyChange({
          scale: c,
          originX: i,
          originY: n,
          friction: e
        }), s && this.panMode === A && this.panWithMouse(s, e);
      }
    }, {
      key: "rotateCCW",
      value: function rotateCCW() {
        this.applyChange({
          angle: -90
        });
      }
    }, {
      key: "rotateCW",
      value: function rotateCW() {
        this.applyChange({
          angle: 90
        });
      }
    }, {
      key: "flipX",
      value: function flipX() {
        this.applyChange({
          flipX: !0
        });
      }
    }, {
      key: "flipY",
      value: function flipY() {
        this.applyChange({
          flipY: !0
        });
      }
    }, {
      key: "fitX",
      value: function fitX() {
        this.stop("target");
        var t = this.containerRect,
          e = this.contentRect,
          i = this.target;
        this.applyChange({
          panX: .5 * t.width - (e.left + .5 * e.fitWidth) - i.e,
          panY: .5 * t.height - (e.top + .5 * e.fitHeight) - i.f,
          scale: t.width / e.fitWidth / this.targetScale,
          originX: 0,
          originY: 0,
          ignoreBounds: !0
        });
      }
    }, {
      key: "fitY",
      value: function fitY() {
        this.stop("target");
        var t = this.containerRect,
          e = this.contentRect,
          i = this.target;
        this.applyChange({
          panX: .5 * t.width - (e.left + .5 * e.fitWidth) - i.e,
          panY: .5 * t.innerHeight - (e.top + .5 * e.fitHeight) - i.f,
          scale: t.height / e.fitHeight / this.targetScale,
          originX: 0,
          originY: 0,
          ignoreBounds: !0
        });
      }
    }, {
      key: "toggleFS",
      value: function toggleFS() {
        var t = this.container,
          e = this.cn("inFullscreen"),
          i = this.cn("htmlHasFullscreen");
        t.classList.toggle(e);
        var n = t.classList.contains(e);
        n ? (document.documentElement.classList.add(i), document.addEventListener("keydown", this.onKeydown, !0)) : (document.documentElement.classList.remove(i), document.removeEventListener("keydown", this.onKeydown, !0)), this.updateMetrics(), this.emit(n ? "enterFS" : "exitFS");
      }
    }, {
      key: "getMatrix",
      value: function getMatrix() {
        var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : this.current;
        var e = t.a,
          i = t.b,
          n = t.c,
          s = t.d,
          o = t.e,
          a = t.f;
        return new DOMMatrix([e, i, n, s, o, a]);
      }
    }, {
      key: "reset",
      value: function reset(t) {
        if (this.state !== v.Init && this.state !== v.Destroy) {
          this.stop("current");
          for (var _i25 = 0, _b10 = b; _i25 < _b10.length; _i25++) {
            var _t23 = _b10[_i25];
            this.target[_t23] = T[_t23];
          }
          this.target.a = this.minScale, this.target.d = this.minScale, this.clampTargetBounds(), this.isResting || (this.friction = void 0 === t ? this.option("friction") : t, this.state = v.Panning, this.requestTick());
        }
      }
    }, {
      key: "destroy",
      value: function destroy() {
        this.stop(), this.state = v.Destroy, this.detachEvents(), this.detachObserver();
        var t = this.container,
          e = this.content,
          i = this.option("classes") || {};
        for (var _i26 = 0, _Object$values = Object.values(i); _i26 < _Object$values.length; _i26++) {
          var _e14 = _Object$values[_i26];
          t.classList.remove(_e14 + "");
        }
        e && (e.removeEventListener("load", this.onLoad), e.removeEventListener("error", this.onError)), this.detachPlugins();
      }
    }]);
    return I;
  }(m);
  Object.defineProperty(I, "defaults", {
    enumerable: !0,
    configurable: !0,
    writable: !0,
    value: w
  }), Object.defineProperty(I, "Plugins", {
    enumerable: !0,
    configurable: !0,
    writable: !0,
    value: {}
  });
  var D = function D(t, e) {
      var i = !0;
      return function () {
        i && (i = !1, t.apply(void 0, arguments), setTimeout(function () {
          i = !0;
        }, e));
      };
    },
    F = function F(t, e) {
      var i = [];
      return t.childNodes.forEach(function (t) {
        t.nodeType !== Node.ELEMENT_NODE || e && !t.matches(e) || i.push(t);
      }), i;
    },
    j = {
      viewport: null,
      track: null,
      enabled: !0,
      slides: [],
      axis: "x",
      transition: "fade",
      preload: 1,
      slidesPerPage: "auto",
      initialPage: 0,
      friction: .12,
      Panzoom: {
        decelFriction: .12
      },
      center: !0,
      infinite: !0,
      fill: !0,
      dragFree: !1,
      adaptiveHeight: !1,
      direction: "ltr",
      classes: {
        container: "f-carousel",
        viewport: "f-carousel__viewport",
        track: "f-carousel__track",
        slide: "f-carousel__slide",
        isLTR: "is-ltr",
        isRTL: "is-rtl",
        isHorizontal: "is-horizontal",
        isVertical: "is-vertical",
        inTransition: "in-transition",
        isSelected: "is-selected"
      },
      l10n: {
        NEXT: "Next slide",
        PREV: "Previous slide",
        GOTO: "Go to slide #%d"
      }
    };
  var B;
  !function (t) {
    t[t.Init = 0] = "Init", t[t.Ready = 1] = "Ready", t[t.Destroy = 2] = "Destroy";
  }(B || (B = {}));
  var H = function H(t) {
      if ("string" == typeof t || t instanceof HTMLElement) t = {
        html: t
      };else {
        var _e15 = t.thumb;
        void 0 !== _e15 && ("string" == typeof _e15 && (t.thumbSrc = _e15), _e15 instanceof HTMLImageElement && (t.thumbEl = _e15, t.thumbElSrc = _e15.src, t.thumbSrc = _e15.src), delete t.thumb);
      }
      return Object.assign({
        html: "",
        el: null,
        isDom: !1,
        "class": "",
        customClass: "",
        index: -1,
        dim: 0,
        gap: 0,
        pos: 0,
        transition: !1
      }, t);
    },
    N = function N() {
      var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
      return Object.assign({
        index: -1,
        slides: [],
        dim: 0,
        pos: -1
      }, t);
    };
  var _ = /*#__PURE__*/function (_g4) {
    _inherits(_, _g4);
    var _super7 = _createSuper(_);
    function _(t, e) {
      var _this24;
      _classCallCheck(this, _);
      _this24 = _super7.call(this, e), Object.defineProperty(_assertThisInitialized(_this24), "instance", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: t
      });
      return _this24;
    }
    _createClass(_, [{
      key: "attach",
      value: function attach() {}
    }, {
      key: "detach",
      value: function detach() {}
    }]);
    return _;
  }(g);
  var $ = {
    classes: {
      list: "f-carousel__dots",
      isDynamic: "is-dynamic",
      hasDots: "has-dots",
      dot: "f-carousel__dot",
      isBeforePrev: "is-before-prev",
      isPrev: "is-prev",
      isCurrent: "is-current",
      isNext: "is-next",
      isAfterNext: "is-after-next"
    },
    dotTpl: '<button type="button" data-carousel-page="%i" aria-label="{{GOTO}}"><span class="f-carousel__dot" aria-hidden="true"></span></button>',
    dynamicFrom: 11,
    maxCount: 1 / 0,
    minCount: 2
  };
  var W = /*#__PURE__*/function (_ref8) {
    _inherits(W, _ref8);
    var _super8 = _createSuper(W);
    function W() {
      var _this25;
      _classCallCheck(this, W);
      _this25 = _super8.apply(this, arguments), Object.defineProperty(_assertThisInitialized(_this25), "isDynamic", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: !1
      }), Object.defineProperty(_assertThisInitialized(_this25), "list", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      });
      return _this25;
    }
    _createClass(W, [{
      key: "onRefresh",
      value: function onRefresh() {
        this.refresh();
      }
    }, {
      key: "build",
      value: function build() {
        var t = this.list;
        if (!t) {
          t = document.createElement("ul"), C(t, this.cn("list")), t.setAttribute("role", "tablist");
          var _e16 = this.instance.container;
          _e16.appendChild(t), C(_e16, this.cn("hasDots")), this.list = t;
        }
        return t;
      }
    }, {
      key: "refresh",
      value: function refresh() {
        var t;
        var e = this.instance.pages.length,
          i = Math.min(2, this.option("minCount")),
          n = Math.max(2e3, this.option("maxCount")),
          s = this.option("dynamicFrom");
        if (e < i || e > n) return void this.cleanup();
        var o = "number" == typeof s && e > 5 && e >= s,
          r = !this.list || this.isDynamic !== o || this.list.children.length !== e;
        r && this.cleanup();
        var l = this.build();
        if (a(l, this.cn("isDynamic"), !!o), r) for (var _t24 = 0; _t24 < e; _t24++) l.append(this.createItem(_t24));
        var c,
          h = 0;
        for (var _i27 = 0, _arr2 = _toConsumableArray(l.children); _i27 < _arr2.length; _i27++) {
          var _e17 = _arr2[_i27];
          var _i28 = h === this.instance.page;
          _i28 && (c = _e17), a(_e17, this.cn("isCurrent"), _i28), null === (t = _e17.children[0]) || void 0 === t || t.setAttribute("aria-selected", _i28 ? "true" : "false");
          for (var _i29 = 0, _arr3 = ["isBeforePrev", "isPrev", "isNext", "isAfterNext"]; _i29 < _arr3.length; _i29++) {
            var _t25 = _arr3[_i29];
            P(_e17, this.cn(_t25));
          }
          h++;
        }
        if (c = c || l.firstChild, o && c) {
          var _t26 = c.previousElementSibling,
            _e18 = _t26 && _t26.previousElementSibling;
          C(_t26, this.cn("isPrev")), C(_e18, this.cn("isBeforePrev"));
          var _i30 = c.nextElementSibling,
            _n5 = _i30 && _i30.nextElementSibling;
          C(_i30, this.cn("isNext")), C(_n5, this.cn("isAfterNext"));
        }
        this.isDynamic = o;
      }
    }, {
      key: "createItem",
      value: function createItem() {
        var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
        var e;
        var i = document.createElement("li");
        i.setAttribute("role", "presentation");
        var n = s(this.instance.localize(this.option("dotTpl"), [["%d", t + 1]]).replace(/\%i/g, t + ""));
        return i.appendChild(n), null === (e = i.children[0]) || void 0 === e || e.setAttribute("role", "tab"), i;
      }
    }, {
      key: "cleanup",
      value: function cleanup() {
        this.list && (this.list.remove(), this.list = null), this.isDynamic = !1, P(this.instance.container, this.cn("hasDots"));
      }
    }, {
      key: "attach",
      value: function attach() {
        this.instance.on(["refresh", "change"], this.onRefresh);
      }
    }, {
      key: "detach",
      value: function detach() {
        this.instance.off(["refresh", "change"], this.onRefresh), this.cleanup();
      }
    }]);
    return W;
  }(_);
  Object.defineProperty(W, "defaults", {
    enumerable: !0,
    configurable: !0,
    writable: !0,
    value: $
  });
  var X = "disabled",
    q = "next",
    Y = "prev";
  var V = /*#__PURE__*/function (_ref9) {
    _inherits(V, _ref9);
    var _super9 = _createSuper(V);
    function V() {
      var _this26;
      _classCallCheck(this, V);
      _this26 = _super9.apply(this, arguments), Object.defineProperty(_assertThisInitialized(_this26), "container", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this26), "prev", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this26), "next", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this26), "isDom", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: !1
      });
      return _this26;
    }
    _createClass(V, [{
      key: "onRefresh",
      value: function onRefresh() {
        var t = this.instance,
          e = t.pages.length,
          i = t.page;
        if (e < 2) return void this.cleanup();
        this.build();
        var n = this.prev,
          s = this.next;
        n && s && (n.removeAttribute(X), s.removeAttribute(X), t.isInfinite || (i <= 0 && n.setAttribute(X, ""), i >= e - 1 && s.setAttribute(X, "")));
      }
    }, {
      key: "addBtn",
      value: function addBtn(t) {
        var e;
        var i = this.instance,
          n = document.createElement("button");
        n.setAttribute("tabindex", "0"), n.setAttribute("title", i.localize("{{".concat(t.toUpperCase(), "}}"))), C(n, this.cn("button") + " " + this.cn(t === q ? "isNext" : "isPrev"));
        var s = i.isRTL ? t === q ? Y : q : t;
        var o;
        return n.innerHTML = i.localize(this.option("".concat(s, "Tpl"))), n.dataset["carousel".concat((o = t, o ? o.match("^[a-z]") ? o.charAt(0).toUpperCase() + o.substring(1) : o : ""))] = "true", null === (e = this.container) || void 0 === e || e.appendChild(n), n;
      }
    }, {
      key: "build",
      value: function build() {
        var t = this.instance.container,
          e = this.cn("container");
        var i = this.container,
          n = this.prev,
          s = this.next;
        i || (i = t.querySelector("." + e), this.isDom = !!i), i || (i = document.createElement("div"), C(i, e), t.appendChild(i)), this.container = i, s || (s = i.querySelector("[data-carousel-next]")), s || (s = this.addBtn(q)), this.next = s, n || (n = i.querySelector("[data-carousel-prev]")), n || (n = this.addBtn(Y)), this.prev = n;
      }
    }, {
      key: "cleanup",
      value: function cleanup() {
        this.isDom || (this.prev && this.prev.remove(), this.next && this.next.remove(), this.container && this.container.remove()), this.prev = null, this.next = null, this.container = null, this.isDom = !1;
      }
    }, {
      key: "attach",
      value: function attach() {
        this.instance.on(["refresh", "change"], this.onRefresh);
      }
    }, {
      key: "detach",
      value: function detach() {
        this.instance.off(["refresh", "change"], this.onRefresh), this.cleanup();
      }
    }]);
    return V;
  }(_);
  Object.defineProperty(V, "defaults", {
    enumerable: !0,
    configurable: !0,
    writable: !0,
    value: {
      classes: {
        container: "f-carousel__nav",
        button: "f-button",
        isNext: "is-next",
        isPrev: "is-prev"
      },
      nextTpl: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" tabindex="-1"><path d="M9 3l9 9-9 9"/></svg>',
      prevTpl: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" tabindex="-1"><path d="M15 3l-9 9 9 9"/></svg>'
    }
  });
  var Z = /*#__PURE__*/function (_ref10) {
    _inherits(Z, _ref10);
    var _super10 = _createSuper(Z);
    function Z() {
      var _this27;
      _classCallCheck(this, Z);
      _this27 = _super10.apply(this, arguments), Object.defineProperty(_assertThisInitialized(_this27), "selectedIndex", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this27), "target", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this27), "nav", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      });
      return _this27;
    }
    _createClass(Z, [{
      key: "addAsTargetFor",
      value: function addAsTargetFor(t) {
        this.target = this.instance, this.nav = t, this.attachEvents();
      }
    }, {
      key: "addAsNavFor",
      value: function addAsNavFor(t) {
        this.nav = this.instance, this.target = t, this.attachEvents();
      }
    }, {
      key: "attachEvents",
      value: function attachEvents() {
        var t = this.nav,
          e = this.target;
        t && e && (t.options.initialSlide = e.options.initialPage, t.state === B.Ready ? this.onNavReady(t) : t.on("ready", this.onNavReady), e.state === B.Ready ? this.onTargetReady(e) : e.on("ready", this.onTargetReady));
      }
    }, {
      key: "onNavReady",
      value: function onNavReady(t) {
        t.on("createSlide", this.onNavCreateSlide), t.on("Panzoom.click", this.onNavClick), t.on("Panzoom.touchEnd", this.onNavTouch), this.onTargetChange();
      }
    }, {
      key: "onTargetReady",
      value: function onTargetReady(t) {
        t.on("change", this.onTargetChange), t.on("Panzoom.refresh", this.onTargetChange), this.onTargetChange();
      }
    }, {
      key: "onNavClick",
      value: function onNavClick(t, e, i) {
        this.onNavTouch(t, t.panzoom, i);
      }
    }, {
      key: "onNavTouch",
      value: function onNavTouch(t, e, i) {
        var n, s;
        if (Math.abs(e.dragOffset.x) > 3 || Math.abs(e.dragOffset.y) > 3) return;
        var o = i.target,
          a = this.nav,
          r = this.target;
        if (!a || !r || !o) return;
        var l = o.closest("[data-index]");
        if (i.stopPropagation(), i.preventDefault(), !l) return;
        var c = parseInt(l.dataset.index || "", 10) || 0,
          h = r.getPageForSlide(c),
          d = a.getPageForSlide(c);
        a.slideTo(d), r.slideTo(h, {
          friction: (null === (s = null === (n = this.nav) || void 0 === n ? void 0 : n.plugins) || void 0 === s ? void 0 : s.Sync.option("friction")) || 0
        }), this.markSelectedSlide(c);
      }
    }, {
      key: "onNavCreateSlide",
      value: function onNavCreateSlide(t, e) {
        e.index === this.selectedIndex && this.markSelectedSlide(e.index);
      }
    }, {
      key: "onTargetChange",
      value: function onTargetChange() {
        var t, e;
        var i = this.target,
          n = this.nav;
        if (!i || !n) return;
        if (n.state !== B.Ready || i.state !== B.Ready) return;
        var s = null === (e = null === (t = i.pages[i.page]) || void 0 === t ? void 0 : t.slides[0]) || void 0 === e ? void 0 : e.index,
          o = n.getPageForSlide(s);
        this.markSelectedSlide(s), n.slideTo(o, null === n.prevPage && null === i.prevPage ? {
          friction: 0
        } : void 0);
      }
    }, {
      key: "markSelectedSlide",
      value: function markSelectedSlide(t) {
        var e = this.nav;
        e && e.state === B.Ready && (this.selectedIndex = t, _toConsumableArray(e.slides).map(function (e) {
          e.el && e.el.classList[e.index === t ? "add" : "remove"]("is-nav-selected");
        }));
      }
    }, {
      key: "attach",
      value: function attach() {
        var t = this;
        var e = t.options.target,
          i = t.options.nav;
        e ? t.addAsNavFor(e) : i && t.addAsTargetFor(i);
      }
    }, {
      key: "detach",
      value: function detach() {
        var t = this,
          e = t.nav,
          i = t.target;
        e && (e.off("ready", t.onNavReady), e.off("createSlide", t.onNavCreateSlide), e.off("Panzoom.click", t.onNavClick), e.off("Panzoom.touchEnd", t.onNavTouch)), t.nav = null, i && (i.off("ready", t.onTargetReady), i.off("refresh", t.onTargetChange), i.off("change", t.onTargetChange)), t.target = null;
      }
    }]);
    return Z;
  }(_);
  Object.defineProperty(Z, "defaults", {
    enumerable: !0,
    configurable: !0,
    writable: !0,
    value: {
      friction: .35
    }
  });
  var U = {
      Navigation: V,
      Dots: W,
      Sync: Z
    },
    G = "animationend",
    K = "isSelected",
    J = "slide";
  var Q = /*#__PURE__*/function (_m3) {
    _inherits(Q, _m3);
    var _super11 = _createSuper(Q);
    function Q(t) {
      var _this28;
      var e = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
      var i = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
      _classCallCheck(this, Q);
      if (_this28 = _super11.call(this), Object.defineProperty(_assertThisInitialized(_this28), "bp", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: ""
      }), Object.defineProperty(_assertThisInitialized(_this28), "lp", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: 0
      }), Object.defineProperty(_assertThisInitialized(_this28), "userOptions", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: {}
      }), Object.defineProperty(_assertThisInitialized(_this28), "userPlugins", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: {}
      }), Object.defineProperty(_assertThisInitialized(_this28), "state", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: B.Init
      }), Object.defineProperty(_assertThisInitialized(_this28), "page", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: 0
      }), Object.defineProperty(_assertThisInitialized(_this28), "prevPage", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this28), "container", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: void 0
      }), Object.defineProperty(_assertThisInitialized(_this28), "viewport", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this28), "track", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this28), "slides", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: []
      }), Object.defineProperty(_assertThisInitialized(_this28), "pages", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: []
      }), Object.defineProperty(_assertThisInitialized(_this28), "panzoom", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this28), "inTransition", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: new Set()
      }), Object.defineProperty(_assertThisInitialized(_this28), "contentDim", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: 0
      }), Object.defineProperty(_assertThisInitialized(_this28), "viewportDim", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: 0
      }), "string" == typeof t && (t = document.querySelector(t)), !t || !S(t)) throw new Error("No Element found");
      _this28.container = t, _this28.slideNext = D(_this28.slideNext.bind(_assertThisInitialized(_this28)), 150), _this28.slidePrev = D(_this28.slidePrev.bind(_assertThisInitialized(_this28)), 150), _this28.userOptions = e, _this28.userPlugins = i, queueMicrotask(function () {
        _this28.processOptions();
      });
      return _possibleConstructorReturn(_this28);
    }
    _createClass(Q, [{
      key: "axis",
      get: function get() {
        return this.isHorizontal ? "e" : "f";
      }
    }, {
      key: "isEnabled",
      get: function get() {
        return this.state === B.Ready;
      }
    }, {
      key: "isInfinite",
      get: function get() {
        var t = !1;
        var e = this.contentDim,
          i = this.viewportDim,
          n = this.pages,
          s = this.slides,
          o = s[0];
        return n.length >= 2 && o && e + o.dim >= i && (t = this.option("infinite")), t;
      }
    }, {
      key: "isRTL",
      get: function get() {
        return "rtl" === this.option("direction");
      }
    }, {
      key: "isHorizontal",
      get: function get() {
        return "x" === this.option("axis");
      }
    }, {
      key: "processOptions",
      value: function processOptions() {
        var _this29 = this;
        var t, e;
        var i = p({}, Q.defaults, this.userOptions);
        var n = "";
        var s = i.breakpoints;
        if (s && u(s)) for (var _i31 = 0, _Object$entries3 = Object.entries(s); _i31 < _Object$entries3.length; _i31++) {
          var _Object$entries3$_i = _slicedToArray(_Object$entries3[_i31], 2),
            _t27 = _Object$entries3$_i[0],
            _e19 = _Object$entries3$_i[1];
          window.matchMedia(_t27).matches && u(_e19) && (n += _t27, p(i, _e19));
        }
        n === this.bp && this.state !== B.Init || (this.bp = n, this.state === B.Ready && (i.initialSlide = (null === (e = null === (t = this.pages[this.page]) || void 0 === t ? void 0 : t.slides[0]) || void 0 === e ? void 0 : e.index) || 0), this.state !== B.Init && this.destroy(), _get(_getPrototypeOf(Q.prototype), "setOptions", this).call(this, i), !1 === this.option("enabled") ? this.attachEvents() : setTimeout(function () {
          _this29.init();
        }, 0));
      }
    }, {
      key: "init",
      value: function init() {
        this.state = B.Init, this.emit("init"), this.attachPlugins(Object.assign(Object.assign({}, Q.Plugins), this.userPlugins)), this.emit("attachPlugins"), this.initLayout(), this.initSlides(), this.updateMetrics(), this.setInitialPosition(), this.initPanzoom(), this.attachEvents(), this.state = B.Ready, this.emit("ready");
      }
    }, {
      key: "initLayout",
      value: function initLayout() {
        var _i32, _n6;
        var t = this.container,
          e = this.option("classes");
        C(t, this.cn("container")), a(t, e.isLTR, !this.isRTL), a(t, e.isRTL, this.isRTL), a(t, e.isVertical, !this.isHorizontal), a(t, e.isHorizontal, this.isHorizontal);
        var i = this.option("viewport") || t.querySelector(".".concat(e.viewport));
        i || (i = document.createElement("div"), C(i, e.viewport), (_i32 = i).append.apply(_i32, _toConsumableArray(F(t, ".".concat(e.slide)))), t.prepend(i)), i.addEventListener("scroll", this.onScroll);
        var n = this.option("track") || t.querySelector(".".concat(e.track));
        n || (n = document.createElement("div"), C(n, e.track), (_n6 = n).append.apply(_n6, _toConsumableArray(Array.from(i.childNodes)))), n.setAttribute("aria-live", "polite"), i.contains(n) || i.prepend(n), this.viewport = i, this.track = n, this.emit("initLayout");
      }
    }, {
      key: "initSlides",
      value: function initSlides() {
        var _this30 = this;
        var t = this.track;
        if (!t) return;
        var e = _toConsumableArray(this.slides),
          i = [];
        _toConsumableArray(F(t, ".".concat(this.cn(J)))).forEach(function (t) {
          if (S(t)) {
            var _e20 = H({
              el: t,
              isDom: !0,
              index: _this30.slides.length
            });
            i.push(_e20);
          }
        });
        for (var _i33 = 0, _arr4 = [].concat(_toConsumableArray(this.option("slides", []) || []), _toConsumableArray(e)); _i33 < _arr4.length; _i33++) {
          var _t28 = _arr4[_i33];
          i.push(H(_t28));
        }
        this.slides = i;
        for (var _t29 = 0; _t29 < this.slides.length; _t29++) this.slides[_t29].index = _t29;
        for (var _i35 = 0, _i34 = i; _i35 < _i34.length; _i35++) {
          var _t30 = _i34[_i35];
          this.emit("beforeInitSlide", _t30, _t30.index), this.emit("initSlide", _t30, _t30.index);
        }
        this.emit("initSlides");
      }
    }, {
      key: "setInitialPage",
      value: function setInitialPage() {
        var t = this.option("initialSlide");
        this.page = "number" == typeof t ? this.getPageForSlide(t) : parseInt(this.option("initialPage", 0) + "", 10) || 0;
      }
    }, {
      key: "setInitialPosition",
      value: function setInitialPosition() {
        var t = this.track,
          e = this.pages,
          i = this.isHorizontal;
        if (!t || !e.length) return;
        var n = this.page;
        e[n] || (this.page = n = 0);
        var s = (e[n].pos || 0) * (this.isRTL && i ? 1 : -1),
          o = i ? "".concat(s, "px") : "0",
          a = i ? "0" : "".concat(s, "px");
        t.style.transform = "translate3d(".concat(o, ", ").concat(a, ", 0) scale(1)"), this.option("adaptiveHeight") && this.setViewportHeight();
      }
    }, {
      key: "initPanzoom",
      value: function initPanzoom() {
        var _this31 = this;
        this.panzoom && (this.panzoom.destroy(), this.panzoom = null);
        var t = this.option("Panzoom") || {};
        this.panzoom = new I(this.viewport, p({}, {
          content: this.track,
          zoom: !1,
          panOnlyZoomed: !1,
          lockAxis: this.isHorizontal ? "x" : "y",
          infinite: this.isInfinite,
          click: !1,
          dblClick: !1,
          touch: function touch(t) {
            return !(_this31.pages.length < 2 && !t.options.infinite);
          },
          bounds: function bounds() {
            return _this31.getBounds();
          },
          maxVelocity: function maxVelocity(t) {
            return Math.abs(t.target[_this31.axis] - t.current[_this31.axis]) < 2 * _this31.viewportDim ? 100 : 0;
          }
        }, t)), this.panzoom.on("*", function (t, e) {
          for (var _len4 = arguments.length, i = new Array(_len4 > 2 ? _len4 - 2 : 0), _key4 = 2; _key4 < _len4; _key4++) {
            i[_key4 - 2] = arguments[_key4];
          }
          _this31.emit.apply(_this31, ["Panzoom.".concat(e), t].concat(i));
        }), this.panzoom.on("decel", this.onDecel), this.panzoom.on("refresh", this.onRefresh), this.panzoom.on("beforeTransform", this.onBeforeTransform), this.panzoom.on("endAnimation", this.onEndAnimation);
      }
    }, {
      key: "attachEvents",
      value: function attachEvents() {
        var t = this.container;
        t && (t.addEventListener("click", this.onClick, {
          passive: !1,
          capture: !1
        }), t.addEventListener("slideTo", this.onSlideTo)), window.addEventListener("resize", this.onResize);
      }
    }, {
      key: "createPages",
      value: function createPages() {
        var t = [];
        var e = this.contentDim,
          i = this.viewportDim;
        var n = this.option("slidesPerPage");
        n = ("auto" === n || e <= i) && !1 !== this.option("fill") ? 1 / 0 : parseFloat(n + "");
        var s = 0,
          o = 0,
          a = 0;
        var _iterator6 = _createForOfIteratorHelper(this.slides),
          _step6;
        try {
          for (_iterator6.s(); !(_step6 = _iterator6.n()).done;) {
            var _e21 = _step6.value;
            (!t.length || o + _e21.dim - i > .05 || a >= n) && (t.push(N()), s = t.length - 1, o = 0, a = 0), t[s].slides.push(_e21), o += _e21.dim + _e21.gap, a++;
          }
        } catch (err) {
          _iterator6.e(err);
        } finally {
          _iterator6.f();
        }
        return t;
      }
    }, {
      key: "processPages",
      value: function processPages() {
        var t = this.pages,
          i = this.contentDim,
          n = this.viewportDim,
          s = this.isInfinite,
          o = this.option("center"),
          a = this.option("fill"),
          r = a && o && i > n && !s;
        if (t.forEach(function (t, e) {
          var s;
          t.index = e, t.pos = (null === (s = t.slides[0]) || void 0 === s ? void 0 : s.pos) || 0, t.dim = 0;
          var _iterator7 = _createForOfIteratorHelper(t.slides.entries()),
            _step7;
          try {
            for (_iterator7.s(); !(_step7 = _iterator7.n()).done;) {
              var _step7$value = _slicedToArray(_step7.value, 2),
                _e22 = _step7$value[0],
                _i36 = _step7$value[1];
              t.dim += _i36.dim, _e22 < t.slides.length - 1 && (t.dim += _i36.gap);
            }
          } catch (err) {
            _iterator7.e(err);
          } finally {
            _iterator7.f();
          }
          r && t.pos + .5 * t.dim < .5 * n ? t.pos = 0 : r && t.pos + .5 * t.dim >= i - .5 * n ? t.pos = i - n : o && (t.pos += -.5 * (n - t.dim));
        }), t.forEach(function (t) {
          a && !s && i > n && (t.pos = Math.max(t.pos, 0), t.pos = Math.min(t.pos, i - n)), t.pos = e(t.pos, 1e3), t.dim = e(t.dim, 1e3), Math.abs(t.pos) <= .1 && (t.pos = 0);
        }), s) return t;
        var l = [];
        var c;
        return t.forEach(function (t) {
          var e = Object.assign({}, t);
          c && e.pos === c.pos ? (c.dim += e.dim, c.slides = [].concat(_toConsumableArray(c.slides), _toConsumableArray(e.slides))) : (e.index = l.length, c = e, l.push(e));
        }), l;
      }
    }, {
      key: "getPageFromIndex",
      value: function getPageFromIndex() {
        var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
        var e = this.pages.length;
        var i;
        return t = parseInt((t || 0).toString()) || 0, i = this.isInfinite ? (t % e + e) % e : Math.max(Math.min(t, e - 1), 0), i;
      }
    }, {
      key: "getSlideMetrics",
      value: function getSlideMetrics(t) {
        var i, n;
        var s = this.isHorizontal ? "width" : "height";
        var o = 0,
          a = 0,
          r = t.el;
        var l = !(!r || r.parentNode);
        if (r ? o = parseFloat(r.dataset[s] || "") || 0 : (r = document.createElement("div"), r.style.visibility = "hidden", (this.track || document.body).prepend(r)), C(r, this.cn(J) + " " + t["class"] + " " + t.customClass), o) r.style[s] = "".concat(o, "px"), r.style["width" === s ? "height" : "width"] = "";else {
          l && (this.track || document.body).prepend(r), o = r.getBoundingClientRect()[s] * Math.max(1, (null === (i = window.visualViewport) || void 0 === i ? void 0 : i.scale) || 1);
          var _t31 = r[this.isHorizontal ? "offsetWidth" : "offsetHeight"];
          _t31 - 1 > o && (o = _t31);
        }
        var c = getComputedStyle(r);
        return "content-box" === c.boxSizing && (this.isHorizontal ? (o += parseFloat(c.paddingLeft) || 0, o += parseFloat(c.paddingRight) || 0) : (o += parseFloat(c.paddingTop) || 0, o += parseFloat(c.paddingBottom) || 0)), a = parseFloat(c[this.isHorizontal ? "marginRight" : "marginBottom"]) || 0, l ? null === (n = r.parentElement) || void 0 === n || n.removeChild(r) : t.el || r.remove(), {
          dim: e(o, 1e3),
          gap: e(a, 1e3)
        };
      }
    }, {
      key: "getBounds",
      value: function getBounds() {
        var t = this.isInfinite,
          e = this.isRTL,
          i = this.isHorizontal,
          n = this.pages;
        var s = {
          min: 0,
          max: 0
        };
        if (t) s = {
          min: -1 / 0,
          max: 1 / 0
        };else if (n.length) {
          var _t32 = n[0].pos,
            _o2 = n[n.length - 1].pos;
          s = e && i ? {
            min: _t32,
            max: _o2
          } : {
            min: -1 * _o2,
            max: -1 * _t32
          };
        }
        return {
          x: i ? s : {
            min: 0,
            max: 0
          },
          y: i ? {
            min: 0,
            max: 0
          } : s
        };
      }
    }, {
      key: "repositionSlides",
      value: function repositionSlides() {
        var t,
          i = this.isHorizontal,
          n = this.isRTL,
          s = this.isInfinite,
          o = this.viewport,
          a = this.viewportDim,
          r = this.contentDim,
          l = this.page,
          c = this.pages,
          h = this.slides,
          d = this.panzoom,
          u = 0,
          p = 0,
          f = 0,
          g = 0;
        d ? g = -1 * d.current[this.axis] : c[l] && (g = c[l].pos || 0), t = i ? n ? "right" : "left" : "top", n && i && (g *= -1);
        var _iterator8 = _createForOfIteratorHelper(h),
          _step8;
        try {
          for (_iterator8.s(); !(_step8 = _iterator8.n()).done;) {
            var _i39 = _step8.value;
            var _n9 = _i39.el;
            _n9 ? ("top" === t ? (_n9.style.right = "", _n9.style.left = "") : _n9.style.top = "", _i39.index !== u ? _n9.style[t] = 0 === p ? "" : "".concat(e(p, 1e3), "px") : _n9.style[t] = "", f += _i39.dim + _i39.gap, u++) : p += _i39.dim + _i39.gap;
          }
        } catch (err) {
          _iterator8.e(err);
        } finally {
          _iterator8.f();
        }
        if (s && f && o) {
          var _n7 = getComputedStyle(o),
            _s5 = "padding",
            _l = i ? "Right" : "Bottom",
            _c2 = parseFloat(_n7[_s5 + (i ? "Left" : "Top")]);
          g -= _c2, a += _c2, a += parseFloat(_n7[_s5 + _l]);
          var _iterator9 = _createForOfIteratorHelper(h),
            _step9;
          try {
            for (_iterator9.s(); !(_step9 = _iterator9.n()).done;) {
              var _i37 = _step9.value;
              _i37.el && (e(_i37.pos) < e(a) && e(_i37.pos + _i37.dim + _i37.gap) < e(g) && e(g) > e(r - a) && (_i37.el.style[t] = "".concat(e(p + f, 1e3), "px")), e(_i37.pos + _i37.gap) >= e(r - a) && e(_i37.pos) > e(g + a) && e(g) < e(a) && (_i37.el.style[t] = "-".concat(e(f, 1e3), "px")));
            }
          } catch (err) {
            _iterator9.e(err);
          } finally {
            _iterator9.f();
          }
        }
        var m,
          v,
          b = _toConsumableArray(this.inTransition);
        if (b.length > 1 && (m = c[b[0]], v = c[b[1]]), m && v) {
          var _i38 = 0;
          var _iterator10 = _createForOfIteratorHelper(h),
            _step10;
          try {
            for (_iterator10.s(); !(_step10 = _iterator10.n()).done;) {
              var _n8 = _step10.value;
              _n8.el ? this.inTransition.has(_n8.index) && m.slides.indexOf(_n8) < 0 && (_n8.el.style[t] = "".concat(e(_i38 + (m.pos - v.pos), 1e3), "px")) : _i38 += _n8.dim + _n8.gap;
            }
          } catch (err) {
            _iterator10.e(err);
          } finally {
            _iterator10.f();
          }
        }
      }
    }, {
      key: "createSlideEl",
      value: function createSlideEl(t) {
        var e = this.track,
          i = this.slides;
        if (!e || !t) return;
        if (t.el && t.el.parentNode) return;
        var n = t.el || document.createElement("div");
        C(n, this.cn(J)), C(n, t["class"]), C(n, t.customClass);
        var s = t.html;
        s && (s instanceof HTMLElement ? n.appendChild(s) : n.innerHTML = t.html + "");
        var o = [];
        i.forEach(function (t, e) {
          t.el && o.push(e);
        });
        var a = t.index;
        var r = null;
        if (o.length) {
          r = i[o.reduce(function (t, e) {
            return Math.abs(e - a) < Math.abs(t - a) ? e : t;
          })];
        }
        var l = r && r.el && r.el.parentNode ? r.index < t.index ? r.el.nextSibling : r.el : null;
        e.insertBefore(n, e.contains(l) ? l : null), t.el = n, this.emit("createSlide", t);
      }
    }, {
      key: "removeSlideEl",
      value: function removeSlideEl(t) {
        var e = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : !1;
        var i = null == t ? void 0 : t.el;
        if (!i || !i.parentNode) return;
        var n = this.cn(K);
        if (i.classList.contains(n) && (P(i, n), this.emit("unselectSlide", t)), t.isDom && !e) return i.removeAttribute("aria-hidden"), i.removeAttribute("data-index"), void (i.style.left = "");
        this.emit("removeSlide", t);
        var s = new CustomEvent(G);
        i.dispatchEvent(s), t.el && (t.el.remove(), t.el = null);
      }
    }, {
      key: "transitionTo",
      value: function transitionTo() {
        var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
        var e = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : this.option("transition");
        var i, n, s, o;
        if (!e) return !1;
        var a = this.page,
          r = this.pages,
          l = this.panzoom;
        t = parseInt((t || 0).toString()) || 0;
        var c = this.getPageFromIndex(t);
        if (!l || !r[c] || r.length < 2 || Math.abs(((null === (n = null === (i = r[a]) || void 0 === i ? void 0 : i.slides[0]) || void 0 === n ? void 0 : n.dim) || 0) - this.viewportDim) > 1) return !1;
        var h = t > a ? 1 : -1,
          d = r[c].pos * (this.isRTL ? 1 : -1);
        if (a === c && Math.abs(d - l.target[this.axis]) < 1) return !1;
        this.clearTransitions();
        var u = l.isResting;
        C(this.container, this.cn("inTransition"));
        var p = (null === (s = r[a]) || void 0 === s ? void 0 : s.slides[0]) || null,
          f = (null === (o = r[c]) || void 0 === o ? void 0 : o.slides[0]) || null;
        this.inTransition.add(f.index), this.createSlideEl(f);
        var g = p.el,
          m = f.el;
        u || e === J || (e = "fadeFast", g = null);
        var v = this.isRTL ? "next" : "prev",
          b = this.isRTL ? "prev" : "next";
        return g && (this.inTransition.add(p.index), p.transition = e, g.addEventListener(G, this.onAnimationEnd), g.classList.add("f-".concat(e, "Out"), "to-".concat(h > 0 ? b : v))), m && (f.transition = e, m.addEventListener(G, this.onAnimationEnd), m.classList.add("f-".concat(e, "In"), "from-".concat(h > 0 ? v : b))), l.current[this.axis] = d, l.target[this.axis] = d, l.requestTick(), this.onChange(c), !0;
      }
    }, {
      key: "manageSlideVisiblity",
      value: function manageSlideVisiblity() {
        var t = new Set(),
          e = new Set(),
          i = this.getVisibleSlides(parseFloat(this.option("preload", 0) + "") || 0);
        var _iterator11 = _createForOfIteratorHelper(this.slides),
          _step11;
        try {
          for (_iterator11.s(); !(_step11 = _iterator11.n()).done;) {
            var _n10 = _step11.value;
            i.has(_n10) ? t.add(_n10) : e.add(_n10);
          }
        } catch (err) {
          _iterator11.e(err);
        } finally {
          _iterator11.f();
        }
        var _iterator12 = _createForOfIteratorHelper(this.inTransition),
          _step12;
        try {
          for (_iterator12.s(); !(_step12 = _iterator12.n()).done;) {
            var _e23 = _step12.value;
            t.add(this.slides[_e23]);
          }
        } catch (err) {
          _iterator12.e(err);
        } finally {
          _iterator12.f();
        }
        var _iterator13 = _createForOfIteratorHelper(t),
          _step13;
        try {
          for (_iterator13.s(); !(_step13 = _iterator13.n()).done;) {
            var _e24 = _step13.value;
            this.createSlideEl(_e24), this.lazyLoadSlide(_e24);
          }
        } catch (err) {
          _iterator13.e(err);
        } finally {
          _iterator13.f();
        }
        var _iterator14 = _createForOfIteratorHelper(e),
          _step14;
        try {
          for (_iterator14.s(); !(_step14 = _iterator14.n()).done;) {
            var _i40 = _step14.value;
            t.has(_i40) || this.removeSlideEl(_i40);
          }
        } catch (err) {
          _iterator14.e(err);
        } finally {
          _iterator14.f();
        }
        this.markSelectedSlides(), this.repositionSlides();
      }
    }, {
      key: "markSelectedSlides",
      value: function markSelectedSlides() {
        if (!this.pages[this.page] || !this.pages[this.page].slides) return;
        var t = "aria-hidden";
        var e = this.cn(K);
        if (e) {
          var _iterator15 = _createForOfIteratorHelper(this.slides),
            _step15;
          try {
            for (_iterator15.s(); !(_step15 = _iterator15.n()).done;) {
              var _i41 = _step15.value;
              var _n11 = _i41.el;
              _n11 && (_n11.dataset.index = "".concat(_i41.index), _n11.classList.contains("f-thumbs__slide") ? this.getVisibleSlides(0).has(_i41) ? _n11.removeAttribute(t) : _n11.setAttribute(t, "true") : this.pages[this.page].slides.includes(_i41) ? (_n11.classList.contains(e) || (C(_n11, e), this.emit("selectSlide", _i41)), _n11.removeAttribute(t)) : (_n11.classList.contains(e) && (P(_n11, e), this.emit("unselectSlide", _i41)), _n11.setAttribute(t, "true")));
            }
          } catch (err) {
            _iterator15.e(err);
          } finally {
            _iterator15.f();
          }
        }
      }
    }, {
      key: "flipInfiniteTrack",
      value: function flipInfiniteTrack() {
        var t = this.axis,
          e = this.isHorizontal,
          i = this.isInfinite,
          n = this.isRTL,
          s = this.viewportDim,
          o = this.contentDim,
          a = this.panzoom;
        if (!a || !i) return;
        var r = a.current[t],
          l = a.target[t] - r,
          c = 0,
          h = .5 * s;
        n && e ? (r < -h && (c = -1, r += o), r > o - h && (c = 1, r -= o)) : (r > h && (c = 1, r -= o), r < -o + h && (c = -1, r += o)), c && (a.current[t] = r, a.target[t] = r + l);
      }
    }, {
      key: "lazyLoadImg",
      value: function lazyLoadImg(t, e) {
        var _this32 = this;
        var i = this,
          n = "f-fadeIn",
          o = "is-preloading";
        var a = !1,
          r = null;
        var l = function l() {
          a || (a = !0, r && (r.remove(), r = null), P(e, o), e.complete && (C(e, n), setTimeout(function () {
            P(e, n);
          }, 350)), _this32.option("adaptiveHeight") && t.el && _this32.pages[_this32.page].slides.indexOf(t) > -1 && (i.updateMetrics(), i.setViewportHeight()), _this32.emit("load", t));
        };
        C(e, o), e.src = e.dataset.lazySrcset || e.dataset.lazySrc || "", delete e.dataset.lazySrc, delete e.dataset.lazySrcset, e.addEventListener("error", function () {
          l();
        }), e.addEventListener("load", function () {
          l();
        }), setTimeout(function () {
          var i = e.parentNode;
          i && t.el && (e.complete ? l() : a || (r = s(E), i.insertBefore(r, e)));
        }, 300);
      }
    }, {
      key: "lazyLoadSlide",
      value: function lazyLoadSlide(t) {
        var e = t && t.el;
        if (!e) return;
        var i = new Set();
        var n = Array.from(e.querySelectorAll("[data-lazy-src],[data-lazy-srcset]"));
        e.dataset.lazySrc && n.push(e), n.map(function (t) {
          t instanceof HTMLImageElement ? i.add(t) : t instanceof HTMLElement && t.dataset.lazySrc && (t.style.backgroundImage = "url('".concat(t.dataset.lazySrc, "')"), delete t.dataset.lazySrc);
        });
        var _iterator16 = _createForOfIteratorHelper(i),
          _step16;
        try {
          for (_iterator16.s(); !(_step16 = _iterator16.n()).done;) {
            var _e25 = _step16.value;
            this.lazyLoadImg(t, _e25);
          }
        } catch (err) {
          _iterator16.e(err);
        } finally {
          _iterator16.f();
        }
      }
    }, {
      key: "onAnimationEnd",
      value: function onAnimationEnd(t) {
        var e;
        var i = t.target,
          n = i ? parseInt(i.dataset.index || "", 10) || 0 : -1,
          s = this.slides[n],
          o = t.animationName;
        if (!i || !s || !o) return;
        var a = !!this.inTransition.has(n) && s.transition;
        a && o.substring(0, a.length + 2) === "f-".concat(a) && this.inTransition["delete"](n), this.inTransition.size || this.clearTransitions(), n === this.page && (null === (e = this.panzoom) || void 0 === e ? void 0 : e.isResting) && this.emit("settle");
      }
    }, {
      key: "onDecel",
      value: function onDecel(t) {
        var e = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
        var i = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 0;
        var n = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 0;
        var s = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : 0;
        if (this.option("dragFree")) return void this.setPageFromPosition();
        var o = this.isRTL,
          a = this.isHorizontal,
          r = this.axis,
          l = this.pages,
          c = l.length,
          h = Math.abs(Math.atan2(i, e) / (Math.PI / 180));
        var d = 0;
        if (d = h > 45 && h < 135 ? a ? 0 : i : a ? e : 0, !c) return;
        var u = this.page,
          p = o && a ? 1 : -1;
        var f = t.current[r] * p;
        var _this$getPageFromPosi = this.getPageFromPosition(f),
          g = _this$getPageFromPosi.pageIndex;
        Math.abs(d) > 5 ? (l[u].dim < document.documentElement["client" + (this.isHorizontal ? "Width" : "Height")] - 1 && (u = g), u = o && a ? d < 0 ? u - 1 : u + 1 : d < 0 ? u + 1 : u - 1) : u = 0 === n && 0 === s ? u : g, this.slideTo(u, {
          transition: !1,
          friction: t.option("decelFriction")
        });
      }
    }, {
      key: "onClick",
      value: function onClick(t) {
        var e = t.target,
          i = e && S(e) ? e.dataset : null;
        var n, s;
        i && (void 0 !== i.carouselPage ? (s = "slideTo", n = i.carouselPage) : void 0 !== i.carouselNext ? s = "slideNext" : void 0 !== i.carouselPrev && (s = "slidePrev")), s ? (t.preventDefault(), t.stopPropagation(), e && !e.hasAttribute("disabled") && this[s](n)) : this.emit("click", t);
      }
    }, {
      key: "onSlideTo",
      value: function onSlideTo(t) {
        var e = t.detail || 0;
        this.slideTo(this.getPageForSlide(e), {
          friction: 0
        });
      }
    }, {
      key: "onChange",
      value: function onChange(t) {
        var e = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
        var i = this.page;
        this.prevPage = i, this.page = t, this.option("adaptiveHeight") && this.setViewportHeight(), t !== i && (this.markSelectedSlides(), this.emit("change", t, i, e));
      }
    }, {
      key: "onRefresh",
      value: function onRefresh() {
        var t = this.contentDim,
          e = this.viewportDim;
        this.updateMetrics(), this.contentDim === t && this.viewportDim === e || this.slideTo(this.page, {
          friction: 0,
          transition: !1
        });
      }
    }, {
      key: "onScroll",
      value: function onScroll() {
        var t;
        null === (t = this.viewport) || void 0 === t || t.scroll(0, 0);
      }
    }, {
      key: "onResize",
      value: function onResize() {
        this.option("breakpoints") && this.processOptions();
      }
    }, {
      key: "onBeforeTransform",
      value: function onBeforeTransform(t) {
        this.lp !== t.current[this.axis] && (this.flipInfiniteTrack(), this.manageSlideVisiblity()), this.lp = t.current.e;
      }
    }, {
      key: "onEndAnimation",
      value: function onEndAnimation() {
        this.inTransition.size || this.emit("settle");
      }
    }, {
      key: "reInit",
      value: function reInit() {
        var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
        var e = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
        this.destroy(), this.state = B.Init, this.prevPage = null, this.userOptions = t || this.userOptions, this.userPlugins = e || this.userPlugins, this.processOptions();
      }
    }, {
      key: "slideTo",
      value: function slideTo() {
        var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
        var _ref11 = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {},
          _ref11$friction = _ref11.friction,
          e = _ref11$friction === void 0 ? this.option("friction") : _ref11$friction,
          _ref11$transition = _ref11.transition,
          i = _ref11$transition === void 0 ? this.option("transition") : _ref11$transition;
        if (this.state === B.Destroy) return;
        t = parseInt((t || 0).toString()) || 0;
        var n = this.getPageFromIndex(t),
          s = this.axis,
          o = this.isHorizontal,
          a = this.isRTL,
          r = this.pages,
          l = this.panzoom,
          c = r.length,
          h = a && o ? 1 : -1;
        if (!l || !c) return;
        if (this.page !== n) {
          var _e26 = new Event("beforeChange", {
            bubbles: !0,
            cancelable: !0
          });
          if (this.emit("beforeChange", _e26, t), _e26.defaultPrevented) return;
        }
        if (this.transitionTo(t, i)) return;
        var d = r[n].pos;
        if (this.isInfinite) {
          var _e27 = this.contentDim,
            _i42 = l.target[s] * h;
          if (2 === c) d += _e27 * Math.floor(parseFloat(t + "") / 2);else {
            d = [d, d - _e27, d + _e27].reduce(function (t, e) {
              return Math.abs(e - _i42) < Math.abs(t - _i42) ? e : t;
            });
          }
        }
        d *= h, Math.abs(l.target[s] - d) < 1 || (l.panTo({
          x: o ? d : 0,
          y: o ? 0 : d,
          friction: e
        }), this.onChange(n));
      }
    }, {
      key: "slideToClosest",
      value: function slideToClosest(t) {
        if (this.panzoom) {
          var _this$getPageFromPosi2 = this.getPageFromPosition(),
            _e28 = _this$getPageFromPosi2.pageIndex;
          this.slideTo(_e28, t);
        }
      }
    }, {
      key: "slideNext",
      value: function slideNext() {
        this.slideTo(this.page + 1);
      }
    }, {
      key: "slidePrev",
      value: function slidePrev() {
        this.slideTo(this.page - 1);
      }
    }, {
      key: "clearTransitions",
      value: function clearTransitions() {
        this.inTransition.clear(), P(this.container, this.cn("inTransition"));
        var t = ["to-prev", "to-next", "from-prev", "from-next"];
        var _iterator17 = _createForOfIteratorHelper(this.slides),
          _step17;
        try {
          for (_iterator17.s(); !(_step17 = _iterator17.n()).done;) {
            var _e29 = _step17.value;
            var _i43 = _e29.el;
            if (_i43) {
              var _i43$classList;
              _i43.removeEventListener(G, this.onAnimationEnd), (_i43$classList = _i43.classList).remove.apply(_i43$classList, t);
              var _n12 = _e29.transition;
              _n12 && _i43.classList.remove("f-".concat(_n12, "Out"), "f-".concat(_n12, "In"));
            }
          }
        } catch (err) {
          _iterator17.e(err);
        } finally {
          _iterator17.f();
        }
        this.manageSlideVisiblity();
      }
    }, {
      key: "addSlide",
      value: function addSlide(t, e) {
        var _this$slides;
        var i, n, s, o;
        var a = this.panzoom,
          r = (null === (i = this.pages[this.page]) || void 0 === i ? void 0 : i.pos) || 0,
          l = (null === (n = this.pages[this.page]) || void 0 === n ? void 0 : n.dim) || 0,
          c = this.contentDim < this.viewportDim;
        var h = Array.isArray(e) ? e : [e];
        var d = [];
        var _iterator18 = _createForOfIteratorHelper(h),
          _step18;
        try {
          for (_iterator18.s(); !(_step18 = _iterator18.n()).done;) {
            var _t36 = _step18.value;
            d.push(H(_t36));
          }
        } catch (err) {
          _iterator18.e(err);
        } finally {
          _iterator18.f();
        }
        (_this$slides = this.slides).splice.apply(_this$slides, [t, 0].concat(d));
        for (var _t33 = 0; _t33 < this.slides.length; _t33++) this.slides[_t33].index = _t33;
        for (var _i44 = 0, _d3 = d; _i44 < _d3.length; _i44++) {
          var _t34 = _d3[_i44];
          this.emit("beforeInitSlide", _t34, _t34.index);
        }
        if (this.page >= t && (this.page += d.length), this.updateMetrics(), a) {
          var _e30 = (null === (s = this.pages[this.page]) || void 0 === s ? void 0 : s.pos) || 0,
            _i45 = (null === (o = this.pages[this.page]) || void 0 === o ? void 0 : o.dim) || 0,
            _n13 = this.pages.length || 1,
            _h2 = this.isRTL ? l - _i45 : _i45 - l,
            _d4 = this.isRTL ? r - _e30 : _e30 - r;
          c && 1 === _n13 ? (t <= this.page && (a.current[this.axis] -= _h2, a.target[this.axis] -= _h2), a.panTo(_defineProperty({}, this.isHorizontal ? "x" : "y", -1 * _e30))) : _d4 && t <= this.page && (a.target[this.axis] -= _d4, a.current[this.axis] -= _d4, a.requestTick());
        }
        for (var _i46 = 0, _d5 = d; _i46 < _d5.length; _i46++) {
          var _t35 = _d5[_i46];
          this.emit("initSlide", _t35, _t35.index);
        }
      }
    }, {
      key: "prependSlide",
      value: function prependSlide(t) {
        this.addSlide(0, t);
      }
    }, {
      key: "appendSlide",
      value: function appendSlide(t) {
        this.addSlide(this.slides.length, t);
      }
    }, {
      key: "removeSlide",
      value: function removeSlide(t) {
        var e = this.slides.length;
        t = (t % e + e) % e;
        var i = this.slides[t];
        if (i) {
          this.removeSlideEl(i, !0), this.slides.splice(t, 1);
          for (var _t37 = 0; _t37 < this.slides.length; _t37++) this.slides[_t37].index = _t37;
          this.updateMetrics(), this.slideTo(this.page, {
            friction: 0,
            transition: !1
          }), this.emit("destroySlide", i);
        }
      }
    }, {
      key: "updateMetrics",
      value: function updateMetrics() {
        var t = this.panzoom,
          i = this.viewport,
          n = this.track,
          s = this.slides,
          o = this.isHorizontal,
          a = this.isInfinite;
        if (!n) return;
        var r = o ? "width" : "height",
          l = o ? "offsetWidth" : "offsetHeight";
        if (i) {
          var _t38 = Math.max(i[l], e(i.getBoundingClientRect()[r], 1e3)),
            _n14 = getComputedStyle(i),
            _s6 = "padding",
            _a2 = o ? "Right" : "Bottom";
          _t38 -= parseFloat(_n14[_s6 + (o ? "Left" : "Top")]) + parseFloat(_n14[_s6 + _a2]), this.viewportDim = _t38;
        }
        var c,
          h = 0;
        var _iterator19 = _createForOfIteratorHelper(s.entries()),
          _step19;
        try {
          for (_iterator19.s(); !(_step19 = _iterator19.n()).done;) {
            var _this$getSlideMetrics;
            var _step19$value = _slicedToArray(_step19.value, 2),
              _t39 = _step19$value[0],
              _i47 = _step19$value[1];
            var _n15 = 0,
              _o3 = 0;
            !_i47.el && c ? (_n15 = c.dim, _o3 = c.gap) : ((_this$getSlideMetrics = this.getSlideMetrics(_i47), _n15 = _this$getSlideMetrics.dim, _o3 = _this$getSlideMetrics.gap), c = _i47), _n15 = e(_n15, 1e3), _o3 = e(_o3, 1e3), _i47.dim = _n15, _i47.gap = _o3, _i47.pos = h, h += _n15, (a || _t39 < s.length - 1) && (h += _o3);
          }
        } catch (err) {
          _iterator19.e(err);
        } finally {
          _iterator19.f();
        }
        h = e(h, 1e3), this.contentDim = h, t && (t.contentRect[r] = h, t.contentRect[o ? "fullWidth" : "fullHeight"] = h), this.pages = this.createPages(), this.pages = this.processPages(), this.state === B.Init && this.setInitialPage(), this.page = Math.max(0, Math.min(this.page, this.pages.length - 1)), this.manageSlideVisiblity(), this.emit("refresh");
      }
    }, {
      key: "getProgress",
      value: function getProgress(t) {
        var i = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : !1;
        var n = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : !1;
        void 0 === t && (t = this.page);
        var s = this,
          o = s.panzoom,
          a = s.contentDim,
          r = s.pages[t] || 0;
        if (!r || !o) return t > this.page ? -1 : 1;
        var l = -1 * o.current.e,
          c = e((l - r.pos) / (1 * r.dim), 1e3),
          h = c,
          d = c;
        this.isInfinite && !0 !== n && (h = e((l - r.pos + a) / (1 * r.dim), 1e3), d = e((l - r.pos - a) / (1 * r.dim), 1e3));
        var u = [c, h, d].reduce(function (t, e) {
          return Math.abs(e) < Math.abs(t) ? e : t;
        });
        return i ? u : u > 1 ? 1 : u < -1 ? -1 : u;
      }
    }, {
      key: "setViewportHeight",
      value: function setViewportHeight() {
        var t = this.page,
          e = this.pages,
          i = this.viewport,
          n = this.isHorizontal;
        if (!i || !e[t]) return;
        var s = 0;
        n && this.track && (this.track.style.height = "auto", e[t].slides.forEach(function (t) {
          t.el && (s = Math.max(s, t.el.offsetHeight));
        })), i.style.height = s ? "".concat(s, "px") : "";
      }
    }, {
      key: "getPageForSlide",
      value: function getPageForSlide(t) {
        var _iterator20 = _createForOfIteratorHelper(this.pages),
          _step20;
        try {
          for (_iterator20.s(); !(_step20 = _iterator20.n()).done;) {
            var _e31 = _step20.value;
            var _iterator21 = _createForOfIteratorHelper(_e31.slides),
              _step21;
            try {
              for (_iterator21.s(); !(_step21 = _iterator21.n()).done;) {
                var _i48 = _step21.value;
                if (_i48.index === t) return _e31.index;
              }
            } catch (err) {
              _iterator21.e(err);
            } finally {
              _iterator21.f();
            }
          }
        } catch (err) {
          _iterator20.e(err);
        } finally {
          _iterator20.f();
        }
        return -1;
      }
    }, {
      key: "getVisibleSlides",
      value: function getVisibleSlides() {
        var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
        var e;
        var i = new Set();
        var n = this.panzoom,
          s = this.contentDim,
          o = this.viewportDim,
          a = this.pages,
          r = this.page;
        if (o) {
          s = s + (null === (e = this.slides[this.slides.length - 1]) || void 0 === e ? void 0 : e.gap) || 0;
          var _l2 = 0;
          _l2 = n && n.state !== v.Init && n.state !== v.Destroy ? -1 * n.current[this.axis] : a[r] && a[r].pos || 0, this.isInfinite && (_l2 -= Math.floor(_l2 / s) * s), this.isRTL && this.isHorizontal && (_l2 *= -1);
          var _c3 = _l2 - o * t,
            _h3 = _l2 + o * (t + 1),
            _d6 = this.isInfinite ? [-1, 0, 1] : [0];
          var _iterator22 = _createForOfIteratorHelper(this.slides),
            _step22;
          try {
            for (_iterator22.s(); !(_step22 = _iterator22.n()).done;) {
              var _t40 = _step22.value;
              var _iterator23 = _createForOfIteratorHelper(_d6),
                _step23;
              try {
                for (_iterator23.s(); !(_step23 = _iterator23.n()).done;) {
                  var _e32 = _step23.value;
                  var _n16 = _t40.pos + _e32 * s,
                    _o4 = _n16 + _t40.dim + _t40.gap;
                  _n16 < _h3 && _o4 > _c3 && i.add(_t40);
                }
              } catch (err) {
                _iterator23.e(err);
              } finally {
                _iterator23.f();
              }
            }
          } catch (err) {
            _iterator22.e(err);
          } finally {
            _iterator22.f();
          }
        }
        return i;
      }
    }, {
      key: "getPageFromPosition",
      value: function getPageFromPosition(t) {
        var e = this.viewportDim,
          i = this.contentDim,
          n = this.slides,
          s = this.pages,
          o = this.panzoom,
          a = s.length,
          r = n.length,
          l = n[0],
          c = n[r - 1],
          h = this.option("center");
        var d = 0,
          u = 0,
          p = 0,
          f = void 0 === t ? -1 * ((null == o ? void 0 : o.target[this.axis]) || 0) : t;
        h && (f += .5 * e), this.isInfinite ? (f < l.pos - .5 * c.gap && (f -= i, p = -1), f > c.pos + c.dim + .5 * c.gap && (f -= i, p = 1)) : f = Math.max(l.pos || 0, Math.min(f, c.pos));
        var g = c,
          m = n.find(function (t) {
            var e = t.pos - .5 * g.gap,
              i = t.pos + t.dim + .5 * t.gap;
            return g = t, f >= e && f < i;
          });
        return m || (m = c), u = this.getPageForSlide(m.index), d = u + p * a, {
          page: d,
          pageIndex: u
        };
      }
    }, {
      key: "setPageFromPosition",
      value: function setPageFromPosition() {
        var _this$getPageFromPosi3 = this.getPageFromPosition(),
          t = _this$getPageFromPosi3.pageIndex;
        this.onChange(t);
      }
    }, {
      key: "destroy",
      value: function destroy() {
        var _this33 = this;
        if ([B.Destroy].includes(this.state)) return;
        this.state = B.Destroy;
        var t = this.container,
          e = this.viewport,
          i = this.track,
          n = this.slides,
          s = this.panzoom,
          o = this.option("classes");
        t.removeEventListener("click", this.onClick, {
          passive: !1,
          capture: !1
        }), t.removeEventListener("slideTo", this.onSlideTo), window.removeEventListener("resize", this.onResize), s && (s.destroy(), this.panzoom = null), n && n.forEach(function (t) {
          _this33.removeSlideEl(t);
        }), this.detachPlugins(), e && (e.removeEventListener("scroll", this.onScroll), e.offsetParent && i && i.offsetParent && e.replaceWith.apply(e, _toConsumableArray(i.childNodes)));
        for (var _i49 = 0, _Object$entries4 = Object.entries(o); _i49 < _Object$entries4.length; _i49++) {
          var _Object$entries4$_i = _slicedToArray(_Object$entries4[_i49], 2),
            _e33 = _Object$entries4$_i[0],
            _i50 = _Object$entries4$_i[1];
          "container" !== _e33 && _i50 && t.classList.remove(_i50);
        }
        this.track = null, this.viewport = null, this.page = 0, this.slides = [];
        var a = this.events.get("ready");
        this.events = new Map(), a && this.events.set("ready", a);
      }
    }]);
    return Q;
  }(m);
  Object.defineProperty(Q, "Panzoom", {
    enumerable: !0,
    configurable: !0,
    writable: !0,
    value: I
  }), Object.defineProperty(Q, "defaults", {
    enumerable: !0,
    configurable: !0,
    writable: !0,
    value: j
  }), Object.defineProperty(Q, "Plugins", {
    enumerable: !0,
    configurable: !0,
    writable: !0,
    value: U
  });
  var tt = function tt(t) {
      if (!S(t)) return 0;
      var e = window.scrollY,
        i = window.innerHeight,
        n = e + i,
        s = t.getBoundingClientRect(),
        o = s.y + e,
        a = s.height,
        r = o + a;
      if (e > r || n < o) return 0;
      if (e < o && n > r) return 100;
      if (o < e && r > n) return 100;
      var l = a;
      o < e && (l -= e - o), r > n && (l -= r - n);
      var c = l / i * 100;
      return Math.round(c);
    },
    et = !("undefined" == typeof window || !window.document || !window.document.createElement);
  var it;
  var nt = ["a[href]", "area[href]", 'input:not([disabled]):not([type="hidden"]):not([aria-hidden])', "select:not([disabled]):not([aria-hidden])", "textarea:not([disabled]):not([aria-hidden])", "button:not([disabled]):not([aria-hidden]):not(.fancybox-focus-guard)", "iframe", "object", "embed", "video", "audio", "[contenteditable]", '[tabindex]:not([tabindex^="-"]):not([disabled]):not([aria-hidden])'].join(","),
    st = function st(t) {
      if (t && et) {
        void 0 === it && document.createElement("div").focus({
          get preventScroll() {
            return it = !0, !1;
          }
        });
        try {
          if (it) t.focus({
            preventScroll: !0
          });else {
            var _e34 = window.scrollY || document.body.scrollTop,
              _i51 = window.scrollX || document.body.scrollLeft;
            t.focus(), document.body.scrollTo({
              top: _e34,
              left: _i51,
              behavior: "auto"
            });
          }
        } catch (t) {}
      }
    },
    ot = function ot() {
      var t = document;
      var e,
        i = "",
        n = "",
        s = "";
      return t.fullscreenEnabled ? (i = "requestFullscreen", n = "exitFullscreen", s = "fullscreenElement") : t.webkitFullscreenEnabled && (i = "webkitRequestFullscreen", n = "webkitExitFullscreen", s = "webkitFullscreenElement"), i && (e = {
        request: function request() {
          var e = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : t.documentElement;
          return "webkitRequestFullscreen" === i ? e[i](Element.ALLOW_KEYBOARD_INPUT) : e[i]();
        },
        exit: function exit() {
          return t[s] && t[n]();
        },
        isFullscreen: function isFullscreen() {
          return t[s];
        }
      }), e;
    },
    at = {
      dragToClose: !0,
      hideScrollbar: !0,
      Carousel: {
        classes: {
          container: "fancybox__carousel",
          viewport: "fancybox__viewport",
          track: "fancybox__track",
          slide: "fancybox__slide"
        }
      },
      contentClick: "toggleZoom",
      contentDblClick: !1,
      backdropClick: "close",
      animated: !0,
      idle: 3500,
      showClass: "f-zoomInUp",
      hideClass: "f-fadeOut",
      commonCaption: !1,
      parentEl: null,
      startIndex: 0,
      l10n: Object.assign(Object.assign({}, y), {
        CLOSE: "Close",
        NEXT: "Next",
        PREV: "Previous",
        MODAL: "You can close this modal content with the ESC key",
        ERROR: "Something Went Wrong, Please Try Again Later",
        IMAGE_ERROR: "Image Not Found",
        ELEMENT_NOT_FOUND: "HTML Element Not Found",
        AJAX_NOT_FOUND: "Error Loading AJAX : Not Found",
        AJAX_FORBIDDEN: "Error Loading AJAX : Forbidden",
        IFRAME_ERROR: "Error Loading Page",
        TOGGLE_ZOOM: "Toggle zoom level",
        TOGGLE_THUMBS: "Toggle thumbnails",
        TOGGLE_SLIDESHOW: "Toggle slideshow",
        TOGGLE_FULLSCREEN: "Toggle full-screen mode",
        DOWNLOAD: "Download"
      }),
      tpl: {
        closeButton: '<button data-fancybox-close class="f-button is-close-btn" title="{{CLOSE}}"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" tabindex="-1"><path d="M20 20L4 4m16 0L4 20"/></svg></button>',
        main: '<div class="fancybox__container" role="dialog" aria-modal="true" aria-label="{{MODAL}}" tabindex="-1">\n    <div class="fancybox__backdrop"></div>\n    <div class="fancybox__carousel"></div>\n    <div class="fancybox__footer"></div>\n  </div>'
      },
      groupAll: !1,
      groupAttr: "data-fancybox",
      defaultType: "image",
      defaultDisplay: "block",
      autoFocus: !0,
      trapFocus: !0,
      placeFocusBack: !0,
      closeButton: "auto",
      keyboard: {
        Escape: "close",
        Delete: "close",
        Backspace: "close",
        PageUp: "next",
        PageDown: "prev",
        ArrowUp: "prev",
        ArrowDown: "next",
        ArrowRight: "next",
        ArrowLeft: "prev"
      },
      Fullscreen: {
        autoStart: !1
      },
      compact: function compact() {
        return window.matchMedia("(max-width: 578px), (max-height: 578px)").matches;
      },
      wheel: "zoom"
    };
  var rt, lt;
  !function (t) {
    t[t.Init = 0] = "Init", t[t.Ready = 1] = "Ready", t[t.Closing = 2] = "Closing", t[t.CustomClosing = 3] = "CustomClosing", t[t.Destroy = 4] = "Destroy";
  }(rt || (rt = {})), function (t) {
    t[t.Loading = 0] = "Loading", t[t.Opening = 1] = "Opening", t[t.Ready = 2] = "Ready", t[t.Closing = 3] = "Closing";
  }(lt || (lt = {}));
  var ct = "",
    ht = !1,
    dt = !1,
    ut = null;
  var pt = function pt() {
      var t = "",
        e = "";
      var i = Te.getInstance();
      if (i) {
        var _n17 = i.carousel,
          _s7 = i.getSlide();
        if (_n17 && _s7) {
          var _o5 = _s7.slug || void 0,
            _a3 = _s7.triggerEl || void 0;
          e = _o5 || i.option("slug") || "", !e && _a3 && _a3.dataset && (e = _a3.dataset.fancybox || ""), e && "true" !== e && (t = "#" + e + (!_o5 && _n17.slides.length > 1 ? "-" + (_s7.index + 1) : ""));
        }
      }
      return {
        hash: t,
        slug: e,
        index: 1
      };
    },
    ft = function ft() {
      var t = new URL(document.URL).hash,
        e = t.slice(1).split("-"),
        i = e[e.length - 1],
        n = i && /^\+?\d+$/.test(i) && parseInt(e.pop() || "1", 10) || 1;
      return {
        hash: t,
        slug: e.join("-"),
        index: n
      };
    },
    gt = function gt() {
      var _ft = ft(),
        t = _ft.slug,
        e = _ft.index;
      if (!t) return;
      var i = document.querySelector("[data-slug=\"".concat(t, "\"]"));
      if (i && i.dispatchEvent(new CustomEvent("click", {
        bubbles: !0,
        cancelable: !0
      })), Te.getInstance()) return;
      var n = document.querySelectorAll("[data-fancybox=\"".concat(t, "\"]"));
      n.length && (i = n[e - 1], i && i.dispatchEvent(new CustomEvent("click", {
        bubbles: !0,
        cancelable: !0
      })));
    },
    mt = function mt() {
      if (!1 === Te.defaults.Hash) return;
      var t = Te.getInstance();
      if (!1 === (null == t ? void 0 : t.options.Hash)) return;
      var _ft2 = ft(),
        e = _ft2.slug,
        i = _ft2.index,
        _pt = pt(),
        n = _pt.slug;
      t && (e === n ? t.jumpTo(i - 1) : (ht = !0, t.close())), gt();
    },
    vt = function vt() {
      ut && clearTimeout(ut), queueMicrotask(function () {
        mt();
      });
    },
    bt = function bt() {
      window.addEventListener("hashchange", vt, !1), setTimeout(function () {
        mt();
      }, 500);
    };
  et && (/complete|interactive|loaded/.test(document.readyState) ? bt() : document.addEventListener("DOMContentLoaded", bt));
  var yt = "is-zooming-in";
  var wt = /*#__PURE__*/function (_ref12) {
    _inherits(wt, _ref12);
    var _super12 = _createSuper(wt);
    function wt() {
      _classCallCheck(this, wt);
      return _super12.apply(this, arguments);
    }
    _createClass(wt, [{
      key: "onCreateSlide",
      value: function onCreateSlide(t, e, i) {
        var n = this.instance.optionFor(i, "src") || "";
        i.el && "image" === i.type && "string" == typeof n && this.setImage(i, n);
      }
    }, {
      key: "onRemoveSlide",
      value: function onRemoveSlide(t, e, i) {
        i.panzoom && i.panzoom.destroy(), i.panzoom = void 0, i.imageEl = void 0;
      }
    }, {
      key: "onChange",
      value: function onChange(t, e, i, n) {
        P(this.instance.container, yt);
        var _iterator24 = _createForOfIteratorHelper(e.slides),
          _step24;
        try {
          for (_iterator24.s(); !(_step24 = _iterator24.n()).done;) {
            var _t41 = _step24.value;
            var _e35 = _t41.panzoom;
            _e35 && _t41.index !== i && _e35.reset(.35);
          }
        } catch (err) {
          _iterator24.e(err);
        } finally {
          _iterator24.f();
        }
      }
    }, {
      key: "onClose",
      value: function onClose() {
        var t;
        var e = this.instance,
          i = e.container,
          n = e.getSlide();
        if (!i || !i.parentElement || !n) return;
        var s = n.el,
          o = n.contentEl,
          a = n.panzoom,
          r = n.thumbElSrc;
        if (!s || !r || !o || !a || a.isContentLoading || a.state === v.Init || a.state === v.Destroy) return;
        a.updateMetrics();
        var l = this.getZoomInfo(n);
        if (!l) return;
        this.instance.state = rt.CustomClosing, i.classList.remove(yt), i.classList.add("is-zooming-out"), o.style.backgroundImage = "url('".concat(r, "')");
        var c = i.getBoundingClientRect();
        1 === ((null === (t = window.visualViewport) || void 0 === t ? void 0 : t.scale) || 1) && Object.assign(i.style, {
          position: "absolute",
          top: "".concat(i.offsetTop + window.scrollY, "px"),
          left: "".concat(i.offsetLeft + window.scrollX, "px"),
          bottom: "auto",
          right: "auto",
          width: "".concat(c.width, "px"),
          height: "".concat(c.height, "px"),
          overflow: "hidden"
        });
        var h = l.x,
          d = l.y,
          u = l.scale,
          p = l.opacity;
        if (p) {
          var _t42 = function (t, e, i, n) {
            var s = e - t,
              o = n - i;
            return function (e) {
              return i + ((e - t) / s * o || 0);
            };
          }(a.scale, u, 1, 0);
          a.on("afterTransform", function () {
            o.style.opacity = _t42(a.scale) + "";
          });
        }
        a.on("endAnimation", function () {
          e.destroy();
        }), a.target.a = u, a.target.b = 0, a.target.c = 0, a.target.d = u, a.panTo({
          x: h,
          y: d,
          scale: u,
          friction: p ? .2 : .33,
          ignoreBounds: !0
        }), a.isResting && e.destroy();
      }
    }, {
      key: "setImage",
      value: function setImage(t, e) {
        var _this34 = this;
        var i = this.instance;
        t.src = e, this.process(t, e).then(function (e) {
          var n = t.contentEl,
            s = t.imageEl,
            o = t.thumbElSrc,
            a = t.el;
          if (i.isClosing() || !n || !s) return;
          n.offsetHeight;
          var r = !!i.isOpeningSlide(t) && _this34.getZoomInfo(t);
          if (_this34.option("protected") && a) {
            a.addEventListener("contextmenu", function (t) {
              t.preventDefault();
            });
            var _t43 = document.createElement("div");
            C(_t43, "fancybox-protected"), n.appendChild(_t43);
          }
          if (o && r) {
            var _s8 = e.contentRect,
              _a4 = Math.max(_s8.fullWidth, _s8.fullHeight);
            var _c4 = null;
            !r.opacity && _a4 > 1200 && (_c4 = document.createElement("img"), C(_c4, "fancybox-ghost"), _c4.src = o, n.appendChild(_c4));
            var _h4 = function _h4() {
              _c4 && (C(_c4, "f-fadeFastOut"), setTimeout(function () {
                _c4 && (_c4.remove(), _c4 = null);
              }, 200));
            };
            (l = o, new Promise(function (t, e) {
              var i = new Image();
              i.onload = t, i.onerror = e, i.src = l;
            })).then(function () {
              i.hideLoading(t), t.state = lt.Opening, _this34.instance.emit("reveal", t), _this34.zoomIn(t).then(function () {
                _h4(), _this34.instance.done(t);
              }, function () {}), _c4 && setTimeout(function () {
                _h4();
              }, _a4 > 2500 ? 800 : 200);
            }, function () {
              i.hideLoading(t), i.revealContent(t);
            });
          } else {
            var _n18 = _this34.optionFor(t, "initialSize"),
              _s9 = _this34.optionFor(t, "zoom"),
              _o6 = {
                event: i.prevMouseMoveEvent || i.options.event,
                friction: _s9 ? .12 : 0
              };
            var _a5 = i.optionFor(t, "showClass") || void 0,
              _r2 = !0;
            i.isOpeningSlide(t) && ("full" === _n18 ? e.zoomToFull(_o6) : "cover" === _n18 ? e.zoomToCover(_o6) : "max" === _n18 ? e.zoomToMax(_o6) : _r2 = !1, e.stop("current")), _r2 && _a5 && (_a5 = e.isDragging ? "f-fadeIn" : ""), i.hideLoading(t), i.revealContent(t, _a5);
          }
          var l;
        }, function () {
          i.setError(t, "{{IMAGE_ERROR}}");
        });
      }
    }, {
      key: "process",
      value: function process(t, e) {
        var _this35 = this;
        return new Promise(function (i, n) {
          var o;
          var a = _this35.instance,
            r = t.el;
          a.clearContent(t), a.showLoading(t);
          var l = _this35.optionFor(t, "content");
          if ("string" == typeof l && (l = s(l)), !l || !S(l)) {
            if (l = document.createElement("img"), l instanceof HTMLImageElement) {
              var _i52 = "",
                _n19 = t.caption;
              _i52 = "string" == typeof _n19 && _n19 ? _n19.replace(/<[^>]+>/gi, "").substring(0, 1e3) : "Image ".concat(t.index + 1, " of ").concat((null === (o = a.carousel) || void 0 === o ? void 0 : o.pages.length) || 1), l.src = e || "", l.alt = _i52, l.draggable = !1, t.srcset && l.setAttribute("srcset", t.srcset), _this35.instance.isOpeningSlide(t) && (l.fetchPriority = "high");
            }
            t.sizes && l.setAttribute("sizes", t.sizes);
          }
          C(l, "fancybox-image"), t.imageEl = l, a.setContent(t, l, !1);
          t.panzoom = new I(r, p({
            transformParent: !0
          }, _this35.option("Panzoom") || {}, {
            content: l,
            width: a.optionFor(t, "width", "auto"),
            height: a.optionFor(t, "height", "auto"),
            wheel: function wheel() {
              var t = a.option("wheel");
              return ("zoom" === t || "pan" == t) && t;
            },
            click: function click(e, i) {
              var n, s;
              if (a.isCompact || a.isClosing()) return !1;
              if (t.index !== (null === (n = a.getSlide()) || void 0 === n ? void 0 : n.index)) return !1;
              if (i) {
                var _t44 = i.composedPath()[0];
                if (["A", "BUTTON", "TEXTAREA", "OPTION", "INPUT", "SELECT", "VIDEO"].includes(_t44.nodeName)) return !1;
              }
              var o = !i || i.target && (null === (s = t.contentEl) || void 0 === s ? void 0 : s.contains(i.target));
              return a.option(o ? "contentClick" : "backdropClick") || !1;
            },
            dblClick: function dblClick() {
              return a.isCompact ? "toggleZoom" : a.option("contentDblClick") || !1;
            },
            spinner: !1,
            panOnlyZoomed: !0,
            wheelLimit: 1 / 0,
            on: {
              ready: function ready(t) {
                i(t);
              },
              error: function error() {
                n();
              },
              destroy: function destroy() {
                n();
              }
            }
          }));
        });
      }
    }, {
      key: "zoomIn",
      value: function zoomIn(t) {
        var _this36 = this;
        return new Promise(function (e, i) {
          var n = _this36.instance,
            s = n.container,
            o = t.panzoom,
            a = t.contentEl,
            r = t.el;
          o && o.updateMetrics();
          var l = _this36.getZoomInfo(t);
          if (!(l && r && a && o && s)) return void i();
          var c = l.x,
            h = l.y,
            d = l.scale,
            u = l.opacity,
            p = function p() {
              t.state !== lt.Closing && (u && (a.style.opacity = Math.max(Math.min(1, 1 - (1 - o.scale) / (1 - d)), 0) + ""), o.scale >= 1 && o.scale > o.targetScale - .1 && e(o));
            },
            f = function f(t) {
              (t.scale < .99 || t.scale > 1.01) && !t.isDragging || (P(s, yt), a.style.opacity = "", t.off("endAnimation", f), t.off("touchStart", f), t.off("afterTransform", p), e(t));
            };
          o.on("endAnimation", f), o.on("touchStart", f), o.on("afterTransform", p), o.on(["error", "destroy"], function () {
            i();
          }), o.panTo({
            x: c,
            y: h,
            scale: d,
            friction: 0,
            ignoreBounds: !0
          }), o.stop("current");
          var g = {
              event: "mousemove" === o.panMode ? n.prevMouseMoveEvent || n.options.event : void 0
            },
            m = _this36.optionFor(t, "initialSize");
          C(s, yt), n.hideLoading(t), "full" === m ? o.zoomToFull(g) : "cover" === m ? o.zoomToCover(g) : "max" === m ? o.zoomToMax(g) : o.reset(.172);
        });
      }
    }, {
      key: "getZoomInfo",
      value: function getZoomInfo(t) {
        var e = t.el,
          i = t.imageEl,
          n = t.thumbEl,
          s = t.panzoom,
          o = this.instance,
          a = o.container;
        if (!e || !i || !n || !s || tt(n) < 3 || !this.optionFor(t, "zoom") || !a || o.state === rt.Destroy) return !1;
        if ("0" === getComputedStyle(a).getPropertyValue("--f-images-zoom")) return !1;
        var r = window.visualViewport || null;
        if (1 !== (r ? r.scale : 1)) return !1;
        var _n$getBoundingClientR = n.getBoundingClientRect(),
          l = _n$getBoundingClientR.top,
          c = _n$getBoundingClientR.left,
          h = _n$getBoundingClientR.width,
          d = _n$getBoundingClientR.height,
          _s$contentRect = s.contentRect,
          u = _s$contentRect.top,
          p = _s$contentRect.left,
          f = _s$contentRect.fitWidth,
          g = _s$contentRect.fitHeight;
        if (!(h && d && f && g)) return !1;
        var m = s.container.getBoundingClientRect();
        p += m.left, u += m.top;
        var v = -1 * (p + .5 * f - (c + .5 * h)),
          b = -1 * (u + .5 * g - (l + .5 * d)),
          y = h / f;
        var w = this.option("zoomOpacity") || !1;
        return "auto" === w && (w = Math.abs(h / d - f / g) > .1), {
          x: v,
          y: b,
          scale: y,
          opacity: w
        };
      }
    }, {
      key: "attach",
      value: function attach() {
        var t = this,
          e = t.instance;
        e.on("Carousel.change", t.onChange), e.on("Carousel.createSlide", t.onCreateSlide), e.on("Carousel.removeSlide", t.onRemoveSlide), e.on("close", t.onClose);
      }
    }, {
      key: "detach",
      value: function detach() {
        var t = this,
          e = t.instance;
        e.off("Carousel.change", t.onChange), e.off("Carousel.createSlide", t.onCreateSlide), e.off("Carousel.removeSlide", t.onRemoveSlide), e.off("close", t.onClose);
      }
    }]);
    return wt;
  }(_);
  Object.defineProperty(wt, "defaults", {
    enumerable: !0,
    configurable: !0,
    writable: !0,
    value: {
      initialSize: "fit",
      Panzoom: {
        maxScale: 1
      },
      "protected": !1,
      zoom: !0,
      zoomOpacity: "auto"
    }
  }), "function" == typeof SuppressedError && SuppressedError;
  var xt = "html",
    Et = "image",
    St = "map",
    Pt = "youtube",
    Ct = "vimeo",
    Tt = "html5video",
    Mt = function Mt(t) {
      var e = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
      var i = new URL(t),
        n = new URLSearchParams(i.search),
        s = new URLSearchParams();
      for (var _i53 = 0, _arr5 = [].concat(_toConsumableArray(n), _toConsumableArray(Object.entries(e))); _i53 < _arr5.length; _i53++) {
        var _arr5$_i = _slicedToArray(_arr5[_i53], 2),
          _t45 = _arr5$_i[0],
          _i54 = _arr5$_i[1];
        var _e36 = _i54 + "";
        if ("t" === _t45) {
          var _t46 = _e36.match(/((\d*)m)?(\d*)s?/);
          _t46 && s.set("start", 60 * parseInt(_t46[2] || "0") + parseInt(_t46[3] || "0") + "");
        } else s.set(_t45, _e36);
      }
      var o = s + "",
        a = t.match(/#t=((.*)?\d+s)/);
      return a && (o += "#t=".concat(a[1])), o;
    },
    Ot = {
      ajax: null,
      autoSize: !0,
      iframeAttr: {
        allow: "autoplay; fullscreen",
        scrolling: "auto"
      },
      preload: !0,
      videoAutoplay: !0,
      videoRatio: 16 / 9,
      videoTpl: '<video class="fancybox__html5video" playsinline controls controlsList="nodownload" poster="{{poster}}">\n  <source src="{{src}}" type="{{format}}" />Sorry, your browser doesn\'t support embedded videos.</video>',
      videoFormat: "",
      vimeo: {
        byline: 1,
        color: "00adef",
        controls: 1,
        dnt: 1,
        muted: 0
      },
      youtube: {
        controls: 1,
        enablejsapi: 1,
        nocookie: 1,
        rel: 0,
        fs: 1
      }
    },
    At = ["image", "html", "ajax", "inline", "clone", "iframe", "map", "pdf", "html5video", "youtube", "vimeo"];
  var Lt = /*#__PURE__*/function (_ref13) {
    _inherits(Lt, _ref13);
    var _super13 = _createSuper(Lt);
    function Lt() {
      _classCallCheck(this, Lt);
      return _super13.apply(this, arguments);
    }
    _createClass(Lt, [{
      key: "onBeforeInitSlide",
      value: function onBeforeInitSlide(t, e, i) {
        this.processType(i);
      }
    }, {
      key: "onCreateSlide",
      value: function onCreateSlide(t, e, i) {
        this.setContent(i);
      }
    }, {
      key: "onClearContent",
      value: function onClearContent(t, e) {
        e.xhr && (e.xhr.abort(), e.xhr = null);
        var i = e.iframeEl;
        i && (i.onload = i.onerror = null, i.src = "//about:blank", e.iframeEl = null);
        var n = e.contentEl,
          s = e.placeholderEl;
        if ("inline" === e.type && n && s) n.classList.remove("fancybox__content"), "none" !== n.style.display && (n.style.display = "none"), s.parentNode && s.parentNode.insertBefore(n, s), s.remove(), e.contentEl = void 0, e.placeholderEl = void 0;else for (; e.el && e.el.firstChild;) e.el.removeChild(e.el.firstChild);
      }
    }, {
      key: "onSelectSlide",
      value: function onSelectSlide(t, e, i) {
        i.state === lt.Ready && this.playVideo();
      }
    }, {
      key: "onUnselectSlide",
      value: function onUnselectSlide(t, e, i) {
        var n, s;
        if (i.type === Tt) {
          try {
            null === (s = null === (n = i.el) || void 0 === n ? void 0 : n.querySelector("video")) || void 0 === s || s.pause();
          } catch (t) {}
          return;
        }
        var o;
        i.type === Ct ? o = {
          method: "pause",
          value: "true"
        } : i.type === Pt && (o = {
          event: "command",
          func: "pauseVideo"
        }), o && i.iframeEl && i.iframeEl.contentWindow && i.iframeEl.contentWindow.postMessage(JSON.stringify(o), "*"), i.poller && clearTimeout(i.poller);
      }
    }, {
      key: "onDone",
      value: function onDone(t, e) {
        t.isCurrentSlide(e) && !t.isClosing() && this.playVideo();
      }
    }, {
      key: "onRefresh",
      value: function onRefresh(t, e) {
        var _this37 = this;
        e.slides.forEach(function (t) {
          t.el && (_this37.resizeIframe(t), _this37.setAspectRatio(t));
        });
      }
    }, {
      key: "onMessage",
      value: function onMessage(t) {
        try {
          var _e37 = JSON.parse(t.data);
          if ("https://player.vimeo.com" === t.origin) {
            if ("ready" === _e37.event) for (var _i55 = 0, _Array$from3 = Array.from(document.getElementsByClassName("fancybox__iframe")); _i55 < _Array$from3.length; _i55++) {
              var _e38 = _Array$from3[_i55];
              _e38 instanceof HTMLIFrameElement && _e38.contentWindow === t.source && (_e38.dataset.ready = "true");
            }
          } else if (t.origin.match(/^https:\/\/(www.)?youtube(-nocookie)?.com$/) && "onReady" === _e37.event) {
            var _t47 = document.getElementById(_e37.id);
            _t47 && (_t47.dataset.ready = "true");
          }
        } catch (t) {}
      }
    }, {
      key: "loadAjaxContent",
      value: function loadAjaxContent(t) {
        var e = this.instance.optionFor(t, "src") || "";
        this.instance.showLoading(t);
        var i = this.instance,
          n = new XMLHttpRequest();
        i.showLoading(t), n.onreadystatechange = function () {
          n.readyState === XMLHttpRequest.DONE && i.state === rt.Ready && (i.hideLoading(t), 200 === n.status ? i.setContent(t, n.responseText) : i.setError(t, 404 === n.status ? "{{AJAX_NOT_FOUND}}" : "{{AJAX_FORBIDDEN}}"));
        };
        var s = t.ajax || null;
        n.open(s ? "POST" : "GET", e + ""), n.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"), n.setRequestHeader("X-Requested-With", "XMLHttpRequest"), n.send(s), t.xhr = n;
      }
    }, {
      key: "setInlineContent",
      value: function setInlineContent(t) {
        var e = null;
        if (S(t.src)) e = t.src;else if ("string" == typeof t.src) {
          var _i56 = t.src.split("#", 2).pop();
          e = _i56 ? document.getElementById(_i56) : null;
        }
        if (e) {
          if ("clone" === t.type || e.closest(".fancybox__slide")) {
            e = e.cloneNode(!0);
            var _i57 = e.dataset.animationName;
            _i57 && (e.classList.remove(_i57), delete e.dataset.animationName);
            var _n20 = e.getAttribute("id");
            _n20 = _n20 ? "".concat(_n20, "--clone") : "clone-".concat(this.instance.id, "-").concat(t.index), e.setAttribute("id", _n20);
          } else if (e.parentNode) {
            var _i58 = document.createElement("div");
            _i58.classList.add("fancybox-placeholder"), e.parentNode.insertBefore(_i58, e), t.placeholderEl = _i58;
          }
          this.instance.setContent(t, e);
        } else this.instance.setError(t, "{{ELEMENT_NOT_FOUND}}");
      }
    }, {
      key: "setIframeContent",
      value: function setIframeContent(t) {
        var _this38 = this;
        var e = t.src,
          i = t.el;
        if (!e || "string" != typeof e || !i) return;
        i.classList.add("is-loading");
        var n = this.instance,
          s = document.createElement("iframe");
        s.className = "fancybox__iframe", s.setAttribute("id", "fancybox__iframe_".concat(n.id, "_").concat(t.index));
        for (var _i59 = 0, _Object$entries5 = Object.entries(this.optionFor(t, "iframeAttr") || {}); _i59 < _Object$entries5.length; _i59++) {
          var _Object$entries5$_i = _slicedToArray(_Object$entries5[_i59], 2),
            _e39 = _Object$entries5$_i[0],
            _i60 = _Object$entries5$_i[1];
          s.setAttribute(_e39, _i60);
        }
        s.onerror = function () {
          n.setError(t, "{{IFRAME_ERROR}}");
        }, t.iframeEl = s;
        var o = this.optionFor(t, "preload");
        if ("iframe" !== t.type || !1 === o) return s.setAttribute("src", t.src + ""), n.setContent(t, s, !1), this.resizeIframe(t), void n.revealContent(t);
        n.showLoading(t), s.onload = function () {
          if (!s.src.length) return;
          var e = "true" !== s.dataset.ready;
          s.dataset.ready = "true", _this38.resizeIframe(t), e ? n.revealContent(t) : n.hideLoading(t);
        }, s.setAttribute("src", e), n.setContent(t, s, !1);
      }
    }, {
      key: "resizeIframe",
      value: function resizeIframe(t) {
        var e = t.type,
          i = t.iframeEl;
        if (e === Pt || e === Ct) return;
        var n = null == i ? void 0 : i.parentElement;
        if (!i || !n) return;
        var s = t.autoSize;
        void 0 === s && (s = this.optionFor(t, "autoSize"));
        var o = t.width || 0,
          a = t.height || 0;
        o && a && (s = !1);
        var r = n && n.style;
        if (!1 !== t.preload && !1 !== s && r) try {
          var _t48 = window.getComputedStyle(n),
            _e40 = parseFloat(_t48.paddingLeft) + parseFloat(_t48.paddingRight),
            _s10 = parseFloat(_t48.paddingTop) + parseFloat(_t48.paddingBottom),
            _l3 = i.contentWindow;
          if (_l3) {
            var _t49 = _l3.document,
              _i61 = _t49.getElementsByTagName(xt)[0],
              _n21 = _t49.body;
            r.width = "", _n21.style.overflow = "hidden", o = o || _i61.scrollWidth + _e40, r.width = "".concat(o, "px"), _n21.style.overflow = "", r.flex = "0 0 auto", r.height = "".concat(_n21.scrollHeight, "px"), a = _i61.scrollHeight + _s10;
          }
        } catch (t) {}
        if (o || a) {
          var _t50 = {
            flex: "0 1 auto",
            width: "",
            height: ""
          };
          o && "auto" !== o && (_t50.width = "".concat(o, "px")), a && "auto" !== a && (_t50.height = "".concat(a, "px")), Object.assign(r, _t50);
        }
      }
    }, {
      key: "playVideo",
      value: function playVideo() {
        var t = this.instance.getSlide();
        if (!t) return;
        var e = t.el;
        if (!e || !e.offsetParent) return;
        if (!this.optionFor(t, "videoAutoplay")) return;
        if (t.type === Tt) try {
          var _t51 = e.querySelector("video");
          if (_t51) {
            var _e41 = _t51.play();
            void 0 !== _e41 && _e41.then(function () {})["catch"](function (e) {
              _t51.muted = !0, _t51.play();
            });
          }
        } catch (t) {}
        if (t.type !== Pt && t.type !== Ct) return;
        var i = function i() {
          if (t.iframeEl && t.iframeEl.contentWindow) {
            var _e42;
            if ("true" === t.iframeEl.dataset.ready) return _e42 = t.type === Pt ? {
              event: "command",
              func: "playVideo"
            } : {
              method: "play",
              value: "true"
            }, _e42 && t.iframeEl.contentWindow.postMessage(JSON.stringify(_e42), "*"), void (t.poller = void 0);
            t.type === Pt && (_e42 = {
              event: "listening",
              id: t.iframeEl.getAttribute("id")
            }, t.iframeEl.contentWindow.postMessage(JSON.stringify(_e42), "*"));
          }
          t.poller = setTimeout(i, 250);
        };
        i();
      }
    }, {
      key: "processType",
      value: function processType(t) {
        if (t.html) return t.type = xt, t.src = t.html, void (t.html = "");
        var e = this.instance.optionFor(t, "src", "");
        if (!e || "string" != typeof e) return;
        var i = t.type,
          n = null;
        if (n = e.match(/(youtube\.com|youtu\.be|youtube\-nocookie\.com)\/(?:watch\?(?:.*&)?v=|v\/|u\/|shorts\/|embed\/?)?(videoseries\?list=(?:.*)|[\w-]{11}|\?listType=(?:.*)&list=(?:.*))(?:.*)/i)) {
          var _s11 = this.optionFor(t, Pt),
            _o7 = _s11.nocookie,
            _a6 = function (t, e) {
              var i = {};
              for (var n in t) Object.prototype.hasOwnProperty.call(t, n) && e.indexOf(n) < 0 && (i[n] = t[n]);
              if (null != t && "function" == typeof Object.getOwnPropertySymbols) {
                var s = 0;
                for (n = Object.getOwnPropertySymbols(t); s < n.length; s++) e.indexOf(n[s]) < 0 && Object.prototype.propertyIsEnumerable.call(t, n[s]) && (i[n[s]] = t[n[s]]);
              }
              return i;
            }(_s11, ["nocookie"]),
            _r3 = "www.youtube".concat(_o7 ? "-nocookie" : "", ".com"),
            _l4 = Mt(e, _a6),
            _c5 = encodeURIComponent(n[2]);
          t.videoId = _c5, t.src = "https://".concat(_r3, "/embed/").concat(_c5, "?").concat(_l4), t.thumbSrc = t.thumbSrc || "https://i.ytimg.com/vi/".concat(_c5, "/mqdefault.jpg"), i = Pt;
        } else if (n = e.match(/^.+vimeo.com\/(?:\/)?([\d]+)((\/|\?h=)([a-z0-9]+))?(.*)?/)) {
          var _s12 = Mt(e, this.optionFor(t, Ct)),
            _o8 = encodeURIComponent(n[1]),
            _a7 = n[4] || "";
          t.videoId = _o8, t.src = "https://player.vimeo.com/video/".concat(_o8, "?").concat(_a7 ? "h=".concat(_a7).concat(_s12 ? "&" : "") : "").concat(_s12), i = Ct;
        }
        if (!i && t.triggerEl) {
          var _e43 = t.triggerEl.dataset.type;
          At.includes(_e43) && (i = _e43);
        }
        i || "string" == typeof e && ("#" === e.charAt(0) ? i = "inline" : (n = e.match(/\.(mp4|mov|ogv|webm)((\?|#).*)?$/i)) ? (i = Tt, t.videoFormat = t.videoFormat || "video/" + ("ogv" === n[1] ? "ogg" : n[1])) : e.match(/(^data:image\/[a-z0-9+\/=]*,)|(\.(jp(e|g|eg)|gif|png|bmp|webp|svg|ico)((\?|#).*)?$)/i) ? i = Et : e.match(/\.(pdf)((\?|#).*)?$/i) && (i = "pdf")), (n = e.match(/(?:maps\.)?google\.([a-z]{2,3}(?:\.[a-z]{2})?)\/(?:(?:(?:maps\/(?:place\/(?:.*)\/)?\@(.*),(\d+.?\d+?)z))|(?:\?ll=))(.*)?/i)) ? (t.src = "https://maps.google.".concat(n[1], "/?ll=").concat((n[2] ? n[2] + "&z=" + Math.floor(parseFloat(n[3])) + (n[4] ? n[4].replace(/^\//, "&") : "") : n[4] + "").replace(/\?/, "&"), "&output=").concat(n[4] && n[4].indexOf("layer=c") > 0 ? "svembed" : "embed"), i = St) : (n = e.match(/(?:maps\.)?google\.([a-z]{2,3}(?:\.[a-z]{2})?)\/(?:maps\/search\/)(.*)/i)) && (t.src = "https://maps.google.".concat(n[1], "/maps?q=").concat(n[2].replace("query=", "q=").replace("api=1", ""), "&output=embed"), i = St), i = i || this.instance.option("defaultType"), t.type = i, i === Et && (t.thumbSrc = t.thumbSrc || t.src);
      }
    }, {
      key: "setContent",
      value: function setContent(t) {
        var e = this.instance.optionFor(t, "src") || "";
        if (t && t.type && e) {
          switch (t.type) {
            case xt:
              this.instance.setContent(t, e);
              break;
            case Tt:
              var _i62 = this.option("videoTpl");
              _i62 && this.instance.setContent(t, _i62.replace(/\{\{src\}\}/gi, e + "").replace(/\{\{format\}\}/gi, this.optionFor(t, "videoFormat") || "").replace(/\{\{poster\}\}/gi, t.poster || t.thumbSrc || ""));
              break;
            case "inline":
            case "clone":
              this.setInlineContent(t);
              break;
            case "ajax":
              this.loadAjaxContent(t);
              break;
            case "pdf":
            case St:
            case Pt:
            case Ct:
              t.preload = !1;
            case "iframe":
              this.setIframeContent(t);
          }
          this.setAspectRatio(t);
        }
      }
    }, {
      key: "setAspectRatio",
      value: function setAspectRatio(t) {
        var e = t.contentEl;
        if (!(t.el && e && t.type && [Pt, Ct, Tt].includes(t.type))) return;
        var i,
          n = t.width || "auto",
          s = t.height || "auto";
        if ("auto" === n || "auto" === s) {
          i = this.optionFor(t, "videoRatio");
          var _e44 = (i + "").match(/(\d+)\s*\/\s?(\d+)/);
          i = _e44 && _e44.length > 2 ? parseFloat(_e44[1]) / parseFloat(_e44[2]) : parseFloat(i + "");
        } else n && s && (i = n / s);
        if (!i) return;
        e.style.aspectRatio = "", e.style.width = "", e.style.height = "", e.offsetHeight;
        var o = e.getBoundingClientRect(),
          a = o.width || 1,
          r = o.height || 1;
        e.style.aspectRatio = i + "", i < a / r ? (s = "auto" === s ? r : Math.min(r, s), e.style.width = "auto", e.style.height = "".concat(s, "px")) : (n = "auto" === n ? a : Math.min(a, n), e.style.width = "".concat(n, "px"), e.style.height = "auto");
      }
    }, {
      key: "attach",
      value: function attach() {
        var t = this,
          e = t.instance;
        e.on("Carousel.beforeInitSlide", t.onBeforeInitSlide), e.on("Carousel.createSlide", t.onCreateSlide), e.on("Carousel.selectSlide", t.onSelectSlide), e.on("Carousel.unselectSlide", t.onUnselectSlide), e.on("Carousel.Panzoom.refresh", t.onRefresh), e.on("done", t.onDone), e.on("clearContent", t.onClearContent), window.addEventListener("message", t.onMessage);
      }
    }, {
      key: "detach",
      value: function detach() {
        var t = this,
          e = t.instance;
        e.off("Carousel.beforeInitSlide", t.onBeforeInitSlide), e.off("Carousel.createSlide", t.onCreateSlide), e.off("Carousel.selectSlide", t.onSelectSlide), e.off("Carousel.unselectSlide", t.onUnselectSlide), e.off("Carousel.Panzoom.refresh", t.onRefresh), e.off("done", t.onDone), e.off("clearContent", t.onClearContent), window.removeEventListener("message", t.onMessage);
      }
    }]);
    return Lt;
  }(_);
  Object.defineProperty(Lt, "defaults", {
    enumerable: !0,
    configurable: !0,
    writable: !0,
    value: Ot
  });
  var zt = "play",
    Rt = "pause",
    kt = "ready";
  var It = /*#__PURE__*/function (_ref14) {
    _inherits(It, _ref14);
    var _super14 = _createSuper(It);
    function It() {
      var _this39;
      _classCallCheck(this, It);
      _this39 = _super14.apply(this, arguments), Object.defineProperty(_assertThisInitialized(_this39), "state", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: kt
      }), Object.defineProperty(_assertThisInitialized(_this39), "inHover", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: !1
      }), Object.defineProperty(_assertThisInitialized(_this39), "timer", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this39), "progressBar", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      });
      return _this39;
    }
    _createClass(It, [{
      key: "isActive",
      get: function get() {
        return this.state !== kt;
      }
    }, {
      key: "onReady",
      value: function onReady(t) {
        this.option("autoStart") && (t.isInfinite || t.page < t.pages.length - 1) && this.start();
      }
    }, {
      key: "onChange",
      value: function onChange() {
        this.removeProgressBar(), this.pause();
      }
    }, {
      key: "onSettle",
      value: function onSettle() {
        this.resume();
      }
    }, {
      key: "onVisibilityChange",
      value: function onVisibilityChange() {
        "visible" === document.visibilityState ? this.resume() : this.pause();
      }
    }, {
      key: "onMouseEnter",
      value: function onMouseEnter() {
        this.inHover = !0, this.pause();
      }
    }, {
      key: "onMouseLeave",
      value: function onMouseLeave() {
        var t;
        this.inHover = !1, (null === (t = this.instance.panzoom) || void 0 === t ? void 0 : t.isResting) && this.resume();
      }
    }, {
      key: "onTimerEnd",
      value: function onTimerEnd() {
        var t = this.instance;
        "play" === this.state && (t.isInfinite || t.page !== t.pages.length - 1 ? t.slideNext() : t.slideTo(0));
      }
    }, {
      key: "removeProgressBar",
      value: function removeProgressBar() {
        this.progressBar && (this.progressBar.remove(), this.progressBar = null);
      }
    }, {
      key: "createProgressBar",
      value: function createProgressBar() {
        var t;
        if (!this.option("showProgress")) return null;
        this.removeProgressBar();
        var e = this.instance,
          i = (null === (t = e.pages[e.page]) || void 0 === t ? void 0 : t.slides) || [];
        var n = this.option("progressParentEl");
        if (n || (n = (1 === i.length ? i[0].el : null) || e.viewport), !n) return null;
        var s = document.createElement("div");
        return C(s, "f-progress"), n.prepend(s), this.progressBar = s, s.offsetHeight, s;
      }
    }, {
      key: "set",
      value: function set() {
        var t = this,
          e = t.instance;
        if (e.pages.length < 2) return;
        if (t.timer) return;
        var i = t.option("timeout");
        t.state = zt, C(e.container, "has-autoplay");
        var n = t.createProgressBar();
        n && (n.style.transitionDuration = "".concat(i, "ms"), n.style.transform = "scaleX(1)"), t.timer = setTimeout(function () {
          t.timer = null, t.inHover || t.onTimerEnd();
        }, i), t.emit("set");
      }
    }, {
      key: "clear",
      value: function clear() {
        var t = this;
        t.timer && (clearTimeout(t.timer), t.timer = null), t.removeProgressBar();
      }
    }, {
      key: "start",
      value: function start() {
        var t = this;
        if (t.set(), t.state !== kt) {
          if (t.option("pauseOnHover")) {
            var _e45 = t.instance.container;
            _e45.addEventListener("mouseenter", t.onMouseEnter, !1), _e45.addEventListener("mouseleave", t.onMouseLeave, !1);
          }
          document.addEventListener("visibilitychange", t.onVisibilityChange, !1), t.emit("start");
        }
      }
    }, {
      key: "stop",
      value: function stop() {
        var t = this,
          e = t.state,
          i = t.instance.container;
        t.clear(), t.state = kt, i.removeEventListener("mouseenter", t.onMouseEnter, !1), i.removeEventListener("mouseleave", t.onMouseLeave, !1), document.removeEventListener("visibilitychange", t.onVisibilityChange, !1), P(i, "has-autoplay"), e !== kt && t.emit("stop");
      }
    }, {
      key: "pause",
      value: function pause() {
        var t = this;
        t.state === zt && (t.state = Rt, t.clear(), t.emit(Rt));
      }
    }, {
      key: "resume",
      value: function resume() {
        var t = this,
          e = t.instance;
        if (e.isInfinite || e.page !== e.pages.length - 1) {
          if (t.state !== zt) {
            if (t.state === Rt && !t.inHover) {
              var _e46 = new Event("resume", {
                bubbles: !0,
                cancelable: !0
              });
              t.emit("resume", _e46), _e46.defaultPrevented || t.set();
            }
          } else t.set();
        } else t.stop();
      }
    }, {
      key: "toggle",
      value: function toggle() {
        this.state === zt || this.state === Rt ? this.stop() : this.start();
      }
    }, {
      key: "attach",
      value: function attach() {
        var t = this,
          e = t.instance;
        e.on("ready", t.onReady), e.on("Panzoom.startAnimation", t.onChange), e.on("Panzoom.endAnimation", t.onSettle), e.on("Panzoom.touchMove", t.onChange);
      }
    }, {
      key: "detach",
      value: function detach() {
        var t = this,
          e = t.instance;
        e.off("ready", t.onReady), e.off("Panzoom.startAnimation", t.onChange), e.off("Panzoom.endAnimation", t.onSettle), e.off("Panzoom.touchMove", t.onChange), t.stop();
      }
    }]);
    return It;
  }(_);
  Object.defineProperty(It, "defaults", {
    enumerable: !0,
    configurable: !0,
    writable: !0,
    value: {
      autoStart: !0,
      pauseOnHover: !0,
      progressParentEl: null,
      showProgress: !0,
      timeout: 3e3
    }
  });
  var Dt = /*#__PURE__*/function (_ref15) {
    _inherits(Dt, _ref15);
    var _super15 = _createSuper(Dt);
    function Dt() {
      var _this40;
      _classCallCheck(this, Dt);
      _this40 = _super15.apply(this, arguments), Object.defineProperty(_assertThisInitialized(_this40), "ref", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      });
      return _this40;
    }
    _createClass(Dt, [{
      key: "onPrepare",
      value: function onPrepare(t) {
        var _this41 = this;
        var e = t.carousel;
        if (!e) return;
        var i = t.container;
        i && (e.options.Autoplay = p({
          autoStart: !1
        }, this.option("Autoplay") || {}, {
          pauseOnHover: !1,
          timeout: this.option("timeout"),
          progressParentEl: function progressParentEl() {
            return _this41.option("progressParentEl") || null;
          },
          on: {
            start: function start() {
              t.emit("startSlideshow");
            },
            set: function set(e) {
              var n;
              i.classList.add("has-slideshow"), (null === (n = t.getSlide()) || void 0 === n ? void 0 : n.state) !== lt.Ready && e.pause();
            },
            stop: function stop() {
              i.classList.remove("has-slideshow"), t.isCompact || t.endIdle(), t.emit("endSlideshow");
            },
            resume: function resume(e, i) {
              var n, s, o;
              !i || !i.cancelable || (null === (n = t.getSlide()) || void 0 === n ? void 0 : n.state) === lt.Ready && (null === (o = null === (s = t.carousel) || void 0 === s ? void 0 : s.panzoom) || void 0 === o ? void 0 : o.isResting) || i.preventDefault();
            }
          }
        }), e.attachPlugins({
          Autoplay: It
        }), this.ref = e.plugins.Autoplay);
      }
    }, {
      key: "onReady",
      value: function onReady(t) {
        var e = t.carousel,
          i = this.ref;
        i && e && this.option("playOnStart") && (e.isInfinite || e.page < e.pages.length - 1) && i.start();
      }
    }, {
      key: "onDone",
      value: function onDone(t, e) {
        var i = this.ref,
          n = t.carousel;
        if (!i || !n) return;
        var s = e.panzoom;
        s && s.on("startAnimation", function () {
          t.isCurrentSlide(e) && i.stop();
        }), t.isCurrentSlide(e) && i.resume();
      }
    }, {
      key: "onKeydown",
      value: function onKeydown(t, e) {
        var i;
        var n = this.ref;
        n && e === this.option("key") && "BUTTON" !== (null === (i = document.activeElement) || void 0 === i ? void 0 : i.nodeName) && n.toggle();
      }
    }, {
      key: "attach",
      value: function attach() {
        var t = this,
          e = t.instance;
        e.on("Carousel.init", t.onPrepare), e.on("Carousel.ready", t.onReady), e.on("done", t.onDone), e.on("keydown", t.onKeydown);
      }
    }, {
      key: "detach",
      value: function detach() {
        var t = this,
          e = t.instance;
        e.off("Carousel.init", t.onPrepare), e.off("Carousel.ready", t.onReady), e.off("done", t.onDone), e.off("keydown", t.onKeydown);
      }
    }]);
    return Dt;
  }(_);
  Object.defineProperty(Dt, "defaults", {
    enumerable: !0,
    configurable: !0,
    writable: !0,
    value: {
      key: " ",
      playOnStart: !1,
      progressParentEl: function progressParentEl(t) {
        var e;
        return (null === (e = t.instance.container) || void 0 === e ? void 0 : e.querySelector(".fancybox__toolbar [data-fancybox-toggle-slideshow]")) || t.instance.container;
      },
      timeout: 3e3
    }
  });
  var Ft = {
    classes: {
      container: "f-thumbs f-carousel__thumbs",
      viewport: "f-thumbs__viewport",
      track: "f-thumbs__track",
      slide: "f-thumbs__slide",
      isResting: "is-resting",
      isSelected: "is-selected",
      isLoading: "is-loading",
      hasThumbs: "has-thumbs"
    },
    minCount: 2,
    parentEl: null,
    thumbTpl: '<button class="f-thumbs__slide__button" tabindex="0" type="button" aria-label="{{GOTO}}" data-carousel-index="%i"><img class="f-thumbs__slide__img" data-lazy-src="{{%s}}" alt="" /></button>',
    type: "modern"
  };
  var jt;
  !function (t) {
    t[t.Init = 0] = "Init", t[t.Ready = 1] = "Ready", t[t.Hidden = 2] = "Hidden";
  }(jt || (jt = {}));
  var Bt = "isResting",
    Ht = "thumbWidth",
    Nt = "thumbHeight",
    _t = "thumbClipWidth";
  var $t = /*#__PURE__*/function (_ref16) {
    _inherits($t, _ref16);
    var _super16 = _createSuper($t);
    function $t() {
      var _this42;
      _classCallCheck(this, $t);
      _this42 = _super16.apply(this, arguments), Object.defineProperty(_assertThisInitialized(_this42), "type", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: "modern"
      }), Object.defineProperty(_assertThisInitialized(_this42), "container", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this42), "track", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this42), "carousel", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this42), "thumbWidth", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: 0
      }), Object.defineProperty(_assertThisInitialized(_this42), "thumbClipWidth", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: 0
      }), Object.defineProperty(_assertThisInitialized(_this42), "thumbHeight", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: 0
      }), Object.defineProperty(_assertThisInitialized(_this42), "thumbGap", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: 0
      }), Object.defineProperty(_assertThisInitialized(_this42), "thumbExtraGap", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: 0
      }), Object.defineProperty(_assertThisInitialized(_this42), "state", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: jt.Init
      });
      return _this42;
    }
    _createClass($t, [{
      key: "isModern",
      get: function get() {
        return "modern" === this.type;
      }
    }, {
      key: "onInitSlide",
      value: function onInitSlide(t, e) {
        var i = e.el ? e.el.dataset : void 0;
        i && (e.thumbSrc = i.thumbSrc || e.thumbSrc || "", e[_t] = parseFloat(i[_t] || "") || e[_t] || 0, e[Nt] = parseFloat(i.thumbHeight || "") || e[Nt] || 0), this.addSlide(e);
      }
    }, {
      key: "onInitSlides",
      value: function onInitSlides() {
        this.build();
      }
    }, {
      key: "onChange",
      value: function onChange() {
        var t;
        if (!this.isModern) return;
        var e = this.container,
          i = this.instance,
          n = i.panzoom,
          s = this.carousel,
          o = s ? s.panzoom : null,
          r = i.page;
        if (n && s && o) {
          if (n.isDragging) {
            P(e, this.cn(Bt));
            var _n22 = (null === (t = s.pages[r]) || void 0 === t ? void 0 : t.pos) || 0;
            _n22 += i.getProgress(r) * (this[_t] + this.thumbGap);
            var _a8 = o.getBounds();
            -1 * _n22 > _a8.x.min && -1 * _n22 < _a8.x.max && o.panTo({
              x: -1 * _n22,
              friction: .12
            });
          } else a(e, this.cn(Bt), n.isResting);
          this.shiftModern();
        }
      }
    }, {
      key: "onRefresh",
      value: function onRefresh() {
        this.updateProps();
        var _iterator25 = _createForOfIteratorHelper(this.instance.slides || []),
          _step25;
        try {
          for (_iterator25.s(); !(_step25 = _iterator25.n()).done;) {
            var _t52 = _step25.value;
            this.resizeModernSlide(_t52);
          }
        } catch (err) {
          _iterator25.e(err);
        } finally {
          _iterator25.f();
        }
        this.shiftModern();
      }
    }, {
      key: "isDisabled",
      value: function isDisabled() {
        var t = this.option("minCount") || 0;
        if (t) {
          var _e47 = this.instance;
          var _i63 = 0;
          var _iterator26 = _createForOfIteratorHelper(_e47.slides || []),
            _step26;
          try {
            for (_iterator26.s(); !(_step26 = _iterator26.n()).done;) {
              var _t53 = _step26.value;
              _t53.thumbSrc && _i63++;
            }
          } catch (err) {
            _iterator26.e(err);
          } finally {
            _iterator26.f();
          }
          if (_i63 < t) return !0;
        }
        var e = this.option("type");
        return ["modern", "classic"].indexOf(e) < 0;
      }
    }, {
      key: "getThumb",
      value: function getThumb(t) {
        var e = this.option("thumbTpl") || "";
        return {
          html: this.instance.localize(e, [["%i", t.index], ["%d", t.index + 1], ["%s", t.thumbSrc || "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"]])
        };
      }
    }, {
      key: "addSlide",
      value: function addSlide(t) {
        var e = this.carousel;
        e && e.addSlide(t.index, this.getThumb(t));
      }
    }, {
      key: "getSlides",
      value: function getSlides() {
        var t = [];
        var _iterator27 = _createForOfIteratorHelper(this.instance.slides || []),
          _step27;
        try {
          for (_iterator27.s(); !(_step27 = _iterator27.n()).done;) {
            var _e48 = _step27.value;
            t.push(this.getThumb(_e48));
          }
        } catch (err) {
          _iterator27.e(err);
        } finally {
          _iterator27.f();
        }
        return t;
      }
    }, {
      key: "resizeModernSlide",
      value: function resizeModernSlide(t) {
        this.isModern && (t[Ht] = t[_t] && t[Nt] ? Math.round(this[Nt] * (t[_t] / t[Nt])) : this[Ht]);
      }
    }, {
      key: "updateProps",
      value: function updateProps() {
        var t = this.container;
        if (!t) return;
        var e = function e(_e49) {
          return parseFloat(getComputedStyle(t).getPropertyValue("--f-thumb-" + _e49)) || 0;
        };
        this.thumbGap = e("gap"), this.thumbExtraGap = e("extra-gap"), this[Ht] = e("width") || 40, this[_t] = e("clip-width") || 40, this[Nt] = e("height") || 40;
      }
    }, {
      key: "build",
      value: function build() {
        var t = this;
        if (t.state !== jt.Init) return;
        if (t.isDisabled()) return void t.emit("disabled");
        var e = t.instance,
          i = e.container,
          n = t.getSlides(),
          s = t.option("type");
        t.type = s;
        var o = t.option("parentEl"),
          a = t.cn("container"),
          r = t.cn("track");
        var l = null == o ? void 0 : o.querySelector("." + a);
        l || (l = document.createElement("div"), C(l, a), o ? o.appendChild(l) : i.after(l)), C(l, "is-".concat(s)), C(i, t.cn("hasThumbs")), t.container = l, t.updateProps();
        var c = l.querySelector("." + r);
        c || (c = document.createElement("div"), C(c, t.cn("track")), l.appendChild(c)), t.track = c;
        var h = p({}, {
            track: c,
            infinite: !1,
            center: !0,
            fill: "classic" === s,
            dragFree: !0,
            slidesPerPage: 1,
            transition: !1,
            preload: .25,
            friction: .12,
            Panzoom: {
              maxVelocity: 0
            },
            Dots: !1,
            Navigation: !1,
            classes: {
              container: "f-thumbs",
              viewport: "f-thumbs__viewport",
              track: "f-thumbs__track",
              slide: "f-thumbs__slide"
            }
          }, t.option("Carousel") || {}, {
            Sync: {
              target: e
            },
            slides: n
          }),
          d = new e.constructor(l, h);
        d.on("createSlide", function (e, i) {
          t.setProps(i.index), t.emit("createSlide", i, i.el);
        }), d.on("ready", function () {
          t.shiftModern(), t.emit("ready");
        }), d.on("refresh", function () {
          t.shiftModern();
        }), d.on("Panzoom.click", function (e, i, n) {
          t.onClick(n);
        }), t.carousel = d, t.state = jt.Ready;
      }
    }, {
      key: "onClick",
      value: function onClick(t) {
        t.preventDefault(), t.stopPropagation();
        var e = this.instance,
          i = e.pages,
          n = e.page,
          s = function s(t) {
            if (t) {
              var _e50 = t.closest("[data-carousel-index]");
              if (_e50) return [parseInt(_e50.dataset.carouselIndex || "", 10) || 0, _e50];
            }
            return [-1, void 0];
          },
          o = function o(t, e) {
            var i = document.elementFromPoint(t, e);
            return i ? s(i) : [-1, void 0];
          };
        var _s13 = s(t.target),
          _s14 = _slicedToArray(_s13, 2),
          a = _s14[0],
          r = _s14[1];
        if (a > -1) return;
        var l = this[_t],
          c = t.clientX,
          h = t.clientY;
        var _o9 = o(c - l, h),
          _o10 = _slicedToArray(_o9, 2),
          d = _o10[0],
          u = _o10[1],
          _o11 = o(c + l, h),
          _o12 = _slicedToArray(_o11, 2),
          p = _o12[0],
          f = _o12[1];
        u && f ? (a = Math.abs(c - u.getBoundingClientRect().right) < Math.abs(c - f.getBoundingClientRect().left) ? d : p, a === n && (a = a === d ? p : d)) : u ? a = d : f && (a = p), a > -1 && i[a] && e.slideTo(a);
      }
    }, {
      key: "getShift",
      value: function getShift(t) {
        var e;
        var i = this,
          n = i.instance,
          s = i.carousel;
        if (!n || !s) return 0;
        var o = i[Ht],
          a = i[_t],
          r = i.thumbGap,
          l = i.thumbExtraGap;
        if (!(null === (e = s.slides[t]) || void 0 === e ? void 0 : e.el)) return 0;
        var c = .5 * (o - a),
          h = n.pages.length - 1;
        var d = n.getProgress(0),
          u = n.getProgress(h),
          p = n.getProgress(t, !1, !0),
          f = 0,
          g = c + l + r;
        var m = d < 0 && d > -1,
          v = u > 0 && u < 1;
        return 0 === t ? (f = g * Math.abs(d), v && 1 === d && (f -= g * Math.abs(u))) : t === h ? (f = g * Math.abs(u) * -1, m && -1 === u && (f += g * Math.abs(d))) : m || v ? (f = -1 * g, f += g * Math.abs(d), f += g * (1 - Math.abs(u))) : f = g * p, f;
      }
    }, {
      key: "setProps",
      value: function setProps(t) {
        var i;
        var n = this;
        if (!n.isModern) return;
        var s = n.instance,
          o = n.carousel;
        if (s && o) {
          var _a9 = null === (i = o.slides[t]) || void 0 === i ? void 0 : i.el;
          if (_a9 && _a9.childNodes.length) {
            var _i64 = e(1 - Math.abs(s.getProgress(t))),
              _o13 = e(n.getShift(t));
            _a9.style.setProperty("--progress", _i64 ? _i64 + "" : ""), _a9.style.setProperty("--shift", _o13 + "");
          }
        }
      }
    }, {
      key: "shiftModern",
      value: function shiftModern() {
        var t = this;
        if (!t.isModern) return;
        var e = t.instance,
          i = t.track,
          n = e.panzoom,
          s = t.carousel;
        if (!(e && i && n && s)) return;
        if (n.state === v.Init || n.state === v.Destroy) return;
        var _iterator28 = _createForOfIteratorHelper(e.slides),
          _step28;
        try {
          for (_iterator28.s(); !(_step28 = _iterator28.n()).done;) {
            var _i65 = _step28.value;
            t.setProps(_i65.index);
          }
        } catch (err) {
          _iterator28.e(err);
        } finally {
          _iterator28.f();
        }
        var o = (t[_t] + t.thumbGap) * (s.slides.length || 0);
        i.style.setProperty("--width", o + "");
      }
    }, {
      key: "cleanup",
      value: function cleanup() {
        var t = this;
        t.carousel && t.carousel.destroy(), t.carousel = null, t.container && t.container.remove(), t.container = null, t.track && t.track.remove(), t.track = null, t.state = jt.Init, P(t.instance.container, t.cn("hasThumbs"));
      }
    }, {
      key: "attach",
      value: function attach() {
        var t = this,
          e = t.instance;
        e.on("initSlide", t.onInitSlide), e.state === B.Init ? e.on("initSlides", t.onInitSlides) : t.onInitSlides(), e.on(["change", "Panzoom.afterTransform"], t.onChange), e.on("Panzoom.refresh", t.onRefresh);
      }
    }, {
      key: "detach",
      value: function detach() {
        var t = this,
          e = t.instance;
        e.off("initSlide", t.onInitSlide), e.off("initSlides", t.onInitSlides), e.off(["change", "Panzoom.afterTransform"], t.onChange), e.off("Panzoom.refresh", t.onRefresh), t.cleanup();
      }
    }]);
    return $t;
  }(_);
  Object.defineProperty($t, "defaults", {
    enumerable: !0,
    configurable: !0,
    writable: !0,
    value: Ft
  });
  var Wt = Object.assign(Object.assign({}, Ft), {
      key: "t",
      showOnStart: !0,
      parentEl: null
    }),
    Xt = "is-masked",
    qt = "aria-hidden";
  var Yt = /*#__PURE__*/function (_ref17) {
    _inherits(Yt, _ref17);
    var _super17 = _createSuper(Yt);
    function Yt() {
      var _this43;
      _classCallCheck(this, Yt);
      _this43 = _super17.apply(this, arguments), Object.defineProperty(_assertThisInitialized(_this43), "ref", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this43), "hidden", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: !1
      });
      return _this43;
    }
    _createClass(Yt, [{
      key: "isEnabled",
      get: function get() {
        var t = this.ref;
        return t && !t.isDisabled();
      }
    }, {
      key: "isHidden",
      get: function get() {
        return this.hidden;
      }
    }, {
      key: "onClick",
      value: function onClick(t, e) {
        e.stopPropagation();
      }
    }, {
      key: "onCreateSlide",
      value: function onCreateSlide(t, e) {
        var i, n, s;
        var o = (null === (s = null === (n = null === (i = this.instance) || void 0 === i ? void 0 : i.carousel) || void 0 === n ? void 0 : n.slides[e.index]) || void 0 === s ? void 0 : s.type) || "",
          a = e.el;
        if (a && o) {
          var _t54 = "for-".concat(o);
          ["video", "youtube", "vimeo", "html5video"].includes(o) && (_t54 += " for-video"), C(a, _t54);
        }
      }
    }, {
      key: "onInit",
      value: function onInit() {
        var _this44 = this;
        var t;
        var e = this,
          i = e.instance,
          n = i.carousel;
        if (e.ref || !n) return;
        var s = e.option("parentEl") || i.footer || i.container;
        if (!s) return;
        var o = p({}, e.options, {
          parentEl: s,
          classes: {
            container: "f-thumbs fancybox__thumbs"
          },
          Carousel: {
            Sync: {
              friction: i.option("Carousel.friction") || 0
            }
          },
          on: {
            ready: function ready(t) {
              var i = t.container;
              i && _this44.hidden && (e.refresh(), i.style.transition = "none", e.hide(), i.offsetHeight, queueMicrotask(function () {
                i.style.transition = "", e.show();
              }));
            }
          }
        });
        o.Carousel = o.Carousel || {}, o.Carousel.on = p((null === (t = e.options.Carousel) || void 0 === t ? void 0 : t.on) || {}, {
          click: this.onClick,
          createSlide: this.onCreateSlide
        }), n.options.Thumbs = o, n.attachPlugins({
          Thumbs: $t
        }), e.ref = n.plugins.Thumbs, e.option("showOnStart") || (e.ref.state = jt.Hidden, e.hidden = !0);
      }
    }, {
      key: "onResize",
      value: function onResize() {
        var t;
        var e = null === (t = this.ref) || void 0 === t ? void 0 : t.container;
        e && (e.style.maxHeight = "");
      }
    }, {
      key: "onKeydown",
      value: function onKeydown(t, e) {
        var i = this.option("key");
        i && i === e && this.toggle();
      }
    }, {
      key: "toggle",
      value: function toggle() {
        var t = this.ref;
        if (t && !t.isDisabled()) return t.state === jt.Hidden ? (t.state = jt.Init, void t.build()) : void (this.hidden ? this.show() : this.hide());
      }
    }, {
      key: "show",
      value: function show() {
        var t = this.ref;
        if (!t || t.isDisabled()) return;
        var e = t.container;
        e && (this.refresh(), e.offsetHeight, e.removeAttribute(qt), e.classList.remove(Xt), this.hidden = !1);
      }
    }, {
      key: "hide",
      value: function hide() {
        var t = this.ref,
          e = t && t.container;
        e && (this.refresh(), e.offsetHeight, e.classList.add(Xt), e.setAttribute(qt, "true")), this.hidden = !0;
      }
    }, {
      key: "refresh",
      value: function refresh() {
        var t = this.ref;
        if (!t || !t.state) return;
        var e = t.container,
          i = (null == e ? void 0 : e.firstChild) || null;
        e && i && i.childNodes.length && (e.style.maxHeight = "".concat(i.getBoundingClientRect().height, "px"));
      }
    }, {
      key: "attach",
      value: function attach() {
        var t = this,
          e = t.instance;
        e.state === rt.Init ? e.on("Carousel.init", t.onInit) : t.onInit(), e.on("resize", t.onResize), e.on("keydown", t.onKeydown);
      }
    }, {
      key: "detach",
      value: function detach() {
        var t;
        var e = this,
          i = e.instance;
        i.off("Carousel.init", e.onInit), i.off("resize", e.onResize), i.off("keydown", e.onKeydown), null === (t = i.carousel) || void 0 === t || t.detachPlugins(["Thumbs"]), e.ref = null;
      }
    }]);
    return Yt;
  }(_);
  Object.defineProperty(Yt, "defaults", {
    enumerable: !0,
    configurable: !0,
    writable: !0,
    value: Wt
  });
  var Vt = {
    panLeft: {
      icon: '<svg><path d="M5 12h14M5 12l6 6M5 12l6-6"/></svg>',
      change: {
        panX: -100
      }
    },
    panRight: {
      icon: '<svg><path d="M5 12h14M13 18l6-6M13 6l6 6"/></svg>',
      change: {
        panX: 100
      }
    },
    panUp: {
      icon: '<svg><path d="M12 5v14M18 11l-6-6M6 11l6-6"/></svg>',
      change: {
        panY: -100
      }
    },
    panDown: {
      icon: '<svg><path d="M12 5v14M18 13l-6 6M6 13l6 6"/></svg>',
      change: {
        panY: 100
      }
    },
    zoomIn: {
      icon: '<svg><circle cx="11" cy="11" r="7.5"/><path d="m21 21-4.35-4.35M11 8v6M8 11h6"/></svg>',
      action: "zoomIn"
    },
    zoomOut: {
      icon: '<svg><circle cx="11" cy="11" r="7.5"/><path d="m21 21-4.35-4.35M8 11h6"/></svg>',
      action: "zoomOut"
    },
    toggle1to1: {
      icon: '<svg><path d="M3.51 3.07c5.74.02 11.48-.02 17.22.02 1.37.1 2.34 1.64 2.18 3.13 0 4.08.02 8.16 0 12.23-.1 1.54-1.47 2.64-2.79 2.46-5.61-.01-11.24.02-16.86-.01-1.36-.12-2.33-1.65-2.17-3.14 0-4.07-.02-8.16 0-12.23.1-1.36 1.22-2.48 2.42-2.46Z"/><path d="M5.65 8.54h1.49v6.92m8.94-6.92h1.49v6.92M11.5 9.4v.02m0 5.18v0"/></svg>',
      action: "toggleZoom"
    },
    toggleZoom: {
      icon: '<svg><g><line x1="11" y1="8" x2="11" y2="14"></line></g><circle cx="11" cy="11" r="7.5"/><path d="m21 21-4.35-4.35M8 11h6"/></svg>',
      action: "toggleZoom"
    },
    iterateZoom: {
      icon: '<svg><g><line x1="11" y1="8" x2="11" y2="14"></line></g><circle cx="11" cy="11" r="7.5"/><path d="m21 21-4.35-4.35M8 11h6"/></svg>',
      action: "iterateZoom"
    },
    rotateCCW: {
      icon: '<svg><path d="M15 4.55a8 8 0 0 0-6 14.9M9 15v5H4M18.37 7.16v.01M13 19.94v.01M16.84 18.37v.01M19.37 15.1v.01M19.94 11v.01"/></svg>',
      action: "rotateCCW"
    },
    rotateCW: {
      icon: '<svg><path d="M9 4.55a8 8 0 0 1 6 14.9M15 15v5h5M5.63 7.16v.01M4.06 11v.01M4.63 15.1v.01M7.16 18.37v.01M11 19.94v.01"/></svg>',
      action: "rotateCW"
    },
    flipX: {
      icon: '<svg style="stroke-width: 1.3"><path d="M12 3v18M16 7v10h5L16 7M8 7v10H3L8 7"/></svg>',
      action: "flipX"
    },
    flipY: {
      icon: '<svg style="stroke-width: 1.3"><path d="M3 12h18M7 16h10L7 21v-5M7 8h10L7 3v5"/></svg>',
      action: "flipY"
    },
    fitX: {
      icon: '<svg><path d="M4 12V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v6M10 18H3M21 18h-7M6 15l-3 3 3 3M18 15l3 3-3 3"/></svg>',
      action: "fitX"
    },
    fitY: {
      icon: '<svg><path d="M12 20H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h6M18 14v7M18 3v7M15 18l3 3 3-3M15 6l3-3 3 3"/></svg>',
      action: "fitY"
    },
    reset: {
      icon: '<svg><path d="M20 11A8.1 8.1 0 0 0 4.5 9M4 5v4h4M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>',
      action: "reset"
    },
    toggleFS: {
      icon: '<svg><g><path d="M14.5 9.5 21 3m0 0h-6m6 0v6M3 21l6.5-6.5M3 21v-6m0 6h6"/></g><g><path d="m14 10 7-7m-7 7h6m-6 0V4M3 21l7-7m0 0v6m0-6H4"/></g></svg>',
      action: "toggleFS"
    }
  };
  var Zt;
  !function (t) {
    t[t.Init = 0] = "Init", t[t.Ready = 1] = "Ready", t[t.Disabled = 2] = "Disabled";
  }(Zt || (Zt = {}));
  var Ut = {
      absolute: "auto",
      display: {
        left: ["infobar"],
        middle: [],
        right: ["iterateZoom", "slideshow", "fullscreen", "thumbs", "close"]
      },
      enabled: "auto",
      items: {
        infobar: {
          tpl: '<div class="fancybox__infobar" tabindex="-1"><span data-fancybox-current-index></span>/<span data-fancybox-count></span></div>'
        },
        download: {
          tpl: '<a class="f-button" title="{{DOWNLOAD}}" data-fancybox-download href="javasript:;"><svg><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2M7 11l5 5 5-5M12 4v12"/></svg></a>'
        },
        prev: {
          tpl: '<button class="f-button" title="{{PREV}}" data-fancybox-prev><svg><path d="m15 6-6 6 6 6"/></svg></button>'
        },
        next: {
          tpl: '<button class="f-button" title="{{NEXT}}" data-fancybox-next><svg><path d="m9 6 6 6-6 6"/></svg></button>'
        },
        slideshow: {
          tpl: '<button class="f-button" title="{{TOGGLE_SLIDESHOW}}" data-fancybox-toggle-slideshow><svg><g><path d="M8 4v16l13 -8z"></path></g><g><path d="M8 4v15M17 4v15"/></g></svg></button>'
        },
        fullscreen: {
          tpl: '<button class="f-button" title="{{TOGGLE_FULLSCREEN}}" data-fancybox-toggle-fullscreen><svg><g><path d="M4 8V6a2 2 0 0 1 2-2h2M4 16v2a2 2 0 0 0 2 2h2M16 4h2a2 2 0 0 1 2 2v2M16 20h2a2 2 0 0 0 2-2v-2"/></g><g><path d="M15 19v-2a2 2 0 0 1 2-2h2M15 5v2a2 2 0 0 0 2 2h2M5 15h2a2 2 0 0 1 2 2v2M5 9h2a2 2 0 0 0 2-2V5"/></g></svg></button>'
        },
        thumbs: {
          tpl: '<button class="f-button" title="{{TOGGLE_THUMBS}}" data-fancybox-toggle-thumbs><svg><circle cx="5.5" cy="5.5" r="1"/><circle cx="12" cy="5.5" r="1"/><circle cx="18.5" cy="5.5" r="1"/><circle cx="5.5" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="18.5" cy="12" r="1"/><circle cx="5.5" cy="18.5" r="1"/><circle cx="12" cy="18.5" r="1"/><circle cx="18.5" cy="18.5" r="1"/></svg></button>'
        },
        close: {
          tpl: '<button class="f-button" title="{{CLOSE}}" data-fancybox-close><svg><path d="m19.5 4.5-15 15M4.5 4.5l15 15"/></svg></button>'
        }
      },
      parentEl: null
    },
    Gt = {
      tabindex: "-1",
      width: "24",
      height: "24",
      viewBox: "0 0 24 24",
      xmlns: "http://www.w3.org/2000/svg"
    },
    Kt = "has-toolbar",
    Jt = "fancybox__toolbar";
  var Qt = /*#__PURE__*/function (_ref18) {
    _inherits(Qt, _ref18);
    var _super18 = _createSuper(Qt);
    function Qt() {
      var _this45;
      _classCallCheck(this, Qt);
      _this45 = _super18.apply(this, arguments), Object.defineProperty(_assertThisInitialized(_this45), "state", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: Zt.Init
      }), Object.defineProperty(_assertThisInitialized(_this45), "container", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      });
      return _this45;
    }
    _createClass(Qt, [{
      key: "onReady",
      value: function onReady(t) {
        var e;
        if (!t.carousel) return;
        var i = this.option("display"),
          n = this.option("absolute"),
          s = this.option("enabled");
        if ("auto" === s) {
          var _t55 = this.instance.carousel;
          var _e51 = 0;
          if (_t55) {
            var _iterator29 = _createForOfIteratorHelper(_t55.slides),
              _step29;
            try {
              for (_iterator29.s(); !(_step29 = _iterator29.n()).done;) {
                var _i66 = _step29.value;
                (_i66.panzoom || "image" === _i66.type) && _e51++;
              }
            } catch (err) {
              _iterator29.e(err);
            } finally {
              _iterator29.f();
            }
          }
          _e51 || (s = !1);
        }
        s || (i = void 0);
        var o = 0;
        var a = {
          left: [],
          middle: [],
          right: []
        };
        if (i) for (var _i67 = 0, _arr6 = ["left", "middle", "right"]; _i67 < _arr6.length; _i67++) {
          var _t56 = _arr6[_i67];
          var _iterator30 = _createForOfIteratorHelper(i[_t56]),
            _step30;
          try {
            for (_iterator30.s(); !(_step30 = _iterator30.n()).done;) {
              var _n23 = _step30.value;
              var _i68 = this.createEl(_n23);
              _i68 && (null === (e = a[_t56]) || void 0 === e || e.push(_i68), o++);
            }
          } catch (err) {
            _iterator30.e(err);
          } finally {
            _iterator30.f();
          }
        }
        var r = null;
        if (o && (r = this.createContainer()), r) {
          for (var _i69 = 0, _Object$entries6 = Object.entries(a); _i69 < _Object$entries6.length; _i69++) {
            var _Object$entries6$_i = _slicedToArray(_Object$entries6[_i69], 2),
              _t57 = _Object$entries6$_i[0],
              _e52 = _Object$entries6$_i[1];
            var _i70 = document.createElement("div");
            C(_i70, Jt + "__column is-" + _t57);
            var _iterator31 = _createForOfIteratorHelper(_e52),
              _step31;
            try {
              for (_iterator31.s(); !(_step31 = _iterator31.n()).done;) {
                var _t58 = _step31.value;
                _i70.appendChild(_t58);
              }
            } catch (err) {
              _iterator31.e(err);
            } finally {
              _iterator31.f();
            }
            "auto" !== n || "middle" !== _t57 || _e52.length || (n = !0), r.appendChild(_i70);
          }
          !0 === n && C(r, "is-absolute"), this.state = Zt.Ready, this.onRefresh();
        } else this.state = Zt.Disabled;
      }
    }, {
      key: "onClick",
      value: function onClick(t) {
        var e, i;
        var n = this.instance,
          s = n.getSlide(),
          o = null == s ? void 0 : s.panzoom,
          a = t.target,
          r = a && S(a) ? a.dataset : null;
        if (!r) return;
        if (void 0 !== r.fancyboxToggleThumbs) return t.preventDefault(), t.stopPropagation(), void (null === (e = n.plugins.Thumbs) || void 0 === e || e.toggle());
        if (void 0 !== r.fancyboxToggleFullscreen) return t.preventDefault(), t.stopPropagation(), void this.instance.toggleFullscreen();
        if (void 0 !== r.fancyboxToggleSlideshow) {
          t.preventDefault(), t.stopPropagation();
          var _e53 = null === (i = n.carousel) || void 0 === i ? void 0 : i.plugins.Autoplay;
          var _s15 = _e53.isActive;
          return o && "mousemove" === o.panMode && !_s15 && o.reset(), void (_s15 ? _e53.stop() : _e53.start());
        }
        var l = r.panzoomAction,
          c = r.panzoomChange;
        if ((c || l) && (t.preventDefault(), t.stopPropagation()), c) {
          var _t59 = {};
          try {
            _t59 = JSON.parse(c);
          } catch (t) {}
          o && o.applyChange(_t59);
        } else l && o && o[l] && o[l]();
      }
    }, {
      key: "onChange",
      value: function onChange() {
        this.onRefresh();
      }
    }, {
      key: "onRefresh",
      value: function onRefresh() {
        if (this.instance.isClosing()) return;
        var t = this.container;
        if (!t) return;
        var e = this.instance.getSlide();
        if (!e || e.state !== lt.Ready) return;
        var i = e && !e.error && e.panzoom;
        var _iterator32 = _createForOfIteratorHelper(t.querySelectorAll("[data-panzoom-action]")),
          _step32;
        try {
          for (_iterator32.s(); !(_step32 = _iterator32.n()).done;) {
            var _e54 = _step32.value;
            i ? (_e54.removeAttribute("disabled"), _e54.removeAttribute("tabindex")) : (_e54.setAttribute("disabled", ""), _e54.setAttribute("tabindex", "-1"));
          }
        } catch (err) {
          _iterator32.e(err);
        } finally {
          _iterator32.f();
        }
        var n = i && i.canZoomIn(),
          s = i && i.canZoomOut();
        var _iterator33 = _createForOfIteratorHelper(t.querySelectorAll('[data-panzoom-action="zoomIn"]')),
          _step33;
        try {
          for (_iterator33.s(); !(_step33 = _iterator33.n()).done;) {
            var _e55 = _step33.value;
            n ? (_e55.removeAttribute("disabled"), _e55.removeAttribute("tabindex")) : (_e55.setAttribute("disabled", ""), _e55.setAttribute("tabindex", "-1"));
          }
        } catch (err) {
          _iterator33.e(err);
        } finally {
          _iterator33.f();
        }
        var _iterator34 = _createForOfIteratorHelper(t.querySelectorAll('[data-panzoom-action="zoomOut"]')),
          _step34;
        try {
          for (_iterator34.s(); !(_step34 = _iterator34.n()).done;) {
            var _e56 = _step34.value;
            s ? (_e56.removeAttribute("disabled"), _e56.removeAttribute("tabindex")) : (_e56.setAttribute("disabled", ""), _e56.setAttribute("tabindex", "-1"));
          }
        } catch (err) {
          _iterator34.e(err);
        } finally {
          _iterator34.f();
        }
        var _iterator35 = _createForOfIteratorHelper(t.querySelectorAll('[data-panzoom-action="toggleZoom"],[data-panzoom-action="iterateZoom"]')),
          _step35;
        try {
          for (_iterator35.s(); !(_step35 = _iterator35.n()).done;) {
            var _e57 = _step35.value;
            s || n ? (_e57.removeAttribute("disabled"), _e57.removeAttribute("tabindex")) : (_e57.setAttribute("disabled", ""), _e57.setAttribute("tabindex", "-1"));
            var _t60 = _e57.querySelector("g");
            _t60 && (_t60.style.display = n ? "" : "none");
          }
        } catch (err) {
          _iterator35.e(err);
        } finally {
          _iterator35.f();
        }
      }
    }, {
      key: "onDone",
      value: function onDone(t, e) {
        var _this46 = this;
        var i;
        null === (i = e.panzoom) || void 0 === i || i.on("afterTransform", function () {
          _this46.instance.isCurrentSlide(e) && _this46.onRefresh();
        }), this.instance.isCurrentSlide(e) && this.onRefresh();
      }
    }, {
      key: "createContainer",
      value: function createContainer() {
        var t = this.instance.container;
        if (!t) return null;
        var e = this.option("parentEl") || t;
        var i = e.querySelector("." + Jt);
        return i || (i = document.createElement("div"), C(i, Jt), e.prepend(i)), i.addEventListener("click", this.onClick, {
          passive: !1,
          capture: !0
        }), t && C(t, Kt), this.container = i, i;
      }
    }, {
      key: "createEl",
      value: function createEl(t) {
        var _this47 = this;
        var e = this.instance,
          i = e.carousel;
        if (!i) return null;
        if ("toggleFS" === t) return null;
        if ("fullscreen" === t && !ot()) return null;
        var n = null;
        var o = i.slides.length || 0;
        var a = 0,
          r = 0;
        var _iterator36 = _createForOfIteratorHelper(i.slides),
          _step36;
        try {
          for (_iterator36.s(); !(_step36 = _iterator36.n()).done;) {
            var _t63 = _step36.value;
            (_t63.panzoom || "image" === _t63.type) && a++, ("image" === _t63.type || _t63.downloadSrc) && r++;
          }
        } catch (err) {
          _iterator36.e(err);
        } finally {
          _iterator36.f();
        }
        if (o < 2 && ["infobar", "prev", "next"].includes(t)) return n;
        if (void 0 !== Vt[t] && !a) return null;
        if ("download" === t && !r) return null;
        if ("thumbs" === t) {
          var _t61 = e.plugins.Thumbs;
          if (!_t61 || !_t61.isEnabled) return null;
        }
        if ("slideshow" === t) {
          if (!i.plugins.Autoplay || o < 2) return null;
        }
        if (void 0 !== Vt[t]) {
          var _e58 = Vt[t];
          n = document.createElement("button"), n.setAttribute("title", this.instance.localize("{{".concat(t.toUpperCase(), "}}"))), C(n, "f-button"), _e58.action && (n.dataset.panzoomAction = _e58.action), _e58.change && (n.dataset.panzoomChange = JSON.stringify(_e58.change)), n.appendChild(s(this.instance.localize(_e58.icon)));
        } else {
          var _e59 = (this.option("items") || [])[t];
          _e59 && (n = s(this.instance.localize(_e59.tpl)), "function" == typeof _e59.click && n.addEventListener("click", function (t) {
            t.preventDefault(), t.stopPropagation(), "function" == typeof _e59.click && _e59.click.call(_this47, _this47, t);
          }));
        }
        var l = null == n ? void 0 : n.querySelector("svg");
        if (l) for (var _i71 = 0, _Object$entries7 = Object.entries(Gt); _i71 < _Object$entries7.length; _i71++) {
          var _Object$entries7$_i = _slicedToArray(_Object$entries7[_i71], 2),
            _t62 = _Object$entries7$_i[0],
            _e60 = _Object$entries7$_i[1];
          l.getAttribute(_t62) || l.setAttribute(_t62, String(_e60));
        }
        return n;
      }
    }, {
      key: "removeContainer",
      value: function removeContainer() {
        var t = this.container;
        t && t.remove(), this.container = null, this.state = Zt.Disabled;
        var e = this.instance.container;
        e && P(e, Kt);
      }
    }, {
      key: "attach",
      value: function attach() {
        var t = this,
          e = t.instance;
        e.on("Carousel.initSlides", t.onReady), e.on("done", t.onDone), e.on(["reveal", "Carousel.change"], t.onChange), t.onReady(t.instance);
      }
    }, {
      key: "detach",
      value: function detach() {
        var t = this,
          e = t.instance;
        e.off("Carousel.initSlides", t.onReady), e.off("done", t.onDone), e.off(["reveal", "Carousel.change"], t.onChange), t.removeContainer();
      }
    }]);
    return Qt;
  }(_);
  Object.defineProperty(Qt, "defaults", {
    enumerable: !0,
    configurable: !0,
    writable: !0,
    value: Ut
  });
  var te = {
      Hash: /*#__PURE__*/function (_ref19) {
        _inherits(Hash, _ref19);
        var _super19 = _createSuper(Hash);
        function Hash() {
          _classCallCheck(this, Hash);
          return _super19.apply(this, arguments);
        }
        _createClass(Hash, [{
          key: "onReady",
          value: function onReady() {
            ht = !1;
          }
        }, {
          key: "onChange",
          value: function onChange(t) {
            ut && clearTimeout(ut);
            var _pt2 = pt(),
              e = _pt2.hash,
              _ft3 = ft(),
              i = _ft3.hash,
              n = t.isOpeningSlide(t.getSlide());
            n && (ct = i === e ? "" : i), e && e !== i && (ut = setTimeout(function () {
              try {
                if (t.state === rt.Ready) {
                  var _t64 = "replaceState";
                  n && !dt && (_t64 = "pushState", dt = !0), window.history[_t64]({}, document.title, window.location.pathname + window.location.search + e);
                }
              } catch (t) {}
            }, 300));
          }
        }, {
          key: "onClose",
          value: function onClose(t) {
            if (ut && clearTimeout(ut), !ht && dt) return dt = !1, ht = !1, void window.history.back();
            if (!ht) try {
              window.history.replaceState({}, document.title, window.location.pathname + window.location.search + (ct || ""));
            } catch (t) {}
          }
        }, {
          key: "attach",
          value: function attach() {
            var t = this.instance;
            t.on("ready", this.onReady), t.on(["Carousel.ready", "Carousel.change"], this.onChange), t.on("close", this.onClose);
          }
        }, {
          key: "detach",
          value: function detach() {
            var t = this.instance;
            t.off("ready", this.onReady), t.off(["Carousel.ready", "Carousel.change"], this.onChange), t.off("close", this.onClose);
          }
        }], [{
          key: "parseURL",
          value: function parseURL() {
            return ft();
          }
        }, {
          key: "startFromUrl",
          value: function startFromUrl() {
            gt();
          }
        }, {
          key: "destroy",
          value: function destroy() {
            window.removeEventListener("hashchange", vt, !1);
          }
        }]);
        return Hash;
      }(_),
      Html: Lt,
      Images: wt,
      Slideshow: Dt,
      Thumbs: Yt,
      Toolbar: Qt
    },
    ee = "with-fancybox",
    ie = "hide-scrollbar",
    ne = "--fancybox-scrollbar-compensate",
    se = "--fancybox-body-margin",
    oe = "aria-hidden",
    ae = "is-using-tab",
    re = "is-animated",
    le = "is-compact",
    ce = "is-loading",
    he = "is-opening",
    de = "has-caption",
    ue = "disabled",
    pe = "tabindex",
    fe = "download",
    ge = "href",
    me = "src",
    ve = function ve(t) {
      return "string" == typeof t;
    },
    be = function be() {
      var t = window.getSelection();
      return !!t && "Range" === t.type;
    };
  var ye,
    we = null,
    xe = null,
    Ee = 0,
    Se = 0;
  var Pe = new Map();
  var Ce = 0;
  var Te = /*#__PURE__*/function (_m4) {
    _inherits(Te, _m4);
    var _super20 = _createSuper(Te);
    function Te() {
      var _this48;
      var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
      var e = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
      var i = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
      _classCallCheck(this, Te);
      _this48 = _super20.call(this, e), Object.defineProperty(_assertThisInitialized(_this48), "userSlides", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: []
      }), Object.defineProperty(_assertThisInitialized(_this48), "userPlugins", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: {}
      }), Object.defineProperty(_assertThisInitialized(_this48), "idle", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: !1
      }), Object.defineProperty(_assertThisInitialized(_this48), "idleTimer", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this48), "clickTimer", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this48), "pwt", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: 0
      }), Object.defineProperty(_assertThisInitialized(_this48), "ignoreFocusChange", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: !1
      }), Object.defineProperty(_assertThisInitialized(_this48), "startedFs", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: !1
      }), Object.defineProperty(_assertThisInitialized(_this48), "state", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: rt.Init
      }), Object.defineProperty(_assertThisInitialized(_this48), "id", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: 0
      }), Object.defineProperty(_assertThisInitialized(_this48), "container", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this48), "caption", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this48), "footer", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this48), "carousel", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this48), "lastFocus", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: null
      }), Object.defineProperty(_assertThisInitialized(_this48), "prevMouseMoveEvent", {
        enumerable: !0,
        configurable: !0,
        writable: !0,
        value: void 0
      }), ye || (ye = ot()), _this48.id = e.id || ++Ce, Pe.set(_this48.id, _assertThisInitialized(_this48)), _this48.userSlides = t, _this48.userPlugins = i, queueMicrotask(function () {
        _this48.init();
      });
      return _this48;
    }
    _createClass(Te, [{
      key: "isIdle",
      get: function get() {
        return this.idle;
      }
    }, {
      key: "isCompact",
      get: function get() {
        return this.option("compact");
      }
    }, {
      key: "init",
      value: function init() {
        var _this49 = this;
        if (this.state === rt.Destroy) return;
        this.state = rt.Init, this.attachPlugins(Object.assign(Object.assign({}, Te.Plugins), this.userPlugins)), this.emit("init"), this.emit("attachPlugins"), !0 === this.option("hideScrollbar") && function () {
          if (!et) return;
          var t = document,
            e = t.body,
            i = t.documentElement;
          if (e.classList.contains(ie)) return;
          var n = window.innerWidth - i.getBoundingClientRect().width;
          var s = parseFloat(window.getComputedStyle(e).marginRight);
          n < 0 && (n = 0), i.style.setProperty(ne, "".concat(n, "px")), s && e.style.setProperty(se, "".concat(s, "px")), e.classList.add(ie);
        }(), this.initLayout(), this.scale();
        var t = function t() {
          _this49.initCarousel(_this49.userSlides), _this49.state = rt.Ready, _this49.attachEvents(), _this49.emit("ready"), setTimeout(function () {
            _this49.container && _this49.container.setAttribute(oe, "false");
          }, 16);
        };
        this.option("Fullscreen.autoStart") && ye && !ye.isFullscreen() ? ye.request().then(function () {
          _this49.startedFs = !0, t();
        })["catch"](function () {
          return t();
        }) : t();
      }
    }, {
      key: "initLayout",
      value: function initLayout() {
        var _this50 = this;
        var t, e;
        var i = this.option("parentEl") || document.body,
          n = s(this.localize(this.option("tpl.main") || ""));
        n && (n.setAttribute("id", "fancybox-".concat(this.id)), n.setAttribute("aria-label", this.localize("{{MODAL}}")), n.classList.toggle(le, this.isCompact), C(n, this.option("mainClass") || ""), C(n, he), this.container = n, this.footer = n.querySelector(".fancybox__footer"), i.appendChild(n), C(document.documentElement, ee), we && xe || (we = document.createElement("span"), C(we, "fancybox-focus-guard"), we.setAttribute(pe, "0"), we.setAttribute(oe, "true"), we.setAttribute("aria-label", "Focus guard"), xe = we.cloneNode(), null === (t = n.parentElement) || void 0 === t || t.insertBefore(we, n), null === (e = n.parentElement) || void 0 === e || e.append(xe)), n.addEventListener("mousedown", function (t) {
          Ee = t.pageX, Se = t.pageY, P(n, ae);
        }), this.option("animated") && (C(n, re), setTimeout(function () {
          _this50.isClosing() || P(n, re);
        }, 350)), this.emit("initLayout"));
      }
    }, {
      key: "initCarousel",
      value: function initCarousel(t) {
        var _this51 = this;
        var e = this.container;
        if (!e) return;
        var n = e.querySelector(".fancybox__carousel");
        if (!n) return;
        var s = this.carousel = new Q(n, p({}, {
          slides: t,
          transition: "fade",
          Panzoom: {
            lockAxis: this.option("dragToClose") ? "xy" : "x",
            infinite: !!this.option("dragToClose") && "y"
          },
          Dots: !1,
          Navigation: {
            classes: {
              container: "fancybox__nav",
              button: "f-button",
              isNext: "is-next",
              isPrev: "is-prev"
            }
          },
          initialPage: this.option("startIndex"),
          l10n: this.option("l10n")
        }, this.option("Carousel") || {}));
        s.on("*", function (t, e) {
          for (var _len5 = arguments.length, i = new Array(_len5 > 2 ? _len5 - 2 : 0), _key5 = 2; _key5 < _len5; _key5++) {
            i[_key5 - 2] = arguments[_key5];
          }
          _this51.emit.apply(_this51, ["Carousel.".concat(e), t].concat(i));
        }), s.on(["ready", "change"], function () {
          _this51.manageCaption();
        }), this.on("Carousel.removeSlide", function (t, e, i) {
          _this51.clearContent(i), i.state = void 0;
        }), s.on("Panzoom.touchStart", function () {
          var t, e;
          _this51.isCompact || _this51.endIdle(), (null === (t = document.activeElement) || void 0 === t ? void 0 : t.closest(".f-thumbs")) && (null === (e = _this51.container) || void 0 === e || e.focus());
        }), s.on("settle", function () {
          _this51.idleTimer || _this51.isCompact || !_this51.option("idle") || _this51.setIdle(), _this51.option("autoFocus") && !_this51.isClosing && _this51.checkFocus();
        }), this.option("dragToClose") && (s.on("Panzoom.afterTransform", function (t, e) {
          var n = _this51.getSlide();
          if (n && i(n.el)) return;
          var s = _this51.container;
          if (s) {
            var _t65 = Math.abs(e.current.f),
              _i72 = _t65 < 1 ? "" : Math.max(.5, Math.min(1, 1 - _t65 / e.contentRect.fitHeight * 1.5));
            s.style.setProperty("--fancybox-ts", _i72 ? "0s" : ""), s.style.setProperty("--fancybox-opacity", _i72 + "");
          }
        }), s.on("Panzoom.touchEnd", function (t, e, n) {
          var s;
          var o = _this51.getSlide();
          if (o && i(o.el)) return;
          if (e.isMobile && document.activeElement && -1 !== ["TEXTAREA", "INPUT"].indexOf(null === (s = document.activeElement) || void 0 === s ? void 0 : s.nodeName)) return;
          var a = Math.abs(e.dragOffset.y);
          "y" === e.lockedAxis && (a >= 200 || a >= 50 && e.dragOffset.time < 300) && (n && n.cancelable && n.preventDefault(), _this51.close(n, "f-throwOut" + (e.current.f < 0 ? "Up" : "Down")));
        })), s.on("change", function (t) {
          var e;
          var i = null === (e = _this51.getSlide()) || void 0 === e ? void 0 : e.triggerEl;
          if (i) {
            var _e61 = new CustomEvent("slideTo", {
              bubbles: !0,
              cancelable: !0,
              detail: t.page
            });
            i.dispatchEvent(_e61);
          }
        }), s.on(["refresh", "change"], function (t) {
          var e = _this51.container;
          if (!e) return;
          var _iterator37 = _createForOfIteratorHelper(e.querySelectorAll("[data-fancybox-current-index]")),
            _step37;
          try {
            for (_iterator37.s(); !(_step37 = _iterator37.n()).done;) {
              var _i75 = _step37.value;
              _i75.innerHTML = t.page + 1;
            }
          } catch (err) {
            _iterator37.e(err);
          } finally {
            _iterator37.f();
          }
          var _iterator38 = _createForOfIteratorHelper(e.querySelectorAll("[data-fancybox-count]")),
            _step38;
          try {
            for (_iterator38.s(); !(_step38 = _iterator38.n()).done;) {
              var _i76 = _step38.value;
              _i76.innerHTML = t.pages.length;
            }
          } catch (err) {
            _iterator38.e(err);
          } finally {
            _iterator38.f();
          }
          if (!t.isInfinite) {
            var _iterator39 = _createForOfIteratorHelper(e.querySelectorAll("[data-fancybox-next]")),
              _step39;
            try {
              for (_iterator39.s(); !(_step39 = _iterator39.n()).done;) {
                var _i73 = _step39.value;
                t.page < t.pages.length - 1 ? (_i73.removeAttribute(ue), _i73.removeAttribute(pe)) : (_i73.setAttribute(ue, ""), _i73.setAttribute(pe, "-1"));
              }
            } catch (err) {
              _iterator39.e(err);
            } finally {
              _iterator39.f();
            }
            var _iterator40 = _createForOfIteratorHelper(e.querySelectorAll("[data-fancybox-prev]")),
              _step40;
            try {
              for (_iterator40.s(); !(_step40 = _iterator40.n()).done;) {
                var _i74 = _step40.value;
                t.page > 0 ? (_i74.removeAttribute(ue), _i74.removeAttribute(pe)) : (_i74.setAttribute(ue, ""), _i74.setAttribute(pe, "-1"));
              }
            } catch (err) {
              _iterator40.e(err);
            } finally {
              _iterator40.f();
            }
          }
          var i = _this51.getSlide();
          if (!i) return;
          var n = i.downloadSrc || "";
          n || "image" !== i.type || i.error || !ve(i[me]) || (n = i[me]);
          var _iterator41 = _createForOfIteratorHelper(e.querySelectorAll("[data-fancybox-download]")),
            _step41;
          try {
            for (_iterator41.s(); !(_step41 = _iterator41.n()).done;) {
              var _t66 = _step41.value;
              var _e62 = i.downloadFilename;
              n ? (_t66.removeAttribute(ue), _t66.removeAttribute(pe), _t66.setAttribute(ge, n), _t66.setAttribute(fe, _e62 || n), _t66.setAttribute("target", "_blank")) : (_t66.setAttribute(ue, ""), _t66.setAttribute(pe, "-1"), _t66.removeAttribute(ge), _t66.removeAttribute(fe));
            }
          } catch (err) {
            _iterator41.e(err);
          } finally {
            _iterator41.f();
          }
        }), this.emit("initCarousel");
      }
    }, {
      key: "attachEvents",
      value: function attachEvents() {
        var t = this,
          e = t.container;
        if (!e) return;
        e.addEventListener("click", t.onClick, {
          passive: !1,
          capture: !1
        }), e.addEventListener("wheel", t.onWheel, {
          passive: !1,
          capture: !1
        }), document.addEventListener("keydown", t.onKeydown, {
          passive: !1,
          capture: !0
        }), document.addEventListener("visibilitychange", t.onVisibilityChange, !1), document.addEventListener("mousemove", t.onMousemove), t.option("trapFocus") && document.addEventListener("focus", t.onFocus, !0), window.addEventListener("resize", t.onResize);
        var i = window.visualViewport;
        i && (i.addEventListener("scroll", t.onResize), i.addEventListener("resize", t.onResize));
      }
    }, {
      key: "detachEvents",
      value: function detachEvents() {
        var t = this,
          e = t.container;
        if (!e) return;
        document.removeEventListener("keydown", t.onKeydown, {
          passive: !1,
          capture: !0
        }), e.removeEventListener("wheel", t.onWheel, {
          passive: !1,
          capture: !1
        }), e.removeEventListener("click", t.onClick, {
          passive: !1,
          capture: !1
        }), document.removeEventListener("mousemove", t.onMousemove), window.removeEventListener("resize", t.onResize);
        var i = window.visualViewport;
        i && (i.removeEventListener("resize", t.onResize), i.removeEventListener("scroll", t.onResize)), document.removeEventListener("visibilitychange", t.onVisibilityChange, !1), document.removeEventListener("focus", t.onFocus, !0);
      }
    }, {
      key: "scale",
      value: function scale() {
        var t = this.container;
        if (!t) return;
        var e = window.visualViewport,
          i = Math.max(1, (null == e ? void 0 : e.scale) || 1);
        var n = "",
          s = "",
          o = "";
        if (e && i > 1) {
          var _t67 = "".concat(e.offsetLeft, "px"),
            _a10 = "".concat(e.offsetTop, "px");
          n = e.width * i + "px", s = e.height * i + "px", o = "translate3d(".concat(_t67, ", ").concat(_a10, ", 0) scale(").concat(1 / i, ")");
        }
        t.style.transform = o, t.style.width = n, t.style.height = s;
      }
    }, {
      key: "onClick",
      value: function onClick(t) {
        var _this52 = this;
        var e;
        var i = this.container,
          n = this.isCompact;
        if (!i || this.isClosing()) return;
        !n && this.option("idle") && this.resetIdle();
        var s = t.composedPath()[0];
        if (s.closest(".fancybox-spinner") || s.closest("[data-fancybox-close]")) return t.preventDefault(), void this.close(t);
        if (s.closest("[data-fancybox-prev]")) return t.preventDefault(), void this.prev();
        if (s.closest("[data-fancybox-next]")) return t.preventDefault(), void this.next();
        if ("click" === t.type && 0 === t.detail) return;
        if (Math.abs(t.pageX - Ee) > 30 || Math.abs(t.pageY - Se) > 30) return;
        var o = document.activeElement;
        if (be() && o && i.contains(o)) return;
        if (n && "image" === (null === (e = this.getSlide()) || void 0 === e ? void 0 : e.type)) return void (this.clickTimer ? (clearTimeout(this.clickTimer), this.clickTimer = null) : this.clickTimer = setTimeout(function () {
          _this52.toggleIdle(), _this52.clickTimer = null;
        }, 350));
        if (this.emit("click", t), t.defaultPrevented) return;
        var a = !1;
        if (s.closest(".fancybox__content")) {
          if (o) {
            if (o.closest("[contenteditable]")) return;
            s.matches(nt) || o.blur();
          }
          if (be()) return;
          a = this.option("contentClick");
        } else s.closest(".fancybox__carousel") && !s.matches(nt) && (a = this.option("backdropClick"));
        "close" === a ? (t.preventDefault(), this.close(t)) : "next" === a ? (t.preventDefault(), this.next()) : "prev" === a && (t.preventDefault(), this.prev());
      }
    }, {
      key: "onWheel",
      value: function onWheel(t) {
        var e = t.target;
        var i = this.option("wheel", t);
        e.closest(".fancybox__thumbs") && (i = "slide");
        var s = "slide" === i,
          o = [-t.deltaX || 0, -t.deltaY || 0, -t.detail || 0].reduce(function (t, e) {
            return Math.abs(e) > Math.abs(t) ? e : t;
          }),
          a = Math.max(-1, Math.min(1, o)),
          r = Date.now();
        this.pwt && r - this.pwt < 300 ? s && t.preventDefault() : (this.pwt = r, this.emit("wheel", t, a), t.defaultPrevented || ("close" === i ? (t.preventDefault(), this.close(t)) : "slide" === i && (n(e) || (t.preventDefault(), this[a > 0 ? "prev" : "next"]()))));
      }
    }, {
      key: "onKeydown",
      value: function onKeydown(t) {
        if (!this.isTopmost()) return;
        this.isCompact || !this.option("idle") || this.isClosing() || this.resetIdle();
        var e = t.key,
          i = this.option("keyboard");
        if (!i) return;
        var n = t.composedPath()[0],
          s = document.activeElement && document.activeElement.classList,
          o = s && s.contains("f-button") || n.dataset.carouselPage || n.dataset.carouselIndex;
        if ("Escape" !== e && !o && S(n)) {
          if (n.isContentEditable || -1 !== ["TEXTAREA", "OPTION", "INPUT", "SELECT", "VIDEO"].indexOf(n.nodeName)) return;
        }
        if ("Tab" === t.key ? C(this.container, ae) : P(this.container, ae), t.ctrlKey || t.altKey || t.shiftKey) return;
        this.emit("keydown", e, t);
        var a = i[e];
        a && "function" == typeof this[a] && (t.preventDefault(), this[a]());
      }
    }, {
      key: "onResize",
      value: function onResize() {
        var t = this.container;
        if (!t) return;
        var e = this.isCompact;
        t.classList.toggle(le, e), this.manageCaption(this.getSlide()), this.isCompact ? this.clearIdle() : this.endIdle(), this.scale(), this.emit("resize");
      }
    }, {
      key: "onFocus",
      value: function onFocus(t) {
        this.isTopmost() && this.checkFocus(t);
      }
    }, {
      key: "onMousemove",
      value: function onMousemove(t) {
        this.prevMouseMoveEvent = t, !this.isCompact && this.option("idle") && this.resetIdle();
      }
    }, {
      key: "onVisibilityChange",
      value: function onVisibilityChange() {
        "visible" === document.visibilityState ? this.checkFocus() : this.endIdle();
      }
    }, {
      key: "manageCloseBtn",
      value: function manageCloseBtn(t) {
        var e = this.optionFor(t, "closeButton") || !1;
        if ("auto" === e) {
          var _t68 = this.plugins.Toolbar;
          if (_t68 && _t68.state === Zt.Ready) return;
        }
        if (!e) return;
        if (!t.contentEl || t.closeBtnEl) return;
        var i = this.option("tpl.closeButton");
        if (i) {
          var _e63 = s(this.localize(i));
          t.closeBtnEl = t.contentEl.appendChild(_e63), t.el && C(t.el, "has-close-btn");
        }
      }
    }, {
      key: "manageCaption",
      value: function manageCaption() {
        var _this53 = this;
        var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : void 0;
        var e, i;
        var n = "fancybox__caption",
          s = this.container;
        if (!s) return;
        P(s, de);
        var o = this.isCompact || this.option("commonCaption"),
          a = !o;
        if (this.caption && this.stop(this.caption), a && this.caption && (this.caption.remove(), this.caption = null), o && !this.caption) {
          var _iterator42 = _createForOfIteratorHelper((null === (e = this.carousel) || void 0 === e ? void 0 : e.slides) || []),
            _step42;
          try {
            for (_iterator42.s(); !(_step42 = _iterator42.n()).done;) {
              var _t69 = _step42.value;
              _t69.captionEl && (_t69.captionEl.remove(), _t69.captionEl = void 0, P(_t69.el, de), null === (i = _t69.el) || void 0 === i || i.removeAttribute("aria-labelledby"));
            }
          } catch (err) {
            _iterator42.e(err);
          } finally {
            _iterator42.f();
          }
        }
        if (t || (t = this.getSlide()), !t || o && !this.isCurrentSlide(t)) return;
        var r = t.el;
        var l = this.optionFor(t, "caption", "");
        if (!l) return void (o && this.caption && this.animate(this.caption, "f-fadeOut", function () {
          _this53.caption && (_this53.caption.innerHTML = "");
        }));
        var c = null;
        if (a) {
          if (c = t.captionEl || null, r && !c) {
            var _e64 = n + "_".concat(this.id, "_").concat(t.index);
            c = document.createElement("div"), C(c, n), c.setAttribute("id", _e64), t.captionEl = r.appendChild(c), C(r, de), r.setAttribute("aria-labelledby", _e64);
          }
        } else {
          if (c = this.caption, c || (c = s.querySelector("." + n)), !c) {
            c = document.createElement("div"), c.dataset.fancyboxCaption = "", C(c, n);
            (this.footer || s).prepend(c);
          }
          C(s, de), this.caption = c;
        }
        c && (c.innerHTML = "", ve(l) || "number" == typeof l ? c.innerHTML = l + "" : l instanceof HTMLElement && c.appendChild(l));
      }
    }, {
      key: "checkFocus",
      value: function checkFocus(t) {
        this.focus(t);
      }
    }, {
      key: "focus",
      value: function focus(t) {
        var e;
        if (this.ignoreFocusChange) return;
        var i = document.activeElement || null,
          n = (null == t ? void 0 : t.target) || null,
          s = this.container,
          o = null === (e = this.carousel) || void 0 === e ? void 0 : e.viewport;
        if (!s || !o) return;
        if (!t && i && s.contains(i)) return;
        var a = this.getSlide(),
          r = a && a.state === lt.Ready ? a.el : null;
        if (!r || r.contains(i) || s === i) return;
        t && t.cancelable && t.preventDefault(), this.ignoreFocusChange = !0;
        var l = Array.from(s.querySelectorAll(nt));
        var c = [],
          h = null;
        for (var _i77 = 0, _l5 = l; _i77 < _l5.length; _i77++) {
          var _t70 = _l5[_i77];
          var _e65 = !_t70.offsetParent || !!_t70.closest('[aria-hidden="true"]'),
            _i78 = r && r.contains(_t70),
            _n24 = !o.contains(_t70);
          if (_t70 === s || (_i78 || _n24) && !_e65) {
            c.push(_t70);
            var _e66 = _t70.dataset.origTabindex;
            void 0 !== _e66 && _e66 && (_t70.tabIndex = parseFloat(_e66)), _t70.removeAttribute("data-orig-tabindex"), !_t70.hasAttribute("autoFocus") && h || (h = _t70);
          } else {
            var _e67 = void 0 === _t70.dataset.origTabindex ? _t70.getAttribute("tabindex") || "" : _t70.dataset.origTabindex;
            _e67 && (_t70.dataset.origTabindex = _e67), _t70.tabIndex = -1;
          }
        }
        var d = null;
        t ? (!n || c.indexOf(n) < 0) && (d = h || s, c.length && (i === xe ? d = c[0] : this.lastFocus !== s && i !== we || (d = c[c.length - 1]))) : d = a && "image" === a.type ? s : h || s, d && st(d), this.lastFocus = document.activeElement, this.ignoreFocusChange = !1;
      }
    }, {
      key: "next",
      value: function next() {
        var t = this.carousel;
        t && t.pages.length > 1 && t.slideNext();
      }
    }, {
      key: "prev",
      value: function prev() {
        var t = this.carousel;
        t && t.pages.length > 1 && t.slidePrev();
      }
    }, {
      key: "jumpTo",
      value: function jumpTo() {
        var _this$carousel;
        this.carousel && (_this$carousel = this.carousel).slideTo.apply(_this$carousel, arguments);
      }
    }, {
      key: "isTopmost",
      value: function isTopmost() {
        var t;
        return (null === (t = Te.getInstance()) || void 0 === t ? void 0 : t.id) == this.id;
      }
    }, {
      key: "animate",
      value: function animate() {
        var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
        var e = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : "";
        var i = arguments.length > 2 ? arguments[2] : undefined;
        if (!t || !e) return void (i && i());
        this.stop(t);
        var n = function n(s) {
          s.target === t && t.dataset.animationName && (t.removeEventListener("animationend", n), delete t.dataset.animationName, i && i(), P(t, e));
        };
        t.dataset.animationName = e, t.addEventListener("animationend", n), C(t, e);
      }
    }, {
      key: "stop",
      value: function stop(t) {
        t && t.dispatchEvent(new CustomEvent("animationend", {
          bubbles: !1,
          cancelable: !0,
          currentTarget: t
        }));
      }
    }, {
      key: "setContent",
      value: function setContent(t) {
        var e = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : "";
        var i = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : !0;
        if (this.isClosing()) return;
        var n = t.el;
        if (!n) return;
        var o = null;
        if (S(e) ? o = e : (o = s(e + ""), S(o) || (o = document.createElement("div"), o.innerHTML = e + "")), ["img", "picture", "iframe", "video", "audio"].includes(o.nodeName.toLowerCase())) {
          var _t71 = document.createElement("div");
          _t71.appendChild(o), o = _t71;
        }
        S(o) && t.filter && !t.error && (o = o.querySelector(t.filter)), o && S(o) ? (C(o, "fancybox__content"), t.id && o.setAttribute("id", t.id), "none" !== o.style.display && "none" !== getComputedStyle(o).getPropertyValue("display") || (o.style.display = t.display || this.option("defaultDisplay") || "flex"), n.classList.add("has-".concat(t.error ? "error" : t.type || "unknown")), n.prepend(o), t.contentEl = o, i && this.revealContent(t), this.manageCloseBtn(t), this.manageCaption(t)) : this.setError(t, "{{ELEMENT_NOT_FOUND}}");
      }
    }, {
      key: "revealContent",
      value: function revealContent(t, e) {
        var _this54 = this;
        var i = t.el,
          n = t.contentEl;
        i && n && (this.emit("reveal", t), this.hideLoading(t), t.state = lt.Opening, (e = this.isOpeningSlide(t) ? void 0 === e ? this.optionFor(t, "showClass") : e : "f-fadeIn") ? this.animate(n, e, function () {
          _this54.done(t);
        }) : this.done(t));
      }
    }, {
      key: "done",
      value: function done(t) {
        var _this55 = this;
        this.isClosing() || (t.state = lt.Ready, this.emit("done", t), C(t.el, "is-done"), this.isCurrentSlide(t) && this.option("autoFocus") && queueMicrotask(function () {
          var e;
          null === (e = t.panzoom) || void 0 === e || e.updateControls(), _this55.option("autoFocus") && _this55.focus();
        }), this.isOpeningSlide(t) && (P(this.container, he), !this.isCompact && this.option("idle") && this.setIdle()));
      }
    }, {
      key: "isCurrentSlide",
      value: function isCurrentSlide(t) {
        var e = this.getSlide();
        return !(!t || !e) && e.index === t.index;
      }
    }, {
      key: "isOpeningSlide",
      value: function isOpeningSlide(t) {
        var e, i;
        return null === (null === (e = this.carousel) || void 0 === e ? void 0 : e.prevPage) && t && t.index === (null === (i = this.getSlide()) || void 0 === i ? void 0 : i.index);
      }
    }, {
      key: "showLoading",
      value: function showLoading(t) {
        var _this56 = this;
        t.state = lt.Loading;
        var e = t.el;
        if (!e) return;
        C(e, ce), this.emit("loading", t), t.spinnerEl || setTimeout(function () {
          if (!_this56.isClosing() && !t.spinnerEl && t.state === lt.Loading) {
            var _i79 = s(E);
            C(_i79, "fancybox-spinner"), t.spinnerEl = _i79, e.prepend(_i79), _this56.animate(_i79, "f-fadeIn");
          }
        }, 250);
      }
    }, {
      key: "hideLoading",
      value: function hideLoading(t) {
        var e = t.el;
        if (!e) return;
        var i = t.spinnerEl;
        this.isClosing() ? null == i || i.remove() : (P(e, ce), i && this.animate(i, "f-fadeOut", function () {
          i.remove();
        }), t.state === lt.Loading && (this.emit("loaded", t), t.state = lt.Ready));
      }
    }, {
      key: "setError",
      value: function setError(t, e) {
        if (this.isClosing()) return;
        var i = new Event("error", {
          bubbles: !0,
          cancelable: !0
        });
        if (this.emit("error", i, t), i.defaultPrevented) return;
        t.error = e, this.hideLoading(t), this.clearContent(t);
        var n = document.createElement("div");
        n.classList.add("fancybox-error"), n.innerHTML = this.localize(e || "<p>{{ERROR}}</p>"), this.setContent(t, n);
      }
    }, {
      key: "clearContent",
      value: function clearContent(t) {
        if (void 0 === t.state) return;
        this.emit("clearContent", t), t.contentEl && (t.contentEl.remove(), t.contentEl = void 0);
        var e = t.el;
        e && (P(e, "has-error"), P(e, "has-unknown"), P(e, "has-".concat(t.type || "unknown"))), t.closeBtnEl && t.closeBtnEl.remove(), t.closeBtnEl = void 0, t.captionEl && t.captionEl.remove(), t.captionEl = void 0, t.spinnerEl && t.spinnerEl.remove(), t.spinnerEl = void 0;
      }
    }, {
      key: "getSlide",
      value: function getSlide() {
        var t;
        var e = this.carousel;
        return (null === (t = null == e ? void 0 : e.pages[null == e ? void 0 : e.page]) || void 0 === t ? void 0 : t.slides[0]) || void 0;
      }
    }, {
      key: "close",
      value: function close(t, e) {
        var _this57 = this;
        if (this.isClosing()) return;
        var i = new Event("shouldClose", {
          bubbles: !0,
          cancelable: !0
        });
        if (this.emit("shouldClose", i, t), i.defaultPrevented) return;
        t && t.cancelable && (t.preventDefault(), t.stopPropagation());
        var n = function n() {
          _this57.proceedClose(t, e);
        };
        this.startedFs && ye && ye.isFullscreen() ? Promise.resolve(ye.exit()).then(function () {
          return n();
        }) : n();
      }
    }, {
      key: "clearIdle",
      value: function clearIdle() {
        this.idleTimer && clearTimeout(this.idleTimer), this.idleTimer = null;
      }
    }, {
      key: "setIdle",
      value: function setIdle() {
        var _this58 = this;
        var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : !1;
        var e = function e() {
          _this58.clearIdle(), _this58.idle = !0, C(_this58.container, "is-idle"), _this58.emit("setIdle");
        };
        if (this.clearIdle(), !this.isClosing()) if (t) e();else {
          var _t72 = this.option("idle");
          _t72 && (this.idleTimer = setTimeout(e, _t72));
        }
      }
    }, {
      key: "endIdle",
      value: function endIdle() {
        this.clearIdle(), this.idle && !this.isClosing() && (this.idle = !1, P(this.container, "is-idle"), this.emit("endIdle"));
      }
    }, {
      key: "resetIdle",
      value: function resetIdle() {
        this.endIdle(), this.setIdle();
      }
    }, {
      key: "toggleIdle",
      value: function toggleIdle() {
        this.idle ? this.endIdle() : this.setIdle(!0);
      }
    }, {
      key: "toggleFullscreen",
      value: function toggleFullscreen() {
        var _this59 = this;
        ye && (ye.isFullscreen() ? ye.exit() : ye.request().then(function () {
          _this59.startedFs = !0;
        }));
      }
    }, {
      key: "isClosing",
      value: function isClosing() {
        return [rt.Closing, rt.CustomClosing, rt.Destroy].includes(this.state);
      }
    }, {
      key: "proceedClose",
      value: function proceedClose(t, e) {
        var _this60 = this;
        var i, n;
        this.state = rt.Closing, this.clearIdle(), this.detachEvents();
        var s = this.container,
          o = this.carousel,
          a = this.getSlide(),
          r = a && this.option("placeFocusBack") ? a.triggerEl || this.option("triggerEl") : null;
        if (r && (tt(r) ? st(r) : r.focus()), s && (P(s, he), C(s, "is-closing"), s.setAttribute(oe, "true"), this.option("animated") && C(s, re), s.style.pointerEvents = "none"), o) {
          o.clearTransitions(), null === (i = o.panzoom) || void 0 === i || i.destroy(), null === (n = o.plugins.Navigation) || void 0 === n || n.detach();
          var _iterator43 = _createForOfIteratorHelper(o.slides),
            _step43;
          try {
            for (_iterator43.s(); !(_step43 = _iterator43.n()).done;) {
              var _t73 = _step43.value;
              _t73.state = lt.Closing, this.hideLoading(_t73);
              var _e68 = _t73.contentEl;
              _e68 && this.stop(_e68);
              var _i80 = null == _t73 ? void 0 : _t73.panzoom;
              _i80 && (_i80.stop(), _i80.detachEvents(), _i80.detachObserver()), this.isCurrentSlide(_t73) || o.emit("removeSlide", _t73);
            }
          } catch (err) {
            _iterator43.e(err);
          } finally {
            _iterator43.f();
          }
        }
        this.emit("close", t), this.state !== rt.CustomClosing ? (void 0 === e && a && (e = this.optionFor(a, "hideClass")), e && a ? (this.animate(a.contentEl, e, function () {
          o && o.emit("removeSlide", a);
        }), setTimeout(function () {
          _this60.destroy();
        }, 500)) : this.destroy()) : setTimeout(function () {
          _this60.destroy();
        }, 500);
      }
    }, {
      key: "destroy",
      value: function destroy() {
        var t;
        if (this.state === rt.Destroy) return;
        this.state = rt.Destroy, null === (t = this.carousel) || void 0 === t || t.destroy();
        var e = this.container;
        e && e.remove(), Pe["delete"](this.id);
        var i = Te.getInstance();
        i ? i.focus() : (we && (we.remove(), we = null), xe && (xe.remove(), xe = null), P(document.documentElement, ee), function () {
          if (!et) return;
          var t = document,
            e = t.body;
          e.classList.remove(ie), e.style.setProperty(se, ""), t.documentElement.style.setProperty(ne, "");
        }(), this.emit("destroy"));
      }
    }], [{
      key: "bind",
      value: function bind(t, e, i) {
        if (!et) return;
        var n,
          s = "",
          o = {};
        if (void 0 === t ? n = document.body : ve(t) ? (n = document.body, s = t, "object" == _typeof(e) && (o = e || {})) : (n = t, ve(e) && (s = e), "object" == _typeof(i) && (o = i || {})), !n || !S(n)) return;
        s = s || "[data-fancybox]";
        var a = Te.openers.get(n) || new Map();
        a.set(s, o), Te.openers.set(n, a), 1 === a.size && n.addEventListener("click", Te.fromEvent);
      }
    }, {
      key: "unbind",
      value: function unbind(t, e) {
        var i,
          n = "";
        if (ve(t) ? (i = document.body, n = t) : (i = t, ve(e) && (n = e)), !i) return;
        var s = Te.openers.get(i);
        s && n && s["delete"](n), n && s || (Te.openers["delete"](i), i.removeEventListener("click", Te.fromEvent));
      }
    }, {
      key: "destroy",
      value: function destroy() {
        var t;
        for (; t = Te.getInstance();) t.destroy();
        var _iterator44 = _createForOfIteratorHelper(Te.openers.keys()),
          _step44;
        try {
          for (_iterator44.s(); !(_step44 = _iterator44.n()).done;) {
            var _t74 = _step44.value;
            _t74.removeEventListener("click", Te.fromEvent);
          }
        } catch (err) {
          _iterator44.e(err);
        } finally {
          _iterator44.f();
        }
        Te.openers = new Map();
      }
    }, {
      key: "fromEvent",
      value: function fromEvent(t) {
        if (t.defaultPrevented) return;
        if (t.button && 0 !== t.button) return;
        if (t.ctrlKey || t.metaKey || t.shiftKey) return;
        var e = t.composedPath()[0];
        var i = e.closest("[data-fancybox-trigger]");
        if (i) {
          var _t75 = i.dataset.fancyboxTrigger || "",
            _n25 = document.querySelectorAll("[data-fancybox=\"".concat(_t75, "\"]")),
            _s16 = parseInt(i.dataset.fancyboxIndex || "", 10) || 0;
          e = _n25[_s16] || e;
        }
        if (!(e && e instanceof Element)) return;
        var n, s, o, a;
        if (_toConsumableArray(Te.openers).reverse().find(function (_ref20) {
          var _ref21 = _slicedToArray(_ref20, 2),
            t = _ref21[0],
            i = _ref21[1];
          return !(!t.contains(e) || !_toConsumableArray(i).reverse().find(function (_ref22) {
            var _ref23 = _slicedToArray(_ref22, 2),
              i = _ref23[0],
              r = _ref23[1];
            var l = e.closest(i);
            return !!l && (n = t, s = i, o = l, a = r, !0);
          }));
        }), !n || !s || !o) return;
        a = a || {}, t.preventDefault(), e = o;
        var r = [],
          l = p({}, at, a);
        l.event = t, l.triggerEl = e, l.delegate = i;
        var c = l.groupAll,
          h = l.groupAttr,
          d = h && e ? e.getAttribute("".concat(h)) : "";
        if ((!e || d || c) && (r = [].slice.call(n.querySelectorAll(s))), e && !c && (r = d ? r.filter(function (t) {
          return t.getAttribute("".concat(h)) === d;
        }) : [e]), !r.length) return;
        var u = Te.getInstance();
        return u && u.options.triggerEl && r.indexOf(u.options.triggerEl) > -1 ? void 0 : (e && (l.startIndex = r.indexOf(e)), Te.fromNodes(r, l));
      }
    }, {
      key: "fromSelector",
      value: function fromSelector(t, e, i) {
        var n = null,
          s = "",
          o = {};
        if (ve(t) ? (n = document.body, s = t, "object" == _typeof(e) && (o = e || {})) : t instanceof HTMLElement && ve(e) && (n = t, s = e, "object" == _typeof(i) && (o = i || {})), !n || !s) return !1;
        var a = Te.openers.get(n);
        return !!a && (o = p({}, a.get(s) || {}, o), !!o && Te.fromNodes(Array.from(n.querySelectorAll(s)), o));
      }
    }, {
      key: "fromNodes",
      value: function fromNodes(t, e) {
        e = p({}, at, e || {});
        var i = [];
        var _iterator45 = _createForOfIteratorHelper(t),
          _step45;
        try {
          for (_iterator45.s(); !(_step45 = _iterator45.n()).done;) {
            var _n26 = _step45.value;
            var _t76 = _n26.dataset || {},
              _s17 = _t76[me] || _n26.getAttribute(ge) || _n26.getAttribute("currentSrc") || _n26.getAttribute(me) || void 0;
            var _o14 = void 0;
            var _a11 = e.delegate;
            var _r4 = void 0;
            _a11 && i.length === e.startIndex && (_o14 = _a11 instanceof HTMLImageElement ? _a11 : _a11.querySelector("img:not([aria-hidden])")), _o14 || (_o14 = _n26 instanceof HTMLImageElement ? _n26 : _n26.querySelector("img:not([aria-hidden])")), _o14 && (_r4 = _o14.currentSrc || _o14[me] || void 0, !_r4 && _o14.dataset && (_r4 = _o14.dataset.lazySrc || _o14.dataset[me] || void 0));
            var _l6 = {
              src: _s17,
              triggerEl: _n26,
              thumbEl: _o14,
              thumbElSrc: _r4,
              thumbSrc: _r4
            };
            for (var _e69 in _t76) {
              var _i81 = _t76[_e69] + "";
              _i81 = "false" !== _i81 && ("true" === _i81 || _i81), _l6[_e69] = _i81;
            }
            i.push(_l6);
          }
        } catch (err) {
          _iterator45.e(err);
        } finally {
          _iterator45.f();
        }
        return new Te(i, e);
      }
    }, {
      key: "getInstance",
      value: function getInstance(t) {
        if (t) return Pe.get(t);
        return Array.from(Pe.values()).reverse().find(function (t) {
          return !t.isClosing() && t;
        }) || null;
      }
    }, {
      key: "getSlide",
      value: function getSlide() {
        var t;
        return (null === (t = Te.getInstance()) || void 0 === t ? void 0 : t.getSlide()) || null;
      }
    }, {
      key: "show",
      value: function show() {
        var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
        var e = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
        return new Te(t, e);
      }
    }, {
      key: "next",
      value: function next() {
        var t = Te.getInstance();
        t && t.next();
      }
    }, {
      key: "prev",
      value: function prev() {
        var t = Te.getInstance();
        t && t.prev();
      }
    }, {
      key: "close",
      value: function close() {
        var t = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : !0;
        for (var _len6 = arguments.length, e = new Array(_len6 > 1 ? _len6 - 1 : 0), _key6 = 1; _key6 < _len6; _key6++) {
          e[_key6 - 1] = arguments[_key6];
        }
        if (t) {
          var _iterator46 = _createForOfIteratorHelper(Pe.values()),
            _step46;
          try {
            for (_iterator46.s(); !(_step46 = _iterator46.n()).done;) {
              var _t77 = _step46.value;
              _t77.close.apply(_t77, e);
            }
          } catch (err) {
            _iterator46.e(err);
          } finally {
            _iterator46.f();
          }
        } else {
          var _t78 = Te.getInstance();
          _t78 && _t78.close.apply(_t78, e);
        }
      }
    }]);
    return Te;
  }(m);
  Object.defineProperty(Te, "version", {
    enumerable: !0,
    configurable: !0,
    writable: !0,
    value: "5.0.33"
  }), Object.defineProperty(Te, "defaults", {
    enumerable: !0,
    configurable: !0,
    writable: !0,
    value: at
  }), Object.defineProperty(Te, "Plugins", {
    enumerable: !0,
    configurable: !0,
    writable: !0,
    value: te
  }), Object.defineProperty(Te, "openers", {
    enumerable: !0,
    configurable: !0,
    writable: !0,
    value: new Map()
  }), t.Carousel = Q, t.Fancybox = Te, t.Panzoom = I;
});

/*
Waypoints - 4.0.1
Copyright © 2011-2016 Caleb Troughton
Licensed under the MIT license.
https://github.com/imakewebthings/waypoints/blob/master/licenses.txt
*/
(function () {
  'use strict';

  var keyCounter = 0;
  var allWaypoints = {};

  /* http://imakewebthings.com/waypoints/api/waypoint */
  function Waypoint(options) {
    if (!options) {
      throw new Error('No options passed to Waypoint constructor');
    }
    if (!options.element) {
      throw new Error('No element option passed to Waypoint constructor');
    }
    if (!options.handler) {
      throw new Error('No handler option passed to Waypoint constructor');
    }
    this.key = 'waypoint-' + keyCounter;
    this.options = Waypoint.Adapter.extend({}, Waypoint.defaults, options);
    this.element = this.options.element;
    this.adapter = new Waypoint.Adapter(this.element);
    this.callback = options.handler;
    this.axis = this.options.horizontal ? 'horizontal' : 'vertical';
    this.enabled = this.options.enabled;
    this.triggerPoint = null;
    this.group = Waypoint.Group.findOrCreate({
      name: this.options.group,
      axis: this.axis
    });
    this.context = Waypoint.Context.findOrCreateByElement(this.options.context);
    if (Waypoint.offsetAliases[this.options.offset]) {
      this.options.offset = Waypoint.offsetAliases[this.options.offset];
    }
    this.group.add(this);
    this.context.add(this);
    allWaypoints[this.key] = this;
    keyCounter += 1;
  }

  /* Private */
  Waypoint.prototype.queueTrigger = function (direction) {
    this.group.queueTrigger(this, direction);
  };

  /* Private */
  Waypoint.prototype.trigger = function (args) {
    if (!this.enabled) {
      return;
    }
    if (this.callback) {
      this.callback.apply(this, args);
    }
  };

  /* Public */
  /* http://imakewebthings.com/waypoints/api/destroy */
  Waypoint.prototype.destroy = function () {
    this.context.remove(this);
    this.group.remove(this);
    delete allWaypoints[this.key];
  };

  /* Public */
  /* http://imakewebthings.com/waypoints/api/disable */
  Waypoint.prototype.disable = function () {
    this.enabled = false;
    return this;
  };

  /* Public */
  /* http://imakewebthings.com/waypoints/api/enable */
  Waypoint.prototype.enable = function () {
    this.context.refresh();
    this.enabled = true;
    return this;
  };

  /* Public */
  /* http://imakewebthings.com/waypoints/api/next */
  Waypoint.prototype.next = function () {
    return this.group.next(this);
  };

  /* Public */
  /* http://imakewebthings.com/waypoints/api/previous */
  Waypoint.prototype.previous = function () {
    return this.group.previous(this);
  };

  /* Private */
  Waypoint.invokeAll = function (method) {
    var allWaypointsArray = [];
    for (var waypointKey in allWaypoints) {
      allWaypointsArray.push(allWaypoints[waypointKey]);
    }
    for (var i = 0, end = allWaypointsArray.length; i < end; i++) {
      allWaypointsArray[i][method]();
    }
  };

  /* Public */
  /* http://imakewebthings.com/waypoints/api/destroy-all */
  Waypoint.destroyAll = function () {
    Waypoint.invokeAll('destroy');
  };

  /* Public */
  /* http://imakewebthings.com/waypoints/api/disable-all */
  Waypoint.disableAll = function () {
    Waypoint.invokeAll('disable');
  };

  /* Public */
  /* http://imakewebthings.com/waypoints/api/enable-all */
  Waypoint.enableAll = function () {
    Waypoint.Context.refreshAll();
    for (var waypointKey in allWaypoints) {
      allWaypoints[waypointKey].enabled = true;
    }
    return this;
  };

  /* Public */
  /* http://imakewebthings.com/waypoints/api/refresh-all */
  Waypoint.refreshAll = function () {
    Waypoint.Context.refreshAll();
  };

  /* Public */
  /* http://imakewebthings.com/waypoints/api/viewport-height */
  Waypoint.viewportHeight = function () {
    return window.innerHeight || document.documentElement.clientHeight;
  };

  /* Public */
  /* http://imakewebthings.com/waypoints/api/viewport-width */
  Waypoint.viewportWidth = function () {
    return document.documentElement.clientWidth;
  };
  Waypoint.adapters = [];
  Waypoint.defaults = {
    context: window,
    continuous: true,
    enabled: true,
    group: 'default',
    horizontal: false,
    offset: 0
  };
  Waypoint.offsetAliases = {
    'bottom-in-view': function bottomInView() {
      return this.context.innerHeight() - this.adapter.outerHeight();
    },
    'right-in-view': function rightInView() {
      return this.context.innerWidth() - this.adapter.outerWidth();
    }
  };
  window.Waypoint = Waypoint;
})();
(function () {
  'use strict';

  function requestAnimationFrameShim(callback) {
    window.setTimeout(callback, 1000 / 60);
  }
  var keyCounter = 0;
  var contexts = {};
  var Waypoint = window.Waypoint;
  var oldWindowLoad = window.onload;

  /* http://imakewebthings.com/waypoints/api/context */
  function Context(element) {
    this.element = element;
    this.Adapter = Waypoint.Adapter;
    this.adapter = new this.Adapter(element);
    this.key = 'waypoint-context-' + keyCounter;
    this.didScroll = false;
    this.didResize = false;
    this.oldScroll = {
      x: this.adapter.scrollLeft(),
      y: this.adapter.scrollTop()
    };
    this.waypoints = {
      vertical: {},
      horizontal: {}
    };
    element.waypointContextKey = this.key;
    contexts[element.waypointContextKey] = this;
    keyCounter += 1;
    if (!Waypoint.windowContext) {
      Waypoint.windowContext = true;
      Waypoint.windowContext = new Context(window);
    }
    this.createThrottledScrollHandler();
    this.createThrottledResizeHandler();
  }

  /* Private */
  Context.prototype.add = function (waypoint) {
    var axis = waypoint.options.horizontal ? 'horizontal' : 'vertical';
    this.waypoints[axis][waypoint.key] = waypoint;
    this.refresh();
  };

  /* Private */
  Context.prototype.checkEmpty = function () {
    var horizontalEmpty = this.Adapter.isEmptyObject(this.waypoints.horizontal);
    var verticalEmpty = this.Adapter.isEmptyObject(this.waypoints.vertical);
    var isWindow = this.element == this.element.window;
    if (horizontalEmpty && verticalEmpty && !isWindow) {
      this.adapter.off('.waypoints');
      delete contexts[this.key];
    }
  };

  /* Private */
  Context.prototype.createThrottledResizeHandler = function () {
    var self = this;
    function resizeHandler() {
      self.handleResize();
      self.didResize = false;
    }
    this.adapter.on('resize.waypoints', function () {
      if (!self.didResize) {
        self.didResize = true;
        Waypoint.requestAnimationFrame(resizeHandler);
      }
    });
  };

  /* Private */
  Context.prototype.createThrottledScrollHandler = function () {
    var self = this;
    function scrollHandler() {
      self.handleScroll();
      self.didScroll = false;
    }
    this.adapter.on('scroll.waypoints', function () {
      if (!self.didScroll || Waypoint.isTouch) {
        self.didScroll = true;
        Waypoint.requestAnimationFrame(scrollHandler);
      }
    });
  };

  /* Private */
  Context.prototype.handleResize = function () {
    Waypoint.Context.refreshAll();
  };

  /* Private */
  Context.prototype.handleScroll = function () {
    var triggeredGroups = {};
    var axes = {
      horizontal: {
        newScroll: this.adapter.scrollLeft(),
        oldScroll: this.oldScroll.x,
        forward: 'right',
        backward: 'left'
      },
      vertical: {
        newScroll: this.adapter.scrollTop(),
        oldScroll: this.oldScroll.y,
        forward: 'down',
        backward: 'up'
      }
    };
    for (var axisKey in axes) {
      var axis = axes[axisKey];
      var isForward = axis.newScroll > axis.oldScroll;
      var direction = isForward ? axis.forward : axis.backward;
      for (var waypointKey in this.waypoints[axisKey]) {
        var waypoint = this.waypoints[axisKey][waypointKey];
        if (waypoint.triggerPoint === null) {
          continue;
        }
        var wasBeforeTriggerPoint = axis.oldScroll < waypoint.triggerPoint;
        var nowAfterTriggerPoint = axis.newScroll >= waypoint.triggerPoint;
        var crossedForward = wasBeforeTriggerPoint && nowAfterTriggerPoint;
        var crossedBackward = !wasBeforeTriggerPoint && !nowAfterTriggerPoint;
        if (crossedForward || crossedBackward) {
          waypoint.queueTrigger(direction);
          triggeredGroups[waypoint.group.id] = waypoint.group;
        }
      }
    }
    for (var groupKey in triggeredGroups) {
      triggeredGroups[groupKey].flushTriggers();
    }
    this.oldScroll = {
      x: axes.horizontal.newScroll,
      y: axes.vertical.newScroll
    };
  };

  /* Private */
  Context.prototype.innerHeight = function () {
    /*eslint-disable eqeqeq */
    if (this.element == this.element.window) {
      return Waypoint.viewportHeight();
    }
    /*eslint-enable eqeqeq */
    return this.adapter.innerHeight();
  };

  /* Private */
  Context.prototype.remove = function (waypoint) {
    delete this.waypoints[waypoint.axis][waypoint.key];
    this.checkEmpty();
  };

  /* Private */
  Context.prototype.innerWidth = function () {
    /*eslint-disable eqeqeq */
    if (this.element == this.element.window) {
      return Waypoint.viewportWidth();
    }
    /*eslint-enable eqeqeq */
    return this.adapter.innerWidth();
  };

  /* Public */
  /* http://imakewebthings.com/waypoints/api/context-destroy */
  Context.prototype.destroy = function () {
    var allWaypoints = [];
    for (var axis in this.waypoints) {
      for (var waypointKey in this.waypoints[axis]) {
        allWaypoints.push(this.waypoints[axis][waypointKey]);
      }
    }
    for (var i = 0, end = allWaypoints.length; i < end; i++) {
      allWaypoints[i].destroy();
    }
  };

  /* Public */
  /* http://imakewebthings.com/waypoints/api/context-refresh */
  Context.prototype.refresh = function () {
    /*eslint-disable eqeqeq */
    var isWindow = this.element == this.element.window;
    /*eslint-enable eqeqeq */
    var contextOffset = isWindow ? undefined : this.adapter.offset();
    var triggeredGroups = {};
    var axes;
    this.handleScroll();
    axes = {
      horizontal: {
        contextOffset: isWindow ? 0 : contextOffset.left,
        contextScroll: isWindow ? 0 : this.oldScroll.x,
        contextDimension: this.innerWidth(),
        oldScroll: this.oldScroll.x,
        forward: 'right',
        backward: 'left',
        offsetProp: 'left'
      },
      vertical: {
        contextOffset: isWindow ? 0 : contextOffset.top,
        contextScroll: isWindow ? 0 : this.oldScroll.y,
        contextDimension: this.innerHeight(),
        oldScroll: this.oldScroll.y,
        forward: 'down',
        backward: 'up',
        offsetProp: 'top'
      }
    };
    for (var axisKey in axes) {
      var axis = axes[axisKey];
      for (var waypointKey in this.waypoints[axisKey]) {
        var waypoint = this.waypoints[axisKey][waypointKey];
        var adjustment = waypoint.options.offset;
        var oldTriggerPoint = waypoint.triggerPoint;
        var elementOffset = 0;
        var freshWaypoint = oldTriggerPoint == null;
        var contextModifier, wasBeforeScroll, nowAfterScroll;
        var triggeredBackward, triggeredForward;
        if (waypoint.element !== waypoint.element.window) {
          elementOffset = waypoint.adapter.offset()[axis.offsetProp];
        }
        if (typeof adjustment === 'function') {
          adjustment = adjustment.apply(waypoint);
        } else if (typeof adjustment === 'string') {
          adjustment = parseFloat(adjustment);
          if (waypoint.options.offset.indexOf('%') > -1) {
            adjustment = Math.ceil(axis.contextDimension * adjustment / 100);
          }
        }
        contextModifier = axis.contextScroll - axis.contextOffset;
        waypoint.triggerPoint = Math.floor(elementOffset + contextModifier - adjustment);
        wasBeforeScroll = oldTriggerPoint < axis.oldScroll;
        nowAfterScroll = waypoint.triggerPoint >= axis.oldScroll;
        triggeredBackward = wasBeforeScroll && nowAfterScroll;
        triggeredForward = !wasBeforeScroll && !nowAfterScroll;
        if (!freshWaypoint && triggeredBackward) {
          waypoint.queueTrigger(axis.backward);
          triggeredGroups[waypoint.group.id] = waypoint.group;
        } else if (!freshWaypoint && triggeredForward) {
          waypoint.queueTrigger(axis.forward);
          triggeredGroups[waypoint.group.id] = waypoint.group;
        } else if (freshWaypoint && axis.oldScroll >= waypoint.triggerPoint) {
          waypoint.queueTrigger(axis.forward);
          triggeredGroups[waypoint.group.id] = waypoint.group;
        }
      }
    }
    Waypoint.requestAnimationFrame(function () {
      for (var groupKey in triggeredGroups) {
        triggeredGroups[groupKey].flushTriggers();
      }
    });
    return this;
  };

  /* Private */
  Context.findOrCreateByElement = function (element) {
    return Context.findByElement(element) || new Context(element);
  };

  /* Private */
  Context.refreshAll = function () {
    for (var contextId in contexts) {
      contexts[contextId].refresh();
    }
  };

  /* Public */
  /* http://imakewebthings.com/waypoints/api/context-find-by-element */
  Context.findByElement = function (element) {
    return contexts[element.waypointContextKey];
  };
  window.onload = function () {
    if (oldWindowLoad) {
      oldWindowLoad();
    }
    Context.refreshAll();
  };
  Waypoint.requestAnimationFrame = function (callback) {
    var requestFn = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || requestAnimationFrameShim;
    requestFn.call(window, callback);
  };
  Waypoint.Context = Context;
})();
(function () {
  'use strict';

  function byTriggerPoint(a, b) {
    return a.triggerPoint - b.triggerPoint;
  }
  function byReverseTriggerPoint(a, b) {
    return b.triggerPoint - a.triggerPoint;
  }
  var groups = {
    vertical: {},
    horizontal: {}
  };
  var Waypoint = window.Waypoint;

  /* http://imakewebthings.com/waypoints/api/group */
  function Group(options) {
    this.name = options.name;
    this.axis = options.axis;
    this.id = this.name + '-' + this.axis;
    this.waypoints = [];
    this.clearTriggerQueues();
    groups[this.axis][this.name] = this;
  }

  /* Private */
  Group.prototype.add = function (waypoint) {
    this.waypoints.push(waypoint);
  };

  /* Private */
  Group.prototype.clearTriggerQueues = function () {
    this.triggerQueues = {
      up: [],
      down: [],
      left: [],
      right: []
    };
  };

  /* Private */
  Group.prototype.flushTriggers = function () {
    for (var direction in this.triggerQueues) {
      var waypoints = this.triggerQueues[direction];
      var reverse = direction === 'up' || direction === 'left';
      waypoints.sort(reverse ? byReverseTriggerPoint : byTriggerPoint);
      for (var i = 0, end = waypoints.length; i < end; i += 1) {
        var waypoint = waypoints[i];
        if (waypoint.options.continuous || i === waypoints.length - 1) {
          waypoint.trigger([direction]);
        }
      }
    }
    this.clearTriggerQueues();
  };

  /* Private */
  Group.prototype.next = function (waypoint) {
    this.waypoints.sort(byTriggerPoint);
    var index = Waypoint.Adapter.inArray(waypoint, this.waypoints);
    var isLast = index === this.waypoints.length - 1;
    return isLast ? null : this.waypoints[index + 1];
  };

  /* Private */
  Group.prototype.previous = function (waypoint) {
    this.waypoints.sort(byTriggerPoint);
    var index = Waypoint.Adapter.inArray(waypoint, this.waypoints);
    return index ? this.waypoints[index - 1] : null;
  };

  /* Private */
  Group.prototype.queueTrigger = function (waypoint, direction) {
    this.triggerQueues[direction].push(waypoint);
  };

  /* Private */
  Group.prototype.remove = function (waypoint) {
    var index = Waypoint.Adapter.inArray(waypoint, this.waypoints);
    if (index > -1) {
      this.waypoints.splice(index, 1);
    }
  };

  /* Public */
  /* http://imakewebthings.com/waypoints/api/first */
  Group.prototype.first = function () {
    return this.waypoints[0];
  };

  /* Public */
  /* http://imakewebthings.com/waypoints/api/last */
  Group.prototype.last = function () {
    return this.waypoints[this.waypoints.length - 1];
  };

  /* Private */
  Group.findOrCreate = function (options) {
    return groups[options.axis][options.name] || new Group(options);
  };
  Waypoint.Group = Group;
})();
(function () {
  'use strict';

  var $ = window.jQuery;
  var Waypoint = window.Waypoint;
  function JQueryAdapter(element) {
    this.$element = $(element);
  }
  $.each(['innerHeight', 'innerWidth', 'off', 'offset', 'on', 'outerHeight', 'outerWidth', 'scrollLeft', 'scrollTop'], function (i, method) {
    JQueryAdapter.prototype[method] = function () {
      var args = Array.prototype.slice.call(arguments);
      return this.$element[method].apply(this.$element, args);
    };
  });
  $.each(['extend', 'inArray', 'isEmptyObject'], function (i, method) {
    JQueryAdapter[method] = $[method];
  });
  Waypoint.adapters.push({
    name: 'jquery',
    Adapter: JQueryAdapter
  });
  Waypoint.Adapter = JQueryAdapter;
})();
(function () {
  'use strict';

  var Waypoint = window.Waypoint;
  function createExtension(framework) {
    return function () {
      var waypoints = [];
      var overrides = arguments[0];
      if (framework.isFunction(arguments[0])) {
        overrides = framework.extend({}, arguments[1]);
        overrides.handler = arguments[0];
      }
      this.each(function () {
        var options = framework.extend({}, overrides, {
          element: this
        });
        if (typeof options.context === 'string') {
          options.context = framework(this).closest(options.context)[0];
        }
        waypoints.push(new Waypoint(options));
      });
      return waypoints;
    };
  }
  if (window.jQuery) {
    window.jQuery.fn.waypoint = createExtension(window.jQuery);
  }
  if (window.Zepto) {
    window.Zepto.fn.waypoint = createExtension(window.Zepto);
  }
})();
var tns = function () {
  var raf = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.msRequestAnimationFrame || function (cb) {
    return setTimeout(cb, 16);
  };
  // -----------------------------------------------------------------------------------------------------------------------

  // 'use strict';

  // Object.defineProperty(exports, '__esModule', { value: true });

  var win = window;
  var caf = win.cancelAnimationFrame || win.mozCancelAnimationFrame || function (id) {
    clearTimeout(id);
  };
  function extend() {
    var obj,
      name,
      copy,
      target = arguments[0] || {},
      i = 1,
      length = arguments.length;
    for (; i < length; i++) {
      if ((obj = arguments[i]) !== null) {
        for (name in obj) {
          copy = obj[name];
          if (target === copy) {
            continue;
          } else if (copy !== undefined) {
            target[name] = copy;
          }
        }
      }
    }
    return target;
  }
  function checkStorageValue(value) {
    return ['true', 'false'].indexOf(value) >= 0 ? JSON.parse(value) : value;
  }
  function setLocalStorage(storage, key, value, access) {
    if (access) {
      try {
        storage.setItem(key, value);
      } catch (e) {}
    }
    return value;
  }
  function getSlideId() {
    var id = window.tnsId;
    window.tnsId = !id ? 1 : id + 1;
    return 'tns' + window.tnsId;
  }
  function getBody() {
    var doc = document,
      body = doc.body;
    if (!body) {
      body = doc.createElement('body');
      body.fake = true;
    }
    return body;
  }
  var docElement = document.documentElement;
  function setFakeBody(body) {
    var docOverflow = '';
    if (body.fake) {
      docOverflow = docElement.style.overflow; //avoid crashing IE8, if background image is used

      body.style.background = ''; //Safari 5.13/5.1.4 OSX stops loading if ::-webkit-scrollbar is used and scrollbars are visible

      body.style.overflow = docElement.style.overflow = 'hidden';
      docElement.appendChild(body);
    }
    return docOverflow;
  }
  function resetFakeBody(body, docOverflow) {
    if (body.fake) {
      body.remove();
      docElement.style.overflow = docOverflow; // Trigger layout so kinetic scrolling isn't disabled in iOS6+
      // eslint-disable-next-line

      docElement.offsetHeight;
    }
  }

  // get css-calc
  function calc() {
    var doc = document,
      body = getBody(),
      docOverflow = setFakeBody(body),
      div = doc.createElement('div'),
      result = false;
    body.appendChild(div);
    try {
      var str = '(10px * 10)',
        vals = ['calc' + str, '-moz-calc' + str, '-webkit-calc' + str],
        val;
      for (var i = 0; i < 3; i++) {
        val = vals[i];
        div.style.width = val;
        if (div.offsetWidth === 100) {
          result = val.replace(str, '');
          break;
        }
      }
    } catch (e) {}
    body.fake ? resetFakeBody(body, docOverflow) : div.remove();
    return result;
  }

  // get subpixel support value
  function percentageLayout() {
    // check subpixel layout supporting
    var doc = document,
      body = getBody(),
      docOverflow = setFakeBody(body),
      wrapper = doc.createElement('div'),
      outer = doc.createElement('div'),
      str = '',
      count = 70,
      perPage = 3,
      supported = false;
    wrapper.className = "tns-t-subp2";
    outer.className = "tns-t-ct";
    for (var i = 0; i < count; i++) {
      str += '<div></div>';
    }
    outer.innerHTML = str;
    wrapper.appendChild(outer);
    body.appendChild(wrapper);
    supported = Math.abs(wrapper.getBoundingClientRect().left - outer.children[count - perPage].getBoundingClientRect().left) < 2;
    body.fake ? resetFakeBody(body, docOverflow) : wrapper.remove();
    return supported;
  }
  function mediaquerySupport() {
    if (window.matchMedia || window.msMatchMedia) {
      return true;
    }
    var doc = document,
      body = getBody(),
      docOverflow = setFakeBody(body),
      div = doc.createElement('div'),
      style = doc.createElement('style'),
      rule = '@media all and (min-width:1px){.tns-mq-test{position:absolute}}',
      position;
    style.type = 'text/css';
    div.className = 'tns-mq-test';
    body.appendChild(style);
    body.appendChild(div);
    if (style.styleSheet) {
      style.styleSheet.cssText = rule;
    } else {
      style.appendChild(doc.createTextNode(rule));
    }
    position = window.getComputedStyle ? window.getComputedStyle(div).position : div.currentStyle['position'];
    body.fake ? resetFakeBody(body, docOverflow) : div.remove();
    return position === "absolute";
  }

  // create and append style sheet
  function createStyleSheet(media, nonce) {
    // Create the <style> tag
    var style = document.createElement("style"); // style.setAttribute("type", "text/css");
    // Add a media (and/or media query) here if you'd like!
    // style.setAttribute("media", "screen")
    // style.setAttribute("media", "only screen and (max-width : 1024px)")

    if (media) {
      style.setAttribute("media", media);
    } // Add nonce attribute for Content Security Policy

    if (nonce) {
      style.setAttribute("nonce", nonce);
    } // WebKit hack :(
    // style.appendChild(document.createTextNode(""));
    // Add the <style> element to the page

    document.querySelector('head').appendChild(style);
    return style.sheet ? style.sheet : style.styleSheet;
  }

  // cross browsers addRule method
  function addCSSRule(sheet, selector, rules, index) {
    // return raf(function() {
    'insertRule' in sheet ? sheet.insertRule(selector + '{' + rules + '}', index) : sheet.addRule(selector, rules, index); // });
  }

  // cross browsers addRule method
  function removeCSSRule(sheet, index) {
    // return raf(function() {
    'deleteRule' in sheet ? sheet.deleteRule(index) : sheet.removeRule(index); // });
  }

  function getCssRulesLength(sheet) {
    var rule = 'insertRule' in sheet ? sheet.cssRules : sheet.rules;
    return rule.length;
  }
  function toDegree(y, x) {
    return Math.atan2(y, x) * (180 / Math.PI);
  }
  function getTouchDirection(angle, range) {
    var direction = false,
      gap = Math.abs(90 - Math.abs(angle));
    if (gap >= 90 - range) {
      direction = 'horizontal';
    } else if (gap <= range) {
      direction = 'vertical';
    }
    return direction;
  }

  // https://toddmotto.com/ditch-the-array-foreach-call-nodelist-hack/
  function forEach(arr, callback, scope) {
    for (var i = 0, l = arr.length; i < l; i++) {
      callback.call(scope, arr[i], i);
    }
  }
  var classListSupport = ('classList' in document.createElement('_'));
  var hasClass = classListSupport ? function (el, str) {
    return el.classList.contains(str);
  } : function (el, str) {
    return el.className.indexOf(str) >= 0;
  };
  var addClass = classListSupport ? function (el, str) {
    if (!hasClass(el, str)) {
      el.classList.add(str);
    }
  } : function (el, str) {
    if (!hasClass(el, str)) {
      el.className += ' ' + str;
    }
  };
  var removeClass = classListSupport ? function (el, str) {
    if (hasClass(el, str)) {
      el.classList.remove(str);
    }
  } : function (el, str) {
    if (hasClass(el, str)) {
      el.className = el.className.replace(str, '');
    }
  };
  function hasAttr(el, attr) {
    return el.hasAttribute(attr);
  }
  function getAttr(el, attr) {
    return el.getAttribute(attr);
  }
  function isNodeList(el) {
    // Only NodeList has the "item()" function
    return typeof el.item !== "undefined";
  }
  function setAttrs(els, attrs) {
    els = isNodeList(els) || els instanceof Array ? els : [els];
    if (Object.prototype.toString.call(attrs) !== '[object Object]') {
      return;
    }
    for (var i = els.length; i--;) {
      for (var key in attrs) {
        els[i].setAttribute(key, attrs[key]);
      }
    }
  }
  function removeAttrs(els, attrs) {
    els = isNodeList(els) || els instanceof Array ? els : [els];
    attrs = attrs instanceof Array ? attrs : [attrs];
    var attrLength = attrs.length;
    for (var i = els.length; i--;) {
      for (var j = attrLength; j--;) {
        els[i].removeAttribute(attrs[j]);
      }
    }
  }
  function arrayFromNodeList(nl) {
    var arr = [];
    for (var i = 0, l = nl.length; i < l; i++) {
      arr.push(nl[i]);
    }
    return arr;
  }
  function hideElement(el, forceHide) {
    if (el.style.display !== 'none') {
      el.style.display = 'none';
    }
  }
  function showElement(el, forceHide) {
    if (el.style.display === 'none') {
      el.style.display = '';
    }
  }
  function isVisible(el) {
    return window.getComputedStyle(el).display !== 'none';
  }
  function whichProperty(props) {
    if (typeof props === 'string') {
      var arr = [props],
        Props = props.charAt(0).toUpperCase() + props.substr(1),
        prefixes = ['Webkit', 'Moz', 'ms', 'O'];
      prefixes.forEach(function (prefix) {
        if (prefix !== 'ms' || props === 'transform') {
          arr.push(prefix + Props);
        }
      });
      props = arr;
    }
    var el = document.createElement('fakeelement');
    props.length;
    for (var i = 0; i < props.length; i++) {
      var prop = props[i];
      if (el.style[prop] !== undefined) {
        return prop;
      }
    }
    return false; // explicit for ie9-
  }

  function has3DTransforms(tf) {
    if (!tf) {
      return false;
    }
    if (!window.getComputedStyle) {
      return false;
    }
    var doc = document,
      body = getBody(),
      docOverflow = setFakeBody(body),
      el = doc.createElement('p'),
      has3d,
      cssTF = tf.length > 9 ? '-' + tf.slice(0, -9).toLowerCase() + '-' : '';
    cssTF += 'transform'; // Add it to the body to get the computed style

    body.insertBefore(el, null);
    el.style[tf] = 'translate3d(1px,1px,1px)';
    has3d = window.getComputedStyle(el).getPropertyValue(cssTF);
    body.fake ? resetFakeBody(body, docOverflow) : el.remove();
    return has3d !== undefined && has3d.length > 0 && has3d !== "none";
  }

  // get transitionend, animationend based on transitionDuration
  // @propin: string
  // @propOut: string, first-letter uppercase
  // Usage: getEndProperty('WebkitTransitionDuration', 'Transition') => webkitTransitionEnd
  function getEndProperty(propIn, propOut) {
    var endProp = false;
    if (/^Webkit/.test(propIn)) {
      endProp = 'webkit' + propOut + 'End';
    } else if (/^O/.test(propIn)) {
      endProp = 'o' + propOut + 'End';
    } else if (propIn) {
      endProp = propOut.toLowerCase() + 'end';
    }
    return endProp;
  }

  // Test via a getter in the options object to see if the passive property is accessed
  var supportsPassive = false;
  try {
    var opts = Object.defineProperty({}, 'passive', {
      get: function get() {
        supportsPassive = true;
      }
    });
    window.addEventListener("test", null, opts);
  } catch (e) {}
  var passiveOption = supportsPassive ? {
    passive: true
  } : false;
  function addEvents(el, obj, preventScrolling) {
    for (var prop in obj) {
      var option = ['touchstart', 'touchmove'].indexOf(prop) >= 0 && !preventScrolling ? passiveOption : false;
      el.addEventListener(prop, obj[prop], option);
    }
  }
  function removeEvents(el, obj) {
    for (var prop in obj) {
      var option = ['touchstart', 'touchmove'].indexOf(prop) >= 0 ? passiveOption : false;
      el.removeEventListener(prop, obj[prop], option);
    }
  }
  function Events() {
    return {
      topics: {},
      on: function on(eventName, fn) {
        this.topics[eventName] = this.topics[eventName] || [];
        this.topics[eventName].push(fn);
      },
      off: function off(eventName, fn) {
        if (this.topics[eventName]) {
          for (var i = 0; i < this.topics[eventName].length; i++) {
            if (this.topics[eventName][i] === fn) {
              this.topics[eventName].splice(i, 1);
              break;
            }
          }
        }
      },
      emit: function emit(eventName, data) {
        data.type = eventName;
        if (this.topics[eventName]) {
          this.topics[eventName].forEach(function (fn) {
            fn(data, eventName);
          });
        }
      }
    };
  }
  function jsTransform(element, attr, prefix, postfix, to, duration, callback) {
    var tick = Math.min(duration, 10),
      unit = to.indexOf('%') >= 0 ? '%' : 'px',
      to = to.replace(unit, ''),
      from = Number(element.style[attr].replace(prefix, '').replace(postfix, '').replace(unit, '')),
      positionTick = (to - from) / duration * tick;
    setTimeout(moveElement, tick);
    function moveElement() {
      duration -= tick;
      from += positionTick;
      element.style[attr] = prefix + from + unit + postfix;
      if (duration > 0) {
        setTimeout(moveElement, tick);
      } else {
        callback();
      }
    }
  }

  // Object.keys
  if (!Object.keys) {
    Object.keys = function (object) {
      var keys = [];
      for (var name in object) {
        if (Object.prototype.hasOwnProperty.call(object, name)) {
          keys.push(name);
        }
      }
      return keys;
    };
  } // ChildNode.remove

  if (!("remove" in Element.prototype)) {
    Element.prototype.remove = function () {
      if (this.parentNode) {
        this.parentNode.removeChild(this);
      }
    };
  }
  var tns = function tns(options) {
    options = extend({
      container: '.slider',
      mode: 'carousel',
      axis: 'horizontal',
      items: 1,
      gutter: 0,
      edgePadding: 0,
      fixedWidth: false,
      autoWidth: false,
      viewportMax: false,
      slideBy: 1,
      center: false,
      controls: true,
      controlsPosition: 'top',
      controlsText: ['prev', 'next'],
      controlsContainer: false,
      prevButton: false,
      nextButton: false,
      nav: true,
      navPosition: 'top',
      navContainer: false,
      navAsThumbnails: false,
      arrowKeys: false,
      speed: 300,
      autoplay: false,
      autoplayPosition: 'top',
      autoplayTimeout: 5000,
      autoplayDirection: 'forward',
      autoplayText: ['start', 'stop'],
      autoplayHoverPause: false,
      autoplayButton: false,
      autoplayButtonOutput: true,
      autoplayResetOnVisibility: true,
      animateIn: 'tns-fadeIn',
      animateOut: 'tns-fadeOut',
      animateNormal: 'tns-normal',
      animateDelay: false,
      loop: true,
      rewind: false,
      autoHeight: false,
      responsive: false,
      lazyload: false,
      lazyloadSelector: '.tns-lazy-img',
      touch: true,
      mouseDrag: false,
      swipeAngle: 15,
      nested: false,
      preventActionWhenRunning: false,
      preventScrollOnTouch: false,
      freezable: true,
      onInit: false,
      useLocalStorage: true,
      nonce: false
    }, options || {});
    var doc = document,
      win = window,
      KEYS = {
        ENTER: 13,
        SPACE: 32,
        LEFT: 37,
        RIGHT: 39
      },
      tnsStorage = {},
      localStorageAccess = options.useLocalStorage;
    if (localStorageAccess) {
      // check browser version and local storage access
      var browserInfo = navigator.userAgent;
      var uid = new Date();
      try {
        tnsStorage = win.localStorage;
        if (tnsStorage) {
          tnsStorage.setItem(uid, uid);
          localStorageAccess = tnsStorage.getItem(uid) == uid;
          tnsStorage.removeItem(uid);
        } else {
          localStorageAccess = false;
        }
        if (!localStorageAccess) {
          tnsStorage = {};
        }
      } catch (e) {
        localStorageAccess = false;
      }
      if (localStorageAccess) {
        // remove storage when browser version changes
        if (tnsStorage['tnsApp'] && tnsStorage['tnsApp'] !== browserInfo) {
          ['tC', 'tPL', 'tMQ', 'tTf', 't3D', 'tTDu', 'tTDe', 'tADu', 'tADe', 'tTE', 'tAE'].forEach(function (item) {
            tnsStorage.removeItem(item);
          });
        } // update browserInfo

        localStorage['tnsApp'] = browserInfo;
      }
    }
    var CALC = tnsStorage['tC'] ? checkStorageValue(tnsStorage['tC']) : setLocalStorage(tnsStorage, 'tC', calc(), localStorageAccess),
      PERCENTAGELAYOUT = tnsStorage['tPL'] ? checkStorageValue(tnsStorage['tPL']) : setLocalStorage(tnsStorage, 'tPL', percentageLayout(), localStorageAccess),
      CSSMQ = tnsStorage['tMQ'] ? checkStorageValue(tnsStorage['tMQ']) : setLocalStorage(tnsStorage, 'tMQ', mediaquerySupport(), localStorageAccess),
      TRANSFORM = tnsStorage['tTf'] ? checkStorageValue(tnsStorage['tTf']) : setLocalStorage(tnsStorage, 'tTf', whichProperty('transform'), localStorageAccess),
      HAS3DTRANSFORMS = tnsStorage['t3D'] ? checkStorageValue(tnsStorage['t3D']) : setLocalStorage(tnsStorage, 't3D', has3DTransforms(TRANSFORM), localStorageAccess),
      TRANSITIONDURATION = tnsStorage['tTDu'] ? checkStorageValue(tnsStorage['tTDu']) : setLocalStorage(tnsStorage, 'tTDu', whichProperty('transitionDuration'), localStorageAccess),
      TRANSITIONDELAY = tnsStorage['tTDe'] ? checkStorageValue(tnsStorage['tTDe']) : setLocalStorage(tnsStorage, 'tTDe', whichProperty('transitionDelay'), localStorageAccess),
      ANIMATIONDURATION = tnsStorage['tADu'] ? checkStorageValue(tnsStorage['tADu']) : setLocalStorage(tnsStorage, 'tADu', whichProperty('animationDuration'), localStorageAccess),
      ANIMATIONDELAY = tnsStorage['tADe'] ? checkStorageValue(tnsStorage['tADe']) : setLocalStorage(tnsStorage, 'tADe', whichProperty('animationDelay'), localStorageAccess),
      TRANSITIONEND = tnsStorage['tTE'] ? checkStorageValue(tnsStorage['tTE']) : setLocalStorage(tnsStorage, 'tTE', getEndProperty(TRANSITIONDURATION, 'Transition'), localStorageAccess),
      ANIMATIONEND = tnsStorage['tAE'] ? checkStorageValue(tnsStorage['tAE']) : setLocalStorage(tnsStorage, 'tAE', getEndProperty(ANIMATIONDURATION, 'Animation'), localStorageAccess); // get element nodes from selectors

    var supportConsoleWarn = win.console && typeof win.console.warn === "function",
      tnsList = ['container', 'controlsContainer', 'prevButton', 'nextButton', 'navContainer', 'autoplayButton'],
      optionsElements = {};
    tnsList.forEach(function (item) {
      if (typeof options[item] === 'string') {
        var str = options[item],
          el = doc.querySelector(str);
        optionsElements[item] = str;
        if (el && el.nodeName) {
          options[item] = el;
        } else {
          if (supportConsoleWarn) {
            console.warn('Can\'t find', options[item]);
          }
          return;
        }
      }
    }); // make sure at least 1 slide

    if (options.container.children.length < 1) {
      if (supportConsoleWarn) {
        console.warn('No slides found in', options.container);
      }
      return;
    } // update options

    var responsive = options.responsive,
      nested = options.nested,
      carousel = options.mode === 'carousel' ? true : false;
    if (responsive) {
      // apply responsive[0] to options and remove it
      if (0 in responsive) {
        options = extend(options, responsive[0]);
        delete responsive[0];
      }
      var responsiveTem = {};
      for (var key in responsive) {
        var val = responsive[key]; // update responsive
        // from: 300: 2
        // to:
        //   300: {
        //     items: 2
        //   }

        val = typeof val === 'number' ? {
          items: val
        } : val;
        responsiveTem[key] = val;
      }
      responsive = responsiveTem;
      responsiveTem = null;
    } // update options

    function updateOptions(obj) {
      for (var key in obj) {
        if (!carousel) {
          if (key === 'slideBy') {
            obj[key] = 'page';
          }
          if (key === 'edgePadding') {
            obj[key] = false;
          }
          if (key === 'autoHeight') {
            obj[key] = false;
          }
        } // update responsive options

        if (key === 'responsive') {
          updateOptions(obj[key]);
        }
      }
    }
    if (!carousel) {
      updateOptions(options);
    } // === define and set variables ===

    if (!carousel) {
      options.axis = 'horizontal';
      options.slideBy = 'page';
      options.edgePadding = false;
      var animateIn = options.animateIn,
        animateOut = options.animateOut,
        animateDelay = options.animateDelay,
        animateNormal = options.animateNormal;
    }
    var horizontal = options.axis === 'horizontal' ? true : false,
      outerWrapper = doc.createElement('div'),
      innerWrapper = doc.createElement('div'),
      middleWrapper,
      container = options.container,
      containerParent = container.parentNode,
      containerHTML = container.outerHTML,
      slideItems = container.children,
      slideCount = slideItems.length,
      breakpointZone,
      windowWidth = getWindowWidth(),
      isOn = false;
    if (responsive) {
      setBreakpointZone();
    }
    if (carousel) {
      container.className += ' tns-vpfix';
    } // fixedWidth: viewport > rightBoundary > indexMax

    var autoWidth = options.autoWidth,
      fixedWidth = getOption('fixedWidth'),
      edgePadding = getOption('edgePadding'),
      gutter = getOption('gutter'),
      viewport = getViewportWidth(),
      center = getOption('center'),
      items = !autoWidth ? Math.floor(getOption('items')) : 1,
      slideBy = getOption('slideBy'),
      viewportMax = options.viewportMax || options.fixedWidthViewportWidth,
      arrowKeys = getOption('arrowKeys'),
      speed = getOption('speed'),
      rewind = options.rewind,
      loop = rewind ? false : options.loop,
      autoHeight = getOption('autoHeight'),
      controls = getOption('controls'),
      controlsText = getOption('controlsText'),
      nav = getOption('nav'),
      touch = getOption('touch'),
      mouseDrag = getOption('mouseDrag'),
      autoplay = getOption('autoplay'),
      autoplayTimeout = getOption('autoplayTimeout'),
      autoplayText = getOption('autoplayText'),
      autoplayHoverPause = getOption('autoplayHoverPause'),
      autoplayResetOnVisibility = getOption('autoplayResetOnVisibility'),
      sheet = createStyleSheet(null, getOption('nonce')),
      lazyload = options.lazyload,
      lazyloadSelector = options.lazyloadSelector,
      slidePositions,
      // collection of slide positions
      slideItemsOut = [],
      cloneCount = loop ? getCloneCountForLoop() : 0,
      slideCountNew = !carousel ? slideCount + cloneCount : slideCount + cloneCount * 2,
      hasRightDeadZone = (fixedWidth || autoWidth) && !loop ? true : false,
      rightBoundary = fixedWidth ? getRightBoundary() : null,
      updateIndexBeforeTransform = !carousel || !loop ? true : false,
      // transform
      transformAttr = horizontal ? 'left' : 'top',
      transformPrefix = '',
      transformPostfix = '',
      // index
      getIndexMax = function () {
        if (fixedWidth) {
          return function () {
            return center && !loop ? slideCount - 1 : Math.ceil(-rightBoundary / (fixedWidth + gutter));
          };
        } else if (autoWidth) {
          return function () {
            for (var i = 0; i < slideCountNew; i++) {
              if (slidePositions[i] >= -rightBoundary) {
                return i;
              }
            }
          };
        } else {
          return function () {
            if (center && carousel && !loop) {
              return slideCount - 1;
            } else {
              return loop || carousel ? Math.max(0, slideCountNew - Math.ceil(items)) : slideCountNew - 1;
            }
          };
        }
      }(),
      index = getStartIndex(getOption('startIndex')),
      indexCached = index;
    getCurrentSlide();
    var indexMin = 0,
      indexMax = !autoWidth ? getIndexMax() : null,
      preventActionWhenRunning = options.preventActionWhenRunning,
      swipeAngle = options.swipeAngle,
      moveDirectionExpected = swipeAngle ? '?' : true,
      running = false,
      onInit = options.onInit,
      events = new Events(),
      // id, class
      newContainerClasses = ' tns-slider tns-' + options.mode,
      slideId = container.id || getSlideId(),
      disable = getOption('disable'),
      disabled = false,
      freezable = options.freezable,
      freeze = freezable && !autoWidth ? getFreeze() : false,
      frozen = false,
      controlsEvents = {
        'click': onControlsClick,
        'keydown': onControlsKeydown
      },
      navEvents = {
        'click': onNavClick,
        'keydown': onNavKeydown
      },
      hoverEvents = {
        'mouseover': mouseoverPause,
        'mouseout': mouseoutRestart
      },
      visibilityEvent = {
        'visibilitychange': onVisibilityChange
      },
      docmentKeydownEvent = {
        'keydown': onDocumentKeydown
      },
      touchEvents = {
        'touchstart': onPanStart,
        'touchmove': onPanMove,
        'touchend': onPanEnd,
        'touchcancel': onPanEnd
      },
      dragEvents = {
        'mousedown': onPanStart,
        'mousemove': onPanMove,
        'mouseup': onPanEnd,
        'mouseleave': onPanEnd
      },
      hasControls = hasOption('controls'),
      hasNav = hasOption('nav'),
      navAsThumbnails = autoWidth ? true : options.navAsThumbnails,
      hasAutoplay = hasOption('autoplay'),
      hasTouch = hasOption('touch'),
      hasMouseDrag = hasOption('mouseDrag'),
      slideActiveClass = 'tns-slide-active',
      slideClonedClass = 'tns-slide-cloned',
      imgCompleteClass = 'tns-complete',
      imgEvents = {
        'load': onImgLoaded,
        'error': onImgFailed
      },
      imgsComplete,
      liveregionCurrent,
      preventScroll = options.preventScrollOnTouch === 'force' ? true : false; // controls

    if (hasControls) {
      var controlsContainer = options.controlsContainer,
        controlsContainerHTML = options.controlsContainer ? options.controlsContainer.outerHTML : '',
        prevButton = options.prevButton,
        nextButton = options.nextButton,
        prevButtonHTML = options.prevButton ? options.prevButton.outerHTML : '',
        nextButtonHTML = options.nextButton ? options.nextButton.outerHTML : '',
        prevIsButton,
        nextIsButton;
    } // nav

    if (hasNav) {
      var navContainer = options.navContainer,
        navContainerHTML = options.navContainer ? options.navContainer.outerHTML : '',
        navItems,
        pages = autoWidth ? slideCount : getPages(),
        pagesCached = 0,
        navClicked = -1,
        navCurrentIndex = getCurrentNavIndex(),
        navCurrentIndexCached = navCurrentIndex,
        navActiveClass = 'tns-nav-active',
        navStr = 'Carousel Page ',
        navStrCurrent = ' (Current Slide)';
    } // autoplay

    if (hasAutoplay) {
      var autoplayDirection = options.autoplayDirection === 'forward' ? 1 : -1,
        autoplayButton = options.autoplayButton,
        autoplayButtonHTML = options.autoplayButton ? options.autoplayButton.outerHTML : '',
        autoplayHtmlStrings = ['<span class=\'tns-visually-hidden\'>', ' animation</span>'],
        autoplayTimer,
        animating,
        autoplayHoverPaused,
        autoplayUserPaused,
        autoplayVisibilityPaused;
    }
    if (hasTouch || hasMouseDrag) {
      var initPosition = {},
        lastPosition = {},
        translateInit,
        panStart = false,
        rafIndex,
        getDist = horizontal ? function (a, b) {
          return a.x - b.x;
        } : function (a, b) {
          return a.y - b.y;
        };
    } // disable slider when slidecount <= items

    if (!autoWidth) {
      resetVariblesWhenDisable(disable || freeze);
    }
    if (TRANSFORM) {
      transformAttr = TRANSFORM;
      transformPrefix = 'translate';
      if (HAS3DTRANSFORMS) {
        transformPrefix += horizontal ? '3d(' : '3d(0px, ';
        transformPostfix = horizontal ? ', 0px, 0px)' : ', 0px)';
      } else {
        transformPrefix += horizontal ? 'X(' : 'Y(';
        transformPostfix = ')';
      }
    }
    if (carousel) {
      container.className = container.className.replace('tns-vpfix', '');
    }
    initStructure();
    initSheet();
    initSliderTransform(); // === COMMON FUNCTIONS === //

    function resetVariblesWhenDisable(condition) {
      if (condition) {
        controls = nav = touch = mouseDrag = arrowKeys = autoplay = autoplayHoverPause = autoplayResetOnVisibility = false;
      }
    }
    function getCurrentSlide() {
      var tem = carousel ? index - cloneCount : index;
      while (tem < 0) {
        tem += slideCount;
      }
      return tem % slideCount + 1;
    }
    function getStartIndex(ind) {
      ind = ind ? Math.max(0, Math.min(loop ? slideCount - 1 : slideCount - items, ind)) : 0;
      return carousel ? ind + cloneCount : ind;
    }
    function getAbsIndex(i) {
      if (i == null) {
        i = index;
      }
      if (carousel) {
        i -= cloneCount;
      }
      while (i < 0) {
        i += slideCount;
      }
      return Math.floor(i % slideCount);
    }
    function getCurrentNavIndex() {
      var absIndex = getAbsIndex(),
        result;
      result = navAsThumbnails ? absIndex : fixedWidth || autoWidth ? Math.ceil((absIndex + 1) * pages / slideCount - 1) : Math.floor(absIndex / items); // set active nav to the last one when reaches the right edge

      if (!loop && carousel && index === indexMax) {
        result = pages - 1;
      }
      return result;
    }
    function getItemsMax() {
      // fixedWidth or autoWidth while viewportMax is not available
      if (autoWidth || fixedWidth && !viewportMax) {
        return slideCount - 1; // most cases
      } else {
        var str = fixedWidth ? 'fixedWidth' : 'items',
          arr = [];
        if (fixedWidth || options[str] < slideCount) {
          arr.push(options[str]);
        }
        if (responsive) {
          for (var bp in responsive) {
            var tem = responsive[bp][str];
            if (tem && (fixedWidth || tem < slideCount)) {
              arr.push(tem);
            }
          }
        }
        if (!arr.length) {
          arr.push(0);
        }
        return Math.ceil(fixedWidth ? viewportMax / Math.min.apply(null, arr) : Math.max.apply(null, arr));
      }
    }
    function getCloneCountForLoop() {
      var itemsMax = getItemsMax(),
        result = carousel ? Math.ceil((itemsMax * 5 - slideCount) / 2) : itemsMax * 4 - slideCount;
      result = Math.max(itemsMax, result);
      return hasOption('edgePadding') ? result + 1 : result;
    }
    function getWindowWidth() {
      return win.innerWidth || doc.documentElement.clientWidth || doc.body.clientWidth;
    }
    function getInsertPosition(pos) {
      return pos === 'top' ? 'afterbegin' : 'beforeend';
    }
    function getClientWidth(el) {
      if (el == null) {
        return;
      }
      var div = doc.createElement('div'),
        rect,
        width;
      el.appendChild(div);
      rect = div.getBoundingClientRect();
      width = rect.right - rect.left;
      div.remove();
      return width || getClientWidth(el.parentNode);
    }
    function getViewportWidth() {
      var gap = edgePadding ? edgePadding * 2 - gutter : 0;
      return getClientWidth(containerParent) - gap;
    }
    function hasOption(item) {
      if (options[item]) {
        return true;
      } else {
        if (responsive) {
          for (var bp in responsive) {
            if (responsive[bp][item]) {
              return true;
            }
          }
        }
        return false;
      }
    } // get option:
    // fixed width: viewport, fixedWidth, gutter => items
    // others: window width => all variables
    // all: items => slideBy

    function getOption(item, ww) {
      if (ww == null) {
        ww = windowWidth;
      }
      if (item === 'items' && fixedWidth) {
        return Math.floor((viewport + gutter) / (fixedWidth + gutter)) || 1;
      } else {
        var result = options[item];
        if (responsive) {
          for (var bp in responsive) {
            // bp: convert string to number
            if (ww >= parseInt(bp)) {
              if (item in responsive[bp]) {
                result = responsive[bp][item];
              }
            }
          }
        }
        if (item === 'slideBy' && result === 'page') {
          result = getOption('items');
        }
        if (!carousel && (item === 'slideBy' || item === 'items')) {
          result = Math.floor(result);
        }
        return result;
      }
    }
    function getSlideMarginLeft(i) {
      return CALC ? CALC + '(' + i * 100 + '% / ' + slideCountNew + ')' : i * 100 / slideCountNew + '%';
    }
    function getInnerWrapperStyles(edgePaddingTem, gutterTem, fixedWidthTem, speedTem, autoHeightBP) {
      var str = '';
      if (edgePaddingTem !== undefined) {
        var gap = edgePaddingTem;
        if (gutterTem) {
          gap -= gutterTem;
        }
        str = horizontal ? 'margin: 0 ' + gap + 'px 0 ' + edgePaddingTem + 'px;' : 'margin: ' + edgePaddingTem + 'px 0 ' + gap + 'px 0;';
      } else if (gutterTem && !fixedWidthTem) {
        var gutterTemUnit = '-' + gutterTem + 'px',
          dir = horizontal ? gutterTemUnit + ' 0 0' : '0 ' + gutterTemUnit + ' 0';
        str = 'margin: 0 ' + dir + ';';
      }
      if (!carousel && autoHeightBP && TRANSITIONDURATION && speedTem) {
        str += getTransitionDurationStyle(speedTem);
      }
      return str;
    }
    function getContainerWidth(fixedWidthTem, gutterTem, itemsTem) {
      if (fixedWidthTem) {
        return (fixedWidthTem + gutterTem) * slideCountNew + 'px';
      } else {
        return CALC ? CALC + '(' + slideCountNew * 100 + '% / ' + itemsTem + ')' : slideCountNew * 100 / itemsTem + '%';
      }
    }
    function getSlideWidthStyle(fixedWidthTem, gutterTem, itemsTem) {
      var width;
      if (fixedWidthTem) {
        width = fixedWidthTem + gutterTem + 'px';
      } else {
        if (!carousel) {
          itemsTem = Math.floor(itemsTem);
        }
        var dividend = carousel ? slideCountNew : itemsTem;
        width = CALC ? CALC + '(100% / ' + dividend + ')' : 100 / dividend + '%';
      }
      width = 'width:' + width; // inner slider: overwrite outer slider styles

      return nested !== 'inner' ? width + ';' : width + ' !important;';
    }
    function getSlideGutterStyle(gutterTem) {
      var str = ''; // gutter maybe interger || 0
      // so can't use 'if (gutter)'

      if (gutterTem !== false) {
        var prop = horizontal ? 'padding-' : 'margin-',
          dir = horizontal ? 'right' : 'bottom';
        str = prop + dir + ': ' + gutterTem + 'px;';
      }
      return str;
    }
    function getCSSPrefix(name, num) {
      var prefix = name.substring(0, name.length - num).toLowerCase();
      if (prefix) {
        prefix = '-' + prefix + '-';
      }
      return prefix;
    }
    function getTransitionDurationStyle(speed) {
      return getCSSPrefix(TRANSITIONDURATION, 18) + 'transition-duration:' + speed / 1000 + 's;';
    }
    function getAnimationDurationStyle(speed) {
      return getCSSPrefix(ANIMATIONDURATION, 17) + 'animation-duration:' + speed / 1000 + 's;';
    }
    function initStructure() {
      var classOuter = 'tns-outer',
        classInner = 'tns-inner';
      hasOption('gutter');
      outerWrapper.className = classOuter;
      innerWrapper.className = classInner;
      outerWrapper.id = slideId + '-ow';
      innerWrapper.id = slideId + '-iw'; // set container properties

      if (container.id === '') {
        container.id = slideId;
      }
      newContainerClasses += PERCENTAGELAYOUT || autoWidth ? ' tns-subpixel' : ' tns-no-subpixel';
      newContainerClasses += CALC ? ' tns-calc' : ' tns-no-calc';
      if (autoWidth) {
        newContainerClasses += ' tns-autowidth';
      }
      newContainerClasses += ' tns-' + options.axis;
      container.className += newContainerClasses; // add constrain layer for carousel

      if (carousel) {
        middleWrapper = doc.createElement('div');
        middleWrapper.id = slideId + '-mw';
        middleWrapper.className = 'tns-ovh';
        outerWrapper.appendChild(middleWrapper);
        middleWrapper.appendChild(innerWrapper);
      } else {
        outerWrapper.appendChild(innerWrapper);
      }
      if (autoHeight) {
        var wp = middleWrapper ? middleWrapper : innerWrapper;
        wp.className += ' tns-ah';
      }
      containerParent.insertBefore(outerWrapper, container);
      innerWrapper.appendChild(container); // add id, class, aria attributes
      // before clone slides

      forEach(slideItems, function (item, i) {
        addClass(item, 'tns-item');
        if (!item.id) {
          item.id = slideId + '-item' + i;
        }
        if (!carousel && animateNormal) {
          addClass(item, animateNormal);
        }
        setAttrs(item, {
          'aria-hidden': 'true',
          'tabindex': '-1'
        });
      }); // ## clone slides
      // carousel: n + slides + n
      // gallery:      slides + n

      if (cloneCount) {
        var fragmentBefore = doc.createDocumentFragment(),
          fragmentAfter = doc.createDocumentFragment();
        for (var j = cloneCount; j--;) {
          var num = j % slideCount,
            cloneFirst = slideItems[num].cloneNode(true);
          addClass(cloneFirst, slideClonedClass);
          removeAttrs(cloneFirst, 'id');
          fragmentAfter.insertBefore(cloneFirst, fragmentAfter.firstChild);
          if (carousel) {
            var cloneLast = slideItems[slideCount - 1 - num].cloneNode(true);
            addClass(cloneLast, slideClonedClass);
            removeAttrs(cloneLast, 'id');
            fragmentBefore.appendChild(cloneLast);
          }
        }
        container.insertBefore(fragmentBefore, container.firstChild);
        container.appendChild(fragmentAfter);
        slideItems = container.children;
      }
    }
    function initSliderTransform() {
      // ## images loaded/failed
      if (hasOption('autoHeight') || autoWidth || !horizontal) {
        var imgs = container.querySelectorAll('img'); // add img load event listener

        forEach(imgs, function (img) {
          var src = img.src;
          if (!lazyload) {
            // not data img
            if (src && src.indexOf('data:image') < 0) {
              img.src = '';
              addEvents(img, imgEvents);
              addClass(img, 'loading');
              img.src = src; // data img
            } else {
              imgLoaded(img);
            }
          }
        }); // set imgsComplete

        raf(function () {
          imgsLoadedCheck(arrayFromNodeList(imgs), function () {
            imgsComplete = true;
          });
        }); // reset imgs for auto height: check visible imgs only

        if (hasOption('autoHeight')) {
          imgs = getImageArray(index, Math.min(index + items - 1, slideCountNew - 1));
        }
        lazyload ? initSliderTransformStyleCheck() : raf(function () {
          imgsLoadedCheck(arrayFromNodeList(imgs), initSliderTransformStyleCheck);
        });
      } else {
        // set container transform property
        if (carousel) {
          doContainerTransformSilent();
        } // update slider tools and events

        initTools();
        initEvents();
      }
    }
    function initSliderTransformStyleCheck() {
      if (autoWidth && slideCount > 1) {
        // check styles application
        var num = loop ? index : slideCount - 1;
        (function stylesApplicationCheck() {
          var left = slideItems[num].getBoundingClientRect().left;
          var right = slideItems[num - 1].getBoundingClientRect().right;
          Math.abs(left - right) <= 1 ? initSliderTransformCore() : setTimeout(function () {
            stylesApplicationCheck();
          }, 16);
        })();
      } else {
        initSliderTransformCore();
      }
    }
    function initSliderTransformCore() {
      // run Fn()s which are rely on image loading
      if (!horizontal || autoWidth) {
        setSlidePositions();
        if (autoWidth) {
          rightBoundary = getRightBoundary();
          if (freezable) {
            freeze = getFreeze();
          }
          indexMax = getIndexMax(); // <= slidePositions, rightBoundary <=

          resetVariblesWhenDisable(disable || freeze);
        } else {
          updateContentWrapperHeight();
        }
      } // set container transform property

      if (carousel) {
        doContainerTransformSilent();
      } // update slider tools and events

      initTools();
      initEvents();
    }
    function initSheet() {
      // gallery:
      // set animation classes and left value for gallery slider
      if (!carousel) {
        for (var i = index, l = index + Math.min(slideCount, items); i < l; i++) {
          var item = slideItems[i];
          item.style.left = (i - index) * 100 / items + '%';
          addClass(item, animateIn);
          removeClass(item, animateNormal);
        }
      } // #### LAYOUT
      // ## INLINE-BLOCK VS FLOAT
      // ## PercentageLayout:
      // slides: inline-block
      // remove blank space between slides by set font-size: 0
      // ## Non PercentageLayout:
      // slides: float
      //         margin-right: -100%
      //         margin-left: ~
      // Resource: https://docs.google.com/spreadsheets/d/147up245wwTXeQYve3BRSAD4oVcvQmuGsFteJOeA5xNQ/edit?usp=sharing

      if (horizontal) {
        if (PERCENTAGELAYOUT || autoWidth) {
          addCSSRule(sheet, '#' + slideId + ' > .tns-item', 'font-size:' + win.getComputedStyle(slideItems[0]).fontSize + ';', getCssRulesLength(sheet));
          addCSSRule(sheet, '#' + slideId, 'font-size:0;', getCssRulesLength(sheet));
        } else if (carousel) {
          forEach(slideItems, function (slide, i) {
            slide.style.marginLeft = getSlideMarginLeft(i);
          });
        }
      } // ## BASIC STYLES

      if (CSSMQ) {
        // middle wrapper style
        if (TRANSITIONDURATION) {
          var str = middleWrapper && options.autoHeight ? getTransitionDurationStyle(options.speed) : '';
          addCSSRule(sheet, '#' + slideId + '-mw', str, getCssRulesLength(sheet));
        } // inner wrapper styles

        str = getInnerWrapperStyles(options.edgePadding, options.gutter, options.fixedWidth, options.speed, options.autoHeight);
        addCSSRule(sheet, '#' + slideId + '-iw', str, getCssRulesLength(sheet)); // container styles

        if (carousel) {
          str = horizontal && !autoWidth ? 'width:' + getContainerWidth(options.fixedWidth, options.gutter, options.items) + ';' : '';
          if (TRANSITIONDURATION) {
            str += getTransitionDurationStyle(speed);
          }
          addCSSRule(sheet, '#' + slideId, str, getCssRulesLength(sheet));
        } // slide styles

        str = horizontal && !autoWidth ? getSlideWidthStyle(options.fixedWidth, options.gutter, options.items) : '';
        if (options.gutter) {
          str += getSlideGutterStyle(options.gutter);
        } // set gallery items transition-duration

        if (!carousel) {
          if (TRANSITIONDURATION) {
            str += getTransitionDurationStyle(speed);
          }
          if (ANIMATIONDURATION) {
            str += getAnimationDurationStyle(speed);
          }
        }
        if (str) {
          addCSSRule(sheet, '#' + slideId + ' > .tns-item', str, getCssRulesLength(sheet));
        } // non CSS mediaqueries: IE8
        // ## update inner wrapper, container, slides if needed
        // set inline styles for inner wrapper & container
        // insert stylesheet (one line) for slides only (since slides are many)
      } else {
        // middle wrapper styles
        update_carousel_transition_duration(); // inner wrapper styles

        innerWrapper.style.cssText = getInnerWrapperStyles(edgePadding, gutter, fixedWidth, autoHeight); // container styles

        if (carousel && horizontal && !autoWidth) {
          container.style.width = getContainerWidth(fixedWidth, gutter, items);
        } // slide styles

        var str = horizontal && !autoWidth ? getSlideWidthStyle(fixedWidth, gutter, items) : '';
        if (gutter) {
          str += getSlideGutterStyle(gutter);
        } // append to the last line

        if (str) {
          addCSSRule(sheet, '#' + slideId + ' > .tns-item', str, getCssRulesLength(sheet));
        }
      } // ## MEDIAQUERIES

      if (responsive && CSSMQ) {
        for (var bp in responsive) {
          // bp: convert string to number
          bp = parseInt(bp);
          var opts = responsive[bp],
            str = '',
            middleWrapperStr = '',
            innerWrapperStr = '',
            containerStr = '',
            slideStr = '',
            itemsBP = !autoWidth ? getOption('items', bp) : null,
            fixedWidthBP = getOption('fixedWidth', bp),
            speedBP = getOption('speed', bp),
            edgePaddingBP = getOption('edgePadding', bp),
            autoHeightBP = getOption('autoHeight', bp),
            gutterBP = getOption('gutter', bp); // middle wrapper string

          if (TRANSITIONDURATION && middleWrapper && getOption('autoHeight', bp) && 'speed' in opts) {
            middleWrapperStr = '#' + slideId + '-mw{' + getTransitionDurationStyle(speedBP) + '}';
          } // inner wrapper string

          if ('edgePadding' in opts || 'gutter' in opts) {
            innerWrapperStr = '#' + slideId + '-iw{' + getInnerWrapperStyles(edgePaddingBP, gutterBP, fixedWidthBP, speedBP, autoHeightBP) + '}';
          } // container string

          if (carousel && horizontal && !autoWidth && ('fixedWidth' in opts || 'items' in opts || fixedWidth && 'gutter' in opts)) {
            containerStr = 'width:' + getContainerWidth(fixedWidthBP, gutterBP, itemsBP) + ';';
          }
          if (TRANSITIONDURATION && 'speed' in opts) {
            containerStr += getTransitionDurationStyle(speedBP);
          }
          if (containerStr) {
            containerStr = '#' + slideId + '{' + containerStr + '}';
          } // slide string

          if ('fixedWidth' in opts || fixedWidth && 'gutter' in opts || !carousel && 'items' in opts) {
            slideStr += getSlideWidthStyle(fixedWidthBP, gutterBP, itemsBP);
          }
          if ('gutter' in opts) {
            slideStr += getSlideGutterStyle(gutterBP);
          } // set gallery items transition-duration

          if (!carousel && 'speed' in opts) {
            if (TRANSITIONDURATION) {
              slideStr += getTransitionDurationStyle(speedBP);
            }
            if (ANIMATIONDURATION) {
              slideStr += getAnimationDurationStyle(speedBP);
            }
          }
          if (slideStr) {
            slideStr = '#' + slideId + ' > .tns-item{' + slideStr + '}';
          } // add up

          str = middleWrapperStr + innerWrapperStr + containerStr + slideStr;
          if (str) {
            sheet.insertRule('@media (min-width: ' + bp / 16 + 'em) {' + str + '}', sheet.cssRules.length);
          }
        }
      }
    }
    function initTools() {
      // == slides ==
      updateSlideStatus(); // == live region ==

      outerWrapper.insertAdjacentHTML('afterbegin', '<div class="tns-liveregion tns-visually-hidden" aria-live="polite" aria-atomic="true">slide <span class="current">' + getLiveRegionStr() + '</span>  of ' + slideCount + '</div>');
      liveregionCurrent = outerWrapper.querySelector('.tns-liveregion .current'); // == autoplayInit ==

      if (hasAutoplay) {
        var txt = autoplay ? 'stop' : 'start';
        if (autoplayButton) {
          setAttrs(autoplayButton, {
            'data-action': txt
          });
        } else if (options.autoplayButtonOutput) {
          outerWrapper.insertAdjacentHTML(getInsertPosition(options.autoplayPosition), '<button type="button" data-action="' + txt + '">' + autoplayHtmlStrings[0] + txt + autoplayHtmlStrings[1] + autoplayText[0] + '</button>');
          autoplayButton = outerWrapper.querySelector('[data-action]');
        } // add event

        if (autoplayButton) {
          addEvents(autoplayButton, {
            'click': toggleAutoplay
          });
        }
        if (autoplay) {
          startAutoplay();
          if (autoplayHoverPause) {
            addEvents(container, hoverEvents);
          }
          if (autoplayResetOnVisibility) {
            addEvents(container, visibilityEvent);
          }
        }
      } // == navInit ==

      if (hasNav) {
        // will not hide the navs in case they're thumbnails

        if (navContainer) {
          setAttrs(navContainer, {
            'aria-label': 'Carousel Pagination'
          });
          navItems = navContainer.children;
          forEach(navItems, function (item, i) {
            setAttrs(item, {
              'data-nav': i,
              'tabindex': '-1',
              'aria-label': navStr + (i + 1),
              'aria-controls': slideId
            });
          }); // generated nav
        } else {
          var navHtml = '',
            hiddenStr = navAsThumbnails ? '' : 'style="display:none"';
          for (var i = 0; i < slideCount; i++) {
            // hide nav items by default
            navHtml += '<button type="button" data-nav="' + i + '" tabindex="-1" aria-controls="' + slideId + '" ' + hiddenStr + ' aria-label="' + navStr + (i + 1) + '"></button>';
          }
          navHtml = '<div class="tns-nav" aria-label="Carousel Pagination">' + navHtml + '</div>';
          outerWrapper.insertAdjacentHTML(getInsertPosition(options.navPosition), navHtml);
          navContainer = outerWrapper.querySelector('.tns-nav');
          navItems = navContainer.children;
        }
        updateNavVisibility(); // add transition

        if (TRANSITIONDURATION) {
          var prefix = TRANSITIONDURATION.substring(0, TRANSITIONDURATION.length - 18).toLowerCase(),
            str = 'transition: all ' + speed / 1000 + 's';
          if (prefix) {
            str = '-' + prefix + '-' + str;
          }
          addCSSRule(sheet, '[aria-controls^=' + slideId + '-item]', str, getCssRulesLength(sheet));
        }
        setAttrs(navItems[navCurrentIndex], {
          'aria-label': navStr + (navCurrentIndex + 1) + navStrCurrent
        });
        removeAttrs(navItems[navCurrentIndex], 'tabindex');
        addClass(navItems[navCurrentIndex], navActiveClass); // add events

        addEvents(navContainer, navEvents);
      } // == controlsInit ==

      if (hasControls) {
        if (!controlsContainer && (!prevButton || !nextButton)) {
          outerWrapper.insertAdjacentHTML(getInsertPosition(options.controlsPosition), '<div class="tns-controls" aria-label="Carousel Navigation" tabindex="0"><button type="button" data-controls="prev" tabindex="-1" aria-controls="' + slideId + '">' + controlsText[0] + '</button><button type="button" data-controls="next" tabindex="-1" aria-controls="' + slideId + '">' + controlsText[1] + '</button></div>');
          controlsContainer = outerWrapper.querySelector('.tns-controls');
        }
        if (!prevButton || !nextButton) {
          prevButton = controlsContainer.children[0];
          nextButton = controlsContainer.children[1];
        }
        if (options.controlsContainer) {
          setAttrs(controlsContainer, {
            'aria-label': 'Carousel Navigation',
            'tabindex': '0'
          });
        }
        if (options.controlsContainer || options.prevButton && options.nextButton) {
          setAttrs([prevButton, nextButton], {
            'aria-controls': slideId,
            'tabindex': '-1'
          });
        }
        if (options.controlsContainer || options.prevButton && options.nextButton) {
          setAttrs(prevButton, {
            'data-controls': 'prev'
          });
          setAttrs(nextButton, {
            'data-controls': 'next'
          });
        }
        prevIsButton = isButton(prevButton);
        nextIsButton = isButton(nextButton);
        updateControlsStatus(); // add events

        if (controlsContainer) {
          addEvents(controlsContainer, controlsEvents);
        } else {
          addEvents(prevButton, controlsEvents);
          addEvents(nextButton, controlsEvents);
        }
      } // hide tools if needed

      disableUI();
    }
    function initEvents() {
      // add events
      if (carousel && TRANSITIONEND) {
        var eve = {};
        eve[TRANSITIONEND] = onTransitionEnd;
        addEvents(container, eve);
      }
      if (touch) {
        addEvents(container, touchEvents, options.preventScrollOnTouch);
      }
      if (mouseDrag) {
        addEvents(container, dragEvents);
      }
      if (arrowKeys) {
        addEvents(doc, docmentKeydownEvent);
      }
      if (nested === 'inner') {
        events.on('outerResized', function () {
          resizeTasks();
          events.emit('innerLoaded', info());
        });
      } else if (responsive || fixedWidth || autoWidth || autoHeight || !horizontal) {
        addEvents(win, {
          'resize': onResize
        });
      }
      if (autoHeight) {
        if (nested === 'outer') {
          events.on('innerLoaded', doAutoHeight);
        } else if (!disable) {
          doAutoHeight();
        }
      }
      doLazyLoad();
      if (disable) {
        disableSlider();
      } else if (freeze) {
        freezeSlider();
      }
      events.on('indexChanged', additionalUpdates);
      if (nested === 'inner') {
        events.emit('innerLoaded', info());
      }
      if (typeof onInit === 'function') {
        onInit(info());
      }
      isOn = true;
    }
    function destroy() {
      // sheet
      sheet.disabled = true;
      if (sheet.ownerNode) {
        sheet.ownerNode.remove();
      } // remove win event listeners

      removeEvents(win, {
        'resize': onResize
      }); // arrowKeys, controls, nav

      if (arrowKeys) {
        removeEvents(doc, docmentKeydownEvent);
      }
      if (controlsContainer) {
        removeEvents(controlsContainer, controlsEvents);
      }
      if (navContainer) {
        removeEvents(navContainer, navEvents);
      } // autoplay

      removeEvents(container, hoverEvents);
      removeEvents(container, visibilityEvent);
      if (autoplayButton) {
        removeEvents(autoplayButton, {
          'click': toggleAutoplay
        });
      }
      if (autoplay) {
        clearInterval(autoplayTimer);
      } // container

      if (carousel && TRANSITIONEND) {
        var eve = {};
        eve[TRANSITIONEND] = onTransitionEnd;
        removeEvents(container, eve);
      }
      if (touch) {
        removeEvents(container, touchEvents);
      }
      if (mouseDrag) {
        removeEvents(container, dragEvents);
      } // cache Object values in options && reset HTML

      var htmlList = [containerHTML, controlsContainerHTML, prevButtonHTML, nextButtonHTML, navContainerHTML, autoplayButtonHTML];
      tnsList.forEach(function (item, i) {
        var el = item === 'container' ? outerWrapper : options[item];
        if (_typeof(el) === 'object' && el) {
          var prevEl = el.previousElementSibling ? el.previousElementSibling : false,
            parentEl = el.parentNode;
          el.outerHTML = htmlList[i];
          options[item] = prevEl ? prevEl.nextElementSibling : parentEl.firstElementChild;
        }
      }); // reset variables

      tnsList = animateIn = animateOut = animateDelay = animateNormal = horizontal = outerWrapper = innerWrapper = container = containerParent = containerHTML = slideItems = slideCount = breakpointZone = windowWidth = autoWidth = fixedWidth = edgePadding = gutter = viewport = items = slideBy = viewportMax = arrowKeys = speed = rewind = loop = autoHeight = sheet = lazyload = slidePositions = slideItemsOut = cloneCount = slideCountNew = hasRightDeadZone = rightBoundary = updateIndexBeforeTransform = transformAttr = transformPrefix = transformPostfix = getIndexMax = index = indexCached = indexMin = indexMax = swipeAngle = moveDirectionExpected = running = onInit = events = newContainerClasses = slideId = disable = disabled = freezable = freeze = frozen = controlsEvents = navEvents = hoverEvents = visibilityEvent = docmentKeydownEvent = touchEvents = dragEvents = hasControls = hasNav = navAsThumbnails = hasAutoplay = hasTouch = hasMouseDrag = slideActiveClass = imgCompleteClass = imgEvents = imgsComplete = controls = controlsText = controlsContainer = controlsContainerHTML = prevButton = nextButton = prevIsButton = nextIsButton = nav = navContainer = navContainerHTML = navItems = pages = pagesCached = navClicked = navCurrentIndex = navCurrentIndexCached = navActiveClass = navStr = navStrCurrent = autoplay = autoplayTimeout = autoplayDirection = autoplayText = autoplayHoverPause = autoplayButton = autoplayButtonHTML = autoplayResetOnVisibility = autoplayHtmlStrings = autoplayTimer = animating = autoplayHoverPaused = autoplayUserPaused = autoplayVisibilityPaused = initPosition = lastPosition = translateInit = panStart = rafIndex = getDist = touch = mouseDrag = null; // check variables
      // [animateIn, animateOut, animateDelay, animateNormal, horizontal, outerWrapper, innerWrapper, container, containerParent, containerHTML, slideItems, slideCount, breakpointZone, windowWidth, autoWidth, fixedWidth, edgePadding, gutter, viewport, items, slideBy, viewportMax, arrowKeys, speed, rewind, loop, autoHeight, sheet, lazyload, slidePositions, slideItemsOut, cloneCount, slideCountNew, hasRightDeadZone, rightBoundary, updateIndexBeforeTransform, transformAttr, transformPrefix, transformPostfix, getIndexMax, index, indexCached, indexMin, indexMax, resizeTimer, swipeAngle, moveDirectionExpected, running, onInit, events, newContainerClasses, slideId, disable, disabled, freezable, freeze, frozen, controlsEvents, navEvents, hoverEvents, visibilityEvent, docmentKeydownEvent, touchEvents, dragEvents, hasControls, hasNav, navAsThumbnails, hasAutoplay, hasTouch, hasMouseDrag, slideActiveClass, imgCompleteClass, imgEvents, imgsComplete, controls, controlsText, controlsContainer, controlsContainerHTML, prevButton, nextButton, prevIsButton, nextIsButton, nav, navContainer, navContainerHTML, navItems, pages, pagesCached, navClicked, navCurrentIndex, navCurrentIndexCached, navActiveClass, navStr, navStrCurrent, autoplay, autoplayTimeout, autoplayDirection, autoplayText, autoplayHoverPause, autoplayButton, autoplayButtonHTML, autoplayResetOnVisibility, autoplayHtmlStrings, autoplayTimer, animating, autoplayHoverPaused, autoplayUserPaused, autoplayVisibilityPaused, initPosition, lastPosition, translateInit, disX, disY, panStart, rafIndex, getDist, touch, mouseDrag ].forEach(function(item) { if (item !== null) { console.log(item); } });

      for (var a in this) {
        if (a !== 'rebuild') {
          this[a] = null;
        }
      }
      isOn = false;
    } // === ON RESIZE ===
    // responsive || fixedWidth || autoWidth || !horizontal

    function onResize(e) {
      raf(function () {
        resizeTasks(getEvent(e));
      });
    }
    function resizeTasks(e) {
      if (!isOn) {
        return;
      }
      if (nested === 'outer') {
        events.emit('outerResized', info(e));
      }
      windowWidth = getWindowWidth();
      var bpChanged,
        breakpointZoneTem = breakpointZone,
        needContainerTransform = false;
      if (responsive) {
        setBreakpointZone();
        bpChanged = breakpointZoneTem !== breakpointZone; // if (hasRightDeadZone) { needContainerTransform = true; } // *?

        if (bpChanged) {
          events.emit('newBreakpointStart', info(e));
        }
      }
      var indChanged,
        itemsChanged,
        itemsTem = items,
        disableTem = disable,
        freezeTem = freeze,
        arrowKeysTem = arrowKeys,
        controlsTem = controls,
        navTem = nav,
        touchTem = touch,
        mouseDragTem = mouseDrag,
        autoplayTem = autoplay,
        autoplayHoverPauseTem = autoplayHoverPause,
        autoplayResetOnVisibilityTem = autoplayResetOnVisibility,
        indexTem = index;
      if (bpChanged) {
        var fixedWidthTem = fixedWidth,
          autoHeightTem = autoHeight,
          controlsTextTem = controlsText,
          centerTem = center,
          autoplayTextTem = autoplayText;
        if (!CSSMQ) {
          var gutterTem = gutter,
            edgePaddingTem = edgePadding;
        }
      } // get option:
      // fixed width: viewport, fixedWidth, gutter => items
      // others: window width => all variables
      // all: items => slideBy

      arrowKeys = getOption('arrowKeys');
      controls = getOption('controls');
      nav = getOption('nav');
      touch = getOption('touch');
      center = getOption('center');
      mouseDrag = getOption('mouseDrag');
      autoplay = getOption('autoplay');
      autoplayHoverPause = getOption('autoplayHoverPause');
      autoplayResetOnVisibility = getOption('autoplayResetOnVisibility');
      if (bpChanged) {
        disable = getOption('disable');
        fixedWidth = getOption('fixedWidth');
        speed = getOption('speed');
        autoHeight = getOption('autoHeight');
        controlsText = getOption('controlsText');
        autoplayText = getOption('autoplayText');
        autoplayTimeout = getOption('autoplayTimeout');
        if (!CSSMQ) {
          edgePadding = getOption('edgePadding');
          gutter = getOption('gutter');
        }
      } // update options

      resetVariblesWhenDisable(disable);
      viewport = getViewportWidth(); // <= edgePadding, gutter

      if ((!horizontal || autoWidth) && !disable) {
        setSlidePositions();
        if (!horizontal) {
          updateContentWrapperHeight(); // <= setSlidePositions

          needContainerTransform = true;
        }
      }
      if (fixedWidth || autoWidth) {
        rightBoundary = getRightBoundary(); // autoWidth: <= viewport, slidePositions, gutter
        // fixedWidth: <= viewport, fixedWidth, gutter

        indexMax = getIndexMax(); // autoWidth: <= rightBoundary, slidePositions
        // fixedWidth: <= rightBoundary, fixedWidth, gutter
      }

      if (bpChanged || fixedWidth) {
        items = getOption('items');
        slideBy = getOption('slideBy');
        itemsChanged = items !== itemsTem;
        if (itemsChanged) {
          if (!fixedWidth && !autoWidth) {
            indexMax = getIndexMax();
          } // <= items
          // check index before transform in case
          // slider reach the right edge then items become bigger

          updateIndex();
        }
      }
      if (bpChanged) {
        if (disable !== disableTem) {
          if (disable) {
            disableSlider();
          } else {
            enableSlider(); // <= slidePositions, rightBoundary, indexMax
          }
        }
      }

      if (freezable && (bpChanged || fixedWidth || autoWidth)) {
        freeze = getFreeze(); // <= autoWidth: slidePositions, gutter, viewport, rightBoundary
        // <= fixedWidth: fixedWidth, gutter, rightBoundary
        // <= others: items

        if (freeze !== freezeTem) {
          if (freeze) {
            doContainerTransform(getContainerTransformValue(getStartIndex(0)));
            freezeSlider();
          } else {
            unfreezeSlider();
            needContainerTransform = true;
          }
        }
      }
      resetVariblesWhenDisable(disable || freeze); // controls, nav, touch, mouseDrag, arrowKeys, autoplay, autoplayHoverPause, autoplayResetOnVisibility

      if (!autoplay) {
        autoplayHoverPause = autoplayResetOnVisibility = false;
      }
      if (arrowKeys !== arrowKeysTem) {
        arrowKeys ? addEvents(doc, docmentKeydownEvent) : removeEvents(doc, docmentKeydownEvent);
      }
      if (controls !== controlsTem) {
        if (controls) {
          if (controlsContainer) {
            showElement(controlsContainer);
          } else {
            if (prevButton) {
              showElement(prevButton);
            }
            if (nextButton) {
              showElement(nextButton);
            }
          }
        } else {
          if (controlsContainer) {
            hideElement(controlsContainer);
          } else {
            if (prevButton) {
              hideElement(prevButton);
            }
            if (nextButton) {
              hideElement(nextButton);
            }
          }
        }
      }
      if (nav !== navTem) {
        if (nav) {
          showElement(navContainer);
          updateNavVisibility();
        } else {
          hideElement(navContainer);
        }
      }
      if (touch !== touchTem) {
        touch ? addEvents(container, touchEvents, options.preventScrollOnTouch) : removeEvents(container, touchEvents);
      }
      if (mouseDrag !== mouseDragTem) {
        mouseDrag ? addEvents(container, dragEvents) : removeEvents(container, dragEvents);
      }
      if (autoplay !== autoplayTem) {
        if (autoplay) {
          if (autoplayButton) {
            showElement(autoplayButton);
          }
          if (!animating && !autoplayUserPaused) {
            startAutoplay();
          }
        } else {
          if (autoplayButton) {
            hideElement(autoplayButton);
          }
          if (animating) {
            stopAutoplay();
          }
        }
      }
      if (autoplayHoverPause !== autoplayHoverPauseTem) {
        autoplayHoverPause ? addEvents(container, hoverEvents) : removeEvents(container, hoverEvents);
      }
      if (autoplayResetOnVisibility !== autoplayResetOnVisibilityTem) {
        autoplayResetOnVisibility ? addEvents(doc, visibilityEvent) : removeEvents(doc, visibilityEvent);
      }
      if (bpChanged) {
        if (fixedWidth !== fixedWidthTem || center !== centerTem) {
          needContainerTransform = true;
        }
        if (autoHeight !== autoHeightTem) {
          if (!autoHeight) {
            innerWrapper.style.height = '';
          }
        }
        if (controls && controlsText !== controlsTextTem) {
          prevButton.innerHTML = controlsText[0];
          nextButton.innerHTML = controlsText[1];
        }
        if (autoplayButton && autoplayText !== autoplayTextTem) {
          var i = autoplay ? 1 : 0,
            html = autoplayButton.innerHTML,
            len = html.length - autoplayTextTem[i].length;
          if (html.substring(len) === autoplayTextTem[i]) {
            autoplayButton.innerHTML = html.substring(0, len) + autoplayText[i];
          }
        }
      } else {
        if (center && (fixedWidth || autoWidth)) {
          needContainerTransform = true;
        }
      }
      if (itemsChanged || fixedWidth && !autoWidth) {
        pages = getPages();
        updateNavVisibility();
      }
      indChanged = index !== indexTem;
      if (indChanged) {
        events.emit('indexChanged', info());
        needContainerTransform = true;
      } else if (itemsChanged) {
        if (!indChanged) {
          additionalUpdates();
        }
      } else if (fixedWidth || autoWidth) {
        doLazyLoad();
        updateSlideStatus();
        updateLiveRegion();
      }
      if (itemsChanged && !carousel) {
        updateGallerySlidePositions();
      }
      if (!disable && !freeze) {
        // non-mediaqueries: IE8
        if (bpChanged && !CSSMQ) {
          // middle wrapper styles
          // inner wrapper styles
          if (edgePadding !== edgePaddingTem || gutter !== gutterTem) {
            innerWrapper.style.cssText = getInnerWrapperStyles(edgePadding, gutter, fixedWidth, speed, autoHeight);
          }
          if (horizontal) {
            // container styles
            if (carousel) {
              container.style.width = getContainerWidth(fixedWidth, gutter, items);
            } // slide styles

            var str = getSlideWidthStyle(fixedWidth, gutter, items) + getSlideGutterStyle(gutter); // remove the last line and
            // add new styles

            removeCSSRule(sheet, getCssRulesLength(sheet) - 1);
            addCSSRule(sheet, '#' + slideId + ' > .tns-item', str, getCssRulesLength(sheet));
          }
        } // auto height

        if (autoHeight) {
          doAutoHeight();
        }
        if (needContainerTransform) {
          doContainerTransformSilent();
          indexCached = index;
        }
      }
      if (bpChanged) {
        events.emit('newBreakpointEnd', info(e));
      }
    } // === INITIALIZATION FUNCTIONS === //

    function getFreeze() {
      if (!fixedWidth && !autoWidth) {
        var a = center ? items - (items - 1) / 2 : items;
        return slideCount <= a;
      }
      var width = fixedWidth ? (fixedWidth + gutter) * slideCount : slidePositions[slideCount],
        vp = edgePadding ? viewport + edgePadding * 2 : viewport + gutter;
      if (center) {
        vp -= fixedWidth ? (viewport - fixedWidth) / 2 : (viewport - (slidePositions[index + 1] - slidePositions[index] - gutter)) / 2;
      }
      return width <= vp;
    }
    function setBreakpointZone() {
      breakpointZone = 0;
      for (var bp in responsive) {
        bp = parseInt(bp); // convert string to number

        if (windowWidth >= bp) {
          breakpointZone = bp;
        }
      }
    } // (slideBy, indexMin, indexMax) => index

    var updateIndex = function () {
      return loop ? carousel ?
      // loop + carousel
      function () {
        var leftEdge = indexMin,
          rightEdge = indexMax;
        leftEdge += slideBy;
        rightEdge -= slideBy; // adjust edges when has edge paddings
        // or fixed-width slider with extra space on the right side

        if (edgePadding) {
          leftEdge += 1;
          rightEdge -= 1;
        } else if (fixedWidth) {
          if ((viewport + gutter) % (fixedWidth + gutter)) {
            rightEdge -= 1;
          }
        }
        if (cloneCount) {
          if (index > rightEdge) {
            index -= slideCount;
          } else if (index < leftEdge) {
            index += slideCount;
          }
        }
      } :
      // loop + gallery
      function () {
        if (index > indexMax) {
          while (index >= indexMin + slideCount) {
            index -= slideCount;
          }
        } else if (index < indexMin) {
          while (index <= indexMax - slideCount) {
            index += slideCount;
          }
        }
      } :
      // non-loop
      function () {
        index = Math.max(indexMin, Math.min(indexMax, index));
      };
    }();
    function disableUI() {
      if (!autoplay && autoplayButton) {
        hideElement(autoplayButton);
      }
      if (!nav && navContainer) {
        hideElement(navContainer);
      }
      if (!controls) {
        if (controlsContainer) {
          hideElement(controlsContainer);
        } else {
          if (prevButton) {
            hideElement(prevButton);
          }
          if (nextButton) {
            hideElement(nextButton);
          }
        }
      }
    }
    function enableUI() {
      if (autoplay && autoplayButton) {
        showElement(autoplayButton);
      }
      if (nav && navContainer) {
        showElement(navContainer);
      }
      if (controls) {
        if (controlsContainer) {
          showElement(controlsContainer);
        } else {
          if (prevButton) {
            showElement(prevButton);
          }
          if (nextButton) {
            showElement(nextButton);
          }
        }
      }
    }
    function freezeSlider() {
      if (frozen) {
        return;
      } // remove edge padding from inner wrapper

      if (edgePadding) {
        innerWrapper.style.margin = '0px';
      } // add class tns-transparent to cloned slides

      if (cloneCount) {
        var str = 'tns-transparent';
        for (var i = cloneCount; i--;) {
          if (carousel) {
            addClass(slideItems[i], str);
          }
          addClass(slideItems[slideCountNew - i - 1], str);
        }
      } // update tools

      disableUI();
      frozen = true;
    }
    function unfreezeSlider() {
      if (!frozen) {
        return;
      } // restore edge padding for inner wrapper
      // for mordern browsers

      if (edgePadding && CSSMQ) {
        innerWrapper.style.margin = '';
      } // remove class tns-transparent to cloned slides

      if (cloneCount) {
        var str = 'tns-transparent';
        for (var i = cloneCount; i--;) {
          if (carousel) {
            removeClass(slideItems[i], str);
          }
          removeClass(slideItems[slideCountNew - i - 1], str);
        }
      } // update tools

      enableUI();
      frozen = false;
    }
    function disableSlider() {
      if (disabled) {
        return;
      }
      sheet.disabled = true;
      container.className = container.className.replace(newContainerClasses.substring(1), '');
      removeAttrs(container, ['style']);
      if (loop) {
        for (var j = cloneCount; j--;) {
          if (carousel) {
            hideElement(slideItems[j]);
          }
          hideElement(slideItems[slideCountNew - j - 1]);
        }
      } // vertical slider

      if (!horizontal || !carousel) {
        removeAttrs(innerWrapper, ['style']);
      } // gallery

      if (!carousel) {
        for (var i = index, l = index + slideCount; i < l; i++) {
          var item = slideItems[i];
          removeAttrs(item, ['style']);
          removeClass(item, animateIn);
          removeClass(item, animateNormal);
        }
      } // update tools

      disableUI();
      disabled = true;
    }
    function enableSlider() {
      if (!disabled) {
        return;
      }
      sheet.disabled = false;
      container.className += newContainerClasses;
      doContainerTransformSilent();
      if (loop) {
        for (var j = cloneCount; j--;) {
          if (carousel) {
            showElement(slideItems[j]);
          }
          showElement(slideItems[slideCountNew - j - 1]);
        }
      } // gallery

      if (!carousel) {
        for (var i = index, l = index + slideCount; i < l; i++) {
          var item = slideItems[i],
            classN = i < index + items ? animateIn : animateNormal;
          item.style.left = (i - index) * 100 / items + '%';
          addClass(item, classN);
        }
      } // update tools

      enableUI();
      disabled = false;
    }
    function updateLiveRegion() {
      var str = getLiveRegionStr();
      if (liveregionCurrent.innerHTML !== str) {
        liveregionCurrent.innerHTML = str;
      }
    }
    function getLiveRegionStr() {
      var arr = getVisibleSlideRange(),
        start = arr[0] + 1,
        end = arr[1] + 1;
      return start === end ? start + '' : start + ' to ' + end;
    }
    function getVisibleSlideRange(val) {
      if (val == null) {
        val = getContainerTransformValue();
      }
      var start = index,
        end,
        rangestart,
        rangeend; // get range start, range end for autoWidth and fixedWidth

      if (center || edgePadding) {
        if (autoWidth || fixedWidth) {
          rangestart = -(parseFloat(val) + edgePadding);
          rangeend = rangestart + viewport + edgePadding * 2;
        }
      } else {
        if (autoWidth) {
          rangestart = slidePositions[index];
          rangeend = rangestart + viewport;
        }
      } // get start, end
      // - check auto width

      if (autoWidth) {
        slidePositions.forEach(function (point, i) {
          if (i < slideCountNew) {
            if ((center || edgePadding) && point <= rangestart + 0.5) {
              start = i;
            }
            if (rangeend - point >= 0.5) {
              end = i;
            }
          }
        }); // - check percentage width, fixed width
      } else {
        if (fixedWidth) {
          var cell = fixedWidth + gutter;
          if (center || edgePadding) {
            start = Math.floor(rangestart / cell);
            end = Math.ceil(rangeend / cell - 1);
          } else {
            end = start + Math.ceil(viewport / cell) - 1;
          }
        } else {
          if (center || edgePadding) {
            var a = items - 1;
            if (center) {
              start -= a / 2;
              end = index + a / 2;
            } else {
              end = index + a;
            }
            if (edgePadding) {
              var b = edgePadding * items / viewport;
              start -= b;
              end += b;
            }
            start = Math.floor(start);
            end = Math.ceil(end);
          } else {
            end = start + items - 1;
          }
        }
        start = Math.max(start, 0);
        end = Math.min(end, slideCountNew - 1);
      }
      return [start, end];
    }
    function doLazyLoad() {
      if (lazyload && !disable) {
        var arg = getVisibleSlideRange();
        arg.push(lazyloadSelector);
        getImageArray.apply(null, arg).forEach(function (img) {
          if (!hasClass(img, imgCompleteClass)) {
            // stop propagation transitionend event to container
            var eve = {};
            eve[TRANSITIONEND] = function (e) {
              e.stopPropagation();
            };
            addEvents(img, eve);
            addEvents(img, imgEvents); // update src

            img.src = getAttr(img, 'data-src'); // update srcset

            var srcset = getAttr(img, 'data-srcset');
            if (srcset) {
              img.srcset = srcset;
            }
            addClass(img, 'loading');
          }
        });
      }
    }
    function onImgLoaded(e) {
      imgLoaded(getTarget(e));
    }
    function onImgFailed(e) {
      imgFailed(getTarget(e));
    }
    function imgLoaded(img) {
      addClass(img, 'loaded');
      imgCompleted(img);
    }
    function imgFailed(img) {
      addClass(img, 'failed');
      imgCompleted(img);
    }
    function imgCompleted(img) {
      addClass(img, imgCompleteClass);
      removeClass(img, 'loading');
      removeEvents(img, imgEvents);
    }
    function getImageArray(start, end, imgSelector) {
      var imgs = [];
      if (!imgSelector) {
        imgSelector = 'img';
      }
      while (start <= end) {
        forEach(slideItems[start].querySelectorAll(imgSelector), function (img) {
          imgs.push(img);
        });
        start++;
      }
      return imgs;
    } // check if all visible images are loaded
    // and update container height if it's done

    function doAutoHeight() {
      var imgs = getImageArray.apply(null, getVisibleSlideRange());
      raf(function () {
        imgsLoadedCheck(imgs, updateInnerWrapperHeight);
      });
    }
    function imgsLoadedCheck(imgs, cb) {
      // execute callback function if all images are complete
      if (imgsComplete) {
        return cb();
      } // check image classes

      imgs.forEach(function (img, index) {
        if (!lazyload && img.complete) {
          imgCompleted(img);
        } // Check image.complete

        if (hasClass(img, imgCompleteClass)) {
          imgs.splice(index, 1);
        }
      }); // execute callback function if selected images are all complete

      if (!imgs.length) {
        return cb();
      } // otherwise execute this functiona again

      raf(function () {
        imgsLoadedCheck(imgs, cb);
      });
    }
    function additionalUpdates() {
      doLazyLoad();
      updateSlideStatus();
      updateLiveRegion();
      updateControlsStatus();
      updateNavStatus();
    }
    function update_carousel_transition_duration() {
      if (carousel && autoHeight) {
        middleWrapper.style[TRANSITIONDURATION] = speed / 1000 + 's';
      }
    }
    function getMaxSlideHeight(slideStart, slideRange) {
      var heights = [];
      for (var i = slideStart, l = Math.min(slideStart + slideRange, slideCountNew); i < l; i++) {
        heights.push(slideItems[i].offsetHeight);
      }
      return Math.max.apply(null, heights);
    } // update inner wrapper height
    // 1. get the max-height of the visible slides
    // 2. set transitionDuration to speed
    // 3. update inner wrapper height to max-height
    // 4. set transitionDuration to 0s after transition done

    function updateInnerWrapperHeight() {
      var maxHeight = autoHeight ? getMaxSlideHeight(index, items) : getMaxSlideHeight(cloneCount, slideCount),
        wp = middleWrapper ? middleWrapper : innerWrapper;
      if (wp.style.height !== maxHeight) {
        wp.style.height = maxHeight + 'px';
      }
    } // get the distance from the top edge of the first slide to each slide
    // (init) => slidePositions

    function setSlidePositions() {
      slidePositions = [0];
      var attr = horizontal ? 'left' : 'top',
        attr2 = horizontal ? 'right' : 'bottom',
        base = slideItems[0].getBoundingClientRect()[attr];
      forEach(slideItems, function (item, i) {
        // skip the first slide
        if (i) {
          slidePositions.push(item.getBoundingClientRect()[attr] - base);
        } // add the end edge

        if (i === slideCountNew - 1) {
          slidePositions.push(item.getBoundingClientRect()[attr2] - base);
        }
      });
    } // update slide

    function updateSlideStatus() {
      var range = getVisibleSlideRange(),
        start = range[0],
        end = range[1];
      forEach(slideItems, function (item, i) {
        // show slides
        if (i >= start && i <= end) {
          if (hasAttr(item, 'aria-hidden')) {
            removeAttrs(item, ['aria-hidden', 'tabindex']);
            addClass(item, slideActiveClass);
          } // hide slides
        } else {
          if (!hasAttr(item, 'aria-hidden')) {
            setAttrs(item, {
              'aria-hidden': 'true',
              'tabindex': '-1'
            });
            removeClass(item, slideActiveClass);
          }
        }
      });
    } // gallery: update slide position

    function updateGallerySlidePositions() {
      var l = index + Math.min(slideCount, items);
      for (var i = slideCountNew; i--;) {
        var item = slideItems[i];
        if (i >= index && i < l) {
          // add transitions to visible slides when adjusting their positions
          addClass(item, 'tns-moving');
          item.style.left = (i - index) * 100 / items + '%';
          addClass(item, animateIn);
          removeClass(item, animateNormal);
        } else if (item.style.left) {
          item.style.left = '';
          addClass(item, animateNormal);
          removeClass(item, animateIn);
        } // remove outlet animation

        removeClass(item, animateOut);
      } // removing '.tns-moving'

      setTimeout(function () {
        forEach(slideItems, function (el) {
          removeClass(el, 'tns-moving');
        });
      }, 300);
    } // set tabindex on Nav

    function updateNavStatus() {
      // get current nav
      if (nav) {
        navCurrentIndex = navClicked >= 0 ? navClicked : getCurrentNavIndex();
        navClicked = -1;
        if (navCurrentIndex !== navCurrentIndexCached) {
          var navPrev = navItems[navCurrentIndexCached],
            navCurrent = navItems[navCurrentIndex];
          setAttrs(navPrev, {
            'tabindex': '-1',
            'aria-label': navStr + (navCurrentIndexCached + 1)
          });
          removeClass(navPrev, navActiveClass);
          setAttrs(navCurrent, {
            'aria-label': navStr + (navCurrentIndex + 1) + navStrCurrent
          });
          removeAttrs(navCurrent, 'tabindex');
          addClass(navCurrent, navActiveClass);
          navCurrentIndexCached = navCurrentIndex;
        }
      }
    }
    function getLowerCaseNodeName(el) {
      return el.nodeName.toLowerCase();
    }
    function isButton(el) {
      return getLowerCaseNodeName(el) === 'button';
    }
    function isAriaDisabled(el) {
      return el.getAttribute('aria-disabled') === 'true';
    }
    function disEnableElement(isButton, el, val) {
      if (isButton) {
        el.disabled = val;
      } else {
        el.setAttribute('aria-disabled', val.toString());
      }
    } // set 'disabled' to true on controls when reach the edges

    function updateControlsStatus() {
      if (!controls || rewind || loop) {
        return;
      }
      var prevDisabled = prevIsButton ? prevButton.disabled : isAriaDisabled(prevButton),
        nextDisabled = nextIsButton ? nextButton.disabled : isAriaDisabled(nextButton),
        disablePrev = index <= indexMin ? true : false,
        disableNext = !rewind && index >= indexMax ? true : false;
      if (disablePrev && !prevDisabled) {
        disEnableElement(prevIsButton, prevButton, true);
      }
      if (!disablePrev && prevDisabled) {
        disEnableElement(prevIsButton, prevButton, false);
      }
      if (disableNext && !nextDisabled) {
        disEnableElement(nextIsButton, nextButton, true);
      }
      if (!disableNext && nextDisabled) {
        disEnableElement(nextIsButton, nextButton, false);
      }
    } // set duration

    function resetDuration(el, str) {
      if (TRANSITIONDURATION) {
        el.style[TRANSITIONDURATION] = str;
      }
    }
    function getSliderWidth() {
      return fixedWidth ? (fixedWidth + gutter) * slideCountNew : slidePositions[slideCountNew];
    }
    function getCenterGap(num) {
      if (num == null) {
        num = index;
      }
      var gap = edgePadding ? gutter : 0;
      return autoWidth ? (viewport - gap - (slidePositions[num + 1] - slidePositions[num] - gutter)) / 2 : fixedWidth ? (viewport - fixedWidth) / 2 : (items - 1) / 2;
    }
    function getRightBoundary() {
      var gap = edgePadding ? gutter : 0,
        result = viewport + gap - getSliderWidth();
      if (center && !loop) {
        result = fixedWidth ? -(fixedWidth + gutter) * (slideCountNew - 1) - getCenterGap() : getCenterGap(slideCountNew - 1) - slidePositions[slideCountNew - 1];
      }
      if (result > 0) {
        result = 0;
      }
      return result;
    }
    function getContainerTransformValue(num) {
      if (num == null) {
        num = index;
      }
      var val;
      if (horizontal && !autoWidth) {
        if (fixedWidth) {
          val = -(fixedWidth + gutter) * num;
          if (center) {
            val += getCenterGap();
          }
        } else {
          var denominator = TRANSFORM ? slideCountNew : items;
          if (center) {
            num -= getCenterGap();
          }
          val = -num * 100 / denominator;
        }
      } else {
        val = -slidePositions[num];
        if (center && autoWidth) {
          val += getCenterGap();
        }
      }
      if (hasRightDeadZone) {
        val = Math.max(val, rightBoundary);
      }
      val += horizontal && !autoWidth && !fixedWidth ? '%' : 'px';
      return val;
    }
    function doContainerTransformSilent(val) {
      resetDuration(container, '0s');
      doContainerTransform(val);
    }
    function doContainerTransform(val) {
      if (val == null) {
        val = getContainerTransformValue();
      }
      container.style[transformAttr] = transformPrefix + val + transformPostfix;
    }
    function animateSlide(number, classOut, classIn, isOut) {
      var l = number + items;
      if (!loop) {
        l = Math.min(l, slideCountNew);
      }
      for (var i = number; i < l; i++) {
        var item = slideItems[i]; // set item positions

        if (!isOut) {
          item.style.left = (i - index) * 100 / items + '%';
        }
        if (animateDelay && TRANSITIONDELAY) {
          item.style[TRANSITIONDELAY] = item.style[ANIMATIONDELAY] = animateDelay * (i - number) / 1000 + 's';
        }
        removeClass(item, classOut);
        addClass(item, classIn);
        if (isOut) {
          slideItemsOut.push(item);
        }
      }
    } // make transfer after click/drag:
    // 1. change 'transform' property for mordern browsers
    // 2. change 'left' property for legacy browsers

    var transformCore = function () {
      return carousel ? function () {
        resetDuration(container, '');
        if (TRANSITIONDURATION || !speed) {
          // for morden browsers with non-zero duration or
          // zero duration for all browsers
          doContainerTransform(); // run fallback function manually
          // when duration is 0 / container is hidden

          if (!speed || !isVisible(container)) {
            onTransitionEnd();
          }
        } else {
          // for old browser with non-zero duration
          jsTransform(container, transformAttr, transformPrefix, transformPostfix, getContainerTransformValue(), speed, onTransitionEnd);
        }
        if (!horizontal) {
          updateContentWrapperHeight();
        }
      } : function () {
        slideItemsOut = [];
        var eve = {};
        eve[TRANSITIONEND] = eve[ANIMATIONEND] = onTransitionEnd;
        removeEvents(slideItems[indexCached], eve);
        addEvents(slideItems[index], eve);
        animateSlide(indexCached, animateIn, animateOut, true);
        animateSlide(index, animateNormal, animateIn); // run fallback function manually
        // when transition or animation not supported / duration is 0

        if (!TRANSITIONEND || !ANIMATIONEND || !speed || !isVisible(container)) {
          onTransitionEnd();
        }
      };
    }();
    function render(e, sliderMoved) {
      if (updateIndexBeforeTransform) {
        updateIndex();
      } // render when slider was moved (touch or drag) even though index may not change

      if (index !== indexCached || sliderMoved) {
        // events
        events.emit('indexChanged', info());
        events.emit('transitionStart', info());
        if (autoHeight) {
          doAutoHeight();
        } // pause autoplay when click or keydown from user

        if (animating && e && ['click', 'keydown'].indexOf(e.type) >= 0) {
          stopAutoplay();
        }
        running = true;
        transformCore();
      }
    }
    /*
     * Transfer prefixed properties to the same format
     * CSS: -Webkit-Transform => webkittransform
     * JS: WebkitTransform => webkittransform
     * @param {string} str - property
     *
     */

    function strTrans(str) {
      return str.toLowerCase().replace(/-/g, '');
    } // AFTER TRANSFORM
    // Things need to be done after a transfer:
    // 1. check index
    // 2. add classes to visible slide
    // 3. disable controls buttons when reach the first/last slide in non-loop slider
    // 4. update nav status
    // 5. lazyload images
    // 6. update container height

    function onTransitionEnd(event) {
      // check running on gallery mode
      // make sure trantionend/animationend events run only once
      if (carousel || running) {
        events.emit('transitionEnd', info(event));
        if (!carousel && slideItemsOut.length > 0) {
          for (var i = 0; i < slideItemsOut.length; i++) {
            var item = slideItemsOut[i]; // set item positions

            item.style.left = '';
            if (ANIMATIONDELAY && TRANSITIONDELAY) {
              item.style[ANIMATIONDELAY] = '';
              item.style[TRANSITIONDELAY] = '';
            }
            removeClass(item, animateOut);
            addClass(item, animateNormal);
          }
        }
        /* update slides, nav, controls after checking ...
         * => legacy browsers who don't support 'event'
         *    have to check event first, otherwise event.target will cause an error
         * => or 'gallery' mode:
         *   + event target is slide item
         * => or 'carousel' mode:
         *   + event target is container,
         *   + event.property is the same with transform attribute
         */

        if (!event || !carousel && event.target.parentNode === container || event.target === container && strTrans(event.propertyName) === strTrans(transformAttr)) {
          if (!updateIndexBeforeTransform) {
            var indexTem = index;
            updateIndex();
            if (index !== indexTem) {
              events.emit('indexChanged', info());
              doContainerTransformSilent();
            }
          }
          if (nested === 'inner') {
            events.emit('innerLoaded', info());
          }
          running = false;
          indexCached = index;
        }
      }
    } // # ACTIONS

    function goTo(targetIndex, e) {
      if (freeze) {
        return;
      } // prev slideBy

      if (targetIndex === 'prev') {
        onControlsClick(e, -1); // next slideBy
      } else if (targetIndex === 'next') {
        onControlsClick(e, 1); // go to exact slide
      } else {
        if (running) {
          if (preventActionWhenRunning) {
            return;
          } else {
            onTransitionEnd();
          }
        }
        var absIndex = getAbsIndex(),
          indexGap = 0;
        if (targetIndex === 'first') {
          indexGap = -absIndex;
        } else if (targetIndex === 'last') {
          indexGap = carousel ? slideCount - items - absIndex : slideCount - 1 - absIndex;
        } else {
          if (typeof targetIndex !== 'number') {
            targetIndex = parseInt(targetIndex);
          }
          if (!isNaN(targetIndex)) {
            // from directly called goTo function
            if (!e) {
              targetIndex = Math.max(0, Math.min(slideCount - 1, targetIndex));
            }
            indexGap = targetIndex - absIndex;
          }
        } // gallery: make sure new page won't overlap with current page

        if (!carousel && indexGap && Math.abs(indexGap) < items) {
          var factor = indexGap > 0 ? 1 : -1;
          indexGap += index + indexGap - slideCount >= indexMin ? slideCount * factor : slideCount * 2 * factor * -1;
        }
        index += indexGap; // make sure index is in range

        if (carousel && loop) {
          if (index < indexMin) {
            index += slideCount;
          }
          if (index > indexMax) {
            index -= slideCount;
          }
        } // if index is changed, start rendering

        if (getAbsIndex(index) !== getAbsIndex(indexCached)) {
          render(e);
        }
      }
    } // on controls click

    function onControlsClick(e, dir) {
      if (running) {
        if (preventActionWhenRunning) {
          return;
        } else {
          onTransitionEnd();
        }
      }
      var passEventObject;
      if (!dir) {
        e = getEvent(e);
        var target = getTarget(e);
        while (target !== controlsContainer && [prevButton, nextButton].indexOf(target) < 0) {
          target = target.parentNode;
        }
        var targetIn = [prevButton, nextButton].indexOf(target);
        if (targetIn >= 0) {
          passEventObject = true;
          dir = targetIn === 0 ? -1 : 1;
        }
      }
      if (rewind) {
        if (index === indexMin && dir === -1) {
          goTo('last', e);
          return;
        } else if (index === indexMax && dir === 1) {
          goTo('first', e);
          return;
        }
      }
      if (dir) {
        index += slideBy * dir;
        if (autoWidth) {
          index = Math.floor(index);
        } // pass e when click control buttons or keydown

        render(passEventObject || e && e.type === 'keydown' ? e : null);
      }
    } // on nav click

    function onNavClick(e) {
      if (running) {
        if (preventActionWhenRunning) {
          return;
        } else {
          onTransitionEnd();
        }
      }
      e = getEvent(e);
      var target = getTarget(e),
        navIndex; // find the clicked nav item

      while (target !== navContainer && !hasAttr(target, 'data-nav')) {
        target = target.parentNode;
      }
      if (hasAttr(target, 'data-nav')) {
        var navIndex = navClicked = Number(getAttr(target, 'data-nav')),
          targetIndexBase = fixedWidth || autoWidth ? navIndex * slideCount / pages : navIndex * items,
          targetIndex = navAsThumbnails ? navIndex : Math.min(Math.ceil(targetIndexBase), slideCount - 1);
        goTo(targetIndex, e);
        if (navCurrentIndex === navIndex) {
          if (animating) {
            stopAutoplay();
          }
          navClicked = -1; // reset navClicked
        }
      }
    } // autoplay functions

    function setAutoplayTimer() {
      autoplayTimer = setInterval(function () {
        onControlsClick(null, autoplayDirection);
      }, autoplayTimeout);
      animating = true;
    }
    function stopAutoplayTimer() {
      clearInterval(autoplayTimer);
      animating = false;
    }
    function updateAutoplayButton(action, txt) {
      setAttrs(autoplayButton, {
        'data-action': action
      });
      autoplayButton.innerHTML = autoplayHtmlStrings[0] + action + autoplayHtmlStrings[1] + txt;
    }
    function startAutoplay() {
      setAutoplayTimer();
      if (autoplayButton) {
        updateAutoplayButton('stop', autoplayText[1]);
      }
    }
    function stopAutoplay() {
      stopAutoplayTimer();
      if (autoplayButton) {
        updateAutoplayButton('start', autoplayText[0]);
      }
    } // programaitcally play/pause the slider

    function play() {
      if (autoplay && !animating) {
        startAutoplay();
        autoplayUserPaused = false;
      }
    }
    function pause() {
      if (animating) {
        stopAutoplay();
        autoplayUserPaused = true;
      }
    }
    function toggleAutoplay() {
      if (animating) {
        stopAutoplay();
        autoplayUserPaused = true;
      } else {
        startAutoplay();
        autoplayUserPaused = false;
      }
    }
    function onVisibilityChange() {
      if (doc.hidden) {
        if (animating) {
          stopAutoplayTimer();
          autoplayVisibilityPaused = true;
        }
      } else if (autoplayVisibilityPaused) {
        setAutoplayTimer();
        autoplayVisibilityPaused = false;
      }
    }
    function mouseoverPause() {
      if (animating) {
        stopAutoplayTimer();
        autoplayHoverPaused = true;
      }
    }
    function mouseoutRestart() {
      if (autoplayHoverPaused) {
        setAutoplayTimer();
        autoplayHoverPaused = false;
      }
    } // keydown events on document

    function onDocumentKeydown(e) {
      e = getEvent(e);
      var keyIndex = [KEYS.LEFT, KEYS.RIGHT].indexOf(e.keyCode);
      if (keyIndex >= 0) {
        onControlsClick(e, keyIndex === 0 ? -1 : 1);
      }
    } // on key control

    function onControlsKeydown(e) {
      e = getEvent(e);
      var keyIndex = [KEYS.LEFT, KEYS.RIGHT].indexOf(e.keyCode);
      if (keyIndex >= 0) {
        if (keyIndex === 0) {
          if (!prevButton.disabled) {
            onControlsClick(e, -1);
          }
        } else if (!nextButton.disabled) {
          onControlsClick(e, 1);
        }
      }
    } // set focus

    function setFocus(el) {
      el.focus();
    } // on key nav

    function onNavKeydown(e) {
      e = getEvent(e);
      var curElement = doc.activeElement;
      if (!hasAttr(curElement, 'data-nav')) {
        return;
      } // var code = e.keyCode,

      var keyIndex = [KEYS.LEFT, KEYS.RIGHT, KEYS.ENTER, KEYS.SPACE].indexOf(e.keyCode),
        navIndex = Number(getAttr(curElement, 'data-nav'));
      if (keyIndex >= 0) {
        if (keyIndex === 0) {
          if (navIndex > 0) {
            setFocus(navItems[navIndex - 1]);
          }
        } else if (keyIndex === 1) {
          if (navIndex < pages - 1) {
            setFocus(navItems[navIndex + 1]);
          }
        } else {
          navClicked = navIndex;
          goTo(navIndex, e);
        }
      }
    }
    function getEvent(e) {
      e = e || win.event;
      return isTouchEvent(e) ? e.changedTouches[0] : e;
    }
    function getTarget(e) {
      return e.target || win.event.srcElement;
    }
    function isTouchEvent(e) {
      return e.type.indexOf('touch') >= 0;
    }
    function preventDefaultBehavior(e) {
      e.preventDefault ? e.preventDefault() : e.returnValue = false;
    }
    function getMoveDirectionExpected() {
      return getTouchDirection(toDegree(lastPosition.y - initPosition.y, lastPosition.x - initPosition.x), swipeAngle) === options.axis;
    }
    function onPanStart(e) {
      if (running) {
        if (preventActionWhenRunning) {
          return;
        } else {
          onTransitionEnd();
        }
      }
      if (autoplay && animating) {
        stopAutoplayTimer();
      }
      panStart = true;
      if (rafIndex) {
        caf(rafIndex);
        rafIndex = null;
      }
      var $ = getEvent(e);
      events.emit(isTouchEvent(e) ? 'touchStart' : 'dragStart', info(e));
      if (!isTouchEvent(e) && ['img', 'a'].indexOf(getLowerCaseNodeName(getTarget(e))) >= 0) {
        preventDefaultBehavior(e);
      }
      lastPosition.x = initPosition.x = $.clientX;
      lastPosition.y = initPosition.y = $.clientY;
      if (carousel) {
        translateInit = parseFloat(container.style[transformAttr].replace(transformPrefix, ''));
        resetDuration(container, '0s');
      }
    }
    function onPanMove(e) {
      if (panStart) {
        var $ = getEvent(e);
        lastPosition.x = $.clientX;
        lastPosition.y = $.clientY;
        if (carousel) {
          if (!rafIndex) {
            rafIndex = raf(function () {
              panUpdate(e);
            });
          }
        } else {
          if (moveDirectionExpected === '?') {
            moveDirectionExpected = getMoveDirectionExpected();
          }
          if (moveDirectionExpected) {
            preventScroll = true;
          }
        }
        if ((typeof e.cancelable !== 'boolean' || e.cancelable) && preventScroll) {
          e.preventDefault();
        }
      }
    }
    function panUpdate(e) {
      if (!moveDirectionExpected) {
        panStart = false;
        return;
      }
      caf(rafIndex);
      if (panStart) {
        rafIndex = raf(function () {
          panUpdate(e);
        });
      }
      if (moveDirectionExpected === '?') {
        moveDirectionExpected = getMoveDirectionExpected();
      }
      if (moveDirectionExpected) {
        if (!preventScroll && isTouchEvent(e)) {
          preventScroll = true;
        }
        try {
          if (e.type) {
            events.emit(isTouchEvent(e) ? 'touchMove' : 'dragMove', info(e));
          }
        } catch (err) {}
        var x = translateInit,
          dist = getDist(lastPosition, initPosition);
        if (!horizontal || fixedWidth || autoWidth) {
          x += dist;
          x += 'px';
        } else {
          var percentageX = TRANSFORM ? dist * items * 100 / ((viewport + gutter) * slideCountNew) : dist * 100 / (viewport + gutter);
          x += percentageX;
          x += '%';
        }
        container.style[transformAttr] = transformPrefix + x + transformPostfix;
      }
    }
    function onPanEnd(e) {
      if (panStart) {
        if (rafIndex) {
          caf(rafIndex);
          rafIndex = null;
        }
        if (carousel) {
          resetDuration(container, '');
        }
        panStart = false;
        var $ = getEvent(e);
        lastPosition.x = $.clientX;
        lastPosition.y = $.clientY;
        var dist = getDist(lastPosition, initPosition);
        if (Math.abs(dist)) {
          // drag vs click
          if (!isTouchEvent(e)) {
            // prevent "click"
            var target = getTarget(e);
            addEvents(target, {
              'click': function preventClick(e) {
                preventDefaultBehavior(e);
                removeEvents(target, {
                  'click': preventClick
                });
              }
            });
          }
          if (carousel) {
            rafIndex = raf(function () {
              if (horizontal && !autoWidth) {
                var indexMoved = -dist * items / (viewport + gutter);
                indexMoved = dist > 0 ? Math.floor(indexMoved) : Math.ceil(indexMoved);
                index += indexMoved;
              } else {
                var moved = -(translateInit + dist);
                if (moved <= 0) {
                  index = indexMin;
                } else if (moved >= slidePositions[slideCountNew - 1]) {
                  index = indexMax;
                } else {
                  var i = 0;
                  while (i < slideCountNew && moved >= slidePositions[i]) {
                    index = i;
                    if (moved > slidePositions[i] && dist < 0) {
                      index += 1;
                    }
                    i++;
                  }
                }
              }
              render(e, dist);
              events.emit(isTouchEvent(e) ? 'touchEnd' : 'dragEnd', info(e));
            });
          } else {
            if (moveDirectionExpected) {
              onControlsClick(e, dist > 0 ? -1 : 1);
            }
          }
        }
      } // reset

      if (options.preventScrollOnTouch === 'auto') {
        preventScroll = false;
      }
      if (swipeAngle) {
        moveDirectionExpected = '?';
      }
      if (autoplay && !animating) {
        setAutoplayTimer();
      }
    } // === RESIZE FUNCTIONS === //
    // (slidePositions, index, items) => vertical_conentWrapper.height

    function updateContentWrapperHeight() {
      var wp = middleWrapper ? middleWrapper : innerWrapper;
      wp.style.height = slidePositions[index + items] - slidePositions[index] + 'px';
    }
    function getPages() {
      var rough = fixedWidth ? (fixedWidth + gutter) * slideCount / viewport : slideCount / items;
      return Math.min(Math.ceil(rough), slideCount);
    }
    /*
     * 1. update visible nav items list
     * 2. add "hidden" attributes to previous visible nav items
     * 3. remove "hidden" attrubutes to new visible nav items
     */

    function updateNavVisibility() {
      if (!nav || navAsThumbnails) {
        return;
      }
      if (pages !== pagesCached) {
        var min = pagesCached,
          max = pages,
          fn = showElement;
        if (pagesCached > pages) {
          min = pages;
          max = pagesCached;
          fn = hideElement;
        }
        while (min < max) {
          fn(navItems[min]);
          min++;
        } // cache pages

        pagesCached = pages;
      }
    }
    function info(e) {
      return {
        container: container,
        slideItems: slideItems,
        navContainer: navContainer,
        navItems: navItems,
        controlsContainer: controlsContainer,
        hasControls: hasControls,
        prevButton: prevButton,
        nextButton: nextButton,
        items: items,
        slideBy: slideBy,
        cloneCount: cloneCount,
        slideCount: slideCount,
        slideCountNew: slideCountNew,
        index: index,
        indexCached: indexCached,
        displayIndex: getCurrentSlide(),
        navCurrentIndex: navCurrentIndex,
        navCurrentIndexCached: navCurrentIndexCached,
        pages: pages,
        pagesCached: pagesCached,
        sheet: sheet,
        isOn: isOn,
        event: e || {}
      };
    }
    return {
      version: '2.9.4',
      getInfo: info,
      events: events,
      goTo: goTo,
      play: play,
      pause: pause,
      isOn: isOn,
      updateSliderHeight: updateInnerWrapperHeight,
      refresh: initSliderTransform,
      destroy: destroy,
      rebuild: function rebuild() {
        return tns(extend(options, optionsElements));
      }
    };
  };

  // exports.tns = tns;

  // -----------------------------------------------------------------------------------------------------------------------
  return tns;
}();
/**!
 * V23 ToggleBox
 * @author	Mvelarde   <miguel@velarde23.com>
 * @license MIT
 */

(function v23ToggleBoxModule(factory) {
  "use strict";

  if (typeof define === "function" && define.amd) {
    define(factory);
  } else if (typeof module != "undefined" && typeof module.exports != "undefined") {
    module.exports = factory();
  } else {
    /* jshint sub:true */
    window["V23_ToggleBox"] = factory();
  }
})(function v23ToggleBoxFactory() {
  "use strict";

  var instances = [],
    version = '5.8.31',
    timers = {};

  /**
   * @class  V23_ToggleBox
   * @param  {HTMLElement}  el
   * @param  {Object}       [options]
   */
  function V23_ToggleBox(el, options) {
    if (!(el && el.nodeType && el.nodeType === 1)) {
      console.log('V23 ToggleBox Error: `el` must be HTMLElement, and not ' + {}.toString.call(el));
      return;
    }
    if (!this._createInstance(el)) return;
    this.el = el; // root element
    this.activeTemplate = null;
    this.initialUrl = window.location.href;
    this._handleOptions(options);

    // Bind all private methods
    for (var fn in this) {
      if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
        this[fn] = this[fn].bind(this);
      }
    }
    this.nav = this.el.getElementsByClassName('v23-togglebox__nav')[0];
    this.itemsBox = this.el.getElementsByClassName('v23-togglebox__items')[0];
    this.items = [];
    this._saveItems();
    if (this.items.length > 0) {
      this._handle_template();
      this._attach_click_events();
      this._change_active_tab_if_hash_in_url();
      this._attach_resize_events();
      this._attach_hashchange_events();
    }
  }
  ;
  V23_ToggleBox.prototype = {
    _handleOptions: function _handleOptions(options) {
      // options configured as data-attributes
      var dataOptions = {},
        dataTemplate = this.el.dataset.template,
        dataBreakpoints = this.el.dataset.breakpoints,
        dataHeaderHeight = this.el.dataset.headerheight,
        dataScrolltop = this.el.dataset.scrolltop,
        dataScrollto = this.el.dataset.scrollto;
      if (dataTemplate != undefined) dataOptions.initialTemplate = dataTemplate;
      if (dataBreakpoints != undefined) dataOptions.breakpoints = this._handleDataBreakpoints(dataBreakpoints);
      if (dataHeaderHeight != undefined) dataOptions.headerHeight = dataHeaderHeight;
      if (dataScrolltop != undefined) dataOptions.scrolltop = dataScrolltop;
      if (dataScrollto != undefined) dataOptions.scrollto = dataScrollto;

      // js-options are overriddden if data-options are passed
      this.options = options = _extend(options, dataOptions);

      // defaults if no options are passed
      var defaults = {
        initialTemplate: 'tab',
        breakpoints: {
          768: {
            template: 'accordion'
          }
        },
        headerHeight: 0,
        scrolltop: 0,
        scrollto: 'btn' // el, btn, item
      };

      // Set default options
      for (var name in defaults) {
        !(name in options) && (options[name] = defaults[name]);
      }
    },
    _handleDataBreakpoints: function _handleDataBreakpoints(str) {
      var _obj = {},
        items = str.split(',');
      if (Array.isArray(items)) {
        items.map(function (item) {
          if (item != '') {
            var options = item.split('|');
            if (Array.isArray(options) && options.length && options[0]) _obj[parseInt(options[0])] = {
              template: options[1]
            };
          }
        });
      }
      return _obj;
    },
    _createInstance: function _createInstance(el) {
      for (var i = 0; i < instances.length; i++) {
        if (instances[i].el === el) {
          console.log('V23 ToggleBox Error: el elemento id:' + el.id + ' | class:' + el.className + ' solo puede ser instanciado una vez.');
          return false;
        }
      }
      return true;
    },
    _saveItems: function _saveItems() {
      var btns = this.el.getElementsByClassName('v23-togglebox__btn');
      for (var i = 0; i < btns.length; i++) {
        var boxid = btns[i].dataset.boxid;
        if (boxid) {
          var boxEl = this.el.querySelector(boxid);
          if (boxEl && boxEl.nodeType && boxEl.nodeType === 1) {
            this.items.push({
              btn: btns[i],
              box: boxEl
            });
          }
        }
      }
    },
    _attach_click_events: function _attach_click_events() {
      _on(this.el, 'click', this._open_tab);
    },
    _open_tab: function _open_tab(event) {
      // event.preventDefault();
      var item = _hasClass(event.target, 'v23-togglebox__btn') ? event.target : _findAncestor(event.target, '.v23-togglebox__btn');
      if (item) this._handle_active_class(item);
    },
    _handle_active_class: function _handle_active_class(btn) {
      if (btn) {
        // method is triggered by a user click event
        for (var i = 0; i < this.items.length; i++) {
          var item = this.el.querySelector(this.items[i].btn.dataset.boxid);
          if (this.items[i].btn.dataset.boxid === btn.dataset.boxid) {
            if (this.activeTemplate === 'accordion') {
              _toggleClass(btn, 'active');
              _toggleClass(item, 'active');
            } else {
              _addClass(btn, 'active');
              _addClass(item, 'active');
            }
            if (this.options.scrolltop) {
              // _scrollTo(document.documentElement, (btn.offsetTop - MV23_GLOBALS.headerHeight), 500);
              var scrollToElement = null;
              switch (this.options.scrollto) {
                case 'el':
                  scrollToElement = $(this.el);
                  break;
                case 'item':
                  scrollToElement = $(item);
                  break;
                default:
                  scrollToElement = $(btn);
                  break;
              }
              if (scrollToElement.length) {
                $("html, body").animate({
                  scrollTop: scrollToElement.offset().top - MV23_GLOBALS.headerHeight
                }, {
                  duration: 800,
                  queue: false,
                  easing: 'easeOutCubic'
                });
              }
            }
            this._handle_hash_in_url(btn.dataset.boxid);
          } else {
            _removeClass(this.items[i].btn, 'active');
            _removeClass(item, 'active');
          }
        }
        ;
      } else {
        // method is triggered on init or on resize
        for (var i = 0; i < this.items.length; i++) {
          _removeClass(this.items[i].btn, 'active');
          _removeClass(this.items[i].box, 'active');
        }
        ;
        if (this.activeTemplate === 'tab') {
          _addClass(this.items[0].btn, 'active');
          _addClass(this.items[0].box, 'active');
        }
      }
    },
    _handle_hash_in_url: function _handle_hash_in_url() {
      var hash = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
      var urlObj = new URL(this.initialUrl);
      urlObj.search = '';
      urlObj.hash = '';
      var cleanUrl = urlObj.toString();
      history.pushState({}, null, cleanUrl + hash);
    },
    _attach_hashchange_events: function _attach_hashchange_events() {
      var that = this;
      window.addEventListener('mv23ReplaceState', function () {
        that._change_active_tab_if_hash_in_url();
      }, true);
    },
    _change_active_tab_if_hash_in_url: function _change_active_tab_if_hash_in_url() {
      if (window.location.hash) {
        var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
        var cleaned_hash = _cleanHash(hash);
        for (var i = 0; i < this.items.length; i++) {
          if (this.items[i].box.id === cleaned_hash) this.items[i].btn.click();
        }
      }
    },
    _handle_template: function _handle_template() {
      var previousTemplate = this.activeTemplate,
        template = this._get_viewport_template(_getViewportDimensions().width);
      if (previousTemplate != template) {
        this.el.dataset.template = this.activeTemplate = template;
        for (var i = 0; i < this.items.length; i++) {
          if (template == 'tab') this.nav.appendChild(this.items[i].btn);
          if (template == 'accordion') _insertBefore(this.items[i].btn, this.items[i].box);
        }
        ;
        this._handle_active_class();
      }
    },
    _get_viewport_template: function _get_viewport_template(viewportWidth) {
      var newTemplate = null,
        breakpoints = this.options.breakpoints;
      if (Object.keys(breakpoints).length > 0) {
        var breakpointZones = [];
        for (var bp in breakpoints) {
          if (bp >= viewportWidth) {
            breakpointZones.push(bp);
          }
        }
        if (breakpointZones.length) {
          var shortest = Math.min.apply(Math, breakpointZones);
          newTemplate = breakpoints[shortest].template;
        }
      }
      return newTemplate || this.options.initialTemplate;
    },
    _attach_resize_events: function _attach_resize_events() {
      var timeToWaitForLast = 100,
        that = this,
        id = "v23ToggleBox" + instances.length;
      window.addEventListener('resize', function () {
        _waitForFinalEvent(function () {
          that._handle_template();
        }, timeToWaitForLast, id);
      }, true);
    },
    /**
    	* Add a New Item
    	* @param {Object}      [options]
    * {
    * id:           (required) (string)
    * btn:          (optional) (obj) { content:`` }
    * box:          (optional) (obj) { content:`` }
    * setActive:    (optional) (bool)
    * afterAddItem: (optional) (function)
    * silent:       (optional) (bool) if true there is not cloning neither adding operations
    *                                 useful when working with models that render the view themselves
    *                                 btn: (required) (DOMElement)
    *                                 box: (required) (DOMElement)
    * }
    	*/
    addItem: function addItem(options) {
      if (!options.silent) {
        if (options.id === undefined) return;
        var newBtn = this.items[0].btn.cloneNode(true);
        newBtn.innerHTML = options.btn && options.btn.content ? options.btn.content : 'New Item';
        newBtn.dataset.boxid = '#' + options.id;
        var newBox = this.items[0].box.cloneNode(true);
        newBox.innerHTML = options.box && options.box.content ? options.box.content : 'Lorem ipsum dolor sit amet consectetur...';
        newBox.id = options.id;
        if (this.activeTemplate == 'tab') this.nav.appendChild(newBtn);
        if (this.activeTemplate == 'accordion') this.itemsBox.appendChild(newBtn);
        this.itemsBox.appendChild(newBox);
      } else {
        var newBtn = options.btn;
        var newBox = options.box;
      }
      this.items.push({
        btn: newBtn,
        box: newBox
      });
      if (options.setActive) this._handle_active_class(newBtn);
      if (typeof options.afterAddItem == 'function') options.afterAddItem(newBtn, newBox);
    },
    /**
    	* Remove Item
    	* @param {Object}      [options]
    * {
    * index:           (required) (integer) index of item to be removed
    * setActive:       (optional) (integer) index of item to be set as active
    * afterRemoveItem: (optional) (function)
    * silent:          (optional) (bool) if true there is not removing operations
    *                                 useful when working with models that manage the view themselves
    * }
    	*/
    removeItem: function removeItem(options) {
      var index = options.index;
      if (index <= this.items.length && this.items.length > 1 && index >= 0) {
        if (!options.silent) {
          this.items[index].btn.remove();
          this.items[index].box.remove();
        }
        var deletedItem = this.items.splice(index, 1);
        if (options.setActive != undefined) {
          if (options.setActive >= 0 && options.setActive <= this.items.length) {
            this.items[options.setActive].btn.click();
          }
        } else {
          if (this.activeTemplate == 'tab' && _hasClass(deletedItem[0].btn, 'active')) {
            this.items[this.items.length - 1].btn.click();
          }
        }
        if (typeof options.afterRemoveItem == 'function') options.afterRemoveItem(deletedItem);
      }
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
  }
  ;
  function _on(el, event, fn) {
    el.addEventListener(event, fn, false);
  }
  ;
  function _hasClass(element, cls) {
    return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
  }
  ;
  function _addClass(elem, className) {
    // TODO : ELEM IS ARRAY
    if (!_hasClass(elem, className)) {
      elem.className += ' ' + className;
    }
  }
  ;
  function _removeClass(elem, className) {
    var newClass = ' ' + elem.className.replace(/[\t\r\n]/g, ' ') + ' ';
    if (_hasClass(elem, className)) {
      while (newClass.indexOf(' ' + className + ' ') >= 0) {
        newClass = newClass.replace(' ' + className + ' ', ' ');
      }
      elem.className = newClass.replace(/^\s+|\s+$/g, '');
    }
  }
  ;
  function _toggleClass(element, cls) {
    if (_hasClass(element, cls)) {
      _removeClass(element, cls);
    } else {
      _addClass(element, cls);
    }
  }
  ;
  function _getViewportDimensions() {
    var w = window,
      d = document,
      e = d.documentElement,
      g = d.getElementsByTagName('body')[0],
      x = w.innerWidth || e.clientWidth || g.clientWidth,
      y = w.innerHeight || e.clientHeight || g.clientHeight;
    return {
      width: x,
      height: y
    };
  }
  ;
  function _scrollTo(element, to, duration) {
    var start = element.scrollTop,
      change = to - start,
      currentTime = 0,
      increment = 20;
    var animateScroll = function animateScroll() {
      currentTime += increment;
      var val = Math.easeInOutQuad(currentTime, start, change, duration);
      element.scrollTop = val;
      if (currentTime < duration) {
        setTimeout(animateScroll, increment);
      }
    };
    animateScroll();
  }
  ;
  function _findAncestor(el, selector) {
    while ((el = el.parentElement) && !(el.matches || el.matchesSelector).call(el, selector));
    return el;
  }
  Math.easeInOutQuad = function (t, b, c, d) {
    //t = current time
    //b = start value
    //c = change in value
    //d = duration
    t /= d / 2;
    if (t < 1) return c / 2 * t * t + b;
    t--;
    return -c / 2 * (t * (t - 2) - 1) + b;
  };
  function _cleanHash(hash) {
    // remove query vars
    var index = hash.indexOf("?");
    var result;
    if (index < 0) {
      result = hash;
    } else {
      result = hash.substr(0, index);
    }
    return result;
  }
  ;
  function _insertAfter(newNode, referenceNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
  }
  ;
  function _insertBefore(newNode, referenceNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode);
  }
  ;
  function _waitForFinalEvent(callback, ms, uniqueId) {
    if (timers[uniqueId]) {
      clearTimeout(timers[uniqueId]);
    }
    timers[uniqueId] = setTimeout(callback, ms);
  }
  ;

  /**
   * Create v23ToggleBox instance
   * @param {HTMLElement}  el
   * @param {Object}      [options]
   */
  V23_ToggleBox.create = function (el, options) {
    var options = options ? options : {},
      togglebox = new V23_ToggleBox(el, options);
    if (togglebox.el) {
      instances.push(togglebox);
    }
    return togglebox;
  };
  V23_ToggleBox.v = function () {
    console.log(version);
  };
  V23_ToggleBox.init = function (options) {
    var toggleboxes = document.getElementsByClassName('v23-togglebox');
    for (var i = 0; i < toggleboxes.length; i++) {
      V23_ToggleBox.create(toggleboxes[i], options);
    }
    return instances;
  };

  // Export
  return V23_ToggleBox;
});
function do_get_implementation() {
  if (document.location.toString().indexOf('?') !== -1) {
    var query = document.location.toString()
    // get the query string
    .replace(/^.*?\?/, '')
    // and remove any existing hash string (thanks, @vrijdenker)
    .replace(/#.*$/, '').split('&');
    for (var i = 0, l = query.length; i < l; i++) {
      var aux = decodeURIComponent(query[i]).split('=');
      $_GET[aux[0]] = aux[1];
    }
  }
}
/*
* Get Viewport Dimensions 
* returns object with viewport dimensions to match css in width and height properties 
* ( source: http://andylangton.co.uk/blog/development/get-viewport-size-width-and-height-javascript ) 
*/
function updateViewportDimensions() {
  var w = window,
    d = document,
    e = d.documentElement,
    g = d.getElementsByTagName('body')[0],
    x = w.innerWidth || e.clientWidth || g.clientWidth,
    y = w.innerHeight || e.clientHeight || g.clientHeight;
  return {
    width: x,
    height: y
  };
}
;

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
}
;
function addClass(elem, className) {
  // TODO : ELEM IS ARRAY
  if (!hasClass(elem, className)) {
    elem.className += ' ' + className;
  }
}
;
function removeClass(elem, className) {
  // TODO : ELEM IS ARRAY
  var newClass = ' ' + elem.className.replace(/[\t\r\n]/g, ' ') + ' ';
  if (hasClass(elem, className)) {
    while (newClass.indexOf(' ' + className + ' ') >= 0) {
      newClass = newClass.replace(' ' + className + ' ', ' ');
    }
    elem.className = newClass.replace(/^\s+|\s+$/g, '');
  }
}
;
function toggleClass(elem, className) {
  // TODO : ELEM IS ARRAY
  var newClass = ' ' + elem.className.replace(/[\t\r\n]/g, ' ') + ' ';
  if (hasClass(elem, className)) {
    while (newClass.indexOf(' ' + className + ' ') >= 0) {
      newClass = newClass.replace(' ' + className + ' ', ' ');
    }
    elem.className = newClass.replace(/^\s+|\s+$/g, '');
  } else {
    elem.className += ' ' + className;
  }
}
;
function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}
;
function removeElementsByClass(className) {
  var elements = document.getElementsByClassName(className);
  while (elements.length > 0) {
    elements[0].parentNode.removeChild(elements[0]);
  }
}
;
function wrapAll(elementsArray, elementContainer) {
  for (var ind = 0; ind < elementsArray.length; ind++) {
    elementContainer.append(elementsArray[0]);
  }
  ;
}
;
function idGenerator() {
  var S4 = function S4() {
    return ((1 + Math.random()) * 0x10000 | 0).toString(16).substring(1);
  };
  return 'v23_' + (S4() + S4() + "_" + S4() + "_" + S4());
}
function myscrollTo(element, to, duration) {
  // https://gist.github.com/andjosh/6764939
  var start = element.scrollTop,
    change = to - start,
    currentTime = 0,
    increment = 20;
  var animateScroll = function animateScroll() {
    currentTime += increment;
    var val = Math.easeInOutQuad(currentTime, start, change, duration);
    element.scrollTop = val;
    if (currentTime < duration) {
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
  t /= d / 2;
  if (t < 1) return c / 2 * t * t + b;
  t--;
  return -c / 2 * (t * (t - 2) - 1) + b;
};

// ****************************************************************************************************
// SERIALIZE FORM TO OBJECT
// ****************************************************************************************************
$.fn.serializeObject = function () {
  var o = {};
  var a = this.serializeArray();
  $.each(a, function () {
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

function create_tns_slider(slider) {
  var show_controls = slider.dataset['showControls'],
    nav_position = slider.dataset['navPosition'],
    show_nav = slider.dataset['showNav'],
    autoplay = slider.dataset['autoplay'],
    autoHeight = slider.dataset['autoHeight'],
    mobile = slider.dataset['mobile'],
    tablet = slider.dataset['tablet'],
    laptop = slider.dataset['laptop'],
    desktop = slider.dataset['desktop'],
    axis = slider.dataset['axis'],
    mode = slider.dataset['mode'],
    mobile_gutter = slider.dataset['mobileGutter'],
    tablet_gutter = slider.dataset['tabletGutter'],
    laptop_gutter = slider.dataset['laptopGutter'],
    desktop_gutter = slider.dataset['desktopGutter'];
  show_controls = show_controls == '1' ? true : false;
  show_nav = show_nav == '1' ? true : false;
  autoplay = autoplay == '1' ? true : false;
  autoHeight = autoHeight == '1' ? true : false;
  mobile = mobile != '' ? mobile : 1;
  tablet = tablet != '' ? tablet : 2;
  laptop = laptop != '' ? laptop : 3;
  desktop = desktop != '' ? desktop : 4;
  axis = axis != '' ? axis : 'horizontal', mode = mode != '' ? mode : 'carousel';
  var slider_options = {
    mode: mode,
    touch: false,
    container: slider,
    speed: 450,
    autoplayButton: false,
    autoplay: autoplay,
    autoplayButtonOutput: false,
    loop: true,
    axis: axis,
    controlsText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
    rewind: true,
    autoHeight: autoHeight,
    mouseDrag: true,
    controls: show_controls,
    nav: show_nav,
    navPosition: nav_position,
    responsive: {
      1401: {
        items: desktop,
        slideBy: desktop,
        gutter: desktop_gutter
      },
      1025: {
        items: laptop,
        slideBy: laptop,
        gutter: laptop_gutter
      },
      601: {
        items: tablet,
        slideBy: tablet,
        gutter: tablet_gutter
      },
      100: {
        items: mobile,
        slideBy: mobile,
        gutter: mobile_gutter
      }
    }
  };
  return tns(slider_options);
}

// ****************************************************************************************************
// GET A COOKIE
// ****************************************************************************************************

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
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
    hex = hex.split('').map(function (_char) {
      return _char + _char;
    }).join('');
  }

  // Convertir los valores hexadecimales a RGB
  var bigint = parseInt(hex, 16);
  var r = bigint >> 16 & 255;
  var g = bigint >> 8 & 255;
  var b = bigint & 255;

  // Convertir el valor de alpha de 0-100 a 0-1
  var a = alpha / 100;

  // Retornar el valor en formato rgba
  return "rgba(".concat(r, ", ").concat(g, ", ").concat(b, ", ").concat(a, ")");
}
// ****************************************************************************************************
// INIT GLOBAL VAR FOR ALL MODULES
// ****************************************************************************************************
var viewport = updateViewportDimensions(),
  $_GET = {},
  is_inicio = jQuery('body').hasClass('home'),
  is_checkout = jQuery('body').hasClass('woocommerce-checkout');
do_get_implementation();
(function ($, c) {
  $(function () {
    // ****************************************************************************************************
    // BOTÓN SUBIR
    // When the user scrolls down 1100px from the top of the document, show the button
    // ****************************************************************************************************

    window.onscroll = function () {
      scrollFunction();
    };
    function scrollFunction() {
      if (document.body.scrollTop > 1100 || document.documentElement.scrollTop > 1100) {
        $('.subir-btn').attr('data-state', '');
      } else {
        $('.subir-btn').attr('data-state', 'hidden');
      }
    }
  });
})(jQuery, console.log);
(function ($, c) {
  document.addEventListener('DOMContentLoaded', function () {
    var carrusel = $('.carrusel');
    for (var i = 0; i < carrusel.length; i++) {
      var slider = $(carrusel[i]).find('.carrusel__slider');
      var tns_slider = create_tns_slider(slider[0]),
        uniqueId = Math.random().toString(36).substring(2, 10);
      $(carrusel[i]).attr('data-tns-uid', uniqueId);
      MV23_GLOBALS.carousels[uniqueId] = tns_slider;
    }
  });
})(jQuery, console.log);
(function ($, c) {
  document.addEventListener('DOMContentLoaded', function () {
    // var $columnas_laterales_extendidas = $('.columnas.layout4').find('div[class*=columnas-]');
    var $columnas_laterales_extendidas = $('.columnas.layout4>div>div');
    if ($columnas_laterales_extendidas.length) {
      $.each($columnas_laterales_extendidas, function (i, e) {
        var $first = $(e).children().first();
        var $div = $('<div/>');
        $first.append($div);
        $div.attr({
          'style': $first.attr('style'),
          'class': 'extended-bg-first'
        });
        var $last = $(e).children().last();
        var $div = $('<div/>');
        $last.append($div);
        $div.attr({
          'style': $last.attr('style'),
          'class': 'extended-bg-last'
        });
      });
    }
  });
})(jQuery, console.log);
(function ($, c) {
  $(function () {
    var setMarginElems = $('[data-setmargin]');
    if (setMarginElems.length > 0) {
      for (var i = 0; i < setMarginElems.length; i++) {
        var wrapper = setMarginElems[i];
        var margin = wrapper.dataset.setmargin;
        if (margin != '20') {
          $(wrapper).find('.componente').css('margin', margin + 'px');
        }
      }
    }
  });
})(jQuery, console.log);
function targetBlank() {
  // remove subdomain of current site's url and setup regex
  var internal = location.host.replace("www.", "");
  internal = new RegExp(internal, "i");
  var a = document.getElementsByTagName('a'); // then, grab every link on the page
  for (var i = 0; i < a.length; i++) {
    var href = a[i].host; // set the host of each link
    if (!internal.test(href)) {
      // make sure the href doesn't contain current site's host
      a[i].setAttribute('target', '_blank'); // if it doesn't, set attributes
    }
  }
}

;
targetBlank();
(function ($, c) {
  $(function () {
    // ****************************************************************************************************
    // ****************************************************************************************************

    var toolbar_middle_buttons = ["zoomIn", "zoomOut", "toggle1to1"];
    if (!MV23_GLOBALS.isMobile) toolbar_middle_buttons.push("rotateCCW", "rotateCW", "flipX", "flipY");
    var fancybox_options = {
      Hash: false,
      Carousel: {
        infinite: false,
        transition: "classic"
      },
      Toolbar: {
        display: {
          left: ["infobar"],
          middle: toolbar_middle_buttons,
          right: ["fullscreen", "slideshow", "thumbs", "close"]
        }
      }
    };
    Fancybox.bind("[data-fancybox], .zoom", fancybox_options);

    // ****************************************************************************************************
  });
})(jQuery, console.log);
(function ($, c) {
  $(function () {
    // ****************************************************************************************************
    // FIT TEXT
    // ****************************************************************************************************

    var fitText = document.getElementsByClassName('fit-text');
    for (var i = 0; i < fitText.length; i++) {
      var el = fitText[i],
        fontSize = $(el).css("fontSize"),
        maxSize = fontSize ? parseInt(fontSize) : 300,
        options = {
          maxSize: maxSize
        };
      fitty(el, options);
    }

    // ****************************************************************************************************
  });
})(jQuery, console.log);
(function ($, c) {
  $(function () {
    // ****************************************************************************************************
    // ****************************************************************************************************

    setTimeout(function () {
      $('.theme-gallery--masonry').masonry({
        itemSelector: '.theme-gallery__item',
        columnWidth: '.theme-gallery__item-sizer',
        percentPosition: true
      });
    }, 1);

    // ****************************************************************************************************
    // OPEN FANCYBOX GALLERY BY SLUG WITH A LINK: 
    // .show-gallery--{gallery_id} 
    // ****************************************************************************************************

    var prefijo = 'show-gallery--';
    $(document).on('click', 'a[class*=' + prefijo + ']', function (event) {
      // event.preventDefault();
      var clases = event.target.classList;

      // Iterar sobre todas las clases
      for (var i = 0; i < clases.length; i++) {
        var clase = clases[i];
        // Verificar si la clase comienza con el prefijo
        if (clase.startsWith(prefijo)) {
          var gallerySlug = clase.substring(prefijo.length);
          Fancybox.fromSelector('[data-fancybox="' + gallerySlug + '"]');
        }
      }
    });

    // ****************************************************************************************************
  });
})(jQuery, console.log);
(function ($, c) {
  $(function () {
    // ****************************************************************************************************
    // CONVERTIR ENLACES A PDF EN PDF
    // ****************************************************************************************************
    var is_single = $('body').hasClass('single');
    function convertir_links_en_pdf(links) {
      if (links.length > 0) {
        for (var i = 0; i < links.length; i++) {
          var href = $(links[i]).attr('href');
          $(links[i]).append(' <i class="fa fa-level-down"></i>');
          $('<div class="pdf-responsive"><embed src="' + href + '" width="670" height="500" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html"></div>').insertAfter($(links[i]).parent());
        }
      }
    }
    if (is_single) {
      $('.disable-link-to-embed-conversion a').addClass('normal-link');
      var links = $('.main').find('a[href*=".pdf"]:not(.normal-link)');
      convertir_links_en_pdf(links);
    }

    // ****************************************************************************************************
    // CONVERTIR ENLACES A DOCUMENTOS EN UN VISOR
    // ****************************************************************************************************
    function convertir_docs(links) {
      if (links.length > 0) {
        for (var i = 0; i < links.length; i++) {
          var href = $(links[i]).attr('href');
          $(links[i]).append(' <i class="fa fa-level-down"></i>');
          $('<div class="pdf-responsive"><iframe src="https://view.officeapps.live.com/op/embed.aspx?src=' + href + '" width="100%" height="565px" frameborder="0"></iframe></div>').insertAfter($(links[i]).parent());
        }
      }
    }
    if (is_single) {
      $('.disable-link-to-embed-conversion a').addClass('normal-link');
      var links = $('.main').find('a[href*=".docx"]:not(.normal-link), a[href*=".pptx"]:not(.normal-link), a[href*=".xlsxs"]:not(.normal-link)');
      convertir_docs(links);
    }
    // ****************************************************************************************************
  });
})(jQuery, console.log);
// GENERAL
(function ($, c) {
  var $portfolio1Listings = $('.posts-listing--show-expander');
  var headerHeight = MV23_GLOBALS.headerHeight;
  var expanderHeight = MV23_GLOBALS.expanderHeight;
  var scrollDuration = MV23_GLOBALS.listingPortfolioScrollDuration;
  if ($portfolio1Listings.length) {
    $portfolio1Listings.each(function (i, e) {
      var $listing = $(e);

      // create expander elements
      var expanderResponse = '<div class="expander-response"></div>',
        closeBtn = '<div class="expander-close"></div>',
        expanderInner = '<div class="expander-inner container">' + expanderResponse + closeBtn + '</div>',
        loading = '<div class="expander-loading"></div>';
      $listing.on('click', '.trigger-post-action', function (event) {
        event.preventDefault();
        var $postCard = $(this).parents('.post-card');
        var scrollTo = $listing.attr('data-on-click-post-scroll-to');
        var listingIsCarrusel = $listing.hasClass('posts-listing--carrusel');
        // where to add the expander
        var $expanderTarget = listingIsCarrusel ? $listing : $postCard;

        // reset all
        var $listingItems = $listing.find('.post-card');
        $listing.find('.expander').remove();
        $listingItems.removeClass('active');
        $listingItems.attr('style', '');
        var url = this.getAttribute('href');
        $.ajax({
          url: url,
          beforeSend: function beforeSend() {
            // open expander
            $postCard.addClass('active');
            $expanderTarget.css('paddingBottom', expanderHeight);
            $expanderTarget.append('<div class="expander">' + expanderInner + '</div>');
            $expanderTarget.find('.expander').append(loading);
            if (scrollTo == 'postcard' || scrollTo == 'expander') {
              var $element = scrollTo == 'postcard' ? $postCard : $expanderTarget.find('.expander');
              $("html, body").animate({
                scrollTop: $element.offset().top - headerHeight
              }, {
                duration: scrollDuration,
                queue: false
              });
            }
          },
          success: function success(response) {
            var content = $('.main', response);
            if (response) {
              $expanderTarget.find('.expander-loading').remove();
              $expanderTarget.find('.expander-response').css('height', expanderHeight).html(content.html());

              // colorbox
              // $expanderTarget.find('.expander-response .zoom').colorbox({ rel:'expander-group', maxHeight:"96%", maxWidth: "96%" });
            }
          }
        });
      });

      $listing.on('click', '.expander-close', function () {
        $listing.find('.expander').remove();
        var $listingItems = $listing.find('.post-card');
        $listingItems.removeClass('active');
        var listingIsCarrusel = $listing.hasClass('posts-listing--carrusel');
        var $expanderTarget = listingIsCarrusel ? $listing : $listingItems;
        $expanderTarget.attr('style', '');
      });
    });
  }
})(jQuery, console.log);
// GENERAL
(function ($, c) {
  var $popupListing = $('.posts-listing--show-popup');
  if ($popupListing.length) {
    var $postModal = $('#post-modal'),
      $postModal_content = $postModal.find('.modal-content');
    $popupListing.each(function (i, e) {
      var $listing = $(e);
      $listing.on('click', '.trigger-post-action', function (event) {
        event.preventDefault();
        var url = this.getAttribute('href');
        $.ajax({
          url: url,
          beforeSend: function beforeSend() {
            $postModal.modal('open').attr('data-status', 'loading');
          },
          success: function success(response) {
            $postModal.attr('data-status', '');
            var content = $('.main', response);
            if (response) {
              $postModal_content.html(content.html());
            }
          }
        });
      });
    });
  }
})(jQuery, console.log);
// GENERAL
(function ($, c) {
  var $components = $('.componente.listing');
  var current_lang = MV23_GLOBALS.lang;
  var loading_text = MV23_GLOBALS.listing_loading_text[current_lang];
  function do_the_ajax($component, terms, paged, post_template, per_page, $listing, $pagination, posttype, taxonomies, action, filterValues, order, orderby, offset, listing_template, on_click_post, wookey, pagination_type) {
    $.ajax({
      type: 'POST',
      dataType: "json",
      url: MV23_GLOBALS.ajaxUrl,
      data: {
        action: "load_posts",
        nonce: MV23_GLOBALS.nonce,
        lang: MV23_GLOBALS.lang,
        post_template: post_template,
        listing_template: listing_template,
        on_click_post: on_click_post,
        terms: filterValues.terms ? filterValues.terms : terms,
        paged: paged || 1,
        per_page: per_page,
        offset: offset,
        order: order,
        orderby: orderby,
        wookey: wookey,
        posttype: posttype,
        taxonomies: taxonomies,
        search: filterValues.search,
        year: filterValues.year,
        month: filterValues.month,
        pagination_type: pagination_type
      },
      beforeSend: function beforeSend() {
        $component.attr('data-status', 'loading');
        $pagination && $pagination.html('<p class="center">' + loading_text + '</p>');
      },
      success: function success(response) {
        $component.attr('data-status', 'loaded');
        var $items_container = listing_template === 'carrusel' ? $listing.find('.carrusel__slider') : $listing;
        if (listing_template === 'carrusel') {
          var tns_uid = $listing.find('.carrusel').attr('data-tns-uid');
          var carousel = MV23_GLOBALS.carousels[tns_uid];
        }
        switch (response.status) {
          case 'success':
            var $items = $(response.posts);
            if (listing_template === 'carrusel') {
              carousel.destroy();
              $items_container = $listing.find('.carrusel__slider');
              if (action === 'replace') $items_container.html('');
            }
            if (action === 'replace') $items_container.html($items);
            if (action === 'append') $items_container.append($items);
            if (listing_template === 'carrusel') {
              MV23_GLOBALS.carousels[tns_uid] = create_tns_slider($items_container[0]);
              if (action === 'append') MV23_GLOBALS.carousels[tns_uid].goTo('next');
            }
            $listing.trigger('listingUpdated', {
              listing: $listing,
              items: $items,
              action: action,
              response: response
            });
            $pagination && $pagination.html(response.pagination);
            var scrolltop = $component.attr("data-scrolltop");
            if (scrolltop) {
              var headerHeight = MV23_GLOBALS.headerHeight;
              $("html, body").animate({
                scrollTop: $component.offset().top - headerHeight
              }, {
                duration: 800,
                queue: false,
                easing: 'easeOutCubic'
              });
            }
            break;
          case 'error':
            if (listing_template === 'carrusel') {
              carousel.destroy();
              $items_container = $listing.find('.carrusel__slider');
              $items_container.html('');
            }
            $items_container.html('<p class="center posts-filter-error-msg">' + response.message + '</p>');
            if (listing_template === 'carrusel') MV23_GLOBALS.carousels[tns_uid] = create_tns_slider($items_container[0]);
            $pagination && $pagination.html('');
            break;
          default:
            c(response);
        }
      }
    });
  }
  function getFilterValues($filter) {
    var $year_selector = $filter.find('.posts-filter__year-select'),
      $month_selector = $filter.find('.posts-filter__month-select'),
      $search_input = $filter.find('.posts-filter__search-input'),
      $terms_selects = $filter.find('.posts-filter__term-select'),
      year = $year_selector.length ? $year_selector.val() : '',
      month = $month_selector.length ? $month_selector.val() : '',
      search = $search_input.length ? $search_input.val() : '',
      terms = '';
    if ($terms_selects.length) {
      var term_values = [];
      $terms_selects.each(function (i, elem) {
        term_values.push($(elem).val());
      });
      var are_terms_selected = false;
      for (var i = 0; i < term_values.length; i++) {
        if (term_values != '') {
          are_terms_selected = true;
          break;
        }
      }
      if (are_terms_selected) terms = term_values.join();
    }
    var areParams = terms == '' && year == '' && month == '' && search == '' ? false : true;
    return {
      areParams: areParams,
      terms: terms,
      search: search,
      year: year,
      month: month
    };
  }
  ;
  if ($components.length) {
    $components.each(function (i, e) {
      var $component = $(e),
        $filter = $component.find('.posts-filter'),
        $listing = $component.find('.posts-listing');
      $component.on('click', 'a.page-numbers', function (event) {
        event.preventDefault();
        var href = event.target.getAttribute('href'),
          url = new URL(href),
          paged = url.searchParams.get("paged"),
          $pagination = $component.find('.pagination'),
          posttype = $component.attr("data-posttype"),
          taxonomies = $component.attr("data-taxonomies"),
          terms = $component.attr("data-terms"),
          post_template = $component.attr("post-template"),
          listing_template = $component.attr("listing-template"),
          on_click_post = $component.attr("on-click-post"),
          per_page = $component.attr("data-qty"),
          offset = $component.attr("data-offset"),
          order = $component.attr("data-order"),
          orderby = $component.attr("data-orderby"),
          wookey = $component.attr("data-wookey"),
          pagination_type = $component.attr("data-pagination"),
          action = 'replace',
          filterValues = getFilterValues($filter);
        do_the_ajax($component, terms, paged, post_template, per_page, $listing, $pagination, posttype, taxonomies, action, filterValues, order, orderby, offset, listing_template, wookey, pagination_type);
      });
      $component.on('click', '.load_more_posts', function (event) {
        event.preventDefault();
        var $this = $(this),
          paged = $this.attr("data-paged"),
          $pagination = null,
          posttype = $component.attr("data-posttype"),
          taxonomies = $component.attr("data-taxonomies"),
          terms = $component.attr("data-terms"),
          post_template = $component.attr("post-template"),
          listing_template = $component.attr("listing-template"),
          on_click_post = $component.attr("on-click-post"),
          per_page = $component.attr("data-qty"),
          offset = $component.attr("data-offset"),
          order = $component.attr("data-order"),
          orderby = $component.attr("data-orderby"),
          wookey = $component.attr("data-wookey"),
          pagination_type = $component.attr("data-pagination"),
          action = 'append',
          filterValues = getFilterValues($filter);
        do_the_ajax($component, terms, paged, post_template, per_page, $listing, $pagination, posttype, taxonomies, action, filterValues, order, orderby, offset, listing_template, on_click_post, wookey, pagination_type);
      });
      $component.on('click', '.posts-filter__submit', function (ev) {
        ev.preventDefault();
        var $pagination = $component.find('.pagination'),
          posttype = $component.attr("data-posttype"),
          taxonomies = $component.attr("data-taxonomies"),
          terms = $component.attr("data-terms"),
          post_template = $component.attr("post-template"),
          listing_template = $component.attr("listing-template"),
          on_click_post = $component.attr("on-click-post"),
          per_page = $component.attr("data-qty"),
          offset = $component.attr("data-offset"),
          order = $component.attr("data-order"),
          orderby = $component.attr("data-orderby"),
          wookey = $component.attr("data-wookey"),
          pagination_type = $component.attr("data-pagination"),
          paged = 1,
          action = 'replace',
          filterValues = getFilterValues($filter);
        do_the_ajax($component, terms, paged, post_template, per_page, $listing, $pagination, posttype, taxonomies, action, filterValues, order, orderby, offset, listing_template, on_click_post, wookey, pagination_type);
      });
      $component.on('listingUpdated', function (e, data) {
        e.preventDefault();
        var $load_more_btn = $(this).find('.load_more_posts'),
          paged = $load_more_btn.attr("data-paged"),
          new_paged = parseInt(paged) + 1;
        if (new_paged > data.response.max_num_pages) {
          $load_more_btn.parent().remove();
        } else {
          $load_more_btn.attr('data-paged', new_paged);
        }
      });
    });
  }
})(jQuery, console.log);
var styleArray = [{
  "stylers": [{
    "hue": "#19511B"
  }, {
    "saturation": 0
  }, {
    "gamma": 0.7
  }]
}];
(function ($, c) {
  document.addEventListener('DOMContentLoaded', function () {
    var mapas = document.getElementsByClassName('mapa__gmap');
    if (mapas.length) {
      var initMap = function initMap(element) {
        var lat = parseFloat(element.dataset.lat),
          lng = parseFloat(element.dataset.lng),
          zoom = parseFloat(element.dataset.zoom),
          icon = element.dataset.icon,
          infoContent = $(element).find('.infowindow').html(),
          map = new google.maps.Map(element, {
            zoom: zoom,
            center: {
              lat: lat,
              lng: lng
            }
          }),
          marker_options = {
            map: map,
            position: {
              lat: lat,
              lng: lng
            }
          };
        if (icon) marker_options.icon = icon;
        var marker = new google.maps.Marker(marker_options);
        // map.setOptions({styles: styleArray});

        if (infoContent) {
          var infoWindow = new google.maps.InfoWindow({
            map: map
          });
          infoWindow.setPosition(marker_options.position);
          infoWindow.setContent(infoContent);
        }
        element.mapObject = map;
      };
      var map;
      $.each(mapas, function (i, e) {
        initMap(e);
      });
    }
  });
})(jQuery, console.log);
(function ($, c) {
  $(function () {
    $(".has-megamenu").removeClass('menu-item-has-children');
    // ****************************************************************************************************
    // MENÚ -- SUBMENÚS
    // ****************************************************************************************************

    if (viewport.width > 768) {
      $(".header li.menu-item-has-children").hover(function () {
        $(this).find('ul.sub-menu:first').show(268);
        hide_main_megamenu();
      }, function () {
        $(this).find('ul.sub-menu:first').hide();
      });
      $(".header li.menu-item:not(.is-active)").hover(function () {
        hide_main_megamenu();
      }, function () {});
    }
    ;

    // ****************************************************************************************************
    // MEGAMENÚ
    // ****************************************************************************************************
    $('.megamenu').appendTo('#megamenus');
    var $body = $('body'),
      $header = $('.header'),
      timeout = null;
    $(".has-megamenu").hover(function () {
      clearTimeout(timeout);
      var $that = $(this);
      timeout = setTimeout(function () {
        var megamenu = $that.find('a').eq(0).attr('data-activates');

        // Disable Scrolling
        var oldWidth = $body.innerWidth();
        // $body.css('overflow', 'hidden');
        $body.width(oldWidth);
        $header.width(oldWidth);
        $('.megamenu').removeClass('is-active');
        $that.addClass('is-active');
        $('#' + megamenu).addClass('is-active');
        if ($('.megamenu-overlay').length < 1) {
          $body.append('<div class="megamenu-overlay"></div>');
        }
      }, 250);
    }, function () {
      clearTimeout(timeout);
    });
    function hide_main_megamenu() {
      // Reenable scrolling
      $body.css({
        overflow: '',
        width: ''
      });
      $header.css({
        width: ''
      });
      $('.has-megamenu').removeClass('is-active');
      $('.megamenu').removeClass('is-active');
      $('.megamenu-overlay').remove();
    }
    $(document).on('click', '.megamenu-overlay', function () {
      hide_main_megamenu();
    });
    $(".megamenu-close, .megamenu a").click(function () {
      hide_main_megamenu();
    });

    // ****************************************************************************************************
    // MOBILE MENU
    // ****************************************************************************************************

    $('#menu-movil').sidenav({
      edge: MV23_GLOBALS.mobile_menu_position,
      draggable: false
    });
    $('.menu-movil .sub-menu').css('display', 'none');
    $('.menu-movil li.menu-item-has-children').append('<button class="toogle-submenu"></button>');
    $('.menu-movil li.menu-item a').addClass('sidenav-close');
    $('.menu-movil').on('click', '.toogle-submenu', function () {
      $(this).parent().children('.sub-menu').slideToggle();
    });

    // ****************************************************************************************************
    // ****************************************************************************************************
    // ****************************************************************************************************
  });
})(jQuery, console.log);
(function ($, c) {
  $(function () {
    var modals = document.getElementsByClassName('modal');
    for (var i = 0; i < modals.length; i++) {
      var el = modals[i],
        closeOnClick = el.dataset['closeOnClick'];
      $(el).modal({
        dismissible: true,
        opacity: .6,
        inDuration: 300,
        outDuration: MV23_GLOBALS.modal.outDuration,
        startingTop: '2%',
        endingTop: '5%',
        onOpenStart: function onOpenStart(modal, trigger) {
          $(trigger).css('z-index', 'initial');
        },
        onCloseEnd: function onCloseEnd(modal, trigger) {
          var empty_on_close = $(modal).hasClass('empty-on-close');
          if (empty_on_close) $(modal).find('.modal-content').empty();
          $('#video-modal .video-responsive').html('');
        }
      });
      if (closeOnClick) {
        $(el).find('a').click(function () {
          $(el).modal('close');
        });
      }
    }
    $('.modal-trigger').modal();
    $('.modal-trigger').css('z-index', 25);
  });
})(jQuery, console.log);
window['OffCanvas_Elements'] = function () {
  function Offcanvas_Element(element_data) {
    this.offcanvas_element_id = element_data.id;
    this.offcanvas_element = document.querySelector('#' + this.offcanvas_element_id);
    this.type = element_data.type;
    this.content_type = element_data.content_type;
    this.trigger_events = element_data.trigger_events || [];
    this.async_settings = element_data.async_settings;
    this.settings = element_data.settings;

    // Bind all private methods
    for (var fn in this) {
      if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
        this[fn] = this[fn].bind(this);
      }
    }
    this.M_instance = null;
    this.M_instance_options = [];
    this._handle_async_settings();
    // this._handle_callback_settings();
    this._create_the_M_instance();
    if (_typeof(this.M_instance) === "object") {
      this._handle_styles();
      this._handle_trigger_events();
    }
  }
  Offcanvas_Element.prototype = {
    _handle_async_settings: function _handle_async_settings() {
      var _this61 = this;
      if (this.content_type == 'async' && _typeof(this.async_settings) == "object") {
        var fetchUrl = []; // in case of page source i need to try in two paths pages/posts
        var errorMsg = [];
        var content_source = this.async_settings.content_source;
        switch (content_source) {
          case 'page':
            var _page_source = this.async_settings.page_source;
            if (_page_source) {
              var contentId = _page_source.replace('post_', '');
              var pageUrl = "".concat(MV23_GLOBALS.homeUrl, "/wp-json/wp/v2/pages/").concat(contentId);
              var postUrl = "".concat(MV23_GLOBALS.homeUrl, "/wp-json/wp/v2/posts/").concat(contentId);
              fetchUrl = [pageUrl, postUrl];
              errorMsg = ['No se encontró como página, intentando como post...', 'No se encontró el contenido ni como página ni como post.'];
            }
            break;
          case 'url':
            var url_source = this.async_settings.url_source;
            if (url_source != '') {
              fetchUrl = [url_source];
              errorMsg = ['No se encontró la url'];
            }
            break;
          case 'link':
          default:
            // this need to be handled inside the onOpenStart callback to access the event target
            break;
        }
        this.M_instance_options.onOpenStart = function () {
          var el = _this61.M_instance.el;
          var modal_content = el.querySelector('.modal-content');
          if (_this61.async_settings.clear_on_close) modal_content.innerHTML = "";
          _this61._check_async_attributes(_this61.async_settings, 'beforeSend', el);
          if (content_source === 'link') {
            var trigger = _this61.M_instance._openingTrigger;
            var trigger_href = _typeof(trigger) == 'object' && trigger.tagName == 'A' ? trigger.getAttribute('href') : '';
            if (trigger_href != '') {
              fetchUrl = [trigger_href];
              errorMsg = ['No se encontró la url del link'];
            }
          }
          if (fetchUrl.length > 0) {
            fetch(fetchUrl[0]).then(function (response) {
              return _this61._handle_async_response(response);
            }).then(function (data) {
              return _this61._handle_async_data(data, el);
            })["catch"](function (error) {
              _this61._handle_async_error(error, errorMsg[0], el, true);
              return fetch(fetchUrl[1]).then(function (response) {
                return _this61._handle_async_response(response);
              }).then(function (data) {
                return _this61._handle_async_data(data, el);
              })["catch"](function (error) {
                return _this61._handle_async_error(error, errorMsg[1], el, true);
              });
            });
          } else {
            _this61._check_async_attributes('error', el);
            el.querySelector('.modal-content').innerHTML = "<p class=\"center-align\">Some setting in ".concat(_this61.offcanvas_element_id, " is wrong.<p>");
          }
        };
      }
    },
    _check_async_attributes: function _check_async_attributes(status, el) {
      var _this62 = this;
      var async_settings = this.async_settings;
      if (async_settings.attributes.length) {
        async_settings.attributes.forEach(function (item) {
          if (item.status == status) _this62._assign_attribute(el, item.attribute, item.value);
        });
      }
    },
    _assign_attribute: function _assign_attribute(domObject, selector, value) {
      if (!domObject || typeof selector !== 'string' || typeof value !== 'string') {
        throw new Error('Invalid arguments. Expecting a DOM object, a string selector and a string value.');
      }
      if (selector == 'id') {
        domObject.id = value;
      } else if (selector == 'class') {
        if (value.startsWith('-')) {
          domObject.classList.remove(value.substring(1));
        } else {
          domObject.classList.add(value);
        }
      } else if (selector.startsWith('data-')) {
        domObject.setAttribute(selector, value);
      } else {
        throw new Error('Selector must be id, class or "data-xxx" ');
      }
    },
    _handle_async_response: function _handle_async_response(response) {
      var async_settings = this.async_settings;
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      var content_source = async_settings.content_source;
      return content_source == 'page' ? response.json() : response.text();
    },
    _handle_async_data: function _handle_async_data(data, el) {
      var async_settings = this.async_settings;
      var content = '';
      this._check_async_attributes('success', el);
      var content_source = async_settings.content_source;
      var load_on_iframe = async_settings.load_on_iframe;
      if (load_on_iframe) {
        var iframe_src = content_source == 'page' ? data.link : async_settings.url_source;
        var divWrapper = document.createElement('div');
        divWrapper.className = "pdf-responsive";
        var iframe = document.createElement('iframe');
        iframe.setAttribute("src", iframe_src);
        divWrapper.appendChild(iframe);
        content = divWrapper.outerHTML;
      } else {
        content = content_source == 'page' ? data.content.rendered : data;
        var cherry_pick_sections = async_settings.cherry_pick_sections;
        var cherry_picked_sections = async_settings.cherry_picked_sections;
        if (cherry_pick_sections && cherry_picked_sections != '') {
          var _divWrapper = document.createElement('div');
          _divWrapper.innerHTML = content;
          content = '';
          var sections = _divWrapper.querySelectorAll(cherry_picked_sections);
          sections.forEach(function (section) {
            content += section.outerHTML;
          });
        }
      }
      el.querySelector('.modal-content').innerHTML = content;
    },
    _handle_async_error: function _handle_async_error(error, msg, el, debug) {
      this._check_async_attributes('error', el);
      if (debug) console.log(msg, error);
    },
    _handle_callback_settings: function _handle_callback_settings() {
      if (typeof this.settings.on_open === 'string' && this.settings.on_open.trim() !== '') {
        var callbackFunction = new Function('return ' + this.settings.on_open)();
        if (typeof callbackFunction === 'function') {
          if (this.settings.on_open) M_instance_options.onOpenStart = callbackFunction;
        }
      }
    },
    _create_the_M_instance: function _create_the_M_instance() {
      var settings = this.settings,
        type = this.type,
        M_instance_options = this.M_instance_options,
        offcanvas_element = this.offcanvas_element;
      if (type === 'modal' || type === 'bottom_sheet') {
        M_instance_options.dismissible = settings.dismissible;
        this.M_instance = M.Modal.init(offcanvas_element, M_instance_options);
      }
      if (type === 'sidenav') {
        M_instance_options.edge = settings.position;
        M_instance_options.draggable = false;
        this.M_instance = M.Sidenav.init(offcanvas_element, M_instance_options);
      }
    },
    _handle_styles: function _handle_styles() {
      var settings = this.settings,
        offcanvas_element = this.offcanvas_element,
        M_instance = this.M_instance,
        type = this.type;
      if (settings.background_color.use) offcanvas_element.style.backgroundColor = this._format_color(settings.background_color.color, settings.background_color.alpha);
      if (settings.max_width) offcanvas_element.style.maxWidth = settings.max_width + 'px';
      if (settings.background_color.color_scheme && settings.background_color.color_scheme != '') {
        var color_scheme_class = settings.background_color.color_scheme === 'dark-scheme' ? 'text-color-2' : 'text-color-1';
        offcanvas_element.classList.add(color_scheme_class);
      }
      var overlay = type === 'sidenav' ? M_instance._overlay : M_instance.$overlay[0];
      if (settings.overlay_color.use) overlay.style.backgroundColor = this._format_color(settings.overlay_color.color, settings.overlay_color.alpha);
    },
    _format_color: function _format_color(color, alpha) {
      var formated_color = color;
      if (alpha != 100) formated_color = hexToRgba(color, alpha);
      return formated_color;
    },
    _handle_trigger_events: function _handle_trigger_events() {
      var _this63 = this;
      var offcanvas_element_id = this.offcanvas_element_id,
        trigger_events = this.trigger_events;
      trigger_events.forEach(function (triggerData) {
        switch (triggerData.__type) {
          case 'click':
            _this63._handle_click_event(triggerData);
            break;
          case 'custom_event':
            _this63._handle_custom_event(triggerData);
            break;
          case 'scroll':
            var cookie_name = triggerData.custom_cookie ? triggerData.cookie_name : offcanvas_element_id + '-shown';
            var storage_type = triggerData.custom_cookie ? triggerData.storage_type : 'session';
            var storage = storage_type === "session" ? sessionStorage : localStorage;
            if (triggerData.settings_type == 'basic') {
              _this63._handle_basic_scroll_event(triggerData, storage, cookie_name);
            }
            if (triggerData.settings_type == 'scrollmagic' && MV23_GLOBALS.scrollAnimations) {
              _this63._handle_scrollmagic_event(triggerData, storage, cookie_name);
            }
            break;
          default:
            console.log('No trigger events assigned to offcanvas element with ID:' + offcanvas_element_id);
            break;
        }
      });
    },
    _handle_click_event: function _handle_click_event(triggerData) {
      var type = this.type,
        offcanvas_element_id = this.offcanvas_element_id,
        M_instance = this.M_instance;
      var triggers = document.querySelectorAll(triggerData.selector);
      if (triggers.length) {
        var triggerClass = type != 'bottom_sheet' ? type + '-trigger' : 'modal-trigger';
        triggers.forEach(function (trigger) {
          trigger.classList.add(triggerClass);
          trigger.dataset.target = offcanvas_element_id;
          if (type === 'sidenav') M_instance._openingTrigger = trigger;
        });
      }
      ;
      if (triggerData.delegate_to_body) {
        document.body.addEventListener('click', function (event) {
          if (event.target.closest(triggerData.selector)) {
            event.preventDefault();
            M_instance._openingTrigger = event.target;
            // i need send a cash $trigger to open method:
            M_instance.open($(event.target));
          }
        });
      }
    },
    _handle_custom_event: function _handle_custom_event(triggerData) {
      var M_instance = this.M_instance;
      var event_source = triggerData.event_source;
      var event_name = event_source == 'custom' ? triggerData.event_name : triggerData[event_source + '_event'];
      // dosnt work with woo events:
      // event_name && document.body.addEventListener(event_name, function() { instance.open(); }); 
      event_name && $(document.body).on(event_name, function () {
        M_instance.open();
      });
    },
    _handle_basic_scroll_event: function _handle_basic_scroll_event(triggerData, storage, cookie_name) {
      var M_instance = this.M_instance;

      // if visualization cookie is automatic show the element on every page reload
      if (!triggerData.custom_cookie) storage.removeItem(cookie_name);
      $(window).scroll(function () {
        var xscrollTop = $(document).scrollTop();
        if (xscrollTop > triggerData.scroll_top && !storage.getItem(cookie_name)) {
          M_instance.open();
          storage.setItem(cookie_name, 'true');
          if (triggerData.cookie_expiration) {
            setTimeout(function () {
              storage.removeItem(cookie_name);
            }, triggerData.expiration_time);
          }
        }
      });
    },
    _handle_scrollmagic_event: function _handle_scrollmagic_event(triggerData, storage, cookie_name) {
      var M_instance = this.M_instance;

      // cookie name need to be unique if there are two scroll triggers for the same offcanvas element
      if (!triggerData.custom_cookie) cookie_name = '_' + cookie_name;
      var scrollmagic_settings = triggerData.scrollmagic_settings;
      var trigger_element = document.querySelectorAll(scrollmagic_settings.trigger_element);
      if (trigger_element.length) {
        var controller = new ScrollMagic.Controller();
        var trigger_hook = scrollmagic_settings.trigger_hook;
        var offset = scrollmagic_settings.offset;
        var add_indicators = scrollmagic_settings.add_indicators;
        for (var i = 0; i < trigger_element.length; i++) {
          var scene = new ScrollMagic.Scene({
            triggerElement: trigger_element[i],
            triggerHook: trigger_hook,
            offset: offset
          }).addTo(controller);
          scene.on("enter", function (event) {
            if (triggerData.custom_cookie && storage.getItem(cookie_name)) return;
            M_instance.open();
            if (triggerData.custom_cookie) {
              storage.setItem(cookie_name, 'true');
              if (triggerData.cookie_expiration) {
                setTimeout(function () {
                  storage.removeItem(cookie_name);
                }, triggerData.expiration_time);
              }
            }
          });
          if (MV23_GLOBALS.scrollIndicators && add_indicators) scene.addIndicators();
        }
      }
    }
  };
  Offcanvas_Element.init = function (data) {
    data.forEach(function (element_data) {
      var instance = new Offcanvas_Element(element_data);
    });
  };
  return Offcanvas_Element;
}();
(function ($, c) {
  document.addEventListener('DOMContentLoaded', function () {
    OffCanvas_Elements.init(OFFCANVAS_ELEMENTS);
  });

  // esto tiene que ir al final de todo el theme
  document.addEventListener('DOMContentLoaded', function () {
    var event = new Event('theme_document_ready');
    document.body.dispatchEvent(event);
  });
})(jQuery, console.log);
(function ($, c) {
  $(window).load(function () {
    // ****************************************************************************************************
    // ****************************************************************************************************
    var header_height = parseInt(MV23_GLOBALS.headerHeight);
    var $pinnedBlocks = $('.pinned-block');
    if (viewport.width > 1024) {
      $pinnedBlocks.each(function () {
        var $this = $(this),
          $target = $this.parent();
        $this.css('width', $target.css('width'));
        if ($target.height() > $this.height()) {
          setTimeout(function () {
            $this.pushpin({
              top: $target.offset().top,
              bottom: $target.offset().top + $target.outerHeight() - $this.height(),
              offset: header_height
            });
          }, 1000);
        }
      });
    }
    window.addEventListener('resize', function (event) {
      $pinnedBlocks.each(function () {
        var $this = $(this),
          $target = $this.parent();
        if (window.innerWidth > 1024) {
          $this.css('width', $target.css('width'));
        } else {
          $this.pushpin('remove');
          $this.css('width', '100%');
        }
      });
    });

    // ****************************************************************************************************
    // ****************************************************************************************************
  });
})(jQuery, console.log);
(function ($, c) {
  $(function () {
    // ****************************************************************************************************
    // SCRIPT PARA MOVER EL RECAPTCHA A UN WRAPPER
    // ****************************************************************************************************

    $(window).load(function (e) {
      setTimeout(function () {
        $('.grecaptcha-badge').appendTo('.grecaptcha-badge-wrapper');
      }, 3500);
    });

    // *********************************************************************
    // REMOVE ACTIVE IN MENU ITEMS WITH ANCHOR
    // *********************************************************************
    var menu_items_links = $(".main-nav li a");
    menu_items_links.each(function () {
      if ($(this).is('[href*="#"')) {
        $(this).parent().removeClass('current-menu-item current-menu-ancestor');
        // $(this).click(function () {
        // var current_index = $(this).parent().index(),
        // parent_element = $(this).closest('ul');
        // parent_element.find('li').not(':eq(' + current_index + ')').removeClass('current-menu-item current-menu-ancestor');
        // $(this).parent().addClass('current-menu-item current-menu-ancestor');
        // })
      }
    });

    // ****************************************************************************************************
    // ****************************************************************************************************
    $('.cover-all').parent().css('position', 'relative');
    $('.share-modal').appendTo('#share-modal-wrapper');
    // --------------------------------------------------------------------------------------------------------------
  });
})(jQuery, console.log);
(function ($, c) {
  $(function () {
    var animatedElements = $('[data-scroll-animations]');
    if (animatedElements.length > 0) {
      var controller = new ScrollMagic.Controller();
      for (var i = 0; i < animatedElements.length; i++) {
        var elem = animatedElements[i],
          scrollAnimations = JSON.parse(elem.dataset.scrollAnimations);
        if (scrollAnimations.length > 0) {
          scrollAnimations.forEach(function (group) {
            var triggerElement = group['trigger_element'] != 'this' ? $(elem).find(group['trigger_element']) : elem;
            if (triggerElement.length) {
              for (var index = 0; index < triggerElement.length; index++) {
                var _tgrEl = triggerElement[index];
                var tweenElem = group['trigger_element'] != group['element']['el'] ? $(_tgrEl).find(group['element']['el']) : _tgrEl;
                addAnimation(_tgrEl, tweenElem, group);
              }
            } else {
              var tweenElem = null;
              switch (group['element']['key']) {
                case 'selector':
                  tweenElem = $(elem).find(group['element']['el']);
                  break;
                case 'outer_selector':
                  tweenElem = $(group['element']['el'])[0];
                  break;
                default:
                  tweenElem = elem;
                  break;
              }
              if (tweenElem) addAnimation(triggerElement, tweenElem, group);
            }
          });
        }
      }
    }
    function addAnimation(triggerElement, tweenElem, group) {
      var from = JSON.parse(group['from']);
      var to = JSON.parse(group['to']);
      var addIndicators = group['add_indicators'];
      var setPin = group['set_pin'];
      var triggerCarrusel = group['trigger_carrusel'];

      // var timeline = new TimelineMax();
      // timeline.from(tweenElem, 1, from);
      // timeline.to(tweenElem, 1, to);
      var tween = TweenMax.fromTo(tweenElem, 1, from, to);
      var scene = new ScrollMagic.Scene({
        triggerElement: triggerElement,
        duration: group['duration'],
        triggerHook: group['trigger_hook'],
        offset: group['offset']
      }).setTween(tween).addTo(controller);
      if (setPin) scene.setPin(triggerElement);
      if (triggerCarrusel) {
        // trgigger carrusel next / prev
        scene.on("progress", function (e) {
          var carrusels = $(triggerElement).hasClass('carrusel') ? $(triggerElement) : $(triggerElement).find('.carrusel');
          if (carrusels.length) {
            for (var i = 0; i < carrusels.length; i++) {
              var carrusel_uid = $(carrusels[i]).attr('data-tns-uid');
              var carrusel = MV23_GLOBALS.carousels[carrusel_uid];
              if (carrusel) {
                var nth_slides = carrusel.getInfo().slideCount;
                for (var _i82 = 1; _i82 <= nth_slides; _i82++) {
                  if (e.progress >= 1 / nth_slides * (_i82 - 1) && e.progress <= 1 / nth_slides * _i82) {
                    var slide_should_be_here = _i82 - 1; // slide index starts in 0
                    carrusel.goTo(slide_should_be_here);
                  }
                }
              }
            }
          }
        });
      }
      if (MV23_GLOBALS.scrollIndicators && addIndicators == '1') scene.addIndicators();
    }
  });
})(jQuery, console.log);
(function ($, c) {
  $(function () {
    // ****************************************************************************************************
    // SCROLLSPY
    // ****************************************************************************************************
    var header_height = parseInt(MV23_GLOBALS.headerHeight);
    $('.scrollspy').scrollSpy({
      activeClass: 'is-inview',
      throttle: header_height
      // scrollOffset: 0,
    });

    // ****************************************************************************************************
  });
})(jQuery, console.log);
(function ($, c) {
  $(function () {
    // ****************************************************************************************************
    // ANCLAS
    // ****************************************************************************************************
    var initial_url = window.location.href.split('#')[0];
    var pageLinks = $('a[href*="#"]');
    var headerHeight = MV23_GLOBALS.headerHeight;
    for (var i = 0; i < pageLinks.length; i++) {
      var href = $(pageLinks[i]).attr('href'),
        hash = href.split('#')[1];
      if (href.split('#')[0] == initial_url) {
        $(pageLinks[i]).attr('href', '#' + hash);
      }
    }
    $('a[href^="#"]').click(function (event) {
      event.preventDefault();
      var href = $(this).attr('href');
      if ($(href).length > 0) {
        history.pushState({}, null, href);
        var e = new Event('mv23ReplaceState');
        window.dispatchEvent(e);
        var elementPosition = $(href).offset().top;
        var newPosition = 0;
        if (href != '#content') {
          newPosition = elementPosition - headerHeight;
        } else {
          var bodyStyles = window.getComputedStyle(document.body);
          var paddingTop = bodyStyles.getPropertyValue('--body-padding-top');
          newPosition = elementPosition - parseInt(paddingTop);
        }
        $("html, body").animate({
          scrollTop: newPosition
        }, {
          duration: 800,
          queue: false
          // easing: 'easeOutCubic'
        });
      }
    });
    // ****************************************************************************************************
  });
})(jQuery, console.log);
(function ($, c) {
  $(function () {
    // ****************************************************************************************************
    // HEADER -- STICKY
    // ****************************************************************************************************
    var $header = $('.header'),
      $logo = $('.header__logo__link img'),
      breakpoint = MV23_GLOBALS.stickyHeaderBreakpoint;
    var stickyHeader = {
      isSticky: false,
      element: '.header',
      init: function init() {
        var xscrollTop = $(document).scrollTop();
        if (xscrollTop > breakpoint && !this.isSticky) {
          this.isSticky = true;
          stickyHeader.show();
        }
        $(window).scroll(function () {
          var xscrollTop = $(document).scrollTop();
          if (xscrollTop > breakpoint && !this.isSticky) {
            this.isSticky = true;
            stickyHeader.show();
          }
          if (xscrollTop < breakpoint && this.isSticky) {
            this.isSticky = false;
            stickyHeader.hide();
          }
        });
      },
      show: function show() {
        $logo.attr('src', STICKY_HEADER.logo);
        $header.attr('style', STICKY_HEADER.styles);
        $header.attr('class', STICKY_HEADER.classes);
      },
      hide: function hide() {
        $logo.attr('src', STATIC_HEADER.logo);
        $header.attr('style', STATIC_HEADER.styles);
        $header.attr('class', STATIC_HEADER.classes);
      }
    };
    stickyHeader.init();

    // ****************************************************************************************************
    // ****************************************************************************************************
  });
})(jQuery, console.log);
(function ($, c) {
  document.addEventListener('DOMContentLoaded', function () {
    var $testimonioModal = $('#testimonio-modal'),
      $testimonioModal_content = $testimonioModal.find('.modal-content');
    $(document).on('click', ".testimonio__open", function (ev) {
      ev.preventDefault();
      var id = $(this).attr('data-id'),
        node = document.getElementById(id),
        clone = node.cloneNode(true);
      $testimonioModal_content.html(clone);
      $testimonioModal.modal('open');
    });

    // --------------------------------------------------------------------------------------------------------------
  });
})(jQuery, console.log);
(function ($, c) {
  document.addEventListener('DOMContentLoaded', function () {
    var toggle_buttons = document.querySelectorAll('.toggle-box');
    var headerHeight = MV23_GLOBALS.headerHeight;
    for (var i = 0; i < toggle_buttons.length; i++) {
      var selector = toggle_buttons[i].dataset.selector;
      if (selector) {
        var toggle_boxes = document.querySelectorAll(selector);
        for (var ind = 0; ind < toggle_boxes.length; ind++) {
          toggle_boxes[ind].style.display = 'none';
        }
      }
    }
    $('body').on('click', '.toggle-box', function (ev) {
      var selector = this.dataset.selector;
      var scrollToBox = this.dataset.scrollToBox;
      if (selector) {
        var boxes = document.querySelectorAll(selector);
        for (var ind = 0; ind < boxes.length; ind++) {
          if (boxes[ind].style.display === 'none') {
            $(this).parent().addClass('active');
            $(boxes[ind]).slideDown();
            if (scrollToBox) {
              $("html, body").animate({
                scrollTop: $(boxes[ind]).offset().top - headerHeight
              }, {
                duration: 800,
                queue: false
              });
            }
          } else {
            $(this).parent().removeClass('active');
            $(boxes[ind]).slideUp();
          }
        }
      }
    });
  });
})(jQuery, console.log);
(function ($, c) {
  $(function () {
    // ****************************************************************************************************
    // INIT TOGGLEBOXES SCRIPT
    // ****************************************************************************************************
    var toggleboxes = document.getElementsByClassName('v23-togglebox');
    for (var i = 0; i < toggleboxes.length; i++) {
      var el = toggleboxes[i],
        options = {
          headerHeight: MV23_GLOBALS.headerHeight
        };
      V23_ToggleBox.create(el, options);
    }

    // ****************************************************************************************************
  });
})(jQuery, console.log);
(function ($, c) {
  $(function () {
    var is_checkout = $('body').hasClass('woocommerce-checkout'),
      is_cart = $('body').hasClass('woocommerce-cart'),
      is_single = $('body').hasClass('single-product');

    // ****************************************************************************************************
    // ****************************************************************************************************

    function get_items_in_cart_qty(cart_fragments) {
      var TotalCount = 0;
      if (cart_fragments != null) {
        var cartCount = cart_fragments['div.widget_shopping_cart_content'].split('<span class=\"quantity\">');
        for (var index = 1; index < cartCount.length; index++) {
          var item = cartCount[index];
          var ItemCount = item.split(' &times;')[0];
          TotalCount += parseInt(ItemCount);
        }
      } else {
        TotalCount = MV23_GLOBALS.items_in_cart;
      }
      return TotalCount;
    }
    function show_cart_item_qty() {
      var cart_fragments = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
      // var qty = getCookie('woocommerce_items_in_cart'); // boolean cookie :/
      var qty = get_items_in_cart_qty(cart_fragments);
      $('.cart-items-qty').remove();
      if (qty != "" && qty != 0) {
        $('.show-cart-items-qty').append(' <span class="cart-items-qty">' + qty + '</span>');
      }
    }
    show_cart_item_qty();
    $(document.body).on('added_to_cart', function (event, fragments, cart_hash, btn) {
      show_cart_item_qty(fragments);
    });
    $(document.body).on('removed_from_cart', function (event, fragments, cart_hash, btn) {
      show_cart_item_qty(fragments);
    });
    if (MV23_GLOBALS.open_minicart_on_add_to_cart) {
      $(document.body).on('added_to_cart', function (event, fragments, cart_hash, btn) {
        var event = new Event('theme_open_minicart_on_product_added');
        document.body.dispatchEvent(event);
      });
    }

    // ****************************************************************************************************
    // ****************************************************************************************************
  });
})(jQuery, console.log);