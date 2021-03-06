<?php
class Theme_Nav_Walker extends Walker_Nav_Menu{

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
        // ************************************************************************************************************
        // ************************************************************************************************************
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $class_names = $value = '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        $style = get_post_meta($item->ID,'style',true);
        $classes[] = (!empty($style)) ? $style : '';
        if ($is_megamenu) $classes[] = 'has-megamenu';
        if ($hide_label) $classes[] = 'hidden-text';

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= $indent . '<li' . $id . $value . $class_names .'>';
    
        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';
        if ($is_megamenu) {
            $atts['data-activates']  = 'megamenu-'.$item->ID;
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

        if ($depth == 0) {
        
            $identificador = get_post_meta($item->ID,'identificador',true);
            $icon_html = '';
            if($identificador != ''){
                $icon = get_post_meta($item->ID,'menu_item_icon',true);
                $image = get_post_meta($item->ID,'menu_item_image',true);
                $imagen_url = wp_get_attachment_url($image);
                $icon_html = ( $identificador == 'imagen' && $image ) ? '<span><img src="'.$imagen_url .'" /></span>' : '<span><i class="fa '.$icon.'"></i></span>';
            }

            $item_output = $args->before;
            $item_output .= '<a'. $attributes .'>';
            $item_output .= $icon_html;
            if(!$hide_label) $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
            $item_output .= '</a>';
            $item_output .= $args->after;

            if( $is_megamenu ) {
                $megamenu_data = get_post_meta($item->ID,'megamenu_post',true);
                $megamenu_id = str_replace('post_', '', $megamenu_data);

                $item_output .= '<div id="megamenu-'.$item->ID.'" class="megamenu"><div class="container">';
                $item_output .= ultimate_fields_page_content($megamenu_id); 
                $item_output .= '<a href="#" class="megamenu-close"></a>';
                $item_output .= '</div></div>'; 
            }

        } else {
            $item_output = $args->before;
            $item_output .= '<a'. $attributes .'>';
            $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
            $item_output .= '</a>';
            $item_output .= $args->after;
        }
        
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

add_filter( 'wp_nav_menu_objects', 'remove_sub_items', 10, 2 );
function remove_sub_items( $items,$args ) {
    if( $args->theme_location == 'main-nav' ){
        $new_items = array();
        for ($i=1;$i<count($items)+1;$i++){
            if( empty($items[$i]->menu_item_parent) ){
               $new_items= array_merge($new_items, nav_tree($items[$i],$items));
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
            $rtn= array_merge($rtn,nav_tree($items[$i],$items));
        }
    }
    return $rtn;
}