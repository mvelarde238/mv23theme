window.gjsListing = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'listing';

    // add custom css to canvasCss
    let config = editor.getConfig();
    config.canvasCss = config.canvasCss || '';
    config.canvasCss += `.listing a { pointer-events: none; }.listing .postcard { flex-shrink: 0; }`;
    editor.canvasCss = config.canvasCss;

    // Define the component
    domc.addType(compClass, {
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: 'Listing',
                tagName: 'div',
                classes: [compClass,'component']
            },
        },
        view: {
            onRender({el, model}) {
                const editorConfig = editor.getConfig(),
                	temporalCompStore = editorConfig.temporalCompStore || {},
					__tempID = model.get('__tempID');

				if (__tempID && temporalCompStore[__tempID]) {
					const datastore = temporalCompStore[__tempID].datastore;
					if (datastore) {
                        const view_template = temporalCompStore[__tempID].get('view_template');
                        if (view_template) {
                            const _view_template = _.template( view_template );
                            el.innerHTML = _view_template( datastore.toJSON() );
                            
                            const dont_load_posts_on_change = ['__tab','listing_template','columns_qty_wrapper','gap_wrapper','pagination_type','scrolltop','filter','category-filter','month-filter','carousel_settings_wrapper','on_click_post','on_click_scroll_to'];
                            // TODO: 'year-filter' is excluded for now because change on inital render. Need to investigate why.
                            const changed = datastore.changed;
                            // console.log('datastore changed:', changed); // e.g: {"__tab": "List Template"}
                            
                            let should_load_posts = true;

                            if( changed ){
                                for( const key in changed ){
                                    if( dont_load_posts_on_change.includes(key) ){
                                        should_load_posts = false;
                                        break;
                                    }
                                }
                            }

                            if(should_load_posts){
                                this.load_posts(datastore.toJSON(), el, model);
                            } else {
                                // use cached posts if available
                                const posts_cached = model.get('__temp_posts_cached') || null;
                                if (posts_cached) {
                                    const postsListing = el.querySelector('.posts-listing');
                                    if (postsListing) {
                                        postsListing.innerHTML = posts_cached;
                                    }
                                }
                            }
                        }
                    }
                }
            },
            load_posts( datastore, el, model ) {
                let formData = new FormData();

                let listing_args = {
                    source: datastore.source || 'auto',
                    posttype: datastore.posttype || 'post',
                    per_page: datastore.query_params.posts_per_page || -1,
                    post_template: datastore.post_template || '',
                    order: datastore.query_params.order || 'DESC',
                    orderby: datastore.query_params.orderby || 'date',
                    offset: datastore.query_params.offset || 0,
                    taxonomies: [],
                    terms: [],
                    wookey: '',
                    listing_template: datastore.listing_template || '',
                    on_click_post: '',
                    on_click_scroll_to: '',
                    pagination_type: ''
                };

                // prepare taxonomies
                const tax_params = datastore.tax_params || {};
                const posttype = listing_args.posttype;
                for( const tax_key in tax_params ){
                    const terms = tax_params[tax_key] || [];
                    if( tax_key.startsWith( posttype + '--' ) && Array.isArray(terms) && terms.length > 0 ){
                        const tax_name = tax_key.substring( (posttype + '--').length );
                        listing_args.taxonomies.push(tax_name);
                        if( Array.isArray(terms) && terms.length > 0 && terms[0] !== '' ){
                            listing_args.terms = terms;
                        }
                    }
                }

                // send posts if source is manual
                if( listing_args.source === 'manual' ){
                    listing_args.posts = datastore.posts || [];
                }

                formData.append('action', "load_posts");
                // formData.append('nonce', MV23_GLOBALS.nonce);
                // formData.append('lang', MV23_GLOBALS.lang);
                formData.append('lang', 'en');
                formData.append('listing_args', JSON.stringify(listing_args));
                formData.append('paged', 1);


                jQuery.ajax({
                    type: 'POST',
                    dataType: "json",
                    url: MV23_GLOBALS.ajaxUrl,
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function(){
                        // console.log(listing_args);
                    },
                    success: function(response) {
                        if(response.status === 'success'){
                            const postsListing = el.querySelector('.posts-listing');
                            if (postsListing) {
                                postsListing.innerHTML = response.posts;
                                model.set('__temp_posts_cached', response.posts);
                            }
                        }
                    }
                });
            }
        },
    });
}