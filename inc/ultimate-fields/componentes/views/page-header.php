<?php
$tipo = $componente['__type'];

$page_header_type = $componente['page_header_type'];
?>
<header class="componente-<?=$tipo?>">
    <div>
        <?php
        switch ($page_header_type) {
            case 'contenido':
                $page_header_content = $componente['page_header_content'];

                $page_header_bgi_type = 'imagen';

                if ($page_header_bgi_type == 'imagen') {
                    $page_header_bgi = $componente['page_header_bgi'];
                    $fondo = wp_get_attachment_url($page_header_bgi);
                } 

                // if ($page_header_bgi_type == 'video') {
                //     $page_header_video = $componente['page_header_video'];
                //     $page_header_video_image = $componente['page_header_video_image'];
                //     $fondo = ($page_header_video_image['url']) ? $page_header_video_image['url'] : '';
                // } 

                $bgi_style = ($fondo) ? 'background-image: url('.$fondo.');' : '';

                if ($page_header_content) : ?>
                    <header class="page-header" style="<?=$bgi_style?>">
                        <?php if ( !empty($page_header_video) && !wp_is_mobile() ): ?>
                            <?php if ( strstr($page_header_video['mime_type'], 'video/')): ?>
                                <video poster="<?=$fondo?>" playsinline autoplay muted loop>
                                    <source src="<?php echo $page_header_video['url']; ?>" type="<?php echo $page_header_video['mime_type']; ?>">
                                </video>
                            <?php endif ?> 
                        <?php endif ?>
                        <div class="page-header__content"><?php echo $page_header_content; ?></div>
                    </header>
                    <?php 
                endif;
                break;

           default:
                echo '<header class="page-header">';
                echo '<div class="page-header__content">';
                if (is_archive()) :
                    the_archive_title( '<h1>', '</h1>' );
                else:
                    the_title( '<h1>', '</h1>', true );
                endif;
                echo '</div>';
                echo '</header>';
                break;
        } 
        ?>
    </div>
</header>