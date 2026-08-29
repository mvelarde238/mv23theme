window.gjsCarousel = function (editor) {
    const domc = editor.DomComponents;

    const createSliderUid = () => {
        const timestamp = Date.now().toString(16);
        const random = Math.random().toString(16).slice(2, 10);

        return `slider_${timestamp}${random}`;
    };

    const sliderSettingsToMap = (settings) => {
        const defaults = {
            slider_theme: 'theme1'
        };

        if (!Array.isArray(settings)) {
            return defaults;
        }

        settings.forEach(row => {
            if (!row || typeof row !== 'object') return;
            const key = row.property || row.__type;
            if (!key || !Object.prototype.hasOwnProperty.call(row, 'value')) return;
            defaults[key] = row.value;
        });

        return defaults;
    };

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
                classes: ['carousel__item', 'components-wrapper']
            },
        }, 
        view: {
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
                    { type: 'carousel' },
                    { type: 'carousel-controls' },
                    { type: 'carousel-nav' },
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
                const ignored_changes = ['__tab', '__hidden', 'marquee_settings'];
                if (changed_keys.length && ignored_changes.includes(changed_keys[0])) return;

                // For other changes, we can handle specific updates without full re-render
                this.handle_datastore_data();
            },
            handle_datastore_data() {
                const datastore = editor.getComponentDatastore(this.model);
                const {carousel_type, slider_settings} = datastore.toJSON();
                const settings = sliderSettingsToMap(slider_settings);
                const nav_position = settings.nav ? settings.nav_position : 'bottom';
                
                const el = this.el;
                const carousel = this.model.findType('carousel')[0];
                const carouselEl = carousel ? carousel.getEl() : null;
                if (!carouselEl) return;

                if (carousel_type === 'slider') {
                    // add slider theme
                    carouselEl.setAttribute('data-slider-theme', settings.slider_theme || 'theme1');

                    // handle controls visibility and position
                    if (settings.controls ){
                        carouselEl.setAttribute('data-controls-position', settings.controls_position || 'center');
                        const controls = el.querySelector('.tns-controls');
                        if (controls) controls.style.display = '';
                    } else {
                        const controls = el.querySelector('.tns-controls');
                        if (controls) controls.style.display = 'none';
                    }

                    // handle nav visibility and position
                    if (settings.nav ){
                        const nav = el.querySelector('.tns-nav');
                        if (nav) {
                            nav.style.display = '';
                            // Move nav to correct position (top or bottom of carousel slider)
                            const carouselNav = this.model.findType('carousel-nav')[0];
                            if (carouselNav) {
                                if (nav_position === 'top') {
                                    carouselNav.move(this.model,{at:0});
                                } else {
                                    carouselNav.move(this.model);
                                }
                            }
                        }
                    } else {
                        const nav = el.querySelector('.carousel__nav');
                        if (nav) nav.style.display = 'none';
                    }
                    carouselEl.setAttribute('data-nav-position', nav_position);

                } else if (carousel_type === 'marquee') {
                    carouselEl.setAttribute('data-theme', 'none');
                    const controls = el.querySelector('.carousel__controls');
                    const nav = el.querySelector('.carousel__nav');
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
                this.showCurrentSlideIndex();
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
            showCurrentSlideIndex() {
                const btn = this.el.querySelector('.select-cmp-parent');
                if (!btn) return;
                const totalPages = this.getTotalPages();
                const current = this.getCurrentSlideIndex();
                const displayCurrent = (typeof current === 'number' && totalPages > 0) ? (current + 1) : 0;
                const displayTotal = totalPages || 0;
                btn.textContent = `C - ${displayCurrent}/${displayTotal}`;
            },
            events: {
                'click .add-item-btn': 'addCarouselItem',
                'click .remove-item-btn': 'removeLastItem',
                'click [data-controls="next"]': 'selectNextSlide',
                'click [data-controls="prev"]': 'selectPrevSlide',
                'click .next-page': 'selectNextSlide',
                'click .prev-page': 'selectPrevSlide',
                'click .tns-nav button': 'onNavDotClick',
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
                this.model.set('__currentSlide', index, { noUndo: true });
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
                this.showCurrentSlideIndex();
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
                const nav = this.el.querySelector('.carousel__nav');
                if (!nav) return;

                // Don't show nav in marquee mode
                const datastore = editor.getComponentDatastore(this.model);
                const carouselType = datastore ? datastore.get('carousel_type') : 'slider';
                if (carouselType === 'marquee') return;

                // Dont show nav if nav settings is not enabled
                const sliderSettings = datastore ? sliderSettingsToMap(datastore.get('slider_settings')) : null;
                if (sliderSettings && !sliderSettings.nav) return;

                // rernder the carousel-nav component to update the number of dots based on the new total pages
                const carouselNav = this.model.findType('carousel-nav')[0];
                if (carouselNav) {
                    carouselNav.getView().render();
                }

                // if there are just one page, hide the nav
                const totalPages = this.getTotalPages();
                if (totalPages <= 1 && nav) {
                    nav.style.display = 'none';
                } else if (nav) {
                    nav.style.display = '';
                }

                // Clamp current page if it exceeds the new total pages after item removal
                if (this.getCurrentSlideIndex() >= totalPages) {
                    this.setCurrentSlideIndex(Math.max(0, totalPages - 1));
                }
                this.updateNavActive();
                this.showCurrentSlideIndex();
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
                this.showCurrentSlideIndex();
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
                            this.showCurrentSlideIndex();
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

    // Add a filter to generate a unique slider UID when a slider_uid group is created in the repeater. 
    // This ensures that each carousel has a unique identifier for its settings and state management.
    UltimateFields.addFilter('repeater_group_classes', function($args) {
        const datastore = $args && $args.datastore;
        if (!datastore || datastore.get('__type') !== 'slider_uid' || datastore.get('value')) {
            return;
        }
        datastore.set('value', createSliderUid());
    });

    // After component clone, change the slider_uid to a new unique value to avoid conflicts between cloned components.
    editor.on('afterComponentClone', (clonedComponent, newDatastore) => {
        const compType = clonedComponent.get('type');
        const compWithSliderSettings = ['carousel-wrapper', 'listing', 'gallery'];

        if ( compWithSliderSettings.includes(compType) && newDatastore) {
            const slider_settings = newDatastore.get('slider_settings');
            if (slider_settings && Array.isArray(slider_settings)) {
                const settings = slider_settings.map(row => ({ ...row }));
                const uidGroup = settings.find(row => row.__type === 'slider_uid');

                if (uidGroup) {
                    uidGroup.value = createSliderUid();
                    newDatastore.set('slider_settings', settings);
                }
            }
        }
    });
};