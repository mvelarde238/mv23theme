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
            Field::create( 'tab', __( 'Content', 'mv23theme' ) ),
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

            Field::create( 'select', 'text_align' )
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
                    Field::create( 'textarea', 'content', __( 'Content', 'mv23theme' ) )
                        ->set_rows( 2 )
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

            Field::create( 'select', 'tagline_position' )
                ->hide_label()
                ->set_prefix( __( 'Tagline Position', 'mv23theme' ) )
                ->set_orientation( 'horizontal' )
                ->add_options( array(
                    'before' => __( 'Before Heading', 'mv23theme' ),
                    'after' => __( 'After Heading', 'mv23theme' )
                ) )
                ->set_default_value( 'after' )
                ->add_dependency( 'add_tagline' ),

            Field::create( 'tab', 'preset_styles', __( 'Preset styles', 'mv23theme' ) ),
            Field::create( 'image_select', 'preset', __('Preset Style', 'mv23theme') )
                ->add_options($preset_styles)
                ->hide_label()
                ->set_default_value('style1'),
                // ->set_attr( 'class', 'image-select-2-cols' ),

            Field::create( 'tab', 'preset_settings', __( 'Preset settings', 'mv23theme' ) ),
            Field::create( 'radio', 'highlighted_element', __( 'Highlighted Element', 'mv23theme' ) )
                ->set_orientation( 'horizontal' )
                ->add_options( array(
                    'heading' => __( 'Heading', 'mv23theme' ),
                    'tagline' => __( 'Tagline', 'mv23theme' )
                ) )
                ->set_default_value( 'heading' )
                ->add_dependency( 'add_tagline' )
                ->add_dependency( 'preset', 'default', '!=' ),
            Field::create( 'complex', 'accent_color', __( 'Accent Color', 'mv23theme' ) )
                ->add_fields( array(
                    Field::create( 'text', 'color_variable' )
                        ->set_placeholder( 'currentColor' )
                        ->add_suggestions( array('currentColor', '--primary-color', '--secondary-color', 'Use ColorPicker') )
                        ->hide_label(),
                    Field::create( 'color', 'color', __( 'Color', 'mv23theme' ) )
                        ->add_dependency( 'color_variable', 'Use ColorPicker' )
                        ->hide_label()
                ) )->add_dependency( 'preset', 'default', '!=' )
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
        $accent_color = $args['accent_color'] ?? array('color' => '', 'color_variable' => '');
        if( $accent_color['color_variable'] == 'Use ColorPicker' ) {
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
                    Template_Engine::handle_placeholders(nl2br($contents[$key]['content']))
                );
            }
        }
		echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}

    public static function get_view_template() {
        return '<%
        h_tag = heading.html_tag;
        h_text = __handlePlhs(heading.content);
        h_cls = ["heading__text"];
        if( highlighted_element == "heading" ){
            h_cls.push("highlighted");
        }
        h_html = "<" + h_tag + " class=\\"" + h_cls.join(" ") + "\\"><span>" + h_text + "</span></" + h_tag + ">";

        t_tag = tagline.html_tag;
        t_text = __handlePlhs(tagline.content);
        t_cls = ["heading__tagline"];
        if( highlighted_element == "tagline" ){
            t_cls.push("highlighted");
        }
        t_html = "<" + t_tag + " class=\\"" + t_cls.join(" ") + "\\"><span>" + t_text + "</span></" + t_tag + ">";
        if( !add_tagline ){
            t_html = "";
        }

        cmp_cls = ["heading", "heading--" + preset, text_align + "-align"];
        cmp_style = "";
        if( accent_color.color_variable == "Use ColorPicker" ){
            cmp_style += "--accent-color: " + accent_color.color + ";";
        } else {
            if( accent_color.color_variable ) {
                if( accent_color.color_variable.startsWith("--") ) {
                    cmp_style += "--accent-color: var(" + accent_color.color_variable + ");";
                } else {
                    cmp_style += "--accent-color: " + accent_color.color_variable + ";";
                }
            }
        }

        cmp_content = [h_html, t_html];
        if( tagline_position == "before" ){
            cmp_content = [t_html, h_html];
        }
        %>
        <div class="<%= cmp_cls.join(" ") %>" style="<%= cmp_style %>">
            <%= cmp_content.join("") %>
        </div>';
    }
}

new Heading();