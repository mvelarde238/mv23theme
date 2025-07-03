<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'common_settings_container' ) 
    // ->add_location( 'post_type', UF_POSTTYPES )
    ->set_layout( 'rows' )
    ->add_fields(array(
        Field::create( 'complex', 'main_attributes', __('Attributes html', 'mv23theme') )->add_fields(array(
            Field::create( 'text', 'id', __('ID','mv23theme') )
                ->set_validation_rule('^[a-z][a-za-z0-9_-]+$')
                ->set_description( __('Section identifier ID, use only lowercase letters and hyphens (-)', 'mv23theme') )
                ->set_width( 50 ),
            Field::create( 'text', 'class', __('Class','mv23theme') )
                ->set_description( __('Section class attribute, use only lowercase letters and hyphens (-)', 'mv23theme' ) )
                ->set_width( 50 )
        ))->merge(),
        Field::create( 'complex', 'layout', __('Layout','mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use', __('Activate','mv23theme') )->fancy()->set_width(50),
            Field::create( 'select', 'key', __('Layout','mv23theme'))->add_options( array(
                'layout1' => __('Standard Layout','mv23theme'),
                'layout2' => __('Extended background / Centered content','mv23theme'),
                'layout3' => __('Fully extended','mv23theme')
            ))->add_dependency('use')->set_width( 50 )
        )),
        Field::create( 'complex', 'helpers', __('Helpers','mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use', __('Activate','mv23theme') )->fancy()->set_width(10),
            Field::create( 'multiselect', 'list')
                ->hide_label()
                ->set_input_type( 'checkbox' )
                ->set_orientation( 'horizontal' )
                ->add_options( array(
                    'disable-link-to-embed-conversion' => __('Disable link to embed conversion','mv23theme'),
                    'overflow-scroll' => __('Overflow scroll','mv23theme'),
                    'overflow-hidden' => __('Overflow hidden','mv23theme'),
                    'hide-br' => __('Hide line breaks on tablets and mobile devices','mv23theme'),
                    'hide-br-tablet' => __('Hide line breaks on tablets only','mv23theme'),
                    'hide-br-mobile' => __('Hide line breaks on mobile devices only','mv23theme'),
                    'extend-bg-to-left' => __('Extend background to left','mv23theme'),
                    'extend-bg-to-right' => __('Extend background to right','mv23theme'),
                    'full-height' => __('Full Height','mv23theme')
                ))->add_dependency('use')->set_width( 80 )
        )),
        Field::create( 'complex', 'background_color', __('Background color', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use', __('Activate','mv23theme') )->fancy()->set_width( 20 ),
            Field::create( 'color', 'color' )->add_dependency('use')->set_width( 30 ),
            Field::create( 'number', 'alpha', __('Opacity','mv23theme') )
                ->add_dependency('use')
                ->set_placeholder('0')
                ->enable_slider(0,100,1)
                ->set_default_value(100)
                ->set_width( 30 )
        )),
        Field::create( 'complex', 'font_color', __('Font color', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use', __('Activate','mv23theme') )->fancy()->set_width( 20 ),
            Field::create( 'color', 'color' )->add_dependency('use')->set_width( 30 ),
            Field::create( 'select', 'color_scheme', __('Color Scheme','mv23theme') )
                ->add_dependency('use')
                ->set_description( __("Some components use the .dark-scheme CSS class to adjust their color styles for better appearance in dark mode.",'mv23theme') )
                ->add_options(array(
                    'default_scheme' => __('Default Scheme','mv23theme'),
                    'dark_scheme' => __('Dark Scheme','mv23theme')
                ))
                ->set_width( 30 )
        )),
        Field::create( 'complex', 'background_image', __('Background Image', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use', __('Activate','mv23theme') )->fancy()->set_width( 20 ),
            Field::create( 'image', 'image', __('Image','mv23theme') )->add_dependency('use')->set_width( 20 ),
            Field::create( 'complex', 'settings' )->add_fields(array(
	        	Field::create( 'select', 'size', __('Size','mv23theme') )->add_options( array(
	        	    'cover' => __('Cover','mv23theme'),
	        	    'auto' => __('Auto','mv23theme'),
	        	)),
	        	Field::create( 'select', 'repeat', __('Repeat','mv23theme') )->add_options( array(
	        	    'no-repeat' => __('No repeat','mv23theme'),
	        	    'repeat' => __('Repeat','mv23theme'),
	        	    'repeat-x' => __('Repeat X','mv23theme'),
	        	    'repeat-y' => __('Repeat Y','mv23theme'),
	        	)),
	        	Field::create( 'select', 'position_x', __('Position on the horizontal axis','mv23theme') )->add_options( array(
	        	    'center' => __('Center','mv23theme'),
	        	    'left' => __('Left','mv23theme'),
	        	    'right' => __('Right','mv23theme'),
	        	)),
	        	Field::create( 'select', 'position_y', __('Position on the vertical axis','mv23theme') )->add_options( array(
	        	    'center' => __('Center','mv23theme'),
	        	    'top' => __('Top','mv23theme'),
	        	    'bottom' => __('Bottom','mv23theme'),
                )),
                Field::create( 'checkbox', 'parallax' )
	        ))->add_dependency('image','0','>')->merge()->set_width( 20 )
        )),
        Field::create( 'complex', 'video_background', __('Video background', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use', __('Activate','mv23theme') )->fancy()->set_width( 20 ),

            Field::create( 'radio', 'video_source', __('Source','mv23theme'))->set_orientation( 'horizontal' )->add_options( array(
                'selfhosted' => __('Media','mv23theme'),
                'external' => __('External','mv23theme')
                ))->add_dependency('use')->set_width(30),

                Field::create( 'video', 'video' )->add_dependency('use')->add_dependency('video_source','selfhosted','=')->set_width(30),
            Field::create( 'embed', 'external_url', 'URL')->add_dependency('use')->add_dependency('video_source','external','=')->set_width(30),

            Field::create( 'complex', 'video_settings', __('Video Settings','mv23theme') )->add_fields(array(
                Field::create( 'color', 'bgc', __('Background color','mv23theme') )->set_width(10),
                Field::create( 'checkbox', 'controls', __('Controls','mv23theme') )->set_text( __('Activate','mv23theme') )->set_width(10),
                Field::create( 'checkbox', 'autoplay', __('AutoPlay','mv23theme') )->set_text( __('Activate','mv23theme') )->set_default_value(1)->set_width(10),
                Field::create( 'checkbox', 'muted', __('Muted','mv23theme') )->set_text( __('Activate','mv23theme') )->set_default_value(1)->set_width(10),
                Field::create( 'checkbox', 'loop', __('Loop','mv23theme') )->set_text( __('Activate','mv23theme') )->set_default_value(1)->set_width(10),
                Field::create( 'number', 'opacity', __('Opacity','mv23theme') )->enable_slider( 0, 100 )->set_default_value(100)->set_step( 5 )->set_width(10)
            ))->hide_label()->add_dependency('use')
        )),
        Field::create( 'complex', 'slider_background', __('Slider background', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use', __('Activate','mv23theme') )->fancy()->set_width( 20 ),
            Field::create( 'text', 'shortcode')->add_dependency('use')->set_width( 50 )
        )),
        Field::create( 'complex', 'width', __('Width', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use', __('Activate','mv23theme') )->fancy()->set_width( 20 ),
            Field::create( 'text', 'max_width', __('Max Width','mv23theme') )->set_placeholder('100%')->add_dependency('use')->set_width( 20 ),
            Field::create( 'text', 'width', __('Width','mv23theme') )->set_placeholder('100%')->add_dependency('use')->set_width( 20 ),
            Field::create( 'text', 'min_width', __('Min Width','mv23theme') )->set_placeholder('auto')->add_dependency('use')->set_width( 20 )
        )),
        Field::create( 'complex', 'margin', __('Margin', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use', __('Activate','mv23theme') )->fancy()->set_width( 20 ),
            Field::create( 'text', 'top', __('Top','mv23theme') )->set_placeholder('0px')->add_dependency('use')->set_width( 20 ),
            Field::create( 'text', 'right', __('Right','mv23theme') )->set_placeholder('0px')->add_dependency('use')->set_width( 20 ),
            Field::create( 'text', 'bottom', __('Bottom','mv23theme') )->set_placeholder('20px')->add_dependency('use')->set_width( 20 ),
            Field::create( 'text', 'left', __('Left','mv23theme') )->set_placeholder('0px')->add_dependency('use')->set_width( 20 )
        )),
        Field::create( 'complex', 'padding', __('Padding', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use', __('Activate','mv23theme') )->fancy()->set_width( 20 ),
            Field::create( 'text', 'top', __('Top','mv23theme') )->set_placeholder('0px')->add_dependency('use')->set_width( 20 ),
            Field::create( 'text', 'right', __('Right','mv23theme') )->set_placeholder('0px')->add_dependency('use')->set_width( 20 ),
            Field::create( 'text', 'bottom', __('Bottom','mv23theme') )->set_placeholder('0px')->add_dependency('use')->set_width( 20 ),
            Field::create( 'text', 'left', __('Left','mv23theme') )->set_placeholder('0px')->add_dependency('use')->set_width( 20 )
        )),
        Field::create( 'complex', 'border_radius', __('Border Radius', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use', __('Activate','mv23theme') )->fancy()->set_width( 20 ),
            Field::create( 'number', 'top_left', __('Top Left','mv23theme') )->set_suffix('px')->set_placeholder('0')->add_dependency('use')->set_width( 20 ),
            Field::create( 'number', 'top_right', __('Top Right','mv23theme') )->set_suffix('px')->set_placeholder('0')->add_dependency('use')->set_width( 20 ),
            Field::create( 'number', 'bottom_right', __('Bottom Right','mv23theme') )->set_suffix('px')->set_placeholder('0')->add_dependency('use')->set_width( 20 ),
            Field::create( 'number', 'bottom_left', __('Bottom Left','mv23theme') )->set_suffix('px')->set_placeholder('0')->add_dependency('use')->set_width( 20 )
        )),
        Field::create( 'complex', 'border', __('Border', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use', __('Activate','mv23theme') )->fancy()->set_width( 50 ),
            Field::create( 'checkbox', 'unlock', __('Unlock','mv23theme') )->fancy()->set_width( 50 )->add_dependency('use'),
            Field::create( 'complex', 'top' )->add_fields(array(
                Field::create( 'number', 'width', __('Border Top','mv23theme') )->set_suffix( 'px' )->set_placeholder('0')->set_width(10),
                Field::create( 'select', 'style' )->add_options(array( 'solid' => 'solid', 'dotted' => 'dotted', 'dashed' => 'dashed', 'double' => 'double', 'groove' => 'groove', 'ridge' => 'ridge', 'inset' => 'inset', 'outset' => 'outset' ))->set_width(20),
                Field::create( 'color', 'color' )->set_width(40),
            ))->add_dependency('use')->hide_label(),
            Field::create( 'complex', 'right' )->add_fields(array(
                Field::create( 'number', 'width', __('Border Right','mv23theme') )->set_suffix( 'px' )->set_placeholder('0')->set_width(10),
                Field::create( 'select', 'style' )->add_options(array( 'solid' => 'solid', 'dotted' => 'dotted', 'dashed' => 'dashed', 'double' => 'double', 'groove' => 'groove', 'ridge' => 'ridge', 'inset' => 'inset', 'outset' => 'outset' ))->set_width(20),
                Field::create( 'color', 'color' )->set_width(40),
            ))->add_dependency('use')->add_dependency('unlock')->hide_label(),
            Field::create( 'complex', 'bottom' )->add_fields(array(
                Field::create( 'number', 'width', __('Border Bottom','mv23theme') )->set_suffix( 'px' )->set_placeholder('0')->set_width(10),
                Field::create( 'select', 'style' )->add_options(array( 'solid' => 'solid', 'dotted' => 'dotted', 'dashed' => 'dashed', 'double' => 'double', 'groove' => 'groove', 'ridge' => 'ridge', 'inset' => 'inset', 'outset' => 'outset' ))->set_width(20),
                Field::create( 'color', 'color' )->set_width(40),
            ))->add_dependency('use')->add_dependency('unlock')->hide_label(),
            Field::create( 'complex', 'left' )->add_fields(array(
                Field::create( 'number', 'width', __('Border Left','mv23theme') )->set_suffix( 'px' )->set_placeholder('0')->set_width(10),
                Field::create( 'select', 'style' )->add_options(array( 'solid' => 'solid', 'dotted' => 'dotted', 'dashed' => 'dashed', 'double' => 'double', 'groove' => 'groove', 'ridge' => 'ridge', 'inset' => 'inset', 'outset' => 'outset' ))->set_width(20),
                Field::create( 'color', 'color' )->set_width(40),
            ))->add_dependency('use')->add_dependency('unlock')->hide_label(),
        )),
        Field::create( 'complex', 'box_shadow', __('Box Shadow', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use', __('Activate','mv23theme') )->fancy(),
            Field::create( 'repeater', 'box_shadow', '' )->set_add_text(__('Add','mv23theme'))->add_group('Shadow', array(
                'fields' => array(
                    Field::create( 'tab', 'Básico', __('Basic','mv23theme') ),
                    Field::create( 'text', 'h-offset', __('Horizontal Offset','mv23theme') )->set_width(15)->set_suffix( 'px' )->set_default_value('0'),
                    Field::create( 'text', 'v-offset', __('Vertical Offset','mv23theme') )->set_width(15)->set_suffix( 'px' )->set_default_value('0'),
                    Field::create( 'text', 'blur', __('Blur','mv23theme') )->set_width(15)->set_suffix( 'px' )->set_default_value('15'),
                    Field::create( 'color', 'color' )->set_width(30)->set_default_value('#232323'),
                    Field::create( 'tab', 'Avanzado', __('Advanced','mv23theme') ),
                    Field::create( 'number', 'alpha', __('Intensity','mv23theme') )->set_width(50)->enable_slider( 0, 100 )->set_default_value(15)->set_step( 5 ),
                    Field::create( 'select', 'position', __('Position','mv23theme') )->set_width(25)->add_options(array(
                        'outset' => __('Offset','mv23theme'),
                        'inset' => __('Inner','mv23theme')
                    )),
                    Field::create( 'text', 'spread', __('Spread','mv23theme') )->set_width(25)->set_suffix( 'px' )->set_default_value('0')->set_description(__('A positive value increases the size of the shadow, a negative value decreases the size of the shadow', 'mv23theme')),
                )
            ))->add_dependency('use'),
        )),
        Field::create( 'complex', 'visibility', __('Visibility','mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use', __('Activate','mv23theme') )->fancy()->set_width(50),
            Field::create('select', 'key', __('Visibility','mv23theme') )->add_options(array(
                'all' => __('Visible to all users','mv23theme'),
                'user_is_logged_in' => __('Visible for registered users','mv23theme'),
                'user_is_not_logged_in' => __('Visible for non-registered users','mv23theme'),
                'is_private' => __('Visible for admin users','mv23theme')
            ))->add_dependency('use')->set_width(50)
        )),
        Field::create( 'complex', 'responsive', __('Responsive','mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'hide_on_mobile', __('hide_on_mobile','mv23theme') )->fancy()->set_width(30),
            Field::create( 'checkbox', 'hide_on_tablet', __('hide_on_tablet','mv23theme') )->fancy()->set_width(30),
            Field::create( 'checkbox', 'hide_on_desktop', __('hide_on_desktop','mv23theme') )->fancy()->set_width(30)
        ))
    ));  