<?php
use Core\Builder\Component\Post_Title;

global $post;
echo Post_Title::display( array( 'post_id' => $post->ID ) );
?>