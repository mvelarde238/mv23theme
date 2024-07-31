<?php
namespace Theme_Custom_Fields\Component;

use Ultimate_Fields\Field;
use Theme_Custom_Fields\Component;
use Theme_Custom_Fields\Template_Engine;

class Testimonials extends Component {

    public function __construct() {
		parent::__construct(
			'Testimonios',
			__( 'Testimonials', 'default' )
		);
	}

    public static function get_icon() {
        return 'dashicons-testimonial';
    }

    public static function get_title_template() {
		$template = '<% if ( testimonios != "" && testimonios.length ){ %>
            Show <%= testimonios.length %> testimonials in <%= cols_in_desktop %> columns
        <% } else { %>
            This component is empty
        <% } %>';
		
		return $template;
	}

	public static function get_fields() {
		$fields = array( 
            Field::create( 'tab', 'Contenido' ),
            Field::create( 'repeater', 'testimonios' )->set_add_text('Agregar')->add_group( 'testimonio', array(
                'edit_mode' => 'popup',
                'title_template' => '<%= author.replace(/<[^>]+>/ig, "") %> | <%= comment.replace(/<[^>]+>/ig, "") %>',
                'fields' => array(
                    Field::create( 'radio', 'type', 'Tipo de testimonio:')->set_orientation('horizontal')->add_options(array(
                        'text' => 'Texto', 'video' => 'Video'
                    )),
                    Field::create( 'image', 'author_img' )->add_dependency('type', 'text', '=')->set_width(25),
                    Field::create( 'wysiwyg', 'author' )->add_dependency('type', 'text', '=')->set_width(70),
                    Field::create( 'wysiwyg', 'comment' )->add_dependency('type', 'text', '='),
                    Field::create( 'video', 'video' )->add_dependency('type', 'video', '=')->set_width(25),
                )
            )),
            Field::create( 'tab', 'Columnas' ),
            Field::create( 'number', 'cols_in_desktop', 'Columnas en desktop' )->set_default_value( '4' )->set_width( 25 )->set_minimum(1)->set_maximum(4),
            Field::create( 'number', 'cols_in_tablet', 'Columnas en tablet' )->set_default_value( '2' )->set_width( 25 )->set_minimum(1)->set_maximum(4),
            Field::create( 'number', 'cols_in_mobile', 'Columnas en móviles' )->set_default_value( '1' )->set_width( 25 )->set_minimum(1)->set_maximum(4),
        );

		return $fields;
	}

    // public static function display_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx( $args ){
    public static function display( $args ){
		$args['additional_classes'] = array('carrusel');
		$args['additional_attributes'] = array('data-controls-position="center"');
        
        $testimonios = $args['testimonios'];
        $items_in_desktop = $args['cols_in_desktop'];
        $items_in_tablet = $args['cols_in_tablet'];
        $items_in_mobile = $args['cols_in_mobile'];
        $comment_length = 110;

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
        ?>
        <div class="testimonios-list carrusel__slider" 
            data-show-controls="1" 
            data-show-nav="1" 
            data-autoplay="0" 
            data-nav-position="bottom"
            data-mobile="<?=$items_in_mobile?>"
            data-tablet="<?=$items_in_tablet?>"
            data-laptop="<?=$items_in_desktop?>"
            data-desktop="<?=$items_in_desktop?>">
        <?php foreach($testimonios as $testimonio) : 
            $rand_id = 'id'.substr(md5(microtime()),rand(0,26),5);
            $type = $testimonio['type'];

            if($type == 'text'){
                $author = $testimonio['author'];
                $author_img = ( $testimonio['author_img'] ) ? wp_get_attachment_url($testimonio['author_img']) : null;
                $comment = $testimonio['comment'];
            }

            $has_video_background_class = ($type == 'video') ? 'has-video-background' : '';
            ?>  
            <div>
                <div id="<?=$rand_id?>" class="testimonio theme-border <?=$has_video_background_class?>">
                    <?php if( $type == 'text' ): ?>
                        <?php if( $author ): ?>
                            <div class="testimonio__header">
                                <?php 
                                echo '<div class="testimonio__author"';
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
                                $string .= '... <a class="testimonio__open" href="!#" data-id="'.$rand_id.'"><b>Mostrar más</b></a>';
                            }
                            ?>
                            <div class="testimonio__body">
                                <div class="testimonio__excerpt">
                                    <?php echo $string ?>
                                </div>
                                <div class="testimonio__comment">
                                    <?php echo '<div>'.do_shortcode(wpautop(oembed($comment))).'</div>'; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if( $type == 'video' ):
                        $videos = ( isset($testimonio['video']) ) ? $testimonio['video'] : array();
                        $video_id = (isset($videos['videos']) &&  is_array($videos['videos']) && count($videos['videos'])) ? $videos['videos'][0] : null;
                        if($video_id) {
                            $video_url = wp_get_attachment_url($video_id);
                            if($video_url){
                                echo '<video width="100%" loop muted="muted" autoplay><source src="'.$video_url.'">Your browser does not support the video tag.</video>';
                                echo '<a class="cover-all zoom-video" href="'.$video_url.'"></a>';
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
}

new Testimonials();