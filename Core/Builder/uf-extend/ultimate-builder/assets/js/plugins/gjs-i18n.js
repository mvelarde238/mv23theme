window.gjsI18n = function (editor, options) {

    editor.I18n && editor.I18n.addMessages({
        es: {
            'ultimate_builder': {
                save_as_template: 'Guardar como plantilla',
                save_as_template_tagline: 'Ingrese el nombre del item:',
                save_as_template_title_placeholder: 'Nombre del item...',
                save_as_template_save_label: 'Guardar',
                templates_library: 'Biblioteca de plantillas',
                loading_templates: 'Cargando plantillas...',
                delete_template_confirmation: '¿Estás seguro de que deseas eliminar esta plantilla?',
                select_image_title: 'Selecciona una imagen para la plantilla',
                select_image_button: 'Seleccionar',
                templates_system_invalid_image_file_msg: 'Por favor selecciona un archivo de imagen válido (jpg, png, gif, svg).',
                template_saved_successfully: '¡Plantilla guardada exitosamente!',
                structure: 'Estructura',
                content: 'Contenido',
                // Content Migration Modal
                content_migration_modal_title: 'Contenido Detectado',
                content_migration_modal_message: 'Se ha detectado contenido guardado en el editor clásico de WordPress de esta publicación. Para editarlo en el constructor, es necesario migrarlo. Esta acción moverá el contenido al constructor y lo eliminará del editor clásico.',
                content_migration_cancel_button: 'No Migrar',
                content_migration_accept_button: 'Migrar Contenido',
                content_migration_migrating_message: 'Migrando...',
                content_migration_error_message: 'Error al migrar el contenido. Por favor, inténtalo de nuevo.',
                content_migration_success_message: 'El contenido ha sido migrado al constructor exitosamente.',
                content_migration_unknown_error_message: 'Ocurrió un error desconocido.',
            },
            'react-builder': {
                // top bar
                undo: 'DESHACER',
                redo: 'REHACER',
                save: 'GUARDAR',
                preview: 'PREVISUALIZAR',
                view_controls: "Ver controles",
                show_outlines: 'MOSTRAR CONTORNOS',
                fullscreen: 'PANTALLA COMPLETA',
                show_code: 'MOSTRAR CÓDIGOS',
                exit_to_wp_admin: 'SALIR AL ESCRITORIO',
                // left sidebar
                global_settings: 'Ajustes Globales',
                layers: 'Capas',
                on_component_select_label: 'Al seleccionar un componente',
                on_component_select_desc: 'Escoge que mostrar cuando se selecciona un componente',
                open_component_settings: 'Abrir ajustes del componente',
                open_components_tab: 'Mostrar todos los componentes',
                open_style_manager: 'Abrir el administrador de estilos',
                do_nothing: 'No hacer nada',
                // right sidebar
                components: 'Componentes',
                styles: 'Estilos',
                traits: 'Rasgos',
                settings: 'Ajustes',
                component_settings_panel: 'Panel de ajustes del componente',
                select_component_to_edit_settings: 'Selecciona un componente para editar sus ajustes',
                select_component_to_edit_styles: 'Selecciona un componente para editar sus estilos',
                style_manager: 'Administrador de estilos',
                // spacing sector
                spacing: 'ESPACIADO',
                outter: 'EXTERIOR',
                inner: 'INTERIOR',
                lock_margin: 'Bloquear margen',
                unlock_margin: 'Desbloquear margen',
                clear_margin: 'Limpiar márgenes',
                lock_padding: 'Bloquear relleno',
                unlock_padding: 'Desbloquear relleno',
                clear_padding: 'Limpiar relleno',
                content_area: 'Contenido',
            },
            'gjs-context-menu': {
                // global
                boxed: 'ESTÁNDAR',
                full_width: 'ANCHO COMPLETO',
                full_width_stretched: 'EXTENDER FONDO <BR> CENTRAR CONTENIDO',
                save_as_template: 'Guardar como plantilla',
                // wrapper
                edit_theme_options: 'EDITAR OPCIONES DE TEMA',
                color_scheme: 'ESQUEMA DE COLOR',
                dark_mode: 'MODO OSCURO',
                light_mode: 'MODO CLARO',
                // icon and text
                icon_size: 'TAMAÑO DE ICONO',
                background_and_color: 'COLOR DE FONDO Y TEXTO',
                space_around_icon: 'ESPACIO ALREDEDOR DEL ICONO',
                rounded_corners: 'ESQUINAS REDONDEADAS',
                border_size_and_color: 'TAMAÑO Y COLOR DE BORDE',
                gap: 'ESPACIO ENTRE ELEMENTOS',
                // components-wrapper
                space_between_components: 'ESPACIO ENTRE COMPONENTES',
                content_alignment: 'ALINEACIÓN DE CONTENIDO',
                // section
                add_section: 'AÑADIR SECCIÓN',
                above: 'ARRIBA',
                below: 'ABAJO',
                // menu
                select_links: 'SELECCIONAR ENLACES',
                select_hovered_links: 'SELECCIONAR ENLACES HOVER',
                // heading
                select_heading: 'SELECCIONAR ENCABEZADO',
                select_tagline: 'SELECCIONAR SUBTÍTULO',
                // button
                text_align: 'ALINEACIÓN DE TEXTO',
                // text-editor
                font_size: 'TAMAÑO DEL TEXTO',
            }
        }
    });
};