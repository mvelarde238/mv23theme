<?php
function add_button_to_tinymce() {
    // check user permissions
    if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) return;
  
    // check if WYSIWYG is enabled
    if ( 'true' == get_user_option( 'rich_editing' ) ) {
        add_filter( 'mce_external_plugins', 'add_button_tinymce_plugin' );
        add_filter( 'mce_buttons', 'register_button_to_tinymce' );
    }
  }
  add_action('admin_head', 'add_button_to_tinymce');
  
  // Declare script for new button
function add_button_tinymce_plugin( $plugin_array ) {
    $plugin_array['button_manager'] = get_template_directory_uri() .'/assets/js/mce/mce-button-manager.js';
    return $plugin_array;
}
  
// Register new button in the editor
function register_button_to_tinymce( $buttons ) {
    array_push( $buttons, 'button_manager' );
    return $buttons;
}
  