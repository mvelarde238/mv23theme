<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Builder\Template_Engine\Video as Video_Template_Engine;
use Core\Builder\Slider_Settings;
use Core\Builder\Component\Carousel;

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

        // Slider Settings
        $fields[] = Field::create( 'tab', 'slider_settings_tab', __('Slider Settings','mv23theme') )
            ->add_dependency('display', 'slider', '=');
        $fields[] = Slider_Settings::getRepeater( 'slider_settings', __('Slider Settings', 'mv23theme') )
            ->hide_label()
            ->add_dependency('display', 'slider', '=');
        
        // content fields
        $sources = apply_filters('filter_gallery_sources', array(
            'placeholders' => __('Placeholders', 'mv23theme'),
            'manual' => __('Select Images', 'mv23theme'),
        ));

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

        $fields = apply_filters('filter_gallery_content_tab_fields', $fields);
        
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
        $images_path = BUILDER_PATH.'/assets/images/aspect-ratio/';
        $fields[] = Field::create( 'tab', __('Images Size', 'mv23theme') )->add_dependency('display', array('grid'), 'NOT_IN');
        $fields[] = Field::create( 'image_select', 'aspect_ratio', __('Aspect ratio','mv23theme') )
            ->set_description(__('Appearance of the images in the gallery. If you select "default" the images will keep their original aspect ratio.', 'mv23theme'))
            ->set_attr( 'class', 'image-select-3-cols' )
            ->set_default_value('1/1')
            ->add_options(array(
                'default' => array('label' => 'default', 'image' => $images_path.'aspect-ratio-default.png'),
                '1/1'  => array('label' => '1:1', 'image' => $images_path.'aspect-ratio-1-1.png'),
                '4/3'  => array('label' => '4:3', 'image' => $images_path.'aspect-ratio-4-3.png'),
                '16/9'  => array('label' => '16:9', 'image' => $images_path.'aspect-ratio-16-9.png'),
                '2/1'  => array('label' => '2:1', 'image' => $images_path.'aspect-ratio-2-1.png'),
                '2.5/1'  => array('label' => '2.5:1', 'image' => $images_path.'aspect-ratio-2_5-1.png'),
                '4/1'  => array('label' => '4:1', 'image' => $images_path.'aspect-ratio-4-1.png'),
                '3/4'  => array('label' => '3:4', 'image' => $images_path.'aspect-ratio-3-4.png'),
                '9/16'  => array('label' => '9:16', 'image' => $images_path.'aspect-ratio-9-16.png'),
                '1/2'  => array('label' => '1:2', 'image' => $images_path.'aspect-ratio-1-2.png'),
                '1/2.5'  => array('label' => '1:2.5', 'image' => $images_path.'aspect-ratio-1-2_5.png')
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

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_restricted( $args ) ) return;

        $args['additional_classes'][] = 'component';
        $args['__type'] = 'theme-gallery-comp';
        
        $source = $args['source'] ?? 'manual';
        if( $source == 'manual' ){
            $gallery = $args['gallery'] ?? array();
            if( empty($gallery) ) return '';
        } else if( $source == 'placeholders' ){
            $placeholders_quantity = $args['placeholders_quantity'] ?? 8;
            if( empty($placeholders_quantity) ) return '';
        }

        do_action('before_gallery_process', $args);

        // check if gallery should be hidden
        $hide_gallery = $args['use_id']['hide_gallery'] ?? false;
        if($hide_gallery === true) $args['additional_classes'][] = 'hide';
        
        // build gallery settings
        $gallery_settings = self::get_gallery_settings($args);

        // get gallery attachments
        $attachments = self::get_gallery_attachments($gallery_settings, $args);
        if( empty($attachments) ) return '';

        // prepare item attributes
        $item_attrs = array(
            'additional_classes' => ['theme-gallery__item']
        );
        if ( $gallery_settings['display'] == 'grid' ){
            $item_attrs['additional_classes'][] = 'grid-stack-item';
        }
        if( $gallery_settings['display'] == 'masonry' ){
            $item_attrs['additional_classes'][] = 'masonry-grid-item';
        } 

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
        
        echo self::get_gallery_wrapper_start( $gallery_settings, $args );

        $item_counter = 0;
        foreach ($attachments as $attachment_id) :
            $type = $gallery_settings['use_placeholder_images'] ? 'placeholder' : get_post_mime_type($attachment_id);
            $attachment_type = '';
            $is_remote_video = false;

            // handle grid data for grid display
            if( $gallery_settings['display'] == 'grid' ){
                $item_attrs['additional_attributes'] = []; // reset per-item to avoid accumulating previous attrs
                if( isset($gallery_settings['grid_data'][$item_counter]) ){
                    $item_grid_data = $gallery_settings['grid_data'][$item_counter];
                    if(isset($item_grid_data['x'])) $item_attrs['additional_attributes']['gs-x'] = $item_grid_data['x'];
                    if(isset($item_grid_data['y'])) $item_attrs['additional_attributes']['gs-y'] = $item_grid_data['y'];
                    if(isset($item_grid_data['w'])) $item_attrs['additional_attributes']['gs-w'] = $item_grid_data['w'];
                    if(isset($item_grid_data['h'])) $item_attrs['additional_attributes']['gs-h'] = $item_grid_data['h'];
                }
            }

            // get the attachment output
            $the_attachment_data = self::get_attachment_output( $attachment_id, $gallery_settings, $type);
            $the_attachment_link = self::get_attachment_link( $attachment_id, $gallery_settings, $the_attachment_data );

            echo '<div '.Template_Engine::generate_attributes($item_attrs).'>';
            if ( $gallery_settings['display'] == 'grid' ) echo '<div class="grid-stack-item-content">';
            if ( $gallery_settings['link'] != 'none') echo $the_attachment_link['start'];
            echo $the_attachment_data['output'];
            if ( $gallery_settings['link'] != 'none') echo $the_attachment_link['end'];
            if ( $gallery_settings['display'] == 'grid' ) echo '</div>'; // close grid-stack-item-content
            echo '</div>';
            $item_counter++;
        endforeach;
        
        echo self::get_gallery_wrapper_end( $gallery_settings, $args );
    
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}

    private static function get_gallery_wrapper_start( $gallery_settings, $args ){
        $wrapper_styles = array();

        if( $gallery_settings['aspectratio'] ) $wrapper_styles[] = '--aspect-ratio:'.$gallery_settings['aspectratio'];
        $wrapper_styles[] = '--d-gap:'.$gallery_settings['d_gap'].'px';
        $wrapper_styles[] = '--l-gap:'.$gallery_settings['l_gap'].'px';
        $wrapper_styles[] = '--t-gap:'.$gallery_settings['t_gap'].'px';
        $wrapper_styles[] = '--m-gap:'.$gallery_settings['m_gap'].'px';
        $wrapper_styles[] = '--d-columns:'.$gallery_settings['d_columns'];
        $wrapper_styles[] = '--l-columns:'.$gallery_settings['l_columns'];
        $wrapper_styles[] = '--t-columns:'.$gallery_settings['t_columns'];
        $wrapper_styles[] = '--m-columns:'.$gallery_settings['m_columns'];

        ob_start();
        if( $gallery_settings['display'] == 'slider' ){ 
            ?>
            <div class="theme-gallery theme-gallery--slider carousel" style="<?=implode(';',$wrapper_styles)?>">
            <?php echo Carousel::slider_start( $args ); ?>
            <?php
        } else if ( $gallery_settings['display'] == 'masonry' ) {
            echo '<div class="theme-gallery has-masonry-columns" style="'.implode(';', $wrapper_styles).'">';
            echo '<div class="masonry-grid-sizer"></div>';
            echo '<div class="masonry-gutter-sizer"></div>';

        } else if ( $gallery_settings['display'] == 'marquee' ) {
            $wrapper_styles[] = '--fade-width: '.$gallery_settings['marquee_fade_width'];
            echo '<div class="theme-gallery theme-gallery__marquee marquee" data-speed="'.$gallery_settings['marquee_speed'].'" data-direction="'.$gallery_settings['marquee_direction'].'" style="'.implode(';', $wrapper_styles).'">';
            echo '<div class="marquee-track">';
            
        } else if ( $gallery_settings['display'] == 'grid' ) {
            echo '<div class="theme-gallery grid-stack theme-gallery--grid" style="'.implode(';', $wrapper_styles).'">';
             
        } else if ( $gallery_settings['display'] == 'default' ) {
            echo '<div class="theme-gallery has-columns theme-gallery--'.$gallery_settings['display'].'" style="'.implode(';', $wrapper_styles).'">';

        } else {
            echo '<div class="theme-gallery theme-gallery--'.$gallery_settings['display'].'" style="'.implode(';', $wrapper_styles).'">';
        }
        return ob_get_clean();
    }

    private static function get_gallery_wrapper_end( $gallery_settings, $args ){
        ob_start();
        if( $gallery_settings['display'] == 'slider' ){ 
            echo Carousel::slider_end(); // close carousel__slider
            echo Carousel::slider_controls( $args, true );
            echo '</div>'; // close theme-gallery
        } else if ( $gallery_settings['display'] == 'marquee' ) {
            echo '</div></div>'; // close marquee-track and theme-gallery
        } else {
            echo '</div>'; // close theme-gallery
        }
        return ob_get_clean();
    }

    private static function get_gallery_settings($args) {
        $rand_id = 'gallery_'.substr(md5(microtime()),rand(0,26),5);
        $gallery_id = $args['use_id']['id'] ?? $rand_id;
        
        $source = $args['source'] ?? 'manual';
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

        $gallery_settings = array(
            'link' => $link,
            'd_columns' => $d_columns,
            'l_columns' => $l_columns,
            't_columns' => $t_columns,
            'm_columns' => $m_columns,
            'd_gap' => $d_gap,
            'l_gap' => $l_gap,
            't_gap' => $t_gap,
            'm_gap' => $m_gap,
            'size' => $image_quality,
            'targetsize' => $targetsize,
            'display' => $display,
            'gallery_id' => $gallery_id,
            'aspectratio' => '',
            'use_placeholder_images' => false
        );

        $aspect_ratio = $args['aspect_ratio'] ?? 'default';
        if($aspect_ratio != 'default') $gallery_settings['aspectratio'] = $aspect_ratio;

        if( $source == 'placeholders' ){
            $placeholders_quantity = $args['placeholders_quantity'] ?? 8;
            $gallery_settings['use_placeholder_images'] = '1';
            $gallery_settings['placeholders_quantity'] = $placeholders_quantity;

            $placeholders_source = $args['placeholders_source'] ?? 'picsum';
            $gallery_settings['placeholders_source'] = $placeholders_source;
        } else {
        	$gallery = $args['gallery'] ?? array();
        	$ids = (is_array($gallery)) ? implode(',',$gallery) : '';
        	$gallery_settings['ids'] = $ids;
        }

        if( $display == 'marquee' ){
            $speed = $args['marquee_settings']['speed'] ?? 40;
            $marquee_speed = ( is_numeric($speed) ) ? $speed : 40;
            $gallery_settings['marquee_speed'] = $marquee_speed;

            $fade_width = $args['marquee_settings']['fade_width'] ?? '100px';
            $gallery_settings['marquee_fade_width'] = $fade_width;

            $direction = $args['marquee_settings']['direction'] ?? 'left';
            $gallery_settings['marquee_direction'] = $direction;
        }

        // handle size styles: 'max-width: 100%;...'
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
        if($size_styles) $gallery_settings['size_styles'] = $size_styles;

        // grid data
        if( $display == 'grid' ){
            $grid_data = $args['grid_data'] ?? array();

            if( empty($grid_data) ){
                $gallery_settings['grid_data'] = array(
                    ['x'=>0,'y'=>0,'w'=>3,'h'=>3],
                    ['x'=>3,'y'=>0,'w'=>4,'h'=>2],
                    ['x'=>7,'y'=>0,'w'=>3,'h'=>3],
                    ['x'=>10,'y'=>0,'w'=>2,'h'=>2],
                    ['x'=>3,'y'=>2,'w'=>4,'h'=>3],
                    ['x'=>10,'y'=>2,'w'=>2,'h'=>3],
                    ['x'=>0,'y'=>3,'w'=>3,'h'=>2],
                    ['x'=>7,'y'=>3,'w'=>3,'h'=>2]
                );
            } else {
                $gallery_settings['grid_data'] = $grid_data;
            }
        }

        if ( isset($gallery_settings['use_placeholder_images']) && $gallery_settings['use_placeholder_images'] ) {
            $gallery_settings['link'] = 'placeholder'; // override link type since these are not real attachments
        }

        return apply_filters('filter_gallery_settings', $gallery_settings, $args);
    }

    private static function get_gallery_attachments($gallery_settings, $args) {
        $attachments = array();

        if ( isset($gallery_settings['use_placeholder_images']) && $gallery_settings['use_placeholder_images'] ) {
            $placeholders_source = $gallery_settings['placeholders_source'] ?? 'picsum';
            $placeholders_quantity = $gallery_settings['placeholders_quantity'] ?? 8;

            switch ($placeholders_source) {
                case 'picsum':
                    for ($i = 0; $i < $placeholders_quantity; $i++) {
                        array_push( $attachments, 'https://picsum.photos/600/500?random=' . $i );
                    }
                    break;
                
                case 'unsplash':
                default:
                    for ($i = 0; $i < $placeholders_quantity; $i++) {
                        array_push( $attachments, 'https://unsplash.it/600/500?sig=' . $i );
                    }
                    break;
                
                case 'placehold':   
                    for ($i = 0; $i < $placeholders_quantity; $i++) {
                        array_push( $attachments, 'https://placehold.co/600x500' );
                    }
                    break;
            }
        } else {
            $attachments = explode(',',$gallery_settings['ids']);
        }

        return apply_filters('filter_gallery_attachments', $attachments, $gallery_settings, $args);
    }

    private static function get_attachment_output( $attachment_id, $gallery_settings, $type ){
        switch ($type) {
            case 'image/jpeg':
            case 'image/png':
            case 'image/gif':
                $attachment_type = 'image';

                $attach_url = wp_get_attachment_image_url($attachment_id, $gallery_settings['size']);
                $url = $attach_url;

                $image_attrs = array();
                $image_attrs['additional_attributes']['src'] = $attach_url;
    
                if( !empty($gallery_settings['size_styles'])){
                    $image_attrs['additional_attributes']['style'] = $gallery_settings['size_styles'];
                }
                $the_attachment = '<img '.Template_Engine::generate_attributes($image_attrs).'>';
            break;

            case 'video/mpeg':
            case 'video/mp4': 
            case 'video/quicktime':
                $attachment_type = 'video';
                $url = wp_get_attachment_url($attachment_id);

                $video_args = array(
                    'video' => array(
                        'videos' => array($attachment_id),
                        'poster' => null
                    ),
                    'video_settings' => array()
                );

                if ($gallery_settings['link'] != 'none'){
                    $video_args['video_settings']['controls'] = 0;
                    $video_args['video_settings']['autoplay'] = 1;
                    $video_args['video_settings']['muted'] = 1;
                    $video_args['video_settings']['loop'] = 1;
                } 

                if( !empty($gallery_settings['size_styles'])){
                    $video_args['video_settings']['styles'] = $gallery_settings['size_styles'];
                }

                $video_data = Video_Template_Engine::get_video_data( $video_args );
                if( !empty($video_data['code']) ) $the_attachment = $video_data['code'];
                
                break;

            case 'application/pdf':
                $attachment_type = 'pdf';
                $url = wp_get_attachment_url($attachment_id);

                $image_attrs = array();
                $image_attrs['additional_attributes'] = array(
                    'src' => get_template_directory_uri().'/assets/images/pdf_poster.jpg'
                );

                if( !empty($gallery_settings['size_styles'])){
                    $image_attrs['additional_attributes']['style'] = $gallery_settings['size_styles'];
                }
                $the_attachment = '<img '.Template_Engine::generate_attributes($image_attrs).'>';
                break;

            case 'placeholder':
                $attachment_type = 'image';
                $url = $attachment_id; // In this case, $attachment_id is actually the URL of the placeholder image

                $image_attrs = array();
                $image_attrs['additional_attributes'] = array('src' => $url);
    
                if( !empty($gallery_settings['size_styles'])){
                    $image_attrs['additional_attributes']['style'] = $gallery_settings['size_styles'];
                }
                $the_attachment = '<img '.Template_Engine::generate_attributes($image_attrs).'>';
                break;
                
            default:
                $url = wp_get_attachment_url($attachment_id);
                $the_attachment = '<p>'.$type.'</p>';
                $attachment_type = $type;
        }

        $attachment_data = apply_filters('filter_gallery_attachment_data', array(
            'output' => $the_attachment,
            'url' => $url,
            'type' => $attachment_type
        ), $attachment_id, $gallery_settings, $type);

        return $attachment_data;
    }

    private static function get_attachment_link( $attachment_id, $gallery_settings, $the_attachment_data ){
        $link_attrs = array();

        if($gallery_settings['link'] != 'none') {
            $attachment_type = $the_attachment_data['type'];

            // href
            switch ($gallery_settings['link']) {
                case 'file':
                    $attachment_link = ($attachment_type === 'image') 
                        ? wp_get_attachment_image_url($attachment_id, $gallery_settings['targetsize']) 
                        : $the_attachment_data['url'];
                    break;
                case 'post':
                    $attachment_link = get_attachment_link($attachment_id);
                    break;
                default:
                    $attachment_link = $the_attachment_data['url'];
                    break;
            }
            $link_attrs['href'] = $attachment_link;

            // caption
            $caption = wp_get_attachment_caption($attachment_id) ?? '';
            if( $caption ) $link_attrs['data-description'] = $caption;
            
            // data gallery
            $dont_use_glightbox = array('custom', 'post', 'none');
            if(!in_array($gallery_settings['link'], $dont_use_glightbox)) {
                $link_attrs['data-gallery'] = $gallery_settings['gallery_id'];
            }

            // Explicitly set data-type so GLightbox uses the correct renderer.
            // Without this, URLs without a recognized extension (e.g. external image CDNs)
            // fall back to "external" type which has broken layout at desktop widths.
            if( $attachment_type === 'image' ) {
                $link_attrs['data-type'] = 'image';
            }
        }

        $link_attrs = apply_filters('filter_gallery_attachment_link_attributes', $link_attrs, $attachment_id, $gallery_settings, $the_attachment_data);

        $attachment_link_start = '<a '.Template_Engine::generate_attributes( array( 'additional_attributes' => $link_attrs ) ).'>';
        $attachment_link_end = '</a>';

        return array(
            'start' => $attachment_link_start,
            'end' => $attachment_link_end
        );
    }
}

new Gallery();