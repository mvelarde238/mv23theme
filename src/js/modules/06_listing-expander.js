window['ListingsExpander'] = (function() {
    let instances = [];

    const headerHeight = parseInt(MV23_GLOBALS.headerHeight, 10) || 0,
        scrollDuration = parseInt(MV23_GLOBALS.expanderScrollDuration, 10) || 500;

    const closeText = {
        'en': 'Close',
        'es': 'Cerrar',
        'fr': 'Fermer',
    };
    const contentLoadedText = {
        'en': 'Content loaded.',
        'es': 'Contenido cargado.',
        'fr': 'Contenu chargé.',
    };
    const contentLoadErrorText = {
        'en': 'Error loading content.',
        'es': 'Error al cargar el contenido.',
        'fr': 'Erreur lors du chargement du contenu.',
    };
    const expanderErrorMsg = {
        'en': 'Unable to load content. Please try again.',
        'es': 'No se pudo cargar el contenido. Inténtalo de nuevo.',
        'fr': 'Impossible de charger le contenu. Veuillez réessayer.',
    };

    function ListingExpander(el) {
        this.el = el;

        this.postsListing = this.el.querySelector('.posts-listing');
        if (!this.postsListing) return;

        this.allPostcards = this.postsListing.querySelectorAll('.postcard');
        if (!this.allPostcards.length) return;

        this.allTriggers = this.postsListing.querySelectorAll('.trigger-post-action');
        if (!this.allTriggers.length) return;

        this.currentPostcard = null;
        this.currentTrigger = null;
        this._loadToken = 0;
        this._loadController = null;

        try {
            this.listingArgs = JSON.parse(this.el.dataset.listingArgs || '{}');
        } catch (e) {
            this.listingArgs = {};
        }

        // Bind all private methods
		for (var fn in this) {
			if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
				this[fn] = this[fn].bind(this);
			}
		}

        this._init();
    }

    ListingExpander.prototype = {
        _init() {
            this._handleListingType();
            this._insertExpander();
            this._handleTriggers();
            this._handleCloseBtn();
            this._handleResize();
        },
        _handleListingType() {
            this.listingType = 'grid';

            if (this.postsListing.classList.contains('posts-listing--carousel')) {
                this.listingType = 'carousel';
            }
            if (this.postsListing.classList.contains('posts-listing--masonry')) {
                this.listingType = 'masonry';
            }
        },
        _insertExpander(){
            const expander = document.createElement('div'),
                listingId = this.el.id || 'listing-'+Math.floor(Math.random()*1000000),
                expanderId = 'expander-'+listingId,
                expanderResponse = '<div class="expander-response"></div>',
                closeBtn = '<button type="button" class="expander-close" aria-label="'+closeText[MV23_GLOBALS.lang]+'"></button>',
                loading = '<div class="expander-loading" hidden></div>',
                statusPolite = '<div class="expander-status sr-only" aria-live="polite" aria-atomic="true"></div>',
                statusAssertive = '<div class="expander-status-error sr-only" aria-live="assertive" aria-atomic="true"></div>',
                expanderInner = '<div class="expander-inner">'+closeBtn+expanderResponse+loading+statusPolite+statusAssertive+'</div>';
            
            expander.setAttribute('id', expanderId);
            expander.setAttribute('hidden', 'true');
            expander.classList.add('expander');
            expander.innerHTML = expanderInner;

            this.postsListing.insertAdjacentHTML('afterend', expander.outerHTML);            
            this.expander = document.getElementById(expanderId);
            this.expanderResponse = this.expander.querySelector('.expander-response');
            this.loadingIndicator = this.expander.querySelector('.expander-loading');
            this.closeBtn = this.expander.querySelector('.expander-close');
            this.statusPolite = this.expander.querySelector('.expander-status');
            this.statusAssertive = this.expander.querySelector('.expander-status-error');
        },
        _handleTriggers() {
            for (let i = 0; i < this.allTriggers.length; i++) {
                const trigger = this.allTriggers[i];

                trigger.setAttribute('aria-expanded', 'false');
                trigger.setAttribute('aria-controls', this.expander.id);

                // on click, if postcard is different than current hide the panel
                // this is because all triggers are using the same panel, so we need to close it before opening a new one
                // otherwise a click on a different postcard will close the panel
                trigger.addEventListener('click', (e) => {
                    e.preventDefault();
                    const isSamePostcard = this.currentTrigger && this.currentTrigger.closest('.postcard') === trigger.closest('.postcard');

                    if (!isSamePostcard && this.currentTrigger) {
                        Disclosure.close(this.currentTrigger);
                    }
                });

                Disclosure.create({
                    trigger: trigger,
                    panel: this.expander,
                    allTriggers: this.allTriggers,
                    focusFirstTabbable: true,
                    tabFocusTrap: true,
                    onOpenStart: ()=>{
                        this.currentTrigger = trigger;
                        this.currentPostcard = this.currentTrigger.closest('.postcard');
                        this.allPostcards.forEach(postcard => postcard.classList.remove('active'));

                        if( this.listingType == 'grid' ){
                            this._moveExpander();
                        }

                        // add aria-labelledby to the panel
                        const postcardTitle = this.currentPostcard.querySelector('h2');
                        if (postcardTitle && !postcardTitle.id) {
                            postcardTitle.id = 'title-' + Math.floor(Math.random() * 1000000);
                        }
                        this.expander.setAttribute('aria-labelledby', postcardTitle.id);
                    },
                    onOpenEnd: ()=>{
                        this.currentPostcard.classList.add('active');
                        this._loadContent();
                    },
                    onCloseEnd: ()=>{
                        this._cancelPendingLoad();
                        this.expanderResponse.innerHTML = '';
                        this.currentPostcard.classList.remove('active');
                        this.currentTrigger = null;
                        this.currentPostcard = null;
                    },
                });
            }
        },
        /**
         * For grid-based post listings, finds the last postcard in the same row
         * as the clicked postcard so the expander can be moved after it.
         * The expander then uses grid-column: 1 / -1 in CSS to span the full row.
         */
        _moveExpander() {
            // Detect the current device key (d/l/t/m) based on viewport width,
            // using the same breakpoints defined in the CSS variables
            const viewport = updateViewportDimensions();
            const breakpoints = { m: 480, t: 768, l: 992 };
            let device = 'd';
            for (const key in breakpoints) {
                if (viewport.width < breakpoints[key]) {
                    device = key;
                    break;
                }
            }

            // Read the column count for the active device from the CSS custom property
            // e.g. --d-columns:3, --l-columns:3, --t-columns:2, --m-columns:1
            const columns = parseInt( getComputedStyle( this.postsListing ).getPropertyValue(`--${device}-columns`).trim() ) || 1;

            // Determine the index of the clicked postcard within the listing
            const allPostcards = this.allPostcards;
            const postcardIndex = Array.prototype.indexOf.call(allPostcards, this.currentTrigger.closest('.postcard'));

            // Calculate the index of the last postcard in the same row,
            // clamped to the total number of postcards to handle incomplete rows
            const lastInRowIndex = Math.min( Math.floor(postcardIndex / columns) * columns + columns - 1, allPostcards.length - 1 );

            // Move the expander after the last postcard in the same row
            const lastInRowPostcard = allPostcards[lastInRowIndex];
            lastInRowPostcard.insertAdjacentElement('afterend', this.expander);
        },
        // Keeps the expander aligned with its row if the viewport crosses a breakpoint while it's open
        _handleResize() {
            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    if (this.listingType === 'grid' && this.currentTrigger && !this.expander.hidden) {
                        this._moveExpander();
                    }
                }, 150);
            });
        },
        _handleCloseBtn() {
            if (!this.closeBtn) return;

            this.closeBtn.addEventListener('click', () => {
                Disclosure.close( this.currentTrigger );
                this._cancelPendingLoad();
            });
        },
        // Cancels any in-flight fetch and scroll animation so a late response can't write into a closed/switched panel
        _cancelPendingLoad() {
            this._loadToken++;
            if (this._loadController) this._loadController.abort();
            this.loadingIndicator.hidden = true;
            this.expander.removeAttribute('aria-busy');
        },
        _announce(message, isError = false) {
            const region = isError ? this.statusAssertive : this.statusPolite;
            if (region) region.textContent = message;
        },
        // Smoothly scrolls to the postcard or the expander, per the listing's on_click_scroll_to/scrollTop config
        _maybeScrollToTarget() {
            const scrollTo = this.listingArgs.on_click_scroll_to;
            if (!this.listingArgs.scrollTop || (scrollTo !== 'postcard' && scrollTo !== 'expander')) return;

            const target = (scrollTo === 'postcard') ? this.currentPostcard : this.expander;
            if (!target) return;

            const targetY = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;
            animateScrollTo(targetY, scrollDuration);
        },
        _loadContent() {
            this.expander.setAttribute('aria-busy', 'true');
            this.loadingIndicator.hidden = false;
            const token = ++this._loadToken;
            this._loadController = new AbortController();

            this._maybeScrollToTarget();

            this._fetchPostContent(this.currentTrigger.href, this._loadController.signal)
                .then((html) => {
                    if (token === this._loadToken) this._renderContent(html);
                })
                .catch((error) => {
                    if (token !== this._loadToken || error.name === 'AbortError') return;
                    this._renderError();
                })
                .finally(() => {
                    if (token !== this._loadToken) return;
                    this.loadingIndicator.hidden = true;
                    this.expander.removeAttribute('aria-busy');
                });
        },
        _fetchPostContent(url, signal) {
            return fetch(url, { credentials: 'same-origin', signal }).then((response) => {
                if (!response.ok) throw new Error('HTTP ' + response.status);
                return response.text();
            });
        },
        _renderContent(html) {
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const main = doc.querySelector('.main');
            const containerStyles = doc.querySelectorAll('.container style');

            this.expanderResponse.innerHTML = main ? main.innerHTML : '';
            containerStyles.forEach(style => this.expanderResponse.prepend(style.cloneNode(true)));

            this._announce(contentLoadedText[MV23_GLOBALS.lang]);
        },
        _renderError() {
            this.expanderResponse.innerHTML = '<p class="expander-error-msg">' + expanderErrorMsg[MV23_GLOBALS.lang] + '</p>';
            this._announce(contentLoadErrorText[MV23_GLOBALS.lang], true);
        }
    };

    ListingExpander.create = function(el) {
        const instance = new ListingExpander(el);
        if (instance.el) {
            instances.push(instance);
        }
        return instance;
    };

    ListingExpander.init = function() {
        const listings = document.querySelectorAll('.listing--expander');
        for (let i = 0; i < listings.length; i++) {
            ListingExpander.create(listings[i]);
        }
        return instances;
    };

    ListingExpander.getInstances = function() {
        return instances;
    };

    return ListingExpander;
})();

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    ListingsExpander.init();
});