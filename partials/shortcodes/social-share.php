<?php
class SocialShare {
    private $social_networks;
    private $limit;

    public function __construct() {
        $this->limit = 5;
        
        $this->social_networks = [
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

        add_shortcode('social_share', [$this, 'render_shortcode']);
    }

    /**
     * Generar los botones sociales.
     */
    public function generate_buttons($selected_networks, $starts_at = 0, $limit = 5) {
        $buttons = '';
        $count = 0;

        $page_title = get_the_title();
        $current_url = get_permalink();

        foreach ($selected_networks as $network) {
            if ($count < $starts_at) {
                $count++;
                continue;
            }
            if ($count >= $starts_at + $limit) {
                break;
            }

            $network = trim($network);
            if (isset($this->social_networks[$network])) {
                $icon = $this->social_networks[$network]['icon'];
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
     * Renderizar el shortcode.
     */
    public function render_shortcode($atts) {
        // Atributos del shortcode
        $atts = shortcode_atts([
            'networks' => '',
            'title' => __('Share this post:', 'mv23theme'),
            'limit' => $this->limit,
            'more_text' => __('more', 'mv23theme'),
            'more_icon' => 'bi-three-dots',
            'alignment' => ''
        ], $atts);

        // Obtener redes sociales seleccionadas o usar todas por defecto
        $selected_networks = $atts['networks'] ? explode(',', $atts['networks']) : array_keys($this->social_networks);

        $output = '<div class="social-share text-xs">';
        $output .= '<h6>'.esc_html($atts['title']).'</h6>';
        $limit = $atts['limit'];
        $output .= '<div class="social-share-buttons-wrapper">';
        $output .= '<div class="social-share-buttons"';
        // buttons alignment
        if($atts['alignment']) $output .= ' style="justify-content:'.esc_attr($atts['alignment']).'"';
        $output .= '>';
        $output .= $this->generate_buttons($selected_networks, 0, $limit);
        if( count($selected_networks) > $limit ) $output .= '<button class="modal-trigger" data-target="more-social-share-modal"><span><i class="bi '.$atts['more_icon'].'"></i></span> <span>'.$atts['more_text'].'</span></button>';
        $output .= '</div>';
        $output .= '</div>';

        $output .= '<div id="more-social-share-modal" class="modal bottom-sheet theme-modal text-xs"><div class="modal-content">';
        $output .= '<div class="container">';
        $output .= '<h6>'.esc_html($atts['title']).'</h6>';
        $output .= '<div class="social-share-buttons-wrapper">';
        $output .= '<div class="social-share-buttons">';
        $output .= $this->generate_buttons($selected_networks, $limit, 999);
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div></div>';

        return $output;
    }
}

new SocialShare();