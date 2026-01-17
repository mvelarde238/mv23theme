<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;

class Single_Page_Header extends Component {

    public function __construct() {
		parent::__construct(
			'single_page_header',
			__( 'Single_Page_Header', 'mv23theme' )
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
        // $post_id = isset($args['post_id']) ? $args['post_id'] : null;
        $post_id = 55; // For testing purposes
        ob_start();
        if($post_id) { ?>
            <div class="page-module">
                <div class="component single-post__title-wrapper">
                    <?php
                    $post = get_post($post_id);
                    $posttype = get_post_type($post);

                    $category_name = ($posttype == 'post') ? 'category' : 'portfolio-cat';
                    $categories = get_the_terms( $post_id, $category_name );

                    $title = get_the_title($post_id);
                    echo '<h1 class="single-post__title">' . esc_html($title) . '</h1>';
                    echo '<p class="single-post__postdata">';
                    if($posttype == 'post'){
                        $date = get_the_time(get_option('date_format'), $post_id);
                        echo $date;
                    } 
                    if (is_array($categories) && count($categories) > 0) {
                        if($posttype == 'post') echo ' | ';
                        $count = 0;
                        foreach ($categories as $c) {
                            $cat = get_category($c);
                            echo '<a href="' . esc_attr( get_tag_link( $cat->term_id ) ) . '">' . $cat->name . '</a>';
                            $count++;
                            if ($count < count($categories)) echo ', ';
                        }
                    }
                    echo '</p>';
                    ?>
                </div>
                <?php
                $tag_name = ($posttype == 'post') ? 'post_tag' : 'portfolio-tag';
                $tags = get_the_terms( $post_id, $tag_name );
                if (is_array($tags) && count($tags) > 0) { ?>
                    <div class="component">
                        <div class="single-post__tags dark-mode">
                            <?php foreach ($tags as $tag) {
                                $background_color = get_term_meta($tag->term_id, 'background_color', true);
                                $style = ($background_color) ? ' style="background-color:' . $background_color . ';"' : ' ';
                                echo '<span class="'.$tag->slug.'"><a href="' . esc_attr( get_tag_link( $tag->term_id ) ) . '">#' . __( $tag->name ) . '</a></span>';
                            } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <?php
        } else {
            echo '<p>' . __('No post selected', 'mv23theme') . '</p>';
        }
        return ob_get_clean();
    }
}

new Single_Page_Header();