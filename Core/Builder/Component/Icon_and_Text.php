<?php
namespace Core\Builder\Component;

use Ultimate_Fields\Field;
use Core\Builder\Component;
use Core\Builder\Template_Engine;
use Core\Theme_Options\Theme_Options;

class Icon_and_Text extends Component {

    public function __construct() {
		parent::__construct(
			'icon_and_text',
			__( 'Icon and Text', 'mv23theme' )
		);
	}

    public static function get_icon() {
        return 'dashicons-align-pull-left';
    }

	public static function get_fields() {
		$fields = array(
            Field::create( 'tab', 'Icono'),
        
            Field::create( 'radio', 'ielement','Seleccione que mostrar:')->set_orientation( 'horizontal' )->add_options( array(
                'icono' => 'Icono',
                'imagen' => 'Imagen',
            ))->set_default_value('icono')->set_width(20),
            Field::create( 'icon', 'iname', 'Icono' )
                ->add_set( 'bootstrap-icons' )
                ->add_set( 'font-awesome' )
                ->set_default_value( 'bi-alarm' )
                ->add_dependency('ielement','icono','=')->set_width(20),
            Field::create( 'image', 'iimage', 'Imágen' )->add_dependency('ielement','imagen','=')->set_width(20),

            Field::create( 'image_select', 'iposition', 'Posición')->add_options(array(
                'left'  => array(
                    'label' => 'Izquierda',
                    'image' =>  BUILDER_PATH.'/assets/images/icon-left.png'
                ),
                'top'  => array(
                    'label' => 'Arriba',
                    'image' =>  BUILDER_PATH.'/assets/images/icon-top.png'
                ),
                'right'  => array(
                    'label' => 'Derecha',
                    'image' =>  BUILDER_PATH.'/assets/images/icon-right.png'
                ),
            )),

            // ALIGNMENT
            Field::create( 'select', 'itopalign', __('Icon Alignment', 'mv23theme'))
                ->set_input_type( 'radio' )
                ->set_orientation( 'horizontal' )
                ->add_options(array(
                    'center'  => 'Al Centro',
                    'left'  => 'Izquierda',
                    'right'  => 'Derecha',
                ))->add_dependency('iposition','top','='),
            Field::create( 'select', 'ialign', __('Icon Alignment', 'mv23theme'))
                ->set_input_type( 'radio' )
                ->set_orientation( 'horizontal' )
                ->set_default_value('flex-start')
                ->add_options(array(
                    'center'  => 'Al Centro',
                    'flex-start'  => 'Arriba',
                    'flex-end'  => 'Abajo',
                ))->add_dependency('iposition','top','!='),
            Field::create( 'checkbox', 'hide_icon_on_mobile' )
                ->set_text( __('Hide Icon on Mobile', 'mv23theme') )
                ->hide_label()
                ->fancy(),

            // STYLE
            // Field::create( 'section', 'icon_style_section' ),
            Field::create( 'tab', 'icon_style_tab', __('Icon Style','mv23theme') ),
            Field::create( 'image_select', 'istyle', __('Style','mv23theme'))->set_attr( 'class', 'image-select-5-cols' )->add_options(array(
                'default' => array(
                    'label' => 'Normal',
                    'image' =>  BUILDER_PATH.'/assets/images/icon-default.png'
                ),
                'circle'  => array(
                    'label' => 'Circular',
                    'image' =>  BUILDER_PATH.'/assets/images/icon-circle.png'
                ),
                'circle-outline'  => array(
                    'label' => 'Circular y Lineal',
                    'image' =>  BUILDER_PATH.'/assets/images/icon-circle-outline.png'
                ),
                'square'  => array(
                    'label' => __('Square','mv23theme'),
                    'image' =>  BUILDER_PATH.'/assets/images/icon-square.png'
                ),
                'square-outline'  => array(
                    'label' => __('Square and Lineal','mv23theme'),
                    'image' =>  BUILDER_PATH.'/assets/images/icon-square-outline.png'
                ),
            ))->set_width(30),
            Field::create( 'complex', 'icon_style', __('Settings','mv23theme') )->hide_label()->add_fields(array(
                Field::create( 'number', 'fontsize', 'Tamaño')->set_default_value(40)->set_suffix('px')->set_width(25),
                Field::create( 'color', 'color', 'Color del ícono')->set_width(25),
                Field::create( 'checkbox', 'has_bgc','Activar fondo' )->fancy()
                    ->add_dependency('../istyle',['circle-outline','square-outline'],'IN')
                    ->set_width(25),
                Field::create( 'color', 'bgcolor', 'Color de Fondo')
                    ->set_default_value( Theme_Options::getInstance()->get_property('primary_color') )
                    ->add_dependency('../istyle','circle','=')
                    ->add_dependency_group()
                    ->add_dependency('../istyle',['circle-outline','square-outline'],'IN')
                    ->add_dependency('has_bgc')
                    ->set_width(25),
            )),
    
            // CONTENT
            Field::create( 'tab', 'Texto'),
            Field::create( 'wysiwyg', 'content' )
                ->required()->set_default_value( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' )
                ->hide_label()->set_rows( 10 )->set_width(100),

            // GLOBAL
            Field::create( 'tab', 'Global'),
            Field::create( 'select', 'horizontal_alignment', 'Alineación Horizontal')
                ->set_description( "This setting allows you to align the entire component." )
                ->add_options(array(
                    ''  => __('Default','mv23theme'),
                    'left'  => 'Izquierda',
                    'center'  => 'Al Centro',
                    'right'  => 'Derecha'
                ))->set_width(33),
        );

		return $fields;
	}

    public static function display( $args ){
        if( Template_Engine::is_private( $args ) ) return;
        
		$args['additional_classes'][] = 'component';
        $args['__type'] = 'icon-and-text';

        if (isset($args['iposition'])) $args['additional_classes'][] = 'icon--'.$args['iposition'];
        if (isset($args['center-all']) && $args['center-all'] == 1) $args['additional_classes'][] = 'center-all';

        $has_horizontal_alignment = (isset($args['horizontal_alignment']) && $args['horizontal_alignment'] != '');
        if ($has_horizontal_alignment) $args['additional_classes'][] = $args['horizontal_alignment'].'-all';

        // **************************************************************************************************
        
        if (isset($args['iposition']) && $args['iposition'] != 'top' ){
            $args['additional_styles']['align-items'] = $args['ialign'];
        } 
        
        // **************************************************************************************************
        
        $content = $args['content'];
        $icon_element = $args['ielement'];

        if ($icon_element == 'icono') {
            $icon_prefix = (str_starts_with($args['iname'],'fa')) ? 'fa' : 'bi';
        	$element = '<i class="'.$icon_prefix.' '.$args['iname'].'"></i>';
        } else {
        	$imagen_url = wp_get_attachment_url($args['iimage']);
        	$element = '<img style="height:'.$args['ifontsize'].'px;" src="'.$imagen_url .'" />';
        }

        $icon_style = '';
        $icon_style .= 'font-size:'.$args['icon_style']['fontsize'].'px;';
        if($args['icon_style']['color']) $icon_style .= 'color:'.$args['icon_style']['color'].';';
        $icon_style .= (isset($args['iposition']) && $args['iposition'] == 'top' && isset($args['itopalign']) && $args['itopalign']) ? "text-align:".$args['itopalign'].";" : "text-align:center;";
        $icon_style = ($icon_style) ? 'style="'.$icon_style.'"' : '';

        $classes = array('icon-wrapper');
        if($args['istyle']!='default') array_push($classes, 'icon--'.$args['istyle']);
        if(isset($args['hide_icon_on_mobile']) && $args['hide_icon_on_mobile']) array_push($classes, 'hide-on-small-only');
        $icon_class = (!empty($classes)) ? 'class="'.implode(' ',$classes).'"' : '';

        $hasBackground = false;
        if ($args['istyle'] == 'circle' || $args['istyle'] == 'square') $hasBackground = true;
        if (
            ( $args['istyle'] == 'circle-outline' && $args['icon_style']['has_bgc'] == 1 ) ||
            ( $args['istyle'] == 'square-outline' && $args['icon_style']['has_bgc'] == 1 ) 
        ){
            $hasBackground = true;
        } 
        $ibgc = ($args['icon_style']['has_bgc'] == '') ? Theme_Options::getInstance()->get_property('primary_color') : $args['icon_style']['bgcolor'];
        $backgroundColor = ( $hasBackground ) ? $ibgc : '';
		
		ob_start();
        echo Template_Engine::component_wrapper('start', $args);

        echo '<div '.$icon_class.' '.$icon_style.'>';
	    if ($args['istyle']!='default') { echo '<span style="background-color:'.$backgroundColor.'">'; } else { echo '<span>'; };
	    echo $element;
	    echo '</span>';
	    echo '</div>';

        if($content) echo '<div class="content-wrapper">'.do_shortcode(wpautop($content)).'</div>';

        echo Template_Engine::component_wrapper('end', $args);
		return ob_get_clean();
	}

    public static function get_view_template() {
        return '
        <% 
        cmp_classes = ["icon-and-text","icon--"+iposition];
        if( horizontal_alignment && horizontal_alignment != "" ) cmp_classes.push(horizontal_alignment+"-all");

        cmp_styles = [] 
        if(iposition != "top") cmp_styles.push("align-items:"+ialign);
        %>
        <div class="<%= cmp_classes.join(" ") %>" style="<%= cmp_styles.join(";") %>">
            <% 
            icon_wrapper_classes = ["icon-wrapper"];
            if (istyle != "default") icon_wrapper_classes.push("icon--"+istyle);
            if (hide_icon_on_mobile) icon_wrapper_classes.push("hide-on-small-only");

            icon_styles = [] 
            icon_styles.push("font-size:"+icon_style.fontsize+"px");
            icon_styles.push("text-align:"+itopalign);
            if (icon_style.color) icon_styles.push("color:"+icon_style.color);
            %>
            <div class="<%= icon_wrapper_classes.join(" ") %>" style="<%= icon_styles.join(";") %>">
                <%
                span_wrapper_styles = [];
                if ( (istyle == "circle" || istyle == "square") || 
                     (istyle == "circle-outline" && icon_style.has_bgc) || 
                     (istyle == "square-outline" && icon_style.has_bgc) ) {
                    span_wrapper_styles.push("background-color:" + icon_style.bgcolor);
                }
                %>
                <span style="<%= span_wrapper_styles.join(";") %>">
                    <% if (ielement == "icono") { 
                        icon_exists = (typeof iname === "string" && iname.length > 0);
                        icon_prefix = ( icon_exists && iname.startsWith("fa")) ? "fa" : "bi";
                        %>
                        <i class="<%= icon_prefix %> <%= iname %>"></i>
                    <% } else { 
                        img_prepared_exists = (typeof iimage_prepared !== "undefined" && Array.isArray(iimage_prepared) && iimage_prepared.length > 0);
                        image_url = img_prepared_exists ? iimage_prepared[0].url : "";
                        %>
                        <img src="<%= image_url %>" />
                    <% } %>
                </span>
            </div>
            <div class="content-wrapper"><%= content %></div>
        </div>
        ';
    }
}

new Icon_and_Text();