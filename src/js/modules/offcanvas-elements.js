window['OffCanvas_Elements'] = (function(){

    function Offcanvas_Element( element_data ){
        this.offcanvas_element_id = element_data.id;
        this.offcanvas_element = document.querySelector( '#'+this.offcanvas_element_id );
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
        if( typeof this.M_instance === "object" ){
            this._handle_styles();
            this._handle_trigger_events();
        }
    }
    
    Offcanvas_Element.prototype = {
        _handle_async_settings(){
            if( this.content_type == 'async' && typeof this.async_settings == "object" ){
                let fetchUrl = []; // in case of page source i need to try in two paths pages/posts
                let errorMsg = [];
                let content_source = this.async_settings.content_source;

                switch (content_source) {
                    case 'page':
                        let _page_source = this.async_settings.page_source;
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
                        let url_source = this.async_settings.url_source;
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

                this.M_instance_options.onOpenStart = ()=>{
                    const el = this.M_instance.el;
                    const modal_content = el.querySelector('.modal-content');
                    if( this.async_settings.clear_on_close ) modal_content.innerHTML = "";

                    this._check_async_attributes('beforeSend', el);

                    if( content_source === 'link' ){
                        let trigger = this.M_instance._openingTrigger;
                        let trigger_href = ( typeof trigger == 'object' && trigger.tagName == 'A' ) ? trigger.getAttribute('href') : '';
                        if( trigger_href != '' ){
                            fetchUrl = [ trigger_href ];
                            errorMsg = [ 'No se encontró la url del link' ];
                        }
                    }

                    if( fetchUrl.length > 0 ){
                        fetch(fetchUrl[0])
                            .then(response => this._handle_async_response(response))
                            .then(data => this._handle_async_data(data, el))
                            .catch(error => {
                                this._handle_async_error(error, errorMsg[0], el, true)
                                return fetch(fetchUrl[1])
                                    .then(response => this._handle_async_response(response))
                                    .then(data => this._handle_async_data(data, el))
                                    .catch(error => this._handle_async_error(error, errorMsg[1], el, true));
                            });
                    } else {
                        this._check_async_attributes('error', el);
                        el.querySelector('.modal-content').innerHTML = `<p class="center-align">Some setting in ${this.offcanvas_element_id} is wrong.<p>`;
                    }
                };
            }
        },
        _check_async_attributes( status, el ){
            let { async_settings } = this;
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
        _handle_async_response(response) {
            let { async_settings } = this;

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            let content_source = async_settings.content_source;
    
            return (content_source == 'page') ? response.json() : response.text();
        },
        _handle_async_data(data, el) {
            let { async_settings } = this;
            let content = '';

            this._check_async_attributes('success', el);
    
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
    
            el.querySelector('.modal-content').innerHTML = content;
        },
        _handle_async_error(error, msg, el, debug) {
            this._check_async_attributes('error', el);
            if(debug) console.log(msg, error);
        },
        _handle_callback_settings(){
            if (typeof this.settings.on_open === 'string' && this.settings.on_open.trim() !== '') {
                let callbackFunction = new Function('return ' + this.settings.on_open)();
                if (typeof callbackFunction === 'function') {
                    if( this.settings.on_open ) M_instance_options.onOpenStart = callbackFunction;
                }
            }
        },
        _create_the_M_instance(){
            let { settings, type, M_instance_options, offcanvas_element } = this;

            if( type === 'modal' ||  type === 'bottom_sheet'){
                M_instance_options.dismissible = settings.dismissible;
                this.M_instance = M.Modal.init( offcanvas_element, M_instance_options );
            }
            if( type === 'sidenav'){
                M_instance_options.edge = settings.position;
                M_instance_options.draggable = false;
                this.M_instance = M.Sidenav.init( offcanvas_element, M_instance_options );
            }
        },
        _handle_styles(){
            let { settings, offcanvas_element, M_instance, type } = this;

            if( settings.background_color.use ) offcanvas_element.style.backgroundColor = this._format_color(settings.background_color.color, settings.background_color.alpha);
            if( settings.max_width ) offcanvas_element.style.maxWidth = settings.max_width+'px';
            if( settings.max_height ) offcanvas_element.style.maxHeight = settings.max_height+'px';
            if( settings.background_color.color_scheme && settings.background_color.color_scheme != '' ){
                let color_scheme_class = ( settings.background_color.color_scheme === 'dark-scheme' ) ? 'text-color-2' : 'text-color-1';
                offcanvas_element.classList.add( color_scheme_class );
            }
                
            let overlay = ( type === 'sidenav' ) ? M_instance._overlay : M_instance.$overlay[0];
            if( settings.overlay_color.use ) overlay.style.backgroundColor = this._format_color(settings.overlay_color.color, settings.overlay_color.alpha);
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

                        if( triggerData.settings_type == 'scrollmagic' && MV23_GLOBALS.scrollAnimations ){
                            this._handle_scrollmagic_event( triggerData, storage, cookie_name );
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
        _handle_scrollmagic_event(triggerData, storage, cookie_name ){
            let { M_instance } = this;

            // cookie name need to be unique if there are two scroll triggers for the same offcanvas element
            if( !triggerData.custom_cookie ) cookie_name = '_'+cookie_name; 
            const scrollmagic_settings = triggerData.scrollmagic_settings;
            const trigger_element = document.querySelectorAll(scrollmagic_settings.trigger_element);
            
            if( trigger_element.length ){
                var controller = new ScrollMagic.Controller();
                const trigger_hook = scrollmagic_settings.trigger_hook;
                const offset = scrollmagic_settings.offset;
                const add_indicators = scrollmagic_settings.add_indicators;

                for (let i = 0; i < trigger_element.length; i++) {
                    var scene = new ScrollMagic.Scene({
                        triggerElement: trigger_element[i], 
                        triggerHook: trigger_hook, 
                        offset: offset
                    })
                    .addTo(controller);

                    scene.on("enter", function (event) {
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
                    });

                    if( MV23_GLOBALS.scrollIndicators && add_indicators ) scene.addIndicators(); 
                }
            }
        }
    }

    Offcanvas_Element.init = function( data ){
        data.forEach( element_data => {
            let instance = new Offcanvas_Element(element_data);
        });
    }

    return Offcanvas_Element;
})();

(function($,c){
    document.addEventListener('DOMContentLoaded', function() {
        OffCanvas_Elements.init( OFFCANVAS_ELEMENTS );
    });
})(jQuery,console.log); 