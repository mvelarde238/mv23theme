<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Frontend\Page;
use Core\Posttype\Reusable_Section_CPT;

class Accordion_Button extends Component {

    public function __construct() {
		parent::__construct(
			'togglebox-button',
			__( 'Accordion Button', 'mv23theme' )
		);
	}

    public static function get_builder_data() {
        return array(
            'display_gjs_block' => false
		);
    }

	public static function get_fields() {
        $fields = array(
            Field::create( 'text', 'title' )
                ->set_attr( 'style', 'background-color: #f0f0f0;' ),
            Field::create( 'text', 'subtitle' ),
            Field::create( 'complex', 'icon_settings', __('Element before the title','mv23theme'))->add_fields(array(
                Field::create( 'radio', 'type' )->set_orientation( 'horizontal' )->hide_label()->add_options( array(
                    '' => __('None','mv23theme'),
                    'icon' => __('Icon','mv23theme'),
                    'image' => __('Image','mv23theme'),
                )),
                Field::create( 'icon', 'icon', __('Icon','mv23theme') )
                    ->hide_label()
                    ->add_set( 'bootstrap-icons' )
                    ->add_set( 'font-awesome' )
                    ->add_dependency('type','icon','='),
                Field::create( 'image', 'image', __('Image','mv23theme') )
                    ->hide_label()
                    ->add_dependency('type','image','='),
                Field::create( 'select', 'image_size' )
                    ->hide_label()
                    ->add_dependency('type','image','=')
                    ->set_prefix(__('Image Size','mv23theme'))
                    ->add_options(array(
                        'iconsize' => __('Small','mv23theme'),
                        'auto' => __('Automatic','mv23theme')
                ))
            )),
            Field::create( 'text', 'itemid', 'ID' )
        );

		return $fields;
	}

	public static function display( $args ){
        $title = '<span class="v23-togglebox__title">'.$args['title'].'</span>';
        $subtitle = (isset($args['subtitle']) && $args['subtitle']) ? '<span class="v23-togglebox__subtitle">'.$args['subtitle'].'</span>' : '';
        $argsid = (isset($args['itemid'])) ? $args['itemid'] : false;
        $slug = ($argsid) ? $argsid : sanitize_title($title);
        if( preg_match('@^[0-9]@',$slug) ) $slug = 'item-'.$slug;

        $icon_settings = $args['icon_settings'];
        $type = $icon_settings['type'] ?? '';

        switch ($type) {
            case 'image':
                $image = (is_numeric($icon_settings['image'])) ? wp_get_attachment_url($icon_settings['image']) : $icon_settings['image'];
                $style = (isset($icon_settings['image_size']) && $icon_settings['image_size'] == 'auto') ? 'style="height:auto;width:auto;"' : '';
                $icon_html = ($image) ? '<img '.$style.' src="'.$image .'" />' : '';
                break;
            
            case 'icon':
                $icon = $icon_settings['icon'];
                $icon_prefix = (str_starts_with($icon,'fa')) ? 'fa' : 'bi';
                $icon_html = ($icon) ? '<i class="'.$icon_prefix.' '.$icon.'"></i>' : '';
                break;

            default:
                $icon_html = '';
                break;
        };

        $count = $args['count'] ?? 0;
		ob_start();
        echo '<div class="v23-togglebox__btn" data-boxid="#'.$slug.'" data-count="'.$count.'">'.$icon_html.$title.$subtitle.'</div>';
		return ob_get_clean();
	}
}

new Accordion_Button();