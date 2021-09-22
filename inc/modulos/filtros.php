<?php
function filtros($term){

        
            $terms = get_terms( array(
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'hierarchical' => false,
                'parent' => $term
            ) );
    
            if ( !empty($terms) && !is_wp_error( $terms ) ) :
                ?>
                <p>Mostrar</p> <i class="material-icons">navigate_next</i>
                <div class="filter-selects">
                    <select id="lel" class="browser-default">
                        <option value="<?=$term?>">Todos</option>
                        <?php foreach( $terms as $term ) { 
                            $name = esc_html( $term->name );
                            $id = esc_attr( $term->term_id );
                            $term_children = get_term_children($term->term_id, 'product_cat');
                            $has_children = ( !empty( $term_children ) && !is_wp_error( $term_children ) ) ? true : false;
                            ?>    
                            <option value="<?=$id?>" <?php if ($has_children) echo 'class="has-children"' ?>><?php echo $name; ?></option>
                        <?php } ?>
                    </select> 
                </div>
                <button class="btn filtrar-productos">Filtrar Productos</button>
                <?php
            endif;
        
}