<?php
namespace Theme_Custom_Fields\Component;

use Ultimate_Fields\Field;
use Theme_Custom_Fields\Component;
use Theme_Custom_Fields\Template_Engine;
use WP_Query;

if( !defined('LISTING_CPTS') ) define( 'LISTING_CPTS', array('post' => 'Entradas'));
if( !defined('LISTING_TAXONOMIES') ) define( 'LISTING_TAXONOMIES', array( array( 'cpt_slug' => 'post', 'slug' => 'category' ) ));
if( !defined('LISTING_TEMPLATES') ) define( 'LISTING_TEMPLATES', array('' => 'Estilo por defecto', 'carrusel' => 'Carrusel'));
if( !defined('LISTING_PAGINATION_TYPES') ) define( 'LISTING_PAGINATION_TYPES', array('none' => 'Ninguno', 'classic' => 'Numérico', 'load_more' => 'Cargar más'));
if( !defined('LISTING_POST_TEMPLATE') ) define( 'LISTING_POST_TEMPLATE', array('' => 'Estilo por defecto','post-horizontal' => 'Post Horizontal'));

class Listing extends Component {

    public function __construct() {
		parent::__construct(
			'listing',
			__( 'Listing', 'default' )
		);
	}

    public static function get_icon() {
        return 'dashicons-screenoptions';
    }

    public static function get_layout(){
        return 'grid';
    }

    public static function get_title_template() {
		$template = '<% if ( show == "auto" || (show == "manual" && posts.lenght) ){ %>
            Auto | Show <%= ( qty != "-1" ) ? qty : "all" %> <%= posttype %> 
            <%= (list_template) ? " | List template: "+list_template : "" %> 
            <%= (post_template) ? " | Post template: "+post_template : "" %> 
        <% } else { %>
            There arent any posts selected
        <%  } %>';
		
