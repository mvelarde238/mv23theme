"use strict";

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
/*
 * jQuery Easing v1.4.0 - http://gsgd.co.uk/sandbox/jquery/easing/
 * Open source under the BSD License.
 * Copyright © 2008 George McGinley Smith
 * All rights reserved.
 * https://raw.github.com/gdsmith/jquery-easing/master/LICENSE
*/

(function (factory) {
  if (typeof define === "function" && define.amd) {
    define(['jquery'], function ($) {
      return factory($);
    });
  } else if ((typeof module === "undefined" ? "undefined" : _typeof(module)) === "object" && _typeof(module.exports) === "object") {
    exports = factory(require('jquery'));
  } else {
    factory(jQuery);
  }
})(function ($) {
  // Preserve the original jQuery "swing" easing as "jswing"
  $.easing['jswing'] = $.easing['swing'];
  var pow = Math.pow,
    sqrt = Math.sqrt,
    sin = Math.sin,
    cos = Math.cos,
    PI = Math.PI,
    c1 = 1.70158,
    c2 = c1 * 1.525,
    c3 = c1 + 1,
    c4 = 2 * PI / 3,
    c5 = 2 * PI / 4.5;

  // x is the fraction of animation progress, in the range 0..1
  function bounceOut(x) {
    var n1 = 7.5625,
      d1 = 2.75;
    if (x < 1 / d1) {
      return n1 * x * x;
    } else if (x < 2 / d1) {
      return n1 * (x -= 1.5 / d1) * x + .75;
    } else if (x < 2.5 / d1) {
      return n1 * (x -= 2.25 / d1) * x + .9375;
    } else {
      return n1 * (x -= 2.625 / d1) * x + .984375;
    }
  }
  $.extend($.easing, {
    def: 'easeOutQuad',
    swing: function swing(x) {
      return $.easing[$.easing.def](x);
    },
    easeInQuad: function easeInQuad(x) {
      return x * x;
    },
    easeOutQuad: function easeOutQuad(x) {
      return 1 - (1 - x) * (1 - x);
    },
    easeInOutQuad: function easeInOutQuad(x) {
      return x < 0.5 ? 2 * x * x : 1 - pow(-2 * x + 2, 2) / 2;
    },
    easeInCubic: function easeInCubic(x) {
      return x * x * x;
    },
    easeOutCubic: function easeOutCubic(x) {
      return 1 - pow(1 - x, 3);
    },
    easeInOutCubic: function easeInOutCubic(x) {
      return x < 0.5 ? 4 * x * x * x : 1 - pow(-2 * x + 2, 3) / 2;
    },
    easeInQuart: function easeInQuart(x) {
      return x * x * x * x;
    },
    easeOutQuart: function easeOutQuart(x) {
      return 1 - pow(1 - x, 4);
    },
    easeInOutQuart: function easeInOutQuart(x) {
      return x < 0.5 ? 8 * x * x * x * x : 1 - pow(-2 * x + 2, 4) / 2;
    },
    easeInQuint: function easeInQuint(x) {
      return x * x * x * x * x;
    },
    easeOutQuint: function easeOutQuint(x) {
      return 1 - pow(1 - x, 5);
    },
    easeInOutQuint: function easeInOutQuint(x) {
      return x < 0.5 ? 16 * x * x * x * x * x : 1 - pow(-2 * x + 2, 5) / 2;
    },
    easeInSine: function easeInSine(x) {
      return 1 - cos(x * PI / 2);
    },
    easeOutSine: function easeOutSine(x) {
      return sin(x * PI / 2);
    },
    easeInOutSine: function easeInOutSine(x) {
      return -(cos(PI * x) - 1) / 2;
    },
    easeInExpo: function easeInExpo(x) {
      return x === 0 ? 0 : pow(2, 10 * x - 10);
    },
    easeOutExpo: function easeOutExpo(x) {
      return x === 1 ? 1 : 1 - pow(2, -10 * x);
    },
    easeInOutExpo: function easeInOutExpo(x) {
      return x === 0 ? 0 : x === 1 ? 1 : x < 0.5 ? pow(2, 20 * x - 10) / 2 : (2 - pow(2, -20 * x + 10)) / 2;
    },
    easeInCirc: function easeInCirc(x) {
      return 1 - sqrt(1 - pow(x, 2));
    },
    easeOutCirc: function easeOutCirc(x) {
      return sqrt(1 - pow(x - 1, 2));
    },
    easeInOutCirc: function easeInOutCirc(x) {
      return x < 0.5 ? (1 - sqrt(1 - pow(2 * x, 2))) / 2 : (sqrt(1 - pow(-2 * x + 2, 2)) + 1) / 2;
    },
    easeInElastic: function easeInElastic(x) {
      return x === 0 ? 0 : x === 1 ? 1 : -pow(2, 10 * x - 10) * sin((x * 10 - 10.75) * c4);
    },
    easeOutElastic: function easeOutElastic(x) {
      return x === 0 ? 0 : x === 1 ? 1 : pow(2, -10 * x) * sin((x * 10 - 0.75) * c4) + 1;
    },
    easeInOutElastic: function easeInOutElastic(x) {
      return x === 0 ? 0 : x === 1 ? 1 : x < 0.5 ? -(pow(2, 20 * x - 10) * sin((20 * x - 11.125) * c5)) / 2 : pow(2, -20 * x + 10) * sin((20 * x - 11.125) * c5) / 2 + 1;
    },
    easeInBack: function easeInBack(x) {
      return c3 * x * x * x - c1 * x * x;
    },
    easeOutBack: function easeOutBack(x) {
      return 1 + c3 * pow(x - 1, 3) + c1 * pow(x - 1, 2);
    },
    easeInOutBack: function easeInOutBack(x) {
      return x < 0.5 ? pow(2 * x, 2) * ((c2 + 1) * 2 * x - c2) / 2 : (pow(2 * x - 2, 2) * ((c2 + 1) * (x * 2 - 2) + c2) + 2) / 2;
    },
    easeInBounce: function easeInBounce(x) {
      return 1 - bounceOut(1 - x);
    },
    easeOutBounce: bounceOut,
    easeInOutBounce: function easeInOutBounce(x) {
      return x < 0.5 ? (1 - bounceOut(1 - 2 * x)) / 2 : (1 + bounceOut(2 * x - 1)) / 2;
    }
  });
});
/*! VelocityJS.org (1.2.3). (C) 2014 Julian Shapiro. MIT @license: en.wikipedia.org/wiki/MIT_License */
/*! VelocityJS.org jQuery Shim (1.0.1). (C) 2014 The jQuery Foundation. MIT @license: en.wikipedia.org/wiki/MIT_License. */
/*! Note that this has been modified by Materialize to confirm that Velocity is not already being imported. */
jQuery.Velocity ? console.log("Velocity is already loaded. You may be needlessly importing Velocity again; note that Materialize includes Velocity.") : (!function (e) {
  function t(e) {
    var t = e.length,
      a = r.type(e);
    return "function" === a || r.isWindow(e) ? !1 : 1 === e.nodeType && t ? !0 : "array" === a || 0 === t || "number" == typeof t && t > 0 && t - 1 in e;
  }
  if (!e.jQuery) {
    var r = function r(e, t) {
      return new r.fn.init(e, t);
    };
    r.isWindow = function (e) {
      return null != e && e == e.window;
    }, r.type = function (e) {
      return null == e ? e + "" : "object" == _typeof(e) || "function" == typeof e ? n[i.call(e)] || "object" : _typeof(e);
    }, r.isArray = Array.isArray || function (e) {
      return "array" === r.type(e);
    }, r.isPlainObject = function (e) {
      var t;
      if (!e || "object" !== r.type(e) || e.nodeType || r.isWindow(e)) return !1;
      try {
        if (e.constructor && !o.call(e, "constructor") && !o.call(e.constructor.prototype, "isPrototypeOf")) return !1;
      } catch (a) {
        return !1;
      }
      for (t in e);
      return void 0 === t || o.call(e, t);
    }, r.each = function (e, r, a) {
      var n,
        o = 0,
        i = e.length,
        s = t(e);
      if (a) {
        if (s) for (; i > o && (n = r.apply(e[o], a), n !== !1); o++);else for (o in e) if (n = r.apply(e[o], a), n === !1) break;
      } else if (s) for (; i > o && (n = r.call(e[o], o, e[o]), n !== !1); o++);else for (o in e) if (n = r.call(e[o], o, e[o]), n === !1) break;
      return e;
    }, r.data = function (e, t, n) {
      if (void 0 === n) {
        var o = e[r.expando],
          i = o && a[o];
        if (void 0 === t) return i;
        if (i && t in i) return i[t];
      } else if (void 0 !== t) {
        var o = e[r.expando] || (e[r.expando] = ++r.uuid);
        return a[o] = a[o] || {}, a[o][t] = n, n;
      }
    }, r.removeData = function (e, t) {
      var n = e[r.expando],
        o = n && a[n];
      o && r.each(t, function (e, t) {
        delete o[t];
      });
    }, r.extend = function () {
      var e,
        t,
        a,
        n,
        o,
        i,
        s = arguments[0] || {},
        l = 1,
        u = arguments.length,
        c = !1;
      for ("boolean" == typeof s && (c = s, s = arguments[l] || {}, l++), "object" != _typeof(s) && "function" !== r.type(s) && (s = {}), l === u && (s = this, l--); u > l; l++) if (null != (o = arguments[l])) for (n in o) e = s[n], a = o[n], s !== a && (c && a && (r.isPlainObject(a) || (t = r.isArray(a))) ? (t ? (t = !1, i = e && r.isArray(e) ? e : []) : i = e && r.isPlainObject(e) ? e : {}, s[n] = r.extend(c, i, a)) : void 0 !== a && (s[n] = a));
      return s;
    }, r.queue = function (e, a, n) {
      function o(e, r) {
        var a = r || [];
        return null != e && (t(Object(e)) ? !function (e, t) {
          for (var r = +t.length, a = 0, n = e.length; r > a;) e[n++] = t[a++];
          if (r !== r) for (; void 0 !== t[a];) e[n++] = t[a++];
          return e.length = n, e;
        }(a, "string" == typeof e ? [e] : e) : [].push.call(a, e)), a;
      }
      if (e) {
        a = (a || "fx") + "queue";
        var i = r.data(e, a);
        return n ? (!i || r.isArray(n) ? i = r.data(e, a, o(n)) : i.push(n), i) : i || [];
      }
    }, r.dequeue = function (e, t) {
      r.each(e.nodeType ? [e] : e, function (e, a) {
        t = t || "fx";
        var n = r.queue(a, t),
          o = n.shift();
        "inprogress" === o && (o = n.shift()), o && ("fx" === t && n.unshift("inprogress"), o.call(a, function () {
          r.dequeue(a, t);
        }));
      });
    }, r.fn = r.prototype = {
      init: function init(e) {
        if (e.nodeType) return this[0] = e, this;
        throw new Error("Not a DOM node.");
      },
      offset: function offset() {
        var t = this[0].getBoundingClientRect ? this[0].getBoundingClientRect() : {
          top: 0,
          left: 0
        };
        return {
          top: t.top + (e.pageYOffset || document.scrollTop || 0) - (document.clientTop || 0),
          left: t.left + (e.pageXOffset || document.scrollLeft || 0) - (document.clientLeft || 0)
        };
      },
      position: function position() {
        function e() {
          for (var e = this.offsetParent || document; e && "html" === !e.nodeType.toLowerCase && "static" === e.style.position;) e = e.offsetParent;
          return e || document;
        }
        var t = this[0],
          e = e.apply(t),
          a = this.offset(),
          n = /^(?:body|html)$/i.test(e.nodeName) ? {
            top: 0,
            left: 0
          } : r(e).offset();
        return a.top -= parseFloat(t.style.marginTop) || 0, a.left -= parseFloat(t.style.marginLeft) || 0, e.style && (n.top += parseFloat(e.style.borderTopWidth) || 0, n.left += parseFloat(e.style.borderLeftWidth) || 0), {
          top: a.top - n.top,
          left: a.left - n.left
        };
      }
    };
    var a = {};
    r.expando = "velocity" + new Date().getTime(), r.uuid = 0;
    for (var n = {}, o = n.hasOwnProperty, i = n.toString, s = "Boolean Number String Function Array Date RegExp Object Error".split(" "), l = 0; l < s.length; l++) n["[object " + s[l] + "]"] = s[l].toLowerCase();
    r.fn.init.prototype = r.fn, e.Velocity = {
      Utilities: r
    };
  }
}(window), function (e) {
  "object" == (typeof module === "undefined" ? "undefined" : _typeof(module)) && "object" == _typeof(module.exports) ? module.exports = e() : "function" == typeof define && define.amd ? define(e) : e();
}(function () {
  return function (e, t, r, a) {
    function n(e) {
      for (var t = -1, r = e ? e.length : 0, a = []; ++t < r;) {
        var n = e[t];
        n && a.push(n);
      }
      return a;
    }
    function o(e) {
      return m.isWrapped(e) ? e = [].slice.call(e) : m.isNode(e) && (e = [e]), e;
    }
    function i(e) {
      var t = f.data(e, "velocity");
      return null === t ? a : t;
    }
    function s(e) {
      return function (t) {
        return Math.round(t * e) * (1 / e);
      };
    }
    function l(e, r, a, n) {
      function o(e, t) {
        return 1 - 3 * t + 3 * e;
      }
      function i(e, t) {
        return 3 * t - 6 * e;
      }
      function s(e) {
        return 3 * e;
      }
      function l(e, t, r) {
        return ((o(t, r) * e + i(t, r)) * e + s(t)) * e;
      }
      function u(e, t, r) {
        return 3 * o(t, r) * e * e + 2 * i(t, r) * e + s(t);
      }
      function c(t, r) {
        for (var n = 0; m > n; ++n) {
          var o = u(r, e, a);
          if (0 === o) return r;
          var i = l(r, e, a) - t;
          r -= i / o;
        }
        return r;
      }
      function p() {
        for (var t = 0; b > t; ++t) w[t] = l(t * x, e, a);
      }
      function f(t, r, n) {
        var o,
          i,
          s = 0;
        do i = r + (n - r) / 2, o = l(i, e, a) - t, o > 0 ? n = i : r = i; while (Math.abs(o) > h && ++s < v);
        return i;
      }
      function d(t) {
        for (var r = 0, n = 1, o = b - 1; n != o && w[n] <= t; ++n) r += x;
        --n;
        var i = (t - w[n]) / (w[n + 1] - w[n]),
          s = r + i * x,
          l = u(s, e, a);
        return l >= y ? c(t, s) : 0 == l ? s : f(t, r, r + x);
      }
      function g() {
        V = !0, (e != r || a != n) && p();
      }
      var m = 4,
        y = .001,
        h = 1e-7,
        v = 10,
        b = 11,
        x = 1 / (b - 1),
        S = ("Float32Array" in t);
      if (4 !== arguments.length) return !1;
      for (var P = 0; 4 > P; ++P) if ("number" != typeof arguments[P] || isNaN(arguments[P]) || !isFinite(arguments[P])) return !1;
      e = Math.min(e, 1), a = Math.min(a, 1), e = Math.max(e, 0), a = Math.max(a, 0);
      var w = S ? new Float32Array(b) : new Array(b),
        V = !1,
        C = function C(t) {
          return V || g(), e === r && a === n ? t : 0 === t ? 0 : 1 === t ? 1 : l(d(t), r, n);
        };
      C.getControlPoints = function () {
        return [{
          x: e,
          y: r
        }, {
          x: a,
          y: n
        }];
      };
      var T = "generateBezier(" + [e, r, a, n] + ")";
      return C.toString = function () {
        return T;
      }, C;
    }
    function u(e, t) {
      var r = e;
      return m.isString(e) ? b.Easings[e] || (r = !1) : r = m.isArray(e) && 1 === e.length ? s.apply(null, e) : m.isArray(e) && 2 === e.length ? x.apply(null, e.concat([t])) : m.isArray(e) && 4 === e.length ? l.apply(null, e) : !1, r === !1 && (r = b.Easings[b.defaults.easing] ? b.defaults.easing : v), r;
    }
    function c(e) {
      if (e) {
        var t = new Date().getTime(),
          r = b.State.calls.length;
        r > 1e4 && (b.State.calls = n(b.State.calls));
        for (var o = 0; r > o; o++) if (b.State.calls[o]) {
          var s = b.State.calls[o],
            l = s[0],
            u = s[2],
            d = s[3],
            g = !!d,
            y = null;
          d || (d = b.State.calls[o][3] = t - 16);
          for (var h = Math.min((t - d) / u.duration, 1), v = 0, x = l.length; x > v; v++) {
            var P = l[v],
              V = P.element;
            if (i(V)) {
              var C = !1;
              if (u.display !== a && null !== u.display && "none" !== u.display) {
                if ("flex" === u.display) {
                  var T = ["-webkit-box", "-moz-box", "-ms-flexbox", "-webkit-flex"];
                  f.each(T, function (e, t) {
                    S.setPropertyValue(V, "display", t);
                  });
                }
                S.setPropertyValue(V, "display", u.display);
              }
              u.visibility !== a && "hidden" !== u.visibility && S.setPropertyValue(V, "visibility", u.visibility);
              for (var k in P) if ("element" !== k) {
                var A,
                  F = P[k],
                  j = m.isString(F.easing) ? b.Easings[F.easing] : F.easing;
                if (1 === h) A = F.endValue;else {
                  var E = F.endValue - F.startValue;
                  if (A = F.startValue + E * j(h, u, E), !g && A === F.currentValue) continue;
                }
                if (F.currentValue = A, "tween" === k) y = A;else {
                  if (S.Hooks.registered[k]) {
                    var H = S.Hooks.getRoot(k),
                      N = i(V).rootPropertyValueCache[H];
                    N && (F.rootPropertyValue = N);
                  }
                  var L = S.setPropertyValue(V, k, F.currentValue + (0 === parseFloat(A) ? "" : F.unitType), F.rootPropertyValue, F.scrollData);
                  S.Hooks.registered[k] && (i(V).rootPropertyValueCache[H] = S.Normalizations.registered[H] ? S.Normalizations.registered[H]("extract", null, L[1]) : L[1]), "transform" === L[0] && (C = !0);
                }
              }
              u.mobileHA && i(V).transformCache.translate3d === a && (i(V).transformCache.translate3d = "(0px, 0px, 0px)", C = !0), C && S.flushTransformCache(V);
            }
          }
          u.display !== a && "none" !== u.display && (b.State.calls[o][2].display = !1), u.visibility !== a && "hidden" !== u.visibility && (b.State.calls[o][2].visibility = !1), u.progress && u.progress.call(s[1], s[1], h, Math.max(0, d + u.duration - t), d, y), 1 === h && p(o);
        }
      }
      b.State.isTicking && w(c);
    }
    function p(e, t) {
      if (!b.State.calls[e]) return !1;
      var _loop = function _loop() {
          p = r[u].element;
          if (t || o.loop || ("none" === o.display && S.setPropertyValue(p, "display", o.display), "hidden" === o.visibility && S.setPropertyValue(p, "visibility", o.visibility)), o.loop !== !0 && (f.queue(p)[1] === a || !/\.velocityQueueEntryFlag/i.test(f.queue(p)[1])) && i(p)) {
            i(p).isAnimating = !1, i(p).rootPropertyValueCache = {};
            d = !1;
            f.each(S.Lists.transforms3D, function (e, t) {
              var r = /^scale/.test(t) ? 1 : 0,
                n = i(p).transformCache[t];
              i(p).transformCache[t] !== a && new RegExp("^\\(" + r + "[^.]").test(n) && (d = !0, delete i(p).transformCache[t]);
            }), o.mobileHA && (d = !0, delete i(p).transformCache.translate3d), d && S.flushTransformCache(p), S.Values.removeClass(p, "velocity-animating");
          }
          if (!t && o.complete && !o.loop && u === c - 1) try {
            o.complete.call(n, n);
          } catch (g) {
            setTimeout(function () {
              throw g;
            }, 1);
          }
          s && o.loop !== !0 && s(n), i(p) && o.loop === !0 && !t && (f.each(i(p).tweensContainer, function (e, t) {
            /^rotate/.test(e) && 360 === parseFloat(t.endValue) && (t.endValue = 0, t.startValue = 360), /^backgroundPosition/.test(e) && 100 === parseFloat(t.endValue) && "%" === t.unitType && (t.endValue = 0, t.startValue = 100);
          }), b(p, "reverse", {
            loop: !0,
            delay: o.delay
          })), o.queue !== !1 && f.dequeue(p, o.queue);
        },
        p,
        d;
      for (var r = b.State.calls[e][0], n = b.State.calls[e][1], o = b.State.calls[e][2], s = b.State.calls[e][4], l = !1, u = 0, c = r.length; c > u; u++) {
        _loop();
      }
      b.State.calls[e] = !1;
      for (var m = 0, y = b.State.calls.length; y > m; m++) if (b.State.calls[m] !== !1) {
        l = !0;
        break;
      }
      l === !1 && (b.State.isTicking = !1, delete b.State.calls, b.State.calls = []);
    }
    var f,
      d = function () {
        if (r.documentMode) return r.documentMode;
        for (var e = 7; e > 4; e--) {
          var t = r.createElement("div");
          if (t.innerHTML = "<!--[if IE " + e + "]><span></span><![endif]-->", t.getElementsByTagName("span").length) return t = null, e;
        }
        return a;
      }(),
      g = function () {
        var e = 0;
        return t.webkitRequestAnimationFrame || t.mozRequestAnimationFrame || function (t) {
          var r,
            a = new Date().getTime();
          return r = Math.max(0, 16 - (a - e)), e = a + r, setTimeout(function () {
            t(a + r);
          }, r);
        };
      }(),
      m = {
        isString: function isString(e) {
          return "string" == typeof e;
        },
        isArray: Array.isArray || function (e) {
          return "[object Array]" === Object.prototype.toString.call(e);
        },
        isFunction: function isFunction(e) {
          return "[object Function]" === Object.prototype.toString.call(e);
        },
        isNode: function isNode(e) {
          return e && e.nodeType;
        },
        isNodeList: function isNodeList(e) {
          return "object" == _typeof(e) && /^\[object (HTMLCollection|NodeList|Object)\]$/.test(Object.prototype.toString.call(e)) && e.length !== a && (0 === e.length || "object" == _typeof(e[0]) && e[0].nodeType > 0);
        },
        isWrapped: function isWrapped(e) {
          return e && (e.jquery || t.Zepto && t.Zepto.zepto.isZ(e));
        },
        isSVG: function isSVG(e) {
          return t.SVGElement && e instanceof t.SVGElement;
        },
        isEmptyObject: function isEmptyObject(e) {
          for (var t in e) return !1;
          return !0;
        }
      },
      y = !1;
    if (e.fn && e.fn.jquery ? (f = e, y = !0) : f = t.Velocity.Utilities, 8 >= d && !y) throw new Error("Velocity: IE8 and below require jQuery to be loaded before Velocity.");
    if (7 >= d) return void (jQuery.fn.velocity = jQuery.fn.animate);
    var h = 400,
      v = "swing",
      b = {
        State: {
          isMobile: /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),
          isAndroid: /Android/i.test(navigator.userAgent),
          isGingerbread: /Android 2\.3\.[3-7]/i.test(navigator.userAgent),
          isChrome: t.chrome,
          isFirefox: /Firefox/i.test(navigator.userAgent),
          prefixElement: r.createElement("div"),
          prefixMatches: {},
          scrollAnchor: null,
          scrollPropertyLeft: null,
          scrollPropertyTop: null,
          isTicking: !1,
          calls: []
        },
        CSS: {},
        Utilities: f,
        Redirects: {},
        Easings: {},
        Promise: t.Promise,
        defaults: {
          queue: "",
          duration: h,
          easing: v,
          begin: a,
          complete: a,
          progress: a,
          display: a,
          visibility: a,
          loop: !1,
          delay: !1,
          mobileHA: !0,
          _cacheValues: !0
        },
        init: function init(e) {
          f.data(e, "velocity", {
            isSVG: m.isSVG(e),
            isAnimating: !1,
            computedStyle: null,
            tweensContainer: null,
            rootPropertyValueCache: {},
            transformCache: {}
          });
        },
        hook: null,
        mock: !1,
        version: {
          major: 1,
          minor: 2,
          patch: 2
        },
        debug: !1
      };
    t.pageYOffset !== a ? (b.State.scrollAnchor = t, b.State.scrollPropertyLeft = "pageXOffset", b.State.scrollPropertyTop = "pageYOffset") : (b.State.scrollAnchor = r.documentElement || r.body.parentNode || r.body, b.State.scrollPropertyLeft = "scrollLeft", b.State.scrollPropertyTop = "scrollTop");
    var x = function () {
      function e(e) {
        return -e.tension * e.x - e.friction * e.v;
      }
      function t(t, r, a) {
        var n = {
          x: t.x + a.dx * r,
          v: t.v + a.dv * r,
          tension: t.tension,
          friction: t.friction
        };
        return {
          dx: n.v,
          dv: e(n)
        };
      }
      function r(r, a) {
        var n = {
            dx: r.v,
            dv: e(r)
          },
          o = t(r, .5 * a, n),
          i = t(r, .5 * a, o),
          s = t(r, a, i),
          l = 1 / 6 * (n.dx + 2 * (o.dx + i.dx) + s.dx),
          u = 1 / 6 * (n.dv + 2 * (o.dv + i.dv) + s.dv);
        return r.x = r.x + l * a, r.v = r.v + u * a, r;
      }
      return function a(e, t, n) {
        var o,
          i,
          s,
          l = {
            x: -1,
            v: 0,
            tension: null,
            friction: null
          },
          u = [0],
          c = 0,
          p = 1e-4,
          f = .016;
        for (e = parseFloat(e) || 500, t = parseFloat(t) || 20, n = n || null, l.tension = e, l.friction = t, o = null !== n, o ? (c = a(e, t), i = c / n * f) : i = f; s = r(s || l, i), u.push(1 + s.x), c += 16, Math.abs(s.x) > p && Math.abs(s.v) > p;);
        return o ? function (e) {
          return u[e * (u.length - 1) | 0];
        } : c;
      };
    }();
    b.Easings = {
      linear: function linear(e) {
        return e;
      },
      swing: function swing(e) {
        return .5 - Math.cos(e * Math.PI) / 2;
      },
      spring: function spring(e) {
        return 1 - Math.cos(4.5 * e * Math.PI) * Math.exp(6 * -e);
      }
    }, f.each([["ease", [.25, .1, .25, 1]], ["ease-in", [.42, 0, 1, 1]], ["ease-out", [0, 0, .58, 1]], ["ease-in-out", [.42, 0, .58, 1]], ["easeInSine", [.47, 0, .745, .715]], ["easeOutSine", [.39, .575, .565, 1]], ["easeInOutSine", [.445, .05, .55, .95]], ["easeInQuad", [.55, .085, .68, .53]], ["easeOutQuad", [.25, .46, .45, .94]], ["easeInOutQuad", [.455, .03, .515, .955]], ["easeInCubic", [.55, .055, .675, .19]], ["easeOutCubic", [.215, .61, .355, 1]], ["easeInOutCubic", [.645, .045, .355, 1]], ["easeInQuart", [.895, .03, .685, .22]], ["easeOutQuart", [.165, .84, .44, 1]], ["easeInOutQuart", [.77, 0, .175, 1]], ["easeInQuint", [.755, .05, .855, .06]], ["easeOutQuint", [.23, 1, .32, 1]], ["easeInOutQuint", [.86, 0, .07, 1]], ["easeInExpo", [.95, .05, .795, .035]], ["easeOutExpo", [.19, 1, .22, 1]], ["easeInOutExpo", [1, 0, 0, 1]], ["easeInCirc", [.6, .04, .98, .335]], ["easeOutCirc", [.075, .82, .165, 1]], ["easeInOutCirc", [.785, .135, .15, .86]]], function (e, t) {
      b.Easings[t[0]] = l.apply(null, t[1]);
    });
    var S = b.CSS = {
      RegEx: {
        isHex: /^#([A-f\d]{3}){1,2}$/i,
        valueUnwrap: /^[A-z]+\((.*)\)$/i,
        wrappedValueAlreadyExtracted: /[0-9.]+ [0-9.]+ [0-9.]+( [0-9.]+)?/,
        valueSplit: /([A-z]+\(.+\))|(([A-z0-9#-.]+?)(?=\s|$))/gi
      },
      Lists: {
        colors: ["fill", "stroke", "stopColor", "color", "backgroundColor", "borderColor", "borderTopColor", "borderRightColor", "borderBottomColor", "borderLeftColor", "outlineColor"],
        transformsBase: ["translateX", "translateY", "scale", "scaleX", "scaleY", "skewX", "skewY", "rotateZ"],
        transforms3D: ["transformPerspective", "translateZ", "scaleZ", "rotateX", "rotateY"]
      },
      Hooks: {
        templates: {
          textShadow: ["Color X Y Blur", "black 0px 0px 0px"],
          boxShadow: ["Color X Y Blur Spread", "black 0px 0px 0px 0px"],
          clip: ["Top Right Bottom Left", "0px 0px 0px 0px"],
          backgroundPosition: ["X Y", "0% 0%"],
          transformOrigin: ["X Y Z", "50% 50% 0px"],
          perspectiveOrigin: ["X Y", "50% 50%"]
        },
        registered: {},
        register: function register() {
          for (var e = 0; e < S.Lists.colors.length; e++) {
            var t = "color" === S.Lists.colors[e] ? "0 0 0 1" : "255 255 255 1";
            S.Hooks.templates[S.Lists.colors[e]] = ["Red Green Blue Alpha", t];
          }
          var r, a, n;
          if (d) for (r in S.Hooks.templates) {
            a = S.Hooks.templates[r], n = a[0].split(" ");
            var o = a[1].match(S.RegEx.valueSplit);
            "Color" === n[0] && (n.push(n.shift()), o.push(o.shift()), S.Hooks.templates[r] = [n.join(" "), o.join(" ")]);
          }
          for (r in S.Hooks.templates) {
            a = S.Hooks.templates[r], n = a[0].split(" ");
            for (var e in n) {
              var i = r + n[e],
                s = e;
              S.Hooks.registered[i] = [r, s];
            }
          }
        },
        getRoot: function getRoot(e) {
          var t = S.Hooks.registered[e];
          return t ? t[0] : e;
        },
        cleanRootPropertyValue: function cleanRootPropertyValue(e, t) {
          return S.RegEx.valueUnwrap.test(t) && (t = t.match(S.RegEx.valueUnwrap)[1]), S.Values.isCSSNullValue(t) && (t = S.Hooks.templates[e][1]), t;
        },
        extractValue: function extractValue(e, t) {
          var r = S.Hooks.registered[e];
          if (r) {
            var a = r[0],
              n = r[1];
            return t = S.Hooks.cleanRootPropertyValue(a, t), t.toString().match(S.RegEx.valueSplit)[n];
          }
          return t;
        },
        injectValue: function injectValue(e, t, r) {
          var a = S.Hooks.registered[e];
          if (a) {
            var n,
              o,
              i = a[0],
              s = a[1];
            return r = S.Hooks.cleanRootPropertyValue(i, r), n = r.toString().match(S.RegEx.valueSplit), n[s] = t, o = n.join(" ");
          }
          return r;
        }
      },
      Normalizations: {
        registered: {
          clip: function clip(e, t, r) {
            switch (e) {
              case "name":
                return "clip";
              case "extract":
                var a;
                return S.RegEx.wrappedValueAlreadyExtracted.test(r) ? a = r : (a = r.toString().match(S.RegEx.valueUnwrap), a = a ? a[1].replace(/,(\s+)?/g, " ") : r), a;
              case "inject":
                return "rect(" + r + ")";
            }
          },
          blur: function blur(e, t, r) {
            switch (e) {
              case "name":
                return b.State.isFirefox ? "filter" : "-webkit-filter";
              case "extract":
                var a = parseFloat(r);
                if (!a && 0 !== a) {
                  var n = r.toString().match(/blur\(([0-9]+[A-z]+)\)/i);
                  a = n ? n[1] : 0;
                }
                return a;
              case "inject":
                return parseFloat(r) ? "blur(" + r + ")" : "none";
            }
          },
          opacity: function opacity(e, t, r) {
            if (8 >= d) switch (e) {
              case "name":
                return "filter";
              case "extract":
                var a = r.toString().match(/alpha\(opacity=(.*)\)/i);
                return r = a ? a[1] / 100 : 1;
              case "inject":
                return t.style.zoom = 1, parseFloat(r) >= 1 ? "" : "alpha(opacity=" + parseInt(100 * parseFloat(r), 10) + ")";
            } else switch (e) {
              case "name":
                return "opacity";
              case "extract":
                return r;
              case "inject":
                return r;
            }
          }
        },
        register: function register() {
          9 >= d || b.State.isGingerbread || (S.Lists.transformsBase = S.Lists.transformsBase.concat(S.Lists.transforms3D));
          for (var e = 0; e < S.Lists.transformsBase.length; e++) !function () {
            var t = S.Lists.transformsBase[e];
            S.Normalizations.registered[t] = function (e, r, n) {
              switch (e) {
                case "name":
                  return "transform";
                case "extract":
                  return i(r) === a || i(r).transformCache[t] === a ? /^scale/i.test(t) ? 1 : 0 : i(r).transformCache[t].replace(/[()]/g, "");
                case "inject":
                  var o = !1;
                  switch (t.substr(0, t.length - 1)) {
                    case "translate":
                      o = !/(%|px|em|rem|vw|vh|\d)$/i.test(n);
                      break;
                    case "scal":
                    case "scale":
                      b.State.isAndroid && i(r).transformCache[t] === a && 1 > n && (n = 1), o = !/(\d)$/i.test(n);
                      break;
                    case "skew":
                      o = !/(deg|\d)$/i.test(n);
                      break;
                    case "rotate":
                      o = !/(deg|\d)$/i.test(n);
                  }
                  return o || (i(r).transformCache[t] = "(" + n + ")"), i(r).transformCache[t];
              }
            };
          }();
          for (var e = 0; e < S.Lists.colors.length; e++) !function () {
            var t = S.Lists.colors[e];
            S.Normalizations.registered[t] = function (e, r, n) {
              switch (e) {
                case "name":
                  return t;
                case "extract":
                  var o;
                  if (S.RegEx.wrappedValueAlreadyExtracted.test(n)) o = n;else {
                    var i,
                      s = {
                        black: "rgb(0, 0, 0)",
                        blue: "rgb(0, 0, 255)",
                        gray: "rgb(128, 128, 128)",
                        green: "rgb(0, 128, 0)",
                        red: "rgb(255, 0, 0)",
                        white: "rgb(255, 255, 255)"
                      };
                    /^[A-z]+$/i.test(n) ? i = s[n] !== a ? s[n] : s.black : S.RegEx.isHex.test(n) ? i = "rgb(" + S.Values.hexToRgb(n).join(" ") + ")" : /^rgba?\(/i.test(n) || (i = s.black), o = (i || n).toString().match(S.RegEx.valueUnwrap)[1].replace(/,(\s+)?/g, " ");
                  }
                  return 8 >= d || 3 !== o.split(" ").length || (o += " 1"), o;
                case "inject":
                  return 8 >= d ? 4 === n.split(" ").length && (n = n.split(/\s+/).slice(0, 3).join(" ")) : 3 === n.split(" ").length && (n += " 1"), (8 >= d ? "rgb" : "rgba") + "(" + n.replace(/\s+/g, ",").replace(/\.(\d)+(?=,)/g, "") + ")";
              }
            };
          }();
        }
      },
      Names: {
        fortunatalCase: function fortunatalCase(e) {
          return e.replace(/-(\w)/g, function (e, t) {
            return t.toUpperCase();
          });
        },
        SVGAttribute: function SVGAttribute(e) {
          var t = "width|height|x|y|cx|cy|r|rx|ry|x1|x2|y1|y2";
          return (d || b.State.isAndroid && !b.State.isChrome) && (t += "|transform"), new RegExp("^(" + t + ")$", "i").test(e);
        },
        prefixCheck: function prefixCheck(e) {
          if (b.State.prefixMatches[e]) return [b.State.prefixMatches[e], !0];
          for (var t = ["", "Webkit", "Moz", "ms", "O"], r = 0, a = t.length; a > r; r++) {
            var n;
            if (n = 0 === r ? e : t[r] + e.replace(/^\w/, function (e) {
              return e.toUpperCase();
            }), m.isString(b.State.prefixElement.style[n])) return b.State.prefixMatches[e] = n, [n, !0];
          }
          return [e, !1];
        }
      },
      Values: {
        hexToRgb: function hexToRgb(e) {
          var t,
            r = /^#?([a-f\d])([a-f\d])([a-f\d])$/i,
            a = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i;
          return e = e.replace(r, function (e, t, r, a) {
            return t + t + r + r + a + a;
          }), t = a.exec(e), t ? [parseInt(t[1], 16), parseInt(t[2], 16), parseInt(t[3], 16)] : [0, 0, 0];
        },
        isCSSNullValue: function isCSSNullValue(e) {
          return 0 == e || /^(none|auto|transparent|(rgba\(0, ?0, ?0, ?0\)))$/i.test(e);
        },
        getUnitType: function getUnitType(e) {
          return /^(rotate|skew)/i.test(e) ? "deg" : /(^(scale|scaleX|scaleY|scaleZ|alpha|flexGrow|flexHeight|zIndex|fontWeight)$)|((opacity|red|green|blue|alpha)$)/i.test(e) ? "" : "px";
        },
        getDisplayType: function getDisplayType(e) {
          var t = e && e.tagName.toString().toLowerCase();
          return /^(b|big|i|small|tt|abbr|acronym|cite|code|dfn|em|kbd|strong|samp|var|a|bdo|br|img|map|object|q|script|span|sub|sup|button|input|label|select|textarea)$/i.test(t) ? "inline" : /^(li)$/i.test(t) ? "list-item" : /^(tr)$/i.test(t) ? "table-row" : /^(table)$/i.test(t) ? "table" : /^(tbody)$/i.test(t) ? "table-row-group" : "block";
        },
        addClass: function addClass(e, t) {
          e.classList ? e.classList.add(t) : e.className += (e.className.length ? " " : "") + t;
        },
        removeClass: function removeClass(e, t) {
          e.classList ? e.classList.remove(t) : e.className = e.className.toString().replace(new RegExp("(^|\\s)" + t.split(" ").join("|") + "(\\s|$)", "gi"), " ");
        }
      },
      getPropertyValue: function getPropertyValue(e, r, n, o) {
        function s(e, r) {
          function n() {
            u && S.setPropertyValue(e, "display", "none");
          }
          var l = 0;
          if (8 >= d) l = f.css(e, r);else {
            var u = !1;
            if (/^(width|height)$/.test(r) && 0 === S.getPropertyValue(e, "display") && (u = !0, S.setPropertyValue(e, "display", S.Values.getDisplayType(e))), !o) {
              if ("height" === r && "border-box" !== S.getPropertyValue(e, "boxSizing").toString().toLowerCase()) {
                var c = e.offsetHeight - (parseFloat(S.getPropertyValue(e, "borderTopWidth")) || 0) - (parseFloat(S.getPropertyValue(e, "borderBottomWidth")) || 0) - (parseFloat(S.getPropertyValue(e, "paddingTop")) || 0) - (parseFloat(S.getPropertyValue(e, "paddingBottom")) || 0);
                return n(), c;
              }
              if ("width" === r && "border-box" !== S.getPropertyValue(e, "boxSizing").toString().toLowerCase()) {
                var p = e.offsetWidth - (parseFloat(S.getPropertyValue(e, "borderLeftWidth")) || 0) - (parseFloat(S.getPropertyValue(e, "borderRightWidth")) || 0) - (parseFloat(S.getPropertyValue(e, "paddingLeft")) || 0) - (parseFloat(S.getPropertyValue(e, "paddingRight")) || 0);
                return n(), p;
              }
            }
            var g;
            g = i(e) === a ? t.getComputedStyle(e, null) : i(e).computedStyle ? i(e).computedStyle : i(e).computedStyle = t.getComputedStyle(e, null), "borderColor" === r && (r = "borderTopColor"), l = 9 === d && "filter" === r ? g.getPropertyValue(r) : g[r], ("" === l || null === l) && (l = e.style[r]), n();
          }
          if ("auto" === l && /^(top|right|bottom|left)$/i.test(r)) {
            var m = s(e, "position");
            ("fixed" === m || "absolute" === m && /top|left/i.test(r)) && (l = f(e).position()[r] + "px");
          }
          return l;
        }
        var l;
        if (S.Hooks.registered[r]) {
          var u = r,
            c = S.Hooks.getRoot(u);
          n === a && (n = S.getPropertyValue(e, S.Names.prefixCheck(c)[0])), S.Normalizations.registered[c] && (n = S.Normalizations.registered[c]("extract", e, n)), l = S.Hooks.extractValue(u, n);
        } else if (S.Normalizations.registered[r]) {
          var p, g;
          p = S.Normalizations.registered[r]("name", e), "transform" !== p && (g = s(e, S.Names.prefixCheck(p)[0]), S.Values.isCSSNullValue(g) && S.Hooks.templates[r] && (g = S.Hooks.templates[r][1])), l = S.Normalizations.registered[r]("extract", e, g);
        }
        if (!/^[\d-]/.test(l)) if (i(e) && i(e).isSVG && S.Names.SVGAttribute(r)) {
          if (/^(height|width)$/i.test(r)) try {
            l = e.getBBox()[r];
          } catch (m) {
            l = 0;
          } else l = e.getAttribute(r);
        } else l = s(e, S.Names.prefixCheck(r)[0]);
        return S.Values.isCSSNullValue(l) && (l = 0), b.debug >= 2 && console.log("Get " + r + ": " + l), l;
      },
      setPropertyValue: function setPropertyValue(e, r, a, n, o) {
        var s = r;
        if ("scroll" === r) o.container ? o.container["scroll" + o.direction] = a : "Left" === o.direction ? t.scrollTo(a, o.alternateValue) : t.scrollTo(o.alternateValue, a);else if (S.Normalizations.registered[r] && "transform" === S.Normalizations.registered[r]("name", e)) S.Normalizations.registered[r]("inject", e, a), s = "transform", a = i(e).transformCache[r];else {
          if (S.Hooks.registered[r]) {
            var l = r,
              u = S.Hooks.getRoot(r);
            n = n || S.getPropertyValue(e, u), a = S.Hooks.injectValue(l, a, n), r = u;
          }
          if (S.Normalizations.registered[r] && (a = S.Normalizations.registered[r]("inject", e, a), r = S.Normalizations.registered[r]("name", e)), s = S.Names.prefixCheck(r)[0], 8 >= d) try {
            e.style[s] = a;
          } catch (c) {
            b.debug && console.log("Browser does not support [" + a + "] for [" + s + "]");
          } else i(e) && i(e).isSVG && S.Names.SVGAttribute(r) ? e.setAttribute(r, a) : e.style[s] = a;
          b.debug >= 2 && console.log("Set " + r + " (" + s + "): " + a);
        }
        return [s, a];
      },
      flushTransformCache: function flushTransformCache(e) {
        function t(t) {
          return parseFloat(S.getPropertyValue(e, t));
        }
        var r = "";
        if ((d || b.State.isAndroid && !b.State.isChrome) && i(e).isSVG) {
          var a = {
            translate: [t("translateX"), t("translateY")],
            skewX: [t("skewX")],
            skewY: [t("skewY")],
            scale: 1 !== t("scale") ? [t("scale"), t("scale")] : [t("scaleX"), t("scaleY")],
            rotate: [t("rotateZ"), 0, 0]
          };
          f.each(i(e).transformCache, function (e) {
            /^translate/i.test(e) ? e = "translate" : /^scale/i.test(e) ? e = "scale" : /^rotate/i.test(e) && (e = "rotate"), a[e] && (r += e + "(" + a[e].join(" ") + ") ", delete a[e]);
          });
        } else {
          var n, o;
          f.each(i(e).transformCache, function (t) {
            return n = i(e).transformCache[t], "transformPerspective" === t ? (o = n, !0) : (9 === d && "rotateZ" === t && (t = "rotate"), void (r += t + n + " "));
          }), o && (r = "perspective" + o + " " + r);
        }
        S.setPropertyValue(e, "transform", r);
      }
    };
    S.Hooks.register(), S.Normalizations.register(), b.hook = function (e, t, r) {
      var n = a;
      return e = o(e), f.each(e, function (e, o) {
        if (i(o) === a && b.init(o), r === a) n === a && (n = b.CSS.getPropertyValue(o, t));else {
          var s = b.CSS.setPropertyValue(o, t, r);
          "transform" === s[0] && b.CSS.flushTransformCache(o), n = s;
        }
      }), n;
    };
    var P = function P() {
      function e() {
        return s ? k.promise || null : l;
      }
      function n() {
        function e(e) {
          function p(e, t) {
            var r = a,
              n = a,
              i = a;
            return m.isArray(e) ? (r = e[0], !m.isArray(e[1]) && /^[\d-]/.test(e[1]) || m.isFunction(e[1]) || S.RegEx.isHex.test(e[1]) ? i = e[1] : (m.isString(e[1]) && !S.RegEx.isHex.test(e[1]) || m.isArray(e[1])) && (n = t ? e[1] : u(e[1], s.duration), e[2] !== a && (i = e[2]))) : r = e, t || (n = n || s.easing), m.isFunction(r) && (r = r.call(o, V, w)), m.isFunction(i) && (i = i.call(o, V, w)), [r || 0, n, i];
          }
          function d(e, t) {
            var r, a;
            return a = (t || "0").toString().toLowerCase().replace(/[%A-z]+$/, function (e) {
              return r = e, "";
            }), r || (r = S.Values.getUnitType(e)), [a, r];
          }
          function h() {
            var e = {
                myParent: o.parentNode || r.body,
                position: S.getPropertyValue(o, "position"),
                fontSize: S.getPropertyValue(o, "fontSize")
              },
              a = e.position === L.lastPosition && e.myParent === L.lastParent,
              n = e.fontSize === L.lastFontSize;
            L.lastParent = e.myParent, L.lastPosition = e.position, L.lastFontSize = e.fontSize;
            var s = 100,
              l = {};
            if (n && a) l.emToPx = L.lastEmToPx, l.percentToPxWidth = L.lastPercentToPxWidth, l.percentToPxHeight = L.lastPercentToPxHeight;else {
              var u = i(o).isSVG ? r.createElementNS("http://www.w3.org/2000/svg", "rect") : r.createElement("div");
              b.init(u), e.myParent.appendChild(u), f.each(["overflow", "overflowX", "overflowY"], function (e, t) {
                b.CSS.setPropertyValue(u, t, "hidden");
              }), b.CSS.setPropertyValue(u, "position", e.position), b.CSS.setPropertyValue(u, "fontSize", e.fontSize), b.CSS.setPropertyValue(u, "boxSizing", "content-box"), f.each(["minWidth", "maxWidth", "width", "minHeight", "maxHeight", "height"], function (e, t) {
                b.CSS.setPropertyValue(u, t, s + "%");
              }), b.CSS.setPropertyValue(u, "paddingLeft", s + "em"), l.percentToPxWidth = L.lastPercentToPxWidth = (parseFloat(S.getPropertyValue(u, "width", null, !0)) || 1) / s, l.percentToPxHeight = L.lastPercentToPxHeight = (parseFloat(S.getPropertyValue(u, "height", null, !0)) || 1) / s, l.emToPx = L.lastEmToPx = (parseFloat(S.getPropertyValue(u, "paddingLeft")) || 1) / s, e.myParent.removeChild(u);
            }
            return null === L.remToPx && (L.remToPx = parseFloat(S.getPropertyValue(r.body, "fontSize")) || 16), null === L.vwToPx && (L.vwToPx = parseFloat(t.innerWidth) / 100, L.vhToPx = parseFloat(t.innerHeight) / 100), l.remToPx = L.remToPx, l.vwToPx = L.vwToPx, l.vhToPx = L.vhToPx, b.debug >= 1 && console.log("Unit ratios: " + JSON.stringify(l), o), l;
          }
          if (s.begin && 0 === V) try {
            s.begin.call(g, g);
          } catch (x) {
            setTimeout(function () {
              throw x;
            }, 1);
          }
          if ("scroll" === A) {
            var P,
              C,
              T,
              F = /^x$/i.test(s.axis) ? "Left" : "Top",
              j = parseFloat(s.offset) || 0;
            s.container ? m.isWrapped(s.container) || m.isNode(s.container) ? (s.container = s.container[0] || s.container, P = s.container["scroll" + F], T = P + f(o).position()[F.toLowerCase()] + j) : s.container = null : (P = b.State.scrollAnchor[b.State["scrollProperty" + F]], C = b.State.scrollAnchor[b.State["scrollProperty" + ("Left" === F ? "Top" : "Left")]], T = f(o).offset()[F.toLowerCase()] + j), l = {
              scroll: {
                rootPropertyValue: !1,
                startValue: P,
                currentValue: P,
                endValue: T,
                unitType: "",
                easing: s.easing,
                scrollData: {
                  container: s.container,
                  direction: F,
                  alternateValue: C
                }
              },
              element: o
            }, b.debug && console.log("tweensContainer (scroll): ", l.scroll, o);
          } else if ("reverse" === A) {
            if (!i(o).tweensContainer) return void f.dequeue(o, s.queue);
            "none" === i(o).opts.display && (i(o).opts.display = "auto"), "hidden" === i(o).opts.visibility && (i(o).opts.visibility = "visible"), i(o).opts.loop = !1, i(o).opts.begin = null, i(o).opts.complete = null, v.easing || delete s.easing, v.duration || delete s.duration, s = f.extend({}, i(o).opts, s);
            var E = f.extend(!0, {}, i(o).tweensContainer);
            for (var H in E) if ("element" !== H) {
              var N = E[H].startValue;
              E[H].startValue = E[H].currentValue = E[H].endValue, E[H].endValue = N, m.isEmptyObject(v) || (E[H].easing = s.easing), b.debug && console.log("reverse tweensContainer (" + H + "): " + JSON.stringify(E[H]), o);
            }
            l = E;
          } else if ("start" === A) {
            var E;
            i(o).tweensContainer && i(o).isAnimating === !0 && (E = i(o).tweensContainer), f.each(y, function (e, t) {
              if (RegExp("^" + S.Lists.colors.join("$|^") + "$").test(e)) {
                var r = p(t, !0),
                  n = r[0],
                  o = r[1],
                  i = r[2];
                if (S.RegEx.isHex.test(n)) {
                  for (var s = ["Red", "Green", "Blue"], l = S.Values.hexToRgb(n), u = i ? S.Values.hexToRgb(i) : a, c = 0; c < s.length; c++) {
                    var f = [l[c]];
                    o && f.push(o), u !== a && f.push(u[c]), y[e + s[c]] = f;
                  }
                  delete y[e];
                }
              }
            });
            for (var z in y) {
              var O = p(y[z]),
                q = O[0],
                $ = O[1],
                M = O[2];
              z = S.Names.fortunatalCase(z);
              var I = S.Hooks.getRoot(z),
                B = !1;
              if (i(o).isSVG || "tween" === I || S.Names.prefixCheck(I)[1] !== !1 || S.Normalizations.registered[I] !== a) {
                (s.display !== a && null !== s.display && "none" !== s.display || s.visibility !== a && "hidden" !== s.visibility) && /opacity|filter/.test(z) && !M && 0 !== q && (M = 0), s._cacheValues && E && E[z] ? (M === a && (M = E[z].endValue + E[z].unitType), B = i(o).rootPropertyValueCache[I]) : S.Hooks.registered[z] ? M === a ? (B = S.getPropertyValue(o, I), M = S.getPropertyValue(o, z, B)) : B = S.Hooks.templates[I][1] : M === a && (M = S.getPropertyValue(o, z));
                var W,
                  G,
                  Y,
                  D = !1;
                if (W = d(z, M), M = W[0], Y = W[1], W = d(z, q), q = W[0].replace(/^([+-\/*])=/, function (e, t) {
                  return D = t, "";
                }), G = W[1], M = parseFloat(M) || 0, q = parseFloat(q) || 0, "%" === G && (/^(fontSize|lineHeight)$/.test(z) ? (q /= 100, G = "em") : /^scale/.test(z) ? (q /= 100, G = "") : /(Red|Green|Blue)$/i.test(z) && (q = q / 100 * 255, G = "")), /[\/*]/.test(D)) G = Y;else if (Y !== G && 0 !== M) if (0 === q) G = Y;else {
                  n = n || h();
                  var Q = /margin|padding|left|right|width|text|word|letter/i.test(z) || /X$/.test(z) || "x" === z ? "x" : "y";
                  switch (Y) {
                    case "%":
                      M *= "x" === Q ? n.percentToPxWidth : n.percentToPxHeight;
                      break;
                    case "px":
                      break;
                    default:
                      M *= n[Y + "ToPx"];
                  }
                  switch (G) {
                    case "%":
                      M *= 1 / ("x" === Q ? n.percentToPxWidth : n.percentToPxHeight);
                      break;
                    case "px":
                      break;
                    default:
                      M *= 1 / n[G + "ToPx"];
                  }
                }
                switch (D) {
                  case "+":
                    q = M + q;
                    break;
                  case "-":
                    q = M - q;
                    break;
                  case "*":
                    q = M * q;
                    break;
                  case "/":
                    q = M / q;
                }
                l[z] = {
                  rootPropertyValue: B,
                  startValue: M,
                  currentValue: M,
                  endValue: q,
                  unitType: G,
                  easing: $
                }, b.debug && console.log("tweensContainer (" + z + "): " + JSON.stringify(l[z]), o);
              } else b.debug && console.log("Skipping [" + I + "] due to a lack of browser support.");
            }
            l.element = o;
          }
          l.element && (S.Values.addClass(o, "velocity-animating"), R.push(l), "" === s.queue && (i(o).tweensContainer = l, i(o).opts = s), i(o).isAnimating = !0, V === w - 1 ? (b.State.calls.push([R, g, s, null, k.resolver]), b.State.isTicking === !1 && (b.State.isTicking = !0, c())) : V++);
        }
        var n,
          o = this,
          s = f.extend({}, b.defaults, v),
          l = {};
        switch (i(o) === a && b.init(o), parseFloat(s.delay) && s.queue !== !1 && f.queue(o, s.queue, function (e) {
          b.velocityQueueEntryFlag = !0, i(o).delayTimer = {
            setTimeout: setTimeout(e, parseFloat(s.delay)),
            next: e
          };
        }), s.duration.toString().toLowerCase()) {
          case "fast":
            s.duration = 200;
            break;
          case "normal":
            s.duration = h;
            break;
          case "slow":
            s.duration = 600;
            break;
          default:
            s.duration = parseFloat(s.duration) || 1;
        }
        b.mock !== !1 && (b.mock === !0 ? s.duration = s.delay = 1 : (s.duration *= parseFloat(b.mock) || 1, s.delay *= parseFloat(b.mock) || 1)), s.easing = u(s.easing, s.duration), s.begin && !m.isFunction(s.begin) && (s.begin = null), s.progress && !m.isFunction(s.progress) && (s.progress = null), s.complete && !m.isFunction(s.complete) && (s.complete = null), s.display !== a && null !== s.display && (s.display = s.display.toString().toLowerCase(), "auto" === s.display && (s.display = b.CSS.Values.getDisplayType(o))), s.visibility !== a && null !== s.visibility && (s.visibility = s.visibility.toString().toLowerCase()), s.mobileHA = s.mobileHA && b.State.isMobile && !b.State.isGingerbread, s.queue === !1 ? s.delay ? setTimeout(e, s.delay) : e() : f.queue(o, s.queue, function (t, r) {
          return r === !0 ? (k.promise && k.resolver(g), !0) : (b.velocityQueueEntryFlag = !0, void e(t));
        }), "" !== s.queue && "fx" !== s.queue || "inprogress" === f.queue(o)[0] || f.dequeue(o);
      }
      var s,
        l,
        d,
        g,
        y,
        v,
        x = arguments[0] && (arguments[0].p || f.isPlainObject(arguments[0].properties) && !arguments[0].properties.names || m.isString(arguments[0].properties));
      if (m.isWrapped(this) ? (s = !1, d = 0, g = this, l = this) : (s = !0, d = 1, g = x ? arguments[0].elements || arguments[0].e : arguments[0]), g = o(g)) {
        x ? (y = arguments[0].properties || arguments[0].p, v = arguments[0].options || arguments[0].o) : (y = arguments[d], v = arguments[d + 1]);
        var w = g.length,
          V = 0;
        if (!/^(stop|finish)$/i.test(y) && !f.isPlainObject(v)) {
          var C = d + 1;
          v = {};
          for (var T = C; T < arguments.length; T++) m.isArray(arguments[T]) || !/^(fast|normal|slow)$/i.test(arguments[T]) && !/^\d/.test(arguments[T]) ? m.isString(arguments[T]) || m.isArray(arguments[T]) ? v.easing = arguments[T] : m.isFunction(arguments[T]) && (v.complete = arguments[T]) : v.duration = arguments[T];
        }
        var k = {
          promise: null,
          resolver: null,
          rejecter: null
        };
        s && b.Promise && (k.promise = new b.Promise(function (e, t) {
          k.resolver = e, k.rejecter = t;
        }));
        var A;
        switch (y) {
          case "scroll":
            A = "scroll";
            break;
          case "reverse":
            A = "reverse";
            break;
          case "finish":
          case "stop":
            f.each(g, function (e, t) {
              i(t) && i(t).delayTimer && (clearTimeout(i(t).delayTimer.setTimeout), i(t).delayTimer.next && i(t).delayTimer.next(), delete i(t).delayTimer);
            });
            var F = [];
            return f.each(b.State.calls, function (e, t) {
              t && f.each(t[1], function (r, n) {
                var o = v === a ? "" : v;
                return o === !0 || t[2].queue === o || v === a && t[2].queue === !1 ? void f.each(g, function (r, a) {
                  a === n && ((v === !0 || m.isString(v)) && (f.each(f.queue(a, m.isString(v) ? v : ""), function (e, t) {
                    m.isFunction(t) && t(null, !0);
                  }), f.queue(a, m.isString(v) ? v : "", [])), "stop" === y ? (i(a) && i(a).tweensContainer && o !== !1 && f.each(i(a).tweensContainer, function (e, t) {
                    t.endValue = t.currentValue;
                  }), F.push(e)) : "finish" === y && (t[2].duration = 1));
                }) : !0;
              });
            }), "stop" === y && (f.each(F, function (e, t) {
              p(t, !0);
            }), k.promise && k.resolver(g)), e();
          default:
            if (!f.isPlainObject(y) || m.isEmptyObject(y)) {
              if (m.isString(y) && b.Redirects[y]) {
                var j = f.extend({}, v),
                  E = j.duration,
                  H = j.delay || 0;
                return j.backwards === !0 && (g = f.extend(!0, [], g).reverse()), f.each(g, function (e, t) {
                  parseFloat(j.stagger) ? j.delay = H + parseFloat(j.stagger) * e : m.isFunction(j.stagger) && (j.delay = H + j.stagger.call(t, e, w)), j.drag && (j.duration = parseFloat(E) || (/^(callout|transition)/.test(y) ? 1e3 : h), j.duration = Math.max(j.duration * (j.backwards ? 1 - e / w : (e + 1) / w), .75 * j.duration, 200)), b.Redirects[y].call(t, t, j || {}, e, w, g, k.promise ? k : a);
                }), e();
              }
              var N = "Velocity: First argument (" + y + ") was not a property map, a known action, or a registered redirect. Aborting.";
              return k.promise ? k.rejecter(new Error(N)) : console.log(N), e();
            }
            A = "start";
        }
        var L = {
            lastParent: null,
            lastPosition: null,
            lastFontSize: null,
            lastPercentToPxWidth: null,
            lastPercentToPxHeight: null,
            lastEmToPx: null,
            remToPx: null,
            vwToPx: null,
            vhToPx: null
          },
          R = [];
        f.each(g, function (e, t) {
          m.isNode(t) && n.call(t);
        });
        var z,
          j = f.extend({}, b.defaults, v);
        if (j.loop = parseInt(j.loop), z = 2 * j.loop - 1, j.loop) for (var O = 0; z > O; O++) {
          var q = {
            delay: j.delay,
            progress: j.progress
          };
          O === z - 1 && (q.display = j.display, q.visibility = j.visibility, q.complete = j.complete), P(g, "reverse", q);
        }
        return e();
      }
    };
    b = f.extend(P, b), b.animate = P;
    var w = t.requestAnimationFrame || g;
    return b.State.isMobile || r.hidden === a || r.addEventListener("visibilitychange", function () {
      r.hidden ? (w = function w(e) {
        return setTimeout(function () {
          e(!0);
        }, 16);
      }, c()) : w = t.requestAnimationFrame || g;
    }), e.Velocity = b, e !== t && (e.fn.velocity = P, e.fn.velocity.defaults = b.defaults), f.each(["Down", "Up"], function (e, t) {
      b.Redirects["slide" + t] = function (e, r, n, o, i, s) {
        var l = f.extend({}, r),
          u = l.begin,
          c = l.complete,
          p = {
            height: "",
            marginTop: "",
            marginBottom: "",
            paddingTop: "",
            paddingBottom: ""
          },
          d = {};
        l.display === a && (l.display = "Down" === t ? "inline" === b.CSS.Values.getDisplayType(e) ? "inline-block" : "block" : "none"), l.begin = function () {
          u && u.call(i, i);
          for (var r in p) {
            d[r] = e.style[r];
            var a = b.CSS.getPropertyValue(e, r);
            p[r] = "Down" === t ? [a, 0] : [0, a];
          }
          d.overflow = e.style.overflow, e.style.overflow = "hidden";
        }, l.complete = function () {
          for (var t in d) e.style[t] = d[t];
          c && c.call(i, i), s && s.resolver(i);
        }, b(e, p, l);
      };
    }), f.each(["In", "Out"], function (e, t) {
      b.Redirects["fade" + t] = function (e, r, n, o, i, s) {
        var l = f.extend({}, r),
          u = {
            opacity: "In" === t ? 1 : 0
          },
          c = l.complete;
        l.complete = n !== o - 1 ? l.begin = null : function () {
          c && c.call(i, i), s && s.resolver(i);
        }, l.display === a && (l.display = "In" === t ? "auto" : "none"), b(this, u, l);
      };
    }), b;
  }(window.jQuery || window.Zepto || window, window, document);
}));
!function (a, b, c, d) {
  "use strict";

  function k(a, b, c) {
    return setTimeout(q(a, c), b);
  }
  function l(a, b, c) {
    return Array.isArray(a) ? (m(a, c[b], c), !0) : !1;
  }
  function m(a, b, c) {
    var e;
    if (a) if (a.forEach) a.forEach(b, c);else if (a.length !== d) for (e = 0; e < a.length;) b.call(c, a[e], e, a), e++;else for (e in a) a.hasOwnProperty(e) && b.call(c, a[e], e, a);
  }
  function n(a, b, c) {
    for (var e = Object.keys(b), f = 0; f < e.length;) (!c || c && a[e[f]] === d) && (a[e[f]] = b[e[f]]), f++;
    return a;
  }
  function o(a, b) {
    return n(a, b, !0);
  }
  function p(a, b, c) {
    var e,
      d = b.prototype;
    e = a.prototype = Object.create(d), e.constructor = a, e._super = d, c && n(e, c);
  }
  function q(a, b) {
    return function () {
      return a.apply(b, arguments);
    };
  }
  function r(a, b) {
    return _typeof(a) == g ? a.apply(b ? b[0] || d : d, b) : a;
  }
  function s(a, b) {
    return a === d ? b : a;
  }
  function t(a, b, c) {
    m(x(b), function (b) {
      a.addEventListener(b, c, !1);
    });
  }
  function u(a, b, c) {
    m(x(b), function (b) {
      a.removeEventListener(b, c, !1);
    });
  }
  function v(a, b) {
    for (; a;) {
      if (a == b) return !0;
      a = a.parentNode;
    }
    return !1;
  }
  function w(a, b) {
    return a.indexOf(b) > -1;
  }
  function x(a) {
    return a.trim().split(/\s+/g);
  }
  function y(a, b, c) {
    if (a.indexOf && !c) return a.indexOf(b);
    for (var d = 0; d < a.length;) {
      if (c && a[d][c] == b || !c && a[d] === b) return d;
      d++;
    }
    return -1;
  }
  function z(a) {
    return Array.prototype.slice.call(a, 0);
  }
  function A(a, b, c) {
    for (var d = [], e = [], f = 0; f < a.length;) {
      var g = b ? a[f][b] : a[f];
      y(e, g) < 0 && d.push(a[f]), e[f] = g, f++;
    }
    return c && (d = b ? d.sort(function (a, c) {
      return a[b] > c[b];
    }) : d.sort()), d;
  }
  function B(a, b) {
    for (var c, f, g = b[0].toUpperCase() + b.slice(1), h = 0; h < e.length;) {
      if (c = e[h], f = c ? c + g : b, f in a) return f;
      h++;
    }
    return d;
  }
  function D() {
    return C++;
  }
  function E(a) {
    var b = a.ownerDocument;
    return b.defaultView || b.parentWindow;
  }
  function ab(a, b) {
    var c = this;
    this.manager = a, this.callback = b, this.element = a.element, this.target = a.options.inputTarget, this.domHandler = function (b) {
      r(a.options.enable, [a]) && c.handler(b);
    }, this.init();
  }
  function bb(a) {
    var b,
      c = a.options.inputClass;
    return b = c ? c : H ? wb : I ? Eb : G ? Gb : rb, new b(a, cb);
  }
  function cb(a, b, c) {
    var d = c.pointers.length,
      e = c.changedPointers.length,
      f = b & O && 0 === d - e,
      g = b & (Q | R) && 0 === d - e;
    c.isFirst = !!f, c.isFinal = !!g, f && (a.session = {}), c.eventType = b, db(a, c), a.emit("hammer.input", c), a.recognize(c), a.session.prevInput = c;
  }
  function db(a, b) {
    var c = a.session,
      d = b.pointers,
      e = d.length;
    c.firstInput || (c.firstInput = gb(b)), e > 1 && !c.firstMultiple ? c.firstMultiple = gb(b) : 1 === e && (c.firstMultiple = !1);
    var f = c.firstInput,
      g = c.firstMultiple,
      h = g ? g.center : f.center,
      i = b.center = hb(d);
    b.timeStamp = j(), b.deltaTime = b.timeStamp - f.timeStamp, b.angle = lb(h, i), b.distance = kb(h, i), eb(c, b), b.offsetDirection = jb(b.deltaX, b.deltaY), b.scale = g ? nb(g.pointers, d) : 1, b.rotation = g ? mb(g.pointers, d) : 0, fb(c, b);
    var k = a.element;
    v(b.srcEvent.target, k) && (k = b.srcEvent.target), b.target = k;
  }
  function eb(a, b) {
    var c = b.center,
      d = a.offsetDelta || {},
      e = a.prevDelta || {},
      f = a.prevInput || {};
    (b.eventType === O || f.eventType === Q) && (e = a.prevDelta = {
      x: f.deltaX || 0,
      y: f.deltaY || 0
    }, d = a.offsetDelta = {
      x: c.x,
      y: c.y
    }), b.deltaX = e.x + (c.x - d.x), b.deltaY = e.y + (c.y - d.y);
  }
  function fb(a, b) {
    var f,
      g,
      h,
      j,
      c = a.lastInterval || b,
      e = b.timeStamp - c.timeStamp;
    if (b.eventType != R && (e > N || c.velocity === d)) {
      var k = c.deltaX - b.deltaX,
        l = c.deltaY - b.deltaY,
        m = ib(e, k, l);
      g = m.x, h = m.y, f = i(m.x) > i(m.y) ? m.x : m.y, j = jb(k, l), a.lastInterval = b;
    } else f = c.velocity, g = c.velocityX, h = c.velocityY, j = c.direction;
    b.velocity = f, b.velocityX = g, b.velocityY = h, b.direction = j;
  }
  function gb(a) {
    for (var b = [], c = 0; c < a.pointers.length;) b[c] = {
      clientX: h(a.pointers[c].clientX),
      clientY: h(a.pointers[c].clientY)
    }, c++;
    return {
      timeStamp: j(),
      pointers: b,
      center: hb(b),
      deltaX: a.deltaX,
      deltaY: a.deltaY
    };
  }
  function hb(a) {
    var b = a.length;
    if (1 === b) return {
      x: h(a[0].clientX),
      y: h(a[0].clientY)
    };
    for (var c = 0, d = 0, e = 0; b > e;) c += a[e].clientX, d += a[e].clientY, e++;
    return {
      x: h(c / b),
      y: h(d / b)
    };
  }
  function ib(a, b, c) {
    return {
      x: b / a || 0,
      y: c / a || 0
    };
  }
  function jb(a, b) {
    return a === b ? S : i(a) >= i(b) ? a > 0 ? T : U : b > 0 ? V : W;
  }
  function kb(a, b, c) {
    c || (c = $);
    var d = b[c[0]] - a[c[0]],
      e = b[c[1]] - a[c[1]];
    return Math.sqrt(d * d + e * e);
  }
  function lb(a, b, c) {
    c || (c = $);
    var d = b[c[0]] - a[c[0]],
      e = b[c[1]] - a[c[1]];
    return 180 * Math.atan2(e, d) / Math.PI;
  }
  function mb(a, b) {
    return lb(b[1], b[0], _) - lb(a[1], a[0], _);
  }
  function nb(a, b) {
    return kb(b[0], b[1], _) / kb(a[0], a[1], _);
  }
  function rb() {
    this.evEl = pb, this.evWin = qb, this.allow = !0, this.pressed = !1, ab.apply(this, arguments);
  }
  function wb() {
    this.evEl = ub, this.evWin = vb, ab.apply(this, arguments), this.store = this.manager.session.pointerEvents = [];
  }
  function Ab() {
    this.evTarget = yb, this.evWin = zb, this.started = !1, ab.apply(this, arguments);
  }
  function Bb(a, b) {
    var c = z(a.touches),
      d = z(a.changedTouches);
    return b & (Q | R) && (c = A(c.concat(d), "identifier", !0)), [c, d];
  }
  function Eb() {
    this.evTarget = Db, this.targetIds = {}, ab.apply(this, arguments);
  }
  function Fb(a, b) {
    var c = z(a.touches),
      d = this.targetIds;
    if (b & (O | P) && 1 === c.length) return d[c[0].identifier] = !0, [c, c];
    var e,
      f,
      g = z(a.changedTouches),
      h = [],
      i = this.target;
    if (f = c.filter(function (a) {
      return v(a.target, i);
    }), b === O) for (e = 0; e < f.length;) d[f[e].identifier] = !0, e++;
    for (e = 0; e < g.length;) d[g[e].identifier] && h.push(g[e]), b & (Q | R) && delete d[g[e].identifier], e++;
    return h.length ? [A(f.concat(h), "identifier", !0), h] : void 0;
  }
  function Gb() {
    ab.apply(this, arguments);
    var a = q(this.handler, this);
    this.touch = new Eb(this.manager, a), this.mouse = new rb(this.manager, a);
  }
  function Pb(a, b) {
    this.manager = a, this.set(b);
  }
  function Qb(a) {
    if (w(a, Mb)) return Mb;
    var b = w(a, Nb),
      c = w(a, Ob);
    return b && c ? Nb + " " + Ob : b || c ? b ? Nb : Ob : w(a, Lb) ? Lb : Kb;
  }
  function Yb(a) {
    this.id = D(), this.manager = null, this.options = o(a || {}, this.defaults), this.options.enable = s(this.options.enable, !0), this.state = Rb, this.simultaneous = {}, this.requireFail = [];
  }
  function Zb(a) {
    return a & Wb ? "cancel" : a & Ub ? "end" : a & Tb ? "move" : a & Sb ? "start" : "";
  }
  function $b(a) {
    return a == W ? "down" : a == V ? "up" : a == T ? "left" : a == U ? "right" : "";
  }
  function _b(a, b) {
    var c = b.manager;
    return c ? c.get(a) : a;
  }
  function ac() {
    Yb.apply(this, arguments);
  }
  function bc() {
    ac.apply(this, arguments), this.pX = null, this.pY = null;
  }
  function cc() {
    ac.apply(this, arguments);
  }
  function dc() {
    Yb.apply(this, arguments), this._timer = null, this._input = null;
  }
  function ec() {
    ac.apply(this, arguments);
  }
  function fc() {
    ac.apply(this, arguments);
  }
  function gc() {
    Yb.apply(this, arguments), this.pTime = !1, this.pCenter = !1, this._timer = null, this._input = null, this.count = 0;
  }
  function hc(a, b) {
    return b = b || {}, b.recognizers = s(b.recognizers, hc.defaults.preset), new kc(a, b);
  }
  function kc(a, b) {
    b = b || {}, this.options = o(b, hc.defaults), this.options.inputTarget = this.options.inputTarget || a, this.handlers = {}, this.session = {}, this.recognizers = [], this.element = a, this.input = bb(this), this.touchAction = new Pb(this, this.options.touchAction), lc(this, !0), m(b.recognizers, function (a) {
      var b = this.add(new a[0](a[1]));
      a[2] && b.recognizeWith(a[2]), a[3] && b.requireFailure(a[3]);
    }, this);
  }
  function lc(a, b) {
    var c = a.element;
    m(a.options.cssProps, function (a, d) {
      c.style[B(c.style, d)] = b ? a : "";
    });
  }
  function mc(a, c) {
    var d = b.createEvent("Event");
    d.initEvent(a, !0, !0), d.gesture = c, c.target.dispatchEvent(d);
  }
  var e = ["", "webkit", "moz", "MS", "ms", "o"],
    f = b.createElement("div"),
    g = "function",
    h = Math.round,
    i = Math.abs,
    j = Date.now,
    C = 1,
    F = /mobile|tablet|ip(ad|hone|od)|android/i,
    G = ("ontouchstart" in a),
    H = B(a, "PointerEvent") !== d,
    I = G && F.test(navigator.userAgent),
    J = "touch",
    K = "pen",
    L = "mouse",
    M = "kinect",
    N = 25,
    O = 1,
    P = 2,
    Q = 4,
    R = 8,
    S = 1,
    T = 2,
    U = 4,
    V = 8,
    W = 16,
    X = T | U,
    Y = V | W,
    Z = X | Y,
    $ = ["x", "y"],
    _ = ["clientX", "clientY"];
  ab.prototype = {
    handler: function handler() {},
    init: function init() {
      this.evEl && t(this.element, this.evEl, this.domHandler), this.evTarget && t(this.target, this.evTarget, this.domHandler), this.evWin && t(E(this.element), this.evWin, this.domHandler);
    },
    destroy: function destroy() {
      this.evEl && u(this.element, this.evEl, this.domHandler), this.evTarget && u(this.target, this.evTarget, this.domHandler), this.evWin && u(E(this.element), this.evWin, this.domHandler);
    }
  };
  var ob = {
      mousedown: O,
      mousemove: P,
      mouseup: Q
    },
    pb = "mousedown",
    qb = "mousemove mouseup";
  p(rb, ab, {
    handler: function handler(a) {
      var b = ob[a.type];
      b & O && 0 === a.button && (this.pressed = !0), b & P && 1 !== a.which && (b = Q), this.pressed && this.allow && (b & Q && (this.pressed = !1), this.callback(this.manager, b, {
        pointers: [a],
        changedPointers: [a],
        pointerType: L,
        srcEvent: a
      }));
    }
  });
  var sb = {
      pointerdown: O,
      pointermove: P,
      pointerup: Q,
      pointercancel: R,
      pointerout: R
    },
    tb = {
      2: J,
      3: K,
      4: L,
      5: M
    },
    ub = "pointerdown",
    vb = "pointermove pointerup pointercancel";
  a.MSPointerEvent && (ub = "MSPointerDown", vb = "MSPointerMove MSPointerUp MSPointerCancel"), p(wb, ab, {
    handler: function handler(a) {
      var b = this.store,
        c = !1,
        d = a.type.toLowerCase().replace("ms", ""),
        e = sb[d],
        f = tb[a.pointerType] || a.pointerType,
        g = f == J,
        h = y(b, a.pointerId, "pointerId");
      e & O && (0 === a.button || g) ? 0 > h && (b.push(a), h = b.length - 1) : e & (Q | R) && (c = !0), 0 > h || (b[h] = a, this.callback(this.manager, e, {
        pointers: b,
        changedPointers: [a],
        pointerType: f,
        srcEvent: a
      }), c && b.splice(h, 1));
    }
  });
  var xb = {
      touchstart: O,
      touchmove: P,
      touchend: Q,
      touchcancel: R
    },
    yb = "touchstart",
    zb = "touchstart touchmove touchend touchcancel";
  p(Ab, ab, {
    handler: function handler(a) {
      var b = xb[a.type];
      if (b === O && (this.started = !0), this.started) {
        var c = Bb.call(this, a, b);
        b & (Q | R) && 0 === c[0].length - c[1].length && (this.started = !1), this.callback(this.manager, b, {
          pointers: c[0],
          changedPointers: c[1],
          pointerType: J,
          srcEvent: a
        });
      }
    }
  });
  var Cb = {
      touchstart: O,
      touchmove: P,
      touchend: Q,
      touchcancel: R
    },
    Db = "touchstart touchmove touchend touchcancel";
  p(Eb, ab, {
    handler: function handler(a) {
      var b = Cb[a.type],
        c = Fb.call(this, a, b);
      c && this.callback(this.manager, b, {
        pointers: c[0],
        changedPointers: c[1],
        pointerType: J,
        srcEvent: a
      });
    }
  }), p(Gb, ab, {
    handler: function handler(a, b, c) {
      var d = c.pointerType == J,
        e = c.pointerType == L;
      if (d) this.mouse.allow = !1;else if (e && !this.mouse.allow) return;
      b & (Q | R) && (this.mouse.allow = !0), this.callback(a, b, c);
    },
    destroy: function destroy() {
      this.touch.destroy(), this.mouse.destroy();
    }
  });
  var Hb = B(f.style, "touchAction"),
    Ib = Hb !== d,
    Jb = "compute",
    Kb = "auto",
    Lb = "manipulation",
    Mb = "none",
    Nb = "pan-x",
    Ob = "pan-y";
  Pb.prototype = {
    set: function set(a) {
      a == Jb && (a = this.compute()), Ib && (this.manager.element.style[Hb] = a), this.actions = a.toLowerCase().trim();
    },
    update: function update() {
      this.set(this.manager.options.touchAction);
    },
    compute: function compute() {
      var a = [];
      return m(this.manager.recognizers, function (b) {
        r(b.options.enable, [b]) && (a = a.concat(b.getTouchAction()));
      }), Qb(a.join(" "));
    },
    preventDefaults: function preventDefaults(a) {
      if (!Ib) {
        var b = a.srcEvent,
          c = a.offsetDirection;
        if (this.manager.session.prevented) return b.preventDefault(), void 0;
        var d = this.actions,
          e = w(d, Mb),
          f = w(d, Ob),
          g = w(d, Nb);
        return e || f && c & X || g && c & Y ? this.preventSrc(b) : void 0;
      }
    },
    preventSrc: function preventSrc(a) {
      this.manager.session.prevented = !0, a.preventDefault();
    }
  };
  var Rb = 1,
    Sb = 2,
    Tb = 4,
    Ub = 8,
    Vb = Ub,
    Wb = 16,
    Xb = 32;
  Yb.prototype = {
    defaults: {},
    set: function set(a) {
      return n(this.options, a), this.manager && this.manager.touchAction.update(), this;
    },
    recognizeWith: function recognizeWith(a) {
      if (l(a, "recognizeWith", this)) return this;
      var b = this.simultaneous;
      return a = _b(a, this), b[a.id] || (b[a.id] = a, a.recognizeWith(this)), this;
    },
    dropRecognizeWith: function dropRecognizeWith(a) {
      return l(a, "dropRecognizeWith", this) ? this : (a = _b(a, this), delete this.simultaneous[a.id], this);
    },
    requireFailure: function requireFailure(a) {
      if (l(a, "requireFailure", this)) return this;
      var b = this.requireFail;
      return a = _b(a, this), -1 === y(b, a) && (b.push(a), a.requireFailure(this)), this;
    },
    dropRequireFailure: function dropRequireFailure(a) {
      if (l(a, "dropRequireFailure", this)) return this;
      a = _b(a, this);
      var b = y(this.requireFail, a);
      return b > -1 && this.requireFail.splice(b, 1), this;
    },
    hasRequireFailures: function hasRequireFailures() {
      return this.requireFail.length > 0;
    },
    canRecognizeWith: function canRecognizeWith(a) {
      return !!this.simultaneous[a.id];
    },
    emit: function emit(a) {
      function d(d) {
        b.manager.emit(b.options.event + (d ? Zb(c) : ""), a);
      }
      var b = this,
        c = this.state;
      Ub > c && d(!0), d(), c >= Ub && d(!0);
    },
    tryEmit: function tryEmit(a) {
      return this.canEmit() ? this.emit(a) : (this.state = Xb, void 0);
    },
    canEmit: function canEmit() {
      for (var a = 0; a < this.requireFail.length;) {
        if (!(this.requireFail[a].state & (Xb | Rb))) return !1;
        a++;
      }
      return !0;
    },
    recognize: function recognize(a) {
      var b = n({}, a);
      return r(this.options.enable, [this, b]) ? (this.state & (Vb | Wb | Xb) && (this.state = Rb), this.state = this.process(b), this.state & (Sb | Tb | Ub | Wb) && this.tryEmit(b), void 0) : (this.reset(), this.state = Xb, void 0);
    },
    process: function process() {},
    getTouchAction: function getTouchAction() {},
    reset: function reset() {}
  }, p(ac, Yb, {
    defaults: {
      pointers: 1
    },
    attrTest: function attrTest(a) {
      var b = this.options.pointers;
      return 0 === b || a.pointers.length === b;
    },
    process: function process(a) {
      var b = this.state,
        c = a.eventType,
        d = b & (Sb | Tb),
        e = this.attrTest(a);
      return d && (c & R || !e) ? b | Wb : d || e ? c & Q ? b | Ub : b & Sb ? b | Tb : Sb : Xb;
    }
  }), p(bc, ac, {
    defaults: {
      event: "pan",
      threshold: 10,
      pointers: 1,
      direction: Z
    },
    getTouchAction: function getTouchAction() {
      var a = this.options.direction,
        b = [];
      return a & X && b.push(Ob), a & Y && b.push(Nb), b;
    },
    directionTest: function directionTest(a) {
      var b = this.options,
        c = !0,
        d = a.distance,
        e = a.direction,
        f = a.deltaX,
        g = a.deltaY;
      return e & b.direction || (b.direction & X ? (e = 0 === f ? S : 0 > f ? T : U, c = f != this.pX, d = Math.abs(a.deltaX)) : (e = 0 === g ? S : 0 > g ? V : W, c = g != this.pY, d = Math.abs(a.deltaY))), a.direction = e, c && d > b.threshold && e & b.direction;
    },
    attrTest: function attrTest(a) {
      return ac.prototype.attrTest.call(this, a) && (this.state & Sb || !(this.state & Sb) && this.directionTest(a));
    },
    emit: function emit(a) {
      this.pX = a.deltaX, this.pY = a.deltaY;
      var b = $b(a.direction);
      b && this.manager.emit(this.options.event + b, a), this._super.emit.call(this, a);
    }
  }), p(cc, ac, {
    defaults: {
      event: "pinch",
      threshold: 0,
      pointers: 2
    },
    getTouchAction: function getTouchAction() {
      return [Mb];
    },
    attrTest: function attrTest(a) {
      return this._super.attrTest.call(this, a) && (Math.abs(a.scale - 1) > this.options.threshold || this.state & Sb);
    },
    emit: function emit(a) {
      if (this._super.emit.call(this, a), 1 !== a.scale) {
        var b = a.scale < 1 ? "in" : "out";
        this.manager.emit(this.options.event + b, a);
      }
    }
  }), p(dc, Yb, {
    defaults: {
      event: "press",
      pointers: 1,
      time: 500,
      threshold: 5
    },
    getTouchAction: function getTouchAction() {
      return [Kb];
    },
    process: function process(a) {
      var b = this.options,
        c = a.pointers.length === b.pointers,
        d = a.distance < b.threshold,
        e = a.deltaTime > b.time;
      if (this._input = a, !d || !c || a.eventType & (Q | R) && !e) this.reset();else if (a.eventType & O) this.reset(), this._timer = k(function () {
        this.state = Vb, this.tryEmit();
      }, b.time, this);else if (a.eventType & Q) return Vb;
      return Xb;
    },
    reset: function reset() {
      clearTimeout(this._timer);
    },
    emit: function emit(a) {
      this.state === Vb && (a && a.eventType & Q ? this.manager.emit(this.options.event + "up", a) : (this._input.timeStamp = j(), this.manager.emit(this.options.event, this._input)));
    }
  }), p(ec, ac, {
    defaults: {
      event: "rotate",
      threshold: 0,
      pointers: 2
    },
    getTouchAction: function getTouchAction() {
      return [Mb];
    },
    attrTest: function attrTest(a) {
      return this._super.attrTest.call(this, a) && (Math.abs(a.rotation) > this.options.threshold || this.state & Sb);
    }
  }), p(fc, ac, {
    defaults: {
      event: "swipe",
      threshold: 10,
      velocity: .65,
      direction: X | Y,
      pointers: 1
    },
    getTouchAction: function getTouchAction() {
      return bc.prototype.getTouchAction.call(this);
    },
    attrTest: function attrTest(a) {
      var c,
        b = this.options.direction;
      return b & (X | Y) ? c = a.velocity : b & X ? c = a.velocityX : b & Y && (c = a.velocityY), this._super.attrTest.call(this, a) && b & a.direction && a.distance > this.options.threshold && i(c) > this.options.velocity && a.eventType & Q;
    },
    emit: function emit(a) {
      var b = $b(a.direction);
      b && this.manager.emit(this.options.event + b, a), this.manager.emit(this.options.event, a);
    }
  }), p(gc, Yb, {
    defaults: {
      event: "tap",
      pointers: 1,
      taps: 1,
      interval: 300,
      time: 250,
      threshold: 2,
      posThreshold: 10
    },
    getTouchAction: function getTouchAction() {
      return [Lb];
    },
    process: function process(a) {
      var b = this.options,
        c = a.pointers.length === b.pointers,
        d = a.distance < b.threshold,
        e = a.deltaTime < b.time;
      if (this.reset(), a.eventType & O && 0 === this.count) return this.failTimeout();
      if (d && e && c) {
        if (a.eventType != Q) return this.failTimeout();
        var f = this.pTime ? a.timeStamp - this.pTime < b.interval : !0,
          g = !this.pCenter || kb(this.pCenter, a.center) < b.posThreshold;
        this.pTime = a.timeStamp, this.pCenter = a.center, g && f ? this.count += 1 : this.count = 1, this._input = a;
        var h = this.count % b.taps;
        if (0 === h) return this.hasRequireFailures() ? (this._timer = k(function () {
          this.state = Vb, this.tryEmit();
        }, b.interval, this), Sb) : Vb;
      }
      return Xb;
    },
    failTimeout: function failTimeout() {
      return this._timer = k(function () {
        this.state = Xb;
      }, this.options.interval, this), Xb;
    },
    reset: function reset() {
      clearTimeout(this._timer);
    },
    emit: function emit() {
      this.state == Vb && (this._input.tapCount = this.count, this.manager.emit(this.options.event, this._input));
    }
  }), hc.VERSION = "2.0.4", hc.defaults = {
    domEvents: !1,
    touchAction: Jb,
    enable: !0,
    inputTarget: null,
    inputClass: null,
    preset: [[ec, {
      enable: !1
    }], [cc, {
      enable: !1
    }, ["rotate"]], [fc, {
      direction: X
    }], [bc, {
      direction: X
    }, ["swipe"]], [gc], [gc, {
      event: "doubletap",
      taps: 2
    }, ["tap"]], [dc]],
    cssProps: {
      userSelect: "default",
      touchSelect: "none",
      touchCallout: "none",
      contentZooming: "none",
      userDrag: "none",
      tapHighlightColor: "rgba(0,0,0,0)"
    }
  };
  var ic = 1,
    jc = 2;
  kc.prototype = {
    set: function set(a) {
      return n(this.options, a), a.touchAction && this.touchAction.update(), a.inputTarget && (this.input.destroy(), this.input.target = a.inputTarget, this.input.init()), this;
    },
    stop: function stop(a) {
      this.session.stopped = a ? jc : ic;
    },
    recognize: function recognize(a) {
      var b = this.session;
      if (!b.stopped) {
        this.touchAction.preventDefaults(a);
        var c,
          d = this.recognizers,
          e = b.curRecognizer;
        (!e || e && e.state & Vb) && (e = b.curRecognizer = null);
        for (var f = 0; f < d.length;) c = d[f], b.stopped === jc || e && c != e && !c.canRecognizeWith(e) ? c.reset() : c.recognize(a), !e && c.state & (Sb | Tb | Ub) && (e = b.curRecognizer = c), f++;
      }
    },
    get: function get(a) {
      if (a instanceof Yb) return a;
      for (var b = this.recognizers, c = 0; c < b.length; c++) if (b[c].options.event == a) return b[c];
      return null;
    },
    add: function add(a) {
      if (l(a, "add", this)) return this;
      var b = this.get(a.options.event);
      return b && this.remove(b), this.recognizers.push(a), a.manager = this, this.touchAction.update(), a;
    },
    remove: function remove(a) {
      if (l(a, "remove", this)) return this;
      var b = this.recognizers;
      return a = this.get(a), b.splice(y(b, a), 1), this.touchAction.update(), this;
    },
    on: function on(a, b) {
      var c = this.handlers;
      return m(x(a), function (a) {
        c[a] = c[a] || [], c[a].push(b);
      }), this;
    },
    off: function off(a, b) {
      var c = this.handlers;
      return m(x(a), function (a) {
        b ? c[a].splice(y(c[a], b), 1) : delete c[a];
      }), this;
    },
    emit: function emit(a, b) {
      this.options.domEvents && mc(a, b);
      var c = this.handlers[a] && this.handlers[a].slice();
      if (c && c.length) {
        b.type = a, b.preventDefault = function () {
          b.srcEvent.preventDefault();
        };
        for (var d = 0; d < c.length;) c[d](b), d++;
      }
    },
    destroy: function destroy() {
      this.element && lc(this, !1), this.handlers = {}, this.session = {}, this.input.destroy(), this.element = null;
    }
  }, n(hc, {
    INPUT_START: O,
    INPUT_MOVE: P,
    INPUT_END: Q,
    INPUT_CANCEL: R,
    STATE_POSSIBLE: Rb,
    STATE_BEGAN: Sb,
    STATE_CHANGED: Tb,
    STATE_ENDED: Ub,
    STATE_RECOGNIZED: Vb,
    STATE_CANCELLED: Wb,
    STATE_FAILED: Xb,
    DIRECTION_NONE: S,
    DIRECTION_LEFT: T,
    DIRECTION_RIGHT: U,
    DIRECTION_UP: V,
    DIRECTION_DOWN: W,
    DIRECTION_HORIZONTAL: X,
    DIRECTION_VERTICAL: Y,
    DIRECTION_ALL: Z,
    Manager: kc,
    Input: ab,
    TouchAction: Pb,
    TouchInput: Eb,
    MouseInput: rb,
    PointerEventInput: wb,
    TouchMouseInput: Gb,
    SingleTouchInput: Ab,
    Recognizer: Yb,
    AttrRecognizer: ac,
    Tap: gc,
    Pan: bc,
    Swipe: fc,
    Pinch: cc,
    Rotate: ec,
    Press: dc,
    on: t,
    off: u,
    each: m,
    merge: o,
    extend: n,
    inherit: p,
    bindFn: q,
    prefixed: B
  }), (typeof define === "undefined" ? "undefined" : _typeof(define)) == g && define.amd ? define(function () {
    return hc;
  }) : "undefined" != typeof module && module.exports ? module.exports = hc : a[c] = hc;
}(window, document, "Hammer");
(function (factory) {
  if (typeof define === 'function' && define.amd) {
    define(['jquery', 'hammerjs'], factory);
  } else if ((typeof exports === "undefined" ? "undefined" : _typeof(exports)) === 'object') {
    factory(require('jquery'), require('hammerjs'));
  } else {
    factory(jQuery, Hammer);
  }
})(function ($, Hammer) {
  function hammerify(el, options) {
    var $el = $(el);
    if (!$el.data("hammer")) {
      $el.data("hammer", new Hammer($el[0], options));
    }
  }
  $.fn.hammer = function (options) {
    return this.each(function () {
      hammerify(this, options);
    });
  };

  // extend the emit method to also trigger jQuery events
  Hammer.Manager.prototype.emit = function (originalEmit) {
    return function (type, data) {
      originalEmit.call(this, type, data);
      $(this.element).trigger({
        type: type,
        gesture: data
      });
    };
  }(Hammer.Manager.prototype.emit);
});

// Required for Meteor package, the use of window prevents export by Meteor
(function (window) {
  if (window.Package) {
    Materialize = {};
  } else {
    window.Materialize = {};
  }
})(window);
if (typeof exports !== 'undefined' && !exports.nodeType) {
  if (typeof module !== 'undefined' && !module.nodeType && module.exports) {
    exports = module.exports = Materialize;
  }
  exports["default"] = Materialize;
}

/*
 * raf.js
 * https://github.com/ngryman/raf.js
 *
 * original requestAnimationFrame polyfill by Erik Möller
 * inspired from paul_irish gist and post
 *
 * Copyright (c) 2013 ngryman
 * Licensed under the MIT license.
 */
(function (window) {
  var lastTime = 0,
    vendors = ['webkit', 'moz'],
    requestAnimationFrame = window.requestAnimationFrame,
    cancelAnimationFrame = window.cancelAnimationFrame,
    i = vendors.length;

  // try to un-prefix existing raf
  while (--i >= 0 && !requestAnimationFrame) {
    requestAnimationFrame = window[vendors[i] + 'RequestAnimationFrame'];
    cancelAnimationFrame = window[vendors[i] + 'CancelRequestAnimationFrame'];
  }

  // polyfill with setTimeout fallback
  // heavily inspired from @darius gist mod: https://gist.github.com/paulirish/1579671#comment-837945
  if (!requestAnimationFrame || !cancelAnimationFrame) {
    requestAnimationFrame = function requestAnimationFrame(callback) {
      var now = +Date.now(),
        nextTime = Math.max(lastTime + 16, now);
      return setTimeout(function () {
        callback(lastTime = nextTime);
      }, nextTime - now);
    };
    cancelAnimationFrame = clearTimeout;
  }

  // export to window
  window.requestAnimationFrame = requestAnimationFrame;
  window.cancelAnimationFrame = cancelAnimationFrame;
})(window);

/**
 * Generate approximated selector string for a jQuery object
 * @param {jQuery} obj  jQuery object to be parsed
 * @returns {string}
 */
Materialize.objectSelectorString = function (obj) {
  var tagStr = obj.prop('tagName') || '';
  var idStr = obj.attr('id') || '';
  var classStr = obj.attr('class') || '';
  return (tagStr + idStr + classStr).replace(/\s/g, '');
};

// Unique Random ID
Materialize.guid = function () {
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
Materialize.escapeHash = function (hash) {
  return hash.replace(/(:|\.|\[|\]|,|=)/g, "\\$1");
};
Materialize.elementOrParentIsFixed = function (element) {
  var $element = $(element);
  var $checkElements = $element.add($element.parents());
  var isFixed = false;
  $checkElements.each(function () {
    if ($(this).css("position") === "fixed") {
      isFixed = true;
      return false;
    }
  });
  return isFixed;
};

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
Materialize.throttle = function (func, wait, options) {
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

// Velocity has conflicts when loaded with jQuery, this will check for it
// First, check if in noConflict mode
var Vel;
if (jQuery) {
  Vel = jQuery.Velocity;
} else if ($) {
  Vel = $.Velocity;
} else {
  Vel = Velocity;
}
if (Vel) {
  Materialize.Vel = Vel;
} else {
  Materialize.Vel = Velocity;
}
(function ($, Vel) {
  'use strict';

  var _defaults = {
    opacity: 0.5,
    inDuration: 250,
    outDuration: 250,
    ready: undefined,
    complete: undefined,
    dismissible: true,
    startingTop: '4%',
    endingTop: '10%'
  };

  /**
   * @class
   *
   */
  var Modal = /*#__PURE__*/function () {
    /**
     * Construct Modal instance and set up overlay
     * @constructor
     * @param {jQuery} $el
     * @param {Object} options
     */
    function Modal($el, options) {
      _classCallCheck(this, Modal);
      // If exists, destroy and reinitialize
      if (!!$el[0].M_Modal) {
        $el[0].M_Modal.destroy();
      }

      /**
       * The jQuery element
       * @type {jQuery}
       */
      this.$el = $el;

      /**
       * Options for the modal
       * @member Modal#options
       * @prop {Number} [opacity=0.5] - Opacity of the modal overlay
       * @prop {Number} [inDuration=250] - Length in ms of enter transition
       * @prop {Number} [outDuration=250] - Length in ms of exit transition
       * @prop {Function} ready - Callback function called when modal is finished entering
       * @prop {Function} complete - Callback function called when modal is finished exiting
       * @prop {Boolean} [dismissible=true] - Allow modal to be dismissed by keyboard or overlay click
       * @prop {String} [startingTop='4%'] - startingTop
       * @prop {String} [endingTop='10%'] - endingTop
       */
      this.options = $.extend({}, Modal.defaults, options);

      /**
       * Describes open/close state of modal
       * @type {Boolean}
       */
      this.isOpen = false;
      this.$el[0].M_Modal = this;
      this.id = $el.attr('id');
      this.openingTrigger = undefined;
      this.$overlay = $('<div class="modal-overlay"></div>');
      Modal._increment++;
      Modal._count++;
      this.$overlay[0].style.zIndex = 1000 + Modal._increment * 2;
      this.$el[0].style.zIndex = 1000 + Modal._increment * 2 + 1;
      this.setupEventHandlers();
    }
    _createClass(Modal, [{
      key: "getInstance",
      value:
      /**
       * Get Instance
       */
      function getInstance() {
        return this;
      }

      /**
       * Teardown component
       */
    }, {
      key: "destroy",
      value: function destroy() {
        this.removeEventHandlers();
        this.$el[0].removeAttribute('style');
        if (!!this.$overlay[0].parentNode) {
          this.$overlay[0].parentNode.removeChild(this.$overlay[0]);
        }
        this.$el[0].M_Modal = undefined;
        Modal._count--;
      }

      /**
       * Setup Event Handlers
       */
    }, {
      key: "setupEventHandlers",
      value: function setupEventHandlers() {
        this.handleOverlayClickBound = this.handleOverlayClick.bind(this);
        this.handleModalCloseClickBound = this.handleModalCloseClick.bind(this);
        if (Modal._count === 1) {
          document.body.addEventListener('click', this.handleTriggerClick);
        }
        this.$overlay[0].addEventListener('click', this.handleOverlayClickBound);
        this.$el[0].addEventListener('click', this.handleModalCloseClickBound);
      }

      /**
       * Remove Event Handlers
       */
    }, {
      key: "removeEventHandlers",
      value: function removeEventHandlers() {
        if (Modal._count === 0) {
          document.body.removeEventListener('click', this.handleTriggerClick);
        }
        this.$overlay[0].removeEventListener('click', this.handleOverlayClickBound);
        this.$el[0].removeEventListener('click', this.handleModalCloseClickBound);
      }

      /**
       * Handle Trigger Click
       * @param {Event} e
       */
    }, {
      key: "handleTriggerClick",
      value: function handleTriggerClick(e) {
        var $trigger = $(e.target).closest('.modal-trigger');
        if (e.target && $trigger.length) {
          var modalId = $trigger[0].getAttribute('href');
          if (modalId) {
            modalId = modalId.slice(1);
          } else {
            modalId = $trigger[0].getAttribute('data-target');
          }
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
      key: "handleOverlayClick",
      value: function handleOverlayClick() {
        if (this.options.dismissible) {
          this.close();
        }
      }

      /**
       * Handle Modal Close Click
       * @param {Event} e
       */
    }, {
      key: "handleModalCloseClick",
      value: function handleModalCloseClick(e) {
        var $closeTrigger = $(e.target).closest('.modal-close');
        if (e.target && $closeTrigger.length) {
          this.close();
        }
      }

      /**
       * Handle Keydown
       * @param {Event} e
       */
    }, {
      key: "handleKeydown",
      value: function handleKeydown(e) {
        // ESC key
        if (e.keyCode === 27 && this.options.dismissible) {
          this.close();
        }
      }

      /**
       * Animate in modal
       */
    }, {
      key: "animateIn",
      value: function animateIn() {
        var _this = this;
        // Set initial styles
        $.extend(this.$el[0].style, {
          display: 'block',
          opacity: 0
        });
        $.extend(this.$overlay[0].style, {
          display: 'block',
          opacity: 0
        });

        // Animate overlay
        Vel(this.$overlay[0], {
          opacity: this.options.opacity
        }, {
          duration: this.options.inDuration,
          queue: false,
          ease: 'easeOutCubic'
        });

        // Define modal animation options
        var enterVelocityOptions = {
          duration: this.options.inDuration,
          queue: false,
          ease: 'easeOutCubic',
          // Handle modal ready callback
          complete: function complete() {
            if (typeof _this.options.ready === 'function') {
              _this.options.ready.call(_this, _this.$el, _this.openingTrigger);
            }
          }
        };

        // Bottom sheet animation
        if (this.$el[0].classList.contains('bottom-sheet')) {
          Vel(this.$el[0], {
            bottom: 0,
            opacity: 1
          }, enterVelocityOptions);

          // Normal modal animation
        } else {
          Vel.hook(this.$el[0], 'scaleX', 0.7);
          this.$el[0].style.top = this.options.startingTop;
          Vel(this.$el[0], {
            top: this.options.endingTop,
            opacity: 1,
            scaleX: 1
          }, enterVelocityOptions);
        }
      }

      /**
       * Animate out modal
       */
    }, {
      key: "animateOut",
      value: function animateOut() {
        var _this2 = this;
        // Animate overlay
        Vel(this.$overlay[0], {
          opacity: 0
        }, {
          duration: this.options.outDuration,
          queue: false,
          ease: 'easeOutQuart'
        });

        // Define modal animation options
        var exitVelocityOptions = {
          duration: this.options.outDuration,
          queue: false,
          ease: 'easeOutCubic',
          // Handle modal ready callback
          complete: function complete() {
            _this2.$el[0].style.display = 'none';
            // Call complete callback
            if (typeof _this2.options.complete === 'function') {
              _this2.options.complete.call(_this2, _this2.$el);
            }
            _this2.$overlay[0].parentNode.removeChild(_this2.$overlay[0]);
          }
        };

        // Bottom sheet animation
        if (this.$el[0].classList.contains('bottom-sheet')) {
          Vel(this.$el[0], {
            bottom: '-100%',
            opacity: 0
          }, exitVelocityOptions);

          // Normal modal animation
        } else {
          Vel(this.$el[0], {
            top: this.options.startingTop,
            opacity: 0,
            scaleX: 0.7
          }, exitVelocityOptions);
        }
      }

      /**
       * Open Modal
       * @param {jQuery} [$trigger]
       */
    }, {
      key: "open",
      value: function open($trigger) {
        if (this.isOpen) {
          return;
        }
        this.isOpen = true;
        var body = document.body;
        body.style.overflow = 'hidden';
        this.$el[0].classList.add('open');
        body.appendChild(this.$overlay[0]);

        // Set opening trigger, undefined indicates modal was opened by javascript
        this.openingTrigger = !!$trigger ? $trigger : undefined;
        if (this.options.dismissible) {
          this.handleKeydownBound = this.handleKeydown.bind(this);
          document.addEventListener('keydown', this.handleKeydownBound);
        }
        this.animateIn();
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
        this.$el[0].classList.remove('open');
        document.body.style.overflow = '';
        if (this.options.dismissible) {
          document.removeEventListener('keydown', this.handleKeydownBound);
        }
        this.animateOut();
        return this;
      }
    }], [{
      key: "defaults",
      get: function get() {
        return _defaults;
      }
    }, {
      key: "init",
      value: function init($els, options) {
        var arr = [];
        $els.each(function () {
          arr.push(new Modal($(this), options));
        });
        return arr;
      }
    }]);
    return Modal;
  }();
  /**
   * @static
   * @memberof Modal
   */
  Modal._increment = 0;

  /**
   * @static
   * @memberof Modal
   */
  Modal._count = 0;
  Materialize.Modal = Modal;
  $.fn.modal = function (methodOrOptions) {
    // Call plugin method if valid method name is passed in
    if (Modal.prototype[methodOrOptions]) {
      // Getter methods
      if (methodOrOptions.slice(0, 3) === 'get') {
        return this.first()[0].M_Modal[methodOrOptions]();

        // Void methods
      } else {
        return this.each(function () {
          this.M_Modal[methodOrOptions]();
        });
      }

      // Initialize plugin if options or no argument is passed in
    } else if (_typeof(methodOrOptions) === 'object' || !methodOrOptions) {
      Modal.init(this, arguments[0]);
      return this;

      // Return error if an unrecognized  method name is passed in
    } else {
      $.error("Method ".concat(methodOrOptions, " does not exist on jQuery.modal"));
    }
  };
})(jQuery, Materialize.Vel);
(function ($) {
  var methods = {
    init: function init(options) {
      var defaults = {
        menuWidth: 300,
        edge: 'left',
        closeOnClick: false,
        draggable: true,
        onOpen: null,
        onClose: null
      };
      options = $.extend(defaults, options);
      $(this).each(function () {
        var $this = $(this);
        var menuId = $this.attr('data-activates');
        var menu = $("#" + menuId);

        // Set to width
        if (options.menuWidth != 300) {
          menu.css('width', options.menuWidth);
        }

        // Add Touch Area
        var $dragTarget = $('.drag-target[data-sidenav="' + menuId + '"]');
        if (options.draggable) {
          // Regenerate dragTarget
          if ($dragTarget.length) {
            $dragTarget.remove();
          }
          $dragTarget = $('<div class="drag-target"></div>').attr('data-sidenav', menuId);
          $('body').append($dragTarget);
        } else {
          $dragTarget = $();
        }
        if (options.edge == 'left') {
          menu.css('transform', 'translateX(-100%)');
          $dragTarget.css({
            'left': 0
          }); // Add Touch Area
        } else {
          menu.addClass('right-aligned') // Change text-alignment to right
          .css('transform', 'translateX(100%)');
          $dragTarget.css({
            'right': 0
          }); // Add Touch Area
        }

        // If fixed sidenav, bring menu out
        if (menu.hasClass('fixed')) {
          if (window.innerWidth > 992) {
            menu.css('transform', 'translateX(0)');
          }
        }

        // Window resize to reset on large screens fixed
        if (menu.hasClass('fixed')) {
          $(window).resize(function () {
            if (window.innerWidth > 992) {
              // Close menu if window is resized bigger than 992 and user has fixed sidenav
              if ($('#sidenav-overlay').length !== 0 && menuOut) {
                removeMenu(true);
              } else {
                // menu.removeAttr('style');
                menu.css('transform', 'translateX(0%)');
                // menu.css('width', options.menuWidth);
              }
            } else if (menuOut === false) {
              if (options.edge === 'left') {
                menu.css('transform', 'translateX(-100%)');
              } else {
                menu.css('transform', 'translateX(100%)');
              }
            }
          });
        }

        // if closeOnClick, then add close event for all a tags in side sideNav
        if (options.closeOnClick === true) {
          menu.on("click.itemclick", "a:not(.collapsible-header)", function () {
            if (!(window.innerWidth > 992 && menu.hasClass('fixed'))) {
              removeMenu();
            }
          });
        } else {
          menu.on("click.itemclick", "a.close-sidenav", function () {
            if (!(window.innerWidth > 992 && menu.hasClass('fixed'))) {
              removeMenu();
            }
          });
        }
        var removeMenu = function removeMenu(restoreNav) {
          panning = false;
          menuOut = false;
          // Reenable scrolling
          $('body').css({
            overflow: '',
            width: ''
          });
          $('#sidenav-overlay').velocity({
            opacity: 0
          }, {
            duration: 200,
            queue: false,
            easing: 'easeOutQuad',
            complete: function complete() {
              $(this).remove();
            }
          });
          if (options.edge === 'left') {
            // Reset phantom div
            $dragTarget.css({
              width: '',
              right: '',
              left: '0'
            });
            menu.velocity({
              'translateX': '-100%'
            }, {
              duration: 200,
              queue: false,
              easing: 'easeOutCubic',
              complete: function complete() {
                if (restoreNav === true) {
                  // Restore Fixed sidenav
                  menu.removeAttr('style');
                  menu.css('width', options.menuWidth);
                }
              }
            });
          } else {
            // Reset phantom div
            $dragTarget.css({
              width: '',
              right: '0',
              left: ''
            });
            menu.velocity({
              'translateX': '100%'
            }, {
              duration: 200,
              queue: false,
              easing: 'easeOutCubic',
              complete: function complete() {
                if (restoreNav === true) {
                  // Restore Fixed sidenav
                  menu.removeAttr('style');
                  menu.css('width', options.menuWidth);
                }
              }
            });
          }

          // Callback
          if (typeof options.onClose === 'function') {
            options.onClose.call(this, menu);
          }
        };

        // Touch Event
        var panning = false;
        var menuOut = false;
        if (options.draggable) {
          $dragTarget.on('click', function () {
            if (menuOut) {
              removeMenu();
            }
          });
          $dragTarget.hammer({
            prevent_default: false
          }).on('pan', function (e) {
            if (e.gesture.pointerType == "touch") {
              var direction = e.gesture.direction;
              var x = e.gesture.center.x;
              var y = e.gesture.center.y;
              var velocityX = e.gesture.velocityX;

              // Vertical scroll bugfix
              if (x === 0 && y === 0) {
                return;
              }

              // Disable Scrolling
              var $body = $('body');
              var $overlay = $('#sidenav-overlay');
              var oldWidth = $body.innerWidth();
              $body.css('overflow', 'hidden');
              $body.width(oldWidth);

              // If overlay does not exist, create one and if it is clicked, close menu
              if ($overlay.length === 0) {
                $overlay = $('<div id="sidenav-overlay"></div>');
                $overlay.css('opacity', 0).click(function () {
                  removeMenu();
                });

                // Run 'onOpen' when sidenav is opened via touch/swipe if applicable
                if (typeof options.onOpen === 'function') {
                  options.onOpen.call(this, menu);
                }
                $('body').append($overlay);
              }

              // Keep within boundaries
              if (options.edge === 'left') {
                if (x > options.menuWidth) {
                  x = options.menuWidth;
                } else if (x < 0) {
                  x = 0;
                }
              }
              if (options.edge === 'left') {
                // Left Direction
                if (x < options.menuWidth / 2) {
                  menuOut = false;
                }
                // Right Direction
                else if (x >= options.menuWidth / 2) {
                  menuOut = true;
                }
                menu.css('transform', 'translateX(' + (x - options.menuWidth) + 'px)');
              } else {
                // Left Direction
                if (x < window.innerWidth - options.menuWidth / 2) {
                  menuOut = true;
                }
                // Right Direction
                else if (x >= window.innerWidth - options.menuWidth / 2) {
                  menuOut = false;
                }
                var rightPos = x - options.menuWidth / 2;
                if (rightPos < 0) {
                  rightPos = 0;
                }
                menu.css('transform', 'translateX(' + rightPos + 'px)');
              }

              // Percentage overlay
              var overlayPerc;
              if (options.edge === 'left') {
                overlayPerc = x / options.menuWidth;
                $overlay.velocity({
                  opacity: overlayPerc
                }, {
                  duration: 10,
                  queue: false,
                  easing: 'easeOutQuad'
                });
              } else {
                overlayPerc = Math.abs((x - window.innerWidth) / options.menuWidth);
                $overlay.velocity({
                  opacity: overlayPerc
                }, {
                  duration: 10,
                  queue: false,
                  easing: 'easeOutQuad'
                });
              }
            }
          }).on('panend', function (e) {
            if (e.gesture.pointerType == "touch") {
              var $overlay = $('#sidenav-overlay');
              var velocityX = e.gesture.velocityX;
              var x = e.gesture.center.x;
              var leftPos = x - options.menuWidth;
              var rightPos = x - options.menuWidth / 2;
              if (leftPos > 0) {
                leftPos = 0;
              }
              if (rightPos < 0) {
                rightPos = 0;
              }
              panning = false;
              if (options.edge === 'left') {
                // If velocityX <= 0.3 then the user is flinging the menu closed so ignore menuOut
                if (menuOut && velocityX <= 0.3 || velocityX < -0.5) {
                  // Return menu to open
                  if (leftPos !== 0) {
                    menu.velocity({
                      'translateX': [0, leftPos]
                    }, {
                      duration: 300,
                      queue: false,
                      easing: 'easeOutQuad'
                    });
                  }
                  $overlay.velocity({
                    opacity: 1
                  }, {
                    duration: 50,
                    queue: false,
                    easing: 'easeOutQuad'
                  });
                  $dragTarget.css({
                    width: '50%',
                    right: 0,
                    left: ''
                  });
                  menuOut = true;
                } else if (!menuOut || velocityX > 0.3) {
                  // Enable Scrolling
                  $('body').css({
                    overflow: '',
                    width: ''
                  });
                  // Slide menu closed
                  menu.velocity({
                    'translateX': [-1 * options.menuWidth - 10, leftPos]
                  }, {
                    duration: 200,
                    queue: false,
                    easing: 'easeOutQuad'
                  });
                  $overlay.velocity({
                    opacity: 0
                  }, {
                    duration: 200,
                    queue: false,
                    easing: 'easeOutQuad',
                    complete: function complete() {
                      // Run 'onClose' when sidenav is closed via touch/swipe if applicable
                      if (typeof options.onClose === 'function') {
                        options.onClose.call(this, menu);
                      }
                      $(this).remove();
                    }
                  });
                  $dragTarget.css({
                    width: '10px',
                    right: '',
                    left: 0
                  });
                }
              } else {
                if (menuOut && velocityX >= -0.3 || velocityX > 0.5) {
                  // Return menu to open
                  if (rightPos !== 0) {
                    menu.velocity({
                      'translateX': [0, rightPos]
                    }, {
                      duration: 300,
                      queue: false,
                      easing: 'easeOutQuad'
                    });
                  }
                  $overlay.velocity({
                    opacity: 1
                  }, {
                    duration: 50,
                    queue: false,
                    easing: 'easeOutQuad'
                  });
                  $dragTarget.css({
                    width: '50%',
                    right: '',
                    left: 0
                  });
                  menuOut = true;
                } else if (!menuOut || velocityX < -0.3) {
                  // Enable Scrolling
                  $('body').css({
                    overflow: '',
                    width: ''
                  });

                  // Slide menu closed
                  menu.velocity({
                    'translateX': [options.menuWidth + 10, rightPos]
                  }, {
                    duration: 200,
                    queue: false,
                    easing: 'easeOutQuad'
                  });
                  $overlay.velocity({
                    opacity: 0
                  }, {
                    duration: 200,
                    queue: false,
                    easing: 'easeOutQuad',
                    complete: function complete() {
                      // Run 'onClose' when sidenav is closed via touch/swipe if applicable
                      if (typeof options.onClose === 'function') {
                        options.onClose.call(this, menu);
                      }
                      $(this).remove();
                    }
                  });
                  $dragTarget.css({
                    width: '10px',
                    right: 0,
                    left: ''
                  });
                }
              }
            }
          });
        }
        $this.off('click.sidenav').on('click.sidenav', function () {
          if (menuOut === true) {
            menuOut = false;
            panning = false;
            removeMenu();
          } else {
            // Disable Scrolling
            var $body = $('body');
            var $overlay = $('<div id="sidenav-overlay"></div>');
            var oldWidth = $body.innerWidth();
            $body.css('overflow', 'hidden');
            $body.width(oldWidth);

            // Push current drag target on top of DOM tree
            $('body').append($dragTarget);
            if (options.edge === 'left') {
              $dragTarget.css({
                width: '50%',
                right: 0,
                left: ''
              });
              menu.velocity({
                'translateX': [0, -1 * options.menuWidth]
              }, {
                duration: 300,
                queue: false,
                easing: 'easeOutQuad'
              });
            } else {
              $dragTarget.css({
                width: '50%',
                right: '',
                left: 0
              });
              menu.velocity({
                'translateX': [0, options.menuWidth]
              }, {
                duration: 300,
                queue: false,
                easing: 'easeOutQuad'
              });
            }

            // Overlay close on click
            $overlay.css('opacity', 0).click(function () {
              menuOut = false;
              panning = false;
              removeMenu();
              $overlay.velocity({
                opacity: 0
              }, {
                duration: 300,
                queue: false,
                easing: 'easeOutQuad',
                complete: function complete() {
                  $(this).remove();
                }
              });
            });

            // Append body
            $('body').append($overlay);
            $overlay.velocity({
              opacity: 1
            }, {
              duration: 300,
              queue: false,
              easing: 'easeOutQuad',
              complete: function complete() {
                menuOut = true;
                panning = false;
              }
            });

            // Callback
            if (typeof options.onOpen === 'function') {
              options.onOpen.call(this, menu);
            }
          }
          return false;
        });
      });
    },
    destroy: function destroy() {
      var $overlay = $('#sidenav-overlay');
      var $dragTarget = $('.drag-target[data-sidenav="' + $(this).attr('data-activates') + '"]');
      $overlay.trigger('click');
      $dragTarget.remove();
      $(this).off('click');
      $overlay.remove();
    },
    show: function show() {
      this.trigger('click');
    },
    hide: function hide() {
      $('#sidenav-overlay').trigger('click');
    }
  };
  $.fn.sideNav = function (methodOrOptions) {
    if (methods[methodOrOptions]) {
      return methods[methodOrOptions].apply(this, Array.prototype.slice.call(arguments, 1));
    } else if (_typeof(methodOrOptions) === 'object' || !methodOrOptions) {
      // Default to "init"
      return methods.init.apply(this, arguments);
    } else {
      $.error('Method ' + methodOrOptions + ' does not exist on jQuery.sideNav');
    }
  }; // Plugin end
})(jQuery);

/**
 * Extend jquery with a scrollspy plugin.
 * This watches the window scroll and fires events when elements are scrolled into viewport.
 *
 * throttle() and getTime() taken from Underscore.js
 * https://github.com/jashkenas/underscore
 *
 * @author Copyright 2013 John Smart
 * @license https://raw.github.com/thesmart/jquery-scrollspy/master/LICENSE
 * @see https://github.com/thesmart
 * @version 0.1.2
 */
(function ($) {
  var jWindow = $(window);
  var elements = [];
  var elementsInView = [];
  var isSpying = false;
  var ticks = 0;
  var unique_id = 1;
  var offset = {
    top: 0,
    right: 0,
    bottom: 0,
    left: 0
  };

  /**
   * Find elements that are within the boundary
   * @param {number} top
   * @param {number} right
   * @param {number} bottom
   * @param {number} left
   * @return {jQuery}		A collection of elements
   */
  function findElements(top, right, bottom, left) {
    var hits = $();
    $.each(elements, function (i, element) {
      if (element.height() > 0) {
        var elTop = element.offset().top,
          elLeft = element.offset().left,
          elRight = elLeft + element.width(),
          elBottom = elTop + element.height();
        var isIntersect = !(elLeft > right || elRight < left || elTop > bottom || elBottom < top);
        if (isIntersect) {
          hits.push(element);
        }
      }
    });
    return hits;
  }

  /**
   * Called when the user scrolls the window
   */
  function onScroll(scrollOffset) {
    // unique tick id
    ++ticks;

    // viewport rectangle
    var top = jWindow.scrollTop(),
      left = jWindow.scrollLeft(),
      right = left + jWindow.width(),
      bottom = top + jWindow.height();

    // determine which elements are in view
    var intersections = findElements(top + offset.top + scrollOffset || 200, right + offset.right, bottom + offset.bottom, left + offset.left);
    $.each(intersections, function (i, element) {
      var lastTick = element.data('scrollSpy:ticks');
      if (typeof lastTick != 'number') {
        // entered into view
        element.triggerHandler('scrollSpy:enter');
      }

      // update tick id
      element.data('scrollSpy:ticks', ticks);
    });

    // determine which elements are no longer in view
    $.each(elementsInView, function (i, element) {
      var lastTick = element.data('scrollSpy:ticks');
      if (typeof lastTick == 'number' && lastTick !== ticks) {
        // exited from view
        element.triggerHandler('scrollSpy:exit');
        element.data('scrollSpy:ticks', null);
      }
    });

    // remember elements in view for next tick
    elementsInView = intersections;
  }

  /**
   * Called when window is resized
  */
  function onWinSize() {
    jWindow.trigger('scrollSpy:winSize');
  }

  /**
   * Enables ScrollSpy using a selector
   * @param {jQuery|string} selector  The elements collection, or a selector
   * @param {Object=} options	Optional.
         throttle : number -> scrollspy throttling. Default: 100 ms
         offsetTop : number -> offset from top. Default: 0
         offsetRight : number -> offset from right. Default: 0
         offsetBottom : number -> offset from bottom. Default: 0
         offsetLeft : number -> offset from left. Default: 0
  			activeClass : string -> Class name to be added to the active link. Default: active
   * @returns {jQuery}
   */
  $.scrollSpy = function (selector, options) {
    var defaults = {
      throttle: 100,
      scrollOffset: 200,
      // offset - 200 allows elements near bottom of page to scroll
      activeClass: 'active',
      getActiveElement: function getActiveElement(id) {
        return 'a[href="#' + id + '"]';
      }
    };
    options = $.extend(defaults, options);
    var visible = [];
    selector = $(selector);
    selector.each(function (i, element) {
      elements.push($(element));
      $(element).data("scrollSpy:id", i);
      // // Smooth scroll to section
      //  $('a[href="#' + $(element).attr('id') + '"]').click(function(e) {
      //    e.preventDefault();
      //    var offset = $(Materialize.escapeHash(this.hash)).offset().top + 1;
      //   	$('html, body').animate({ scrollTop: offset - options.scrollOffset }, {duration: 400, queue: false, easing: 'easeOutCubic'});
      //  });
    });

    offset.top = options.offsetTop || 0;
    offset.right = options.offsetRight || 0;
    offset.bottom = options.offsetBottom || 0;
    offset.left = options.offsetLeft || 0;
    var throttledScroll = Materialize.throttle(function () {
      onScroll(options.scrollOffset);
    }, options.throttle || 100);
    var readyScroll = function readyScroll() {
      $(document).ready(throttledScroll);
    };
    if (!isSpying) {
      jWindow.on('scroll', readyScroll);
      jWindow.on('resize', readyScroll);
      isSpying = true;
    }

    // perform a scan once, after current execution context, and after dom is ready
    setTimeout(readyScroll, 0);
    selector.on('scrollSpy:enter', function () {
      visible = $.grep(visible, function (value) {
        return value.height() != 0;
      });
      var $this = $(this);
      if (visible[0]) {
        $(options.getActiveElement(visible[0].attr('id'))).removeClass(options.activeClass);
        if ($this.data('scrollSpy:id') < visible[0].data('scrollSpy:id')) {
          visible.unshift($(this));
        } else {
          visible.push($(this));
        }
      } else {
        visible.push($(this));
      }
      $(options.getActiveElement(visible[0].attr('id'))).addClass(options.activeClass);
    });
    selector.on('scrollSpy:exit', function () {
      visible = $.grep(visible, function (value) {
        return value.height() != 0;
      });
      if (visible[0]) {
        $(options.getActiveElement(visible[0].attr('id'))).removeClass(options.activeClass);
        var $this = $(this);
        visible = $.grep(visible, function (value) {
          return value.attr('id') != $this.attr('id');
        });
        if (visible[0]) {
          // Check if empty
          $(options.getActiveElement(visible[0].attr('id'))).addClass(options.activeClass);
        }
      }
    });
    return selector;
  };

  /**
   * Listen for window resize events
   * @param {Object=} options						Optional. Set { throttle: number } to change throttling. Default: 100 ms
   * @returns {jQuery}		$(window)
   */
  $.winSizeSpy = function (options) {
    $.winSizeSpy = function () {
      return jWindow;
    }; // lock from multiple calls
    options = options || {
      throttle: 100
    };
    return jWindow.on('resize', Materialize.throttle(onWinSize, options.throttle || 100));
  };

  /**
   * Enables ScrollSpy on a collection of elements
   * e.g. $('.scrollSpy').scrollSpy()
   * @param {Object=} options	Optional.
  										throttle : number -> scrollspy throttling. Default: 100 ms
  										offsetTop : number -> offset from top. Default: 0
  										offsetRight : number -> offset from right. Default: 0
  										offsetBottom : number -> offset from bottom. Default: 0
  										offsetLeft : number -> offset from left. Default: 0
   * @returns {jQuery}
   */
  $.fn.scrollSpy = function (options) {
    return $.scrollSpy($(this), options);
  };
})(jQuery);
(function ($) {
  $(document).ready(function () {
    // Function to update labels of text fields
    Materialize.updateTextFields = function () {
      var input_selector = 'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=number], input[type=search], textarea';
      $(input_selector).each(function (index, element) {
        var $this = $(this);
        if ($(element).val().length > 0 || $(element).is(':focus') || element.autofocus || $this.attr('placeholder') !== undefined) {
          $this.siblings('label').addClass('active');
        } else if ($(element)[0].validity) {
          $this.siblings('label').toggleClass('active', $(element)[0].validity.badInput === true);
        } else {
          $this.siblings('label').removeClass('active');
        }
      });
    };

    // Text based inputs
    var input_selector = 'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=number], input[type=search], textarea';

    // Add active if form auto complete
    $(document).on('change', input_selector, function () {
      if ($(this).val().length !== 0 || $(this).attr('placeholder') !== undefined) {
        $(this).siblings('label').addClass('active');
      }
      validate_field($(this));
    });

    // Add active if input element has been pre-populated on document ready
    $(document).ready(function () {
      Materialize.updateTextFields();
    });

    // HTML DOM FORM RESET handling
    $(document).on('reset', function (e) {
      var formReset = $(e.target);
      if (formReset.is('form')) {
        formReset.find(input_selector).removeClass('valid').removeClass('invalid');
        formReset.find(input_selector).each(function () {
          if ($(this).attr('value') === '') {
            $(this).siblings('label').removeClass('active');
          }
        });

        // Reset select
        formReset.find('select.initialized').each(function () {
          var reset_text = formReset.find('option[selected]').text();
          formReset.siblings('input.select-dropdown').val(reset_text);
        });
      }
    });

    // Add active when element has focus
    $(document).on('focus', input_selector, function () {
      $(this).siblings('label, .prefix').addClass('active');
    });
    $(document).on('blur', input_selector, function () {
      var $inputElement = $(this);
      var selector = ".prefix";
      if ($inputElement.val().length === 0 && $inputElement[0].validity.badInput !== true && $inputElement.attr('placeholder') === undefined) {
        selector += ", label";
      }
      $inputElement.siblings(selector).removeClass('active');
      validate_field($inputElement);
    });
    window.validate_field = function (object) {
      var hasLength = object.attr('data-length') !== undefined;
      var lenAttr = parseInt(object.attr('data-length'));
      var len = object.val().length;
      if (object.val().length === 0 && object[0].validity.badInput === false && !object.is(':required')) {
        if (object.hasClass('validate')) {
          object.removeClass('valid');
          object.removeClass('invalid');
        }
      } else {
        if (object.hasClass('validate')) {
          // Check for character counter attributes
          if (object.is(':valid') && hasLength && len <= lenAttr || object.is(':valid') && !hasLength) {
            object.removeClass('invalid');
            object.addClass('valid');
          } else {
            object.removeClass('valid');
            object.addClass('invalid');
          }
        }
      }
    };

    // Radio and Checkbox focus class
    var radio_checkbox = 'input[type=radio], input[type=checkbox]';
    $(document).on('keyup.radio', radio_checkbox, function (e) {
      // TAB, check if tabbing to radio or checkbox.
      if (e.which === 9) {
        $(this).addClass('tabbed');
        var $this = $(this);
        $this.one('blur', function (e) {
          $(this).removeClass('tabbed');
        });
        return;
      }
    });

    // Textarea Auto Resize
    var hiddenDiv = $('.hiddendiv').first();
    if (!hiddenDiv.length) {
      hiddenDiv = $('<div class="hiddendiv common"></div>');
      $('body').append(hiddenDiv);
    }
    var text_area_selector = '.materialize-textarea';
    function textareaAutoResize($textarea) {
      // Set font properties of hiddenDiv

      var fontFamily = $textarea.css('font-family');
      var fontSize = $textarea.css('font-size');
      var lineHeight = $textarea.css('line-height');
      var padding = $textarea.css('padding');
      if (fontSize) {
        hiddenDiv.css('font-size', fontSize);
      }
      if (fontFamily) {
        hiddenDiv.css('font-family', fontFamily);
      }
      if (lineHeight) {
        hiddenDiv.css('line-height', lineHeight);
      }
      if (padding) {
        hiddenDiv.css('padding', padding);
      }

      // Set original-height, if none
      if (!$textarea.data('original-height')) {
        $textarea.data('original-height', $textarea.height());
      }
      if ($textarea.attr('wrap') === 'off') {
        hiddenDiv.css('overflow-wrap', 'normal').css('white-space', 'pre');
      }
      hiddenDiv.text($textarea.val() + '\n');
      var content = hiddenDiv.html().replace(/\n/g, '<br>');
      hiddenDiv.html(content);

      // When textarea is hidden, width goes crazy.
      // Approximate with half of window size

      if ($textarea.is(':visible')) {
        hiddenDiv.css('width', $textarea.width());
      } else {
        hiddenDiv.css('width', $(window).width() / 2);
      }

      /**
       * Resize if the new height is greater than the
       * original height of the textarea
       */
      if ($textarea.data('original-height') <= hiddenDiv.height()) {
        $textarea.css('height', hiddenDiv.height());
      } else if ($textarea.val().length < $textarea.data('previous-length')) {
        /**
         * In case the new height is less than original height, it
         * means the textarea has less text than before
         * So we set the height to the original one
         */
        $textarea.css('height', $textarea.data('original-height'));
      }
      $textarea.data('previous-length', $textarea.val().length);
    }
    $(text_area_selector).each(function () {
      var $textarea = $(this);
      /**
       * Instead of resizing textarea on document load,
       * store the original height and the original length
       */
      $textarea.data('original-height', $textarea.height());
      $textarea.data('previous-length', $textarea.val().length);
    });
    $('body').on('keyup keydown autoresize', text_area_selector, function () {
      textareaAutoResize($(this));
    });

    // File Input Path
    $(document).on('change', '.file-field input[type="file"]', function () {
      var file_field = $(this).closest('.file-field');
      var path_input = file_field.find('input.file-path');
      var files = $(this)[0].files;
      var file_names = [];
      for (var i = 0; i < files.length; i++) {
        file_names.push(files[i].name);
      }
      path_input.val(file_names.join(", "));
      path_input.trigger('change');
    });

    /****************
    *  Range Input  *
    ****************/

    var range_type = 'input[type=range]';
    var range_mousedown = false;
    var left;
    $(range_type).each(function () {
      var thumb = $('<span class="thumb"><span class="value"></span></span>');
      $(this).after(thumb);
    });
    var showRangeBubble = function showRangeBubble(thumb) {
      var paddingLeft = parseInt(thumb.parent().css('padding-left'));
      var marginLeft = -7 + paddingLeft + 'px';
      thumb.velocity({
        height: "30px",
        width: "30px",
        top: "-30px",
        marginLeft: marginLeft
      }, {
        duration: 300,
        easing: 'easeOutExpo'
      });
    };
    var calcRangeOffset = function calcRangeOffset(range) {
      var width = range.width() - 15;
      var max = parseFloat(range.attr('max'));
      var min = parseFloat(range.attr('min'));
      var percent = (parseFloat(range.val()) - min) / (max - min);
      return percent * width;
    };
    var range_wrapper = '.range-field';
    $(document).on('change', range_type, function (e) {
      var thumb = $(this).siblings('.thumb');
      thumb.find('.value').html($(this).val());
      if (!thumb.hasClass('active')) {
        showRangeBubble(thumb);
      }
      var offsetLeft = calcRangeOffset($(this));
      thumb.addClass('active').css('left', offsetLeft);
    });
    $(document).on('mousedown touchstart', range_type, function (e) {
      var thumb = $(this).siblings('.thumb');

      // If thumb indicator does not exist yet, create it
      if (thumb.length <= 0) {
        thumb = $('<span class="thumb"><span class="value"></span></span>');
        $(this).after(thumb);
      }

      // Set indicator value
      thumb.find('.value').html($(this).val());
      range_mousedown = true;
      $(this).addClass('active');
      if (!thumb.hasClass('active')) {
        showRangeBubble(thumb);
      }
      if (e.type !== 'input') {
        var offsetLeft = calcRangeOffset($(this));
        thumb.addClass('active').css('left', offsetLeft);
      }
    });
    $(document).on('mouseup touchend', range_wrapper, function () {
      range_mousedown = false;
      $(this).removeClass('active');
    });
    $(document).on('input mousemove touchmove', range_wrapper, function (e) {
      var thumb = $(this).children('.thumb');
      var left;
      var input = $(this).find(range_type);
      if (range_mousedown) {
        if (!thumb.hasClass('active')) {
          showRangeBubble(thumb);
        }
        var offsetLeft = calcRangeOffset(input);
        thumb.addClass('active').css('left', offsetLeft);
        thumb.find('.value').html(thumb.siblings(range_type).val());
      }
    });
    $(document).on('mouseout touchleave', range_wrapper, function () {
      if (!range_mousedown) {
        var thumb = $(this).children('.thumb');
        var paddingLeft = parseInt($(this).css('padding-left'));
        var marginLeft = 7 + paddingLeft + 'px';
        if (thumb.hasClass('active')) {
          thumb.velocity({
            height: '0',
            width: '0',
            top: '10px',
            marginLeft: marginLeft
          }, {
            duration: 100
          });
        }
        thumb.removeClass('active');
      }
    });

    /**************************
     * Auto complete plugin  *
     *************************/
    $.fn.autocomplete = function (options) {
      // Defaults
      var defaults = {
        data: {},
        limit: Infinity,
        onAutocomplete: null,
        minLength: 1
      };
      options = $.extend(defaults, options);
      return this.each(function () {
        var $input = $(this);
        var data = options.data,
          count = 0,
          activeIndex = -1,
          oldVal,
          $inputDiv = $input.closest('.input-field'); // Div to append on

        // Check if data isn't empty
        if (!$.isEmptyObject(data)) {
          var $autocomplete = $('<ul class="autocomplete-content dropdown-content"></ul>');
          var $oldAutocomplete;

          // Append autocomplete element.
          // Prevent double structure init.
          if ($inputDiv.length) {
            $oldAutocomplete = $inputDiv.children('.autocomplete-content.dropdown-content').first();
            if (!$oldAutocomplete.length) {
              $inputDiv.append($autocomplete); // Set ul in body
            }
          } else {
            $oldAutocomplete = $input.next('.autocomplete-content.dropdown-content');
            if (!$oldAutocomplete.length) {
              $input.after($autocomplete);
            }
          }
          if ($oldAutocomplete.length) {
            $autocomplete = $oldAutocomplete;
          }

          // Highlight partial match.
          var highlight = function highlight(string, $el) {
            var img = $el.find('img');
            var matchStart = $el.text().toLowerCase().indexOf("" + string.toLowerCase() + ""),
              matchEnd = matchStart + string.length - 1,
              beforeMatch = $el.text().slice(0, matchStart),
              matchText = $el.text().slice(matchStart, matchEnd + 1),
              afterMatch = $el.text().slice(matchEnd + 1);
            $el.html("<span>" + beforeMatch + "<span class='highlight'>" + matchText + "</span>" + afterMatch + "</span>");
            if (img.length) {
              $el.prepend(img);
            }
          };

          // Reset current element position
          var resetCurrentElement = function resetCurrentElement() {
            activeIndex = -1;
            $autocomplete.find('.active').removeClass('active');
          };

          // Remove autocomplete elements
          var removeAutocomplete = function removeAutocomplete() {
            $autocomplete.empty();
            resetCurrentElement();
            oldVal = undefined;
          };
          $input.off('blur.autocomplete').on('blur.autocomplete', function () {
            removeAutocomplete();
          });

          // Perform search
          $input.off('keyup.autocomplete focus.autocomplete').on('keyup.autocomplete focus.autocomplete', function (e) {
            // Reset count.
            count = 0;
            var val = $input.val().toLowerCase();

            // Don't capture enter or arrow key usage.
            if (e.which === 13 || e.which === 38 || e.which === 40) {
              return;
            }

            // Check if the input isn't empty
            if (oldVal !== val) {
              removeAutocomplete();
              if (val.length >= options.minLength) {
                for (var key in data) {
                  if (data.hasOwnProperty(key) && key.toLowerCase().indexOf(val) !== -1) {
                    // Break if past limit
                    if (count >= options.limit) {
                      break;
                    }
                    var autocompleteOption = $('<li></li>');
                    if (!!data[key]) {
                      autocompleteOption.append('<img src="' + data[key] + '" class="right circle"><span>' + key + '</span>');
                    } else {
                      autocompleteOption.append('<span>' + key + '</span>');
                    }
                    $autocomplete.append(autocompleteOption);
                    highlight(val, autocompleteOption);
                    count++;
                  }
                }
              }
            }

            // Update oldVal
            oldVal = val;
          });
          $input.off('keydown.autocomplete').on('keydown.autocomplete', function (e) {
            // Arrow keys and enter key usage
            var keyCode = e.which,
              liElement,
              numItems = $autocomplete.children('li').length,
              $active = $autocomplete.children('.active').first();

            // select element on Enter
            if (keyCode === 13 && activeIndex >= 0) {
              liElement = $autocomplete.children('li').eq(activeIndex);
              if (liElement.length) {
                liElement.trigger('mousedown.autocomplete');
                e.preventDefault();
              }
              return;
            }

            // Capture up and down key
            if (keyCode === 38 || keyCode === 40) {
              e.preventDefault();
              if (keyCode === 38 && activeIndex > 0) {
                activeIndex--;
              }
              if (keyCode === 40 && activeIndex < numItems - 1) {
                activeIndex++;
              }
              $active.removeClass('active');
              if (activeIndex >= 0) {
                $autocomplete.children('li').eq(activeIndex).addClass('active');
              }
            }
          });

          // Set input value
          $autocomplete.off('mousedown.autocomplete touchstart.autocomplete').on('mousedown.autocomplete touchstart.autocomplete', 'li', function () {
            var text = $(this).text().trim();
            $input.val(text);
            $input.trigger('change');
            removeAutocomplete();

            // Handle onAutocomplete callback.
            if (typeof options.onAutocomplete === "function") {
              options.onAutocomplete.call(this, text);
            }
          });

          // Empty data
        } else {
          $input.off('keyup.autocomplete focus.autocomplete');
        }
      });
    };
  }); // End of $(document).ready

  /*******************
   *  Select Plugin  *
   ******************/
  $.fn.material_select = function (callback) {
    $(this).each(function () {
      var $select = $(this);
      if ($select.hasClass('browser-default')) {
        return; // Continue to next (return false breaks out of entire loop)
      }

      var multiple = $select.attr('multiple') ? true : false,
        lastID = $select.attr('data-select-id'); // Tear down structure if Select needs to be rebuilt

      if (lastID) {
        $select.parent().find('span.caret').remove();
        $select.parent().find('input').remove();
        $select.unwrap();
        $('ul#select-options-' + lastID).remove();
      }

      // If destroying the select, remove the selelct-id and reset it to it's uninitialized state.
      if (callback === 'destroy') {
        $select.removeAttr('data-select-id').removeClass('initialized');
        $(window).off('click.select');
        return;
      }
      var uniqueID = Materialize.guid();
      $select.attr('data-select-id', uniqueID);
      var wrapper = $('<div class="select-wrapper"></div>');
      wrapper.addClass($select.attr('class'));
      if ($select.is(':disabled')) wrapper.addClass('disabled');
      var options = $('<ul id="select-options-' + uniqueID + '" class="dropdown-content select-dropdown ' + (multiple ? 'multiple-select-dropdown' : '') + '"></ul>'),
        selectChildren = $select.children('option, optgroup'),
        valuesSelected = [],
        optionsHover = false;
      var label = $select.find('option:selected').html() || $select.find('option:first').html() || "";

      // Function that renders and appends the option taking into
      // account type and possible image icon.
      var appendOptionWithIcon = function appendOptionWithIcon(select, option, type) {
        // Add disabled attr if disabled
        var disabledClass = option.is(':disabled') ? 'disabled ' : '';
        var optgroupClass = type === 'optgroup-option' ? 'optgroup-option ' : '';
        var multipleCheckbox = multiple ? '<input type="checkbox"' + disabledClass + '/><label></label>' : '';

        // add icons
        var icon_url = option.data('icon');
        var classes = option.attr('class');
        if (!!icon_url) {
          var classString = '';
          if (!!classes) classString = ' class="' + classes + '"';

          // Check for multiple type.
          options.append($('<li class="' + disabledClass + optgroupClass + '"><img alt="" src="' + icon_url + '"' + classString + '><span>' + multipleCheckbox + option.html() + '</span></li>'));
          return true;
        }

        // Check for multiple type.
        options.append($('<li class="' + disabledClass + optgroupClass + '"><span>' + multipleCheckbox + option.html() + '</span></li>'));
      };

      /* Create dropdown structure. */
      if (selectChildren.length) {
        selectChildren.each(function () {
          if ($(this).is('option')) {
            // Direct descendant option.
            if (multiple) {
              appendOptionWithIcon($select, $(this), 'multiple');
            } else {
              appendOptionWithIcon($select, $(this));
            }
          } else if ($(this).is('optgroup')) {
            // Optgroup.
            var selectOptions = $(this).children('option');
            options.append($('<li class="optgroup"><span>' + $(this).attr('label') + '</span></li>'));
            selectOptions.each(function () {
              appendOptionWithIcon($select, $(this), 'optgroup-option');
            });
          }
        });
      }
      options.find('li:not(.optgroup)').each(function (i) {
        $(this).click(function (e) {
          // Check if option element is disabled
          if (!$(this).hasClass('disabled') && !$(this).hasClass('optgroup')) {
            var selected = true;
            if (multiple) {
              $('input[type="checkbox"]', this).prop('checked', function (i, v) {
                return !v;
              });
              selected = toggleEntryFromArray(valuesSelected, i, $select);
              $newSelect.trigger('focus');
            } else {
              options.find('li').removeClass('active');
              $(this).toggleClass('active');
              $newSelect.val($(this).text());
            }
            activateOption(options, $(this));
            $select.find('option').eq(i).prop('selected', selected);
            // Trigger onchange() event
            $select.trigger('change');
            if (typeof callback !== 'undefined') callback();
          }
          e.stopPropagation();
        });
      });

      // Wrap Elements
      $select.wrap(wrapper);
      // Add Select Display Element
      var dropdownIcon = $('<span class="caret">&#9660;</span>');

      // escape double quotes
      var sanitizedLabelHtml = label.replace(/"/g, '&quot;');
      var $newSelect = $('<input type="text" class="select-dropdown" readonly="true" ' + ($select.is(':disabled') ? 'disabled' : '') + ' data-activates="select-options-' + uniqueID + '" value="' + sanitizedLabelHtml + '"/>');
      $select.before($newSelect);
      $newSelect.before(dropdownIcon);
      $newSelect.after(options);
      // Check if section element is disabled
      if (!$select.is(':disabled')) {
        $newSelect.dropdown({
          'hover': false
        });
      }

      // Copy tabindex
      if ($select.attr('tabindex')) {
        $($newSelect[0]).attr('tabindex', $select.attr('tabindex'));
      }
      $select.addClass('initialized');
      $newSelect.on({
        'focus': function focus() {
          if ($('ul.select-dropdown').not(options[0]).is(':visible')) {
            $('input.select-dropdown').trigger('close');
            $(window).off('click.select');
          }
          if (!options.is(':visible')) {
            $(this).trigger('open', ['focus']);
            var label = $(this).val();
            if (multiple && label.indexOf(',') >= 0) {
              label = label.split(',')[0];
            }
            var selectedOption = options.find('li').filter(function () {
              return $(this).text().toLowerCase() === label.toLowerCase();
            })[0];
            activateOption(options, selectedOption, true);
            $(window).off('click.select').on('click.select', function () {
              multiple && (optionsHover || $newSelect.trigger('close'));
              $(window).off('click.select');
            });
          }
        },
        'click': function click(e) {
          e.stopPropagation();
        }
      });
      $newSelect.on('blur', function () {
        if (!multiple) {
          $(this).trigger('close');
          $(window).off('click.select');
        }
        options.find('li.selected').removeClass('selected');
      });
      options.hover(function () {
        optionsHover = true;
      }, function () {
        optionsHover = false;
      });

      // Add initial multiple selections.
      if (multiple) {
        $select.find("option:selected:not(:disabled)").each(function () {
          var index = this.index;
          toggleEntryFromArray(valuesSelected, index, $select);
          options.find("li:not(.optgroup)").eq(index).find(":checkbox").prop("checked", true);
        });
      }

      /**
       * Make option as selected and scroll to selected position
       * @param {jQuery} collection  Select options jQuery element
       * @param {Element} newOption  element of the new option
       * @param {Boolean} firstActivation  If on first activation of select
       */
      var activateOption = function activateOption(collection, newOption, firstActivation) {
        if (newOption) {
          collection.find('li.selected').removeClass('selected');
          var option = $(newOption);
          option.addClass('selected');
          if (!multiple || !!firstActivation) {
            options.scrollTo(option);
          }
        }
      };

      // Allow user to search by typing
      // this array is cleared after 1 second
      var filterQuery = [],
        onKeyDown = function onKeyDown(e) {
          // TAB - switch to another input
          if (e.which == 9) {
            $newSelect.trigger('close');
            return;
          }

          // ARROW DOWN WHEN SELECT IS CLOSED - open select options
          if (e.which == 40 && !options.is(':visible')) {
            $newSelect.trigger('open');
            return;
          }

          // ENTER WHEN SELECT IS CLOSED - submit form
          if (e.which == 13 && !options.is(':visible')) {
            return;
          }
          e.preventDefault();

          // CASE WHEN USER TYPE LETTERS
          var letter = String.fromCharCode(e.which).toLowerCase(),
            nonLetters = [9, 13, 27, 38, 40];
          if (letter && nonLetters.indexOf(e.which) === -1) {
            filterQuery.push(letter);
            var string = filterQuery.join(''),
              newOption = options.find('li').filter(function () {
                return $(this).text().toLowerCase().indexOf(string) === 0;
              })[0];
            if (newOption) {
              activateOption(options, newOption);
            }
          }

          // ENTER - select option and close when select options are opened
          if (e.which == 13) {
            var activeOption = options.find('li.selected:not(.disabled)')[0];
            if (activeOption) {
              $(activeOption).trigger('click');
              if (!multiple) {
                $newSelect.trigger('close');
              }
            }
          }

          // ARROW DOWN - move to next not disabled option
          if (e.which == 40) {
            if (options.find('li.selected').length) {
              newOption = options.find('li.selected').next('li:not(.disabled)')[0];
            } else {
              newOption = options.find('li:not(.disabled)')[0];
            }
            activateOption(options, newOption);
          }

          // ESC - close options
          if (e.which == 27) {
            $newSelect.trigger('close');
          }

          // ARROW UP - move to previous not disabled option
          if (e.which == 38) {
            newOption = options.find('li.selected').prev('li:not(.disabled)')[0];
            if (newOption) activateOption(options, newOption);
          }

          // Automaticaly clean filter query so user can search again by starting letters
          setTimeout(function () {
            filterQuery = [];
          }, 1000);
        };
      $newSelect.on('keydown', onKeyDown);
    });
    function toggleEntryFromArray(entriesArray, entryIndex, select) {
      var index = entriesArray.indexOf(entryIndex),
        notAdded = index === -1;
      if (notAdded) {
        entriesArray.push(entryIndex);
      } else {
        entriesArray.splice(index, 1);
      }
      select.siblings('ul.dropdown-content').find('li:not(.optgroup)').eq(entryIndex).toggleClass('active');

      // use notAdded instead of true (to detect if the option is selected or not)
      select.find('option').eq(entryIndex).prop('selected', notAdded);
      setValueToInput(entriesArray, select);
      return notAdded;
    }
    function setValueToInput(entriesArray, select) {
      var value = '';
      for (var i = 0, count = entriesArray.length; i < count; i++) {
        var text = select.find('option').eq(entriesArray[i]).text();
        i === 0 ? value += text : value += ', ' + text;
      }
      if (value === '') {
        value = select.find('option:disabled').eq(0).text();
      }
      select.siblings('input.select-dropdown').val(value);
    }
  };
})(jQuery);
(function ($) {
  $.fn.pushpin = function (options) {
    // Defaults
    var defaults = {
      top: 0,
      bottom: Infinity,
      offset: 0
    };

    // Remove pushpin event and classes
    if (options === "remove") {
      this.each(function () {
        var id = $(this).data('pushpin-id');
        $(window).off('scroll.' + id);
        $(this).removeData('pushpin-id').removeClass('pin-top pinned pin-bottom').removeAttr('style');
      });
      return false;
    }
    options = $.extend(defaults, options);
    var $index = 0;
    return this.each(function () {
      var $uniqueId = Materialize.guid(),
        $this = $(this),
        $original_offset = $(this).offset().top;
      function removePinClasses(object) {
        object.removeClass('pin-top');
        object.removeClass('pinned');
        object.removeClass('pin-bottom');
      }
      function updateElements(objects, scrolled) {
        objects.each(function () {
          // Add position fixed (because its between top and bottom)
          if (options.top <= scrolled && options.bottom >= scrolled && !$(this).hasClass('pinned')) {
            removePinClasses($(this));
            $(this).css('top', options.offset);
            $(this).addClass('pinned');
          }

          // Add pin-top (when scrolled position is above top)
          if (scrolled < options.top && !$(this).hasClass('pin-top')) {
            removePinClasses($(this));
            $(this).css('top', 0);
            $(this).addClass('pin-top');
          }

          // Add pin-bottom (when scrolled position is below bottom)
          if (scrolled > options.bottom && !$(this).hasClass('pin-bottom')) {
            removePinClasses($(this));
            $(this).addClass('pin-bottom');
            $(this).css('top', options.bottom - $original_offset);
          }
        });
      }
      $(this).data('pushpin-id', $uniqueId);
      updateElements($this, $(window).scrollTop());
      $(window).on('scroll.' + $uniqueId, function () {
        var $scrolled = $(window).scrollTop() + options.offset;
        updateElements($this, $scrolled);
      });
    });
  };
})(jQuery);
!function (a) {
  "use strict";

  "function" == typeof require && "object" == (typeof exports === "undefined" ? "undefined" : _typeof(exports)) ? module.exports = a() : "function" == typeof define && define.amd ? define(["velocity"], a) : a();
}(function () {
  "use strict";

  return function (a, b, c, d) {
    var e = a.Velocity;
    if (!e || !e.Utilities) return void (b.console && console.log("Velocity UI Pack: Velocity must be loaded first. Aborting."));
    var f = e.Utilities,
      g = e.version,
      h = {
        major: 1,
        minor: 1,
        patch: 0
      };
    if (function (a, b) {
      var c = [];
      return !(!a || !b) && (f.each([a, b], function (a, b) {
        var d = [];
        f.each(b, function (a, b) {
          for (; b.toString().length < 5;) b = "0" + b;
          d.push(b);
        }), c.push(d.join(""));
      }), parseFloat(c[0]) > parseFloat(c[1]));
    }(h, g)) {
      var i = "Velocity UI Pack: You need to update Velocity (velocity.js) to a newer version. Visit http://github.com/julianshapiro/velocity.";
      throw alert(i), new Error(i);
    }
    e.RegisterEffect = e.RegisterUI = function (a, b) {
      function c(a, b, c, d) {
        var g,
          h = 0;
        f.each(a.nodeType ? [a] : a, function (a, b) {
          d && (c += a * d), g = b.parentNode;
          var i = ["height", "paddingTop", "paddingBottom", "marginTop", "marginBottom"];
          "border-box" === e.CSS.getPropertyValue(b, "boxSizing").toString().toLowerCase() && (i = ["height"]), f.each(i, function (a, c) {
            h += parseFloat(e.CSS.getPropertyValue(b, c));
          });
        }), e.animate(g, {
          height: ("In" === b ? "+" : "-") + "=" + h
        }, {
          queue: !1,
          easing: "ease-in-out",
          duration: c * ("In" === b ? .6 : 1)
        });
      }
      return e.Redirects[a] = function (d, g, h, i, j, k, l) {
        var m = h === i - 1,
          n = 0;
        l = l || b.loop, "function" == typeof b.defaultDuration ? b.defaultDuration = b.defaultDuration.call(j, j) : b.defaultDuration = parseFloat(b.defaultDuration);
        for (var o = 0; o < b.calls.length; o++) "number" == typeof (t = b.calls[o][1]) && (n += t);
        var p = n >= 1 ? 0 : b.calls.length ? (1 - n) / b.calls.length : 1;
        for (o = 0; o < b.calls.length; o++) {
          var q = b.calls[o],
            r = q[0],
            s = 1e3,
            t = q[1],
            u = q[2] || {},
            v = {};
          if (void 0 !== g.duration ? s = g.duration : void 0 !== b.defaultDuration && (s = b.defaultDuration), v.duration = s * ("number" == typeof t ? t : p), v.queue = g.queue || "", v.easing = u.easing || "ease", v.delay = parseFloat(u.delay) || 0, v.loop = !b.loop && u.loop, v._cacheValues = u._cacheValues || !0, 0 === o) {
            if (v.delay += parseFloat(g.delay) || 0, 0 === h && (v.begin = function () {
              g.begin && g.begin.call(j, j);
              var b = a.match(/(In|Out)$/);
              b && "In" === b[0] && void 0 !== r.opacity && f.each(j.nodeType ? [j] : j, function (a, b) {
                e.CSS.setPropertyValue(b, "opacity", 0);
              }), g.animateParentHeight && b && c(j, b[0], s + v.delay, g.stagger);
            }), null !== g.display) if (void 0 !== g.display && "none" !== g.display) v.display = g.display;else if (/In$/.test(a)) {
              var w = e.CSS.Values.getDisplayType(d);
              v.display = "inline" === w ? "inline-block" : w;
            }
            g.visibility && "hidden" !== g.visibility && (v.visibility = g.visibility);
          }
          if (o === b.calls.length - 1) {
            var x = function x() {
              void 0 !== g.display && "none" !== g.display || !/Out$/.test(a) || f.each(j.nodeType ? [j] : j, function (a, b) {
                e.CSS.setPropertyValue(b, "display", "none");
              }), g.complete && g.complete.call(j, j), k && k.resolver(j || d);
            };
            v.complete = function () {
              if (l && e.Redirects[a](d, g, h, i, j, k, !0 === l || Math.max(0, l - 1)), b.reset) {
                for (var c in b.reset) if (b.reset.hasOwnProperty(c)) {
                  var f = b.reset[c];
                  void 0 !== e.CSS.Hooks.registered[c] || "string" != typeof f && "number" != typeof f || (b.reset[c] = [b.reset[c], b.reset[c]]);
                }
                var n = {
                  duration: 0,
                  queue: !1
                };
                m && (n.complete = x), e.animate(d, b.reset, n);
              } else m && x();
            }, "hidden" === g.visibility && (v.visibility = g.visibility);
          }
          e.animate(d, r, v);
        }
      }, e;
    }, e.RegisterEffect.packagedEffects = {
      "callout.bounce": {
        defaultDuration: 550,
        calls: [[{
          translateY: -30
        }, .25], [{
          translateY: 0
        }, .125], [{
          translateY: -15
        }, .125], [{
          translateY: 0
        }, .25]]
      },
      "callout.shake": {
        defaultDuration: 800,
        calls: [[{
          translateX: -11
        }], [{
          translateX: 11
        }], [{
          translateX: -11
        }], [{
          translateX: 11
        }], [{
          translateX: -11
        }], [{
          translateX: 11
        }], [{
          translateX: -11
        }], [{
          translateX: 0
        }]]
      },
      "callout.flash": {
        defaultDuration: 1100,
        calls: [[{
          opacity: [0, "easeInOutQuad", 1]
        }], [{
          opacity: [1, "easeInOutQuad"]
        }], [{
          opacity: [0, "easeInOutQuad"]
        }], [{
          opacity: [1, "easeInOutQuad"]
        }]]
      },
      "callout.pulse": {
        defaultDuration: 825,
        calls: [[{
          scaleX: 1.1,
          scaleY: 1.1
        }, .5, {
          easing: "easeInExpo"
        }], [{
          scaleX: 1,
          scaleY: 1
        }, .5]]
      },
      "callout.swing": {
        defaultDuration: 950,
        calls: [[{
          rotateZ: 15
        }], [{
          rotateZ: -10
        }], [{
          rotateZ: 5
        }], [{
          rotateZ: -5
        }], [{
          rotateZ: 0
        }]]
      },
      "callout.tada": {
        defaultDuration: 1e3,
        calls: [[{
          scaleX: .9,
          scaleY: .9,
          rotateZ: -3
        }, .1], [{
          scaleX: 1.1,
          scaleY: 1.1,
          rotateZ: 3
        }, .1], [{
          scaleX: 1.1,
          scaleY: 1.1,
          rotateZ: -3
        }, .1], ["reverse", .125], ["reverse", .125], ["reverse", .125], ["reverse", .125], ["reverse", .125], [{
          scaleX: 1,
          scaleY: 1,
          rotateZ: 0
        }, .2]]
      },
      "transition.fadeIn": {
        defaultDuration: 500,
        calls: [[{
          opacity: [1, 0]
        }]]
      },
      "transition.fadeOut": {
        defaultDuration: 500,
        calls: [[{
          opacity: [0, 1]
        }]]
      },
      "transition.flipXIn": {
        defaultDuration: 700,
        calls: [[{
          opacity: [1, 0],
          transformPerspective: [800, 800],
          rotateY: [0, -55]
        }]],
        reset: {
          transformPerspective: 0
        }
      },
      "transition.flipXOut": {
        defaultDuration: 700,
        calls: [[{
          opacity: [0, 1],
          transformPerspective: [800, 800],
          rotateY: 55
        }]],
        reset: {
          transformPerspective: 0,
          rotateY: 0
        }
      },
      "transition.flipYIn": {
        defaultDuration: 800,
        calls: [[{
          opacity: [1, 0],
          transformPerspective: [800, 800],
          rotateX: [0, -45]
        }]],
        reset: {
          transformPerspective: 0
        }
      },
      "transition.flipYOut": {
        defaultDuration: 800,
        calls: [[{
          opacity: [0, 1],
          transformPerspective: [800, 800],
          rotateX: 25
        }]],
        reset: {
          transformPerspective: 0,
          rotateX: 0
        }
      },
      "transition.flipBounceXIn": {
        defaultDuration: 900,
        calls: [[{
          opacity: [.725, 0],
          transformPerspective: [400, 400],
          rotateY: [-10, 90]
        }, .5], [{
          opacity: .8,
          rotateY: 10
        }, .25], [{
          opacity: 1,
          rotateY: 0
        }, .25]],
        reset: {
          transformPerspective: 0
        }
      },
      "transition.flipBounceXOut": {
        defaultDuration: 800,
        calls: [[{
          opacity: [.9, 1],
          transformPerspective: [400, 400],
          rotateY: -10
        }], [{
          opacity: 0,
          rotateY: 90
        }]],
        reset: {
          transformPerspective: 0,
          rotateY: 0
        }
      },
      "transition.flipBounceYIn": {
        defaultDuration: 850,
        calls: [[{
          opacity: [.725, 0],
          transformPerspective: [400, 400],
          rotateX: [-10, 90]
        }, .5], [{
          opacity: .8,
          rotateX: 10
        }, .25], [{
          opacity: 1,
          rotateX: 0
        }, .25]],
        reset: {
          transformPerspective: 0
        }
      },
      "transition.flipBounceYOut": {
        defaultDuration: 800,
        calls: [[{
          opacity: [.9, 1],
          transformPerspective: [400, 400],
          rotateX: -15
        }], [{
          opacity: 0,
          rotateX: 90
        }]],
        reset: {
          transformPerspective: 0,
          rotateX: 0
        }
      },
      "transition.swoopIn": {
        defaultDuration: 850,
        calls: [[{
          opacity: [1, 0],
          transformOriginX: ["100%", "50%"],
          transformOriginY: ["100%", "100%"],
          scaleX: [1, 0],
          scaleY: [1, 0],
          translateX: [0, -700],
          translateZ: 0
        }]],
        reset: {
          transformOriginX: "50%",
          transformOriginY: "50%"
        }
      },
      "transition.swoopOut": {
        defaultDuration: 850,
        calls: [[{
          opacity: [0, 1],
          transformOriginX: ["50%", "100%"],
          transformOriginY: ["100%", "100%"],
          scaleX: 0,
          scaleY: 0,
          translateX: -700,
          translateZ: 0
        }]],
        reset: {
          transformOriginX: "50%",
          transformOriginY: "50%",
          scaleX: 1,
          scaleY: 1,
          translateX: 0
        }
      },
      "transition.whirlIn": {
        defaultDuration: 850,
        calls: [[{
          opacity: [1, 0],
          transformOriginX: ["50%", "50%"],
          transformOriginY: ["50%", "50%"],
          scaleX: [1, 0],
          scaleY: [1, 0],
          rotateY: [0, 160]
        }, 1, {
          easing: "easeInOutSine"
        }]]
      },
      "transition.whirlOut": {
        defaultDuration: 750,
        calls: [[{
          opacity: [0, "easeInOutQuint", 1],
          transformOriginX: ["50%", "50%"],
          transformOriginY: ["50%", "50%"],
          scaleX: 0,
          scaleY: 0,
          rotateY: 160
        }, 1, {
          easing: "swing"
        }]],
        reset: {
          scaleX: 1,
          scaleY: 1,
          rotateY: 0
        }
      },
      "transition.shrinkIn": {
        defaultDuration: 750,
        calls: [[{
          opacity: [1, 0],
          transformOriginX: ["50%", "50%"],
          transformOriginY: ["50%", "50%"],
          scaleX: [1, 1.5],
          scaleY: [1, 1.5],
          translateZ: 0
        }]]
      },
      "transition.shrinkOut": {
        defaultDuration: 600,
        calls: [[{
          opacity: [0, 1],
          transformOriginX: ["50%", "50%"],
          transformOriginY: ["50%", "50%"],
          scaleX: 1.3,
          scaleY: 1.3,
          translateZ: 0
        }]],
        reset: {
          scaleX: 1,
          scaleY: 1
        }
      },
      "transition.expandIn": {
        defaultDuration: 700,
        calls: [[{
          opacity: [1, 0],
          transformOriginX: ["50%", "50%"],
          transformOriginY: ["50%", "50%"],
          scaleX: [1, .625],
          scaleY: [1, .625],
          translateZ: 0
        }]]
      },
      "transition.expandOut": {
        defaultDuration: 700,
        calls: [[{
          opacity: [0, 1],
          transformOriginX: ["50%", "50%"],
          transformOriginY: ["50%", "50%"],
          scaleX: .5,
          scaleY: .5,
          translateZ: 0
        }]],
        reset: {
          scaleX: 1,
          scaleY: 1
        }
      },
      "transition.bounceIn": {
        defaultDuration: 800,
        calls: [[{
          opacity: [1, 0],
          scaleX: [1.05, .3],
          scaleY: [1.05, .3]
        }, .35], [{
          scaleX: .9,
          scaleY: .9,
          translateZ: 0
        }, .2], [{
          scaleX: 1,
          scaleY: 1
        }, .45]]
      },
      "transition.bounceOut": {
        defaultDuration: 800,
        calls: [[{
          scaleX: .95,
          scaleY: .95
        }, .35], [{
          scaleX: 1.1,
          scaleY: 1.1,
          translateZ: 0
        }, .35], [{
          opacity: [0, 1],
          scaleX: .3,
          scaleY: .3
        }, .3]],
        reset: {
          scaleX: 1,
          scaleY: 1
        }
      },
      "transition.bounceUpIn": {
        defaultDuration: 800,
        calls: [[{
          opacity: [1, 0],
          translateY: [-30, 1e3]
        }, .6, {
          easing: "easeOutCirc"
        }], [{
          translateY: 10
        }, .2], [{
          translateY: 0
        }, .2]]
      },
      "transition.bounceUpOut": {
        defaultDuration: 1e3,
        calls: [[{
          translateY: 20
        }, .2], [{
          opacity: [0, "easeInCirc", 1],
          translateY: -1e3
        }, .8]],
        reset: {
          translateY: 0
        }
      },
      "transition.bounceDownIn": {
        defaultDuration: 800,
        calls: [[{
          opacity: [1, 0],
          translateY: [30, -1e3]
        }, .6, {
          easing: "easeOutCirc"
        }], [{
          translateY: -10
        }, .2], [{
          translateY: 0
        }, .2]]
      },
      "transition.bounceDownOut": {
        defaultDuration: 1e3,
        calls: [[{
          translateY: -20
        }, .2], [{
          opacity: [0, "easeInCirc", 1],
          translateY: 1e3
        }, .8]],
        reset: {
          translateY: 0
        }
      },
      "transition.bounceLeftIn": {
        defaultDuration: 750,
        calls: [[{
          opacity: [1, 0],
          translateX: [30, -1250]
        }, .6, {
          easing: "easeOutCirc"
        }], [{
          translateX: -10
        }, .2], [{
          translateX: 0
        }, .2]]
      },
      "transition.bounceLeftOut": {
        defaultDuration: 750,
        calls: [[{
          translateX: 30
        }, .2], [{
          opacity: [0, "easeInCirc", 1],
          translateX: -1250
        }, .8]],
        reset: {
          translateX: 0
        }
      },
      "transition.bounceRightIn": {
        defaultDuration: 750,
        calls: [[{
          opacity: [1, 0],
          translateX: [-30, 1250]
        }, .6, {
          easing: "easeOutCirc"
        }], [{
          translateX: 10
        }, .2], [{
          translateX: 0
        }, .2]]
      },
      "transition.bounceRightOut": {
        defaultDuration: 750,
        calls: [[{
          translateX: -30
        }, .2], [{
          opacity: [0, "easeInCirc", 1],
          translateX: 1250
        }, .8]],
        reset: {
          translateX: 0
        }
      },
      "transition.slideUpIn": {
        defaultDuration: 900,
        calls: [[{
          opacity: [1, 0],
          translateY: [0, 20],
          translateZ: 0
        }]]
      },
      "transition.slideUpOut": {
        defaultDuration: 900,
        calls: [[{
          opacity: [0, 1],
          translateY: -20,
          translateZ: 0
        }]],
        reset: {
          translateY: 0
        }
      },
      "transition.slideDownIn": {
        defaultDuration: 900,
        calls: [[{
          opacity: [1, 0],
          translateY: [0, -20],
          translateZ: 0
        }]]
      },
      "transition.slideDownOut": {
        defaultDuration: 900,
        calls: [[{
          opacity: [0, 1],
          translateY: 20,
          translateZ: 0
        }]],
        reset: {
          translateY: 0
        }
      },
      "transition.slideLeftIn": {
        defaultDuration: 1e3,
        calls: [[{
          opacity: [1, 0],
          translateX: [0, -20],
          translateZ: 0
        }]]
      },
      "transition.slideLeftOut": {
        defaultDuration: 1050,
        calls: [[{
          opacity: [0, 1],
          translateX: -20,
          translateZ: 0
        }]],
        reset: {
          translateX: 0
        }
      },
      "transition.slideRightIn": {
        defaultDuration: 1e3,
        calls: [[{
          opacity: [1, 0],
          translateX: [0, 20],
          translateZ: 0
        }]]
      },
      "transition.slideRightOut": {
        defaultDuration: 1050,
        calls: [[{
          opacity: [0, 1],
          translateX: 20,
          translateZ: 0
        }]],
        reset: {
          translateX: 0
        }
      },
      "transition.slideUpBigIn": {
        defaultDuration: 850,
        calls: [[{
          opacity: [1, 0],
          translateY: [0, 75],
          translateZ: 0
        }]]
      },
      "transition.slideUpBigOut": {
        defaultDuration: 800,
        calls: [[{
          opacity: [0, 1],
          translateY: -75,
          translateZ: 0
        }]],
        reset: {
          translateY: 0
        }
      },
      "transition.slideDownBigIn": {
        defaultDuration: 850,
        calls: [[{
          opacity: [1, 0],
          translateY: [0, -75],
          translateZ: 0
        }]]
      },
      "transition.slideDownBigOut": {
        defaultDuration: 800,
        calls: [[{
          opacity: [0, 1],
          translateY: 75,
          translateZ: 0
        }]],
        reset: {
          translateY: 0
        }
      },
      "transition.slideLeftBigIn": {
        defaultDuration: 800,
        calls: [[{
          opacity: [1, 0],
          translateX: [0, -75],
          translateZ: 0
        }]]
      },
      "transition.slideLeftBigOut": {
        defaultDuration: 750,
        calls: [[{
          opacity: [0, 1],
          translateX: -75,
          translateZ: 0
        }]],
        reset: {
          translateX: 0
        }
      },
      "transition.slideRightBigIn": {
        defaultDuration: 800,
        calls: [[{
          opacity: [1, 0],
          translateX: [0, 75],
          translateZ: 0
        }]]
      },
      "transition.slideRightBigOut": {
        defaultDuration: 750,
        calls: [[{
          opacity: [0, 1],
          translateX: 75,
          translateZ: 0
        }]],
        reset: {
          translateX: 0
        }
      },
      "transition.perspectiveUpIn": {
        defaultDuration: 800,
        calls: [[{
          opacity: [1, 0],
          transformPerspective: [800, 800],
          transformOriginX: [0, 0],
          transformOriginY: ["100%", "100%"],
          rotateX: [0, -180]
        }]],
        reset: {
          transformPerspective: 0,
          transformOriginX: "50%",
          transformOriginY: "50%"
        }
      },
      "transition.perspectiveUpOut": {
        defaultDuration: 850,
        calls: [[{
          opacity: [0, 1],
          transformPerspective: [800, 800],
          transformOriginX: [0, 0],
          transformOriginY: ["100%", "100%"],
          rotateX: -180
        }]],
        reset: {
          transformPerspective: 0,
          transformOriginX: "50%",
          transformOriginY: "50%",
          rotateX: 0
        }
      },
      "transition.perspectiveDownIn": {
        defaultDuration: 800,
        calls: [[{
          opacity: [1, 0],
          transformPerspective: [800, 800],
          transformOriginX: [0, 0],
          transformOriginY: [0, 0],
          rotateX: [0, 180]
        }]],
        reset: {
          transformPerspective: 0,
          transformOriginX: "50%",
          transformOriginY: "50%"
        }
      },
      "transition.perspectiveDownOut": {
        defaultDuration: 850,
        calls: [[{
          opacity: [0, 1],
          transformPerspective: [800, 800],
          transformOriginX: [0, 0],
          transformOriginY: [0, 0],
          rotateX: 180
        }]],
        reset: {
          transformPerspective: 0,
          transformOriginX: "50%",
          transformOriginY: "50%",
          rotateX: 0
        }
      },
      "transition.perspectiveLeftIn": {
        defaultDuration: 950,
        calls: [[{
          opacity: [1, 0],
          transformPerspective: [2e3, 2e3],
          transformOriginX: [0, 0],
          transformOriginY: [0, 0],
          rotateY: [0, -180]
        }]],
        reset: {
          transformPerspective: 0,
          transformOriginX: "50%",
          transformOriginY: "50%"
        }
      },
      "transition.perspectiveLeftOut": {
        defaultDuration: 950,
        calls: [[{
          opacity: [0, 1],
          transformPerspective: [2e3, 2e3],
          transformOriginX: [0, 0],
          transformOriginY: [0, 0],
          rotateY: -180
        }]],
        reset: {
          transformPerspective: 0,
          transformOriginX: "50%",
          transformOriginY: "50%",
          rotateY: 0
        }
      },
      "transition.perspectiveRightIn": {
        defaultDuration: 950,
        calls: [[{
          opacity: [1, 0],
          transformPerspective: [2e3, 2e3],
          transformOriginX: ["100%", "100%"],
          transformOriginY: [0, 0],
          rotateY: [0, 180]
        }]],
        reset: {
          transformPerspective: 0,
          transformOriginX: "50%",
          transformOriginY: "50%"
        }
      },
      "transition.perspectiveRightOut": {
        defaultDuration: 950,
        calls: [[{
          opacity: [0, 1],
          transformPerspective: [2e3, 2e3],
          transformOriginX: ["100%", "100%"],
          transformOriginY: [0, 0],
          rotateY: 180
        }]],
        reset: {
          transformPerspective: 0,
          transformOriginX: "50%",
          transformOriginY: "50%",
          rotateY: 0
        }
      }
    };
    for (var j in e.RegisterEffect.packagedEffects) e.RegisterEffect.packagedEffects.hasOwnProperty(j) && e.RegisterEffect(j, e.RegisterEffect.packagedEffects[j]);
    e.RunSequence = function (a) {
      var b = f.extend(!0, [], a);
      b.length > 1 && (f.each(b.reverse(), function (a, c) {
        var d = b[a + 1];
        if (d) {
          var g = c.o || c.options,
            h = d.o || d.options,
            i = g && !1 === g.sequenceQueue ? "begin" : "complete",
            j = h && h[i],
            k = {};
          k[i] = function () {
            var a = d.e || d.elements,
              b = a.nodeType ? [a] : a;
            j && j.call(b, b), e(c);
          }, d.o ? d.o = f.extend({}, h, k) : d.options = f.extend({}, h, k);
        }
      }), b.reverse()), e(b[0]);
    };
  }(window.jQuery || window.Zepto || window, window, window ? window.document : undefined);
});
/*
	Colorbox 1.6.4
	license: MIT
	http://www.jacklmoore.com/colorbox
*/
(function ($, document, window) {
  var
    // Default settings object.
    // See http://jacklmoore.com/colorbox for details.
    defaults = {
      // data sources
      html: false,
      photo: false,
      iframe: false,
      inline: false,
      // behavior and appearance
      transition: "elastic",
      speed: 300,
      fadeOut: 300,
      width: false,
      initialWidth: "600",
      innerWidth: false,
      maxWidth: false,
      height: false,
      initialHeight: "450",
      innerHeight: false,
      maxHeight: false,
      scalePhotos: true,
      scrolling: true,
      opacity: 0.9,
      preloading: true,
      className: false,
      overlayClose: true,
      escKey: true,
      arrowKey: true,
      top: false,
      bottom: false,
      left: false,
      right: false,
      fixed: false,
      data: undefined,
      closeButton: true,
      fastIframe: true,
      open: false,
      reposition: true,
      loop: true,
      slideshow: false,
      slideshowAuto: true,
      slideshowSpeed: 2500,
      slideshowStart: "start slideshow",
      slideshowStop: "stop slideshow",
      photoRegex: /\.(gif|png|jp(e|g|eg)|bmp|ico|webp|jxr|svg)((#|\?).*)?$/i,
      // alternate image paths for high-res displays
      retinaImage: false,
      retinaUrl: false,
      retinaSuffix: '@2x.$1',
      // internationalization
      current: "imagen {current} de {total}",
      previous: "previous",
      next: "next",
      close: "close",
      xhrError: "This content failed to load.",
      imgError: "This image failed to load.",
      // accessbility
      returnFocus: true,
      trapFocus: true,
      // callbacks
      onOpen: false,
      onLoad: false,
      onComplete: false,
      onCleanup: false,
      onClosed: false,
      rel: function rel() {
        return this.rel;
      },
      href: function href() {
        // using this.href would give the absolute url, when the href may have been inteded as a selector (e.g. '#container')
        return $(this).attr('href');
      },
      title: function title() {
        return this.title;
      },
      createImg: function createImg() {
        var img = new Image();
        var attrs = $(this).data('cbox-img-attrs');
        if (_typeof(attrs) === 'object') {
          $.each(attrs, function (key, val) {
            img[key] = val;
          });
        }
        return img;
      },
      createIframe: function createIframe() {
        var iframe = document.createElement('iframe');
        var attrs = $(this).data('cbox-iframe-attrs');
        if (_typeof(attrs) === 'object') {
          $.each(attrs, function (key, val) {
            iframe[key] = val;
          });
        }
        if ('frameBorder' in iframe) {
          iframe.frameBorder = 0;
        }
        if ('allowTransparency' in iframe) {
          iframe.allowTransparency = "true";
        }
        iframe.name = new Date().getTime(); // give the iframe a unique name to prevent caching
        iframe.allowFullscreen = true;
        return iframe;
      }
    },
    // Abstracting the HTML and event identifiers for easy rebranding
    colorbox = 'colorbox',
    prefix = 'cbox',
    boxElement = prefix + 'Element',
    // Events
    event_open = prefix + '_open',
    event_load = prefix + '_load',
    event_complete = prefix + '_complete',
    event_cleanup = prefix + '_cleanup',
    event_closed = prefix + '_closed',
    event_purge = prefix + '_purge',
    // Cached jQuery Object Variables
    $overlay,
    $box,
    $wrap,
    $content,
    $topBorder,
    $leftBorder,
    $rightBorder,
    $bottomBorder,
    $related,
    $window,
    $loaded,
    $loadingBay,
    $loadingOverlay,
    $title,
    $current,
    $slideshow,
    $next,
    $prev,
    $close,
    $groupControls,
    $events = $('<a/>'),
    // $({}) would be preferred, but there is an issue with jQuery 1.4.2

    // Variables for cached values or use across multiple functions
    settings,
    interfaceHeight,
    interfaceWidth,
    loadedHeight,
    loadedWidth,
    index,
    photo,
    open,
    active,
    closing,
    loadingTimer,
    publicMethod,
    div = "div",
    requests = 0,
    previousCSS = {},
    init;

  // ****************
  // HELPER FUNCTIONS
  // ****************

  // Convenience function for creating new jQuery objects
  function $tag(tag, id, css) {
    var element = document.createElement(tag);
    if (id) {
      element.id = prefix + id;
    }
    if (css) {
      element.style.cssText = css;
    }
    return $(element);
  }

  // Get the window height using innerHeight when available to avoid an issue with iOS
  // http://bugs.jquery.com/ticket/6724
  function winheight() {
    return window.innerHeight ? window.innerHeight : $(window).height();
  }
  function Settings(element, options) {
    if (options !== Object(options)) {
      options = {};
    }
    this.cache = {};
    this.el = element;
    this.value = function (key) {
      var dataAttr;
      if (this.cache[key] === undefined) {
        dataAttr = $(this.el).attr('data-cbox-' + key);
        if (dataAttr !== undefined) {
          this.cache[key] = dataAttr;
        } else if (options[key] !== undefined) {
          this.cache[key] = options[key];
        } else if (defaults[key] !== undefined) {
          this.cache[key] = defaults[key];
        }
      }
      return this.cache[key];
    };
    this.get = function (key) {
      var value = this.value(key);
      return $.isFunction(value) ? value.call(this.el, this) : value;
    };
  }

  // Determine the next and previous members in a group.
  function getIndex(increment) {
    var max = $related.length,
      newIndex = (index + increment) % max;
    return newIndex < 0 ? max + newIndex : newIndex;
  }

  // Convert '%' and 'px' values to integers
  function setSize(size, dimension) {
    return Math.round((/%/.test(size) ? (dimension === 'x' ? $window.width() : winheight()) / 100 : 1) * parseInt(size, 10));
  }

  // Checks an href to see if it is a photo.
  // There is a force photo option (photo: true) for hrefs that cannot be matched by the regex.
  function isImage(settings, url) {
    return settings.get('photo') || settings.get('photoRegex').test(url);
  }
  function retinaUrl(settings, url) {
    return settings.get('retinaUrl') && window.devicePixelRatio > 1 ? url.replace(settings.get('photoRegex'), settings.get('retinaSuffix')) : url;
  }
  function trapFocus(e) {
    if ('contains' in $box[0] && !$box[0].contains(e.target) && e.target !== $overlay[0]) {
      e.stopPropagation();
      $box.focus();
    }
  }
  function setClass(str) {
    if (setClass.str !== str) {
      $box.add($overlay).removeClass(setClass.str).addClass(str);
      setClass.str = str;
    }
  }
  function getRelated(rel) {
    index = 0;
    if (rel && rel !== false && rel !== 'nofollow') {
      $related = $('.' + boxElement).filter(function () {
        var options = $.data(this, colorbox);
        var settings = new Settings(this, options);
        return settings.get('rel') === rel;
      });
      index = $related.index(settings.el);

      // Check direct calls to Colorbox.
      if (index === -1) {
        $related = $related.add(settings.el);
        index = $related.length - 1;
      }
    } else {
      $related = $(settings.el);
    }
  }
  function trigger(event) {
    // for external use
    $(document).trigger(event);
    // for internal use
    $events.triggerHandler(event);
  }
  var slideshow = function () {
    var active,
      className = prefix + "Slideshow_",
      click = "click." + prefix,
      timeOut;
    function clear() {
      clearTimeout(timeOut);
    }
    function set() {
      if (settings.get('loop') || $related[index + 1]) {
        clear();
        timeOut = setTimeout(publicMethod.next, settings.get('slideshowSpeed'));
      }
    }
    function start() {
      $slideshow.html(settings.get('slideshowStop')).unbind(click).one(click, stop);
      $events.bind(event_complete, set).bind(event_load, clear);
      $box.removeClass(className + "off").addClass(className + "on");
    }
    function stop() {
      clear();
      $events.unbind(event_complete, set).unbind(event_load, clear);
      $slideshow.html(settings.get('slideshowStart')).unbind(click).one(click, function () {
        publicMethod.next();
        start();
      });
      $box.removeClass(className + "on").addClass(className + "off");
    }
    function reset() {
      active = false;
      $slideshow.hide();
      clear();
      $events.unbind(event_complete, set).unbind(event_load, clear);
      $box.removeClass(className + "off " + className + "on");
    }
    return function () {
      if (active) {
        if (!settings.get('slideshow')) {
          $events.unbind(event_cleanup, reset);
          reset();
        }
      } else {
        if (settings.get('slideshow') && $related[1]) {
          active = true;
          $events.one(event_cleanup, reset);
          if (settings.get('slideshowAuto')) {
            start();
          } else {
            stop();
          }
          $slideshow.show();
        }
      }
    };
  }();
  function launch(element) {
    var options;
    if (!closing) {
      options = $(element).data(colorbox);
      settings = new Settings(element, options);
      getRelated(settings.get('rel'));
      if (!open) {
        open = active = true; // Prevents the page-change action from queuing up if the visitor holds down the left or right keys.

        setClass(settings.get('className'));

        // Show colorbox so the sizes can be calculated in older versions of jQuery
        $box.css({
          visibility: 'hidden',
          display: 'block',
          opacity: ''
        });
        $loaded = $tag(div, 'LoadedContent', 'width:0; height:0; overflow:hidden; visibility:hidden');
        $content.css({
          width: '',
          height: ''
        }).append($loaded);

        // Cache values needed for size calculations
        interfaceHeight = $topBorder.height() + $bottomBorder.height() + $content.outerHeight(true) - $content.height();
        interfaceWidth = $leftBorder.width() + $rightBorder.width() + $content.outerWidth(true) - $content.width();
        loadedHeight = $loaded.outerHeight(true);
        loadedWidth = $loaded.outerWidth(true);

        // Opens inital empty Colorbox prior to content being loaded.
        var initialWidth = setSize(settings.get('initialWidth'), 'x');
        var initialHeight = setSize(settings.get('initialHeight'), 'y');
        var maxWidth = settings.get('maxWidth');
        var maxHeight = settings.get('maxHeight');
        settings.w = Math.max((maxWidth !== false ? Math.min(initialWidth, setSize(maxWidth, 'x')) : initialWidth) - loadedWidth - interfaceWidth, 0);
        settings.h = Math.max((maxHeight !== false ? Math.min(initialHeight, setSize(maxHeight, 'y')) : initialHeight) - loadedHeight - interfaceHeight, 0);
        $loaded.css({
          width: '',
          height: settings.h
        });
        publicMethod.position();
        trigger(event_open);
        settings.get('onOpen');
        $groupControls.add($title).hide();
        $box.focus();
        if (settings.get('trapFocus')) {
          // Confine focus to the modal
          // Uses event capturing that is not supported in IE8-
          if (document.addEventListener) {
            document.addEventListener('focus', trapFocus, true);
            $events.one(event_closed, function () {
              document.removeEventListener('focus', trapFocus, true);
            });
          }
        }

        // Return focus on closing
        if (settings.get('returnFocus')) {
          $events.one(event_closed, function () {
            $(settings.el).focus();
          });
        }
      }
      var opacity = parseFloat(settings.get('opacity'));
      $overlay.css({
        opacity: opacity === opacity ? opacity : '',
        cursor: settings.get('overlayClose') ? 'pointer' : '',
        visibility: 'visible'
      }).show();
      if (settings.get('closeButton')) {
        $close.html(settings.get('close')).appendTo($content);
      } else {
        $close.appendTo('<div/>'); // replace with .detach() when dropping jQuery < 1.4
      }

      load();
    }
  }

  // Colorbox's markup needs to be added to the DOM prior to being called
  // so that the browser will go ahead and load the CSS background images.
  function appendHTML() {
    if (!$box) {
      init = false;
      $window = $(window);
      $box = $tag(div).attr({
        id: colorbox,
        'class': $.support.opacity === false ? prefix + 'IE' : '',
        // class for optional IE8 & lower targeted CSS.
        role: 'dialog',
        tabindex: '-1'
      }).hide();
      $overlay = $tag(div, "Overlay").hide();
      $loadingOverlay = $([$tag(div, "LoadingOverlay")[0], $tag(div, "LoadingGraphic")[0]]);
      $wrap = $tag(div, "Wrapper");
      $content = $tag(div, "Content").append($title = $tag(div, "Title"), $current = $tag(div, "Current"), $prev = $('<button type="button"/>').attr({
        id: prefix + 'Previous'
      }), $next = $('<button type="button"/>').attr({
        id: prefix + 'Next'
      }), $slideshow = $('<button type="button"/>').attr({
        id: prefix + 'Slideshow'
      }), $loadingOverlay);
      $close = $('<button type="button"/>').attr({
        id: prefix + 'Close'
      });
      $wrap.append(
      // The 3x3 Grid that makes up Colorbox
      $tag(div).append($tag(div, "TopLeft"), $topBorder = $tag(div, "TopCenter"), $tag(div, "TopRight")), $tag(div, false, 'clear:left').append($leftBorder = $tag(div, "MiddleLeft"), $content, $rightBorder = $tag(div, "MiddleRight")), $tag(div, false, 'clear:left').append($tag(div, "BottomLeft"), $bottomBorder = $tag(div, "BottomCenter"), $tag(div, "BottomRight"))).find('div div').css({
        'float': 'left'
      });
      $loadingBay = $tag(div, false, 'position:absolute; width:9999px; visibility:hidden; display:none; max-width:none;');
      $groupControls = $next.add($prev).add($current).add($slideshow);
    }
    if (document.body && !$box.parent().length) {
      $(document.body).append($overlay, $box.append($wrap, $loadingBay));
    }
  }

  // Add Colorbox's event bindings
  function addBindings() {
    function clickHandler(e) {
      // ignore non-left-mouse-clicks and clicks modified with ctrl / command, shift, or alt.
      // See: http://jacklmoore.com/notes/click-events/
      if (!(e.which > 1 || e.shiftKey || e.altKey || e.metaKey || e.ctrlKey)) {
        e.preventDefault();
        launch(this);
      }
    }
    if ($box) {
      if (!init) {
        init = true;

        // Anonymous functions here keep the public method from being cached, thereby allowing them to be redefined on the fly.
        $next.click(function () {
          publicMethod.next();
        });
        $prev.click(function () {
          publicMethod.prev();
        });
        $close.click(function () {
          publicMethod.close();
        });
        $overlay.click(function () {
          if (settings.get('overlayClose')) {
            publicMethod.close();
          }
        });

        // Key Bindings
        $(document).bind('keydown.' + prefix, function (e) {
          var key = e.keyCode;
          if (open && settings.get('escKey') && key === 27) {
            e.preventDefault();
            publicMethod.close();
          }
          if (open && settings.get('arrowKey') && $related[1] && !e.altKey) {
            if (key === 37) {
              e.preventDefault();
              $prev.click();
            } else if (key === 39) {
              e.preventDefault();
              $next.click();
            }
          }
        });
        if ($.isFunction($.fn.on)) {
          // For jQuery 1.7+
          $(document).on('click.' + prefix, '.' + boxElement, clickHandler);
        } else {
          // For jQuery 1.3.x -> 1.6.x
          // This code is never reached in jQuery 1.9, so do not contact me about 'live' being removed.
          // This is not here for jQuery 1.9, it's here for legacy users.
          $('.' + boxElement).live('click.' + prefix, clickHandler);
        }
      }
      return true;
    }
    return false;
  }

  // Don't do anything if Colorbox already exists.
  if ($[colorbox]) {
    return;
  }

  // Append the HTML when the DOM loads
  $(appendHTML);

  // ****************
  // PUBLIC FUNCTIONS
  // Usage format: $.colorbox.close();
  // Usage from within an iframe: parent.jQuery.colorbox.close();
  // ****************

  publicMethod = $.fn[colorbox] = $[colorbox] = function (options, callback) {
    var settings;
    var $obj = this;
    options = options || {};
    if ($.isFunction($obj)) {
      // assume a call to $.colorbox
      $obj = $('<a/>');
      options.open = true;
    }
    if (!$obj[0]) {
      // colorbox being applied to empty collection
      return $obj;
    }
    appendHTML();
    if (addBindings()) {
      if (callback) {
        options.onComplete = callback;
      }
      $obj.each(function () {
        var old = $.data(this, colorbox) || {};
        $.data(this, colorbox, $.extend(old, options));
      }).addClass(boxElement);
      settings = new Settings($obj[0], options);
      if (settings.get('open')) {
        launch($obj[0]);
      }
    }
    return $obj;
  };
  publicMethod.position = function (speed, loadedCallback) {
    var css,
      top = 0,
      left = 0,
      offset = $box.offset(),
      scrollTop,
      scrollLeft;
    $window.unbind('resize.' + prefix);

    // remove the modal so that it doesn't influence the document width/height
    $box.css({
      top: -9e4,
      left: -9e4
    });
    scrollTop = $window.scrollTop();
    scrollLeft = $window.scrollLeft();
    if (settings.get('fixed')) {
      offset.top -= scrollTop;
      offset.left -= scrollLeft;
      $box.css({
        position: 'fixed'
      });
    } else {
      top = scrollTop;
      left = scrollLeft;
      $box.css({
        position: 'absolute'
      });
    }

    // keeps the top and left positions within the browser's viewport.
    if (settings.get('right') !== false) {
      left += Math.max($window.width() - settings.w - loadedWidth - interfaceWidth - setSize(settings.get('right'), 'x'), 0);
    } else if (settings.get('left') !== false) {
      left += setSize(settings.get('left'), 'x');
    } else {
      left += Math.round(Math.max($window.width() - settings.w - loadedWidth - interfaceWidth, 0) / 2);
    }
    if (settings.get('bottom') !== false) {
      top += Math.max(winheight() - settings.h - loadedHeight - interfaceHeight - setSize(settings.get('bottom'), 'y'), 0);
    } else if (settings.get('top') !== false) {
      top += setSize(settings.get('top'), 'y');
    } else {
      top += Math.round(Math.max(winheight() - settings.h - loadedHeight - interfaceHeight, 0) / 2);
    }
    $box.css({
      top: offset.top,
      left: offset.left,
      visibility: 'visible'
    });

    // this gives the wrapper plenty of breathing room so it's floated contents can move around smoothly,
    // but it has to be shrank down around the size of div#colorbox when it's done.  If not,
    // it can invoke an obscure IE bug when using iframes.
    $wrap[0].style.width = $wrap[0].style.height = "9999px";
    function modalDimensions() {
      $topBorder[0].style.width = $bottomBorder[0].style.width = $content[0].style.width = parseInt($box[0].style.width, 10) - interfaceWidth + 'px';
      $content[0].style.height = $leftBorder[0].style.height = $rightBorder[0].style.height = parseInt($box[0].style.height, 10) - interfaceHeight + 'px';
    }
    css = {
      width: settings.w + loadedWidth + interfaceWidth,
      height: settings.h + loadedHeight + interfaceHeight,
      top: top,
      left: left
    };

    // setting the speed to 0 if the content hasn't changed size or position
    if (speed) {
      var tempSpeed = 0;
      $.each(css, function (i) {
        if (css[i] !== previousCSS[i]) {
          tempSpeed = speed;
          return;
        }
      });
      speed = tempSpeed;
    }
    previousCSS = css;
    if (!speed) {
      $box.css(css);
    }
    $box.dequeue().animate(css, {
      duration: speed || 0,
      complete: function complete() {
        modalDimensions();
        active = false;

        // shrink the wrapper down to exactly the size of colorbox to avoid a bug in IE's iframe implementation.
        $wrap[0].style.width = settings.w + loadedWidth + interfaceWidth + "px";
        $wrap[0].style.height = settings.h + loadedHeight + interfaceHeight + "px";
        if (settings.get('reposition')) {
          setTimeout(function () {
            // small delay before binding onresize due to an IE8 bug.
            $window.bind('resize.' + prefix, publicMethod.position);
          }, 1);
        }
        if ($.isFunction(loadedCallback)) {
          loadedCallback();
        }
      },
      step: modalDimensions
    });
  };
  publicMethod.resize = function (options) {
    var scrolltop;
    if (open) {
      options = options || {};
      if (options.width) {
        settings.w = setSize(options.width, 'x') - loadedWidth - interfaceWidth;
      }
      if (options.innerWidth) {
        settings.w = setSize(options.innerWidth, 'x');
      }
      $loaded.css({
        width: settings.w
      });
      if (options.height) {
        settings.h = setSize(options.height, 'y') - loadedHeight - interfaceHeight;
      }
      if (options.innerHeight) {
        settings.h = setSize(options.innerHeight, 'y');
      }
      if (!options.innerHeight && !options.height) {
        scrolltop = $loaded.scrollTop();
        $loaded.css({
          height: "auto"
        });
        settings.h = $loaded.height();
      }
      $loaded.css({
        height: settings.h
      });
      if (scrolltop) {
        $loaded.scrollTop(scrolltop);
      }
      publicMethod.position(settings.get('transition') === "none" ? 0 : settings.get('speed'));
    }
  };
  publicMethod.prep = function (object) {
    if (!open) {
      return;
    }
    var callback,
      speed = settings.get('transition') === "none" ? 0 : settings.get('speed');
    $loaded.remove();
    $loaded = $tag(div, 'LoadedContent').append(object);
    function getWidth() {
      settings.w = settings.w || $loaded.width();
      settings.w = settings.mw && settings.mw < settings.w ? settings.mw : settings.w;
      return settings.w;
    }
    function getHeight() {
      settings.h = settings.h || $loaded.height();
      settings.h = settings.mh && settings.mh < settings.h ? settings.mh : settings.h;
      return settings.h;
    }
    $loaded.hide().appendTo($loadingBay.show()) // content has to be appended to the DOM for accurate size calculations.
    .css({
      width: getWidth(),
      overflow: settings.get('scrolling') ? 'auto' : 'hidden'
    }).css({
      height: getHeight()
    }) // sets the height independently from the width in case the new width influences the value of height.
    .prependTo($content);
    $loadingBay.hide();

    // floating the IMG removes the bottom line-height and fixed a problem where IE miscalculates the width of the parent element as 100% of the document width.

    $(photo).css({
      'float': 'none'
    });
    setClass(settings.get('className'));
    callback = function callback() {
      var total = $related.length,
        iframe,
        complete;
      if (!open) {
        return;
      }
      function removeFilter() {
        // Needed for IE8 in versions of jQuery prior to 1.7.2
        if ($.support.opacity === false) {
          $box[0].style.removeAttribute('filter');
        }
      }
      complete = function complete() {
        clearTimeout(loadingTimer);
        $loadingOverlay.hide();
        trigger(event_complete);
        settings.get('onComplete');
      };
      $title.html(settings.get('title')).show();
      $loaded.show();
      if (total > 1) {
        // handle grouping
        if (typeof settings.get('current') === "string") {
          $current.html(settings.get('current').replace('{current}', index + 1).replace('{total}', total)).show();
        }
        $next[settings.get('loop') || index < total - 1 ? "show" : "hide"]().html(settings.get('next'));
        $prev[settings.get('loop') || index ? "show" : "hide"]().html(settings.get('previous'));
        slideshow();

        // Preloads images within a rel group
        if (settings.get('preloading')) {
          $.each([getIndex(-1), getIndex(1)], function () {
            var img,
              i = $related[this],
              settings = new Settings(i, $.data(i, colorbox)),
              src = settings.get('href');
            if (src && isImage(settings, src)) {
              src = retinaUrl(settings, src);
              img = document.createElement('img');
              img.src = src;
            }
          });
        }
      } else {
        $groupControls.hide();
      }
      if (settings.get('iframe')) {
        iframe = settings.get('createIframe');
        if (!settings.get('scrolling')) {
          iframe.scrolling = "no";
        }
        $(iframe).attr({
          src: settings.get('href'),
          'class': prefix + 'Iframe'
        }).one('load', complete).appendTo($loaded);
        $events.one(event_purge, function () {
          iframe.src = "//about:blank";
        });
        if (settings.get('fastIframe')) {
          $(iframe).trigger('load');
        }
      } else {
        complete();
      }
      if (settings.get('transition') === 'fade') {
        $box.fadeTo(speed, 1, removeFilter);
      } else {
        removeFilter();
      }
    };
    if (settings.get('transition') === 'fade') {
      $box.fadeTo(speed, 0, function () {
        publicMethod.position(0, callback);
      });
    } else {
      publicMethod.position(speed, callback);
    }
  };
  function load() {
    var href,
      setResize,
      prep = publicMethod.prep,
      $inline,
      request = ++requests;
    active = true;
    photo = false;
    trigger(event_purge);
    trigger(event_load);
    settings.get('onLoad');
    settings.h = settings.get('height') ? setSize(settings.get('height'), 'y') - loadedHeight - interfaceHeight : settings.get('innerHeight') && setSize(settings.get('innerHeight'), 'y');
    settings.w = settings.get('width') ? setSize(settings.get('width'), 'x') - loadedWidth - interfaceWidth : settings.get('innerWidth') && setSize(settings.get('innerWidth'), 'x');

    // Sets the minimum dimensions for use in image scaling
    settings.mw = settings.w;
    settings.mh = settings.h;

    // Re-evaluate the minimum width and height based on maxWidth and maxHeight values.
    // If the width or height exceed the maxWidth or maxHeight, use the maximum values instead.
    if (settings.get('maxWidth')) {
      settings.mw = setSize(settings.get('maxWidth'), 'x') - loadedWidth - interfaceWidth;
      settings.mw = settings.w && settings.w < settings.mw ? settings.w : settings.mw;
    }
    if (settings.get('maxHeight')) {
      settings.mh = setSize(settings.get('maxHeight'), 'y') - loadedHeight - interfaceHeight;
      settings.mh = settings.h && settings.h < settings.mh ? settings.h : settings.mh;
    }
    href = settings.get('href');
    loadingTimer = setTimeout(function () {
      $loadingOverlay.show();
    }, 100);
    if (settings.get('inline')) {
      var $target = $(href).eq(0);
      // Inserts an empty placeholder where inline content is being pulled from.
      // An event is bound to put inline content back when Colorbox closes or loads new content.
      $inline = $('<div>').hide().insertBefore($target);
      $events.one(event_purge, function () {
        $inline.replaceWith($target);
      });
      prep($target);
    } else if (settings.get('iframe')) {
      // IFrame element won't be added to the DOM until it is ready to be displayed,
      // to avoid problems with DOM-ready JS that might be trying to run in that iframe.
      prep(" ");
    } else if (settings.get('html')) {
      prep(settings.get('html'));
    } else if (isImage(settings, href)) {
      href = retinaUrl(settings, href);
      photo = settings.get('createImg');
      $(photo).addClass(prefix + 'Photo').bind('error.' + prefix, function () {
        prep($tag(div, 'Error').html(settings.get('imgError')));
      }).one('load', function () {
        if (request !== requests) {
          return;
        }

        // A small pause because some browsers will occasionally report a
        // img.width and img.height of zero immediately after the img.onload fires
        setTimeout(function () {
          var percent;
          if (settings.get('retinaImage') && window.devicePixelRatio > 1) {
            photo.height = photo.height / window.devicePixelRatio;
            photo.width = photo.width / window.devicePixelRatio;
          }
          if (settings.get('scalePhotos')) {
            setResize = function setResize() {
              photo.height -= photo.height * percent;
              photo.width -= photo.width * percent;
            };
            if (settings.mw && photo.width > settings.mw) {
              percent = (photo.width - settings.mw) / photo.width;
              setResize();
            }
            if (settings.mh && photo.height > settings.mh) {
              percent = (photo.height - settings.mh) / photo.height;
              setResize();
            }
          }
          if (settings.h) {
            photo.style.marginTop = Math.max(settings.mh - photo.height, 0) / 2 + 'px';
          }
          if ($related[1] && (settings.get('loop') || $related[index + 1])) {
            photo.style.cursor = 'pointer';
            $(photo).bind('click.' + prefix, function () {
              publicMethod.next();
            });
          }
          photo.style.width = photo.width + 'px';
          photo.style.height = photo.height + 'px';
          prep(photo);
        }, 1);
      });
      photo.src = href;
    } else if (href) {
      $loadingBay.load(href, settings.get('data'), function (data, status) {
        if (request === requests) {
          prep(status === 'error' ? $tag(div, 'Error').html(settings.get('xhrError')) : $(this).contents());
        }
      });
    }
  }

  // Navigates to the next page/image in a set.
  publicMethod.next = function () {
    if (!active && $related[1] && (settings.get('loop') || $related[index + 1])) {
      index = getIndex(1);
      launch($related[index]);
    }
  };
  publicMethod.prev = function () {
    if (!active && $related[1] && (settings.get('loop') || index)) {
      index = getIndex(-1);
      launch($related[index]);
    }
  };

  // Note: to use this within an iframe use the following format: parent.jQuery.colorbox.close();
  publicMethod.close = function () {
    if (open && !closing) {
      closing = true;
      open = false;
      trigger(event_cleanup);
      settings.get('onCleanup');
      $window.unbind('.' + prefix);
      $overlay.fadeTo(settings.get('fadeOut') || 0, 0);
      $box.stop().fadeTo(settings.get('fadeOut') || 0, 0, function () {
        $box.hide();
        $overlay.hide();
        trigger(event_purge);
        $loaded.remove();
        setTimeout(function () {
          closing = false;
          trigger(event_closed);
          settings.get('onClosed');
        }, 1);
      });
    }
  };

  // Removes changes Colorbox made to the document, but does not remove the plugin.
  publicMethod.remove = function () {
    if (!$box) {
      return;
    }
    $box.stop();
    $[colorbox].close();
    $box.stop(false, true).remove();
    $overlay.remove();
    closing = false;
    $box = null;
    $('.' + boxElement).removeData(colorbox).removeClass(boxElement);
    $(document).unbind('click.' + prefix).unbind('keydown.' + prefix);
  };

  // A method for fetching the current element Colorbox is referencing.
  // returns a jQuery object.
  publicMethod.element = function () {
    return $(settings.el);
  };
  publicMethod.settings = defaults;
})(jQuery, document, window);

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
(function (p, r) {
  "use strict";

  var i = function i(t) {
    this.elem = t;
  };
  i.init = function () {
    var t = r.querySelectorAll("[data-sharer]"),
      e,
      a = t.length;
    for (e = 0; e < a; e++) {
      t[e].addEventListener("click", i.add);
    }
  };
  i.add = function (t) {
    var e = t.currentTarget || t.srcElement;
    var a = new i(e);
    a.share();
  };
  i.prototype = {
    constructor: i,
    getValue: function getValue(t) {
      var e = this.elem.getAttribute("data-" + t);
      if (!e) return;
      if (t === "hashtag") {
        if (!e.startsWith("#")) {
          e = "#" + e;
        }
      }
      return e;
    },
    share: function share() {
      var t = this.getValue("sharer").toLowerCase(),
        e = {
          facebook: {
            shareUrl: "https://www.facebook.com/sharer/sharer.php",
            params: {
              u: this.getValue("url"),
              hashtag: this.getValue("hashtag")
            }
          },
          linkedin: {
            shareUrl: "https://www.linkedin.com/shareArticle",
            params: {
              url: this.getValue("url"),
              mini: true
            }
          },
          twitter: {
            shareUrl: "https://twitter.com/intent/tweet/",
            params: {
              text: this.getValue("title"),
              url: this.getValue("url"),
              hashtags: this.getValue("hashtags"),
              via: this.getValue("via")
            }
          },
          email: {
            shareUrl: "mailto:" + this.getValue("to"),
            params: {
              subject: this.getValue("subject"),
              body: this.getValue("title") + "\n" + this.getValue("url")
            },
            isLink: true
          },
          whatsapp: {
            shareUrl: "whatsapp://send",
            params: {
              text: this.getValue("title") + " " + this.getValue("url")
            },
            isLink: true
          },
          telegram: {
            shareUrl: "tg://msg_url",
            params: {
              text: this.getValue("title") + " " + this.getValue("url")
            },
            isLink: true
          },
          viber: {
            shareUrl: "viber://forward",
            params: {
              text: this.getValue("title") + " " + this.getValue("url")
            },
            isLink: true
          },
          line: {
            shareUrl: "http://line.me/R/msg/text/?" + encodeURIComponent(this.getValue("title") + " " + this.getValue("url")),
            isLink: true
          },
          pinterest: {
            shareUrl: "https://www.pinterest.com/pin/create/button/",
            params: {
              url: this.getValue("url"),
              media: this.getValue("image"),
              description: this.getValue("description")
            }
          },
          tumblr: {
            shareUrl: "http://tumblr.com/widgets/share/tool",
            params: {
              canonicalUrl: this.getValue("url"),
              content: this.getValue("url"),
              posttype: "link",
              title: this.getValue("title"),
              caption: this.getValue("caption"),
              tags: this.getValue("tags")
            }
          },
          hackernews: {
            shareUrl: "https://news.ycombinator.com/submitlink",
            params: {
              u: this.getValue("url"),
              t: this.getValue("title")
            }
          },
          reddit: {
            shareUrl: "https://www.reddit.com/submit",
            params: {
              url: this.getValue("url")
            }
          },
          vk: {
            shareUrl: "http://vk.com/share.php",
            params: {
              url: this.getValue("url"),
              title: this.getValue("title"),
              description: this.getValue("caption"),
              image: this.getValue("image")
            }
          },
          xing: {
            shareUrl: "https://www.xing.com/app/user",
            params: {
              op: "share",
              url: this.getValue("url"),
              title: this.getValue("title")
            }
          },
          buffer: {
            shareUrl: "https://buffer.com/add",
            params: {
              url: this.getValue("url"),
              title: this.getValue("title"),
              via: this.getValue("via"),
              picture: this.getValue("picture")
            }
          },
          instapaper: {
            shareUrl: "http://www.instapaper.com/edit",
            params: {
              url: this.getValue("url"),
              title: this.getValue("title"),
              description: this.getValue("description")
            }
          },
          pocket: {
            shareUrl: "https://getpocket.com/save",
            params: {
              url: this.getValue("url")
            }
          },
          digg: {
            shareUrl: "http://www.digg.com/submit",
            params: {
              url: this.getValue("url")
            }
          },
          stumbleupon: {
            shareUrl: "http://www.stumbleupon.com/submit",
            params: {
              url: this.getValue("url"),
              title: this.getValue("title")
            }
          },
          flipboard: {
            shareUrl: "https://share.flipboard.com/bookmarklet/popout",
            params: {
              v: 2,
              title: this.getValue("title"),
              url: this.getValue("url"),
              t: Date.now()
            }
          },
          weibo: {
            shareUrl: "http://service.weibo.com/share/share.php",
            params: {
              url: this.getValue("url"),
              title: this.getValue("title"),
              pic: this.getValue("image"),
              appkey: this.getValue("appkey"),
              ralateUid: this.getValue("ralateuid"),
              language: "zh_cn"
            }
          },
          renren: {
            shareUrl: "http://share.renren.com/share/buttonshare",
            params: {
              link: this.getValue("url")
            }
          },
          myspace: {
            shareUrl: "https://myspace.com/post",
            params: {
              u: this.getValue("url"),
              t: this.getValue("title"),
              c: this.getValue("description")
            }
          },
          blogger: {
            shareUrl: "https://www.blogger.com/blog-this.g",
            params: {
              u: this.getValue("url"),
              n: this.getValue("title"),
              t: this.getValue("description")
            }
          },
          baidu: {
            shareUrl: "http://cang.baidu.com/do/add",
            params: {
              it: this.getValue("title"),
              iu: this.getValue("url")
            }
          },
          douban: {
            shareUrl: "https://www.douban.com/share/service",
            params: {
              name: this.getValue("title"),
              href: this.getValue("url"),
              image: this.getValue("image")
            }
          },
          okru: {
            shareUrl: "https://connect.ok.ru/dk",
            params: {
              "st.cmd": "WidgetSharePreview",
              "st.shareUrl": this.getValue("url"),
              title: this.getValue("title")
            }
          },
          mailru: {
            shareUrl: "http://connect.mail.ru/share",
            params: {
              share_url: this.getValue("url"),
              linkname: this.getValue("title"),
              linknote: this.getValue("description"),
              type: "page"
            }
          }
        },
        a = e[t];
      if (a) {
        a.width = this.getValue("width");
        a.height = this.getValue("height");
      }
      return a !== undefined ? this.urlSharer(a) : false;
    },
    urlSharer: function urlSharer(t) {
      var e = t.params || {},
        a = Object.keys(e),
        r,
        i = a.length > 0 ? "?" : "";
      for (r = 0; r < a.length; r++) {
        if (i !== "?") {
          i += "&";
        }
        if (e[a[r]]) {
          i += a[r] + "=" + encodeURIComponent(e[a[r]]);
        }
      }
      t.shareUrl += i;
      if (!t.isLink) {
        var s = t.width || 600,
          l = t.height || 480,
          h = p.innerWidth / 2 - s / 2 + p.screenX,
          u = p.innerHeight / 2 - l / 2 + p.screenY,
          n = "scrollbars=no, width=" + s + ", height=" + l + ", top=" + u + ", left=" + h,
          g = p.open(t.shareUrl, "", n);
        if (p.focus) {
          g.focus();
        }
      } else {
        p.location.href = t.shareUrl;
      }
    }
  };
  if (r.readyState === "complete" || r.readyState !== "loading") {
    i.init();
  } else {
    r.addEventListener("DOMContentLoaded", i.init);
  }
  p.addEventListener("page:load", i.init);
  p.Sharer = i;
})(window, document);
var tns = function () {
  var win = window;
  var raf = win.requestAnimationFrame || win.webkitRequestAnimationFrame || win.mozRequestAnimationFrame || win.msRequestAnimationFrame || function (cb) {
    return setTimeout(cb, 16);
  };
  var win$1 = window;
  var caf = win$1.cancelAnimationFrame || win$1.mozCancelAnimationFrame || function (id) {
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
      docOverflow = docElement.style.overflow;
      //avoid crashing IE8, if background image is used
      body.style.background = '';
      //Safari 5.13/5.1.4 OSX stops loading if ::-webkit-scrollbar is used and scrollbars are visible
      body.style.overflow = docElement.style.overflow = 'hidden';
      docElement.appendChild(body);
    }
    return docOverflow;
  }
  function resetFakeBody(body, docOverflow) {
    if (body.fake) {
      body.remove();
      docElement.style.overflow = docOverflow;
      // Trigger layout so kinetic scrolling isn't disabled in iOS6+
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
    var style = document.createElement("style");
    // style.setAttribute("type", "text/css");

    // Add a media (and/or media query) here if you'd like!
    // style.setAttribute("media", "screen")
    // style.setAttribute("media", "only screen and (max-width : 1024px)")
    if (media) {
      style.setAttribute("media", media);
    }

    // Add nonce attribute for Content Security Policy
    if (nonce) {
      style.setAttribute("nonce", nonce);
    }

    // WebKit hack :(
    // style.appendChild(document.createTextNode(""));

    // Add the <style> element to the page
    document.querySelector('head').appendChild(style);
    return style.sheet ? style.sheet : style.styleSheet;
  }

  // cross browsers addRule method
  function addCSSRule(sheet, selector, rules, index) {
    // return raf(function() {
    'insertRule' in sheet ? sheet.insertRule(selector + '{' + rules + '}', index) : sheet.addRule(selector, rules, index);
    // });
  }

  // cross browsers addRule method
  function removeCSSRule(sheet, index) {
    // return raf(function() {
    'deleteRule' in sheet ? sheet.deleteRule(index) : sheet.removeRule(index);
    // });
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
    var el = document.createElement('fakeelement'),
      len = props.length;
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
    cssTF += 'transform';

    // Add it to the body to get the computed style
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
      positionTick = (to - from) / duration * tick,
      running;
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
  }

  // ChildNode.remove
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
        }
        // update browserInfo
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
      ANIMATIONEND = tnsStorage['tAE'] ? checkStorageValue(tnsStorage['tAE']) : setLocalStorage(tnsStorage, 'tAE', getEndProperty(ANIMATIONDURATION, 'Animation'), localStorageAccess);

    // get element nodes from selectors
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
    });

    // make sure at least 1 slide
    if (options.container.children.length < 1) {
      if (supportConsoleWarn) {
        console.warn('No slides found in', options.container);
      }
      return;
    }

    // update options
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
        var val = responsive[key];
        // update responsive
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
    }

    // update options
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
        }

        // update responsive options
        if (key === 'responsive') {
          updateOptions(obj[key]);
        }
      }
    }
    if (!carousel) {
      updateOptions(options);
    }

    // === define and set variables ===
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
    }

    // fixedWidth: viewport > rightBoundary > indexMax
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
      indexCached = index,
      displayIndex = getCurrentSlide(),
      indexMin = 0,
      indexMax = !autoWidth ? getIndexMax() : null,
      // resize
      resizeTimer,
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
      preventScroll = options.preventScrollOnTouch === 'force' ? true : false;

    // controls
    if (hasControls) {
      var controlsContainer = options.controlsContainer,
        controlsContainerHTML = options.controlsContainer ? options.controlsContainer.outerHTML : '',
        prevButton = options.prevButton,
        nextButton = options.nextButton,
        prevButtonHTML = options.prevButton ? options.prevButton.outerHTML : '',
        nextButtonHTML = options.nextButton ? options.nextButton.outerHTML : '',
        prevIsButton,
        nextIsButton;
    }

    // nav
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
    }

    // autoplay
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
        disX,
        disY,
        panStart = false,
        rafIndex,
        getDist = horizontal ? function (a, b) {
          return a.x - b.x;
        } : function (a, b) {
          return a.y - b.y;
        };
    }

    // disable slider when slidecount <= items
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
    initSliderTransform();

    // === COMMON FUNCTIONS === //
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
      result = navAsThumbnails ? absIndex : fixedWidth || autoWidth ? Math.ceil((absIndex + 1) * pages / slideCount - 1) : Math.floor(absIndex / items);

      // set active nav to the last one when reaches the right edge
      if (!loop && carousel && index === indexMax) {
        result = pages - 1;
      }
      return result;
    }
    function getItemsMax() {
      // fixedWidth or autoWidth while viewportMax is not available
      if (autoWidth || fixedWidth && !viewportMax) {
        return slideCount - 1;
        // most cases
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
    }

    // get option:
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
      width = 'width:' + width;

      // inner slider: overwrite outer slider styles
      return nested !== 'inner' ? width + ';' : width + ' !important;';
    }
    function getSlideGutterStyle(gutterTem) {
      var str = '';

      // gutter maybe interger || 0
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
        classInner = 'tns-inner',
        hasGutter = hasOption('gutter');
      outerWrapper.className = classOuter;
      innerWrapper.className = classInner;
      outerWrapper.id = slideId + '-ow';
      innerWrapper.id = slideId + '-iw';

      // set container properties
      if (container.id === '') {
        container.id = slideId;
      }
      newContainerClasses += PERCENTAGELAYOUT || autoWidth ? ' tns-subpixel' : ' tns-no-subpixel';
      newContainerClasses += CALC ? ' tns-calc' : ' tns-no-calc';
      if (autoWidth) {
        newContainerClasses += ' tns-autowidth';
      }
      newContainerClasses += ' tns-' + options.axis;
      container.className += newContainerClasses;

      // add constrain layer for carousel
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
      innerWrapper.appendChild(container);

      // add id, class, aria attributes
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
      });

      // ## clone slides
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
        var imgs = container.querySelectorAll('img');

        // add img load event listener
        forEach(imgs, function (img) {
          var src = img.src;
          if (!lazyload) {
            // not data img
            if (src && src.indexOf('data:image') < 0) {
              img.src = '';
              addEvents(img, imgEvents);
              addClass(img, 'loading');
              img.src = src;
              // data img
            } else {
              imgLoaded(img);
            }
          }
        });

        // set imgsComplete
        raf(function () {
          imgsLoadedCheck(arrayFromNodeList(imgs), function () {
            imgsComplete = true;
          });
        });

        // reset imgs for auto height: check visible imgs only
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
        }

        // update slider tools and events
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
      }

      // set container transform property
      if (carousel) {
        doContainerTransformSilent();
      }

      // update slider tools and events
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
      }

      // #### LAYOUT

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
      }

      // ## BASIC STYLES
      if (CSSMQ) {
        // middle wrapper style
        if (TRANSITIONDURATION) {
          var str = middleWrapper && options.autoHeight ? getTransitionDurationStyle(options.speed) : '';
          addCSSRule(sheet, '#' + slideId + '-mw', str, getCssRulesLength(sheet));
        }

        // inner wrapper styles
        str = getInnerWrapperStyles(options.edgePadding, options.gutter, options.fixedWidth, options.speed, options.autoHeight);
        addCSSRule(sheet, '#' + slideId + '-iw', str, getCssRulesLength(sheet));

        // container styles
        if (carousel) {
          str = horizontal && !autoWidth ? 'width:' + getContainerWidth(options.fixedWidth, options.gutter, options.items) + ';' : '';
          if (TRANSITIONDURATION) {
            str += getTransitionDurationStyle(speed);
          }
          addCSSRule(sheet, '#' + slideId, str, getCssRulesLength(sheet));
        }

        // slide styles
        str = horizontal && !autoWidth ? getSlideWidthStyle(options.fixedWidth, options.gutter, options.items) : '';
        if (options.gutter) {
          str += getSlideGutterStyle(options.gutter);
        }
        // set gallery items transition-duration
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
        }

        // non CSS mediaqueries: IE8
        // ## update inner wrapper, container, slides if needed
        // set inline styles for inner wrapper & container
        // insert stylesheet (one line) for slides only (since slides are many)
      } else {
        // middle wrapper styles
        update_carousel_transition_duration();

        // inner wrapper styles
        innerWrapper.style.cssText = getInnerWrapperStyles(edgePadding, gutter, fixedWidth, autoHeight);

        // container styles
        if (carousel && horizontal && !autoWidth) {
          container.style.width = getContainerWidth(fixedWidth, gutter, items);
        }

        // slide styles
        var str = horizontal && !autoWidth ? getSlideWidthStyle(fixedWidth, gutter, items) : '';
        if (gutter) {
          str += getSlideGutterStyle(gutter);
        }

        // append to the last line
        if (str) {
          addCSSRule(sheet, '#' + slideId + ' > .tns-item', str, getCssRulesLength(sheet));
        }
      }

      // ## MEDIAQUERIES
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
            gutterBP = getOption('gutter', bp);

          // middle wrapper string
          if (TRANSITIONDURATION && middleWrapper && getOption('autoHeight', bp) && 'speed' in opts) {
            middleWrapperStr = '#' + slideId + '-mw{' + getTransitionDurationStyle(speedBP) + '}';
          }

          // inner wrapper string
          if ('edgePadding' in opts || 'gutter' in opts) {
            innerWrapperStr = '#' + slideId + '-iw{' + getInnerWrapperStyles(edgePaddingBP, gutterBP, fixedWidthBP, speedBP, autoHeightBP) + '}';
          }

          // container string
          if (carousel && horizontal && !autoWidth && ('fixedWidth' in opts || 'items' in opts || fixedWidth && 'gutter' in opts)) {
            containerStr = 'width:' + getContainerWidth(fixedWidthBP, gutterBP, itemsBP) + ';';
          }
          if (TRANSITIONDURATION && 'speed' in opts) {
            containerStr += getTransitionDurationStyle(speedBP);
          }
          if (containerStr) {
            containerStr = '#' + slideId + '{' + containerStr + '}';
          }

          // slide string
          if ('fixedWidth' in opts || fixedWidth && 'gutter' in opts || !carousel && 'items' in opts) {
            slideStr += getSlideWidthStyle(fixedWidthBP, gutterBP, itemsBP);
          }
          if ('gutter' in opts) {
            slideStr += getSlideGutterStyle(gutterBP);
          }
          // set gallery items transition-duration
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
          }

          // add up
          str = middleWrapperStr + innerWrapperStr + containerStr + slideStr;
          if (str) {
            sheet.insertRule('@media (min-width: ' + bp / 16 + 'em) {' + str + '}', sheet.cssRules.length);
          }
        }
      }
    }
    function initTools() {
      // == slides ==
      updateSlideStatus();

      // == live region ==
      outerWrapper.insertAdjacentHTML('afterbegin', '<div class="tns-liveregion tns-visually-hidden" aria-live="polite" aria-atomic="true">slide <span class="current">' + getLiveRegionStr() + '</span>  of ' + slideCount + '</div>');
      liveregionCurrent = outerWrapper.querySelector('.tns-liveregion .current');

      // == autoplayInit ==
      if (hasAutoplay) {
        var txt = autoplay ? 'stop' : 'start';
        if (autoplayButton) {
          setAttrs(autoplayButton, {
            'data-action': txt
          });
        } else if (options.autoplayButtonOutput) {
          outerWrapper.insertAdjacentHTML(getInsertPosition(options.autoplayPosition), '<button type="button" data-action="' + txt + '">' + autoplayHtmlStrings[0] + txt + autoplayHtmlStrings[1] + autoplayText[0] + '</button>');
          autoplayButton = outerWrapper.querySelector('[data-action]');
        }

        // add event
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
      }

      // == navInit ==
      if (hasNav) {
        var initIndex = !carousel ? 0 : cloneCount;
        // customized nav
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
          });

          // generated nav
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
        updateNavVisibility();

        // add transition
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
        addClass(navItems[navCurrentIndex], navActiveClass);

        // add events
        addEvents(navContainer, navEvents);
      }

      // == controlsInit ==
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
        updateControlsStatus();

        // add events
        if (controlsContainer) {
          addEvents(controlsContainer, controlsEvents);
        } else {
          addEvents(prevButton, controlsEvents);
          addEvents(nextButton, controlsEvents);
        }
      }

      // hide tools if needed
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
      }

      // remove win event listeners
      removeEvents(win, {
        'resize': onResize
      });

      // arrowKeys, controls, nav
      if (arrowKeys) {
        removeEvents(doc, docmentKeydownEvent);
      }
      if (controlsContainer) {
        removeEvents(controlsContainer, controlsEvents);
      }
      if (navContainer) {
        removeEvents(navContainer, navEvents);
      }

      // autoplay
      removeEvents(container, hoverEvents);
      removeEvents(container, visibilityEvent);
      if (autoplayButton) {
        removeEvents(autoplayButton, {
          'click': toggleAutoplay
        });
      }
      if (autoplay) {
        clearInterval(autoplayTimer);
      }

      // container
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
      }

      // cache Object values in options && reset HTML
      var htmlList = [containerHTML, controlsContainerHTML, prevButtonHTML, nextButtonHTML, navContainerHTML, autoplayButtonHTML];
      tnsList.forEach(function (item, i) {
        var el = item === 'container' ? outerWrapper : options[item];
        if (_typeof(el) === 'object' && el) {
          var prevEl = el.previousElementSibling ? el.previousElementSibling : false,
            parentEl = el.parentNode;
          el.outerHTML = htmlList[i];
          options[item] = prevEl ? prevEl.nextElementSibling : parentEl.firstElementChild;
        }
      });

      // reset variables
      tnsList = animateIn = animateOut = animateDelay = animateNormal = horizontal = outerWrapper = innerWrapper = container = containerParent = containerHTML = slideItems = slideCount = breakpointZone = windowWidth = autoWidth = fixedWidth = edgePadding = gutter = viewport = items = slideBy = viewportMax = arrowKeys = speed = rewind = loop = autoHeight = sheet = lazyload = slidePositions = slideItemsOut = cloneCount = slideCountNew = hasRightDeadZone = rightBoundary = updateIndexBeforeTransform = transformAttr = transformPrefix = transformPostfix = getIndexMax = index = indexCached = indexMin = indexMax = resizeTimer = swipeAngle = moveDirectionExpected = running = onInit = events = newContainerClasses = slideId = disable = disabled = freezable = freeze = frozen = controlsEvents = navEvents = hoverEvents = visibilityEvent = docmentKeydownEvent = touchEvents = dragEvents = hasControls = hasNav = navAsThumbnails = hasAutoplay = hasTouch = hasMouseDrag = slideActiveClass = imgCompleteClass = imgEvents = imgsComplete = controls = controlsText = controlsContainer = controlsContainerHTML = prevButton = nextButton = prevIsButton = nextIsButton = nav = navContainer = navContainerHTML = navItems = pages = pagesCached = navClicked = navCurrentIndex = navCurrentIndexCached = navActiveClass = navStr = navStrCurrent = autoplay = autoplayTimeout = autoplayDirection = autoplayText = autoplayHoverPause = autoplayButton = autoplayButtonHTML = autoplayResetOnVisibility = autoplayHtmlStrings = autoplayTimer = animating = autoplayHoverPaused = autoplayUserPaused = autoplayVisibilityPaused = initPosition = lastPosition = translateInit = disX = disY = panStart = rafIndex = getDist = touch = mouseDrag = null;
      // check variables
      // [animateIn, animateOut, animateDelay, animateNormal, horizontal, outerWrapper, innerWrapper, container, containerParent, containerHTML, slideItems, slideCount, breakpointZone, windowWidth, autoWidth, fixedWidth, edgePadding, gutter, viewport, items, slideBy, viewportMax, arrowKeys, speed, rewind, loop, autoHeight, sheet, lazyload, slidePositions, slideItemsOut, cloneCount, slideCountNew, hasRightDeadZone, rightBoundary, updateIndexBeforeTransform, transformAttr, transformPrefix, transformPostfix, getIndexMax, index, indexCached, indexMin, indexMax, resizeTimer, swipeAngle, moveDirectionExpected, running, onInit, events, newContainerClasses, slideId, disable, disabled, freezable, freeze, frozen, controlsEvents, navEvents, hoverEvents, visibilityEvent, docmentKeydownEvent, touchEvents, dragEvents, hasControls, hasNav, navAsThumbnails, hasAutoplay, hasTouch, hasMouseDrag, slideActiveClass, imgCompleteClass, imgEvents, imgsComplete, controls, controlsText, controlsContainer, controlsContainerHTML, prevButton, nextButton, prevIsButton, nextIsButton, nav, navContainer, navContainerHTML, navItems, pages, pagesCached, navClicked, navCurrentIndex, navCurrentIndexCached, navActiveClass, navStr, navStrCurrent, autoplay, autoplayTimeout, autoplayDirection, autoplayText, autoplayHoverPause, autoplayButton, autoplayButtonHTML, autoplayResetOnVisibility, autoplayHtmlStrings, autoplayTimer, animating, autoplayHoverPaused, autoplayUserPaused, autoplayVisibilityPaused, initPosition, lastPosition, translateInit, disX, disY, panStart, rafIndex, getDist, touch, mouseDrag ].forEach(function(item) { if (item !== null) { console.log(item); } });

      for (var a in this) {
        if (a !== 'rebuild') {
          this[a] = null;
        }
      }
      isOn = false;
    }

    // === ON RESIZE ===
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
        bpChanged = breakpointZoneTem !== breakpointZone;
        // if (hasRightDeadZone) { needContainerTransform = true; } // *?
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
      }

      // get option:
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
      }
      // update options
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
            }

            // slide styles
            var str = getSlideWidthStyle(fixedWidth, gutter, items) + getSlideGutterStyle(gutter);

            // remove the last line and
            // add new styles
            removeCSSRule(sheet, getCssRulesLength(sheet) - 1);
            addCSSRule(sheet, '#' + slideId + ' > .tns-item', str, getCssRulesLength(sheet));
          }
        }

        // auto height
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
    }

    // === INITIALIZATION FUNCTIONS === //
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
    }

    // (slideBy, indexMin, indexMax) => index
    var updateIndex = function () {
      return loop ? carousel ?
      // loop + carousel
      function () {
        var leftEdge = indexMin,
          rightEdge = indexMax;
        leftEdge += slideBy;
        rightEdge -= slideBy;

        // adjust edges when has edge paddings
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
      }

      // remove edge padding from inner wrapper
      if (edgePadding) {
        innerWrapper.style.margin = '0px';
      }

      // add class tns-transparent to cloned slides
      if (cloneCount) {
        var str = 'tns-transparent';
        for (var i = cloneCount; i--;) {
          if (carousel) {
            addClass(slideItems[i], str);
          }
          addClass(slideItems[slideCountNew - i - 1], str);
        }
      }

      // update tools
      disableUI();
      frozen = true;
    }
    function unfreezeSlider() {
      if (!frozen) {
        return;
      }

      // restore edge padding for inner wrapper
      // for mordern browsers
      if (edgePadding && CSSMQ) {
        innerWrapper.style.margin = '';
      }

      // remove class tns-transparent to cloned slides
      if (cloneCount) {
        var str = 'tns-transparent';
        for (var i = cloneCount; i--;) {
          if (carousel) {
            removeClass(slideItems[i], str);
          }
          removeClass(slideItems[slideCountNew - i - 1], str);
        }
      }

      // update tools
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
      }

      // vertical slider
      if (!horizontal || !carousel) {
        removeAttrs(innerWrapper, ['style']);
      }

      // gallery
      if (!carousel) {
        for (var i = index, l = index + slideCount; i < l; i++) {
          var item = slideItems[i];
          removeAttrs(item, ['style']);
          removeClass(item, animateIn);
          removeClass(item, animateNormal);
        }
      }

      // update tools
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
      }

      // gallery
      if (!carousel) {
        for (var i = index, l = index + slideCount; i < l; i++) {
          var item = slideItems[i],
            classN = i < index + items ? animateIn : animateNormal;
          item.style.left = (i - index) * 100 / items + '%';
          addClass(item, classN);
        }
      }

      // update tools
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
        rangeend;

      // get range start, range end for autoWidth and fixedWidth
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
      }

      // get start, end
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
        });

        // - check percentage width, fixed width
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
            addEvents(img, imgEvents);

            // update src
            img.src = getAttr(img, 'data-src');

            // update srcset
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
    }

    // check if all visible images are loaded
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
      }

      // check image classes
      imgs.forEach(function (img, index) {
        if (!lazyload && img.complete) {
          imgCompleted(img);
        } // Check image.complete
        if (hasClass(img, imgCompleteClass)) {
          imgs.splice(index, 1);
        }
      });

      // execute callback function if selected images are all complete
      if (!imgs.length) {
        return cb();
      }

      // otherwise execute this functiona again
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
    }

    // update inner wrapper height
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
    }

    // get the distance from the top edge of the first slide to each slide
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
        }
        // add the end edge
        if (i === slideCountNew - 1) {
          slidePositions.push(item.getBoundingClientRect()[attr2] - base);
        }
      });
    }

    // update slide
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
          }
          // hide slides
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
    }

    // gallery: update slide position
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
        }

        // remove outlet animation
        removeClass(item, animateOut);
      }

      // removing '.tns-moving'
      setTimeout(function () {
        forEach(slideItems, function (el) {
          removeClass(el, 'tns-moving');
        });
      }, 300);
    }

    // set tabindex on Nav
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
    }

    // set 'disabled' to true on controls when reach the edges
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
    }

    // set duration
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
        var item = slideItems[i];

        // set item positions
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
    }

    // make transfer after click/drag:
    // 1. change 'transform' property for mordern browsers
    // 2. change 'left' property for legacy browsers
    var transformCore = function () {
      return carousel ? function () {
        resetDuration(container, '');
        if (TRANSITIONDURATION || !speed) {
          // for morden browsers with non-zero duration or
          // zero duration for all browsers
          doContainerTransform();
          // run fallback function manually
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
        animateSlide(index, animateNormal, animateIn);

        // run fallback function manually
        // when transition or animation not supported / duration is 0
        if (!TRANSITIONEND || !ANIMATIONEND || !speed || !isVisible(container)) {
          onTransitionEnd();
        }
      };
    }();
    function render(e, sliderMoved) {
      if (updateIndexBeforeTransform) {
        updateIndex();
      }

      // render when slider was moved (touch or drag) even though index may not change
      if (index !== indexCached || sliderMoved) {
        // events
        events.emit('indexChanged', info());
        events.emit('transitionStart', info());
        if (autoHeight) {
          doAutoHeight();
        }

        // pause autoplay when click or keydown from user
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
    }

    // AFTER TRANSFORM
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
            var item = slideItemsOut[i];
            // set item positions
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
    }

    // # ACTIONS
    function goTo(targetIndex, e) {
      if (freeze) {
        return;
      }

      // prev slideBy
      if (targetIndex === 'prev') {
        onControlsClick(e, -1);

        // next slideBy
      } else if (targetIndex === 'next') {
        onControlsClick(e, 1);

        // go to exact slide
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
        }

        // gallery: make sure new page won't overlap with current page
        if (!carousel && indexGap && Math.abs(indexGap) < items) {
          var factor = indexGap > 0 ? 1 : -1;
          indexGap += index + indexGap - slideCount >= indexMin ? slideCount * factor : slideCount * 2 * factor * -1;
        }
        index += indexGap;

        // make sure index is in range
        if (carousel && loop) {
          if (index < indexMin) {
            index += slideCount;
          }
          if (index > indexMax) {
            index -= slideCount;
          }
        }

        // if index is changed, start rendering
        if (getAbsIndex(index) !== getAbsIndex(indexCached)) {
          render(e);
        }
      }
    }

    // on controls click
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
        }
        // pass e when click control buttons or keydown
        render(passEventObject || e && e.type === 'keydown' ? e : null);
      }
    }

    // on nav click
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
        navIndex;

      // find the clicked nav item
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
    }

    // autoplay functions
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
    }

    // programaitcally play/pause the slider
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
    }

    // keydown events on document
    function onDocumentKeydown(e) {
      e = getEvent(e);
      var keyIndex = [KEYS.LEFT, KEYS.RIGHT].indexOf(e.keyCode);
      if (keyIndex >= 0) {
        onControlsClick(e, keyIndex === 0 ? -1 : 1);
      }
    }

    // on key control
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
    }

    // set focus
    function setFocus(el) {
      el.focus();
    }

    // on key nav
    function onNavKeydown(e) {
      e = getEvent(e);
      var curElement = doc.activeElement;
      if (!hasAttr(curElement, 'data-nav')) {
        return;
      }

      // var code = e.keyCode,
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
      }

      // reset
      if (options.preventScrollOnTouch === 'auto') {
        preventScroll = false;
      }
      if (swipeAngle) {
        moveDirectionExpected = '?';
      }
      if (autoplay && !animating) {
        setAutoplayTimer();
      }
    }

    // === RESIZE FUNCTIONS === //
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
        }

        // cache pages
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
      version: '2.9.3',
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
  return tns;
}();
!function (a) {
  "use strict";

  "function" == typeof require && "object" == (typeof exports === "undefined" ? "undefined" : _typeof(exports)) ? module.exports = a() : "function" == typeof define && define.amd ? define(["velocity"], a) : a();
}(function () {
  "use strict";

  return function (a, b, c, d) {
    var e = a.Velocity;
    if (!e || !e.Utilities) return void (b.console && console.log("Velocity UI Pack: Velocity must be loaded first. Aborting."));
    var f = e.Utilities,
      g = e.version,
      h = {
        major: 1,
        minor: 1,
        patch: 0
      };
    if (function (a, b) {
      var c = [];
      return !(!a || !b) && (f.each([a, b], function (a, b) {
        var d = [];
        f.each(b, function (a, b) {
          for (; b.toString().length < 5;) b = "0" + b;
          d.push(b);
        }), c.push(d.join(""));
      }), parseFloat(c[0]) > parseFloat(c[1]));
    }(h, g)) {
      var i = "Velocity UI Pack: You need to update Velocity (velocity.js) to a newer version. Visit http://github.com/julianshapiro/velocity.";
      throw alert(i), new Error(i);
    }
    e.RegisterEffect = e.RegisterUI = function (a, b) {
      function c(a, b, c, d) {
        var g,
          h = 0;
        f.each(a.nodeType ? [a] : a, function (a, b) {
          d && (c += a * d), g = b.parentNode;
          var i = ["height", "paddingTop", "paddingBottom", "marginTop", "marginBottom"];
          "border-box" === e.CSS.getPropertyValue(b, "boxSizing").toString().toLowerCase() && (i = ["height"]), f.each(i, function (a, c) {
            h += parseFloat(e.CSS.getPropertyValue(b, c));
          });
        }), e.animate(g, {
          height: ("In" === b ? "+" : "-") + "=" + h
        }, {
          queue: !1,
          easing: "ease-in-out",
          duration: c * ("In" === b ? .6 : 1)
        });
      }
      return e.Redirects[a] = function (d, g, h, i, j, k, l) {
        var m = h === i - 1,
          n = 0;
        l = l || b.loop, "function" == typeof b.defaultDuration ? b.defaultDuration = b.defaultDuration.call(j, j) : b.defaultDuration = parseFloat(b.defaultDuration);
        for (var o = 0; o < b.calls.length; o++) "number" == typeof (t = b.calls[o][1]) && (n += t);
        var p = n >= 1 ? 0 : b.calls.length ? (1 - n) / b.calls.length : 1;
        for (o = 0; o < b.calls.length; o++) {
          var q = b.calls[o],
            r = q[0],
            s = 1e3,
            t = q[1],
            u = q[2] || {},
            v = {};
          if (void 0 !== g.duration ? s = g.duration : void 0 !== b.defaultDuration && (s = b.defaultDuration), v.duration = s * ("number" == typeof t ? t : p), v.queue = g.queue || "", v.easing = u.easing || "ease", v.delay = parseFloat(u.delay) || 0, v.loop = !b.loop && u.loop, v._cacheValues = u._cacheValues || !0, 0 === o) {
            if (v.delay += parseFloat(g.delay) || 0, 0 === h && (v.begin = function () {
              g.begin && g.begin.call(j, j);
              var b = a.match(/(In|Out)$/);
              b && "In" === b[0] && void 0 !== r.opacity && f.each(j.nodeType ? [j] : j, function (a, b) {
                e.CSS.setPropertyValue(b, "opacity", 0);
              }), g.animateParentHeight && b && c(j, b[0], s + v.delay, g.stagger);
            }), null !== g.display) if (void 0 !== g.display && "none" !== g.display) v.display = g.display;else if (/In$/.test(a)) {
              var w = e.CSS.Values.getDisplayType(d);
              v.display = "inline" === w ? "inline-block" : w;
            }
            g.visibility && "hidden" !== g.visibility && (v.visibility = g.visibility);
          }
          if (o === b.calls.length - 1) {
            var x = function x() {
              void 0 !== g.display && "none" !== g.display || !/Out$/.test(a) || f.each(j.nodeType ? [j] : j, function (a, b) {
                e.CSS.setPropertyValue(b, "display", "none");
              }), g.complete && g.complete.call(j, j), k && k.resolver(j || d);
            };
            v.complete = function () {
              if (l && e.Redirects[a](d, g, h, i, j, k, !0 === l || Math.max(0, l - 1)), b.reset) {
                for (var c in b.reset) if (b.reset.hasOwnProperty(c)) {
                  var f = b.reset[c];
                  void 0 !== e.CSS.Hooks.registered[c] || "string" != typeof f && "number" != typeof f || (b.reset[c] = [b.reset[c], b.reset[c]]);
                }
                var n = {
                  duration: 0,
                  queue: !1
                };
                m && (n.complete = x), e.animate(d, b.reset, n);
              } else m && x();
            }, "hidden" === g.visibility && (v.visibility = g.visibility);
          }
          e.animate(d, r, v);
        }
      }, e;
    }, e.RegisterEffect.packagedEffects = {
      "callout.bounce": {
        defaultDuration: 550,
        calls: [[{
          translateY: -30
        }, .25], [{
          translateY: 0
        }, .125], [{
          translateY: -15
        }, .125], [{
          translateY: 0
        }, .25]]
      },
      "callout.shake": {
        defaultDuration: 800,
        calls: [[{
          translateX: -11
        }], [{
          translateX: 11
        }], [{
          translateX: -11
        }], [{
          translateX: 11
        }], [{
          translateX: -11
        }], [{
          translateX: 11
        }], [{
          translateX: -11
        }], [{
          translateX: 0
        }]]
      },
      "callout.flash": {
        defaultDuration: 1100,
        calls: [[{
          opacity: [0, "easeInOutQuad", 1]
        }], [{
          opacity: [1, "easeInOutQuad"]
        }], [{
          opacity: [0, "easeInOutQuad"]
        }], [{
          opacity: [1, "easeInOutQuad"]
        }]]
      },
      "callout.pulse": {
        defaultDuration: 825,
        calls: [[{
          scaleX: 1.1,
          scaleY: 1.1
        }, .5, {
          easing: "easeInExpo"
        }], [{
          scaleX: 1,
          scaleY: 1
        }, .5]]
      },
      "callout.swing": {
        defaultDuration: 950,
        calls: [[{
          rotateZ: 15
        }], [{
          rotateZ: -10
        }], [{
          rotateZ: 5
        }], [{
          rotateZ: -5
        }], [{
          rotateZ: 0
        }]]
      },
      "callout.tada": {
        defaultDuration: 1e3,
        calls: [[{
          scaleX: .9,
          scaleY: .9,
          rotateZ: -3
        }, .1], [{
          scaleX: 1.1,
          scaleY: 1.1,
          rotateZ: 3
        }, .1], [{
          scaleX: 1.1,
          scaleY: 1.1,
          rotateZ: -3
        }, .1], ["reverse", .125], ["reverse", .125], ["reverse", .125], ["reverse", .125], ["reverse", .125], [{
          scaleX: 1,
          scaleY: 1,
          rotateZ: 0
        }, .2]]
      },
      "transition.fadeIn": {
        defaultDuration: 500,
        calls: [[{
          opacity: [1, 0]
        }]]
      },
      "transition.fadeOut": {
        defaultDuration: 500,
        calls: [[{
          opacity: [0, 1]
        }]]
      },
      "transition.flipXIn": {
        defaultDuration: 700,
        calls: [[{
          opacity: [1, 0],
          transformPerspective: [800, 800],
          rotateY: [0, -55]
        }]],
        reset: {
          transformPerspective: 0
        }
      },
      "transition.flipXOut": {
        defaultDuration: 700,
        calls: [[{
          opacity: [0, 1],
          transformPerspective: [800, 800],
          rotateY: 55
        }]],
        reset: {
          transformPerspective: 0,
          rotateY: 0
        }
      },
      "transition.flipYIn": {
        defaultDuration: 800,
        calls: [[{
          opacity: [1, 0],
          transformPerspective: [800, 800],
          rotateX: [0, -45]
        }]],
        reset: {
          transformPerspective: 0
        }
      },
      "transition.flipYOut": {
        defaultDuration: 800,
        calls: [[{
          opacity: [0, 1],
          transformPerspective: [800, 800],
          rotateX: 25
        }]],
        reset: {
          transformPerspective: 0,
          rotateX: 0
        }
      },
      "transition.flipBounceXIn": {
        defaultDuration: 900,
        calls: [[{
          opacity: [.725, 0],
          transformPerspective: [400, 400],
          rotateY: [-10, 90]
        }, .5], [{
          opacity: .8,
          rotateY: 10
        }, .25], [{
          opacity: 1,
          rotateY: 0
        }, .25]],
        reset: {
          transformPerspective: 0
        }
      },
      "transition.flipBounceXOut": {
        defaultDuration: 800,
        calls: [[{
          opacity: [.9, 1],
          transformPerspective: [400, 400],
          rotateY: -10
        }], [{
          opacity: 0,
          rotateY: 90
        }]],
        reset: {
          transformPerspective: 0,
          rotateY: 0
        }
      },
      "transition.flipBounceYIn": {
        defaultDuration: 850,
        calls: [[{
          opacity: [.725, 0],
          transformPerspective: [400, 400],
          rotateX: [-10, 90]
        }, .5], [{
          opacity: .8,
          rotateX: 10
        }, .25], [{
          opacity: 1,
          rotateX: 0
        }, .25]],
        reset: {
          transformPerspective: 0
        }
      },
      "transition.flipBounceYOut": {
        defaultDuration: 800,
        calls: [[{
          opacity: [.9, 1],
          transformPerspective: [400, 400],
          rotateX: -15
        }], [{
          opacity: 0,
          rotateX: 90
        }]],
        reset: {
          transformPerspective: 0,
          rotateX: 0
        }
      },
      "transition.swoopIn": {
        defaultDuration: 850,
        calls: [[{
          opacity: [1, 0],
          transformOriginX: ["100%", "50%"],
          transformOriginY: ["100%", "100%"],
          scaleX: [1, 0],
          scaleY: [1, 0],
          translateX: [0, -700],
          translateZ: 0
        }]],
        reset: {
          transformOriginX: "50%",
          transformOriginY: "50%"
        }
      },
      "transition.swoopOut": {
        defaultDuration: 850,
        calls: [[{
          opacity: [0, 1],
          transformOriginX: ["50%", "100%"],
          transformOriginY: ["100%", "100%"],
          scaleX: 0,
          scaleY: 0,
          translateX: -700,
          translateZ: 0
        }]],
        reset: {
          transformOriginX: "50%",
          transformOriginY: "50%",
          scaleX: 1,
          scaleY: 1,
          translateX: 0
        }
      },
      "transition.whirlIn": {
        defaultDuration: 850,
        calls: [[{
          opacity: [1, 0],
          transformOriginX: ["50%", "50%"],
          transformOriginY: ["50%", "50%"],
          scaleX: [1, 0],
          scaleY: [1, 0],
          rotateY: [0, 160]
        }, 1, {
          easing: "easeInOutSine"
        }]]
      },
      "transition.whirlOut": {
        defaultDuration: 750,
        calls: [[{
          opacity: [0, "easeInOutQuint", 1],
          transformOriginX: ["50%", "50%"],
          transformOriginY: ["50%", "50%"],
          scaleX: 0,
          scaleY: 0,
          rotateY: 160
        }, 1, {
          easing: "swing"
        }]],
        reset: {
          scaleX: 1,
          scaleY: 1,
          rotateY: 0
        }
      },
      "transition.shrinkIn": {
        defaultDuration: 750,
        calls: [[{
          opacity: [1, 0],
          transformOriginX: ["50%", "50%"],
          transformOriginY: ["50%", "50%"],
          scaleX: [1, 1.5],
          scaleY: [1, 1.5],
          translateZ: 0
        }]]
      },
      "transition.shrinkOut": {
        defaultDuration: 600,
        calls: [[{
          opacity: [0, 1],
          transformOriginX: ["50%", "50%"],
          transformOriginY: ["50%", "50%"],
          scaleX: 1.3,
          scaleY: 1.3,
          translateZ: 0
        }]],
        reset: {
          scaleX: 1,
          scaleY: 1
        }
      },
      "transition.expandIn": {
        defaultDuration: 700,
        calls: [[{
          opacity: [1, 0],
          transformOriginX: ["50%", "50%"],
          transformOriginY: ["50%", "50%"],
          scaleX: [1, .625],
          scaleY: [1, .625],
          translateZ: 0
        }]]
      },
      "transition.expandOut": {
        defaultDuration: 700,
        calls: [[{
          opacity: [0, 1],
          transformOriginX: ["50%", "50%"],
          transformOriginY: ["50%", "50%"],
          scaleX: .5,
          scaleY: .5,
          translateZ: 0
        }]],
        reset: {
          scaleX: 1,
          scaleY: 1
        }
      },
      "transition.bounceIn": {
        defaultDuration: 800,
        calls: [[{
          opacity: [1, 0],
          scaleX: [1.05, .3],
          scaleY: [1.05, .3]
        }, .35], [{
          scaleX: .9,
          scaleY: .9,
          translateZ: 0
        }, .2], [{
          scaleX: 1,
          scaleY: 1
        }, .45]]
      },
      "transition.bounceOut": {
        defaultDuration: 800,
        calls: [[{
          scaleX: .95,
          scaleY: .95
        }, .35], [{
          scaleX: 1.1,
          scaleY: 1.1,
          translateZ: 0
        }, .35], [{
          opacity: [0, 1],
          scaleX: .3,
          scaleY: .3
        }, .3]],
        reset: {
          scaleX: 1,
          scaleY: 1
        }
      },
      "transition.bounceUpIn": {
        defaultDuration: 800,
        calls: [[{
          opacity: [1, 0],
          translateY: [-30, 1e3]
        }, .6, {
          easing: "easeOutCirc"
        }], [{
          translateY: 10
        }, .2], [{
          translateY: 0
        }, .2]]
      },
      "transition.bounceUpOut": {
        defaultDuration: 1e3,
        calls: [[{
          translateY: 20
        }, .2], [{
          opacity: [0, "easeInCirc", 1],
          translateY: -1e3
        }, .8]],
        reset: {
          translateY: 0
        }
      },
      "transition.bounceDownIn": {
        defaultDuration: 800,
        calls: [[{
          opacity: [1, 0],
          translateY: [30, -1e3]
        }, .6, {
          easing: "easeOutCirc"
        }], [{
          translateY: -10
        }, .2], [{
          translateY: 0
        }, .2]]
      },
      "transition.bounceDownOut": {
        defaultDuration: 1e3,
        calls: [[{
          translateY: -20
        }, .2], [{
          opacity: [0, "easeInCirc", 1],
          translateY: 1e3
        }, .8]],
        reset: {
          translateY: 0
        }
      },
      "transition.bounceLeftIn": {
        defaultDuration: 750,
        calls: [[{
          opacity: [1, 0],
          translateX: [30, -1250]
        }, .6, {
          easing: "easeOutCirc"
        }], [{
          translateX: -10
        }, .2], [{
          translateX: 0
        }, .2]]
      },
      "transition.bounceLeftOut": {
        defaultDuration: 750,
        calls: [[{
          translateX: 30
        }, .2], [{
          opacity: [0, "easeInCirc", 1],
          translateX: -1250
        }, .8]],
        reset: {
          translateX: 0
        }
      },
      "transition.bounceRightIn": {
        defaultDuration: 750,
        calls: [[{
          opacity: [1, 0],
          translateX: [-30, 1250]
        }, .6, {
          easing: "easeOutCirc"
        }], [{
          translateX: 10
        }, .2], [{
          translateX: 0
        }, .2]]
      },
      "transition.bounceRightOut": {
        defaultDuration: 750,
        calls: [[{
          translateX: -30
        }, .2], [{
          opacity: [0, "easeInCirc", 1],
          translateX: 1250
        }, .8]],
        reset: {
          translateX: 0
        }
      },
      "transition.slideUpIn": {
        defaultDuration: 900,
        calls: [[{
          opacity: [1, 0],
          translateY: [0, 20],
          translateZ: 0
        }]]
      },
      "transition.slideUpOut": {
        defaultDuration: 900,
        calls: [[{
          opacity: [0, 1],
          translateY: -20,
          translateZ: 0
        }]],
        reset: {
          translateY: 0
        }
      },
      "transition.slideDownIn": {
        defaultDuration: 900,
        calls: [[{
          opacity: [1, 0],
          translateY: [0, -20],
          translateZ: 0
        }]]
      },
      "transition.slideDownOut": {
        defaultDuration: 900,
        calls: [[{
          opacity: [0, 1],
          translateY: 20,
          translateZ: 0
        }]],
        reset: {
          translateY: 0
        }
      },
      "transition.slideLeftIn": {
        defaultDuration: 1e3,
        calls: [[{
          opacity: [1, 0],
          translateX: [0, -20],
          translateZ: 0
        }]]
      },
      "transition.slideLeftOut": {
        defaultDuration: 1050,
        calls: [[{
          opacity: [0, 1],
          translateX: -20,
          translateZ: 0
        }]],
        reset: {
          translateX: 0
        }
      },
      "transition.slideRightIn": {
        defaultDuration: 1e3,
        calls: [[{
          opacity: [1, 0],
          translateX: [0, 20],
          translateZ: 0
        }]]
      },
      "transition.slideRightOut": {
        defaultDuration: 1050,
        calls: [[{
          opacity: [0, 1],
          translateX: 20,
          translateZ: 0
        }]],
        reset: {
          translateX: 0
        }
      },
      "transition.slideUpBigIn": {
        defaultDuration: 850,
        calls: [[{
          opacity: [1, 0],
          translateY: [0, 75],
          translateZ: 0
        }]]
      },
      "transition.slideUpBigOut": {
        defaultDuration: 800,
        calls: [[{
          opacity: [0, 1],
          translateY: -75,
          translateZ: 0
        }]],
        reset: {
          translateY: 0
        }
      },
      "transition.slideDownBigIn": {
        defaultDuration: 850,
        calls: [[{
          opacity: [1, 0],
          translateY: [0, -75],
          translateZ: 0
        }]]
      },
      "transition.slideDownBigOut": {
        defaultDuration: 800,
        calls: [[{
          opacity: [0, 1],
          translateY: 75,
          translateZ: 0
        }]],
        reset: {
          translateY: 0
        }
      },
      "transition.slideLeftBigIn": {
        defaultDuration: 800,
        calls: [[{
          opacity: [1, 0],
          translateX: [0, -75],
          translateZ: 0
        }]]
      },
      "transition.slideLeftBigOut": {
        defaultDuration: 750,
        calls: [[{
          opacity: [0, 1],
          translateX: -75,
          translateZ: 0
        }]],
        reset: {
          translateX: 0
        }
      },
      "transition.slideRightBigIn": {
        defaultDuration: 800,
        calls: [[{
          opacity: [1, 0],
          translateX: [0, 75],
          translateZ: 0
        }]]
      },
      "transition.slideRightBigOut": {
        defaultDuration: 750,
        calls: [[{
          opacity: [0, 1],
          translateX: 75,
          translateZ: 0
        }]],
        reset: {
          translateX: 0
        }
      },
      "transition.perspectiveUpIn": {
        defaultDuration: 800,
        calls: [[{
          opacity: [1, 0],
          transformPerspective: [800, 800],
          transformOriginX: [0, 0],
          transformOriginY: ["100%", "100%"],
          rotateX: [0, -180]
        }]],
        reset: {
          transformPerspective: 0,
          transformOriginX: "50%",
          transformOriginY: "50%"
        }
      },
      "transition.perspectiveUpOut": {
        defaultDuration: 850,
        calls: [[{
          opacity: [0, 1],
          transformPerspective: [800, 800],
          transformOriginX: [0, 0],
          transformOriginY: ["100%", "100%"],
          rotateX: -180
        }]],
        reset: {
          transformPerspective: 0,
          transformOriginX: "50%",
          transformOriginY: "50%",
          rotateX: 0
        }
      },
      "transition.perspectiveDownIn": {
        defaultDuration: 800,
        calls: [[{
          opacity: [1, 0],
          transformPerspective: [800, 800],
          transformOriginX: [0, 0],
          transformOriginY: [0, 0],
          rotateX: [0, 180]
        }]],
        reset: {
          transformPerspective: 0,
          transformOriginX: "50%",
          transformOriginY: "50%"
        }
      },
      "transition.perspectiveDownOut": {
        defaultDuration: 850,
        calls: [[{
          opacity: [0, 1],
          transformPerspective: [800, 800],
          transformOriginX: [0, 0],
          transformOriginY: [0, 0],
          rotateX: 180
        }]],
        reset: {
          transformPerspective: 0,
          transformOriginX: "50%",
          transformOriginY: "50%",
          rotateX: 0
        }
      },
      "transition.perspectiveLeftIn": {
        defaultDuration: 950,
        calls: [[{
          opacity: [1, 0],
          transformPerspective: [2e3, 2e3],
          transformOriginX: [0, 0],
          transformOriginY: [0, 0],
          rotateY: [0, -180]
        }]],
        reset: {
          transformPerspective: 0,
          transformOriginX: "50%",
          transformOriginY: "50%"
        }
      },
      "transition.perspectiveLeftOut": {
        defaultDuration: 950,
        calls: [[{
          opacity: [0, 1],
          transformPerspective: [2e3, 2e3],
          transformOriginX: [0, 0],
          transformOriginY: [0, 0],
          rotateY: -180
        }]],
        reset: {
          transformPerspective: 0,
          transformOriginX: "50%",
          transformOriginY: "50%",
          rotateY: 0
        }
      },
      "transition.perspectiveRightIn": {
        defaultDuration: 950,
        calls: [[{
          opacity: [1, 0],
          transformPerspective: [2e3, 2e3],
          transformOriginX: ["100%", "100%"],
          transformOriginY: [0, 0],
          rotateY: [0, 180]
        }]],
        reset: {
          transformPerspective: 0,
          transformOriginX: "50%",
          transformOriginY: "50%"
        }
      },
      "transition.perspectiveRightOut": {
        defaultDuration: 950,
        calls: [[{
          opacity: [0, 1],
          transformPerspective: [2e3, 2e3],
          transformOriginX: ["100%", "100%"],
          transformOriginY: [0, 0],
          rotateY: 180
        }]],
        reset: {
          transformPerspective: 0,
          transformOriginX: "50%",
          transformOriginY: "50%",
          rotateY: 0
        }
      }
    };
    for (var j in e.RegisterEffect.packagedEffects) e.RegisterEffect.packagedEffects.hasOwnProperty(j) && e.RegisterEffect(j, e.RegisterEffect.packagedEffects[j]);
    e.RunSequence = function (a) {
      var b = f.extend(!0, [], a);
      b.length > 1 && (f.each(b.reverse(), function (a, c) {
        var d = b[a + 1];
        if (d) {
          var g = c.o || c.options,
            h = d.o || d.options,
            i = g && !1 === g.sequenceQueue ? "begin" : "complete",
            j = h && h[i],
            k = {};
          k[i] = function () {
            var a = d.e || d.elements,
              b = a.nodeType ? [a] : a;
            j && j.call(b, b), e(c);
          }, d.o ? d.o = f.extend({}, h, k) : d.options = f.extend({}, h, k);
        }
      }), b.reverse()), e(b[0]);
    };
  }(window.jQuery || window.Zepto || window, window, window ? window.document : undefined);
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
  var slider_options = {
    container: slider,
    speed: 450,
    autoplayButton: false,
    autoplay: autoplay,
    autoplayButtonOutput: false,
    loop: true,
    controlsText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
    rewind: true,
    autoHeight: autoHeight,
    mouseDrag: true,
    controls: show_controls,
    nav: show_nav,
    navPosition: nav_position,
    responsive: {
      1100: {
        items: desktop,
        slideBy: desktop,
        gutter: desktop_gutter
      },
      800: {
        items: laptop,
        slideBy: laptop,
        gutter: laptop_gutter
      },
      470: {
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
function animateValue(elem, start, end, duration) {
  // assumes integer values for start and end

  var obj = elem;
  var range = end - start;
  // no timer shorter than 50ms (not really visible any way)
  var minTimer = 50;
  // calc step time to show all interediate values
  var stepTime = Math.abs(Math.floor(duration / range));

  // never go below minTimer
  stepTime = Math.max(stepTime, minTimer);

  // get current time and calculate desired end time
  var startTime = new Date().getTime();
  var endTime = startTime + duration;
  var timer;
  function run() {
    var now = new Date().getTime();
    var remaining = Math.max((endTime - now) / duration, 0);
    var value = Math.round(end - remaining * range);
    obj.innerHTML = new Intl.NumberFormat(["ban", "id"]).format(value);
    if (value == end) {
      clearInterval(timer);
    }
  }
  timer = setInterval(run, stepTime);
  run();
}
(function ($, c) {
  $(function () {
    var counters = document.getElementsByClassName('counter');
    if (counters.length > 0) {
      for (var i = 0; i < counters.length; i++) {
        var elem = counters[i];
        $(elem).waypoint(function (direction) {
          if (direction === 'down') {
            var start = this.element.dataset.start ? parseInt(this.element.dataset.start) : 0,
              end = this.element.dataset.number ? parseInt(this.element.dataset.number) : 100,
              duration = this.element.dataset.duration ? parseInt(this.element.dataset.duration) : 1000;
            animateValue(this.element, start, end, duration);
          }
        }, {
          offset: 'bottom-in-view'
        });
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
    // HEADER -- FIXED
    // ****************************************************************************************************
    var $header = $('.header'),
      $logo = $('.header__logo__link img'),
      breakpoint = FLOATING_HEADER.breakpoint,
      initial_logo = $logo.attr('src'),
      change_logo = initial_logo != FLOATING_HEADER.logo;
    var initial_style = $header.attr('style');
    if (initial_style == undefined) {
      initial_style = 'style=""';
    }
    var change_style = initial_style != FLOATING_HEADER.style,
      change_text_color = FLOATING_HEADER.fixed_text_color != FLOATING_HEADER.floating_text_color;
    var floatingHeader = {
      isFixed: false,
      element: '.header',
      init: function init() {
        var xscrollTop = $(document).scrollTop();
        if (xscrollTop > breakpoint && !this.isFixed) {
          this.isFixed = true;
          floatingHeader.show();
        }
        $(window).scroll(function () {
          var xscrollTop = $(document).scrollTop();
          if (xscrollTop > breakpoint && !this.isFixed) {
            this.isFixed = true;
            floatingHeader.show();
          }
          if (xscrollTop < breakpoint && this.isFixed) {
            this.isFixed = false;
            floatingHeader.hide();
          }
        });
      },
      show: function show() {
        $(this.element).addClass('fixed');
        if (change_logo) $logo.attr('src', FLOATING_HEADER.logo);
        if (change_style) $header.attr('style', FLOATING_HEADER.style);
        if (change_text_color) $header.removeClass(FLOATING_HEADER.fixed_text_color).addClass(FLOATING_HEADER.floating_text_color);
      },
      hide: function hide() {
        $(this.element).removeClass('fixed');
        if (change_logo) $logo.attr('src', initial_logo);
        if (change_style) $header.attr('style', initial_style);
        if (change_text_color) $header.removeClass(FLOATING_HEADER.floating_text_color).addClass(FLOATING_HEADER.fixed_text_color);
      }
    };
    floatingHeader.init();
    // ****************************************************************************************************
    // ****************************************************************************************************
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

    $('.menu-movil__btn').sideNav({
      menuWidth: MV23_GLOBALS.mobile_menu_width,
      edge: MV23_GLOBALS.mobile_menu_position,
      closeOnClick: true,
      draggable: false
    });
    $('.menu-movil .sub-menu').css('display', 'none');
    $('.menu-movil li.menu-item-has-children').append('<button class="toogle-submenu"></button>');
    $('.menu-movil').on('click', '.toogle-submenu', function () {
      $(this).parent().children('.sub-menu').slideToggle();
    });

    // ****************************************************************************************************
    // SCROLLSPY
    // ****************************************************************************************************

    var elems = document.querySelectorAll('.scrollspy'),
      options = {
        activeClass: 'is-inview',
        scrollOffset: 0,
        throttle: 0,
        offsetTop: 100,
        offsetBottom: -500
      };
    $('.scrollspy').scrollSpy(options);

    // ****************************************************************************************************
    // ****************************************************************************************************
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
        $("html, body").animate({
          scrollTop: $(href).offset().top - headerHeight
        }, {
          duration: 800,
          queue: false,
          easing: 'easeOutCubic'
        });
      }
    });
    // ****************************************************************************************************
  });
})(jQuery, console.log);
(function ($, c) {
  $(function () {
    var animatedElements = $('[data-animation]');
    if (animatedElements.length > 0) {
      for (var i = 0; i < animatedElements.length; i++) {
        var elem = animatedElements[i];
        $(elem).css('opcaity', 0).waypoint(function (direction) {
          if (direction === 'down') {
            var animation = this.element.dataset.animation,
              delay = this.element.dataset.delay,
              options = {};
            options.delay = delay ? parseInt(delay) : 0;
            $(this.element).velocity(animation, options);
          }
        }, {
          offset: 'bottom-in-view'
        });
        $(elem).css('opcaity', 0).waypoint(function (direction) {
          if (direction === 'up') {
            $(this.element).velocity("reverse");
          }
        }, {
          offset: '95%'
        });
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
// GENERAL
(function ($, c) {
  var $portfolio1Listings = $('.posts-listing--show-expander');
  var headerHeight = MV23_GLOBALS.headerHeight;
  var expanderHeight = MV23_GLOBALS.expanderHeight;
  var scrollDuration = MV23_GLOBALS.listingPortfolioScrollDuration;
  if ($portfolio1Listings.length) {
    $portfolio1Listings.each(function (i, e) {
      var $listing = $(e),
        $listingItems = $listing.find('.post-card');

      // create expander elements
      var expanderResponse = '<div class="expander-response"></div>',
        closeBtn = '<div class="expander-close"></div>',
        expanderInner = '<div class="expander-inner container">' + expanderResponse + closeBtn + '</div>',
        loading = '<div class="expander-loading"></div>';
      $listing.on('click', '.expander-open', function (event) {
        event.preventDefault();
        var $postCard = $(this).parents('.post-card');
        var scrollTo = $listing.attr('data-on-click-post-scroll-to');
        var listingIsCarrusel = $listing.hasClass('posts-listing--carrusel');
        // where to add the expander
        var $expanderTarget = listingIsCarrusel ? $listing : $postCard;

        // reset all
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
                queue: false,
                easing: 'easeOutCubic'
              });
            }
          },
          success: function success(response) {
            var content = $('.main', response);
            if (response) {
              $expanderTarget.find('.expander-loading').remove();
              $expanderTarget.find('.expander-response').css('height', expanderHeight).html(content.html());

              // colorbox
              $expanderTarget.find('.expander-response .zoom').colorbox({
                rel: 'expander-group',
                maxHeight: "96%",
                maxWidth: "96%"
              });
            }
          }
        });
      });
      $listing.on('click', '.expander-close', function () {
        $listing.find('.expander').remove();
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
  var $components = $('.componente.listing');
  var current_lang = MV23_GLOBALS.lang;
  var loading_text = MV23_GLOBALS.listing_loading_text[current_lang];
  function do_the_ajax($component, terms, paged, post_template, per_page, $listing, $pagination, posttype, taxonomies, action, filterValues, order, orderby, offset, listing_template, wookey) {
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
        terms: filterValues.areParams ? filterValues.terms : terms,
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
        month: filterValues.month
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
            if (listing_template === 'carrusel') MV23_GLOBALS.carousels[tns_uid] = create_tns_slider($items_container[0]);
            if (action === 'append') MV23_GLOBALS.carousels[tns_uid].goTo('next');
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
          per_page = $component.attr("data-qty"),
          offset = $component.attr("data-offset"),
          order = $component.attr("data-order"),
          orderby = $component.attr("data-orderby"),
          wookey = $component.attr("data-wookey"),
          action = 'replace',
          filterValues = getFilterValues($filter);
        do_the_ajax($component, terms, paged, post_template, per_page, $listing, $pagination, posttype, taxonomies, action, filterValues, order, orderby, offset, listing_template, wookey);
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
          per_page = $component.attr("data-qty"),
          offset = $component.attr("data-offset"),
          order = $component.attr("data-order"),
          orderby = $component.attr("data-orderby"),
          wookey = $component.attr("data-wookey"),
          action = 'append',
          filterValues = getFilterValues($filter);
        do_the_ajax($component, terms, paged, post_template, per_page, $listing, $pagination, posttype, taxonomies, action, filterValues, order, orderby, offset, listing_template, wookey);
      });
      $component.on('click', '.posts-filter__submit', function (ev) {
        ev.preventDefault();
        var $pagination = $component.find('.pagination'),
          posttype = $component.attr("data-posttype"),
          taxonomies = $component.attr("data-taxonomies"),
          terms = $component.attr("data-terms"),
          post_template = $component.attr("post-template"),
          listing_template = $component.attr("listing-template"),
          per_page = $component.attr("data-qty"),
          offset = $component.attr("data-offset"),
          order = $component.attr("data-order"),
          orderby = $component.attr("data-orderby"),
          wookey = $component.attr("data-wookey"),
          paged = 1,
          action = 'replace',
          filterValues = getFilterValues($filter);
        do_the_ajax($component, terms, paged, post_template, per_page, $listing, $pagination, posttype, taxonomies, action, filterValues, order, orderby, offset, listing_template, wookey);
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
function animateWidth(elem, start, end, duration, spanElem) {
  var value = start;
  function run() {
    elem.style.width = value + '%';
    spanElem.innerHTML = value;
    if (value == end) {
      clearInterval(timer);
    }
    value++;
  }
  var timer = setInterval(run, 23);
  run();
}
(function ($, c) {
  $(function () {
    var progressBars = document.getElementsByClassName('progress-bar');
    if (progressBars.length > 0) {
      for (var i = 0; i < progressBars.length; i++) {
        var elem = progressBars[i];
        $(elem).waypoint(function (direction) {
          if (direction === 'down') {
            var bar = this.element.getElementsByClassName('progress-bar__bar')[0];
            var spanElem = this.element.getElementsByClassName('progress-bar__num')[0];
            var start = bar.dataset.start ? parseInt(bar.dataset.start) : 0,
              end = bar.dataset.number ? parseInt(bar.dataset.number) : 100,
              duration = bar.dataset.duration ? parseInt(bar.dataset.duration) : 1000;
            animateWidth(bar, start, end, duration, spanElem);
          }
        }, {
          offset: 'bottom-in-view'
        });
      }
    }
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
      if (MV23_GLOBALS.scrollIndicators && addIndicators == '1') scene.addIndicators();
    }
  });
})(jQuery, console.log);
(function ($, c) {
  document.addEventListener('DOMContentLoaded', function () {
    var sliderDeContenidos = $('.componente-slider-de-contenidos');
    var headerHeight = MV23_GLOBALS.headerHeight;
    for (var i = 0; i < sliderDeContenidos.length; i++) {
      var slider = $(sliderDeContenidos[i]).find('.slider-de-contenidos'),
        nav_position = $(slider[0]).attr('data-nav-position'),
        controls_position = $(slider[0]).attr('data-controls-position'),
        nav_show_title = $(slider[0]).attr('data-show-title'),
        show_controls = $(slider[0]).attr('data-show-controls'),
        show_nav = $(slider[0]).attr('data-show-nav');
      show_controls = show_controls == '1' ? true : false;
      show_nav = show_nav == '1' ? true : false;
      var slider_options = {
        container: slider[0],
        speed: 450,
        autoplayButton: false,
        autoplay: false,
        autoplayButtonOutput: false,
        autoHeight: true,
        mouseDrag: false,
        controls: show_controls,
        nav: show_nav,
        axis: 'horizontal',
        controlsPosition: controls_position,
        navPosition: nav_position,
        loop: false,
        rewind: true,
        controlsText: ['<i class="fa fa-caret-left"></i>', '<i class="fa fa-caret-right"></i>'],
        onInit: function onInit(info) {
          if (nav_show_title == '1') {
            $(info.navItems).each(function (i, e) {
              var title = $(info.slideItems).eq(i).attr('data-title');
              $(this).html(title);
            });
          }
        }
      };
      var daSlider = tns(slider_options);
      if ($(sliderDeContenidos[i]).attr('data-extended-bgi') == '1') {
        $(sliderDeContenidos[i]).attr('style', $(sliderDeContenidos[i]).find('.tns-slide-active').attr('data-style'));
        daSlider.events.on('transitionEnd', function (info, eventName) {
          $(info.container).parents('.componente-slider-de-contenidos').attr('style', $(info.slideItems[info.navCurrentIndex]).attr('data-style'));
        });
      }
      if ($(sliderDeContenidos[i]).attr('data-scroll-to-top') == '1') {
        daSlider.events.on('transitionStart', function (info, eventName) {
          $("html, body").animate({
            scrollTop: $(info.container).offset().top - headerHeight
          }, {
            duration: 800,
            queue: false,
            easing: 'easeOutCubic'
          });
        });
      }
    }
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
                queue: false,
                easing: 'easeOutCubic'
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
(function ($, c) {
  function youtube_parser(url) {
    var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
    var match = url.match(regExp);
    return match && match[7].length == 11 ? match[7] : false;
  }
  function vimeo_parser(url) {
    var regExp = /:\/\/(www\.)?vimeo.com\/(\d+)($|\/)/;
    var match = url.match(regExp);
    return match ? match[2] : false;
  }
  function dailymotion_parser(url) {
    var m = url.match(/^.+dailymotion.com\/(video|hub)\/([^_]+)[^#]*(#video=([^_&]+))?/);
    if (m !== null) {
      if (m[4] !== undefined) {
        return m[4];
      }
      return m[2];
    }
    return null;
  }
  document.addEventListener('DOMContentLoaded', function () {
    var currentVideo = null;

    // $('.has-video-background').hover(function(){
    // 	if (currentVideo != null) { currentVideo.trigger('pause') }

    // 	currentVideo = $(this).find('video');
    // 	currentVideo[0].play();

    // } , function(){
    //     currentVideo.trigger('pause');
    // });

    $('body').on('click', '.zoom-video', function (ev) {
      ev.preventDefault();
      var videoUrl = $(this).attr('href');
      var $videoWrapper = $('#video-modal').find('.video-responsive');
      var currentVideo = document.getElementById('video-modal__video');
      var source = document.createElement('source');
      var fuentes_externas = ['youtube', 'vimeo', 'dailymotion'];
      var fuente_del_video = '';
      for (var i = 0; i < fuentes_externas.length; i++) {
        if (videoUrl.indexOf(fuentes_externas[i]) != -1) {
          fuente_del_video = fuentes_externas[i];
          break;
        }
      }
      switch (fuente_del_video) {
        case 'youtube':
          var video_id = youtube_parser(videoUrl);
          if (video_id) {
            $videoWrapper.html('<iframe src="https://www.youtube.com/embed/' + video_id + '?rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe>');
          }
          $('#video-modal').modal('open');
          break;
        case 'vimeo':
          var video_id = vimeo_parser(videoUrl);
          if (video_id) {
            $videoWrapper.html('<iframe src="https://player.vimeo.com/video/' + video_id + '?autoplay=1&loop=1&autopause=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
          }
          $('#video-modal').modal('open');
          break;
        case 'dailymotion':
          var video_id = dailymotion_parser(videoUrl);
          if (video_id) {
            $videoWrapper.html('<iframe frameborder="0" src="//www.dailymotion.com/embed/video/' + video_id + '" allowfullscreen></iframe>');
          }
          $('#video-modal').modal('open');
          break;
        default:
          $videoWrapper.html('<video controls autoplay><source src="' + videoUrl + '"></video>');
          $('#video-modal').modal('open');
          break;
      }
    });
  });
})(jQuery, console.log);
(function ($, c) {
  var header_height = parseInt(MV23_GLOBALS.headerHeight);
  $(function () {
    // var header_height = $('.header__content').height();

    // ****************************************************************************************************
    // INIT MATERIALIZE SCRIPTS
    // ****************************************************************************************************

    $('.modal').modal({
      dismissible: true,
      opacity: .6,
      inDuration: 300,
      outDuration: MV23_GLOBALS.modal.outDuration,
      startingTop: '2%',
      endingTop: '5%',
      ready: function ready(modal, trigger) {
        $(trigger).css('z-index', 'initial');
      },
      complete: function complete(modal, trigger) {
        var empty_on_close = $(modal).hasClass('empty-on-close');
        if (empty_on_close) $(modal).find('.modal-content').empty();
        $('#video-modal .video-responsive').html('');
      }
    });
    $('.modal-trigger').modal();
    $('.modal-trigger').css('z-index', 25);
    // $('.select2').select2();

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
    // MOVE SCROLL IF HASH IN URL SCRIPT
    // ****************************************************************************************************

    function move_scroll_if_hash_in_url() {
      var url = document.location.toString();
      var hash = url.split('#')[1];
      if (hash) {
        var top = window.pageYOffset || document.documentElement.scrollTop;
        var elementTop = top - header_height;
        $("html, body").animate({
          scrollTop: elementTop
        }, 100);
      }
    }
    $(window).load(function (e) {
      if (!MV23_GLOBALS.isMobile) {
        move_scroll_if_hash_in_url();
      }
    });

    // ****************************************************************************************************
    // SCRIPT PARA MOVER EL RECAPTCHA A UN WRAPPER
    // ****************************************************************************************************

    $(window).load(function (e) {
      setTimeout(function () {
        $('.grecaptcha-badge').appendTo('.grecaptcha-badge-wrapper');
      }, 3500);
    });

    // ****************************************************************************************************
    // ****************************************************************************************************

    function colorbox_group() {
      $(".zoom").colorbox({
        rel: 'group1',
        maxHeight: "96%",
        maxWidth: "96%",
        onComplete: function onComplete() {
          // $('#cboxLoadedContent').zoom({ on:'click' });
        }
      });
    }
    colorbox_group();

    // ****************************************************************************************************
    // ****************************************************************************************************

    $('.cover-all').parent().css('position', 'relative');

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
      var links = $('.main').find('a[href*=".pdf"]');
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
      var links = $('.main').find('a[href*=".docx"], a[href*=".pptx"], a[href*=".xlsxs"]');
      convertir_docs(links);
    }

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

    $('.share-modal').appendTo('#share-modal-wrapper');
    // --------------------------------------------------------------------------------------------------------------
  });

  $(window).load(function () {
    // ****************************************************************************************************
    // ****************************************************************************************************

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