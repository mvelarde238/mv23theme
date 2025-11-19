<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;

class Heading extends Component {

    public function __construct() {
		parent::__construct(
			'heading',
			__( 'Heading', 'mv23theme' )
		);
	}

	public static function get_icon() {
        return 'dashicons-heading';
    }

	public static function get_fields() {

        $preset_styles = apply_filters(
            'filter_preset_styles_for_heading_component',
            array(
                'default' => array(
                    'label' => 'Default',
                    'image' => BUILDER_PATH . '/assets/images/headings/default.png'
                ),
                'style1'  => array(
                    'label' => 'Style 1',
                    'image' => BUILDER_PATH . '/assets/images/headings/style1.png'
                ),
                'style2'  => array(
                    'label' => 'Style 2',
                    'image' => BUILDER_PATH . '/assets/images/headings/style2.png'
                ),
                'style3'  => array(
                    'label' => 'Style 3',
                    'image' => BUILDER_PATH . '/assets/images/headings/style3.png'
                ),
                'style4'  => array(
                    'label' => 'Style 4',
                    'image' => BUILDER_PATH . '/assets/images/headings/style4.png'
                ),
                'style5'  => array(
                    'label' => 'Style 5',
                    'image' => BUILDER_PATH . '/assets/images/headings/style5.png'
                ),
                'style6'  => array(
                    'label' => 'Style 6',
                    'image' => BUILDER_PATH . '/assets/images/headings/style6.png'
                ),
                'style7'  => array(
                    'label' => 'Style 7',
                    'image' => BUILDER_PATH . '/assets/images/headings/style7.png'
                ),
                'style8'  => array(
                    'label' => 'Style 8',
                    'image' => BUILDER_PATH . '/assets/images/headings/style8.png'
                ),
                'style9'  => array(
                    'label' => 'Style 9',
                    'image' => BUILDER_PATH . '/assets/images/headings/style9.png'
                )
            )
        );

		$fields = array(
            Field::create( 'tab', __( 'Heading', 'mv23theme' ) ),
			Field::create( 'complex', 'heading' )->hide_label()
                ->add_fields( array(
                    Field::create( 'textarea', 'content', __( 'Content', 'mv23theme' ) )
                        ->set_rows( 5 )
                        ->set_default_value( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit' )
                        ->required()
                        ->hide_label(),
                    Field::create( 'select', 'html_tag', __( 'HTML Tag', 'mv23theme' ) )
                        ->add_options( array(
                            'h1' => __( 'H1', 'mv23theme' ),
                            'h2' => __( 'H2', 'mv23theme' ),
                            'h3' => __( 'H3', 'mv23theme' ),
                            'h4' => __( 'H4', 'mv23theme' ),
                            'h5' => __( 'H5', 'mv23theme' ),
                            'h6' => __( 'H6', 'mv23theme' )
                        ) )
                        ->set_default_value( 'h2' )
                        ->set_prefix( 'HTML Tag:' )
                        ->hide_label()
                ) ),

            Field::create( 'radio', 'text_align' )
                ->hide_label()
                ->set_prefix( __( 'Alignment', 'mv23theme' ) )
                ->set_orientation( 'horizontal' )
                ->add_options( array(
                    'left' => __( 'Left', 'mv23theme' ),
                    'center' => __( 'Center', 'mv23theme' ),
                    'right' => __( 'Right', 'mv23theme' )
                ) )
                ->set_default_value( 'center' ),

            Field::create( 'tab', 'Tagline'),
            Field::create( 'checkbox', 'add_tagline' )
                ->fancy()
                ->set_text(__('Add Tagline','mv23theme'))
                ->hide_label(),

            Field::create( 'complex', 'tagline' )
                ->hide_label()
                ->add_fields( array(
                    Field::create( 'text', 'content', __( 'Content', 'mv23theme' ) )
                        ->hide_label(),
                    Field::create( 'select', 'html_tag', __( 'HTML Tag', 'mv23theme' ) )
                        ->add_options( array(
                            'p' => __( 'Paragraph', 'mv23theme' ),
                            'span' => __( 'Span', 'mv23theme' ),
                            'div' => __( 'Div', 'mv23theme' )
                        ) )
                        ->set_default_value( 'p' )
                        ->set_prefix( 'HTML Tag:' )
                        ->hide_label()
                ) )->add_dependency( 'add_tagline' ),

            Field::create( 'radio', 'tagline_position' )
                ->hide_label()
                ->set_prefix( __( 'Tagline Position', 'mv23theme' ) )
                ->set_orientation( 'horizontal' )
                ->add_options( array(
                    'before' => __( 'Before Heading', 'mv23theme' ),
                    'after' => __( 'After Heading', 'mv23theme' )
                ) )
                ->set_default_value( 'after' )
                ->add_dependency( 'add_tagline' ),

            Field::create( 'tab', 'style', __( 'Style', 'mv23theme' ) ),
            Field::create( 'image_select', 'preset', __('Preset Style', 'mv23theme') )
                ->add_options($preset_styles)
                ->hide_label()
                ->set_default_value('style1')
                ->set_attr( 'class', 'image-select-2-cols' ),
            Field::create( 'complex', 'accent_color', __( 'Accent Color', 'mv23theme' ) )
                ->add_fields( array(
                    Field::create( 'text', 'color_variable' )
                        ->set_placeholder( 'currentColor' )
                        ->add_suggestions( array('currentColor', '--primary-color', '--secondary-color'))
                        ->add_dependency( 'use_color', 0 )
                        ->hide_label()
                        ->set_width( 50 ),
                    Field::create( 'checkbox', 'use_color', __( 'Use Color', 'mv23theme' ) )
                        ->fancy()
                        ->set_text( __( 'Use custom color', 'mv23theme' ) )
                        ->hide_label()
                        ->set_width( 50 ),
                    Field::create( 'color', 'color', __( 'Color', 'mv23theme' ) )
                        ->add_dependency( 'use_color' )
                        ->hide_label()
                        ->set_width( 50 )
                ) )->add_dependency( 'preset', 'default', '!=' ),
            Field::create( 'radio', 'highlighted_element', __( 'Highlighted Element', 'mv23theme' ) )
                ->set_orientation( 'horizontal' )
                ->add_options( array(
                    'heading' => __( 'Heading', 'mv23theme' ),
                    'tagline' => __( 'Tagline', 'mv23theme' )
                ) )
                ->set_default_value( 'heading' )
                ->add_dependency( 'add_tagline' )
                ->add_dependency( 'preset', 'default', '!=' )
		);

		return $fields;
	}

	public static function display( $args ){
		if( Template_Engine::is_private( $args ) ) return;

		$args['additional_classes'] = array('component');

        // content
        $heading_content = $args['heading']['content'] ?? '';
        $heading_html_tag = $args['heading']['html_tag'] ?? 'h2';
        $tagline_content = $args['tagline']['content'] ?? '';
        $tagline_html_tag = $args['tagline']['html_tag'] ?? 'p';

        // additional classes
        $text_align = $args['text_align'] ?? 'left';
        $args['additional_classes'][] = $text_align . '-align';

        $preset = $args['preset'] ?? 'default'; 
        $args['additional_classes'][] = 'heading--' . $preset;

        // add accent color to additional styles
        $accent_color = $args['accent_color'] ?? array('use_color' => false, 'color' => '', 'color_variable' => '');
        if( $accent_color['use_color'] && !empty($accent_color['color']) ) {
            $args['additional_styles']['--accent-color'] = $accent_color['color'];
        } else {
            if( !empty($accent_color['color_variable']) ) {
                $maybe_color_variable = $accent_color['color_variable'];
                if( str_starts_with($maybe_color_variable, '--') ) {
                    $args['additional_styles']['--accent-color'] = 'var(' . $maybe_color_variable . ')';
                } else { // if it does not start with '--', assume it's a color value
                    $args['additional_styles']['--accent-color'] = $maybe_color_variable;
                }
            }
        }

        // highlighted element
        $highlighted_element = $args['highlighted_element'] ?? 'heading';
        $heading_classes = array('heading__text');
        $tagline_classes = array('heading__tagline');
        if( $highlighted_element == 'heading' ) {
            $heading_classes[] = 'highlighted';
        } elseif( $highlighted_element == 'tagline' ) {
            $tagline_classes[] = 'highlighted';
        }

        // prepare content
        $contents = array(
            'heading' => array(
                'content' => $heading_content,
                'html_tag' => $heading_html_tag,
                'classes' => $heading_classes,
                'settings' => $args['heading']['settings'] ?? array()
            ),
            'tagline' => array(
                'content' => $tagline_content,
                'html_tag' => $tagline_html_tag,
                'classes' => $tagline_classes,
                'settings' => $args['tagline']['settings'] ?? array()
            )
        );
        $keys = ['heading', 'tagline'];
        $wrapped_presets = array('style7', 'style8');

        // if tagline position is before, swap the keys
        $tagline_position = $args['tagline_position'] ?? 'after';
        if( $tagline_position == 'before' ) {
            $keys = ['tagline', 'heading'];
        }

        // add tagline
        $add_tagline = $args['add_tagline'] ?? false;
        if( !$add_tagline ) {
            unset($contents['tagline']);
            $keys = ['heading'];
        }

		ob_start();
		echo Template_Engine::component_wrapper('start', $args);
        foreach( $keys as $key ) {
            if( !empty($contents[$key]['content']) ) {

                // if the preset is wrapped, wrap the content in a span
                if( in_array($preset, $wrapped_presets) ) {
                    $contents[$key]['content'] = '<span>' . $contents[$key]['content'] . '</span>';
                }

                printf(
                    '<%1$s %2$s>%3$s</%1$s>',
                    esc_attr($contents[$key]['html_tag']),
                    Template_Engine::generate_attributes( $contents[$key] ),
                    nl2br($contents[$key]['content'])
                );
            }
        }
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}

    public static function get_view_template() {
        ob_start(); 

        printf(
            '<div class="heading %s %s" style="%s;">',
            '<%= text_align %>-align',
            'heading--<%= preset %>',
            '<% if( accent_color.use_color ){ %>'.
                '--accent-color: <%= accent_color.color %>'.
            '<% } else { %>'.
                '<% if( accent_color.color_variable ) { %>'.
                    '<% if( accent_color.color_variable.startsWith("--") ) { %>'.
                        '--accent-color: var(<%= accent_color.color_variable %>)'.
                    '<% } else { %>'.
                        '--accent-color: <%= accent_color.color_variable %>'.
                    '<% } %>'.
                '<% } %>'.
            '<% } %>'
        );

        printf(
            '<%s class="heading__text %s">%s</%s>',
            '<%= heading.html_tag %>',
            '<% if( highlighted_element == "heading" ) { %>highlighted <% } %>',
            '<span><%= heading.content %></span>',
            '<%= heading.html_tag %>'
        );

        echo '<% if(add_tagline){ %>';
        printf(
            '<%s class="heading__tagline %s" data-gjs-editable="true">%s</%s>',
            '<%= tagline.html_tag %>',
            '<% if( highlighted_element == "tagline" ) { %>highlighted<% } %>',
            '<span><%= tagline.content %></span>',
            '<%= tagline.html_tag %>'
        );
        echo '<% } %>';

        echo '</div>';
        
        return ob_get_clean();
    }
}

new Heading();