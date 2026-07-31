<?php
namespace Core\Frontend;

use Walker_Nav_Menu;
use Core\Frontend\Page;

class Nav_Walker extends Walker_Nav_Menu{
    private $context = array();

    // This property will hold the current parent item being processed
    private $current_parent_item = null;

    public function __construct( $context = array() ){
        $this->context = $context;
    }

    public function is_megamenu( $item ) {
        return get_post_meta($item->ID,'is_megamenu',true);
    }

    public function display_element( $item, &$children_elements, $max_depth, $depth, $args, &$output ) {

        if ( $this->is_megamenu( $item ) ) {
            unset( $children_elements[ $item->ID ] );
        }

        parent::display_element(
            $item,
            $children_elements,
            $max_depth,
            $depth,
            $args,
            $output
        );
    }

    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        if (is_array($args)) return $output;

        // Store the current parent item being processed
        $this->current_parent_item = $item;

        // ************************************************************************************************************
        // ************************************************************************************************************
        $visibility = get_post_meta($item->ID,'visibility',true);

        if ($visibility == 'is_private' && !current_user_can('administrator')) return;
        if ($visibility == 'user_is_logged_in' && !is_user_logged_in() ) return;
        if ($visibility == 'user_is_not_logged_in' && is_user_logged_in() ) return;
        if ($visibility == 'comments_open' && !comments_open() ) return;
        if ($visibility == 'comments_closed' && comments_open() ) return;

        // ************************************************************************************************************
        // ************************************************************************************************************
        $is_megamenu = get_post_meta($item->ID,'is_megamenu',true);
        $hide_label = get_post_meta($item->ID,'hide_label',true);
        $offcanvas_element = get_post_meta($item->ID,'offcanvas_element',true);
        // ************************************************************************************************************
        // ************************************************************************************************************
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $class_names = $value = '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        // ************************************************************************************************************
        // Dynamic content implementation
        // ************************************************************************************************************
        $dynamic_content_settings = $this->get_dynamic_content_settings( $item );
        if( $dynamic_content_settings['is_active'] ){
            if (
                ($dynamic_content_settings['type'] == 'list_posts' && count($dynamic_content_settings['posts'])) 
                || ($dynamic_content_settings['type'] == 'list_terms' && !empty($dynamic_content_settings['terms'])) 
            ){
                $classes[] = 'menu-item-has-children';
            };

            if( 
                $dynamic_content_settings['type'] == 'list_all_in_megamenu' 
                && !empty($dynamic_content_settings['megamenu']) 
                ) {
                $is_megamenu = true;
            }
        }
        // ************************************************************************************************************
        // ************************************************************************************************************

        if ($is_megamenu){
            $classes = array_diff($classes, array('menu-item-has-children'));
            $classes[] = 'has-megamenu';
        } 
        if ($hide_label) $classes[] = 'hidden-text';

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $styles = array();
        $style = get_post_meta($item->ID,'menu_item_style',true);
        if( is_array($style) ){
            $background_color = $style['background_color'];
            if( $background_color['use'] ) $styles[] = 'background-color:'.$background_color['color'];

            $text_color = $style['text_color'];
            if( $text_color['use'] ) $styles[] = 'color:'.$text_color['color'];

            $radius = $style['radius'];
            if( $radius['use'] ) $styles[] = 'border-radius:'.$radius['value'].'px';

            $shadow = $style['shadow'];
            if( $shadow['use'] ) $styles[] = 'box-shadow:0 0 '.$shadow['blur'].'px '.$shadow['color'];

            $border = $style['border'];
            if( $border['use'] ) $styles[] = 'border:'.$border['width'].'px solid '.($border['color'] ? $border['color']: '');

            $min_width = (isset($style['min_width'])) ? $style['min_width'] : array('use'=>false);
            if( $min_width['use'] ) $styles[] = 'min-width:'.$min_width['value'].'px';
        }
        $style = ( !empty($styles) ) ? 'style="'.implode(';', $styles).'"' : '';

