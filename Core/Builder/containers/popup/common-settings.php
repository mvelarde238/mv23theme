<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'common_settings_container' ) 
    ->add_location( 'post_type', UF_POSTTYPES )
    ->set_layout( 'rows' )
    ->add_fields(array(
        Field::create( 'complex', 'main_attributes' )->add_fields(array(
            Field::create( 'text', 'id', __('ID','mv23theme') )
                ->set_validation_rule('^[a-z][a-za-z0-9_-]+$')
                ->set_description( 'Identificador -ID- de la sección, usar solo minúsculas y guiones ( - )' )
                ->set_width( 50 ),
            Field::create( 'text', 'class', __('Class','mv23theme') )
                // ->add_datalist( array( 
                    // 'disable-link-to-embed-conversion',
                    // 'overflow-scroll',
                    // 'overflow-hidden',
                // ))
                ->set_description( 'Clases de la sección, usar solo minúsculas y guiones ( -/_ )' )
                ->set_width( 50 )
        ))->merge(),
        Field::create( 'complex', 'layout', __('Layout','mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use' )->fancy()->set_width(50),
            Field::create( 'select', 'key', __('Layout','mv23theme'))->add_options( array(
                'layout1' => 'Estándar',
                'layout2' => 'Fondo extendido / Contenido centrado',
                'layout3' => 'Todo extendido'
            ))->add_dependency('use')->set_width( 50 )
        )),
        Field::create( 'complex', 'helpers', __('Helpers','mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use' )->fancy()->set_width(50),
            Field::create( 'multiselect', 'list')
                ->hide_label()
                ->set_input_type( 'checkbox' )
                ->set_orientation( 'horizontal' )
                ->add_options( array(
                    'disable-link-to-embed-conversion' => 'Disable link to embed conversion',
                    'overflow-scroll' => 'Overflow scroll',
                    'overflow-hidden' => 'Overflow hidden',
                    'hide-br' => 'Ocultar saltos de línea en tablet y móviles',
                    'hide-br-tablet' => 'Ocultar saltos de línea en tablet',
                    'hide-br-mobile' => 'Ocultar saltos de línea en móviles',
                    'extend-bg-to-left' => 'Extend background to left',
                    'extend-bg-to-right' => 'Extend background to right'
                ))->add_dependency('use')->set_width( 50 )
        )),
        Field::create( 'complex', 'background_color', __('Background color', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use' )->fancy()->set_width( 20 ),
            Field::create( 'color', 'color' )->add_dependency('use')->set_width( 30 ),
            Field::create( 'number', 'alpha', __('Opacity','mv23theme') )
                ->add_dependency('use')
                ->set_placeholder('0')
                ->enable_slider(0,100,1)
                ->set_default_value(100)
                ->set_width( 30 )
        )),
        Field::create( 'complex', 'font_color', __('Font color', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use' )->fancy()->set_width( 20 ),
            Field::create( 'select', 'color_scheme' )
                ->add_dependency('use')
                ->set_description( "Some components use the .dark-scheme CSS class to adjust their color styles for better appearance in dark mode." )
                ->add_options(array(
                    'default_scheme' => 'Default Scheme',
                    'dark_scheme' => 'Dark Scheme',
                    'custom' => 'Custom'
                ))
                ->set_width( 30 ),
            Field::create( 'color', 'color' )->add_dependency('use')->add_dependency('color_scheme','custom')->set_width( 30 ),
        )),
        Field::create( 'complex', 'background_image', __('Background Image', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use' )->fancy()->set_width( 20 ),
            Field::create( 'image', 'image' )->add_dependency('use')->set_width( 20 ),
            Field::create( 'complex', 'settings' )->add_fields(array(
	        	Field::create( 'select', 'size' )->add_options( array(
	        	    'cover' => __('Cover','mv23theme'),
	        	    'auto' => __('Auto','mv23theme'),
	        	)),
	        	Field::create( 'select', 'repeat' )->add_options( array(
	        	    'no-repeat' => __('No repeat','mv23theme'),
	        	    'repeat' => __('Repeat','mv23theme'),
	        	    'repeat-x' => __('Repeat X','mv23theme'),
	        	    'repeat-y' => __('Repeat Y','mv23theme'),
	        	)),
	        	Field::create( 'select', 'position_x' )->add_options( array(
	        	    'center' => __('Center','mv23theme'),
	        	    'left' => __('Left','mv23theme'),
	        	    'right' => __('Right','mv23theme'),
	        	)),
	        	Field::create( 'select', 'position_y' )->add_options( array(
	        	    'center' => __('Center','mv23theme'),
	        	    'top' => __('Top','mv23theme'),
	        	    'bottom' => __('Bottom','mv23theme'),
                )),
                Field::create( 'checkbox', 'parallax' )
	        ))->add_dependency('image','0','>')->merge()->set_width( 20 )
        )),
        Field::create( 'complex', 'video_background', __('Video background', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use' )->fancy()->set_width( 20 ),

            Field::create( 'radio', 'video_source', __('Source','mv23theme'))->set_orientation( 'horizontal' )->add_options( array(
                'selfhosted' => __('Media','mv23theme'),
                'external' => __('External','mv23theme')
                ))->add_dependency('use')->set_width(30),

                Field::create( 'video', 'video' )->add_dependency('use')->add_dependency('video_source','selfhosted','=')->set_width(30),
            Field::create( 'embed', 'external_url', 'URL')->add_dependency('use')->add_dependency('video_source','external','=')->set_width(30),

            Field::create( 'complex', 'video_settings' )->add_fields(array(
                Field::create( 'color', 'background_color' )->set_default_value('#000000')->set_width(20),
                Field::create( 'checkbox', 'autoplay' )->set_text( 'Activar' )->set_width(20),
                Field::create( 'checkbox', 'muted' )->set_text( 'Activar' )->set_width(20),
                Field::create( 'checkbox', 'loop' )->set_text( 'Activar' )->set_width(20),
                Field::create( 'number', 'opacity' )->enable_slider( 0, 100 )->set_default_value(100)->set_step( 5 )->set_width(20)
            ))->hide_label()->add_dependency('use')
        )),
        Field::create( 'complex', 'slider_background', __('Slider background', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use' )->fancy()->set_width( 20 ),
            Field::create( 'text', 'shortcode')->add_dependency('use')->set_width( 50 )
        )),
        Field::create( 'complex', 'margin', __('Margin', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use' )->fancy()->set_width( 20 ),
            Field::create( 'text', 'top', __('Top','mv23theme') )->set_placeholder('0px')->add_dependency('use')->set_width( 20 ),
            Field::create( 'text', 'right', __('Right','mv23theme') )->set_placeholder('0px')->add_dependency('use')->set_width( 20 ),
            Field::create( 'text', 'bottom', __('Bottom','mv23theme') )->set_placeholder('20px')->add_dependency('use')->set_width( 20 ),
            Field::create( 'text', 'left', __('Left','mv23theme') )->set_placeholder('0px')->add_dependency('use')->set_width( 20 )
        )),
        Field::create( 'complex', 'padding', __('Padding', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use' )->fancy()->set_width( 20 ),
            Field::create( 'text', 'top', __('Top','mv23theme') )->set_placeholder('0px')->add_dependency('use')->set_width( 20 ),
            Field::create( 'text', 'right', __('Right','mv23theme') )->set_placeholder('0px')->add_dependency('use')->set_width( 20 ),
            Field::create( 'text', 'bottom', __('Bottom','mv23theme') )->set_placeholder('0px')->add_dependency('use')->set_width( 20 ),
            Field::create( 'text', 'left', __('Left','mv23theme') )->set_placeholder('0px')->add_dependency('use')->set_width( 20 )
        )),
        Field::create( 'complex', 'border_radius', __('Border Radius', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use' )->fancy()->set_width( 20 ),
            Field::create( 'number', 'top_left', __('Top Left','mv23theme') )->set_suffix('px')->set_placeholder('0')->add_dependency('use')->set_width( 20 ),
            Field::create( 'number', 'top_right', __('Top Right','mv23theme') )->set_suffix('px')->set_placeholder('0')->add_dependency('use')->set_width( 20 ),
            Field::create( 'number', 'bottom_right', __('Bottom Right','mv23theme') )->set_suffix('px')->set_placeholder('0')->add_dependency('use')->set_width( 20 ),
            Field::create( 'number', 'bottom_left', __('Bottom Left','mv23theme') )->set_suffix('px')->set_placeholder('0')->add_dependency('use')->set_width( 20 )
        )),
        Field::create( 'complex', 'border', __('Border', 'mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use' )->fancy()->set_width( 50 ),
            Field::create( 'checkbox', 'unlock' )->fancy()->set_width( 50 )->add_dependency('use'),
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
            Field::create( 'checkbox', 'use' )->fancy(),
            Field::create( 'repeater', 'box_shadow', '' )->set_add_text('Agregar')->add_group('Shadow', array(
                'fields' => array(
                    Field::create( 'tab', 'Básico' ),
                    Field::create( 'text', 'h-offset', 'Distancia Horizontal' )->set_width(15)->set_suffix( 'px' )->set_default_value('0'),
                    Field::create( 'text', 'v-offset', 'Distancia Vertical' )->set_width(15)->set_suffix( 'px' )->set_default_value('0'),
                    Field::create( 'text', 'blur', 'Desenfoque' )->set_width(15)->set_suffix( 'px' )->set_default_value('15'),
                    Field::create( 'color', 'color' )->set_width(30)->set_default_value('#232323'),
                    Field::create( 'tab', 'Avanzado' ),
                    Field::create( 'number', 'alpha', 'Porcentaje de Intensidad' )->set_width(50)->enable_slider( 0, 100 )->set_default_value(15)->set_step( 5 ),
                    Field::create( 'select', 'position', 'Posición' )->set_width(25)->add_options(array(
                        'outset' => 'Exterior',
                        'inset' => 'Interior',
                    )),
                    Field::create( 'text', 'spread', 'Spread' )->set_width(25)->set_suffix( 'px' )->set_default_value('0')->set_description('A positive value increases the size of the shadow, a negative value decreases the size of the shadow'),
                )
            ))->add_dependency('use'),
        )),
        Field::create( 'complex', 'visibility', __('Visibility','mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'use' )->fancy()->set_width(50),
            Field::create('select', 'key', __('Visibility','mv23theme') )->add_options(array(
                'all' => 'Visible para todos los usuarios',
                'user_is_logged_in' => 'Visible para usuarios registrados',
                'user_is_not_logged_in' => 'Visible para usuarios no registrados',
                'is_private' => 'Solo visible para usuarios admin.',
            ))->add_dependency('use')->set_width(50)
        )),
        Field::create( 'complex', 'responsive', __('Responsive','mv23theme') )->add_fields(array(
            Field::create( 'checkbox', 'hide_on_mobile' )->fancy()->set_width(30),
            Field::create( 'checkbox', 'hide_on_tablet' )->fancy()->set_width(30),
            Field::create( 'checkbox', 'hide_on_desktop' )->fancy()->set_width(30)
        ))
    ));

add_action('admin_head', 'container_to_object');

function container_to_object() {
    $container_data = array();
    foreach( Container::get_registered() as $container ) {
        if( $container->get_id() == 'common_settings_container' ) {
            $container_data = $container->export_fields_settings();
        }
    }
    $container_data = json_encode( $container_data );
    ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Admin inline script loaded');
            var containerData = <?php echo $container_data; ?>;
            console.log(containerData);
        });
    </script>
    <?php
}
    