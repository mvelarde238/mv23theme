<?php
/**
 * Template Name: Sidebar Left
 *
 */
get_header(); ?>

<div id="content">
    <?php get_template_part('inc/modulos/page-header'); ?>
    <div class="main-content container main-content--sidebar-left">
        <main class="main">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <?php the_content(); ?>
                        <?php echo ultimate_fields_page_content(get_the_ID()); ?>
                    </article>
                    <?php comments_template(); ?>
            <?php endwhile;
            endif; ?>
            <?php get_template_part('inc/modulos/modals/controlador'); ?>
        </main>
        <div class="sidebar">
            <?php if (is_active_sidebar('page_sidebar')) : ?>
                <?php dynamic_sidebar('page_sidebar'); ?>
            <?php endif ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>