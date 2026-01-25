<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Testimonials extends Component {

    public function __construct() {
		parent::__construct(
			'testimonials',
			__( 'Testimonials', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'dashicons-testimonial';
    }

	public static function get_fields() {
        $wysiwyg_styles = 'body#tinymce.wp-editor{font-size: var(--text-xs);}';
        $width_style = 'width: 25%; min-width: initial;';

		$fields = array( 
            Field::create( 'tab', __('Content','mv23theme') ),
            Field::create( 'repeater', 'testimonials' )->hide_label()->set_add_text(__('Add Testimonial','mv23theme'))->add_group( 'testimonial', array(
                'edit_mode' => 'popup',
                'title_template' => '<% if (type == "text"){ %>
                        <%= author.replace(/<[^>]+>/ig, "") %> | <%= comment.replace(/<[^>]+>/ig, "") %>
                    <% } else { %>
                        <%= Video %>
                    <% } %>',
                'fields' => array(
                    Field::create( 'radio', 'type', __('Type of testimonial:', 'mv23theme'))->set_orientation('horizontal')->add_options(array(
                        'text' => __('Text', 'mv23theme'), 'video' => __('Video', 'mv23theme')
                    )),
                    Field::create( 'image', 'author_img' )->add_dependency('type', 'text', '=')->set_width(25),
                    Field::create( 'wysiwyg', 'author' )->add_dependency('type', 'text', '=')->set_content_style($wysiwyg_styles)->set_width(70),
                    Field::create( 'wysiwyg', 'comment' )->add_dependency('type', 'text', '=')->set_content_style($wysiwyg_styles),
                    Field::create( 'video', 'video' )->add_dependency('type', 'video', '=')->set_width(25),
                )
            )),
            Field::create( 'tab', __('Columns', 'mv23theme') ),
            Field::create( 'complex', 'items', __('Columns', 'mv23theme') )->add_fields(array(
                Field::create( 'number', 'desktop', __('Desktop', 'mv23theme') )->set_default_value( '4' )->set_width( 25 )->set_minimum(1)->set_maximum(4)->set_attr('style', $width_style),
                Field::create( 'number', 'laptop', __('Laptop', 'mv23theme') )->set_default_value( '4' )->set_width( 25 )->set_minimum(1)->set_maximum(4)->set_attr('style', $width_style),
                Field::create( 'number', 'tablet', __('Tablet', 'mv23theme') )->set_default_value( '2' )->set_width( 25 )->set_minimum(1)->set_maximum(4)->set_attr('style', $width_style),
                Field::create( 'number', 'mobile', __('Mobile', 'mv23theme') )->set_default_value( '2' )->set_width( 25 )->set_minimum(1)->set_maximum(4)->set_attr('style', $width_style)
            ))
        );

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
		$args['additional_classes'][] = 'carousel';
		$args['additional_attributes'] = array('data-controls-position="center"');
        
        $testimonials = $args['testimonials'];
        $items_in_desktop = $args['items']['desktop'];
        $items_in_laptop = $args['items']['laptop'];
        $items_in_tablet = $args['items']['tablet'];
        $items_in_mobile = $args['items']['mobile'];
        $comment_length = 110;

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
        ?>
        <div class="testimonials-list carousel__slider" 
            data-show-controls="1" 
            data-show-nav="1" 
            data-autoplay="0" 
            data-speed="450"
            data-nav-position="bottom"
            data-mobile="<?=$items_in_mobile?>"
            data-tablet="<?=$items_in_tablet?>"
            data-laptop="<?=$items_in_laptop?>"
            data-desktop="<?=$items_in_desktop?>">
        <?php foreach($testimonials as $testimonial) : 
            $rand_id = 'id'.substr(md5(microtime()),rand(0,26),5);
            $type = $testimonial['type'];

            if($type == 'text'){
                $author = $testimonial['author'];
                $author_img = ( $testimonial['author_img'] ) ? wp_get_attachment_url($testimonial['author_img']) : null;
                $comment = $testimonial['comment'];
            }
            ?>  
            <div>
                <div id="<?=$rand_id?>" class="testimonial theme-border">
                    <?php if( $type == 'text' ): ?>
                        <?php if( $author ): ?>
                            <div class="testimonial__header">
                                <?php 
                                echo '<div class="testimonial__author"';
                                if($author_img) echo ' style="background-image: url('.$author_img.')"';
                                echo '></div>'; 
                                ?>
                                <?php if($author) echo '<div>'.do_shortcode(wpautop(oembed($author))).'</div>'; ?>
                            </div>
                        <?php endif; ?> 
                        <?php if($comment): 
                            $string = strip_tags($comment);
                            if (strlen($string) > $comment_length) {
                            
                                // truncate string
                                $stringCut = substr($string, 0, $comment_length);
                                $endPoint = strrpos($stringCut, ' ');
                            
                                $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                $string .= '... <a class="testimonial__open" href="!#" data-id="'.$rand_id.'"><b>Mostrar más</b></a>';
                            }
                            ?>
                            <div class="testimonial__body">
                                <div class="testimonial__excerpt">
                                    <?php echo $string ?>
                                </div>
                                <div class="testimonial__comment">
                                    <?php echo '<div>'.do_shortcode(wpautop(oembed($comment))).'</div>'; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if( $type == 'video' ):
                        $videos = ( isset($testimonial['video']) ) ? $testimonial['video'] : array();
                        $video_id = (isset($videos['videos']) &&  is_array($videos['videos']) && count($videos['videos'])) ? $videos['videos'][0] : null;
                        if($video_id) {
                            $video_url = wp_get_attachment_url($video_id);
                            if($video_url){
                                echo '<video width="100%" class="video-background" loop muted="muted" autoplay><source src="'.$video_url.'">Your browser does not support the video tag.</video>';
                                echo '<a data-fancybox class="cover-all zoom-video" href="'.$video_url.'"></a>';
                            }
                        }
                    endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
        <?php
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}

    public static function get_view_template(){
        return '<% 
        listing_cls = ["testimonials-list", "has-columns"]
        listing_style = ["min-height: 100px", "flex-wrap:nowrap", "overflow-x:scroll"]
        post_style = ["flex-shrink:0"]

        devices = ["d","l","t","m"]
        d = ["desktop","laptop","tablet","mobile"]
        devices.forEach(function(key,index){ 
            columns = items[d[index]]
            gap = "15px"
            listing_style.push("--"+key+"-columns:"+columns+"; --"+key+"-gap:"+gap)
        })
        %>
        <div class="<%= listing_cls.join(" ") %>" style="<%= listing_style.join(";") %>">
            <% for( var i = 0; i < testimonials.length; i++){ 
                testimonial = testimonials[i];
                %>
                <div class="testimonial" style="<%= post_style.join(";") %>">
                    <% if( testimonial.type == "text" ){ %>
                        <div class="testimonial__header">
                            <% if( Array.isArray(testimonial.author_img_prepared) && testimonial.author_img_prepared.length > 0 ){ %>
                                <div class="testimonial__author" style="background-image: url(<%= testimonial.author_img_prepared[0].url %>)"></div>
                            <% } else { %>
                                <div class="testimonial__author"></div>
                            <% } %>
                            <div><%= testimonial.author %></div>
                        </div>
                        <div class="testimonial__body">
                            <div class="testimonial__excerpt"><%= testimonial.comment %></div>
                        </div>
                    <% } else if( testimonial.type == "video" ){ %>
                        <% 
                        video_data = testimonial.video;
                        video_id = ( video_data && Array.isArray(video_data.videos) && video_data.videos.length > 0 ) ? video_data.videos[0] : null
                        if( video_id && testimonial.video_prepared && testimonial.video_prepared[0].url ){ 
                            %>
                            <video width="100%" class="video-background" loop muted="muted" autoplay>
                                <source src="<%= testimonial.video_prepared[0].url %>">
                                Your browser does not support the video tag.
                            </video>
                            <a data-fancybox class="cover-all zoom-video" href="<%= testimonial.video_prepared[0].url %>"></a>
                        <% } %>
                    <% } %>
                </div>
            <% } %>
        </div>';
    }
}

new Testimonials();