        $output .= $indent . '<li' . $id . $value . $class_names . $style.' role="none">';
    
        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';
        if ($is_megamenu) {
            $atts['data-activates']  = 'megamenu-'.$item->ID;
        }
        if($offcanvas_element){
            $atts['data-offcanvas-element'] = str_replace('post_','',$offcanvas_element);
        }
        // add ARIA attributes for accessibility
        $atts['role'] = 'menuitem';
        if (in_array('menu-item-has-children', $classes) || $is_megamenu || $dynamic_content_settings['is_active'] || $offcanvas_element) {
            $atts['aria-haspopup'] = 'true';
        }
    
        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );
    
        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
    
        $item_output = '';
        
        $identificador = get_post_meta($item->ID,'identificador',true);
        $icon_html = '';
        if($identificador != ''){
            $icon = get_post_meta($item->ID,'menu_item_icon',true);
            $image = get_post_meta($item->ID,'menu_item_image',true);
            $imagen_url = wp_get_attachment_url($image);
            $icon_html = '<span class="menu-item__icon">';
            $icon_prefix = (str_starts_with($icon,'fa')) ? 'fa' : 'bi';
            $icon_html .= ( $identificador == 'imagen' && $image ) ? '<img src="'.$imagen_url .'" />' : '<i class="'.$icon_prefix.' '.$icon.'"></i>';
            $icon_html .= '</span>';
        }

        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $icon_html;
        $item_output .= $args->link_before .'<span class="menu-item__label">'. apply_filters( 'the_title', $item->title, $item->ID ) .'</span>'. $args->link_after;
        $item_output .= '</a>';
        if (in_array('menu-item-has-children', $classes)) {
            $item_output .= '<button class="toggle-submenu" aria-expanded="false"></button>';
        }
        $item_output .= $args->after;

        $megamenu_data = get_post_meta($item->ID,'megamenu_post',true);
        if( $depth == 0 && $is_megamenu && $megamenu_data ) {
            $page = new Page();
            $megamenu_id = str_replace('post_', '', $megamenu_data);
            $item_output .= '<div id="megamenu-'.$item->ID.'" class="megamenu"';
            $item_output .= ' aria-label="'.esc_attr__('Megamenu for', 'mv23theme').' '.esc_attr($item->title).'">';
            $item_output .= '<div class="container">';
            $item_output .= $page->the_content( $megamenu_id );
            $item_output .= '<a href="#" class="megamenu-close" aria-label="'.esc_attr__('Close megamenu', 'mv23theme').'"></a>';
            $item_output .= '</div></div>'; 
        }

        // ************************************************************************************************************
        // Dynamic content implementation
        // ************************************************************************************************************
        if( $dynamic_content_settings['is_active'] ){
            if ($dynamic_content_settings['type'] == 'list_posts' && count($dynamic_content_settings['posts']) ) {
                $posts = $dynamic_content_settings['posts'];
                $item_output .= '<ul class="sub-menu" role="menu" aria-label="'.esc_attr($item->title).'">';
                foreach ($posts as $post) {
                    $post_id = $post->ID;
                    $item_output .= '<li class="menu-item" role="none">';
                    $item_output .= '<a href="' . get_permalink($post_id) . '" role="menuitem">' . get_the_title($post_id) . '</a>';
                    $item_output .= '</li>';
                }
                $item_output .= '</ul>';
            }

            if ($dynamic_content_settings['type'] == 'list_terms' && !empty($dynamic_content_settings['terms']) ) {
                $item_output .= $dynamic_content_settings['terms'];
            }

            if( $depth == 0 && $is_megamenu ) {
                $item_output .= $dynamic_content_settings['megamenu'];
            }
        }
        // ************************************************************************************************************
        // ************************************************************************************************************
        
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    /**
     * Adds the start of a level. This is the opening <ul> tag for a submenu.
     * 
     * @param string   $output Used to append additional content (passed by reference).
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat("\t", $depth);
        
        // get the parent title to be used in the aria-label for the submenu
        $parent_title = '';
        if ( ! empty( $this->current_parent_item ) && is_object( $this->current_parent_item ) ) {
            $parent_title = $this->current_parent_item->title;
        }

        // Fallback for older WordPress versions if the object is missing
        if ( empty( $parent_title ) ) {
            $parent_title = __( 'Sub', 'mv23theme' );
        }

        // Dynamically build a unique aria-label matching the parent's text
        $aria_label = sprintf( __( '%s submenu', 'mv23theme' ), $parent_title );

        // Construct the <ul> tag with classes, role, and aria-label
        $output .= "\n$indent<ul class=\"sub-menu\" role=\"menu\" aria-label=\"" . esc_attr( $aria_label ) . "\">\n";
    }

    function get_dynamic_content_settings( $item ){
        $dynamic_content_settings = array();

        $dynamic_content_meta = get_post_meta($item->ID,'dynamic_content_settings',true);
        $dynamic_content_settings['is_active'] = !empty($dynamic_content_meta) && $dynamic_content_meta['content_type'] != '';

        if( $dynamic_content_settings['is_active'] ){
            $dynamic_content_settings['type'] = $dynamic_content_meta['content_type'];

            $posttype = $dynamic_content_meta['connected_posttype'] ?? '';
            $taxonomy = $dynamic_content_meta['connected_'.$posttype.'_taxonomy'] ?? '';
            $terms = $dynamic_content_meta['connected_'.$taxonomy.'_terms'] ?? array();

            if( $dynamic_content_settings['type'] == 'list_posts' ){
                $posts_args = array(
                    'post_type' => $posttype,
                    'posts_per_page' => -1
                );
                if ( $taxonomy != '' ){
                    if( count($terms) ){
                        $posts_args['tax_query'] = array(
                            array (
                                'taxonomy' => $taxonomy,
                                'field' => 'id',
                                'terms' => $terms
                            )
                        );
                    }
                }
                $dynamic_content_settings['posts'] = get_posts($posts_args);
            }

            if( $dynamic_content_settings['type'] == 'list_terms' ) {
                $dynamic_content_settings['terms'] = '';
                if ( $taxonomy != '' ){
                    if( count($terms) ){
                        foreach ($terms as $term) {
                            $dynamic_content_settings['terms'] .= self::list_terms_recursive( $taxonomy, $term, 1 );
                        }
                    } else {
                        $dynamic_content_settings['terms'] .= self::list_terms_recursive( $taxonomy, NULL, 1 );
                    }
                }
            }

            if( $dynamic_content_settings['type'] == 'list_all_in_megamenu' ) {
                $taxonomy = $dynamic_content_meta['connected_'.$posttype.'_taxonomy'] ?? '';
                $dynamic_content_settings['megamenu'] = $this->get_terms_and_posts_megamenu( $item, $posttype, $taxonomy );
            }
        }

        return $dynamic_content_settings;
    }

    static function list_terms_recursive($taxonomy, $parent_term_id, $depth) {
        $args = array(
            'taxonomy' => $taxonomy,
            'hide_empty' => false
        );
        if( $parent_term_id ) $args['parent'] = $parent_term_id; 
        if( $parent_term_id == null ) $args['parent'] = 0;
        $terms = get_categories($args);
        $list = '';
        
        if (!empty($terms)) {
            $list .= ($depth == 0) ? '<ul class="menu" role="menubar">' : '<ul class="sub-menu" role="menu" aria-label="Submenu">';
            $count = 0;
            foreach ($terms as $term) {
                $class = 'menu-item item-depth-'.$depth;
                $has_children = get_term_children($term->term_id, $taxonomy);
                if( $has_children ) $class .= ' menu-item-has-children';
                $link_class = ( $count == 0 ) ? 'active' : '';
                $link = get_term_link( $term->term_id, $taxonomy );
                
                $list .= '<li class="'.$class.'" role="none">';
                $list .= '<a class="'.$link_class.'" data-term="'.$term->term_id.'" href="'.$link.'" role="menuitem" aria-haspopup="'.($has_children ? 'true' : 'false').'">';
                $list .= '<span class="menu-item__label">'.esc_html($term->name).'</span>';
                $list .= '</a>';
                if( $has_children ) $list .= '<button class="toggle-submenu"></button>';
                if( $has_children ) $list .= self::list_terms_recursive($taxonomy, $term->term_id, $depth+1);
                $list .= '</li>';
                $count++;
            }
            $list .= '</ul>';
        }
    
        return $list;
    }

    function get_terms_and_posts_megamenu( $item, $posttype, $taxonomy ){
        $terms_list = self::list_terms_recursive( $taxonomy, NULL, 0 ); // NULL list all terms, TODO: use connected_taxonomy_terms
        ob_start(); ?>
        <div id="megamenu-<?=$item->ID?>" class="megamenu dynamic-megamenu light-theme" aria-label="<?=esc_attr__('Megamenu for', 'mv23theme').' '.esc_attr($item->title)?>">
            <div class="dynamic-megamenu__box"> 
                <div class="dynamic-megamenu__sidenav"> 
                    <nav class="component menu-comp" aria-label="Main Navigation">
                        <?php 
                        $terms_list_edited = str_replace('class="menu"','class="menu vertical-nav vertical-nav-1"',$terms_list);
                        echo $terms_list_edited;
                        ?>
                    </nav>
                </div> 
                <div class="dynamic-megamenu__content"> 
                    <div class="dynamic-megamenu__posts">
                        <?php
                        $terms_args = array(
                            'taxonomy' => $taxonomy,
                            'hide_empty' => false
                        );
                        $terms = get_terms($terms_args);
                        if ($terms) {
                            foreach ($terms as $term) :
                                echo '<ul style="display:none;" class="dynamic-megamenu-list dynamic-megamenu-term-'.$term->term_id.'">';
                                $term_args = array(
                                    'post_type' => $posttype,
                                    'posts_per_page' => -1,
                                    'orderby' => 'menu_order',
                                    // 'order' => 'ASC',
                                    // 'orderby' => 'title',
                                    'tax_query' => array(
                                        array (
                                            'taxonomy' => $taxonomy,
                                            'field' => 'id',
                                            'terms' => $term->term_id
                                            )
                                        ),
                                    );
                                $term_posts = get_posts($term_args);
                                foreach ($term_posts as $post) {
                                    $id = $post->ID;
                                    $term_ids = [];
                                    $terms = get_the_terms($id, $taxonomy);
                                    if ($terms && !is_wp_error($terms)) {
                                        foreach($terms as $term){
                                            $term_ids[] = 'list-item-term-'.$term->term_id;
                                        }
                                    }
                                    $list_item_class = join( " ", $term_ids );
                                    echo '<li class="'.$list_item_class.'"><a href="' . get_permalink($id) . '">' . get_the_title($id) . '</a></li>';
                                }
                                echo '</ul>';
                            endforeach;
                        }
                        ?>
                    </div>
                    <div class="dynamic-megamenu__header">
                        <p class="mb0 term-active-name">■ <b></b></p>
                        <a href="#" class="megamenu-close" aria-label="Close"></a>
                    </div>
                </div> 
            </div>
        </div> 
        <?php
        return ob_get_clean();
    }
}
