<?php
use Core\Frontend\Post_Card;
use Core\Posttype\Document;

global $post;
$id = $post->ID;
$posttype = $post->post_type;
$title = $post->post_title;
$metadata = array();
$link = Post_Card::get_permalink($post);
$excerpt = Post_Card::get_excerpt($post, 110);
$thumb_url = Post_Card::get_thumbnail($post, 'full');
$postcard_attributes = Post_Card::build_attributes($post, $args);
$main_terms = Post_Card::get_main_taxonomy($post);
$tags = Post_Card::get_secondary_taxonomy($post);

if($posttype == 'document') {
    $file_url = Document::get_document_file_url($id);
    $content_type = get_post_meta($id, 'content_type', true);
    $caption = esc_attr($title);
    $ext = ($file_url) ? pathinfo($file_url, PATHINFO_EXTENSION) : 'pdf';
    $icon = 'bi-filetype-'.$ext;
    $document_link = $file_url;
    
    $remote_video_data = Document::get_remote_video_data($id);
    $is_remote_video = ($remote_video_data['link']) ? true : false;
    if( $is_remote_video ){
        $ext = 'video';
        $icon = $remote_video_data['icon'];
        $document_link = $remote_video_data['link'];
    }

    $metadata[] = $ext;

    // FILE SIZE
    if ( $content_type == 'file' && !$is_remote_video ) {
        $file_size = Document::get_file_size($id);
        $metadata[] = $file_size;
    }
    
    $last_modified = get_the_modified_date('d/m/Y', $id);
    $metadata[] = __('Last modified', 'mv23theme') . ': ' . $last_modified;
    $title .= ' <span class="postcard__metadata text-xxs">- '. (implode(', ', $metadata)).'</span>';

    $thumb_url = Document::get_thumbnail($thumb_url, $ext, $file_url);
    $can_be_previewed = Document::can_be_previewed($ext);
}
?>
<div class="postcard postcard--style4" <?php echo $postcard_attributes ?>>
	<div class="postcard__content-wrapper">
	    <div class="postcard__content">
	    	<div class="postcard__postdata">
	    		<div class="postcard__terms">
	    			<p class="truncate">
	    				<?php if (is_array($main_terms) && count($main_terms) > 0) {
                	    	echo Post_Card::display_terms($main_terms,',');
            		    } else {
                            echo $posttype;
                        }
	    			    ?>
	    			</p>
	    		</div>

	    		<?php if( is_array($tags) && count($tags) > 0 ){
		        	echo '<div class="postcard__tags text-color-2">';
		        	echo Post_Card::display_terms($tags);
		        	echo '</div>';
		        } ?>
	    	</div>
                
            <div>
                <h2 class="postcard__title"><a class="trigger-post-action" href="<?=$link?>"><?php echo $title; ?></a></h2>
                <?php if($excerpt) echo '<div class="postcard__excerpt">'.$excerpt.'</div>'; ?>
            </div>
                
	    	<div class="postcard__footer">
                <?php if(TRACK_POSTS_DATA && $posttype == 'document'): ?>
                    <div class="postcard__actions">
                        <?php 
                        $actions = array('post_likes');
                        if( $can_be_previewed ) $actions[] = 'post_previsualizations';
                        if( !$is_remote_video ) $actions[] = 'post_downloads';
                        echo Post_Card::display_actions($post, $actions, $document_link);
                        ?>
                    </div>
                <?php else: ?>
                    <div>
                        <p class="postcard__date"><?php echo Post_Card::display_date($post); ?></p>
                    </div>
                <?php endif; ?>

	    		<div class="postcard__link"><a class="trigger-post-action" href="<?=$link?>"><?php _e('More details','mv23theme') ?> <i class="bi bi-arrow-up-right"></i></a></div>
	    	</div>
	    </div>

        <a href="<?=$link?>" class="trigger-post-action">
            <img class="postcard__image" src="<?=$thumb_url?>">
            <?php
                if($posttype == 'document') {
                    echo '<span class="viewer"><i class="bi '.$icon.'"></i></span>';
                } else {
                    echo '<span class="viewer"><i class="bi bi-eye"></i></span>';
                }
            ?>
        </a>
    </div>
</div>