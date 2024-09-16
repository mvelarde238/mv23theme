<?php
namespace Core\Frontend;

use Walker_Nav_Menu;
use Core\Frontend\Page;

class Nav_Walker extends Walker_Nav_Menu{

    public function __construct(){
        // add_filter( 'wp_nav_menu_objects', array($this, 'remove_sub_items'), 10, 2 );
    }

    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        if (is_array($args)) return $output;

        // ************************************************************************************************************
        // ************************************************************************************************************
        $visibility = get_post_meta($item->ID,'visibility',true);

        if ($visibility == 'is_private' && !current_user_can('administrator')) return;
        if ($visibility == 'user_is_logged_in' && !is_user_logged_in() ) return;
        if ($visibility == 'user_is_not_logged_in' && is_user_logged_in() ) return;

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
        if ($is_megamenu) $classes[] = 'has-megamenu';
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

        $output .= $indent . '<li' . $id . $value . $class_names . $style.'>';
    
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

        if( $depth == 0 && $is_megamenu ) {
            $page = new Page();
            $megamenu_data = get_post_meta($item->ID,'megamenu_post',true);
            $megamenu_id = str_replace('post_', '', $megamenu_data);
            $item_output .= '<div id="megamenu-'.$item->ID.'" class="megamenu"><div class="container">';
            $item_output .= $page->the_content( $megamenu_id );
            $item_output .= '<a href="#" class="megamenu-close"></a>';
            $item_output .= '</div></div>'; 
        }
        
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    function remove_sub_items( $items,$args ) {
        if( $args->theme_location == 'main-nav' ){
            $new_items = array();
            for ($i=1;$i<count($items)+1;$i++){
                if( empty($items[$i]->menu_item_parent) ){
                   $new_items= array_merge($new_items, $this->nav_tree($items[$i],$items));
                }
            } 
            return $new_items; 
        } else {
            return $items;   
        }
    }
    
    function nav_tree($parent,$items){
        $rtn = array();
        $rtn[] = $parent;
    
        $is_megamenu = get_post_meta($parent->ID,'is_megamenu',true);
        if ($is_megamenu) return $rtn;
    
        for ($i=1;$i<count($items)+1;$i++){
            if($items[$i]->menu_item_parent && $items[$i]->menu_item_parent == $parent->ID){
                $rtn= array_merge($rtn, $this->nav_tree($items[$i],$items));
            }
        }
        return $rtn;
    }
}
