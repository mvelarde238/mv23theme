<?php
/**
 * Template Name: Left Sidebar
 *
 */
get_header(); ?>

<div id="content">
    <div id="main-content" class="main-content  container main-content--sidebar-left">
        <main class="main">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <?php the_content(); ?>
                    </article>
                    <?php get_template_part('partials/comments'); ?>
            <?php endwhile;
            endif; ?>
        </main>

        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer(); ?>