<?php
function print_open_minicart( $atts, $content = null ) {
	$a = shortcode_atts( array(
		'text' => 'Ver carrito',
        'show_qty' => 1,
        'link_class' => 'btn'
	), $atts );
    
    $button_class = 'open-minicart';
    if ( $a['show_qty'] === 'false' ) $a['show_qty'] = false;
    if ( (bool) $a['show_qty'] ) $button_class .= ' show-cart-items-qty';

    $link_class = $a['link_class']; 

    $button_content = (empty($content)) ? $a['text'] : do_shortcode($content);

	ob_start(); ?>
	<span class="<?=$button_class?>">
        <a href="#" class="<?=$link_class?>">
            <?php echo $button_content; ?>
        </a>
    </span>
	<?php return ob_get_clean();
}
add_shortcode( 'open_minicart', 'print_open_minicart' );