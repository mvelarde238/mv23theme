<?php
function print_posts_filter( $atts ){
    $a = shortcode_atts( array(
        'posttype' => 'posts',
        'firstyear' => 2012,
        'taxonomy' => 'category'
    ), $atts );

    $first_year = $a['firstyear'];
    $current_year = date('Y');
    $year = ( isset($_GET['Y']) ) ? $_GET['Y'] : $current_year;

    $categories = get_terms( array( 'taxonomy' => $a['taxonomy'], 'hide_empty' => false ));

    ob_start();
    ?>
    <div class="posts-filter">
        <form action="" method="GET">
            <div class="field-wrapper">
                <span class="field-desc">CATEGORÍA:</span>
                <select class="posts-filter__term-select">
                    <option value="">Todas</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat->term_id ?>"><?php echo $cat->name ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="field-wrapper">
                <span class="field-desc">TÍTULO:</span>
                <input type="text" class="posts-filter__search-input">
            </div>
            <!-- <div class="field-wrapper">
                <span class="field-desc">MES:</span>
                <select class="posts-filter__month-select">
                    <option value="">Todos</option>
                    <option value="01">Enero</option>
                    <option value="02">Febrero</option>
                    <option value="03">Marzo</option>
                    <option value="04">Abril</option>
                    <option value="05">Mayo</option>
                    <option value="06">Junio</option>
                    <option value="07">Julio</option>
                    <option value="08">Agosto</option>
                    <option value="09">Setiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>
            </div>
            <div class="field-wrapper">
                <span class="field-desc">AÑO:</span>
                <select name="Y" class="posts-filter__year-select">
                    <?php for ($i=$current_year; $i >= $first_year ; $i--) { ?>
                        <option value="<?=$i?>" <?php selected( $year, $i ); ?>><?=$i?></option>
                    <?php } ?>
                </select>
            </div> -->
            <div class="field-wrapper">
                <input type="hidden" value="<?=$a['posttype']?>" class="posts-filter__posttype">
                <input type="hidden" value="<?=$a['taxonomy']?>" class="posts-filter__taxonomy">
                <button class="posts-filter__submit btn">FILTRAR</button>
            </div>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'posts_filter', 'print_posts_filter' );