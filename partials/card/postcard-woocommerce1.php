<?php
$id = get_the_ID();

// data attributes
$postcard_attributes = array( 'data-id="'.$id.'"'   );
if( !empty($args['on_click_post']) ) $postcard_attributes[] = 'data-action="'.$args['on_click_post'].'"';
if( !empty($args['on_click_scroll_to']) ) $postcard_attributes[] = 'data-scroll-to="'.$args['on_click_scroll_to'].'"';
?>
<div class="postcard" <?php echo implode(' ', $postcard_attributes) ?>>
    <?php echo do_shortcode('[products ids="'.$id.'" columns="1"]'); ?>
</div>