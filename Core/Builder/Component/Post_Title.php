<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;

class Post_Title extends Component {

    public function __construct() {
		parent::__construct(
			'post_title',
			__( 'Post Title', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'display_gjs_block' => false
		);
    }

    public static function get_fields() {
		$fields = array();
		return $fields;
	}

    public static function display($args){
        $post_id = isset($args['post_id']) ? $args['post_id'] : null;

        $post_title_posttypes = apply_filters('filter_post_title_posttypes', array(
            'post' => array( 'main_taxonomy' => 'category', 'tag_taxonomy' => 'post_tag' ),
            'portfolio' => array( 'main_taxonomy' => 'portfolio-cat', 'tag_taxonomy' => 'portfolio-tag' ),
            'product' => array( 'main_taxonomy' => 'product_cat', 'tag_taxonomy' => 'product_tag' ),
        ));

        ob_start();
        if($post_id) {
            echo '<div class="post-title component">';
                $post = get_post($post_id);
                $posttype = $post->post_type;

                echo '<div class="post-title__title-wrapper">';
                $title = get_the_title($post_id);
                echo '<h1 class="post-title__title">' . esc_html($title) . '</h1>';
                    
                $postdata = [];
                if($posttype == 'post'){
                    $date = get_the_time(get_option('date_format'), $post_id);
                    $postdata[] = '<span class="post-date">' . esc_html($date) . '</span>';
                } 

                $main_taxonomy = isset($post_title_posttypes[$posttype]) ? $post_title_posttypes[$posttype]['main_taxonomy'] : '';
                $categories = get_the_terms( $post_id, $main_taxonomy );
                if (is_array($categories) && count($categories) > 0) {
                    $count = 0;
                    $categories_list = [];
                    foreach ($categories as $c) {
                        $cat = get_category($c);
                        $categories_list[] = '<a href="' . esc_attr( get_tag_link( $cat->term_id ) ) . '">' . $cat->name . '</a>';
                    }
                    $postdata[] = implode(', ', $categories_list);
                }

                $postdata = apply_filters('filter_post_title_postdata', $postdata, $post_id);
                if (!empty($postdata)){
                    echo '<p class="post-title__postdata">'.implode(' | ', $postdata).'</p>';
                }
                echo '</div>';

                $tag_taxonomy = isset($post_title_posttypes[$posttype]) ? $post_title_posttypes[$posttype]['tag_taxonomy'] : '';
                $tags = get_the_terms( $post_id, $tag_taxonomy );
                if (is_array($tags) && count($tags) > 0) {
                    echo '<div class="post-title__tags dark-mode">';
                    foreach ($tags as $tag) {
                        $background_color = get_term_meta($tag->term_id, 'background_color', true);
                        $style = ($background_color) ? ' style="background-color:' . $background_color . ';"' : ' ';
                        echo '<span class="'.$tag->slug.'"><a href="' . esc_attr( get_tag_link( $tag->term_id ) ) . '">#' . __( $tag->name ) . '</a></span>';
                    }
                    echo '</div>';
                }
            echo '</div>';

        } else {
            echo '<p>' . __('No post selected', 'mv23theme') . '</p>';
        }
        return ob_get_clean();
    }
}

new Post_Title();