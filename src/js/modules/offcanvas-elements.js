window['OffCanvas_Elements'] = (function(){
    let instances = [];

    function Offcanvas_Element( element_data ){
        this.offcanvas_element_id = element_data.id;
        this.oce_post_id = element_data.post_id;
        this.offcanvas_element = document.querySelector( '#'+this.offcanvas_element_id );
        this.type = element_data.type;
        this.trigger_events = element_data.trigger_events || [];
        this.oce_settings = element_data.oce_settings;

        // Bind all private methods
		for (var fn in this) {
			if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
				this[fn] = this[fn].bind(this);
			}
		}

        this.M_instance = null;
        this.M_instance_options = []
        this._handle_on_open_start_property();
        // this._handle_callback_settings();
        this._create_the_M_instance();
        if( typeof this.M_instance === "object" ){
            this._handle_styles();
            this._handle_trigger_events();
            this._handle_close_on_click_setting();
        }
    }
    
    Offcanvas_Element.prototype = {
        _handle_on_open_start_property(){
            this.M_instance_options.onOpenStart = ()=>{
                // when the offcanvas is opened i need to check for dynamic content components to load their content
                let dynamic_content_components = this.offcanvas_element.querySelectorAll('.oce-dynamic-content');
                dynamic_content_components.length && dynamic_content_components.forEach( component => {
                    this._handle_async_settings( component, this.M_instance._openingTrigger );
                });
            };
        },
        _handle_async_settings(component, trigger){
            let async_settings = component.dataset.settings ? JSON.parse(component.dataset.settings) : {};
            if( typeof async_settings == "object" && Object.keys(async_settings).length > 0 ){
                let fetchUrl = []; // in case of page source i need to try in two paths pages/posts
                let errorMsg = [];
                let content_source = async_settings.content_source;

                switch (content_source) {
                    case 'page':
                        let _page_source = async_settings.page_source;
                        if(_page_source){
                            let contentId = _page_source.replace('post_','');
                            const pageUrl = `${MV23_GLOBALS.homeUrl}/wp-json/wp/v2/pages/${contentId}`;
                            const postUrl = `${MV23_GLOBALS.homeUrl}/wp-json/wp/v2/posts/${contentId}`;
                            fetchUrl = [ pageUrl, postUrl ]; 
                            errorMsg = [
                                'No se encontró como página, intentando como post...',
                                'No se encontró el contenido ni como página ni como post.'
                            ]
                        }
                        break;
                        
                    case 'url':
                        let url_source = async_settings.url_source;
                        if( url_source != '' ){
                            fetchUrl = [ url_source ];
                            errorMsg = [ 'No se encontró la url' ];
                        } 
                        break;
                        
                    case 'link':
                    default: 
                        // this need to be handled inside the onOpenStart callback to access the event target
                        break;
                }

                if( async_settings.clear_on_close ) component.innerHTML = "";

                this._check_async_attributes('beforeSend', component, async_settings);

                if( content_source === 'link' ){
                    let trigger_href = ( typeof trigger == 'object' && trigger.tagName == 'A' ) ? trigger.getAttribute('href') : '';
                    if( trigger_href != '' ){
                        fetchUrl = [ trigger_href ];
                        errorMsg = [ 'No se encontró la url del link' ];
                    }
                }

                if( fetchUrl.length > 0 ){
                    fetch(fetchUrl[0])
                        .then(response => this._handle_async_response(response, async_settings))
                        .then(data => this._handle_async_data(data, component, async_settings))
                        .catch(error => {
                            this._handle_async_error(error, errorMsg[0], component, true, async_settings);
                            return fetch(fetchUrl[1])
                                .then(response => this._handle_async_response(response, async_settings))
                                .then(data => this._handle_async_data(data, component, async_settings))
                                .catch(error => this._handle_async_error(error, errorMsg[1], component, true, async_settings));
                        });
                } else {
                    this._check_async_attributes('error', component, async_settings);
                    component.innerHTML = `<p class="center-align">Some setting in ${this.offcanvas_element_id} is wrong.<p>`;
                }
            }
        },
        _check_async_attributes( status, el, async_settings ){
            if( async_settings.attributes.length ){
                async_settings.attributes.forEach(item => {
                    if(item.status == status) this._assign_attribute(el,item.attribute,item.value);
                });
            }
        },
        _assign_attribute(domObject, selector, value) {
            if (!domObject || typeof selector !== 'string' || typeof value !== 'string') {
                throw new Error('Invalid arguments. Expecting a DOM object, a string selector and a string value.');
            }
        
            if (selector == 'id') {
                domObject.id = value;
            } else if (selector == 'class') {
                if( value.startsWith('-') ){
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
        _handle_async_response(response, async_settings) {            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            let content_source = async_settings.content_source;
    
            return (content_source == 'page') ? response.json() : response.text();
        },
        _handle_async_data(data, el, async_settings) {
            let content = '';

            this._check_async_attributes('success', el, async_settings);
    
            let content_source = async_settings.content_source;
            let load_on_iframe = async_settings.load_on_iframe;
    
            if( load_on_iframe ){
                let iframe_src = ( content_source == 'page' ) ? data.link : async_settings.url_source; 
    
                const divWrapper = document.createElement('div');
                divWrapper.className = "async-iframe-wrapper";
                const iframe = document.createElement('iframe');
                iframe.setAttribute("src", iframe_src);
                divWrapper.appendChild(iframe);
                content = divWrapper.outerHTML;
    
            } else {
                content = ( content_source == 'page' ) ? data.content.rendered : data;
    
                let cherry_pick_sections = async_settings.cherry_pick_sections;
                let cherry_picked_sections = async_settings.cherry_picked_sections;
                if(cherry_pick_sections && cherry_picked_sections != ''){
                    const divWrapper = document.createElement('div');
                    divWrapper.innerHTML = content;
                    content = '';
                    const sections = divWrapper.querySelectorAll( cherry_picked_sections );
                    sections.forEach(section => {
                        content += section.outerHTML;
                    });
                }
            }
    
            el.innerHTML = content;
            this._maybe_reflow_map_size(this.offcanvas_element);
            this._maybe_init_toggleboxes(el);
        },
        _handle_async_error(error, msg, el, debug, async_settings) {
            this._check_async_attributes('error', el, async_settings);
            if(debug) console.log(msg, error);
        },
        // _handle_callback_settings(){
            // let { M_instance_options, offcanvas_element } = this;

            // if (typeof this.oce_settings.on_open === 'string' && this.oce_settings.on_open.trim() !== '') {
            //     let callbackFunction = new Function('return ' + this.oce_settings.on_open)();
            //     if (typeof callbackFunction === 'function') {
            //         if( this.oce_settings.on_open ) M_instance_options.onOpenStart = callbackFunction;
            //     }
            // }
        // },
        _create_the_M_instance(){
            let { oce_settings, type, M_instance_options, offcanvas_element } = this;

            if( type === 'modal' ||  type === 'bottom_sheet'){
                M_instance_options.dismissible = oce_settings.dismissible;
                this.M_instance = M.Modal.init( offcanvas_element, M_instance_options );
            }
            if( type === 'sidenav'){
                M_instance_options.edge = oce_settings.position;
                M_instance_options.draggable = false;
                this.M_instance = M.Sidenav.init( offcanvas_element, M_instance_options );
            }
        },
        _handle_styles(){
            let { oce_settings, offcanvas_element, M_instance, type } = this;

            if( type === 'modal' || type === 'sidenav' ){
                if( oce_settings.max_width ) offcanvas_element.style.maxWidth = oce_settings.max_width+'px';
            }

            if( type === 'bottom_sheet' ){
                if( oce_settings.max_height ) offcanvas_element.style.maxHeight = oce_settings.max_height+'px';
            }
                
            let overlay = ( type === 'sidenav' ) ? M_instance._overlay : M_instance.$overlay[0];
            if( oce_settings.overlay_color.use ) overlay.style.backgroundColor = this._format_color(oce_settings.overlay_color.color, oce_settings.overlay_color.alpha);

            if( oce_settings.remove_modal_content_padding ){
                const modalContent = offcanvas_element.querySelector('.modal-content');
                if (modalContent) {
                    modalContent.style.padding = '0';
                }
            }
        },
        _format_color( color, alpha ){
            let formated_color = color;
            if( alpha != 100 ) formated_color = hexToRgba(color, alpha);
            return formated_color;
        },
        _handle_trigger_events(){
            let { offcanvas_element_id, trigger_events } = this;

            trigger_events.forEach(triggerData => {
                switch ( triggerData.__type ) {
                    case 'click':
                        this._handle_click_event( triggerData );                               
                        break;

                    case 'custom_event':
                        this._handle_custom_event( triggerData );
                        break;

                    case 'scroll':
                        let cookie_name = (triggerData.custom_cookie) ? triggerData.cookie_name : offcanvas_element_id+'-shown';
                        const storage_type = ( triggerData.custom_cookie ) ? triggerData.storage_type : 'session'; 
                        const storage = (storage_type === "session") ? sessionStorage : localStorage;

                        if( triggerData.settings_type == 'basic' ){
                            this._handle_basic_scroll_event( triggerData, storage, cookie_name );
                        }

                        if( triggerData.settings_type == 'gsap' && MV23_GLOBALS.scrollAnimations ){
                            this._handle_gsap_event( triggerData, storage, cookie_name );
                        }
                        break;
                
                    default:
                        console.log('No trigger events assigned to offcanvas element with ID:'+offcanvas_element_id);
                        break;
                }
            });
        },
        _handle_click_event( triggerData ){
            let { type, offcanvas_element_id, M_instance } = this;

            var triggers = document.querySelectorAll(triggerData.selector);

            if( triggers.length ){
                let triggerClass = ( type != 'bottom_sheet' ) ? type+'-trigger' : 'modal-trigger';
                triggers.forEach( trigger => {
                    trigger.classList.add( triggerClass );
                    trigger.dataset.target = offcanvas_element_id;
                    if ( type === 'sidenav' ) M_instance._openingTrigger = trigger;
                });
            };

            if( triggerData.delegate_to_body ){
                document.body.addEventListener('click', function(event) {
                    if (event.target.closest(triggerData.selector)) {
                        event.preventDefault();
                        M_instance._openingTrigger = event.target;
                        // i need send a cash $trigger to open method:
                        M_instance.open( $(event.target) ); 
                    }
                });
            } 
        },
        _handle_custom_event( triggerData ){
            let { M_instance } = this;
            let event_source = triggerData.event_source; 
            let event_name = (event_source == 'custom') ? triggerData.event_name : triggerData[event_source+'_event'];                            
            // dosnt work with woo events:
            // event_name && document.body.addEventListener(event_name, function() { instance.open(); }); 
            event_name && $(document.body).on( event_name, function(){ M_instance.open(); });
        },
        _handle_basic_scroll_event( triggerData, storage , cookie_name){
            let { M_instance } = this;

            // if visualization cookie is automatic show the element on every page reload
            if( !triggerData.custom_cookie ) storage.removeItem(cookie_name);

            $(window).scroll(function(){
                let xscrollTop = $(document).scrollTop();  
                if ( xscrollTop > triggerData.scroll_top && !storage.getItem(cookie_name) ) {
                    M_instance.open();
                    storage.setItem(cookie_name,'true');

                    if( triggerData.cookie_expiration ){
                        setTimeout(() => {
                            storage.removeItem(cookie_name);
                        }, triggerData.expiration_time);
                    }
                }
            });
        },
        _handle_gsap_event(triggerData, storage, cookie_name ){
            let { M_instance } = this;

            // cookie name need to be unique if there are two scroll triggers for the same offcanvas element
            if( !triggerData.custom_cookie ) cookie_name = '_'+cookie_name; 
            const gsap_settings = triggerData.gsap_settings;
            const trigger_element = document.querySelectorAll(gsap_settings.trigger_element);
            
            if( trigger_element.length ){
                gsap.registerPlugin(ScrollTrigger);
                const start = (gsap_settings.start_at.hook != 'custom') ? gsap_settings.start_at.hook : gsap_settings.start_at.custom_hook;
                const add_indicators = gsap_settings.add_indicators;

                for (let i = 0; i < trigger_element.length; i++) {

                    var scrollTriggerOptions = {
                        trigger: trigger_element[i],
                        start: start,
                        onEnter: function() {
                            if( triggerData.custom_cookie && storage.getItem(cookie_name) ) return;
                        
                            M_instance.open();
                            
                            if(triggerData.custom_cookie){
                                storage.setItem(cookie_name,'true');

                                if( triggerData.cookie_expiration ){
                                    setTimeout(() => {
                                        storage.removeItem(cookie_name);
                                    }, triggerData.expiration_time);
                                }
                            }
                        }
                    };

                    if( add_indicators ) scrollTriggerOptions.markers = true; 

                    ScrollTrigger.create(scrollTriggerOptions);
                }
            }
        },
        _handle_close_on_click_setting(){
            let { oce_settings, offcanvas_element, type } = this;
            let close_on_click = oce_settings.close_on_click || false;
            if( close_on_click ){
                let close_on_click_class = (type === 'sidenav') ? 'sidenav-close' : 'modal-close';
                offcanvas_element.querySelectorAll('a').forEach(element => {
                    element.classList.add(close_on_click_class);
                });
            }
        },
        _maybe_init_toggleboxes(el){
            var toggleboxes = el.getElementsByClassName('v23-togglebox');
            for (var i = 0; i < toggleboxes.length; i++) {
                var el = toggleboxes[i],
                    options = { headerHeight : MV23_GLOBALS.headerHeight };
                V23_ToggleBox.create( el, options );
            }
        },
        _maybe_reflow_map_size(el){
            var maps = el.getElementsByClassName('map__gmap');
            for (var i = 0; i < maps.length; i++) {
                let map = maps[i].mapObject,
                    provider = maps[i].dataset.provider;

                if( provider == 'leaflet' ) map.invalidateSize(false);
            }
        }
    }

    Offcanvas_Element.create = function( element_data ){
        let instance = new Offcanvas_Element( element_data );

        if( typeof instance.M_instance === "object" ){
            instances.push(instance);
            return instance;
        }
    }
    
    Offcanvas_Element.init = function( data ){
        data.forEach( element_data => {
            Offcanvas_Element.create( element_data );
        });

        return instances;
    }

    Offcanvas_Element.getInstances = function(){
        return instances;
    }

    Offcanvas_Element.getElementById = function( postId ){
        let _instance = null;
        postId && Offcanvas_Element.getInstances().forEach( _ins => {
            if( _ins.oce_post_id == postId ){
                _instance = _ins;
            }
        });
        return _instance;
    }

    return Offcanvas_Element;
})();

(function($,c){
    document.addEventListener('DOMContentLoaded', function() {
        OffCanvas_Elements.init( OFFCANVAS_ELEMENTS );

        /* 
        show an offcanvas element using a data attribute 
        E.g. data-offcanvas-element="238" 
        */
        document.querySelectorAll('[data-offcanvas-element]').forEach(element => {
            let OCE_element = OffCanvas_Elements.getElementById( element.dataset.offcanvasElement );
            OCE_element && element.addEventListener('click', (ev) => {
                ev.preventDefault();
                OCE_element.M_instance.open( this );
            });
        });
    });
})(jQuery,console.log); 