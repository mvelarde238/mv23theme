<?php get_header(); 

$main_content_classes = array('main-content','container');
if(SINGLE_SIDEBAR) array_push($main_content_classes, SINGLE_MAIN_CONTENT_TEMPLATE);
?>

<div id="content">
	<?php
	$queried_object = get_queried_object();
	$posttype = $queried_object->post_type;
	if( in_array($posttype, PAGE_HEADER_IN) ) get_template_part('inc/modulos/page-header');
	?>
	<div class="<?php echo implode(' ',$main_content_classes) ?>">
		<main class="main">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <div class="page-module" style="padding-bottom: 0">
					<div class="componente single-post__title-wrapper">
						<?php 
                    	the_title('<h1 class="single-post__title">','</h1>'); 
                    	$id = get_the_ID();
                    	$date = get_the_time(get_option('date_format'), $id);
                    	$categories = wp_get_post_categories($id);

                    	echo '<p class="single-post__postdata">'.$date;
                    	if(is_array($categories) && count($categories) > 0){
                    	    echo ' | ';
                    	    $count = 0;
                    	    foreach ( $categories as $c ) {
                    	        $cat = get_category( $c );
                    	        echo '<a href="'.home_url('/category/'.$cat->slug).'">'.$cat->name.'</a>';
								$count++;
								if( $count < count($categories) ) echo ', ';
                    	    }
                    	}
                    	echo '</p>';
                    	?>
					</div>
					<?php  
					$tags = get_the_tags($id);
					if( is_array($tags) && count($tags) > 0 ){ ?>
						<div class="componente">
							<div class="single-post__tags">
								<?php foreach ($tags as $tag ) {
									$background_color = get_term_meta($tag->term_id, 'background_color', true);
									$style = ( $background_color ) ? ' style="background-color:'.$background_color.';"' : ' '; 
									echo '<span'.$style.'>#'.$tag->name.'</span>';
								} ?>
							</div>
						</div>
					<?php } ?>
				</div>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php the_content(); ?>
				</article>
				<?php if ( comments_open() || get_comments_number() ) : ?>
					<div class="page-module pdt0"><div class="componente">
						<?php comments_template(); ?>
					</div></div>
				<?php endif; ?>
			<?php endwhile; endif; ?>
			<?php get_template_part('inc/modulos/modals/controlador'); ?>
		</main>

		<?php if(SINGLE_SIDEBAR): ?>
        	<div class="sidebar">
				<div style="height:100%">
					<div class="pinned-block">
						<?php if (is_active_sidebar('page_sidebar')) : ?>
							<?php dynamic_sidebar('page_sidebar'); ?>
						<?php endif ?>
					</div>
				</div>
        	</div>
		<?php endif; ?>
	</div>
</div>

<?php get_footer(); ?>