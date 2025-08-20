<?php
use Core\Frontend\Post_Card;

global $post;

$postcard_args = array(
    'id' => $post->ID,
    'posttype' => $post->post_type,
    'title' => $post->post_title,
    'metadata' => array(),
    'permalink' => Post_Card::get_permalink($post),
    'permalink_text' => __('More details','mv23theme'),
    'permalink_icon' => 'bi-arrow-up-right',
    'permalink_class' => 'trigger-post-action',
    'excerpt' => Post_Card::get_excerpt($post, 110),
    'thumbnail' => Post_Card::get_thumbnail($post, 'full'),
    'attributes' => Post_Card::build_attributes($post, $args),
    'main_terms' => Post_Card::get_main_taxonomy_terms($post),
    'tags' => Post_Card::get_secondary_taxonomy_terms($post),
    'date' => '<div><p class="postcard__date">'.Post_Card::display_date($post).'</p></div>',
    'viewer_icon' => 'bi-eye',
    'style' => 'style4'
);

$_args = apply_filters( 'filter_postcard', $postcard_args, $post, $args );
?>
<div class="postcard postcard--style4" <?php echo $_args['attributes'] ?>>
	<div class="postcard__content-wrapper">
	    <div class="postcard__content">
	    	<div class="postcard__postdata">
	    		<div class="postcard__terms">
	    			<p class="truncate">
	    				<?php if (is_array($_args['main_terms']) && count($_args['main_terms']) > 0) {
                	    	echo Post_Card::display_terms($_args['main_terms'],',');
            		    } else {
                            echo $_args['posttype'];
                        }
	    			    ?>
	    			</p>
	    		</div>

	    		<?php if( is_array($_args['tags']) && count($_args['tags']) > 0 ){
		        	echo '<div class="postcard__tags text-color-2">';
		        	echo Post_Card::display_terms($_args['tags']);
		        	echo '</div>';
		        } ?>
	    	</div>
                
            <div>
                <h2 class="postcard__title">
                    <a class="<?=$_args['permalink_class']?>" href="<?=$_args['permalink']?>"><?php echo $_args['title']; ?></a>
                </h2>
                <?php if($_args['excerpt']) echo '<div class="postcard__excerpt">'.$_args['excerpt'].'</div>'; ?>
            </div>
                
	    	<div class="postcard__footer">
                <?php echo $_args['date']; ?>

	    		<div class="postcard__link">
                    <a class="<?=$_args['permalink_class']?>" href="<?=$_args['permalink']?>">
                        <?php if( $_args['permalink_text'] ) echo $_args['permalink_text']; ?>
                        <?php if( $_args['permalink_icon'] ) echo ' <i class="bi '.$_args['permalink_icon'].'"></i>'; ?>
                    </a>
                </div>
	    	</div>
	    </div>

        <a href="<?=$_args['permalink']?>" class="<?=$_args['permalink_class']?>">
            <img class="postcard__image" src="<?=$_args['thumbnail']?>">
            <?php if($_args['viewer_icon']) echo '<span class="viewer"><i class="bi '.$_args['viewer_icon'].'"></i></span>'; ?>
        </a>
    </div>
</div>