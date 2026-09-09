<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Frontend\Pagination;
use Core\Posttype\Postcard;
use Core\Builder\Component\Postcard as Postcard_Component;
use Core\Builder\Core;
use Core\Frontend\Listing_Data_Provider;
use Core\Builder\Slider_Settings;
use Core\Builder\Component\Carousel;

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

	public static function get_fields() {
        $posttypes = Core::get_post_types();
        $listing_post_template = LISTING_POST_TEMPLATE;
        $listing_post_template['document'] = 'Document';
        $listing_post_template['portfolio'] = 'Portfolio';
        if(WOOCOMMERCE_IS_ACTIVE) $listing_post_template['woocommerce1'] = 'WooCommerce Product Basic';

        $listing_fields_1 = array( 
            Field::create( 'tab', 'content_tab', __('Content Type','mv23theme') ),
            Field::create( 'radio', 'source', __('Source','mv23theme'))
                ->hide_label()
                ->set_orientation( 'horizontal' )
                ->set_default_value('auto')
                ->add_options( array(
                    'auto'=>__('Automatic (Latest published posts)','mv23theme'),
                    'manual'=>'Manual',
                )),
            Field::create( 'wp_objects', 'posts', '' )->set_button_text( __('Select the posts','mv23theme') )->add_dependency('source','manual','='),

            Field::create( 'radio', 'posttype', __('Select a post type','mv23theme') )
                ->set_default_value('post')
                ->add_dependency('source','auto','=')
                ->set_orientation( 'horizontal' )
                ->add_options( $posttypes )
        );

        // add taxonomies parameters for each post type
        $tax_params = Field::create( 'complex', 'tax_params', __('Categories','mv23theme') )
            ->hide_label()
            ->add_dependency('source','auto','=');
        
        foreach($posttypes as $cpt_slug => $cpt_name){
            $taxonomies = get_object_taxonomies( $cpt_slug, 'objects' );
            if( is_array($taxonomies) && count($taxonomies) > 0 ){
                foreach($taxonomies as $tax_slug => $tax_object){
                    if( !$tax_object->public || !$tax_object->show_ui ) continue;
                    $tax_name = $tax_object->labels->name;
                    $tax_params->add_fields( array(
                        Field::create( 'multiselect', $cpt_slug .'--'. $tax_slug, $tax_name )
                            ->add_terms( $tax_slug )
                            ->add_dependency('../posttype', $cpt_slug, '=')
                    ));
                }
            }
        }
        array_push($listing_fields_1, $tax_params);

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
                Field::create( 'number', 'offset', 'Offset' )->set_attr('style', $width_50)->set_minimum(0),
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
            Field::create( 'select', 'listing_template', 'Template' )->add_options(LISTING_TEMPLATES)
        );

        if( !MASONRY_IS_ACTIVE ){
            $listing_fields_2[] = Field::create( 'message', 'masonry_message', __('Activate Masonry','mv23theme') )
                ->set_description('You need to activate masonry gallery to use this feature: <a href="'.admin_url().'admin.php?page=theme-options#global_options" target="_blank">Activate Masonry Gallery</a>')
                ->add_dependency('listing_template', 'masonry', '=')
                ->set_attr( 'style', 'background:#ffe8e8;width:100%;' );
        }

        $listing_fields_3 = array(
            Field::create( 'complex', 'columns', __('Columns Quantity','mv23theme') )->add_fields(array(
                Field::create( 'number', 'desktop', __('Desktop','mv23theme') )->set_minimum(1)->set_maximum(12)->set_default_value(LISTING_COLUMNS['desktop'])->set_attr('style', $width_25),
                Field::create( 'number', 'laptop', __('Laptop','mv23theme') )->set_minimum(1)->set_maximum(12)->set_default_value(LISTING_COLUMNS['laptop'])->set_attr('style', $width_25),
                Field::create( 'number', 'tablet', __('Tablet','mv23theme') )->set_minimum(1)->set_maximum(12)->set_default_value(LISTING_COLUMNS['tablet'])->set_attr('style', $width_25),
                Field::create( 'number', 'mobile', __('Mobile','mv23theme') )->set_minimum(1)->set_maximum(12)->set_default_value(LISTING_COLUMNS['mobile'])->set_attr('style', $width_25)
            )),
            
            Field::create( 'complex', 'columns_gap', __('Space between columns','mv23theme') )->add_fields(array(
                Field::create( 'number', 'desktop', __('Desktop','mv23theme') )->set_minimum(0)->set_default_value(LISTING_GAP['desktop'])->set_attr('style', $width_25),
                Field::create( 'number', 'laptop', __('Laptop','mv23theme') )->set_minimum(0)->set_default_value(LISTING_GAP['laptop'])->set_attr('style', $width_25),
                Field::create( 'number', 'tablet', __('Tablet','mv23theme') )->set_minimum(0)->set_default_value(LISTING_GAP['tablet'])->set_attr('style', $width_25),
                Field::create( 'number', 'mobile', __('Mobile','mv23theme') )->set_minimum(0)->set_default_value(LISTING_GAP['mobile'])->set_attr('style', $width_25)
            )),

            Field::create('text', 'listing_uid')
                ->set_prefix(__('Listing UID','mv23theme'))
                ->hide_label()
                ->set_default_value(uniqid('listing_'))
                ->set_description(__('This is used to identify the listing. If you leave it empty, a random UID will be generated.', 'mv23theme')),

            Field::create( 'tab', 'slider_settings_tab', __('Carousel Settings','mv23theme'))
                ->add_dependency('listing_template','carousel','='),
            Slider_Settings::getRepeater( 'slider_settings', __('Slider Settings', 'mv23theme') )
                ->hide_label()
                ->add_dependency('listing_template','carousel','=')
        );

        // postcard fields
        $postcards = Postcard::getInstance()->get_postcards();
        $listing_post_template = array_merge( $listing_post_template, $postcards );

        $postcard_fields = array(
            Field::create( 'tab', 'postcard_settings_tab', __('Post Card Settings','mv23theme')),
            Field::create( 'complex', 'postcard_settings' )
			    ->hide_label()
			    ->add_fields(array(
			    	Field::create( 'radio', 'template' )
                        ->set_default_value('_default')
                        ->set_orientation( 'vertical' )
                        ->add_options($listing_post_template), 
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
                	))->add_dependency( 'on_click_post', 'show-expander', '=' ),
                )),
			Field::create( 'checkbox', 'equalize_postcards_height', '' )
                ->set_text( __('Equalize post cards height','mv23theme') )
                ->hide_label()->fancy()
                ->add_dependency( '../listing_template', 'masonry', '!=' )
        );

        // pagination fields
        $pagination_fields = array(
            Field::create( 'tab', 'pagination_tab', __('Pagination','mv23theme')),
            Field::create( 'select', 'pagination_type', __('Pagination type','mv23theme') )->add_options(LISTING_PAGINATION_TYPES),
            Field::create( 'checkbox', 'pagination_scrolltop', '' )->set_text(__('Scroll to top','mv23theme'))->add_dependency('pagination_type','numeric','='),
        );

		$fields = array_merge( $listing_fields_1, $listing_fields_2, $listing_fields_3, $postcard_fields, $pagination_fields );

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_restricted( $args ) ) return;
        
		$args['additional_classes'][] = 'component';

        $listing_source = $args['source'] ?? 'auto'; // auto || manual
        $columns = $args['columns'] ?? LISTING_COLUMNS;
        $columns_gap = $args['columns_gap'] ?? LISTING_GAP;
        $listing_template = $args['listing_template'] ?? '';
        $woocommerce_key = ( WOOCOMMERCE_IS_ACTIVE && isset($args['woocommerce_key']) ) ? $args['woocommerce_key'] : '';

        // postcard settings
        $postcard_settings = $args['postcard_settings'] ?? array();
        $postcard_template = $postcard_settings['template'] ?? '_default';
        $on_click_post = $postcard_settings['on_click_post'] ?? 'redirect';
        $on_click_scroll_to = $postcard_settings['on_click_scroll_to'] ?? '';
        $equalize_postcards_height = !empty( $args['equalize_postcards_height'] ) && $listing_template !== 'masonry';

        if( $on_click_post === 'none' ){
            add_filter('post_link', array(__CLASS__, 'hide_permalink'), 30, 2);
            add_filter('post_type_link', array(__CLASS__, 'hide_permalink'), 30, 2);
        }
        if( $on_click_post === 'show-expander' ){
            $args['additional_classes'][] = 'listing--expander';
        }
        
        // get postcard template content if it is a postcard template
        $postcard_cpt_template = null;
        if( strpos($postcard_template, 'postcard_') === 0 ){
            $postcard_cpt_template = Postcard::getInstance()->get_data( str_replace('postcard_','',$postcard_template) );
        }

        // handle "_default" postcard template placeholder
        $posttype = ($listing_source === 'auto') ? $args['posttype'] ?? '' : '';
        if( $postcard_template === '_default' ){
            $args['postcard_settings']['template'] = $posttype;
            $postcard_template = $posttype;
        } 

        // pagination
        $pagination_type = $args['pagination_type'] ?? 'none';
        $pagination_scrolltop = $args['pagination_scrolltop'] ?? false;
        
        $args['data_source'] = $args['data_source'] ?? 'custom_query';
        $query = Listing_Data_Provider::get_data( $args ); 

        $listing_args = array(
            'post_template' => $postcard_template,
            'listing_template' => $listing_template,
            'on_click_post' => $on_click_post,
            'on_click_scroll_to' => $on_click_scroll_to,
            'wookey' => $woocommerce_key,
            'posttype' => $posttype,
            'pagination_type' => $pagination_type,
            'scrollTop' => $pagination_scrolltop,
            'equalizeHeight' => $equalize_postcards_height,
            'query_args' => $query->query,
        );
        $args['additional_attributes']['data-listing-args'] = esc_attr( json_encode($listing_args) );

        // uid
        $listing_uid = $args['listing_uid'] ?? 'listing-'.uniqid();
        $args['additional_attributes']['data-listing-uid'] = esc_attr( $listing_uid );

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
        
        if(WOOCOMMERCE_IS_ACTIVE && $posttype == 'product') echo do_shortcode('[shop_messages]');

        do_action( 'post_listing_header', $args );
    
        $css_vars = '--d-gap:'.$columns_gap['desktop'].'px; --l-gap:'.$columns_gap['laptop'].'px; --t-gap:'.$columns_gap['tablet'].'px; --m-gap:'.$columns_gap['mobile'].'px; --d-columns:'.$columns['desktop'].'; --l-columns:'.$columns['laptop'].'; --t-columns:'.$columns['tablet'].'; --m-columns:'.$columns['mobile'];
    
        if ($query->have_posts()) : 
            $post_listing_class = 'posts-listing';
            if($listing_template) $post_listing_class .= ' posts-listing--'.$listing_template ;
            if($listing_template != 'carousel' && $listing_template != 'masonry') $post_listing_class .= ' has-columns';
            if($listing_template == 'masonry') $post_listing_class .= ' has-masonry-columns';

            if( $postcard_cpt_template && $postcard_cpt_template['has_content']){
                echo '<style class="postcard-cpt-styles">'.$postcard_cpt_template['styles'].'</style>';
            }
            ?>
            <div class="<?=$post_listing_class?>" style="<?=$css_vars?>">
                <?php if($listing_template == 'carousel'): 
                    echo '<div class="carousel">';
                    echo Carousel::slider_start( $args );
                endif; ?>
    
                <?php 
                do_action('on_listing_start', $args);

                if($listing_template == 'masonry'){
                    echo '<div class="masonry-grid-sizer"></div>';
                    echo '<div class="masonry-gutter-sizer"></div>';
                } 

                $count = 0;
                while ( $query->have_posts() ) : $query->the_post();
                    $args['count'] = $count;
                
                    if($listing_template == 'carousel') echo '<div>';
                    if($listing_template == 'masonry') echo '<div class="masonry-grid-item">';

                    if( $postcard_cpt_template && $postcard_cpt_template['has_content']){
                        $postcard_cpt_template['component']['postcard_settings'] = array(
                            'on_click_post' => $on_click_post,
                            'on_click_scroll_to' => $on_click_scroll_to
                        );
                        echo Postcard_Component::display( $postcard_cpt_template['component'] );
                        // echo Template_Engine::check_components( array('components' => array( $postcard_cpt_template['component'] ) ) );
                    } else {
                        $_postcard_template = apply_filters('filter_listing_postcard_template', $postcard_template, $count);
                        get_template_part( 'partials/card/postcard', $_postcard_template, $args);
                    }

                    if($listing_template == 'carousel' || $listing_template == 'masonry') echo '</div>';
                    $count++;
                endwhile; 

                do_action('on_listing_end', $args);
                ?>
    
                <?php if($listing_template == 'carousel'):
                    echo Carousel::slider_end();
                    echo Carousel::slider_controls( $args, true );
                    echo '</div>';
                endif; ?>
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

        if( $on_click_post === 'none' ){
            remove_filter('post_link', array(__CLASS__, 'hide_permalink'), 30, 2);
            remove_filter('post_type_link', array(__CLASS__, 'hide_permalink'), 30, 2);
        }
    
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}

    public static function hide_permalink( $permalink, $post ) {
        return '#';
    }
}

new Listing();