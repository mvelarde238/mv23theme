<?php
function print_posts_filter( $atts ) {
	$a = shortcode_atts( array(
        'posttype' => 'posts',
        'firstyear' => 2012,
        // 'show_tax' => 1,
        'show_month' => 1,
        'show_year' => 1,
        'default_year' => '',
        // 'taxonomy' => null,
        // 'default_term' => '',
        'taxonomies' => null,
        'default_terms' => null
    ), $atts );

    $current_year = date('Y');
    $first_year = ($a['firstyear'] == '') ? $current_year : $a['firstyear'];

    $taxonomies = explode(',',$a['taxonomies']);
    $default_terms = explode(',',$a['default_terms']);
    if( !is_array( $default_terms ) ) $default_terms = array();

    $search = array( 'es' => 'BUSCAR:', 'en' => 'SEARCH:' );
    $all = array( 'es' => 'Todas', 'en' => 'All' );
    $allm = array( 'es' => 'Todos', 'en' => 'All' );
    $month = array( 'es' => 'MES:', 'en' => 'MONTH:' );
    $year = array( 'es' => 'AÑO:', 'en' => 'YEAR:' );
    $filter = array( 'es' => 'FILTRAR', 'en' => 'FILTER' );
    $months = array(
        'es' => array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'),
        'en' => array('January','February','March','April','May','June','July','August','September','October','November','December'),
    );
    $current_lang = (function_exists('pll_current_language')) ? pll_current_language() : 'es';
	ob_start(); ?>
	<div class="posts-filter">
        <form action="" method="GET">
            <div class="field-wrapper">
                <span class="field-desc"><?php echo $search[$current_lang] ?></span>
                <input type="text" class="posts-filter__search-input">
            </div>
            <?php if ( is_array($taxonomies) && count($taxonomies) > 0 ) : 
                $count = 0;
                foreach ($taxonomies as $tax) { 
                    $taxonomy = get_taxonomy( $tax );
                    if( $taxonomy ){
                        $taxonomy_label = $taxonomy->label; 
                        $tax_name = array( 'es' => strtoupper($taxonomy_label).':', 'en' => __(strtoupper($taxonomy_label)).':' );
                        $terms = get_terms( array( 'taxonomy' => $tax, 'hide_empty' => false ));
                        $default_term = ( isset($default_terms[$count]) ) ? $default_terms[$count] : 0;
                        ?>
                        <div class="field-wrapper">
                            <span class="field-desc"><?php echo $tax_name[$current_lang] ?></span>
                            <select class="posts-filter__term-select">
                                <option value=""><?php echo $all[$current_lang] ?></option>
                                <?php foreach ($terms as $term): ?>
                                    <option value="<?php echo $term->term_id ?>" <?php selected( $default_term, $term->term_id, true) ?>><?php echo $term->name ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <?php
                    }
                    $count++; 
                } 
            endif; ?>
            <?php if ($a['show_month']) : ?>
            <div class="field-wrapper">
                <span class="field-desc"><?php echo $month[$current_lang] ?></span>
                <select class="posts-filter__month-select">
                    <option value=""><?php echo $allm[$current_lang] ?></option>
                    <?php for ($i=0; $i < count($months[$current_lang]); $i++) {
                        $value = ( $i < 10 ) ? '0'.($i+1) : $i+1;  
                        echo '<option value="'.$value.'">'.$months[$current_lang][$i].'</option>';
                    }?>
                </select>
            </div>
            <?php endif; ?>
            <?php if ($a['show_year']) : ?>
            <div class="field-wrapper">
                <span class="field-desc"><?php echo $year[$current_lang] ?></span>
                <select name="Y" class="posts-filter__year-select">
                    <option value=""><?php echo $allm[$current_lang] ?></option>
                    <?php for ($i=$current_year; $i >= $first_year ; $i--) { ?>
                        <option value="<?=$i?>" <?php selected( $a['default_year'], $i, true) ?>><?=$i?></option>
                    <?php } ?>
                </select>
            </div>
            <?php endif; ?>
            <div class="field-wrapper">
                <input type="hidden" value="<?=$a['posttype']?>" class="posts-filter__posttype">
                <input type="hidden" value="<?=$a['taxonomies']?>" class="posts-filter__taxonomies">
                <button class="posts-filter__submit btn"><?php echo $filter[$current_lang] ?></button>
            </div>
        </form>
    </div>
	<?php return ob_get_clean();
}
add_shortcode( 'posts_filter', 'print_posts_filter' );