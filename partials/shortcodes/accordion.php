<?php
function print_accordion( $atts ){
	$a = shortcode_atts( array(
		'id' => ''
	), $atts ); 

	$componente['__type'] = 'accordion';
	$items = get_post_meta( $a['id'], 'accordion', true );
	$desktop_template = get_post_meta( $a['id'], 'desktop_template', true );
	ob_start(); ?>
	<?php if (is_array($items) && count($items)>0): ?>
        <div class="accordion">
        <div class="v23-togglebox" data-desktop-template="<?=$desktop_template?>">
            <?php
            $nav = '<div class="v23-togglebox__nav">';
            $itemsbox = '<div class="v23-togglebox__items">';
            $count = 0;
            foreach ($items as $item): 
                $titulo = $item['titulo'];
                $slug = str_replace(" ","-",strtolower($titulo));
                $slug = str_replace("?", "b", $slug);
                $slug = str_replace("¿", "a", $slug);
                $slug = str_replace("Ó", "a", $slug);
                $slug = str_replace(".", "", $slug);

                $identificador = $item['identificador'];
                $imagen = wp_get_attachment_url($item['imagen']);
                $icon = ( $identificador == 'imagen' && $imagen ) ? '<img src="'.$imagen .'" />' : $item['icon'];
                $icon_html = ($icon) ? '<span class="fa '.$icon.'"></span>' : '';

                $content_element = $item['content_element'];
                if ($content_element == 'pagina' && $item['page']) {
                    $post_data = $item['page'][0];
                    $post_id = str_replace('post_','',$post_data );
                    $page = new Page();
                    ob_start();
                    echo $page->the_content( $post_id );
                    $contenido = ob_get_clean();
                } else {
                    $contenido = wpautop( do_shortcode( $item['content'], false ) );
                }

                $nav .= '<p class="v23-togglebox__btn" data-boxid="#'.$slug.'">'.$icon_html.$titulo.'</p>';
                $itemsbox .= '<div id="'.$slug.'" class="v23-togglebox__item">'.$contenido.'</div>';
                $count++;
            endforeach; 
            $nav .= '</div>';
            $itemsbox .= '</div>';
            echo $nav . $itemsbox;
            ?>
        </div>
        </div>
    <?php endif ?>
	<?php return ob_get_clean();
}
add_shortcode( 'accordion', 'print_accordion' );