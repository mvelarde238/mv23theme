<?php 
    if( is_search() ){ ?>
        <div class="page-header">
            <div class="container">
                <h1 class="page-header__title center">
                    <?php printf( esc_html__( 'Search Results for: %s', 'mv23theme' ), '<span>' . get_search_query() . '</span>' ); ?>
                </h1>
            </div>
        </div>
        <?php
    }
    if( is_archive() ){ 
        the_archive_title( '<div class="page-header"><div class="container"><h1 class="page-header__title center">', '</h1></div></div>' );
    }
?>