<?php
function print_proyectos(){
    $args_query = array( 
        'post_type' => 'proyecto',
        'posts_per_page' => -1,
        'order'=>'DESC'
    );

    $query = new WP_Query( $args_query ); 

    if ($query->have_posts()) :
        ob_start(); ?>
        <div class="proyectos-list full-width">
            <?php    
            while ( $query->have_posts() ) : $query->the_post();
                $post_id = get_the_ID(); 
                $link = get_the_permalink($post_id);
                $terms = get_the_terms( $post_id, 'proyecto-cat');
                $terms_slugs = array();
                if (is_array($terms) && count($terms)>0 ) {
                    foreach($terms as $term):
                        $terms_slugs[] = $term->slug;
                    endforeach;
                }
                $the_terms_slugs = join( " ", $terms_slugs );
                ?>
                <div class="proyectos-list__item <?php echo $the_terms_slugs; ?>">
                    <?php echo ultimate_fields_page_content( $post_id ); ?>
                </div>
            <?php endwhile; ?>
        </div>
        <?php return ob_get_clean();
    endif;
}
add_shortcode( 'proyectos', 'print_proyectos' );




function print_proyectos_filter(){
    $args = array('orderby'  => 'id','order' => 'ASC');
    $terms = get_terms( 'proyecto-cat', $args ); 

    $option_default = ($_GET['category']) ? $_GET['category'] : '*';

    ob_start(); ?>
    <ul class="proyectos-filter">
        <li>
            <input type="radio" name="proyecto-filtro" id="all" value="*" checked <?php checked( $option_default, '*', true ); ?>>
            <label for="all">Todos</label>
        </li>
        <?php 
        foreach($terms as $term): 
            ?>
            <li>
                <input id="<?=$term->slug?>-cat-<?=$term->term_id?>" type="radio" name="proyecto-filtro" value=".<?=$term->slug?>" <?php checked( $option_default, $term->slug, true ); ?>>
                <label for="<?=$term->slug?>-cat-<?=$term->term_id?>"><?php echo $term->name ?></label>
            </li>
            <?php
        endforeach; ?>
    </ul>
    <?php return ob_get_clean();
}
add_shortcode( 'proyectos_filter', 'print_proyectos_filter' );