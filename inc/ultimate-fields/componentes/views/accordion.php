<?php
$tipo = $componente['__type'];
$items = $componente['accordion'];
$desktop_template = $componente['desktop_template'];
$tab_style = (isset($componente['tab_style'])) ? $componente['tab_style'] : 'style1';
$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';

$classes_array = format_classes(array(
    'componente',
    $tipo,
    get_color_scheme($componente),
    $componente['class'],
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
    <?php if ($layout == 'layout2') echo '<div class="container">'; ?>
    <?php if (is_array($items) && count($items)>0): ?>
        <div class="v23-togglebox <?php echo 'tab-'.$tab_style ?>" data-desktoptemplate="<?=$desktop_template?>">
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
                $slug = str_replace("(", "", $slug);
                $slug = str_replace(")", "", $slug);
                $slug = str_replace(",", "", $slug);

                $identificador = $item['identificador'];

                switch ($identificador) {
                    case 'imagen':
                        $image = wp_get_attachment_url($item['image']);
                        $style = (isset($item['image_size']) && $item['image_size'] == 'auto') ? 'style="height:auto"' : '';
                        $icon_html = ($image) ? '<img '.$style.' src="'.$image .'" />' : '';
                        break;
                    
                    case 'icono':
                        $icon = $item['icon'];
                        $icon_html = ($icon) ? '<span class="fa '.$icon.'"></span>' : '';
                        break;

                    default:
                        $icon_html = '';
                        break;
                };

                $content_element = $item['content_element'];
                $contenido = '';

                if ($content_element == 'pagina' && $item['page']) {
                    $post_id = $item['page'][0]; 
                    ob_start();
                    echo ultimate_fields_page_content( str_replace('post_','',$post_id ) );
                    $contenido = ob_get_clean();
                } else if( $content_element == 'seccion_reusable' ) {
                    $key = get_post_meta( $item['seccion_reusable'], 'section_type', true );
                    if ($key == 'modulo') {
                        $modulos_reusables = get_post_meta( $item['seccion_reusable'],'v23_modulos', true);
                        if( is_array($modulos_reusables) && count($modulos_reusables)>0 ):
                            ob_start();
                            foreach ($modulos_reusables as $modulo_reusable) {
                                print_module_view($modulo_reusable);
                            }
                            $contenido = ob_get_clean();
                        endif;
                    }
                    if ($key == 'componente') {
                        $componentes = get_post_meta($item['seccion_reusable'],'componentes', true);
                        ob_start();
                        foreach ($componentes as $componente ) { 
                            $path = get_template_directory().'/inc/ultimate-fields/componentes/views/'.$componente['__type'].'.php';
                            include $path;
                        }
                        $contenido = ob_get_clean();
                    }
                } else {
                    // $contenido = apply_filters('the_content', $item['content']);
                    $contenido = '<div class="componente">'.wpautop( do_shortcode( $item['content'], false ) ).'</div>';
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
    <?php endif ?>
    <?php if ($layout == 'layout2') echo '</div>'; ?>
</div> 