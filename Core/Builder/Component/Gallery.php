<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Gallery extends Component {

    public function __construct() {
		parent::__construct(
			'gallery',
			__( 'Gallery', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'dashicons-images-alt2';
    }

    public static function get_title_template() {
		$template = '<% if ( source == "manual" && gallery != "" || source == "wp-media" && wp_media_folder != 0 ){ %>
            <%= source %>
            <%= ( source == "wp-media" ) ? " | Folder ID: "+ wp_media_folder : ""  %>
            <%= ( source == "manual" ) ? " | Media IDs: "+ gallery : ""  %>
        <% } else { %>
            This component is empty
        <% } %>';
		
		return $template;
	}

	public static function get_fields() {
		$fields = array();

        $sources = array(
            'manual' => __('Select Images', 'mv23theme'),
        );
        if(WPMEDIAFOLDER_IS_ACTIVE) $sources = array_merge( array('wp-media' => __('Select Folder', 'mv23theme')), $sources );
        
        // basic fields
        $fields[] = Field::create( 'tab', __('Content','mv23theme') );
        $fields[] = Field::create( 'radio', 'source', __('Source', 'mv23theme'))->set_orientation('horizontal')->add_options( $sources )->set_width(100);
        $fields[] = Field::create( 'gallery', 'gallery' )->add_dependency('source', 'manual', '=')->set_width(100);
        
        if(WPMEDIAFOLDER_IS_ACTIVE) {
            // wp media fields
            $fields[] = Field::create( 'select', 'wp_media_folder' )->add_terms( 'wpmf-category' )->fancy()->set_width(25)->add_dependency('source', 'wp-media', '=');
            $fields[] = Field::create( 'message', 'wp_media_folder_message', __('WP Media Folder', 'mv23theme') )->set_description('<a href="'.admin_url().'upload.php" target="_blank">'.__('Create a new WP Media Folder', 'mv23theme').'</a>')->add_dependency('source', 'wp-media', '=')->set_width(70);
        }
        
        // gallery settings
        $fields[] = Field::create( 'tab', __('Gallery Settings', 'mv23theme') );

        // Field::create( 'checkbox', 'autoinsert' )->set_text( '¿Autoinsertar las imágenes agregadas a la galerîa?' ); // the shortcode needs the attachments id's

        $fields[] = Field::create( 'image_select', 'display', __('Gallery type','mv23theme') )
            ->show_label()
            ->add_options(array(
                'default' => array(
                    'label' => 'default',
                    'image' => BUILDER_PATH.'/assets/images/galleries/default.png'
                ),
                'slider' => array(
                    'label' => 'slider',
                    'image' => BUILDER_PATH.'/assets/images/galleries/slider.png'
                ),
                'marquee' => array(
                    'label' => 'marquee',
                    'image' => BUILDER_PATH.'/assets/images/galleries/marquee.png'
                ),
                'masonry' => array(
                    'label' => 'masonry',
                    'image' => BUILDER_PATH.'/assets/images/galleries/masonry.png'
                )
                // 'porfolio' => array(
                //     'label' => 'portfolio',
                //     'image' => BUILDER_PATH.'/assets/images/galleries/portfolio.png'
                // ),
            ));
        if( !MASONRY_IS_ACTIVE ){
            $fields[] = Field::create( 'message', 'masonry_message', __('Activate Masonry','mv23theme') )->set_description('You need to activate masonry gallery to use this feature: <a href="'.admin_url().'admin.php?page=theme-options#global_options" target="_blank">Activate Masonry Gallery</a>')->add_dependency('display', 'masonry', '=')->set_attr( 'style', 'background:#ffe8e8;width:100%;' );
        }
        if( !SCROLL_ANIMATIONS ){
            $fields[] = Field::create( 'message', 'marquee_message', __('Activate GSAP Animations','mv23theme') )->set_description('You need to activate GSAP animations to use this feature: <a href="'.admin_url().'admin.php?page=theme-options#global_options" target="_blank">Activate GSAP Animations</a>')->add_dependency('display', 'marquee', '=')->set_attr( 'style', 'background:#ffe8e8;width:100%;' );
        }

        $fields[] = Field::create( 'complex', 'marquee_settings', __('Marquee Settings', 'mv23theme') )->merge()->add_fields(array(
            Field::create( 'number', 'marquee_speed', __('Marquee Animation Speed', 'mv23theme') )
                ->set_default_value(18)
                ->set_suffix(__('Seconds', 'mv23theme'))
                ->set_attr( 'style', 'flex-grow: initial;' ),
            Field::create( 'color', 'fade_color', __('Fade Color', 'mv23theme') )
                ->set_default_value('#ffffff')
                ->set_attr( 'style', 'flex-grow: initial;' )
        ))->add_dependency('display', 'marquee', '=');

        // Field::create( 'select', 'orderby', 'Ordenar por')->add_options( array(
        //     'custom' => 'Personalizado',
        //     'rand' => 'Random',
        //     'title' => 'Tìtulo',
        //     'date' => 'Fecha'
        // ))->add_dependency('../wp_media_folder','0','!=');
        // Field::create( 'select', 'order', 'Orden')->add_options( array(
        //     'DESC' => 'Descendente',
        //     'ASC' => 'Ascendente',
        // ));

        // columns and gutter settings
        $columns_suggestions = array( '4', '3', '2', '1', 'auto' );
        $fields[] = Field::create( 'complex', '_items_wrapper', __('Columns', 'mv23theme') )->merge()->add_fields(array(
            Field::create( 'text', 'items_in_desktop', __('Columns on desktop', 'mv23theme') )->set_default_value( '4' )->set_width( 25 )->add_suggestions( $columns_suggestions ),
            Field::create( 'text', 'items_in_laptop', __('Columns on laptop', 'mv23theme') )->set_default_value( '3' )->set_width( 25 )->add_suggestions( $columns_suggestions ),
            Field::create( 'text', 'items_in_tablet', __('Columns on tablet', 'mv23theme') )->set_default_value( '2' )->set_width( 25 )->add_suggestions( $columns_suggestions ),
            Field::create( 'text', 'items_in_mobile', __('Columns on mobile', 'mv23theme') )->set_default_value( '2' )->set_width( 25 )->add_suggestions( $columns_suggestions )
        ))->add_dependency('display', 'marquee', '!=');

        $fields[] = Field::create( 'complex', '_gutter_wrapper', __('Space between items', 'mv23theme') )->merge()->add_fields(array(
            Field::create( 'number', 'gutter_in_desktop', __('Gap on desktop', 'mv23theme') )->set_default_value( '4' )->set_width( 25 ),
            Field::create( 'number', 'gutter_in_laptop', __('Gap on laptop', 'mv23theme') )->set_default_value( '4' )->set_width( 25 ),
            Field::create( 'number', 'gutter_in_tablet', __('Gap on tablet', 'mv23theme') )->set_default_value( '4' )->set_width( 25 ),
            Field::create( 'number', 'gutter_in_mobile', __('Gap on mobile', 'mv23theme') )->set_default_value( '4' )->set_width( 25 )
        ));
        
        // images settings
        $fields[] = Field::create( 'tab', __('Images Settings', 'mv23theme') );
        $fields[] = Field::create( 'image_select', 'aspect_ratio', __('Aspect ratio','mv23theme') )
            ->set_description(__('Appearance of the images in the gallery. If you select "default" the images will keep their original aspect ratio.', 'mv23theme'))
            ->add_options(array(
                '1/1'  => array(
                    'label' => '1:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-1-1.png'
                ),
                '4/3'  => array(
                    'label' => '4:3',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-4-3.png'
                ),
                '16/9'  => array(
                    'label' => '16:9',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-16-9.png'
                ),
                '2/1'  => array(
                    'label' => '2:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-2-1.png'
                ),
                '2.5/1'  => array(
                    'label' => '2.5:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-2_5-1.png'
                ),
                '4/1'  => array(
                    'label' => '4:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-4-1.png'
                ),
                '3/4'  => array(
                    'label' => '3:4',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-3-4.png'
                ),
                '9/16'  => array(
                    'label' => '9:16',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-9-16.png'
                ),
                '1/2'  => array(
                    'label' => '1:2',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-1-2.png'
                ),
                '1/2.5'  => array(
                    'label' => '1:2.5',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-1-2_5.png'
                ),
                'default' => array(
                    'label' => 'default',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio-default.png'
                ),
            ));

        // size settings
        $size_suggestions = array( '100%', '200px', 'auto' );

        $fields[] = Field::create( 'complex', 'images_width', __('Image Width', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use', __('Customise','mv23theme') )->fancy()->set_width( 20 ),
            Field::create( 'text', 'max_width', __('Max Width','mv23theme') )->set_placeholder('auto')->add_suggestions($size_suggestions)->add_dependency('use')->set_width( 20 ),
            Field::create( 'text', 'width', __('Width','mv23theme') )->set_placeholder('auto')->add_suggestions($size_suggestions)->add_dependency('use')->set_width( 20 ),
            Field::create( 'text', 'min_width', __('Min Width','mv23theme') )->set_placeholder('auto')->add_suggestions($size_suggestions)->add_dependency('use')->set_width( 20 )
        ));

        $fields[] = Field::create( 'complex', 'images_height', __('Image Height', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use', __('Customise','mv23theme') )->fancy()->set_width( 20 ),
            Field::create( 'text', 'max_height', __('Max Height','mv23theme') )->set_placeholder('auto')->add_suggestions($size_suggestions)->add_dependency('use')->set_width( 20 ),
            Field::create( 'text', 'height', __('Height','mv23theme') )->set_placeholder('auto')->add_suggestions($size_suggestions)->add_dependency('use')->set_width( 20 ),
            Field::create( 'text', 'min_height', __('Min Height','mv23theme') )->set_placeholder('auto')->add_suggestions($size_suggestions)->add_dependency('use')->set_width( 20 )
        ));

        // action settings
        $fields[] = Field::create( 'complex', '_action_wrapper', __('Action Settings', 'mv23theme') )->merge()->add_fields(array(
            Field::create( 'select', 'link', __('On Click', 'mv23theme'))->add_options( array(
                'none' => __('None', 'mv23theme'),
                'file' => __('Show in LightBox', 'mv23theme'),
                'post' => __('Go to Image Page', 'mv23theme'),
                'custom' => __('Go to custom link', 'mv23theme')
            ))->set_default_value('file')->set_attr('style', 'flex-grow: initial;'),
            Field::create( 'select', 'targetsize', __('LightBox quality size', 'mv23theme'))->add_options( array(
                'thumbnail' => __('Thumbnail', 'mv23theme'),
                'medium' => __('Medium', 'mv23theme'),
                'large' => __('Large', 'mv23theme'),
                'full' => __('Full', 'mv23theme'),
            ))->set_default_value('full')->add_dependency('link','file','=')->set_attr('style', 'flex-grow: initial;'),
        ));

        // advanced settings
        $fields[] = Field::create( 'tab', __('Advanced Settings', 'mv23theme') );
        $fields[] = Field::create( 'complex', '_gallery_id_wrapper', __('Gallery ID', 'mv23theme') )->merge()->add_fields(array(
            Field::create( 'text', 'gallery_id' )
                ->set_width( 50 ),
            Field::create( 'checkbox', 'hide_gallery' )->set_text( __('Activate', 'mv23theme') )->set_width( 50 ),
            Field::create( 'message', 'gallery_id_usage' )
                ->set_description(__('Use <strong>show-gallery--GALLERY-ID</strong> css class on a button to open the gallery in the frontend', 'mv23theme'))
                ->add_dependency('gallery_id','','!=')->hide_label()->set_width(100)
        ));
        $fields[] = Field::create( 'select', 'size', __('Image Quality', 'mv23theme'))->add_options( array(
                'thumbnail' => __('Thumbnail', 'mv23theme'),
                'medium' => __('Medium', 'mv23theme'),
                'large' => __('Large', 'mv23theme'),
                'full' => __('Full', 'mv23theme'),
            ))->set_default_value('large');

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
		$args['additional_classes'] = array('component');
        $args['__type'] = 'theme-gallery-comp';

        $hide_gallery = ( isset($args['hide_gallery']) ) ? $args['hide_gallery'] : false;
        if($hide_gallery) $args['additional_classes'][] = 'hide';

        $source = $args['source'] ?? 'manual';
        $link = $args['link'] ?? 'file';
        $size = $args['size'] ?? 'large';
        $targetsize = $args['targetsize'] ?? 'full';
        $display = $args['display'] ?? 'default';

        $d_columns = $args['items_in_desktop'] ?? 4;
        $l_columns = $args['items_in_laptop'] ?? 3;
        $t_columns = $args['items_in_tablet'] ?? 2;
        $m_columns = $args['items_in_mobile'] ?? 1;

        $d_gap = $args['gutter_in_desktop'] ?? 4;
        $l_gap = $args['gutter_in_laptop'] ?? 4;
        $t_gap = $args['gutter_in_tablet'] ?? 4;
        $m_gap = $args['gutter_in_mobile'] ?? 4;

        $shortcode_name = ($source === 'manual') ? 'theme_gallery' : 'theme_gallery';
        $gallery_id = (isset($args['gallery_id'])) ? $args['gallery_id'] : '';

        $shortcode = '['.$shortcode_name.' link="'.$link.'" d_columns="'.$d_columns.'" l_columns="'.$l_columns.'" t_columns="'.$t_columns.'" m_columns="'.$m_columns.'" d_gap="'.$d_gap.'" l_gap="'.$l_gap.'" t_gap="'.$t_gap.'" m_gap="'.$m_gap.'" size="'.$size.'" targetsize="'.$targetsize.'" display="'.$display.'" gallery_id="'.$gallery_id.'"';

        $aspect_ratio = $args['aspect_ratio'] ?? 'default';
        if($aspect_ratio != 'default') $shortcode .= ' aspectratio="'.$aspect_ratio.'"';

        if($source == 'wp-media'){
        	$wp_media_folder = $args['wp_media_folder'] ?? 0;
        	if($wp_media_folder){
        		$shortcode .= ' wpmf_folder_id="'.$wp_media_folder.'" wpmf_autoinsert="1"';
        	}
        } else {
        	$gallery = $args['gallery'];
        	$ids = implode(',',$gallery);
        	$shortcode .= ' ids="'.$ids.'"';
        }

        if( $display == 'marquee' ){
            $marquee_speed = ( isset($args['marquee_speed']) && is_numeric($args['marquee_speed']) ) ? $args['marquee_speed'] : 18;
            $shortcode .= ' marquee_speed="'.$marquee_speed.'"';

            $fade_color = $args['fade_color'] ?? '#ffffff';
            $shortcode .= ' marquee_fade_color="'.$fade_color.'"';
        }

        // handle size styles in shorcode size_styles='max-width: 100%;...'
        $size_styles = '';
        $size_properties = array('height', 'width');
        foreach($size_properties as $size_property){
            if( isset($args['images_'.$size_property]) && is_array($args['images_'.$size_property]) ){
                $use = $args['images_'.$size_property]['use'] ?? false;
                if($use){
                    $_properties = array('max_'.$size_property, $size_property, 'min_'.$size_property);
                    foreach($_properties as $property){
                        if( isset($args['images_'.$size_property][$property]) && $args['images_'.$size_property][$property] != '' ){
                            $size_styles .= $property.': '.$args['images_'.$size_property][$property].';';
                        }
                    }
                }
            }
        }
        if($size_styles) $shortcode .= ' size_styles="'.$size_styles.'"';

        // end of shortcode
        $shortcode .= ']';
        
		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
		if($shortcode) echo do_shortcode($shortcode);
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}
}

new Gallery();