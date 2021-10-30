<?php
use Ultimate_Fields\Custom_Widget;
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

/**
 * Widgets in functions php
 */
// add_action( 'uf.init', 'showcase_include_widgets' );
// function showcase_include_widgets() {
//     require_once __DIR__ . '/inc/widgets/Icon_And_Text_Widget.php'; 
//     add_action( 'widgets_init', 'showcase_register_widgets' );
// }

// function showcase_register_widgets() {
//     register_widget( 'Icon_And_Text_Widget' );
// }

class Icon_And_Text_Widget extends Custom_Widget {
	public function __construct() {
		parent::__construct( 'icon_and_text_widget', 'Icono y Texto', array( 
			'classname'   => 'icon-and-text-widget',
			'description' => 'Muestra texto con un ícono',
		));

		Container::create( 'icon_and_text_widget' )
			->add_location( 'widget', 'Icon_And_Text_Widget' )
			->add_fields(array(
				Field::create( 'tab', 'Icono'),
			    Field::create( 'icon', 'iname', 'Icono' )->add_set( 'font-awesome' ),
			    Field::create( 'number', 'ifontsize', 'Tamaño')->set_width(20)->set_default_value(40),
			    Field::create( 'color', 'icolor', 'Color del ícono')->set_width(20),
			    Field::create( 'select', 'ialign', 'Alineación')->add_options( array(
			        'flex-start' => 'Arriba',
			        'center' => 'Centro',
			        'flex-end' => 'Abajo'
			    ))->set_width(20),
			    Field::create( 'select', 'istyle', 'Estilo')->add_options( array(
			        'default' => 'Normal',
			        'circle' => 'Circular',
			        'circle-outline' => 'Circular y Lineal',
			    ))->set_width(20),
			    Field::create( 'color', 'ibgc', 'Color de Fondo')->set_width(50)->add_dependency('style','default','!='),
			    Field::create( 'number', 'ibgc_alpha', 'Transparencia del fondo' )->set_width(50)->enable_slider( 0, 1 )->set_default_value(1)->set_step( 0.1 )->add_dependency('style','default','!='),
			    Field::create( 'complex', 'ienlace' )->rows_layout()->add_fields(array(
			        Field::create( 'radio', 'url_type','Seleccione que contenido se abrirá al hacer clic:')->set_orientation( 'horizontal' )->add_options( array(
			                '' => 'Desactivar',
			                'interna' => 'Página Interna',
			                'externa' => 'Página Externa',
			        )),
			        Field::create( 'wp_object', 'post', 'URL Interna' )->add( 'posts' )->set_button_text( 'Selecciona la página' )->add_dependency('url_type','interna','='),
			        Field::create( 'text', 'url', 'URL Externa' )->add_dependency('url_type','externa','='),
			        Field::create( 'checkbox', 'new_tab' )->set_text( 'Abrir en una nueva ventana.' )->hide_label(),
			    ))->hide_label(),
			    Field::create( 'tab', 'Texto' ),
			    Field::create( 'wysiwyg', 'content' )->hide_label()->set_rows( 20 ),
			));
	}

	public function widget( $args, $instance ) {
		$content = get_value( 'content', 'widget' );
		if( empty($content) ) {
			return;
		}
		$ifontsize = get_value( 'ifontsize', 'widget' );
		$ialign = get_value( 'ialign', 'widget' );
		$icolor = get_value( 'icolor', 'widget' );
		$istyle = get_value( 'istyle', 'widget' );
		$iname = get_value( 'iname', 'widget' );
		$ibgc_alpha = get_value( 'ibgc_alpha', 'widget' );
		$ibgc = get_value( 'ibgc', 'widget' );
		$ienlace = get_value( 'ienlace', 'widget' );

		$icon_style = '';
		$icon_style = 'font-size:'.$ifontsize.'px;';
		if ($ialign && $ialign != 'start') $icon_style .= "align-items:".$ialign.";";
		if($icolor) $icon_style .= 'color:'.$icolor.';';
		$icon_style = ($icon_style) ? 'style="'.$icon_style.'"' : '';

		$classes = array('icon');
		if($istyle!='default') array_push($classes, 'icon--'.$istyle);
		$icon_class = (!empty($classes)) ? 'class="'.implode(' ',$classes).'"' : '';

		$backgroundColor = ( $ibgc_alpha != '' ) ? 'rgba('.hexToRgb($ibgc,$ibgc_alpha).')' : $ibgc;

		$link = NULL;
		$enlace = $ienlace;
		switch ($enlace['url_type']) {
		    case 'externa':
		        $link = $enlace['url'];
		        break;
		    
		    case 'interna':
		        $link = get_permalink( str_replace('post_','',$enlace['post']) );
		        break;
		}
		$target = ($enlace['new_tab'] == 1) ? '_blank' : '';
		echo $args['before_widget']; ?>
		<div class="icon-and-text">
			<div <?=$icon_class?> <?=$icon_style?>>
				<?php if ($iname): ?>
					<?php if ($istyle!='default') echo '<span style="background-color:'.$backgroundColor.'">'; ?>
					<i class="fa <?php echo $iname; ?>"></i>
					<?php if ($istyle!='default') echo '</span>'; ?>
					<?php if ($link != NULL) echo '<a href="'.$link.'" target="'.$target.'"></a>'; ?>
				<?php endif ?>
			</div>
			<div><?php if($content) echo do_shortcode(wpautop($content)); ?></div>
		</div>
		<?php echo $args['after_widget'];
	}
}