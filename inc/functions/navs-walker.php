<?php
if (!function_exists('navs_walker')){
    function navs_walker($item_output, $item, $depth, $args) {
        $menu_locations = get_nav_menu_locations();
    
        // if ( has_term( array($menu_locations['main-nav'],$menu_locations['secondary-nav']) , 'nav_menu', $item) ) {
        if ( has_term( $menu_locations['main-nav'] , 'nav_menu', $item) ) {
        	$visibility = get_post_meta($item->ID,'visibility',true);
    
            if ($visibility == 'is_private' && !current_user_can('administrator')) return;
            if ($visibility == 'user_is_logged_in' && !is_user_logged_in() ) return;
            if ($visibility == 'user_is_not_logged_in' && is_user_logged_in() ) return;
    
            $icon = get_post_meta($item->ID,'menu_item_icon',true);
            if($icon) $item_output = preg_replace('/">/', '"><span><i class="fa '.$icon.'"></i></span>', $item_output, 1);
            
            // $btn_style = get_post_meta($item->ID,'btn_style',true);
        	// $item_output = preg_replace('/">/', '" class="'.$btn_style. '">', $item_output, 1);
    
            $is_megamenu = get_post_meta($item->ID,'is_megamenu',true);
        	if( $is_megamenu ) {
                $megamenu_post = get_post_meta($item->ID,'megamenu_post',true);
                $megamenu_bgi = get_post_meta($megamenu_post,'fondo',true);
        		$content_position = get_post_meta($megamenu_post,'content_position',true);
    
                $item_output = preg_replace('/<a /', '<a class="megamenu-link" ', $item_output, 1);
                $item_output .= '<ul class="sub-menu megamenu megamenu-content'.$content_position.'" style="background-image: url('.wp_get_attachment_url($megamenu_bgi).')">';
           	    $item_output .= '<li class="container">';
                $item_output .= ultimate_fields_page_content($megamenu_post); 
           	    $item_output .= '</li></ul>'; 
        	}
    
            if (in_array('logo', $item->classes)) {
                $logo_id = get_option( 'main_logo' );
                $logo_url = ($logo_id) ? wp_get_attachment_image_src( $logo_id, 'full') : array(); 
                $logo = ( count($logo_url) > 0 ) ? '<img src="'.$logo_url[0].'" alt="Logo">' : '';
                $item_output = preg_replace('/">/', '">'.$logo, $item_output, 1);
            }
        }
    
        return $item_output;
    }
}

add_filter('walker_nav_menu_start_el', 'navs_walker', 10, 4);