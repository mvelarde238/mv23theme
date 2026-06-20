/**!
 * MV23 Marquee
 * @author  Mvelarde   <miguel@velarde23.com>
 * @license MIT
 */

window["MV23_Marquee"] = (function() {
	"use strict";

	var instances = [];

	/**
	 * @class  MV23_Marquee
	 * @param  {HTMLElement}  el
	 * @param  {Object}       [options]
	 */
	function MV23_Marquee(el, options) {
		if (!(el && el.nodeType && el.nodeType === 1)) {
			console.log('MV23_Marquee Error: `el` must be an HTMLElement, not ' + {}.toString.call(el));
			return;
		}

		this.el    = el;
		this.track = el.querySelector('.marquee-track');
		this.items = [...this.track.children]; // original items only, before cloning

		this._isDragging = false;
		this._isHovered  = false;

		this._handleOptions(options);

		// Bind all private methods so they can be safely passed as callbacks
		for (var fn in this) {
			if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
				this[fn] = this[fn].bind(this);
			}
		}

		this._fillTrack();
		this._initAnimation();
		this._attachResizeEvents();
		this._attachHoverEvents();
		this._attachDragEvents();
	}

	MV23_Marquee.prototype = {
		_handleOptions(options) {
			var dataOptions = {};
			if (this.el.dataset.speed)     dataOptions.speed     = parseInt(this.el.dataset.speed);
			if (this.el.dataset.direction) dataOptions.direction = this.el.dataset.direction;

			var defaults = {
				speed:     40,
				direction: 'left'
			};

			this.options = { ...defaults, ...(options || {}), ...dataOptions };
		},

		_trackWidth() {
			return this.track.scrollWidth;
		},

		_fillTrack() {
			const originalWidth  = this._trackWidth();
			const repetitions    = Math.ceil(window.innerWidth / originalWidth) + 1; // +1 for buffer
			for (let i = 0; i < repetitions; i++) {
				this.items.forEach(item => this.track.appendChild(item.cloneNode(true)));
			}
		},

		_initAnimation() {
			const { speed, direction } = this.options;
			this.timeline = gsap.timeline({ repeat: -1 });
			this.timeline.to(this.track, {
				x:        () => direction === 'left' ? `-${this._trackWidth() / 2}px` : `${this._trackWidth() / 2}px`,
				duration: speed,
				ease:     "none"
			});
		},

		_resetAnimation() {
			const { speed, direction } = this.options;
			this.timeline.kill();
			this.track.innerHTML = '';
			this.items.forEach(w => this.track.appendChild(w.cloneNode(true)));
			this._fillTrack();
			this.timeline.clear();
			this.timeline.to(this.track, {
				x:        () => direction === 'left' ? `-${this._trackWidth() / 2}px` : `${this._trackWidth() / 2}px`,
				duration: speed,
				ease:     "none"
			});
		},

		_attachResizeEvents() {
			window.addEventListener('resize', this._resetAnimation);
		},

		_attachHoverEvents() {
			this.el.addEventListener('mouseenter', () => {
				this._isHovered = true;
				if (!this._isDragging) this.timeline.timeScale(0);
			});
			this.el.addEventListener('mouseleave', () => {
				this._isHovered = false;
				if (!this._isDragging) {
					this.timeline.timeScale(1);
					this.timeline.play();
				}
			});
		},

		_attachDragEvents() {
			let dragStartX  = 0;
			let trackStartX = 0;
			let hasDragged  = false;

			this.el.addEventListener('pointerdown', (e) => {
				this._isDragging = true;
				hasDragged       = false;
				dragStartX       = e.clientX;
				trackStartX      = gsap.getProperty(this.track, "x");
				this.timeline.pause();
				// setPointerCapture se aplica solo cuando hay drag real (ver pointermove)
			});

			this.el.addEventListener('pointermove', (e) => {
				if (!this._isDragging) return;
				const delta = e.clientX - dragStartX;
				if (!hasDragged && Math.abs(delta) < 5) return;
				if (!hasDragged) this.el.setPointerCapture(e.pointerId); // capturar solo al cruzar el umbral
				hasDragged = true;
				this.el.style.cursor = 'grabbing';

				let newX             = trackStartX + delta;
				const halfWidth      = this._trackWidth() / 2;
				const { direction }  = this.options;

				// Wrap position so the loop stays seamless
				newX = direction === 'left'
					? -(((-newX % halfWidth) + halfWidth) % halfWidth)
					:  (( newX  % halfWidth) + halfWidth) % halfWidth;

				gsap.set(this.track, { x: newX });
			});

			const onPointerUp = () => {
				if (!this._isDragging) return;
				this._isDragging    = false;
				this.el.style.cursor = '';

				if (hasDragged) {
					// Sync timeline progress to the dragged position before resuming
					const currentX       = gsap.getProperty(this.track, "x");
					const halfWidth      = this._trackWidth() / 2;
					const { speed, direction } = this.options;
					const seekTime = direction === 'left'
						? (Math.abs(currentX) / halfWidth) * speed
						: (currentX           / halfWidth) * speed;
					this.timeline.seek(seekTime % speed);
				}

				// Only resume if the cursor has left the marquee
				if (!this._isHovered) {
					this.timeline.timeScale(1);
					this.timeline.play();
				}
			};

			this.el.addEventListener('pointerup',     onPointerUp);
			this.el.addEventListener('pointercancel', onPointerUp);
		}
	};

	/**
	 * Create a single MV23_Marquee instance
	 * @param  {HTMLElement}  el
	 * @param  {Object}       [options]
	 */
	MV23_Marquee.create = function(el, options) {
		var instance = new MV23_Marquee(el, options || {});
		if (instance.el) instances.push(instance);
		return instance;
	};

	/**
	 * Auto-initialize all .marquee elements on the page
	 * @param  {Object}  [options]
	 */
	MV23_Marquee.init = function(options) {
		if (!MV23_GLOBALS.scrollAnimations) return;
		var marquees = document.querySelectorAll('.marquee');
		marquees.forEach(el => MV23_Marquee.create(el, options));
		return instances;
	};

	return MV23_Marquee;
})();

document.addEventListener('DOMContentLoaded', function() {
	MV23_Marquee.init();
});