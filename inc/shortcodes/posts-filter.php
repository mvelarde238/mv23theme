<?php
function print_posts_filter( $atts ) {
	$a = shortcode_atts( array(
        'posttype' => 'posts',
        'firstyear' => 2012,
        'show_tax' => 1,
        'taxonomy' => null
    ), $atts );

    $current_year = date('Y');
    $first_year = ($a['firstyear'] == '') ? $current_year : $a['firstyear'];

    if( $a['taxonomy'] == null ){
        $taxonomies = get_object_taxonomies( $a['posttype'] );
        $a['taxonomy'] = (count($taxonomies) > 0) ? $taxonomies[0] : 'category';
    }

    $categories = get_terms( array( 'taxonomy' => $a['taxonomy'], 'hide_empty' => false ));

    $search = array( 'es' => 'BUSCAR:', 'en' => 'SEARCH:' );
    $category = array( 'es' => 'CATEGORÍA:', 'en' => 'CATEGORY:' );
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
            <?php if ($a['show_tax']) : ?>
            <div class="field-wrapper">
                <span class="field-desc"><?php echo $category[$current_lang] ?></span>
                <select class="posts-filter__term-select">
                    <option value=""><?php echo $all[$current_lang] ?></option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat->term_id ?>"><?php echo $cat->name ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <?php endif; ?>
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
            <div class="field-wrapper">
                <span class="field-desc"><?php echo $year[$current_lang] ?></span>
                <select name="Y" class="posts-filter__year-select">
                    <option value=""><?php echo $allm[$current_lang] ?></option>
                    <?php for ($i=$current_year; $i >= $first_year ; $i--) { ?>
                        <option value="<?=$i?>"><?=$i?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="field-wrapper">
                <input type="hidden" value="<?=$a['posttype']?>" class="posts-filter__posttype">
                <input type="hidden" value="<?=$a['taxonomy']?>" class="posts-filter__taxonomy">
                <button class="posts-filter__submit btn"><?php echo $filter[$current_lang] ?></button>
            </div>
        </form>
    </div>
	<?php return ob_get_clean();
}
add_shortcode( 'posts_filter', 'print_posts_filter' );