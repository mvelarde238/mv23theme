<?php
use Core\Posttype\Document;
use Core\Frontend\Post_Card;
use Core\Theme_Options\UF_Container\Posts_Subscription;
use Core\Theme_Options\UF_Container\Track_Posts_Data;
use Core\Frontend\Taxonomy_Breadcrumbs;
use Core\Theme_Options\Theme_Options;

get_header();
$main_content_classes = array('main-content','container');

global $post;

$document_data = Document::get_document_data($post->ID);

$single_args = array_merge( array(
    'id' => $post->ID,
    'title' => $post->post_title,
    'thumbnail' => Post_Card::get_thumbnail($post, 'full'),
    'tags' => Post_Card::get_secondary_taxonomy_terms($post),
    'description' => get_post_meta($post->ID, 'description', true)
), $document_data );

$_args = apply_filters( 'filter_single_args', $single_args, $post );

$subscribe_to_continue = Posts_Subscription::is_active($post);

$preview_file_url = Posts_Subscription::maybe_obfuscate_link( $subscribe_to_continue, $_args['file_url'], 'subscribe-to-preview', $post->ID);

$download_file_url = Posts_Subscription::maybe_obfuscate_link( $subscribe_to_continue, $_args['file_url'], 'subscribe-to-download', $post->ID);

$theme_options = Theme_Options::getInstance();
$single_page = $theme_options->get_page_template_settings('single');
$main_content_classes[] = $single_page['page_template'];
?>

<div id="content">
	<div id="main-content" class="<?php echo implode(' ',$main_content_classes) ?>">
		<main class="main">
            <div class="single-document__content-wrapper">
                <div class="single-document__image-wrapper">
                    <div class="single-document__image">
                        <?php
                            if( $_args['can_be_previewed'] ){
                                echo '<a href="'.esc_url($preview_file_url).'"';
                                if(!$subscribe_to_continue) echo ' class="previsualization-count-js" data-fancybox data-caption="'.esc_attr($_args['title']).'"'; 
                                echo ' title="'.__('Preview', 'mv23theme').'">';
                            } else {
                                if( !$_args['is_remote_video'] ): 
                                    echo '<a href="'.esc_url($download_file_url).'"';
                                    if(!$subscribe_to_continue) echo ' class="download-count-js" download';
                                    echo ' title="'.__('Download', 'mv23theme').'">';
                                endif;
                            }
                            if( !str_contains($_args['thumbnail'], 'nothumb') ){
                                echo '<img src="'.$_args['thumbnail'].'" alt="Document image">';
                            } else {
                                echo '<div class="img"><i class="bi '.$_args['icon'].'"></i></div>';
                            }
                            if( $_args['can_be_previewed'] ) echo '</a>';
                            if( !$_args['can_be_previewed'] && !$_args['is_remote_video'] ) echo '</a>';
                        ?>
                    </div>
                </div>
                <div class="single-document__content">
                    <div>
                        <?php get_template_part('partials/breadcrumbs'); ?>
                        <div class="single-document__title-wrapper">
                            <h1 class="single-document__title"><?php echo $_args['title'] ?></h1>
                        </div>
                    </div>

                    <div>
                        <?php
                        if($_args['description']) echo do_shortcode(wpautop(oembed( $_args['description'] )));

                        do_action('single_document_before_metadata');

                        echo '<p><b>' . __('Extension: ', 'mv23theme') . '</b>' . $_args['extension'] . '</p>';
                        if($_args['file_size']) echo '<p><b>' . __('File size: ', 'mv23theme') . '</b>' . $_args['file_size'] . '</p>';
                        echo '<p><b>' . __('Last modified: ', 'mv23theme') . '</b>' . $_args['last_modified'] . '</p>';
                        if( Track_Posts_Data::is_active($post) ){
                            echo '<p><b>' . __('Views: ', 'mv23theme') . '</b>' . do_shortcode('[post_views]') . '</p>';
                        }

                        if($_args['tags'] && !is_wp_error($_args['tags']) && count($_args['tags']) > 0) {
                            echo '<p><b>' . __('Tags: ', 'mv23theme') . '</b>';
                            $tag_links = array();
                            foreach ($_args['tags'] as $tag) {
                                $tag_links[] = '<a href="'.get_term_link($tag).'">'.$tag->name.'</a>';
                            }
                            echo implode(', ', $tag_links);
                            echo '</p>';
                        }

                        do_action('single_document_after_metadata');
                        ?>
                    </div>

                    <div>
                        <div class="single-document__actions">
                            <?php
                            if( Track_Posts_Data::is_active($post) ){
                                echo '<a href="#" class="btn like-count-js"><i class="bi bi-heart"></i> '.do_shortcode('[post_likes]').'</a>';
                            }

                            if( $_args['file_url'] ){
                                if ( $_args['can_be_previewed'] ) :
                                    echo '<a href="'.esc_url($preview_file_url).'"';
                                    if(!$subscribe_to_continue) echo ' class="btn previsualization-count-js" data-fancybox data-caption="'.esc_attr($_args['title']).'"'; 
                                    if($subscribe_to_continue) echo ' class="btn"'; 
                                    echo ' title="'.__('Preview', 'mv23theme').'"><i class="bi bi-arrows-angle-expand"></i> '.__('Preview', 'mv23theme').'</a>';
                                endif;

                                if( !$_args['is_remote_video'] ):                                 
                                    echo '<a href="'.esc_url($download_file_url).'"';
                                    if(!$subscribe_to_continue) echo ' class="btn download-count-js" download';
                                    if($subscribe_to_continue) echo ' class="btn"'; 
                                    echo ' title="'.__('Download', 'mv23theme').'"><i class="bi bi-download"></i> '.__('Download', 'mv23theme').'</a>';
                                endif;
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php if(!$single_page['hide_social_share']) get_template_part('partials/social-share'); ?>

            <?php if(!$single_page['hide_related_posts']) get_template_part('partials/related-posts'); ?>
		</main>

        <?php if( $single_page['page_template'] !== 'main-content--sidebarless' ) get_sidebar(); ?>
	</div>
</div>

<?php get_footer(); ?>