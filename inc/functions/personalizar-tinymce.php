<?php
function wpb_mce_buttons_2($mce_buttons) {
    array_unshift($mce_buttons, 'styleselect', 'fontsizeselect');
    $mce_buttons[] = 'alignjustify';
    $mce_buttons[] = 'table';
    return $mce_buttons;
}
add_filter('mce_buttons_2', 'wpb_mce_buttons_2');

/*
* Callback function to filter the MCE settings
* https://codex.wordpress.org/TinyMCE_Custom_Styles
* https://www.wpexplorer.com/wordpress-tinymce-tweaks/
*/
function my_mce_before_init_insert_formats( $init_array ) {  
 
    // Define the style_formats array
    $style_formats = get_style_formats();

    // Insert the array, JSON ENCODED, into 'style_formats'
    $init_array['style_formats'] = json_encode( $style_formats );  

    // set text color palette
    $default_colours = '"000000", "Black",
                      "993300", "Burnt orange",
                      "333300", "Dark olive",
                      "003300", "Dark green",
                      "003366", "Dark azure",
                      "000080", "Navy Blue",
                      "333399", "Indigo",
                      "333333", "Very dark gray",
                      "800000", "Maroon",
                      "FF6600", "Orange",
                      "808000", "Olive",
                      "008000", "Green",
                      "008080", "Teal",
                      "0000FF", "Blue",
                      "666699", "Grayish blue",
                      "808080", "Gray",
                      "FF0000", "Red",
                      "FF9900", "Amber",
                      "99CC00", "Yellow green",
                      "339966", "Sea green",
                      "33CCCC", "Turquoise",
                      "3366FF", "Royal blue",
                      "800080", "Purple",
                      "999999", "Medium gray",
                      "FF00FF", "Magenta",
                      "FFCC00", "Gold",
                      "FFFF00", "Yellow",
                      "00FF00", "Lime",
                      "00FFFF", "Aqua",
                      "00CCFF", "Sky blue",
                      "993366", "Red violet",
                      "FFFFFF", "White"';

    $custom_colours = get_tinymce_custom_colors(); 

    // build colour grid default+custom colors
    $init_array['textcolor_map'] = '['.$default_colours.','.$custom_colours.']';
     
    return $init_array;  
} 
add_filter( 'tiny_mce_before_init', 'my_mce_before_init_insert_formats' ); 






// Customize mce editor font sizes
if ( ! function_exists( 'wpex_mce_text_sizes' ) ) {
  function wpex_mce_text_sizes( $initArray ){
    $initArray['fontsize_formats'] = "10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 26px 27px 28px 29px 30px 32px 35px 40px 41px 42px 43px 44px 45px 55px 65px 80px";
    return $initArray;
  }
}
add_filter( 'tiny_mce_before_init', 'wpex_mce_text_sizes' );




// Customize mce editor font sizes
// if ( ! function_exists( 'wpex_mce_text_sizes' ) ) {
//   function wpex_mce_text_sizes( $initArray ){
//     $initArray['fontsize_formats'] = "9px 10px 12px 13px 14px 16px 18px 21px 24px 28px 32px 36px";
//     return $initArray;
//   }
// }
// add_filter( 'tiny_mce_before_init', 'wpex_mce_text_sizes' );




// Add Google Scripts for use with the editor
if ( ! function_exists( 'wpex_mce_google_fonts_styles' ) ) {
  function wpex_mce_google_fonts_styles() {
      // $font_url = 'http://fonts.googleapis.com/css?family=Lato:300,400,700';
      $font_url = FONT_AWESOME;
      add_editor_style( str_replace( ',', '%2C', $font_url ) ); // string replace?
  }
}
add_action( 'init', 'wpex_mce_google_fonts_styles' );




// ***************************************************************************************************
// ***************************************************************************************************
// ***************************************************************************************************
// Adding A Simple MCE Button
function add_icon_mce_button() {
  // check user permissions
  if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) return;

  // check if WYSIWYG is enabled
  if ( 'true' == get_user_option( 'rich_editing' ) ) {
    add_filter( 'mce_external_plugins', 'add_icon_tinymce_plugin' );
    add_filter( 'mce_buttons', 'register_icon_mce_button' );
  }
}
add_action('admin_head', 'add_icon_mce_button');

// Declare script for new button
function add_icon_tinymce_plugin( $plugin_array ) {
  $plugin_array['icon_mce_button'] = get_template_directory_uri() .'/assets/js/mce-icon-button.js';
  return $plugin_array;
}

// Register new button in the editor
function register_icon_mce_button( $buttons ) {
  array_push( $buttons, 'icon_mce_button' );
  return $buttons;
}

// Load a stylesheet with your CSS
// function my_shortcodes_mce_css() {
//   wp_enqueue_style('symple_shortcodes-tc', plugins_url('/css/my-mce-style.css', __FILE__) );
// }
// add_action( 'admin_enqueue_scripts', 'my_shortcodes_mce_css' );







function add_the_table_plugin( $plugins ) {
    $plugins['table'] = get_template_directory_uri() .'/assets/js/mce-table-plugin.min.js';
    return $plugins;
}
add_filter( 'mce_external_plugins', 'add_the_table_plugin' );