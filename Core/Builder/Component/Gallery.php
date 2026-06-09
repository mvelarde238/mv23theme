<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Gallery extends Component {

    // temporary in-memory storage for data that needs to be generated and accessed in the same request 
    // (like the grid data generated in the builder and used in the shortcode render)
    private static array $temp_data = [];

    public static function set_temp_data( string $key, mixed $value ): void {
        self::$temp_data[$key] = $value;
    }

    public static function get_temp_data( string $key ): mixed {
        return self::$temp_data[$key] ?? null;
    }

    public function __construct() {
		parent::__construct(
			'gallery',
			__( 'Gallery', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'dashicons-images-alt2';
    }

    public static function get_builder_data() {
        return array(
            'block_render_type' => 'gallery',
            'custom_datastore_change_callback' => true
		);
    }

	public static function get_fields() {
		$fields = array();

        // gallery type
        $fields[] = Field::create( 'tab', __('Gallery Type','mv23theme') );

        $gallery_types = apply_filters('filter_gallery_types', array(
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
            'grid' => array(
                'label' => 'grid',
                'image' => BUILDER_PATH.'/assets/images/galleries/masonry.png'
            ),
            'masonry' => array(
                'label' => 'masonry',
                'image' => BUILDER_PATH.'/assets/images/galleries/masonry.png'
            )
        ));

        $fields[] = Field::create( 'image_select', 'display', __('Gallery type','mv23theme') )->set_default_value('grid')
            ->hide_label()->show_label()->set_attr( 'class', 'image-select-2-cols' )->add_options(  $gallery_types );

        if( !MASONRY_IS_ACTIVE ){
            $fields[] = Field::create( 'message', 'masonry_message', __('Activate Masonry','mv23theme') )
                ->set_description('You need to activate masonry gallery to use this feature: <a href="'.admin_url().'admin.php?page=theme-options#global_options" target="_blank">Activate Masonry Gallery</a>')
                ->add_dependency('display', 'masonry', '=')
                ->set_attr( 'style', 'background:#ffe8e8;width:100%;' );
        }
        if( !SCROLL_ANIMATIONS ){
            $fields[] = Field::create( 'message', 'marquee_message', __('Activate GSAP Animations','mv23theme') )
                ->set_description('You need to activate GSAP animations to use this feature: <a href="'.admin_url().'admin.php?page=theme-options#global_options" target="_blank">Activate GSAP Animations</a>')
                ->add_dependency('display', 'marquee', '=')
                ->set_attr( 'style', 'background:#ffe8e8;width:100%;' );
        }

        // marquee settings
        $fields[] = Field::create( 'tab', '_marquee-settings-tab', __('Marquee Settings', 'mv23theme') )
            ->add_dependency('display', 'marquee', '=');
        $fields[] = Field::create( 'complex', 'marquee_settings' )->hide_label()->add_fields(array(
            Field::create( 'number', 'speed', __('Animation Speed', 'mv23theme') )
                ->set_default_value(40)
                ->set_suffix(__('Seconds', 'mv23theme'))
                ->set_attr( 'style', 'width: 50%; min-width: initial;' ),
            Field::create( 'text', 'fade_width', __('Fade Width', 'mv23theme') )
                ->set_placeholder('100px')
                ->set_default_value('100px')
                ->set_attr( 'style', 'width: 50%; min-width: initial;' ),
            Field::create( 'select', 'direction', __('Direction', 'mv23theme') )
                ->add_options( array(
                    'left' => __('Left','mv23theme'),
                    'right' => __('Right','mv23theme'),
                ))
                ->set_default_value('left')
                ->set_width( 50 )
        ))->add_dependency('display', 'marquee', '=');
        
        // content fields
        $sources = array(
            'placeholders' => __('Placeholders', 'mv23theme'),
            'manual' => __('Select Images', 'mv23theme'),
        );
        if(WPMEDIAFOLDER_IS_ACTIVE) $sources = array_merge( array('wp-media' => __('Select Folder', 'mv23theme')), $sources );

        $fields[] = Field::create( 'tab', __('Content','mv23theme') );
        $fields[] = Field::create( 'radio', 'source', __('Source', 'mv23theme'))
            ->set_default_value('placeholders')
            ->set_orientation('horizontal')
            ->add_options( $sources );
        $fields[] = Field::create( 'number', 'placeholders_quantity', __('Number of images', 'mv23theme') )
            ->set_default_value(8)
            ->add_dependency('source', 'placeholders', '=')
            ->set_width(50);
        $fields[] = Field::create( 'select', 'placeholders_source', __('Source', 'mv23theme') )
            ->add_options( array(
                'picsum' => 'Picsum',
                'unsplash' => 'Unsplash',
                'placehold' => 'Placehold',
            ))
            ->set_default_value('picsum')
            ->add_dependency('source', 'placeholders', '=')
            ->set_width(50);
        $fields[] = Field::create( 'gallery', 'gallery' )
            ->hide_label()
            ->add_dependency('source', 'manual', '=');
        
        if(WPMEDIAFOLDER_IS_ACTIVE) {
            // wp media fields
            $fields[] = Field::create( 'select', 'wp_media_folder' )->add_terms( 'wpmf-category' )->fancy()->set_width(25)->add_dependency('source', 'wp-media', '=');
            $fields[] = Field::create( 'message', 'wp_media_folder_message', __('WP Media Folder', 'mv23theme') )->set_description('<a href="'.admin_url().'upload.php" target="_blank">'.__('Create a new WP Media Folder', 'mv23theme').'</a>')->add_dependency('source', 'wp-media', '=')->set_width(70);
            // Field::create( 'checkbox', 'autoinsert' )->set_text( '¿Autoinsertar las imágenes agregadas a la galerîa?' ); // dosnt work, the shortcode needs the attachments id's
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
        }
        
        // columns and gutter settings
        $fields[] = Field::create( 'tab', '_gallery-colums-tab', __('Columns', 'mv23theme') );
        $width_style = 'width: 25%; min-width: initial;';
        $fields[] = Field::create( 'complex', 'items', __('Columns', 'mv23theme') )->hide_label()->add_fields(array(
            Field::create( 'number', 'desktop', __('Desktop', 'mv23theme') )->set_minimum(1)->set_default_value(GALLERY_COLUMNS['desktop'])->set_attr( 'style', $width_style ),
            Field::create( 'number', 'laptop', __('Laptop', 'mv23theme') )->set_minimum(1)->set_default_value(GALLERY_COLUMNS['laptop'])->set_attr( 'style', $width_style ),
            Field::create( 'number', 'tablet', __('Tablet', 'mv23theme') )->set_minimum(1)->set_default_value(GALLERY_COLUMNS['tablet'])->set_attr( 'style', $width_style ),
            Field::create( 'number', 'mobile', __('Mobile', 'mv23theme') )->set_minimum(1)->set_default_value(GALLERY_COLUMNS['mobile'])->set_attr( 'style', $width_style )
        ))->add_dependency('display', array('marquee','grid'), 'NOT_IN');

        $fields[] = Field::create( 'complex', 'gutter', __('Space between items', 'mv23theme') )->add_fields(array(
            Field::create( 'number', 'desktop', __('Desktop', 'mv23theme') )->set_minimum(0)->set_default_value(GALLERY_GAP['desktop'])->set_attr( 'style', $width_style ),
            Field::create( 'number', 'laptop', __('Laptop', 'mv23theme') )->set_minimum(0)->set_default_value(GALLERY_GAP['laptop'])->set_attr( 'style', $width_style ),
            Field::create( 'number', 'tablet', __('Tablet', 'mv23theme') )->set_minimum(0)->set_default_value(GALLERY_GAP['tablet'])->set_attr( 'style', $width_style ),
            Field::create( 'number', 'mobile', __('Mobile', 'mv23theme') )->set_minimum(0)->set_default_value(GALLERY_GAP['mobile'])->set_attr( 'style', $width_style )
        ));
        
        // images settings
        $fields[] = Field::create( 'tab', __('Images Size', 'mv23theme') )->add_dependency('display', array('grid'), 'NOT_IN');
        $fields[] = Field::create( 'image_select', 'aspect_ratio', __('Aspect ratio','mv23theme') )
            ->set_description(__('Appearance of the images in the gallery. If you select "default" the images will keep their original aspect ratio.', 'mv23theme'))
            ->set_attr( 'class', 'image-select-3-cols' )
            ->set_default_value('1/1')
            ->add_options(array(
                'default' => array(
                    'label' => 'default',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-default.png'
                ),
                '1/1'  => array(
                    'label' => '1:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-1-1.png'
                ),
                '4/3'  => array(
                    'label' => '4:3',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-4-3.png'
                ),
                '16/9'  => array(
                    'label' => '16:9',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-16-9.png'
                ),
                '2/1'  => array(
                    'label' => '2:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-2-1.png'
                ),
                '2.5/1'  => array(
                    'label' => '2.5:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-2_5-1.png'
                ),
                '4/1'  => array(
                    'label' => '4:1',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-4-1.png'
                ),
                '3/4'  => array(
                    'label' => '3:4',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-3-4.png'
                ),
                '9/16'  => array(
                    'label' => '9:16',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-9-16.png'
                ),
                '1/2'  => array(
                    'label' => '1:2',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-1-2.png'
                ),
                '1/2.5'  => array(
                    'label' => '1:2.5',
                    'image' => BUILDER_PATH.'/assets/images/aspect-ratio/aspect-ratio-1-2_5.png'
                )
            ));

        // size settings
        $size_suggestions = array( '100%', '200px', 'auto' );

        $fields[] = Field::create( 'complex', 'images_width' )->hide_label()->add_fields(array(
            Field::create( 'checkbox', 'use' )
				->hide_label()
        		->set_attr( 'class', 'uf-separator-top' )
        		->set_text( __('Customise Image Width', 'mv23theme') ),
            Field::create( 'text', 'width' )
                ->set_placeholder('auto')
                ->hide_label()
                ->set_prefix( __('Width: ', 'mv23theme') )
                ->add_suggestions($size_suggestions)
                ->add_dependency('use'),
            Field::create( 'text', 'max_width' )
                ->set_placeholder('auto')
                ->hide_label()
                ->set_prefix( __('Max Width: ', 'mv23theme') )
                ->add_suggestions($size_suggestions)
                ->add_dependency('use'),
            Field::create( 'text', 'min_width' )
                ->set_placeholder('auto')
                ->hide_label()
                ->set_prefix( __('Min Width: ', 'mv23theme') )
                ->add_suggestions($size_suggestions)
                ->add_dependency('use')
        ));

        $fields[] = Field::create( 'complex', 'images_height' )->hide_label()->add_fields(array(
            Field::create( 'checkbox', 'use' )
                ->set_default_value(1)
				->hide_label()
        		->set_attr( 'class', 'uf-separator-top' )
        		->set_text( __('Customise Image Height', 'mv23theme') ),
            Field::create( 'text', 'height' )
                ->set_placeholder('auto')
                ->hide_label()
                ->set_prefix( __('Height: ', 'mv23theme') )
                ->add_suggestions($size_suggestions)
                ->add_dependency('use'),
            Field::create( 'text', 'max_height' )
                ->set_default_value('200px')
                ->set_placeholder('auto')
                ->hide_label()
                ->set_prefix( __('Max Height: ', 'mv23theme') )
                ->add_suggestions($size_suggestions)
                ->add_dependency('use'),
            Field::create( 'text', 'min_height' )
                ->set_placeholder('auto')
                ->hide_label()
                ->set_prefix( __('Min Height: ', 'mv23theme') )
                ->add_suggestions($size_suggestions)
                ->add_dependency('use')
        ));

        // action settings
        $fields[] = Field::create( 'tab', '_action-settings-tab', __('Action Settings', 'mv23theme') );
        $fields[] = Field::create( 'complex', 'action' )->hide_label()->add_fields(array(
            Field::create( 'select', 'link', __('On Click', 'mv23theme'))->add_options( array(
                'none' => __('None', 'mv23theme'),
                'file' => __('Show in LightBox', 'mv23theme'),
                'post' => __('Go to Image Page', 'mv23theme'),
                'custom' => __('Go to custom link', 'mv23theme')
            ))->set_default_value('file')->set_width(50),
            Field::create( 'select', 'targetsize', __('LightBox quality size', 'mv23theme'))->add_options( array(
                'thumbnail' => __('Thumbnail', 'mv23theme'),
                'medium' => __('Medium', 'mv23theme'),
                'large' => __('Large', 'mv23theme'),
                'full' => __('Full', 'mv23theme'),
            ))->set_default_value('full')->add_dependency('link','file','=')->set_width(50),
        ));

        // advanced settings
        $fields[] = Field::create( 'tab', __('Advanced Settings', 'mv23theme') );
        $fields[] = Field::create( 'complex', 'use_id', __('Gallery ID', 'mv23theme') )->add_fields(array(
            Field::create( 'text', 'id' )->set_width( 50 ),
            Field::create( 'checkbox', 'hide_gallery' )->set_text( __('Activate', 'mv23theme') )->set_width( 50 ),
            Field::create( 'message', 'gallery_id_usage' )
                ->set_description(__('Use <strong>show-gallery--GALLERY-ID</strong> css class on a button to open the gallery in the frontend', 'mv23theme'))
                ->add_dependency('id','','!=')->hide_label()->set_width(100)
        ));
        $fields[] = Field::create( 'select', 'image_quality', __('Image Quality', 'mv23theme'))->add_options( array(
                'thumbnail' => __('Thumbnail', 'mv23theme'),
                'medium' => __('Medium', 'mv23theme'),
                'large' => __('Large', 'mv23theme'),
                'full' => __('Full', 'mv23theme'),
            ))->set_default_value('large');
        $fields[] =  Field::create( 'select', 'carousel_theme', __('Carousel Theme', 'mv23theme') )
                ->add_options( array(
                    'theme1' => __('Theme 1','mv23theme'),
                    // 'theme2' => __('Theme 2','mv23theme'),
                    'none' => __('None','mv23theme'),
                ))
                ->set_default_value('theme1')
                ->add_dependency('display', 'slider', '=');

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_restricted( $args ) ) return;
        
        $source = $args['source'] ?? 'manual';
        if( $source == 'manual' ){
            $gallery = $args['gallery'] ?? array();
            if( empty($gallery) ) return '';
        } elseif( $source == 'wp-media' ){
            $wp_media_folder = $args['wp_media_folder'] ?? 0;
            if( empty($wp_media_folder) ) return '';
        } else if( $source == 'placeholders' ){
            $placeholders_quantity = $args['placeholders_quantity'] ?? 8;
            if( empty($placeholders_quantity) ) return '';
        } else {
            return '';
        }

		$args['additional_classes'][] = 'component';
        $args['__type'] = 'theme-gallery-comp';

        $hide_gallery = $args['use_id']['hide_gallery'] ?? false;
        if($hide_gallery === true) $args['additional_classes'][] = 'hide';

        $link = $args['action']['link'] ?? 'file';
        $image_quality = $args['image_quality'] ?? 'large';
        $targetsize = $args['action']['targetsize'] ?? 'full';
        $display = $args['display'] ?? 'grid';

        $d_columns = $args['items']['desktop'] ?? GALLERY_COLUMNS['desktop'];
        $l_columns = $args['items']['laptop'] ?? GALLERY_COLUMNS['laptop'];
        $t_columns = $args['items']['tablet'] ?? GALLERY_COLUMNS['tablet'];
        $m_columns = $args['items']['mobile'] ?? GALLERY_COLUMNS['mobile'];

        $d_gap = $args['gutter']['desktop'] ?? GALLERY_GAP['desktop'];
        $l_gap = $args['gutter']['laptop'] ?? GALLERY_GAP['laptop']; 
        $t_gap = $args['gutter']['tablet'] ?? GALLERY_GAP['tablet'];
        $m_gap = $args['gutter']['mobile'] ?? GALLERY_GAP['mobile'];

        $shortcode_name = ($source === 'manual') ? 'theme_gallery' : 'theme_gallery';
        $gallery_id = $args['use_id']['id'] ?? '';

        $shortcode = '['.$shortcode_name.' link="'.$link.'" d_columns="'.$d_columns.'" l_columns="'.$l_columns.'" t_columns="'.$t_columns.'" m_columns="'.$m_columns.'" d_gap="'.$d_gap.'" l_gap="'.$l_gap.'" t_gap="'.$t_gap.'" m_gap="'.$m_gap.'" size="'.$image_quality.'" targetsize="'.$targetsize.'" display="'.$display.'" gallery_id="'.$gallery_id.'"';

        $aspect_ratio = $args['aspect_ratio'] ?? 'default';
        if($aspect_ratio != 'default') $shortcode .= ' aspectratio="'.$aspect_ratio.'"';

        if($source == 'wp-media'){
        	$wp_media_folder = $args['wp_media_folder'] ?? 0;
        	if($wp_media_folder){
        		$shortcode .= ' wpmf_folder_id="'.$wp_media_folder.'" wpmf_autoinsert="1"';
        	}
        } else if( $source == 'placeholders' ){
            $placeholders_quantity = $args['placeholders_quantity'] ?? 8;
            $shortcode .= ' use_placeholder_images="1" placeholders_quantity="'.$placeholders_quantity.'"';

            $placeholders_source = $args['placeholders_source'] ?? 'picsum';
            $shortcode .= ' placeholders_source="'.$placeholders_source.'"';
        } else {
        	$gallery = $args['gallery'] ?? array();
        	$ids = (is_array($gallery)) ? implode(',',$gallery) : '';
        	$shortcode .= ' ids="'.$ids.'"';
        }

        if( $display == 'marquee' ){
            $speed = $args['marquee_settings']['speed'] ?? 40;
            $marquee_speed = ( is_numeric($speed) ) ? $speed : 40;
            $shortcode .= ' marquee_speed="'.$marquee_speed.'"';

            $fade_width = $args['marquee_settings']['fade_width'] ?? '100px';
            $shortcode .= ' marquee_fade_width="'.$fade_width.'"';

            $direction = $args['marquee_settings']['direction'] ?? 'left';
            $shortcode .= ' marquee_direction="'.$direction.'"';
        }

        if( $display == 'slider' ){
            $carousel_theme = $args['carousel_theme'] ?? 'theme1';
            if($carousel_theme != 'none') $shortcode .= ' carousel_theme="'.$carousel_theme.'"';
        }

        // handle size styles in shorcode size_styles='max-width: 100%;...'
        $size_styles = '';
        $size_properties = array('height', 'width');
        foreach($size_properties as $size_property){
            if( isset($args['images_'.$size_property]) && is_array($args['images_'.$size_property]) ){
                $use = $args['images_'.$size_property]['use'] ?? false;
                if( $use == 'true' || $use === true || $use === 1 || $use === '1' ){
                    $_properties = array('max_'.$size_property, $size_property, 'min_'.$size_property);
                    foreach($_properties as $property){
                        if( isset($args['images_'.$size_property][$property]) && $args['images_'.$size_property][$property] != '' ){
                            $size_styles .= str_replace('_', '-', $property).': '.$args['images_'.$size_property][$property].';';
                        }
                    }
                }
            }
        }
        if($size_styles) $shortcode .= ' size_styles="'.$size_styles.'"';

        // grid data
        if( $display == 'grid' ){
            $grid_data = $args['grid_data'] ?? array();
            if( is_array($grid_data) && !empty($grid_data) ) {
                $grid_data_key = 'grid_data_' . uniqid();
                self::set_temp_data( $grid_data_key, $grid_data );
                $shortcode .= ' grid_data_key="'.$grid_data_key.'"';
            }
        }
        
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