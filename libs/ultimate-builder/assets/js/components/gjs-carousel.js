window.gjsCarousel = function (editor) {
    const domc = editor.DomComponents;

    // Editor's translator for internationalization
    const __ = editor.createTranslator(editor);

    domc.addType('carousel-item', {
        isComponent: el => el.classList && el.classList.contains('carousel__item'),
        model: {
            defaults: {
                name: 'Carousel Item',
                tagName: 'div',
                draggable: '.carousel__slider',
                removable: false,
                lockedComponents: false,
                classes: ['carousel__item', 'components-wrapper'],
                __wrapperReference: null,
            },
        }, 
        view: {
            onRender({ el, model }) {
                // wrap el with a temporal div with class "carousel-item-wrapper"
                // This is needed to properly initialize the carousel
                const wrapper = document.createElement('div');
                wrapper.className = 'carousel-item-wrapper';

                // Try to insert the wrapper once the element has a parentNode.
                // Sometimes GrapesJS triggers onRender before the component
                // is attached to the DOM, so el.parentNode can be null.
                let attempts = 0;
                const maxAttempts = 60; // ~1 second at 60fps

                const tryWrap = () => {
                    if (el.parentNode) {
                        try {
                            el.parentNode.insertBefore(wrapper, el);
                            wrapper.appendChild(el);
                            model.set('__wrapperReference', wrapper);
                        } catch (err) {
                            console.warn('Error wrapping carousel item:', err, el);
                        }
                    } else if (attempts++ < maxAttempts) {
                        requestAnimationFrame(tryWrap);
                    } else {
                        console.warn('Could not wrap carousel item: parentNode not found', el);
                    }
                };

                tryWrap();
            },
            removeItem: function(){
                let item = this.model,
                    carousel = item.parent(),
                    remove = true,
                    children = item.components(),
                    itemsQuantity = carousel.components();

                // Prevent deleting if it's the last item
                if (itemsQuantity.length <= 1) {
                    alert(__('Cannot delete the last item. A carousel must have at least one item.', 'last_item_alert'));
                    return;
                }

                const removeConfirmMsg = __('Are you sure you want to remove this item? There are components inside', 'remove_confirm_msg');

                if(children.length > 0) remove = confirm(removeConfirmMsg);

                if(remove){
                    // delete the corresponding html wrapper
                    const wrapper = item.get('__wrapperReference');
                    if(wrapper) wrapper.remove();
                    item.remove();
                } 
            }
        }
    });

    domc.addType('carousel', {
        isComponent: el => el.classList && el.classList.contains('carousel__slider'),
        model: {
            defaults: {
                name: 'Carousel Inner',
                tagName: 'div',
                draggable: false,
                droppable: '.carousel__item',
                removable: false,
                copyable: false,
                selectable: false,
                hoverable: false,
                classes: ['carousel__slider'],
                components: [
                    { type: 'carousel-item' },
                    { type: 'carousel-item' },
                    { type: 'carousel-item' },
                    { type: 'carousel-item' }
                ]
            },
        },
        view: {
            onRender({ el, model }) {
                el.style.display = 'flex';
                el.style.flexWrap = 'nowrap';
                el.style.overflowX = 'hidden';
            }
        }
    });

    // Map GrapesJS device ID to datastore items key
    const deviceToDatastoreKey = {
        'desktop': 'desktop',
        'laptop': 'laptop',
        'tablet': 'tablet',
        'mobileLandscape': 'mobile',
        'mobilePortrait': 'mobile'
    };

    domc.addType('carousel-wrapper', {
        isComponent: el => el.classList && el.classList.contains('carousel-wrapper'),
        model: {
            defaults: {
                name: 'Carousel',
                tagName: 'div',
                droppable: false,
                classes: ['carousel-wrapper', 'component', 'carousel'],
                components: [
                    { type: 'carousel' }
                ],
            }
        },
        view: {
            init() {
                this._onDeviceChange = () => {
                    this.setCurrentSlideIndex(0);
                    this.refreshNav();
                };
                editor.on('change:device', this._onDeviceChange);
            },
            removed() {
                editor.off('change:device', this._onDeviceChange);
            },
            custom_datastore_change_callback(changed) {
                // Ignore changes that don't affect the carousel's appearance or behavior
                const changed_keys = Object.keys(changed);
                const ignored_changes = [
                    '__tab', '__hidden', 'carousel_mode', 
                    'autoplay_settings', 'slider_uid',
                    'auto_height', 'touch', 'marquee_settings'
                ];
                if (changed_keys.length && ignored_changes.includes(changed_keys[0])) return;

                // For other changes, we can handle specific updates without full re-render
                this.handle_datastore_data();
            },
            handle_datastore_data() {
                const datastore = editor.getComponentDatastore(this.model);
                const {carousel_type, carousel_theme, controls_settings, nav_settings, customize_icons} = datastore.toJSON();
                const nav_position = nav_settings && nav_settings.show ? nav_settings.position : 'bottom';
                
                const el = this.el;
                const carousel = this.model.findType('carousel')[0];
                const carouselEl = carousel ? carousel.getEl() : null;
                if (!carouselEl) return;
    
                // append tns-controls and tns-nav if they don't exist
                if (!el.querySelector('.tns-controls')) {
                    this.appendTnsControls(el);
                }
                if (!el.querySelector('.tns-nav')) {
                    this.appendTnsNav(el, nav_position);
                }

                if (carousel_type === 'slider') {
                    // add carousel theme
                    el.setAttribute('data-theme', carousel_theme);

                    // handle controls visibility and position
                    if (controls_settings && controls_settings.show ){
                        el.setAttribute('data-controls-position', controls_settings.position);
                        const controls = el.querySelector('.tns-controls');
                        if (controls) controls.style.display = '';
                    } else {
                        const controls = el.querySelector('.tns-controls');
                        if (controls) controls.style.display = 'none';
                    }

                    // handle nav visibility and position
                    if (nav_settings && nav_settings.show ){
                        const nav = el.querySelector('.tns-nav');
                        if (nav) {
                            nav.style.display = '';
                            // Move nav to correct position (top or bottom of carousel slider)
                            const carouselSlider = el.querySelector('.carousel__slider');
                            if (carouselSlider) {
                                if (nav_position === 'top') {
                                    carouselSlider.before(nav);
                                } else {
                                    carouselSlider.after(nav);
                                }
                            }
                        }
                    } else {
                        const nav = el.querySelector('.tns-nav');
                        if (nav) nav.style.display = 'none';
                    }
                    el.setAttribute('data-nav-position', nav_position);

                    // handle custom icons
                    if (customize_icons && customize_icons.active) {
                        const prevIconName = customize_icons.prev_icon || 'fa-angle-left';
                        const nextIconName = customize_icons.next_icon || 'fa-angle-right';
                        const prevIconPrefix = prevIconName.split('-')[0];
                        const nextIconPrefix = nextIconName.split('-')[0];
                        const prevIconClass = prevIconPrefix === 'fa' ? 'fa ' + prevIconName : 'bi ' + prevIconName;
                        const nextIconClass = nextIconPrefix === 'fa' ? 'fa ' + nextIconName : 'bi ' + nextIconName;
                        const prevBtn = el.querySelector('[data-controls="prev"] i');
                        const nextBtn = el.querySelector('[data-controls="next"] i');
                        if (prevBtn) prevBtn.className = `${prevIconClass}`;
                        if (nextBtn) nextBtn.className = `${nextIconClass}`;
                    }

                } else if (carousel_type === 'marquee') {
                    el.setAttribute('data-theme', 'none');
                    const controls = el.querySelector('.tns-controls');
                    const nav = el.querySelector('.tns-nav');
                    if (controls) controls.style.display = 'none';
                    if (nav) nav.style.display = 'none';
                }

                // find .carousel__slider and add .has-columns
                if (carouselEl) {
                    carouselEl.classList.add('has-columns');
                    ['desktop', 'laptop', 'tablet', 'mobile'].map( device => {
                        const items = datastore.get( 'items' );
                        const gutter = datastore.get( 'gutter' );
                        const deviceKey = device.charAt(0);
                        const columnsValue = (carousel_type === 'marquee') ? 'auto' : items[device];
                        carouselEl.style.setProperty(`--${deviceKey}-columns`, columnsValue);
                        carouselEl.style.setProperty(`--${deviceKey}-gap`, gutter[device] + 'px');
                    });
                }
            },
            onRender({ el, model }) {
                // Listen for carousel item add/remove to refresh nav dots
                const carousel = model.findType('carousel')[0];
                if (carousel) {
                    this.stopListening(carousel.components());
                    this.listenTo(carousel.components(), 'add remove reset', () => {
                        this.refreshNav();
                    });
                }

                this.handle_datastore_data();
                this.appendComponentActions(el);
            },
            appendComponentActions(el){
                const actionDiv = document.createElement("div");
                actionDiv.classList = 'component__actions';

                [
                    { htmlTag: 'button', classList: 'cmp-action prev-page', title: __('Previous page'), text: '<' },
                    { htmlTag: 'button', classList: 'cmp-action remove-item-btn', title: __('Remove last item'), text: '-' },
                    { htmlTag: 'button', classList: 'cmp-action select-cmp-parent', title: __('Select carousel'), text: 'C' },
                    { htmlTag: 'button', classList: 'cmp-action add-item-btn', title: __('Add item'), text: '+' },
                    { htmlTag: 'button', classList: 'cmp-action next-page', title: __('Next page'), text: '>' }
                ].map( ({htmlTag, classList, title, text}) => {
                    const btn = document.createElement(htmlTag);
                    btn.classList = classList;
                    btn.setAttribute('title', title);
                    btn.appendChild(document.createTextNode(text));
                    actionDiv.appendChild(btn);
                });

                el.appendChild(actionDiv);
            },
            events: {
                'click .add-item-btn': 'addCarouselItem',
                'click .remove-item-btn': 'removeLastItem',
                'click .next-slide': 'selectNextSlide',
                'click .prev-slide': 'selectPrevSlide',
                'click .next-page': 'selectNextSlide',
                'click .prev-page': 'selectPrevSlide',
                'click .tns-nav button': 'onNavDotClick',
            },
            appendTnsControls: function(el) {
                const carouselSlider = el.querySelector('.carousel__slider');
                if (carouselSlider) {
                    const tnsControls = document.createElement('div');
                    tnsControls.className = 'tns-controls';
                    
                    const prevButton = document.createElement('button');
                    prevButton.className = 'prev-slide';
                    prevButton.setAttribute('data-controls', 'prev');
                    prevButton.innerHTML = '<i class="fa fa-angle-left"></i>';
                    
                    const nextButton = document.createElement('button');
                    nextButton.className = 'next-slide';
                    nextButton.setAttribute('data-controls', 'next');
                    nextButton.innerHTML = '<i class="fa fa-angle-right"></i>';
                    
                    tnsControls.appendChild(prevButton);
                    tnsControls.appendChild(nextButton);
                    carouselSlider.before(tnsControls);
                }
            },
            appendTnsNav: function(el, position) {
                const carouselSlider = el.querySelector('.carousel__slider');
                if (carouselSlider) {
                    const tnsNav = document.createElement('div');
                    tnsNav.className = 'tns-nav';

                    const totalPages = this.getTotalPages();

                    for (let i = 0; i < totalPages; i++) {
                        const btn = document.createElement('button');
                        btn.setAttribute('data-nav', i);
                        if (i === 0) btn.className = 'tns-nav-active';
                        tnsNav.appendChild(btn);
                    }

                    if (position === 'top') {
                        carouselSlider.before(tnsNav);
                    } else {
                        carouselSlider.after(tnsNav);
                    }
                }
            },
            getItemsPerPage() {
                const datastore = editor.getComponentDatastore(this.model);
                if (!datastore) return 1;
                const items = datastore.get('items');
                if (!items) return 1;
                const deviceId = editor.getDevice();
                const datastoreKey = deviceToDatastoreKey[deviceId] || 'desktop';
                return parseInt(items[datastoreKey], 10) || 1;
            },
            getTotalPages() {
                const carousel = this.model.findType('carousel')[0];
                if (!carousel) return 0;
                const totalItems = carousel.components().length;
                const itemsPerPage = this.getItemsPerPage();
                return Math.ceil(totalItems / itemsPerPage);
            },
            getCurrentSlideIndex() {
                return this.model.get('__currentSlide') || 0;
            },
            setCurrentSlideIndex(index) {
                this.model.set('__currentSlide', index);
            },
            goToSlide(pageIndex) {
                const carousel = this.model.findType('carousel')[0];
                if (!carousel) return;
                const totalPages = this.getTotalPages();
                if (totalPages === 0) return;

                // Clamp page index
                pageIndex = Math.max(0, Math.min(pageIndex, totalPages - 1));
                this.setCurrentSlideIndex(pageIndex);

                // Scroll to the first item of the target page
                const itemsPerPage = this.getItemsPerPage();
                const targetItemIndex = pageIndex * itemsPerPage;
                const targetItem = carousel.getChildAt(targetItemIndex);
                this.scrollToItem(targetItem);

                this.updateNavActive();
            },
            updateNavActive() {
                const navContainer = this.el.querySelector('.tns-nav');
                if (!navContainer) return;
                const currentIndex = this.getCurrentSlideIndex();
                navContainer.querySelectorAll('button').forEach((btn, i) => {
                    btn.classList.toggle('tns-nav-active', i === currentIndex);
                });
            },
            refreshNav() {
                const oldNav = this.el.querySelector('.tns-nav');
                if (!oldNav) return;

                // Don't show nav in marquee mode
                const datastore = editor.getComponentDatastore(this.model);
                const carouselType = datastore ? datastore.get('carousel_type') : 'slider';
                if (carouselType === 'marquee') return;

                // Dont show nav if nav settings is not enabled
                const navSettings = datastore ? datastore.get('nav_settings') : null;
                if (navSettings && !navSettings.show) return;

                oldNav.remove();

                const navPos = datastore ? (datastore.get('nav_settings')?.position || 'bottom') : 'bottom';
                this.appendTnsNav(this.el, navPos);

                // Clamp current page if it exceeds the new total
                const totalPages = this.getTotalPages();
                if (this.getCurrentSlideIndex() >= totalPages) {
                    this.setCurrentSlideIndex(Math.max(0, totalPages - 1));
                }
                this.updateNavActive();
            },
            scrollToItem(item, behavior = 'smooth') {
                editor.select(this.model); // Ensure carousel is selected to avoid scroll issues
                if (item && item.getView()) {
                    item.getView().el.scrollIntoView({ behavior, inline: 'start', block: 'nearest' });
                }
            },
            addCarouselItem(e){
                e.stopPropagation();
                const carousel = this.model.findType('carousel')[0];
                carousel.append({ type: 'carousel-item' });
                this.refreshNav();
                // Scroll to the newly added item
                const items = carousel.components();
                const newItem = items.at(items.length - 1);
                this.scrollToItem(newItem, 'instant');
                editor.select(newItem); 
            },
            removeLastItem(e){
                const carousel = this.model.findType('carousel')[0];
                const items = carousel.components();
                if(items.length > 0){
                    const lastItem = items.at(items.length - 1);
                    // Scroll instantly to the item about to be removed
                    this.scrollToItem(lastItem, 'instant');
                    // Double rAF ensures the browser has painted the scroll before the blocking confirm dialog
                    requestAnimationFrame(() => {
                        requestAnimationFrame(() => {
                            lastItem.getView().removeItem();
                            this.refreshNav();
                        });
                    });
                }
            },
            selectNextSlide(e){
                const totalPages = this.getTotalPages();
                if (totalPages === 0) return;
                const nextIndex = (this.getCurrentSlideIndex() + 1) % totalPages;
                this.goToSlide(nextIndex);
            },
            selectPrevSlide(e){
                const totalPages = this.getTotalPages();
                if (totalPages === 0) return;
                const prevIndex = (this.getCurrentSlideIndex() - 1 + totalPages) % totalPages;
                this.goToSlide(prevIndex);
            },
            onNavDotClick(e){
                const btn = e.target.closest('button[data-nav]');
                if (!btn) return;
                const index = parseInt(btn.getAttribute('data-nav'), 10);
                if (!isNaN(index)) this.goToSlide(index);
            }
        }
    });
}