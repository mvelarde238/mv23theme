<?php
use Core\Builder\Component\Social_Share;

class SocialShare {
    public function __construct() {
        add_shortcode('social_share', [$this, 'render_shortcode']);
    }

    /**
     * Renderizar el shortcode.
     * Delega toda la lógica al componente Social_Share.
     */
    public function render_shortcode($atts) {
        // Convertir atributos del shortcode a array asociativo
        $args = shortcode_atts([
            'networks' => '',
            'title' => __('Share this post:', 'mv23theme'),
            'limit' => 5,
            'more_text' => __('more', 'mv23theme'),
            'more_icon' => 'bi-three-dots',
            'alignment' => ''
        ], $atts);

        // Llamar al método display del componente
        return Social_Share::display($args);
    }
}

new SocialShare();