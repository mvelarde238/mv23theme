<?php
// Remove Custom Fields box from the Post edit screen.
function remove_post_custom_fields() {

  $post_types = get_post_types( '', 'names' ); 

  foreach ( $post_types as $post_type ) {
    remove_meta_box( 'postcustom' , $post_type , 'normal' );     
  }

}

add_action( 'admin_menu' , 'remove_post_custom_fields' );