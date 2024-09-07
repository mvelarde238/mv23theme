<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Theme_Custom_Fields\Core;

class Theme_Options_Fields {
    public static function get_logos_fields(){
        $fields = array(
            Field::create( 'tab', 'logos' )
        );
        $_count = 0;
        
        foreach ( Core::getInstance()->get_logos_field_names() as $key => $value) {
            if( $_count < 2 ) $fields[] = Field::create( 'image', $key, $value )->set_width(25);
            $_count++;
        }

        return $fields;
    }

    public static function get_color_fields(){
        $colors_width = ( is_customize_preview() ) ? 100 : 25;

        $fields = array(
            Field::create( 'tab', 'Colors' ),
            
            Field::create( 'complex', 'colors_wrapper', __('Main colors','default') )->add_fields(array(
                Field::create( 'color', 'primary_color' )->set_default_value('#ff7a00')->set_width($colors_width),
                Field::create( 'color', 'secondary_color' )->set_default_value('#071a36')->set_width($colors_width),
                Field::create( 'color', 'font_color' )->set_width($colors_width),
                Field::create( 'color', 'headings_color' )->set_width($colors_width)
            ))->merge(),

            Field::create( 'repeater', 'colorpicker_palette' )
                ->set_add_text(__('Add color','default'))
                ->set_layout( 'table' )
                ->add_group( 'item', array(
                    'fields' => array(
                        Field::create( 'color', 'color' )
                    )
            )),
                
            Field::create( 'complex', 'primary_color_variations' )->add_fields(array(
                Field::create( 'number', 'light_primary_color_percentage', __('Light', 'default') )
                    ->set_placeholder('0')
                    ->set_default_value(50)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'number', 'lighter_primary_color_percentage', __('Lighter', 'default') )
                    ->set_placeholder('0')
                    ->set_default_value(85)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'number', 'dark_primary_color_percentage', __('Dark', 'default') )
                    ->set_placeholder('0')
                    ->set_default_value(15)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
            ))->merge(),

