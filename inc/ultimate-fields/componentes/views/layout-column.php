<?php
$content_layout = $componente['content_layout'];
?>
<div class="columnas-simples">
    <?php 
    echo Content_Layout::the_content($content_layout);
    ?>
</div>