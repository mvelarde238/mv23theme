<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'common_settings_container' ) 
    ->add_location( 'post_type', UF_POSTTYPES )
    ->set_layout( 'rows' )
    ->add_fields(array(
        Field::create( 'complex', 'main_attributes' )->add_fields(array(
            Field::create( 'text', 'id', __('ID','default') )
                ->set_validation_rule('^[a-z][a-za-z0-9_-]+$')
                ->set_description( 'Identificador -ID- de la sección, usar solo minúsculas y guiones ( - )' )
                ->set_width( 50 ),
            Field::create( 'text', 'class', __('Class','default') )
                // ->add_datalist( array( 
                    // 'disable-link-to-embed-conversion',
                    // 'overflow-scroll',
                    // 'overflow-hidden',
                // ))
                ->set_description( 'Clases de la sección, usar solo minúsculas y guiones ( -/_ )' )
                ->set_width( 50 )
        ))->merge(),
        Field::create( 'complex', 'layout', __('Layout','default') )->add_fields(array(
            Field::create( 'checkbox', 'use' )->fancy()->set_width(50),
            Field::create( 'select', 'key', __('Layout','default'))->add_options( array(
                'layout1' => 'Estándar',
                'layout2' => 'Fondo extendido / Contenido centrado',
                'layout3' => 'Todo extendido'
            ))->add_dependency('use')->set_width( 50 )
        )),
        Field::create( 'complex', 'helpers', __('Helpers','default') )->add_fields(array(
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
        Field::create( 'complex', 'background_color', __('Background color', 'default') )->add_fields(array(
            Field::create( 'checkbox', 'use' )->fancy()->set_width( 20 ),
            Field::create( 'color', 'color' )->add_dependency('use')->set_width( 30 ),
            Field::create( 'number', 'alpha', __('Opacity','default') )
                ->add_dependency('use')
                ->set_placeholder('0')
                ->enable_slider(0,100,1)
                ->set_default_value(100)
                ->set_width( 30 )
        )),
        Field::create( 'complex', 'font_color', __('Font color', 'default') )->add_fields(array(
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
        Field::create( 'complex', 'background_image', __('Background Image', 'default') )->add_fields(array(
            Field::create( 'checkbox', 'use' )->fancy()->set_width( 20 ),
            Field::create( 'image', 'image' )->add_dependency('use')->set_width( 20 ),
            Field::create( 'complex', 'settings' )->add_fields(array(
	        	Field::create( 'select', 'size' )->add_options( array(
	        	    'cover' => __('Cover','default'),
	        	    'auto' => __('Auto','default'),
	        	)),
	        	Field::create( 'select', 'repeat' )->add_options( array(
	        	    'no-repeat' => __('No repeat','default'),
	        	    'repeat' => __('Repeat','default'),
	        	    'repeat-x' => __('Repeat X','default'),
	        	    'repeat-y' => __('Repeat Y','default'),
	        	)),
	        	Field::create( 'select', 'position_x' )->add_options( array(
	        	    'center' => __('Center','default'),
	        	    'left' => __('Left','default'),
	        	    'right' => __('Right','default'),
	        	)),
	        	Field::create( 'select', 'position_y' )->add_options( array(
	        	    'center' => __('Center','default'),
	        	    'top' => __('Top','default'),
	        	    'bottom' => __('Bottom','default'),
                )),
                Field::create( 'checkbox', 'parallax' )
	        ))->add_dependency('image','0','>')->merge()->set_width( 20 )
        )),
        Field::create( 'complex', 'video_background', __('Video background', 'default') )->add_fields(array(
            Field::create( 'checkbox', 'use' )->fancy()->set_width( 20 ),

            Field::create( 'radio', 'video_source', __('Source','default'))->set_orientation( 'horizontal' )->add_options( array(
                'selfhosted' => __('Media','default'),
                'external' => __('External','default')
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
        Field::create( 'complex', 'margin', __('Margin', 'default') )->add_fields(array(
            Field::create( 'checkbox', 'use' )->fancy()->set_width( 20 ),
            Field::create( 'number', 'top', __('Top','default') )->set_suffix('px')->set_placeholder('25')->add_dependency('use')->set_width( 20 ),
            Field::create( 'number', 'right', __('Right','default') )->set_suffix('px')->set_placeholder('20')->add_dependency('use')->set_width( 20 ),
            Field::create( 'number', 'bottom', __('Bottom','default') )->set_suffix('px')->set_placeholder('25')->add_dependency('use')->set_width( 20 ),
            Field::create( 'number', 'left', __('Left','default') )->set_suffix('px')->set_placeholder('20')->add_dependency('use')->set_width( 20 )
        )),
        Field::create( 'complex', 'padding', __('Padding', 'default') )->add_fields(array(
            Field::create( 'checkbox', 'use' )->fancy()->set_width( 20 ),
            Field::create( 'number', 'top', __('Top','default') )->set_suffix('px')->set_placeholder('0')->add_dependency('use')->set_width( 20 ),
            Field::create( 'number', 'right', __('Right','default') )->set_suffix('px')->set_placeholder('0')->add_dependency('use')->set_width( 20 ),
            Field::create( 'number', 'bottom', __('Bottom','default') )->set_suffix('px')->set_placeholder('0')->add_dependency('use')->set_width( 20 ),
            Field::create( 'number', 'left', __('Left','default') )->set_suffix('px')->set_placeholder('0')->add_dependency('use')->set_width( 20 )
        )),
        Field::create( 'complex', 'border_radius', __('Border Radius', 'default') )->add_fields(array(
            Field::create( 'checkbox', 'use' )->fancy()->set_width( 20 ),
            Field::create( 'number', 'top_left', __('Top Left','default') )->set_suffix('px')->set_placeholder('0')->add_dependency('use')->set_width( 20 ),
            Field::create( 'number', 'top_right', __('Top Right','default') )->set_suffix('px')->set_placeholder('0')->add_dependency('use')->set_width( 20 ),
            Field::create( 'number', 'bottom_right', __('Bottom Right','default') )->set_suffix('px')->set_placeholder('0')->add_dependency('use')->set_width( 20 ),
            Field::create( 'number', 'bottom_left', __('Bottom Left','default') )->set_suffix('px')->set_placeholder('0')->add_dependency('use')->set_width( 20 )
        )),
        Field::create( 'complex', 'border', __('Border', 'default') )->add_fields(array(
            Field::create( 'checkbox', 'use' )->fancy()->set_width( 50 ),
            Field::create( 'checkbox', 'unlock' )->fancy()->set_width( 50 )->add_dependency('use'),
            Field::create( 'complex', 'top' )->add_fields(array(
                Field::create( 'number', 'width', __('Border Top','default') )->set_suffix( 'px' )->set_default_value('1')->set_width(10),
                Field::create( 'select', 'style' )->add_options(array( 'solid' => 'solid', 'dotted' => 'dotted', 'dashed' => 'dashed', 'double' => 'double', 'groove' => 'groove', 'ridge' => 'ridge', 'inset' => 'inset', 'outset' => 'outset' ))->set_width(20),
                Field::create( 'color', 'color' )->set_width(40),
            ))->add_dependency('use')->hide_label(),
            Field::create( 'complex', 'right' )->add_fields(array(
                Field::create( 'number', 'width', __('Border Right','default') )->set_suffix( 'px' )->set_default_value('1')->set_width(10),
                Field::create( 'select', 'style' )->add_options(array( 'solid' => 'solid', 'dotted' => 'dotted', 'dashed' => 'dashed', 'double' => 'double', 'groove' => 'groove', 'ridge' => 'ridge', 'inset' => 'inset', 'outset' => 'outset' ))->set_width(20),
                Field::create( 'color', 'color' )->set_width(40),
            ))->add_dependency('unlock')->hide_label(),
            Field::create( 'complex', 'bottom' )->add_fields(array(
                Field::create( 'number', 'width', __('Border Bottom','default') )->set_suffix( 'px' )->set_default_value('1')->set_width(10),
                Field::create( 'select', 'style' )->add_options(array( 'solid' => 'solid', 'dotted' => 'dotted', 'dashed' => 'dashed', 'double' => 'double', 'groove' => 'groove', 'ridge' => 'ridge', 'inset' => 'inset', 'outset' => 'outset' ))->set_width(20),
                Field::create( 'color', 'color' )->set_width(40),
            ))->add_dependency('unlock')->hide_label(),
            Field::create( 'complex', 'left' )->add_fields(array(
                Field::create( 'number', 'width', __('Border Left','default') )->set_suffix( 'px' )->set_default_value('1')->set_width(10),
                Field::create( 'select', 'style' )->add_options(array( 'solid' => 'solid', 'dotted' => 'dotted', 'dashed' => 'dashed', 'double' => 'double', 'groove' => 'groove', 'ridge' => 'ridge', 'inset' => 'inset', 'outset' => 'outset' ))->set_width(20),
                Field::create( 'color', 'color' )->set_width(40),
            ))->add_dependency('unlock')->hide_label(),
        )),
        Field::create( 'complex', 'box_shadow', __('Box Shadow', 'default') )->add_fields(array(
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
        Field::create( 'complex', 'visibility', __('Visibility','default') )->add_fields(array(
            Field::create( 'checkbox', 'use' )->fancy()->set_width(50),
            Field::create('select', 'key', __('Visibility','default') )->add_options(array(
                'all' => 'Visible para todos los usuarios',
                'user_is_logged_in' => 'Visible para usuarios registrados',
                'user_is_not_logged_in' => 'Visible para usuarios no registrados',
                'is_private' => 'Solo visible para usuarios admin.',
            ))->add_dependency('use')->set_width(50)
        )),
        Field::create( 'complex', 'responsive', __('Responsive','default') )->add_fields(array(
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
    