            Field::create( 'complex', 'secondary_color_variations' )->add_fields(array(
                Field::create( 'number', 'light_secondary_color_percentage', __('Light', 'default') )
                    ->set_placeholder('0')
                    ->set_default_value(50)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'number', 'lighter_secondary_color_percentage', __('Lighter', 'default') )
                    ->set_placeholder('0')
                    ->set_default_value(85)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'number', 'dark_secondary_color_percentage', __('Dark', 'default') )
                    ->set_placeholder('0')
                    ->set_default_value(15)
                    ->set_suffix('%')
                    ->set_attr('style','width:30%; min-width:50px;'),
            ))->merge()
        );
        return $fields;
    }

    public static function get_typography_fields(){
        $font_size_rule = '^(?:(\d+(\.\d+)?(px|em|rem|vw|vh|%)|xx-small|x-small|small|medium|large|x-large|xx-large|smaller|larger))$';
        $line_height_rule = '^(?:(\d+(\.\d+)?(px|em|rem|vw|vh|%)?|normal))$';
        $font_weight_rule = '^(?:(normal|bold|bolder|lighter|[1-9]00))$';
        $fonts_width = ( is_customize_preview() ) ? 100 : 50;
        $google_api_key = ( defined('MV23_GOOGLE_API_KEY') ) ? MV23_GOOGLE_API_KEY : '';

        $fields = array(
            Field::create( 'tab', 'typography' ),
            
            Field::create( 'complex', 'fonts_wrapper', __('Typography','default') )->merge()
                ->set_description(__('Headings font will be used for titles and subtitles (h1, h2, h3, h4, h5, h6)','default'))->add_fields(array(
                Field::create( 'font', 'general_font' )->set_api_key( $google_api_key )->set_width($fonts_width),
                Field::create( 'font', 'headings_font' )->set_api_key( $google_api_key )->set_width($fonts_width),
            )),
            
            Field::create( 'complex', 'paragraph', __('Paragraphs','default') )->add_fields(array(
                Field::create( 'text', 'font_size' )->set_validation_rule($font_size_rule)->set_placeholder('16px')->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'text', 'line_height' )->set_validation_rule($line_height_rule)->set_placeholder('180%')->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'text', 'font_weight' )->set_validation_rule($font_weight_rule)->set_placeholder('300')->set_attr('style','width:30%; min-width:50px;')
            ))
        );

        $heading_fields = array();
        $headings = array('h1','h2','h3','h4','h5','h6');
        $heading_default_sizes = array('28px', '26px', '24px', '22px', '20px', '18px');
        $count = 0;
        foreach ($headings as $heading) {
            $placeholder = $heading_default_sizes[$count];
            $heading_fields[] = Field::create( 'complex', $heading.'_heading' )->add_fields(array(
                Field::create( 'text', 'font_size' )->set_validation_rule($font_size_rule)->set_placeholder($placeholder)->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'text', 'line_height' )->set_validation_rule($line_height_rule)->set_placeholder('140%')->set_attr('style','width:30%; min-width:50px;'),
                Field::create( 'text', 'font_weight' )->set_validation_rule($font_weight_rule)->set_placeholder('700')->set_attr('style','width:30%; min-width:50px;')
            ));
            $count++;
        }

        $hints_field = array(Field::create( 'complex', 'hints' )->add_fields(array(
            Field::create( 'message', 'font_size_hint' )->set_description('Valid values for font size: 12px, 1.5em, 2rem, 120%, small, medium, etc','default')->hide_label(),
            Field::create( 'message', 'line_height_hint' )->set_description('Valid values for line_height: 1.5, 2, ..., 20px, 2em, 150%, normal, etc','default')->hide_label(), 
            Field::create( 'message', 'font_weight_hint' )->set_description('Valid values for font weight: normal, bold, bolder, lighter, 100, 200, ..., 900','default')->hide_label()
        )));

        return array_merge( $fields, $heading_fields, $hints_field );
    }

    public static function get_header_fields($key){
        $fields = array(
            Field::create('tab', $key.'_header'),
            Field::create('complex', $key.'_header_logo_wrapper', __('Logo','default'))->merge()->add_fields(array(
                Field::create('select', $key.'_header_logo', __('Select','default'))->add_options( Core::getInstance()->get_logos_field_names() )->set_width(50),
                Field::create('image', 'custom_'.$key.'_header_logo', __('Select image','default'))->add_dependency($key.'_header_logo', 'custom', '=')->set_width(50),
            )),
            Field::create('number', $key.'_header_logo_height', __('Logo Height','default'))->set_placeholder(60)->set_suffix('px'),
            Field::create('complex', $key.'_header_bgc', __('Background Color','default'))->add_fields(array(
                Field::create('checkbox', 'add_bgc', __('Use','default'))->fancy()->set_width(25),
                Field::create('color', 'bgc', 'Color')->add_dependency('add_bgc')->set_width(50),
                Field::create( 'number', 'alpha', __('Opacity','default') )
                    ->add_dependency('add_bgc')
                    ->set_placeholder('0')
                    ->enable_slider(0,100,1)
                    ->set_default_value(100)
                    ->set_width( 25 )
            )),
            Field::create('select', $key.'_header_color_scheme', __('Text Color','default'))->add_options(array(
                '' => 'Select',
                'text-color-1' => 'Default Scheme',
                'text-color-2' => 'Dark Scheme'
            ))->set_default_value(DEFAULT_TEXT_COLOR),
        );

        return $fields;
    }

    public static function get_container_fields(){
        $fields = array(
            Field::create( 'tab', __('Container','default') ),

            Field::create( 'repeater', 'containers_width' )
                ->set_add_text(__('Add rule','default'))
                ->hide_label()
                // ->set_layout( 'table' )
                ->add_group( 'item', array(
                    'title_template' => '<% if( scope != "custom" ){ %>
                        .<%= scope %>: <%= width %>px
                    <% } else { %>
                        <%= selector %>: <%= width %>px
                    <% } %>',
                    'fields' => array(
                        Field::create( 'select', 'scope' )->add_options(array(
                            'global' => 'Global',
                            'header' => 'Header',
                            'footer' => 'Footer',
                            'single' => 'Single',
                            'page' => 'Page',
                            'archive' => 'Archive',
                            'blog' => 'Blog',
                            'custom' => 'Custom'
                        ))->set_width(30),
                        Field::create( 'text', 'selector' )->add_dependency('scope','custom')->set_width(30),
                        Field::create( 'number', 'width' )->set_suffix('px')->set_placeholder('1240')->required()->set_width(30)
                    )
            ))
        );
        return $fields;
    }

    public static function get_pages_fields(){
        # post types
        $post_types   = array();
        $excluded = array( 'attachment', 'page' );
		foreach( get_post_types( array('public'=>true), 'objects' ) as $id => $post_type ) {
			if( in_array( $id, $excluded ) ) {
				continue;
			}
			$post_types[ $id ] = __( $post_type->labels->name );
		}
        // hardcoded posttype:
        if(USE_PORTFOLIO_CPT) $post_types['portfolio'] = 'Portfolio';

        # taxonomies
        $taxonomies = array();
        $excluded_tax = array( 'link_category', 'wp_pattern_category' );
		foreach( get_taxonomies( array( 'show_ui' => true ), 'objects' ) as $slug => $taxonomy ) {
            if( in_array( $slug, $excluded_tax ) ) {
				continue;
			}
            $taxonomies[$slug] = $taxonomy->labels->name;
		}
        // hardcoded taxonomies
        if(USE_PORTFOLIO_CPT) {
            $taxonomies['portfolio-cat'] = 'Portfolio Category';
            $taxonomies['portfolio-tag'] = 'Portfolio Tag';
        }

        $fields = array(
            Field::create( 'tab', __('Pages','default') ),

            Field::create( 'repeater', 'pages_settings' )
                ->set_chooser_type( 'tags' )
                ->set_add_text(__('Add rule','default'))
                ->hide_label()
                ->add_group( 'single', array(
                    'title_template' => '<% if( hide_sidebar ){ %>
                        Hide Sidebar in pages whose type is: <%= post_types.join(", ") %>
                     <% } else { %>
                        Use <%= page_template %> template in pages whose type is: <%= post_types.join(", ") %>
                     <% } %>',
                    'edit_mode' => 'popup',
                    'fields' => array(
                        Field::create( 'multiselect', 'post_types', __( 'Post Types', 'default' ) )
                            ->required()
			                ->add_options( $post_types )
                            ->set_orientation( 'horizontal' )
			                ->set_input_type( 'checkbox' )->set_width(20),
                        Field::create( 'select', 'page_template' )->add_options(array(
                            'main-content--sidebar-right' => __('Sidebar Right','deafult'),
                            'main-content--sidebar-left' => __('Sidebar Left','deafult')
                        ))->add_dependency('hide_sidebar',0)->set_width(20),
                        Field::create( 'checkbox', 'hide_sidebar' )->fancy()->set_width(20),
                    )
                ))
                ->add_group( 'archive', array(
                    'title_template' => '<% if( hide_sidebar ){ %>
                        Hide Sidebar in archive pages for: <%= post_types.concat(taxonomies).join(", ") %>
                     <% } else { %>
                        Use <%= page_template %> template in archive pages for: <%= post_types.concat(taxonomies).join(", ") %>
                     <% } %>',
                    'edit_mode' => 'popup',
                    'fields' => array(
                        Field::create( 'multiselect', 'post_types', __( 'Post Types', 'default' ) )
			                ->add_options( $post_types )
                            ->set_orientation( 'horizontal' )
			                ->set_input_type( 'checkbox' )->set_width(20),
                        Field::create( 'multiselect', 'taxonomies', __( 'Taxonomies', 'default' ) )
			                ->add_options( $taxonomies )
                            ->set_orientation( 'horizontal' )
			                ->set_input_type( 'checkbox' )->set_width(20),
                        Field::create( 'select', 'page_template' )->add_options(array(
                            'main-content--sidebar-left' => __('Sidebar Left','deafult'),
                            'main-content--sidebar-right' => __('Sidebar Right','deafult')
                        ))->add_dependency('hide_sidebar',0)->set_width(20),
                        Field::create( 'checkbox', 'hide_sidebar' )->fancy()->set_width(20),
                    )
                ))
        );
        return $fields;
    }
}

Container::create( 'theme_options' )
    ->set_title( __('Theme Options','default') )
    ->set_description_position('label')
    ->add_location( 'options', 'theme-options' )
    ->add_location( 'customizer', array(
        'postmessage_fields' => array( 'colors_wrapper', 'primary_color_variations', 'secondary_color_variations', 'paragraph', 'h1_heading', 'h2_heading', 'h3_heading', 'h4_heading', 'h5_heading', 'h6_heading', 'static_header_logo_height', 'sticky_header_logo_height', 'static_header_bgc', 'sticky_header_bgc', 'containers_width' )
    ))
    ->add_fields( Theme_Options_Fields::get_logos_fields() )
    ->add_fields( Theme_Options_Fields::get_color_fields() )
    ->add_fields( Theme_Options_Fields::get_typography_fields() )
    ->add_fields( Theme_Options_Fields::get_header_fields('static') )
    ->add_fields( Theme_Options_Fields::get_header_fields('sticky') )
    ->add_fields( Theme_Options_Fields::get_container_fields() )
    ->add_fields( Theme_Options_Fields::get_pages_fields() );
