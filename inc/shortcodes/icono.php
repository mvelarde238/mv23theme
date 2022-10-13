<?php
function print_icono( $atts ) {
	$a = shortcode_atts( array(
		'element' => 'icono',
		'name' => 'fa-home',
		'fontsize' => 40,
		'color' => '',
		'textalign' => '',
		'style' => 'default',
		'bgc' => '',
		'bgc_alpha' => '',
		'enlace' => null,
		'image' => null,
		'has_bgc' => false,
	), $atts );

	$style = '';
	$icon_element = $a['element'];

	if ($icon_element == 'icono') {
		$element = '<i class="fa '.$a['name'].'"></i>';
	} else {
		$imagen_url = wp_get_attachment_url($a['image']);
		$element = '<img style="height:'.$a['fontsize'].'px;" src="'.$imagen_url .'" />';
	};

	$style = 'font-size:'.$a['fontsize'].'px;';
	if ($a['textalign'] && $a['textalign'] != 'left') $style .= "text-align:".$a['textalign'].";";
	if($a['color']) $style .= 'color:'.$a['color'].';';
	$style = ($style) ? 'style="'.$style.'"' : '';

	$classes = array('icon');
	if($a['style']!='default' && $a['style'] != '') array_push($classes, 'icon--'.$a['style']);
	$class = (!empty($classes)) ? 'class="'.implode(' ',$classes).'"' : '';

	$hasBackground = false;
	if ($a['style'] == 'circle' ) $hasBackground = true;
	if ($a['style'] == 'circle-outline' && $a['has_bgc'] == 1 ) $hasBackground = true;
	$backgroundColor = ( $hasBackground ) ? 'rgba('.hexToRgb($a['bgc'],$a['bgc_alpha']).')' : '';
	
	$link = NULL;
	$enlace = $a['enlace'];
	if($enlace != null){
		switch ($enlace['url_type']) {
		    case 'externa':
		        $link = $enlace['url'];
		        break;
			
		    case 'interna':
		        $link = get_permalink( str_replace('post_','',$enlace['post']) );
		        break;
		}
	}
	$target = ( $enlace != null && $enlace['new_tab'] == 1) ? '_blank' : '';
	ob_start(); ?>
	<p <?=$class?> <?=$style?>>
		<?php if ($a['style']!='default') { echo '<span style="background-color:'.$backgroundColor.'">'; } else { echo '<span>'; }; ?>
			<?php echo $element; ?>
		</span>
		<?php if ($link != NULL) echo '<a href="'.$link.'" target="'.$target.'"></a>'; ?>
	</p>
	<?php return ob_get_clean();
}
add_shortcode( 'icon', 'print_icono' );



function print_icono_inline( $atts ) {
	$a = shortcode_atts( array(
		'name' => 'fa-home',
		'style' => 'default',
		'color' => '',
		'bgc' => '',
	), $atts );

	ob_start(); ?>
	<?php if ($a['style']!='default' && $a['style'] != '') echo '<span class="inline-icon inline-icon--circle" style="background-color:'.$a['bgc'].';color:'.$a['color'].';">'; ?>
	<i class="fa <?php echo $a['name']; ?>"></i><?php if ($a['style']!='default' && $a['style'] != '') echo '</span>'; ?><?php return ob_get_clean();
}
add_shortcode( 'i', 'print_icono_inline' );