		return $template;
	}

	public static function get_fields() {
        $listing_cpts = LISTING_CPTS;
        $listing_templates = LISTING_TEMPLATES;
        $listing_taxonomies = LISTING_TAXONOMIES;
        $listing_post_template = LISTING_POST_TEMPLATE;

        if(WOOCOMMERCE_IS_ACTIVE){
            $listing_cpts['product'] = 'Productos';
            array_push($listing_taxonomies, array(
                'cpt_slug' => 'product', 
                'slug' => 'product_cat'
            ));
            $listing_post_template['woocommerce1'] = 'WooCommerce Product Basic';
        } 

        if(USE_PORTFOLIO_CPT){
            $listing_cpts['portfolio'] = 'Portfolio';
            array_push($listing_taxonomies, array(
                'cpt_slug' => 'portfolio', 
                'slug' => 'portfolio-cat'
            ));
            $listing_post_template['portfolio'] = 'Portfolio';
        }

        $listing_fields_1 = array( 
            Field::create( 'tab', __('Contenido','default') ),
            Field::create( 'radio', 'show','Seleccione qué entradas se van a mostrar:')->set_orientation( 'horizontal' )->add_options( array(
                'auto'=>'Automático (últimos posts publicados)',
                'manual'=>'Manual',
            )),
            Field::create( 'wp_objects', 'posts', '' )->set_button_text( 'Selecciona los posts' )->add_dependency('show','manual','='),
        
            Field::create( 'number', 'qty', 'Cantidad de posts' )->add_dependency('show','auto','=')->set_default_value(3)->set_width(16),
            Field::create( 'select', 'posttype', 'Tipo de Posts' )->add_dependency('show','auto','=')->add_options($listing_cpts)->set_width(16),
        );

        if(WOOCOMMERCE_IS_ACTIVE){
            $woocommerce_keys_field = Field::create('select','woocommerce_key','WooCommerce Tag')->add_dependency('show','auto','=')->add_dependency('posttype', 'product', '=')->set_width(16)->add_options(array(
                '' => 'Ninguno',
                'featured' => 'Destacados',
                'on_sale' => 'En Oferta',
                'best_selling' => 'Más vendidos'
            ));
            array_push($listing_fields_1, $woocommerce_keys_field);
        }

        if( is_array($listing_taxonomies) && count($listing_taxonomies) > 0 ){
            $taxonomies_field = Field::create( 'complex', 'taxonomies_field' )->set_width(50)->hide_label();
            foreach($listing_taxonomies as $tax){
                $taxonomies_field->add_fields( array(
                    Field::create( 'multiselect', $tax['slug'] )->add_terms( $tax['slug'] )->add_dependency('../show','auto','=')->add_dependency('../posttype', $tax['cpt_slug'], '=')->set_width(20) 
                ));
            }
            array_push($listing_fields_1, $taxonomies_field);
        }

        $listing_fields_2 = array( 
            Field::create( 'select', 'order', 'Orden' )->add_dependency('show','auto','=')->add_options(array(
                'DESC' => 'Descendente',
                'ASC' => 'Ascendente'
            ))->set_width(25),
            Field::create( 'select', 'orderby', 'Ordenar por:' )->add_dependency('show','auto','=')->add_options(array(
                'date' => 'Fecha',
                'title' => 'Título',
                'name' => 'Slug',
                'rand' => 'Random',
                'menu_order' => 'Personalizado',
                // 'comment_count' => 'Comentarios'
            ))->set_width(25),
            Field::create( 'number', 'offset', 'Offset' )->add_dependency('show','auto','=')->set_width(25),
            
            Field::create( 'tab', 'List Template'),
            Field::create( 'radio', 'list_template', 'Template' )->set_orientation( 'horizontal' )->add_options($listing_templates)->hide_label(),
            
            Field::create( 'checkbox', 'show_controls' )->set_width( 33 )->set_text('Mostrar Flechas')->add_dependency('list_template','carrusel','='),
            Field::create( 'checkbox', 'show_nav' )->set_width( 33 )->set_text('Mostrar indicadores de página')->add_dependency('list_template','carrusel','='),
            Field::create( 'checkbox', 'autoplay' )->set_width( 33 )->set_text('Empezar automáticamente')->add_dependency('list_template','carrusel','='),
            
            Field::create( 'section', 'Cantidad de columnas' ),
            Field::create( 'number', 'items_in_desktop', 'Columnas en desktop' )->set_width( 25 )->enable_slider( 1, 12 )->set_default_value(3),
            Field::create( 'number', 'items_in_laptop', 'Columnas en laptop' )->set_width( 25 )->enable_slider( 1, 12 )->set_default_value(3),
            Field::create( 'number', 'items_in_tablet', 'Columnas en tablet' )->set_width( 25 )->enable_slider( 1, 12 )->set_default_value(2),
            Field::create( 'number', 'items_in_mobile', 'Columnas en móviles' )->set_width( 25 )->enable_slider( 1, 12 )->set_default_value(1),
            
            Field::create( 'section', 'Espacio entre las columnas' ),
            Field::create( 'number', 'd_gap', 'En desktop' )->set_default_value(intval(LISTING_DESKTOP_GAP))->set_width( 25 ),
            Field::create( 'number', 'l_gap', 'En laptop' )->set_default_value(intval(LISTING_LAPTOP_GAP))->set_width( 25 ),
            Field::create( 'number', 't_gap', 'En tablet' )->set_default_value(intval(LISTING_TABLET_GAP))->set_width( 25 ),
            Field::create( 'number', 'm_gap', 'En móviles' )->set_default_value(intval(LISTING_MOBILE_GAP))->set_width( 25 ),
            
            Field::create( 'tab', 'Post Template'),
            Field::create( 'radio', 'post_template', 'Template' )->set_orientation( 'vertical' )->add_options($listing_post_template)->hide_label(),
            Field::create( 'section', 'Acciones' ),
            Field::create( 'select', 'on_click_post', 'Al hacer click en el post:' )->add_options(array(
                'redirect' => 'Redirigir a la página del post',
                'show-expander' => 'Mostrar el post en la misma página',
                'show-popup' => 'Mostrar el post en un popup',
                'none' => 'Ninguna'
            ))->set_width( 50 ),
            Field::create( 'select', 'on_click_scroll_to', 'Al hacer click mover el scroll a:' )->add_options(array(
                '' => 'No mover el scroll',
                'postcard' => 'Al post card',
                'expander' => 'Al expander'
            ))->add_dependency( 'on_click_post', 'show-expander', '=' )->set_width( 50 ),
            
            Field::create( 'tab', 'Paginado'),
            Field::create( 'select', 'pagination_type', 'Paginado' )->add_dependency('show','auto','=')->add_options(LISTING_PAGINATION_TYPES)->hide_label()->set_width( 25 ),
            Field::create( 'checkbox', 'scrolltop' )->set_text('Scroll to top')->add_dependency('pagination_type','classic','=')->hide_label()->set_width( 25 ),
        );

        $listing_fields_filter = array(
            Field::create( 'tab', 'Filter'),
            Field::create( 'checkbox', 'filter', 'Filtro' )->set_text( 'Mostrar Filtros' )->set_width(10)
        );

        if( is_array($listing_taxonomies) && count($listing_taxonomies) > 0 ){
            foreach($listing_taxonomies as $tax){
                array_push($listing_fields_filter, 
                    Field::create( 'complex', $tax['slug'].'-filter', $tax['slug'] )->add_fields(array(
                        Field::create( 'checkbox', 'show' )->set_text( 'Mostrar' )->hide_label(),
                        Field::create( 'select', 'default_value' )->add_terms( $tax['slug'] )->add_dependency('show')
                    ))->add_dependency('filter')->add_dependency('show','auto','=')->add_dependency('posttype', $tax['cpt_slug'], '=')->set_width(10)
                );
            }
        }

        array_push($listing_fields_filter, Field::create( 'complex', 'month-filter', 'Mes' )->add_fields(array(Field::create( 'checkbox', 'show' )->set_text( 'Mostrar' )->hide_label()))->add_dependency('filter')->set_width(10) );

        array_push($listing_fields_filter, Field::create( 'complex', 'year-filter', 'Año' )->add_fields(array(
            Field::create( 'checkbox', 'show' )->set_text( 'Mostrar' )->hide_label(),
            Field::create( 'number', 'first_year', 'Primer Año' )->set_minimum(2012)->set_maximum(date('Y'))->add_dependency('show')->set_default_value(2012)->set_width(50),
            Field::create( 'number', 'default', 'Default value' )->set_minimum(2012)->set_maximum(date('Y'))->add_dependency('show')->set_default_value('')->set_width(50),
        ))->add_dependency('filter')->set_width(10) );

		$fields = array_merge( $listing_fields_1, $listing_fields_2, $listing_fields_filter );

		return $fields;
	}

    public static function display( $args ){
		$args['additional_classes'] = array('component');

        $posts_meta = $args['posts'];
        $source_type = $args['show']; // auto || manual
        $items_in_desktop = $args['items_in_desktop'];
        $items_in_laptop = $args['items_in_laptop'];
        $items_in_tablet = $args['items_in_tablet'];
        $items_in_mobile = $args['items_in_mobile'];
        $d_gap = $args['d_gap'];
        $l_gap = $args['l_gap'];
        $t_gap = $args['t_gap'];
        $m_gap = $args['m_gap'];
        $post_template = $args['post_template'];
        $listing_template = $args['list_template'];
        $scrolltop = ( isset($args['scrolltop']) ) ? $args['scrolltop'] : '';
        $filter_taxonomies = array();
        $filter_default_terms = array();
        $query_taxonomies = array();
        $query_terms = array();
        $woocommerce_key = ( WOOCOMMERCE_IS_ACTIVE && isset($args['woocommerce_key']) ) ? $args['woocommerce_key'] : '';
        $pagination_type = $args['pagination_type'];
        $on_click_post = ( isset($args['on_click_post']) ) ? $args['on_click_post'] : 'redirect';
            
        if ($source_type == 'manual') {
            $posttype = '';
            $posts_ids = array();
            foreach ($posts_meta as $post) {
                array_push($posts_ids, str_replace('post_','',$post) );
            };
            
            $args_query = array();
            $args_query['posts_per_page'] = -1;
            $args_query['post_type'] = 'any';
            $args_query['post__in'] = $posts_ids;
            $args_query['orderby'] = 'post__in';
        }
        
        if ($source_type == 'auto') {
            $posttype = $args['posttype'];
            $qty = (isset($args['qty'])) ? $args['qty'] : 3;
            $order = (isset($args['order'])) ? $args['order'] : 'DESC';
            $orderby = (isset($args['orderby'])) ? $args['orderby'] : 'date';
            $offset = (isset($args['offset'])) ? $args['offset'] : 0;
            $args_query = array( 
                'post_type' => $posttype,
                'posts_per_page' => $qty,
                'order' => $order,
                'orderby' => $orderby,
                'offset' => $offset,
                'post_status' => 'publish'
            );
        
            // check if tax_query is needed 
            $taxonomy_field = ( isset($args['taxonomies_field']) ) ? $args['taxonomies_field'] : null;
            $pt_taxonomies = get_object_taxonomies( $posttype ); // get taxonomies for selected posttype 
        
            if( is_array($taxonomy_field) ){    
                $tax_query = array( 'relation' => 'AND' );
                foreach ($taxonomy_field as $tax => $terms) {
                    // create tax_query if tax belongs to selected posttype and there are selected terms
                    if( in_array($tax,$pt_taxonomies) && is_array($terms) && count($terms) > 0 ){
                        if( !empty($terms[0]) ){            
                            array_push($tax_query, array(
                                'taxonomy' => $tax,
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
                if( isset($args[$tax.'-filter'])){
                    $show_tax = $args[$tax.'-filter']['show'];
                    if($show_tax){
                        $default_term = $args[$tax.'-filter']['default_value'];
                        array_push($filter_taxonomies,$tax);
                        array_push($filter_default_terms,$default_term);
                    }
                }
            }
        
            // check date params
            if($args['filter']){
                $date_params = array();
                if( $args['year-filter']['show'] && $args['year-filter']['default'] ) $date_params['year'] = $args['year-filter']['default'];
                if( count($date_params) ) $args_query['date_query'] = array( $date_params );
            }
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

        $args['additional_attributes'] = array( 
            'data-posttype="'.$posttype.'"',
            'data-taxonomies="'.implode(',',$query_taxonomies).'"',
            'data-terms="'.implode(',',$query_terms).'"',
            'post-template="'.$post_template.'"',
            'listing-template="'.$listing_template.'"',
            'on-click-post="'.$on_click_post.'"',
            'data-wookey="'.$woocommerce_key.'"',
            'data-scrolltop="'.$scrolltop.'"',
            'data-pagination="'.$pagination_type.'"'
        );

        if ($source_type == 'auto') {
            $args['additional_attributes'][] = 'data-qty="'.$qty.'"';
            $args['additional_attributes'][] = 'data-offset="'.$offset.'"';
            $args['additional_attributes'][] = 'data-order="'.$order.'"';
            $args['additional_attributes'][] = 'data-orderby="'.$orderby.'"';
        }

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
        
        if($args['filter']) {
            $show_month = 0;
            if( isset($args['month-filter']) ){
                $show_month = $args['month-filter']['show'];
            }
    
            $show_year = 0;
            $firstyear = '';
            $default_year = '';
            if( isset($args['year-filter']) ){
                $show_year = $args['year-filter']['show'];
                $firstyear = $args['year-filter']['first_year'];
                $default_year = $args['year-filter']['default'];
            }
            
            echo do_shortcode('[posts_filter posttype="'.$posttype.'" firstyear="'.$firstyear.'" show_year="'.$show_year.'" show_month="'.$show_month.'" default_year="'.$default_year.'" filter_taxonomies="'.implode(',',$filter_taxonomies).'" filter_default_terms="'.implode(',',$filter_default_terms).'"]');
        };
        
        if(WOOCOMMERCE_IS_ACTIVE && $posttype == 'product') echo do_shortcode('[shop_messages]');

        do_action( 'post_listing_header', $args );
    
        $css_vars = ($listing_template == 'carrusel') ? ' ' : '--d-gap:'.$d_gap.'px; --l-gap:'.$l_gap.'px; --t-gap:'.$t_gap.'px; --m-gap:'.$m_gap.'px; --d-columns:'.$items_in_desktop.'; --l-columns:'.$items_in_laptop.'; --t-columns:'.$items_in_tablet.'; --m-columns:'.$items_in_mobile;
    
        if ($query->have_posts()) : 
            $columns_class = ($listing_template == 'carrusel') ? '' : 'has-columns';
            $post_listing_class = 'posts-listing posts-listing--'.$listing_template . ' ' . $columns_class. ' posts-listing--'.$on_click_post;
    
            $dataAttrs = '';
            $on_click_scroll_to = ( isset($args['on_click_scroll_to']) ) ? $args['on_click_scroll_to'] : '';
            if( $on_click_scroll_to ) $dataAttrs = 'data-on-click-post-scroll-to="'.$on_click_scroll_to.'"';
            ?>
            <div class="<?=$post_listing_class?>" style="<?=$css_vars?>" <?=$dataAttrs?>>
                <?php if($listing_template == 'carrusel'): 
                    $show_controls = (!empty($args['show_controls'])) ? $args['show_controls'] : 0;
                    $show_nav = (!empty($args['show_nav'])) ? $args['show_nav'] : 0;
                    $show_nav = (!empty($args['show_nav'])) ? $args['show_nav'] : 0;
                    $autoplay = (!empty($args['autoplay'])) ? $args['autoplay'] : 0;
    
                    $carrusel_classes_array = array('carrusel','carrusel-inside-component');
                    if( !$show_nav ) array_push($carrusel_classes_array,'without-navigation');
                    ?>
                    <div class="<?php echo implode(' ', $carrusel_classes_array); ?>" data-controls-position="center"><div class="carrusel__slider" 
                        data-show-controls="<?=$show_controls?>" 
                        data-show-nav="<?=$show_nav?>" 
                        data-autoplay="<?=$autoplay?>" 
                        data-nav-position="bottom"
                        data-mobile="<?=$items_in_mobile?>"
                        data-tablet="<?=$items_in_tablet?>"
                        data-laptop="<?=$items_in_laptop?>"
                        data-desktop="<?=$items_in_desktop?>"
                        data-mobile-gutter="<?=$m_gap?>"
                        data-tablet-gutter="<?=$t_gap?>"
                        data-laptop-gutter="<?=$l_gap?>"
                        data-desktop-gutter="<?=$d_gap?>">
                <?php endif; ?>
    
                <?php 
                while ( $query->have_posts() ) : $query->the_post();
                    if($listing_template == 'carrusel') echo '<div>';
                    set_query_var( 'on_click_post', $on_click_post );
                    get_template_part( 'partials/card/minipost',$post_template);
                    if($listing_template == 'carrusel') echo '</div>';
                endwhile; 
                ?>
    
                <?php if($listing_template == 'carrusel'): ?>
                    </div></div>
                <?php endif; ?>
            </div>
        <?php 
        endif;
    
        echo '<br>';
        echo '<div class="pagination">';
        if ( $query->max_num_pages > 1 ){
            switch($pagination_type){
                case 'classic':
                    ///////////////////////////////////////////////////////////////////////////////////////////////
                    // PAGINATION 
                    ///////////////////////////////////////////////////////////////////////////////////////////////
                    $actual_link = home_url();
                    $base = $actual_link.'/page/' .'%#%'. '%_%';
                    echo paginate_links( array(
                        'base'         => $base,
                        'format'       => '?paged=%#%',
                        // 'current'      => max(1, $paged),
                        'total'        => $query->max_num_pages,
                        'prev_text'    => '<<',
                        'next_text'    => '>>',
                        'type'         => 'list',
                        'end_size'     => 3,
                        'mid_size'     => 3
                    ) );
                    ///////////////////////////////////////////////////////////////////////////////////////////////
                    break;
                    
                case 'load_more':
                    $load_more_text = LISTING_LOAD_MORE_TEXT;
                    $current_lang = (function_exists('pll_current_language')) ? pll_current_language() : 'es';
                    echo '<p class="aligncenter"><button class="btn load_more_posts" data-paged="2">'.$load_more_text[$current_lang].'</button></p>'; 
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
}

new Listing();