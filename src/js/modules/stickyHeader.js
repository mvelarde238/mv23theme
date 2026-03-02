const stickyHeader = {
    isSticky: false,
    element: null,
    breakpoint: null,
    windowContext: null,
    init: function (element, windowContext, breakpoint) {
        this.element = element;
        if( 
            !this.element || typeof element !== 'object' || 
            !windowContext || typeof windowContext !== 'object' || 
            !breakpoint || typeof breakpoint !== 'number') {
            return;
        }

        this.breakpoint = breakpoint;
        this.windowContext = windowContext;

        const xscrollTop = windowContext.pageYOffset;
        if (xscrollTop > this.breakpoint && !this.isSticky) {
            this.isSticky = true;
            this.show();
        }
        windowContext.addEventListener('scroll', () => {
            const xscrollTop = windowContext.pageYOffset;
            if (xscrollTop > this.breakpoint && !this.isSticky) {
                this.isSticky = true;
                this.show();
            }
            if (xscrollTop < this.breakpoint && this.isSticky) {
                this.isSticky = false;
                this.hide();
            }
        });

        windowContext.addEventListener('resize', () => {
            this.setHeaderHeight();
        });

        // Set initial header height
        this.setHeaderHeight(0);

        // if header has data-adjust-scroll-position attribute enable scroll position adjustment
        if (this.element.hasAttribute('data-adjust-scroll-position')) {
            setTimeout(() => {
                const hash = windowContext.location.hash;
                if (hash) {
                    this.adjustScrollPosition(hash);
                }
            }, 400);
        }
    },
    show: function () {
        this.element.classList.remove('header--static');
        this.element.classList.add('header--sticky');
        this.setHeaderHeight();
    },
    hide: function () {
        this.element.classList.remove('header--sticky');
        this.element.classList.add('header--static');
        this.setHeaderHeight();
    },
    setHeaderHeight: function (timeout = 800) {
        const root = this.windowContext.document.querySelector(':root');

        setTimeout(() => { // let css transitions change elements height
            const key = this.element.classList.contains('header--sticky') ? 'sticky' : 'static';
            const header_height = this.element.offsetHeight;

            root.style.setProperty('--' + key + '-header-height', header_height + 'px');
        }, timeout);
    },
    adjustScrollPosition: function (anchor) {
        const targetElement = this.windowContext.document.querySelector(anchor);
        if (targetElement) {
            let header = this.element;
            let sticky_header_height = header.offsetHeight;

            const headerHeight = parseInt(sticky_header_height);
            const elementPosition = targetElement.getBoundingClientRect().top + this.windowContext.scrollY;

            this.windowContext.scrollTo({
                top: elementPosition - headerHeight,
                behavior: "smooth"
            });
        }
    }
};