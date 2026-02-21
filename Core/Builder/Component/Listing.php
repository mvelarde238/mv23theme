<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use WP_Query;
use Core\Frontend\Pagination;

class Listing extends Component {

    public function __construct() {
		parent::__construct(
			'listing',
			__( 'Listing', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'dashicons-screenoptions';
    }

    public static function get_builder_data() {
        return array(
            'block_render_type' => 'listing',
            'custom_datastore_change_callback' => true
		);
    }

    public static function get_listing_taxonomies() {
        $listing_taxonomies = LISTING_TAXONOMIES;

        if(WOOCOMMERCE_IS_ACTIVE){
            array_push($listing_taxonomies, array(
                'cpt_slug' => 'product', 
                'slug' => 'product_cat'
            ));
        } 

        if(USE_PORTFOLIO_CPT){
            array_push($listing_taxonomies, array(
                'cpt_slug' => 'portfolio', 
                'slug' => 'portfolio-cat'
            ));
        }

        if(USE_DOCUMENT_CPT){
            array_push($listing_taxonomies, array(
                'cpt_slug' => 'document', 
                'slug' => 'document-cat'
            ));
        }

        return $listing_taxonomies;
    }

	public static function get_fields() {
        $listing_cpts = LISTING_CPTS;
        if(WOOCOMMERCE_IS_ACTIVE) $listing_cpts['product'] = 'Productos';
        if(USE_DOCUMENT_CPT) $listing_cpts['document'] = 'Documentos';
        if(USE_PORTFOLIO_CPT) $listing_cpts['portfolio'] = 'Portfolio';

        $listing_taxonomies = Listing::get_listing_taxonomies();

        $listing_post_template = LISTING_POST_TEMPLATE;
        $listing_post_template['document'] = 'Document';
        $listing_post_template['portfolio'] = 'Portfolio';
        if(WOOCOMMERCE_IS_ACTIVE) $listing_post_template['woocommerce1'] = 'WooCommerce Product Basic';

        $listing_fields_1 = array( 
            Field::create( 'tab', 'content_tab', __('Content','mv23theme') ),
            Field::create( 'radio', 'source', __('Source','mv23theme'))
                ->set_orientation( 'horizontal' )
                ->set_default_value('auto')
                ->add_options( array(
                    'auto'=>__('Automatic (Latest published posts)','mv23theme'),
                    'manual'=>'Manual',
                )),
            Field::create( 'wp_objects', 'posts', '' )->set_button_text( __('Select the posts','mv23theme') )->add_dependency('source','manual','='),
        
            Field::create( 'select', 'posttype', __('Post type','mv23theme') )
                ->add_options($listing_cpts)
                ->set_default_value('post')
                ->add_dependency('source','auto','=')
        );

        if(WOOCOMMERCE_IS_ACTIVE){
            $woocommerce_keys_field = Field::create('select','woocommerce_key','WooCommerce Tag')
                ->add_dependency('source','auto','=')
                ->add_dependency('../posttype', 'product', '=')
                ->add_options(array(
                    '' => __('None','mv23theme'),
                    'featured' => __('Featured','mv23theme'),
                    'on_sale' => __('On Sale','mv23theme'),
                    'best_selling' => __('Best Selling','mv23theme')
                ))->set_width(16);

            array_push($listing_fields_1, $woocommerce_keys_field);
        }

        if( is_array($listing_taxonomies) && count($listing_taxonomies) > 0 ){
            $tax_params = Field::create( 'complex', 'tax_params', __('Categories','mv23theme') )
                ->hide_label()
                ->add_dependency('source','auto','=');

            foreach($listing_taxonomies as $tax){
                $tax_params->add_fields( array(
                    Field::create( 'multiselect', $tax['cpt_slug'] .'--'. $tax['slug'] )->add_terms( $tax['slug'] )
                        ->add_dependency('../posttype', $tax['cpt_slug'], '=')
                        ->set_width(20)
                ));
            }
            array_push($listing_fields_1, $tax_params);
        }

        $width_25 = 'width: 25%; min-width: initial;';
        $width_50 = 'width: 50%; min-width: initial;';

        $listing_fields_2 = array( 
            Field::create( 'tab', 'query_settings_tab', __('Query Settings','mv23theme')),
            Field::create( 'complex', 'query_params', '' )->add_fields(array(
                Field::create( 'number', 'posts_per_page', __('Number of posts','mv23theme') )->set_default_value(3)->set_attr('style', $width_50),
                Field::create( 'select', 'order', __('Order','mv23theme') )->add_options(array(
                    'DESC' => __('Descending','mv23theme'),
                    'ASC' => __('Ascending','mv23theme')
                ))->set_attr('style', $width_50),
                Field::create( 'select', 'orderby', __('Order by','mv23theme') )->add_options(array(
                    'date' => __('Date','mv23theme'),
                    'title' => __('Title','mv23theme'),
                    'name' => __('Name','mv23theme'),
                    'rand' => __('Random','mv23theme'),
                    'menu_order' => __('Custom','mv23theme'),
                    // 'comment_count' => __('Comentarios','mv23theme')
                ))->set_attr('style', $width_50),
                Field::create( 'number', 'offset', 'Offset' )->set_attr('style', $width_50),
            ))->add_dependency('source','auto','='),

            Field::create( 'complex', 'status_params', '' )->add_fields(array(
                Field::create( 'checkbox', 'set_post_status' )
                    ->set_text( __('Set post status','mv23theme') )
                    ->hide_label()->fancy(),
                Field::create( 'multiselect', 'post_status' )->add_options(array(
                    'publish' => __('Published','mv23theme'),
                    'draft' => __('Draft','mv23theme'),
                    'pending' => __('Pending','mv23theme'),
                    'future' => __('Scheduled','mv23theme'),
                    'private' => __('Private','mv23theme'),
                    'inherit' => __('Inherit','mv23theme'),
                    'trash' => __('Trash','mv23theme')
                ))->add_dependency('set_post_status')->hide_label()
            ))->add_dependency('source','auto','='),

            Field::create( 'tab', 'listing_template_tab', __('Listing Template','mv23theme')),
            Field::create( 'select', 'listing_template', 'Template' )->add_options(LISTING_TEMPLATES),
            
            Field::create( 'complex', 'columns', __('Columns Quantity','mv23theme') )->add_fields(array(
                Field::create( 'number', 'desktop', __('Desktop','mv23theme') )->set_minimum(1)->set_maximum(12)->set_default_value(LISTING_COLUMNS['desktop'])->set_attr('style', $width_25),
                Field::create( 'number', 'laptop', __('Laptop','mv23theme') )->set_minimum(1)->set_maximum(12)->set_default_value(LISTING_COLUMNS['laptop'])->set_attr('style', $width_25),
                Field::create( 'number', 'tablet', __('Tablet','mv23theme') )->set_minimum(1)->set_maximum(12)->set_default_value(LISTING_COLUMNS['tablet'])->set_attr('style', $width_25),
                Field::create( 'number', 'mobile', __('Mobile','mv23theme') )->set_minimum(1)->set_maximum(12)->set_default_value(LISTING_COLUMNS['mobile'])->set_attr('style', $width_25)
            )),
            
            Field::create( 'complex', 'columns_gap', __('Space between columns','mv23theme') )->add_fields(array(
                Field::create( 'number', 'desktop', __('Desktop','mv23theme') )->set_default_value(LISTING_GAP['desktop'])->set_attr('style', $width_25),
                Field::create( 'number', 'laptop', __('Laptop','mv23theme') )->set_default_value(LISTING_GAP['laptop'])->set_attr('style', $width_25),
                Field::create( 'number', 'tablet', __('Tablet','mv23theme') )->set_default_value(LISTING_GAP['tablet'])->set_attr('style', $width_25),
                Field::create( 'number', 'mobile', __('Mobile','mv23theme') )->set_default_value(LISTING_GAP['mobile'])->set_attr('style', $width_25)
            )),

            Field::create( 'tab', 'carousel_settings_tab', __('Carousel Settings','mv23theme'))->add_dependency('listing_template','carousel','='),
            Field::create( 'complex', 'carousel_settings' )->hide_label()->add_fields(array(
                Field::create( 'checkbox', 'show_controls' )->hide_label()->set_text(__('Show controls','mv23theme')),
                Field::create( 'checkbox', 'show_nav' )->hide_label()->set_text(__('Show carousel nav','mv23theme')),
                Field::create( 'checkbox', 'autoplay' )->hide_label()->set_text(__('Start automatically','mv23theme')),
                Field::create( 'text', 'carousel_id' )->set_prefix(__('Carousel ID','mv23theme'))->hide_label(),
            ))->add_dependency('listing_template','carousel','='),
            
            Field::create( 'tab', 'postcard_settings_tab', __('Post Card Settings','mv23theme')),
            Field::create( 'complex', 'postcard_settings' )
			    ->hide_label()
			    ->add_fields(array(
			    	Field::create( 'radio', 'template' )->set_orientation( 'vertical' )->add_options($listing_post_template), 
        	    	Field::create( 'select', 'on_click_post', __('On click the post card:','mv23theme') )->add_options(array(
        	            'redirect' => 'Redirigir a la página del post',
        	            'show-expander' => 'Mostrar el post en la misma página',
                	    'show-popup' => 'Mostrar el post en un popup',
                	    'none' => 'Ninguna'
                	)),
                	Field::create( 'select', 'on_click_scroll_to', __('On click, scroll to:','mv23theme') )->add_options(array(
                	    '' => __('Dont move the scroll','mv23theme'),
                	    'postcard' => __('To the post card','mv23theme'),
                	    'expander' => __('To the expander','mv23theme')
                	))->add_dependency( 'on_click_post', 'show-expander', '=' )
			    )),
            
            Field::create( 'tab', 'pagination_tab', __('Pagination','mv23theme')),
            Field::create( 'select', 'pagination_type', __('Pagination type','mv23theme') )->add_options(LISTING_PAGINATION_TYPES),
            Field::create( 'checkbox', 'pagination_scrolltop', '' )->set_text(__('Scroll to top','mv23theme'))->add_dependency('pagination_type','numeric','='),
        );

        // FILTER FIELDS
        $filter_fields = array();
        if( is_array($listing_taxonomies) && count($listing_taxonomies) > 0 ){
            foreach($listing_taxonomies as $tax){
                array_push($filter_fields, 
                    Field::create( 'complex', $tax['slug'] )
                        ->hide_label()
                        ->add_dependency('../show_filter')
                        ->add_dependency('../posttype', $tax['cpt_slug'], '=')
                        ->add_dependency_group()
                        ->add_dependency('../show_filter')
                        ->add_dependency('../connected_posttype', $tax['cpt_slug'], '=')
                        ->add_fields(array(
                            Field::create( 'checkbox', 'show', ucwords($tax['slug']).' filter' )
                                ->fancy()
                                ->set_width(50),
                            Field::create( 'select', 'initial_value', __('Initial value','mv23theme') )
                                ->add_terms( $tax['slug'] )
                                ->add_dependency('show')
                                ->set_width(50)
                        ))
                );
            }
        }

        array_push($filter_fields, Field::create( 'complex', 'month' )->add_fields(array(
            Field::create( 'checkbox', 'show', __('Month filter','mv23theme') )->fancy()
        ))->hide_label()->add_dependency('../show_filter'));

        array_push($filter_fields, Field::create( 'complex', 'year' )->add_fields(array(
            Field::create( 'checkbox', 'show', __('Year filter','mv23theme') )->fancy()->set_width(50),
            Field::create( 'number', 'initial_value', __('Initial value','mv23theme') )->set_minimum(2012)->set_maximum(date('Y'))->add_dependency('show')->set_default_value('')->set_width(50),
            Field::create( 'number', 'first_year')->set_prefix(__('First year','mv23theme'))->hide_label()->set_minimum(2012)->set_maximum(date('Y'))->add_dependency('show')->set_default_value(2012)->set_width(50),
        ))->hide_label()->add_dependency('../show_filter'));

        $listing_fields_filter = array(
            Field::create( 'tab', 'filters_tab', __('Filters','mv23theme')),
            Field::create( 'checkbox', 'show_filter', __('Filter','mv23theme') )->set_text( __('Show filters','mv23theme') )->fancy()->hide_label(),
            Field::create( 'complex', 'filters' )->hide_label()->add_fields( $filter_fields )
        );

		$fields = array_merge( $listing_fields_1, $listing_fields_2, $listing_fields_filter );

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
		$args['additional_classes'][] = 'component';

        $listing_source = $args['source'] ?? 'auto'; // auto || manual
        $columns = $args['columns'] ?? LISTING_COLUMNS;
        $columns_gap = $args['columns_gap'] ?? LISTING_GAP;
        $listing_template = $args['listing_template'] ?? '';
        $query_taxonomies = array();
        $query_terms = array();
        $woocommerce_key = ( WOOCOMMERCE_IS_ACTIVE && isset($args['woocommerce_key']) ) ? $args['woocommerce_key'] : '';
        
        // postcard settings
        $postcard_settings = $args['postcard_settings'] ?? array();
        $postcard_template = $postcard_settings['template'] ?? '_default';
        $on_click_post = $postcard_settings['on_click_post'] ?? 'redirect';
        $on_click_scroll_to = $postcard_settings['on_click_scroll_to'] ?? '';

        // pagination
        $pagination_type = $args['pagination_type'] ?? 'none';
        $pagination_scrolltop = $args['pagination_scrolltop'] ?? false;

        // filters
        $the_filters = $args['filters'] ?? array();
        $filter_taxonomies = array();
        $filter_default_terms = array();
        $show_filters_raw = $args['show_filter'] ?? false;
        $show_filters = self::fix_boolean_on_ajax_calls( $show_filters_raw );

        // post status params
        $status_params = $args['status_params'] ?? array();
        $post_status = ( isset($status_params['set_post_status']) && $status_params['set_post_status'] && isset($status_params['post_status']) ) ? 
            $status_params['post_status'] : 
            ['publish'];
            
        if ($listing_source == 'manual') {
            $posttype = '';
            $posts_ids = array();
            $posts_meta = $args['posts'];
            foreach ($posts_meta as $post) {
                array_push($posts_ids, str_replace('post_','',$post) );
            };
            
            $args_query = array();
            $args_query['posts_per_page'] = -1;
            $args_query['post_type'] = 'any';
            $args_query['post__in'] = $posts_ids;
            $args_query['orderby'] = 'post__in';
        }
        
        if ($listing_source == 'auto') {
            $posttype = $args['posttype'] ?? '';
            // handle _default postcard template placeholder
            if( $postcard_template == '_default' ) $postcard_template = $posttype;

            // query params
            $query_params = $args['query_params'] ?? array();
            $posts_per_page = $query_params['posts_per_page'] ?? 3;
            $order = $query_params['order'] ?? 'DESC';
            $orderby = $query_params['orderby'] ?? 'date';

            $args_query = array( 
                'post_type' => $posttype,
                'posts_per_page' => $posts_per_page,
                'order' => $order,
                'orderby' => $orderby,
                'post_status' => $post_status,
                'paged' => ( get_query_var('paged') ) ? get_query_var('paged') : 1
            );
            if( isset($args['post__not_in']) ) $args_query['post__not_in'] = $args['post__not_in'];
            if( isset($args['offset']) ) $args_query['offset'] = $args['offset'];
        
            // check if tax_query is needed 
            $tax_params = ( isset($args['tax_params']) ) ? $args['tax_params'] : null;
            $pt_taxonomies = get_object_taxonomies( $posttype ); // get taxonomies for selected posttype 
        
            if( is_array($tax_params) ){    
                $tax_query = array( 'relation' => 'AND' );
                foreach ($tax_params as $tax => $terms) {
                    $tax_parts = explode( '--', $tax );
                    $tax_name = $tax_parts[1];
                    // $tax_cpt = $tax_parts[0]; // util, but not used

                    // create tax_query if tax belongs to selected posttype and there are selected terms
                    if( in_array($tax_name,$pt_taxonomies) && is_array($terms) && count($terms) > 0 ){
                        if( !empty($terms[0]) ){            
                            array_push($tax_query, array(
                                'taxonomy' => $tax_name,
                                'field' => 'term_id',
                                'terms' => $terms,
                                'include_children' => true,
                                'operator' => 'IN'
                            ));
                        }
                    }
                }
            
                /* woo featured products */
                if(WOOCOMMERCE_IS_ACTIVE){
                    if($woocommerce_key == 'featured'){
                        array_push($tax_query, array(
                            'taxonomy' => 'product_visibility',
                            'field'    => 'name',
                            'terms'    => array('featured'),
                            'operator' => 'IN'
                        ));
                    }
                }
                /* end woo featured products */
            
                if( count($tax_query) > 1 ){ // add tax query
                    $args_query['tax_query'] = $tax_query;
                
                    // taxonomies and terms for pagination, load more, etc
                    foreach ($tax_query as $query) {
                        if( isset($query['taxonomy']) ){
                            array_push($query_taxonomies,$query['taxonomy']);
                            if( is_array($query['terms']) ){
                                foreach ($query['terms'] as $term) {
                                    array_push($query_terms,$term);
                                }
                            }
                        }
                    }
                } 
            }
        
            // taxonomies and default terms for filter
            foreach($pt_taxonomies as $tax){
                if( isset($the_filters[$tax]) ){
                    $show_tax = self::fix_boolean_on_ajax_calls( $the_filters[$tax]['show'] );
                    if($show_tax){
                        $default_term = $the_filters[$tax]['initial_value'];
                        array_push($query_taxonomies,$tax);
                        array_push($filter_taxonomies,$tax);
                        array_push($filter_default_terms,$default_term);
                    }
                }
            }
        
            // check date params -> it breaks ajax filters
            // if($show_filters){
                // $date_params = array();
                // if( isset($the_filters['year']) && $the_filters['year']['show'] && $the_filters['year']['initial_value'] ){
                    // $date_params['year'] = $the_filters['year']['initial_value'];
                // } 
                // if( count($date_params) ) $args_query['date_query'] = array( $date_params );
            // }
        }
        
        if(WOOCOMMERCE_IS_ACTIVE){
            if($woocommerce_key == 'on_sale'){
                $args_query['meta_query'] = array(
                    array(
                        'key'           => '_sale_price',
                        'value'         => 0,
                        'compare'       => '>',
                        'type'          => 'numeric'
                    )
                );
            }
            if($woocommerce_key == 'best_selling'){
                $args_query['meta_query'] = array(
                    array(
                        'key' => 'total_sales'
                    )
                );
                $args_query['orderby'] = 'meta_value_num';
            }
        }
        
        $query = new WP_Query( $args_query ); 

        $listing_args = array(
            'post_template' => $postcard_template,
            'listing_template' => $listing_template,
            'on_click_post' => $on_click_post,
            'on_click_scroll_to' => $on_click_scroll_to,
            'taxonomies' => $query_taxonomies,
            'terms' => $query_terms,
            'wookey' => $woocommerce_key,
            'posttype' => $posttype,
            'pagination_type' => $pagination_type,
            'scrollTop' => $pagination_scrolltop,
            'post_status' => $post_status
        );
        if ($listing_source == 'auto') {
            $listing_args['per_page'] = $posts_per_page;
            $listing_args['order'] = $order;
            $listing_args['orderby'] = $orderby;

            if( isset($args['offset']) ) $listing_args['offset'] = $args['offset'];
        }
        $args['additional_attributes'][] = "data-listing-args='".json_encode($listing_args)."'";

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
        
        if($show_filters) {
            $show_month = 0;
            if( isset($the_filters['month']) ){
                $show_month = self::fix_boolean_on_ajax_calls( $the_filters['month']['show'] );
            }
    
            $show_year = 0;
            $firstyear = '';
            $default_year = '';
            if( isset($the_filters['year']) ){
                $show_year = self::fix_boolean_on_ajax_calls( $the_filters['year']['show'] );
                $firstyear = $the_filters['year']['first_year'];
                $default_year = $the_filters['year']['initial_value'];
            }
            
            do_action( 'before_posts_filter', $args );
            echo do_shortcode('[posts_filter posttype="'.$posttype.'" firstyear="'.$firstyear.'" show_year="'.$show_year.'" show_month="'.$show_month.'" default_year="'.$default_year.'" filter_taxonomies="'.implode(',',$filter_taxonomies).'" filter_default_terms="'.implode(',',$filter_default_terms).'"]');
            do_action( 'after_posts_filter', $args );
        };
        
        if(WOOCOMMERCE_IS_ACTIVE && $posttype == 'product') echo do_shortcode('[shop_messages]');

        do_action( 'post_listing_header', $args );
    
        $css_vars = '--d-gap:'.$columns_gap['desktop'].'px; --l-gap:'.$columns_gap['laptop'].'px; --t-gap:'.$columns_gap['tablet'].'px; --m-gap:'.$columns_gap['mobile'].'px; --d-columns:'.$columns['desktop'].'; --l-columns:'.$columns['laptop'].'; --t-columns:'.$columns['tablet'].'; --m-columns:'.$columns['mobile'];
    
        if ($query->have_posts()) : 
            $post_listing_class = 'posts-listing';
            if($listing_template) $post_listing_class .= ' posts-listing--'.$listing_template ;
            if($listing_template != 'carousel') $post_listing_class .= ' has-columns';
            ?>
            <div class="<?=$post_listing_class?>" style="<?=$css_vars?>">
                <?php if($listing_template == 'carousel'): 
                    $carousel_settings = $args['carousel_settings'] ?? array();
                    $show_controls = (!empty($carousel_settings['show_controls'])) ? $carousel_settings['show_controls'] : 0;
                    $show_nav = (!empty($carousel_settings['show_nav'])) ? $carousel_settings['show_nav'] : 0;
                    $show_nav = (!empty($carousel_settings['show_nav'])) ? $carousel_settings['show_nav'] : 0;
                    $autoplay = (!empty($carousel_settings['autoplay'])) ? $carousel_settings['autoplay'] : 0;
                    $carousel_id = (!empty($carousel_settings['carousel_id'])) ? $carousel_settings['carousel_id'] : '';
    
                    $carousel_classes_array = array('carousel','carousel-inside-component', 'carousel--theme1');
                    if( !$show_nav ) array_push($carousel_classes_array,'without-navigation');
                    ?>
                    <div class="<?php echo implode(' ', $carousel_classes_array); ?>" data-controls-position="center"><div class="carousel__slider" 
                        data-slider-uid="<?=$carousel_id?>"
                        data-show-controls="<?=$show_controls?>" 
                        data-show-nav="<?=$show_nav?>" 
                        data-touch="1" 
                        data-autoplay="<?=$autoplay?>" 
                        data-speed="450"
                        data-nav-position="bottom"
                        data-mobile="<?=$columns['mobile']?>"
                        data-tablet="<?=$columns['tablet']?>"
                        data-laptop="<?=$columns['laptop']?>"
                        data-desktop="<?=$columns['desktop']?>"
                        data-mobile-gutter="<?=$columns_gap['mobile']?>"
                        data-tablet-gutter="<?=$columns_gap['tablet']?>"
                        data-laptop-gutter="<?=$columns_gap['laptop']?>"
                        data-desktop-gutter="<?=$columns_gap['desktop']?>">
                <?php endif; ?>
    
                <?php 
                do_action('on_listing_start', $args);

                $count = 0;
                while ( $query->have_posts() ) : $query->the_post();
                    $args['count'] = $count;
                    
                    if($listing_template == 'carousel') echo '<div>';

                    $_postcard_template = apply_filters('filter_listing_postcard_template', $postcard_template, $count);

                    get_template_part( 'partials/card/postcard', $_postcard_template, $args);

                    if($listing_template == 'carousel') echo '</div>';
                    $count++;
                endwhile; 

                do_action('on_listing_end', $args);
                ?>
    
                <?php if($listing_template == 'carousel'): ?>
                    </div></div>
                <?php endif; ?>
            </div>
        <?php 
        endif;
    
        if( $pagination_type) echo '<br>';
        echo '<div class="pagination">';
        if ( $query->max_num_pages > 1 ){
            switch($pagination_type){
                case 'numeric':
                    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
                    Pagination::display($query, $paged);
                    break;
                    
                case 'load-more':
                    $load_more_text = LISTING_LOAD_MORE_TEXT;
                    $current_lang = (function_exists('pll_current_language')) ? pll_current_language() : 'es';
                    get_template_part('partials/pagination/load-more', null, array(
                        'max_pages' => $query->max_num_pages,
                        'listing_args' => $listing_args,
                        'load_more_text' => $load_more_text[$current_lang]
                    ));
                    break;
    
                default:
                    break;
            }
        }
        echo '</div>'; // close pagination
        wp_reset_postdata();
    
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}

    private static function fix_boolean_on_ajax_calls( $value ) {
        if( $value === 'true' ) return true;
        if( $value === 'false' ) return false;
        return $value;
    }
}

new Listing();