<?php
use Core\Builder\Component\Listing;
use Core\Posttype\Document;
use Core\Frontend\Post_Card;
use Core\Theme_Options\UF_Container\Posts_Subscription;
use Core\Theme_Options\UF_Container\Track_Posts_Data;
use Core\Frontend\Taxonomy_Breadcrumbs;

get_header();
$main_content_classes = array('main-content','container');

global $post;
$id = get_the_ID();
$thumb_url = Post_Card::get_thumbnail($post, 'full');
$document_link = Document::get_document_file_url($id);
$content_type = get_post_meta($id, 'content_type', true);
$caption = esc_attr($title);
$ext = ($document_link) ? pathinfo($document_link, PATHINFO_EXTENSION) : 'pdf';
$icon = 'bi-filetype-'.$ext;
$file_size = null;

$remote_video_data = Document::get_remote_video_data($id);
$is_remote_video = ($remote_video_data['link']) ? true : false;
if( $is_remote_video ){
    $ext = 'video';
    $icon = $remote_video_data['icon'];
}

if ( $content_type == 'file' && !$is_remote_video ) {
    $file_size = Document::get_file_size($id);
}

$thumb_url = Document::get_thumbnail($thumb_url, $ext, $document_link, $id);
$can_be_previewed = Document::can_be_previewed($ext);

// tags
$tags = Post_Card::get_secondary_taxonomy_terms($post);

$subscribe_to_continue = Posts_Subscription::post_subscription_is_active($id);
if($document_link && $subscribe_to_continue) $document_link = '#';
?>

<div id="content">
	<div id="main-content" class="<?php echo implode(' ',$main_content_classes) ?>">
		<main class="main">
            <div class="single-document__content-wrapper">
                <div class="single-document__image-wrapper">
                    <div class="single-document__image">
                        <?php
                            if($document_link) {
                                // TOD DO: check susbscription 
                                if( $can_be_previewed ){
                                    echo '<a href="'.esc_url($document_link).'"';
                                    if(!$subscribe_to_continue) echo ' class="previsualization-count-js" data-fancybox data-caption="'.$caption.'"'; 
                                    echo ' title="'.__('Preview', 'mv23theme').'">';
                                } else {
                                    if( !$is_remote_video ): 
                                        echo '<a href="'.esc_url($document_link).'"';
                                        if(!$subscribe_to_continue) echo ' class="download-count-js" download';
                                        echo ' title="'.__('Download', 'mv23theme').'">';
                                    endif;
                                }
                            }
                            if( !str_contains($thumb_url, 'nothumb') ){
                                echo '<img src="'.$thumb_url.'" alt="Document image">';
                            } else {
                                echo '<div class="img"><i class="bi '.$icon.'"></i></div>';
                            }
                            if($document_link && $can_be_previewed) echo '</a>';
                        ?>
                    </div>
                </div>
                <div class="single-document__content">
                    <div>
                        <?php get_template_part('partials/breadcrumbs'); ?>
                        <div class="single-document__title-wrapper">
                            <?php the_title('<h1 class="single-document__title">', '</h1>'); ?>
                        </div>
                    </div>

                    <div>
                        <?php
                        $description = get_post_meta($id, 'description', true);
                        if($description) echo do_shortcode(wpautop(oembed( $description )));

                        do_action('single_document_before_metadata');

                        echo '<p><b>' . __('Extension: ', 'mv23theme') . '</b>' . $ext . '</p>';
                        if($file_size) echo '<p><b>' . __('File size: ', 'mv23theme') . '</b>' . $file_size . '</p>';
                        $last_modified = get_the_modified_date('d/m/Y', $id);
                        echo '<p><b>' . __('Last modified: ', 'mv23theme') . '</b>' . $last_modified . '</p>';
                        if( Track_Posts_Data::track_data_is_active($post) ){
                            echo '<p><b>' . __('Views: ', 'mv23theme') . '</b>' . do_shortcode('[post_views]') . '</p>';
                        }

                        if($tags && !is_wp_error($tags) && count($tags) > 0) {
                            echo '<p><b>' . __('Tags: ', 'mv23theme') . '</b>';
                            $tag_links = array();
                            foreach ($tags as $tag) {
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
                            if( Track_Posts_Data::track_data_is_active($post) ){
                                echo '<a href="#" class="btn like-count-js"><i class="bi bi-heart"></i> '.do_shortcode('[post_likes]').'</a>';
                            }

                            if($document_link){
                                if ( $can_be_previewed ) {
                                    if($subscribe_to_continue){
                                        $document_link = add_query_arg(array(
                                            'id' => $id,
                                            'action' => 'subscribe-to-preview'
                                        ), home_url('/'));
                                    }
                                
                                    echo '<a href="'.esc_url($document_link).'"';
                                    if(!$subscribe_to_continue) echo ' class="btn previsualization-count-js" data-fancybox data-caption="'.$caption.'"'; 
                                    if($subscribe_to_continue) echo ' class="btn"'; 
                                    echo ' title="'.__('Preview', 'mv23theme').'"><i class="bi bi-arrows-angle-expand"></i> '.__('Preview', 'mv23theme').'</a>';
                                };

                                if( !$is_remote_video ): 
                                    if($subscribe_to_continue){
                                        $document_link = add_query_arg(array(
                                            'id' => $id,
                                            'action' => 'subscribe-to-download'
                                        ), home_url('/'));
                                    }
                                
                                    echo '<a href="'.esc_url($document_link).'"';
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
            <div class="single-document__related-posts">
                <?php
                $title = __('Related Documents', 'mv23theme');

                printf('<h4 class="single-document__related-posts-title">%s</h4>', $title);

                // filter the related documents arguments
                $related_docs_args = apply_filters('filter_related_'.$post->post_type.'_args', array(
                    'show' => 'auto',
                    'post__not_in' => array($id),
                    'qty' => 5,
                    'items_in_desktop' => 3,
                    'items_in_laptop' => 3,
                    'items_in_tablet' => 3,
                    'items_in_mobile' => 1,
                    'd_gap' => 30,
                    'l_gap' => 30,
                    't_gap' => 30,
                    'm_gap' => 15,
                    'post_template' => 'document',
                    'list_template' => 'carrusel',
                    'show_controls' => 1,
                    'posttype' => $post->post_type,
                ), $id);

                echo Listing::display($related_docs_args);
                ?>
            </div>
		</main>
	</div>
</div>

<?php get_footer(); ?>