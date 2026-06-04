<?php
namespace Core\Builder\Component;

use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Ultimate_Fields\Field;
use Core\Theme_Options\Theme_Options;

class Social_Share extends Component {

    private static $social_networks;
    private static $limit = 5;

    public function __construct() {
		parent::__construct(
			'social-share',
			__( 'Social Share', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'posttypes' => array('single_template')
		);
    }

    public static function get_icon() {
        return 'bi-share';
    }

	public static function get_fields() {
		$fields = array(
            Field::create( 'text', 'title' )
                ->hide_label()
                ->set_prefix(__('Title: ','mv23theme'))
                ->add_suggestions(array(__('Share this post:','mv23theme'))),

            Field::create( 'image_select', 'style' )
                // ->hide_label()
                // ->show_label()
                ->set_default_value('style2')
                ->set_attr( 'class', 'image-select-2-cols' )->add_options(array(
                    'style1'  => array(
                        'label' => 'Style 1',
                        'image' => BUILDER_PATH.'/assets/images/social-share/style1.png'
                    ),
                    'style2'  => array(
                        'label' => 'Style 2',
                        'image' => BUILDER_PATH.'/assets/images/social-share/style2.png'
                    ),
                    'style3'  => array(
                        'label' => 'Style 3',
                        'image' => BUILDER_PATH.'/assets/images/social-share/style3.png'
                    )
                )),

            Field::create( 'icon', 'more_icon', __('More Icon', 'mv23theme') )
                ->add_set( 'bootstrap-icons' )
                ->add_set( 'font-awesome' )
                ->set_default_value( 'bi-three-dots' ),
        );
		return $fields;
	}

    /**
     * Obtener las redes sociales disponibles
     */
    private static function get_social_networks() {
        if (self::$social_networks === null) {
            self::$social_networks = [
                'facebook' => ['icon' => 'bi-facebook'],
                'twitter'  => ['icon' => 'bi-twitter-x'],
                'linkedin' => ['icon' => 'bi-linkedin'],
                'email'    => ['icon' => 'bi-envelope'],
                'whatsapp' => ['icon' => 'bi-whatsapp'],
                'threads' => ['icon' => 'bi-threads'],
                'telegram' => ['icon' => 'bi-telegram'],
                'viber' => ['icon' => 'bi-share'],
                'pinterest' => ['icon' => 'bi-pinterest'],
                'tumblr' => ['icon' => 'bi-share'],
                'hackernews' => ['icon' => 'bi-share'],
                'reddit' => ['icon' => 'bi-reddit'],
                'vk.com' => ['icon' => 'bi-share'],
                'buffer' => ['icon' => 'bi-share'],
                'xing' => ['icon' => 'bi-share'],
                'line' => ['icon' => 'bi-line'],
                'instapaper' => ['icon' => 'bi-share'],
                'pocket' => ['icon' => 'bi-share'],
                'flipboard' => ['icon' => 'bi-share'],
                'weibo' => ['icon' => 'bi-sina-weibo'],
                'blogger' => ['icon' => 'bi-share'],
                'baidu' => ['icon' => 'bi-share'],
                'ok.ru' => ['icon' => 'bi-share'],
                'evernote' => ['icon' => 'bi-share'],
                'skype' => ['icon' => 'bi-skype'],
                'trello' => ['icon' => 'bi-trello'],
                'diaspora' => ['icon' => 'bi-share']
            ];
        }
        return self::$social_networks;
    }

    /**
     * Generar los botones sociales.
     */
    private static function generate_buttons($selected_networks, $starts_at = 0, $limit = 5) {
        $buttons = '';
        $count = 0;

        $page_title = get_the_title();
        $current_url = get_permalink();
        $social_networks = self::get_social_networks();

        foreach ($selected_networks as $network) {
            if ($count < $starts_at) {
                $count++;
                continue;
            }
            if ($count >= $starts_at + $limit) {
                break;
            }

            $network = trim($network);
            if (isset($social_networks[$network])) {
                $icon = $social_networks[$network]['icon'];
                $buttons .= sprintf(
                    '<button data-title="%s" data-url="%s" data-sharer="%s">
                        <span><i class="bi %s"></i></span> <span>%s</span>
                    </button>',
                    esc_attr($page_title),
                    esc_url($current_url),
                    esc_attr($network),
                    esc_attr($icon),
                    esc_html($network)
                );
            }
            $count++;
        }

        return $buttons;
    }

    /**
     * Renderizar el componente
     */
    public static function display($args = array()) {
        // Atributos del shortcode
        $defaults = array(
            'networks' => '',
            'title' => __('Share this post:', 'mv23theme'),
            'limit' => self::$limit,
            'more_text' => __('more', 'mv23theme'),
            'more_icon' => 'bi-three-dots',
            'alignment' => '',
            'style' => 'style2'
        );
        
        $atts = wp_parse_args($args, $defaults);
       
        // Obtener redes sociales seleccionadas o usar todas por defecto
        $social_networks = self::get_social_networks();
        $selected_networks = $atts['networks'] ? explode(',', $atts['networks']) : array_keys($social_networks);

        $output = Template_Engine::component_wrapper('start', $args);
        if($atts['title']) {
            $output .= '<h6>'.esc_html($atts['title']).'</h6>';
        }
        $limit = $atts['limit'];
        $output .= '<div class="social-share-buttons" data-style="'.esc_attr($atts['style']).'"';
        
        // buttons alignment
        if($atts['alignment']) {
            $output .= ' style="justify-content:'.esc_attr($atts['alignment']).'"';
        }
        
        $output .= '>';
        $output .= self::generate_buttons($selected_networks, 0, $limit);
        
        if( count($selected_networks) > $limit ) {
            $output .= '<button class="modal-trigger" data-target="more-social-share-modal"><span><i class="bi '.$atts['more_icon'].'"></i></span> <span>'.$atts['more_text'].'</span></button>';
        }
        
        $output .= '</div>';
        $output .= Template_Engine::component_wrapper('end', $args);

        // Modal to show more social networks
        $output .= '<div id="more-social-share-modal" class="modal bottom-sheet theme-modal text-xs"><div class="modal-content">';
        $output .= '<div class="container">';
        if($atts['title']) {
            $output .= '<h6>'.esc_html($atts['title']).'</h6>';
        }
        $output .= '<div class="social-share-buttons" data-style="style2">';
        $output .= self::generate_buttons($selected_networks, $limit, 999);
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';

        return $output;
    }
}

new Social_Share();