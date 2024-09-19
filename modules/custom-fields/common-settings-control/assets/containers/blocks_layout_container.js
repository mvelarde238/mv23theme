var blocks_layout_container = [
    {
        "type": "Layout",
        "name": "blocks_layout",
        "label": "Blocks Layout",
        "hide_label": true,
        "groups": [
            {
                "id": "text_editor",
                "title": "Text editor",
                "description": "",
                "fields": [
                    {
                        "type": "Tab",
                        "name": "Contenido",
                        "label": "Contenido"
                    },
                    {
                        "type": "WYSIWYG",
                        "name": "content",
                        "label": "Content",
                        "tab": "Contenido",
                        "hide_label": true,
                        "rows": 20
                    },
                    {
                        "type": "Checkbox",
                        "name": "add_responsive",
                        "label": "Add Responsive",
                        "tab": "Contenido",
                        "hide_label": true,
                        "fancy": false,
                        "text": "Change text alignment on mobile devices"
                    },
                    {
                        "type": "Select",
                        "name": "tablet_text_align",
                        "label": "Text alignment on tablet",
                        "tab": "Contenido",
                        "field_width": 50,
                        "dependencies": [
                            [
                                {
                                    "field": "add_responsive",
                                    "value": true,
                                    "compare": "="
                                }
                            ]
                        ],
                        "options": {
                            "": "Seleccionar",
                            "left": "Izquierda",
                            "center": "Centrar",
                            "right": "Derecha"
                        },
                        "use_select2": false,
                        "input_type": "select",
                        "orientation": "vertical"
                    },
                    {
                        "type": "Select",
                        "name": "mobile_text_align",
                        "label": "Text alignment on mobile",
                        "tab": "Contenido",
                        "field_width": 50,
                        "dependencies": [
                            [
                                {
                                    "field": "add_responsive",
                                    "value": true,
                                    "compare": "="
                                }
                            ]
                        ],
                        "options": {
                            "": "Seleccionar",
                            "left": "Izquierda",
                            "center": "Centrar",
                            "right": "Derecha"
                        },
                        "use_select2": false,
                        "input_type": "select",
                        "orientation": "vertical"
                    },
                    {
                        "type": "Tab",
                        "name": "Ajustes",
                        "label": "Ajustes"
                    },
                    {
                        "type": "Common_Settings_Control",
                        "name": "settings",
                        "label": "Settings",
                        "tab": "Ajustes",
                        "field_width": 30,
                        "container": "common_settings_container",
                        "nonce": "6949d7676f",
                        "save_text": "Save Settings",
                        "add_text": "Add Settings"
                    },
                    {
                        "type": "Common_Settings_Control",
                        "name": "scroll_animations_settings",
                        "label": "Scroll Animations Settings",
                        "tab": "Ajustes",
                        "field_width": 30,
                        "container": "scroll_animations_container",
                        "nonce": "49787933d2",
                        "save_text": "Save Settings",
                        "add_text": "Add Scroll Animations"
                    },
                    {
                        "type": "Common_Settings_Control",
                        "name": "actions_settings",
                        "label": "Actions Settings",
                        "tab": "Ajustes",
                        "field_width": 30,
                        "container": "actions_container",
                        "nonce": "53f4a720fe",
                        "save_text": "Save Settings",
                        "add_text": "Add Actions"
                    }
                ],
                "layout": "table",
                "style": "auto",
                "description_position": "input",
                "title_template": "<%= content.replace(/<[^>]+>/ig, \"\") %>",
                "edit_mode": "popup",
                "min_width": 1,
                "max_width": 0
            },
            {
                "id": "image",
                "title": "Imagen",
                "description": "",
                "fields": [
                    {
                        "type": "Tab",
                        "name": "Contenido",
                        "label": "Contenido"
                    },
                    {
                        "type": "Image",
                        "name": "image",
                        "label": "Imagen",
                        "tab": "Contenido",
                        "file_type": "image",
                        "basic": false,
                        "nonce": "9b815f8850"
                    },
                    {
                        "type": "Checkbox",
                        "name": "expand_on_click",
                        "label": "Expand on click",
                        "tab": "Contenido",
                        "fancy": true,
                        "text": "Show the image in a popup."
                    },
                    {
                        "type": "Checkbox",
                        "name": "full_width",
                        "label": "Full Width",
                        "tab": "Contenido",
                        "fancy": true,
                        "text": "Let the image fill the full width of the available space."
                    },
                    {
                        "type": "Select",
                        "name": "alignment",
                        "label": "Alineación",
                        "tab": "Contenido",
                        "dependencies": [
                            [
                                {
                                    "field": "full_width",
                                    "value": 0,
                                    "compare": "="
                                }
                            ]
                        ],
                        "options": {
                            "left": "Izquierda",
                            "center": "Centrar",
                            "right": "Derecha"
                        },
                        "use_select2": false,
                        "input_type": "select",
                        "orientation": "vertical"
                    },
                    {
                        "type": "Tab",
                        "name": "Tamaño",
                        "label": "Tamaño"
                    },
                    {
                        "type": "Image_Select",
                        "name": "aspect_ratio",
                        "label": "Aspect Ratio",
                        "tab": "Tamaño",
                        "options": {
                            "default": {
                                "label": "default",
                                "image": "http://www.tad.posicionarweb.cl/wp-content/themes/mv23theme/modules/custom-fields/assets/images/aspect-ratio-default.png"
                            },
                            "1/1": {
                                "label": "1:1",
                                "image": "http://www.tad.posicionarweb.cl/wp-content/themes/mv23theme/modules/custom-fields/assets/images/aspect-ratio-1-1.png"
                            },
                            "4/3": {
                                "label": "4:3",
                                "image": "http://www.tad.posicionarweb.cl/wp-content/themes/mv23theme/modules/custom-fields/assets/images/aspect-ratio-4-3.png"
                            },
                            "16/9": {
                                "label": "16:9",
                                "image": "http://www.tad.posicionarweb.cl/wp-content/themes/mv23theme/modules/custom-fields/assets/images/aspect-ratio-16-9.png"
                            },
                            "2/1": {
                                "label": "2:1",
                                "image": "http://www.tad.posicionarweb.cl/wp-content/themes/mv23theme/modules/custom-fields/assets/images/aspect-ratio-2-1.png"
                            },
                            "2.5/1": {
                                "label": "2.5:1",
                                "image": "http://www.tad.posicionarweb.cl/wp-content/themes/mv23theme/modules/custom-fields/assets/images/aspect-ratio-2_5-1.png"
                            },
                            "4/1": {
                                "label": "4:1",
                                "image": "http://www.tad.posicionarweb.cl/wp-content/themes/mv23theme/modules/custom-fields/assets/images/aspect-ratio-4-1.png"
                            },
                            "3/4": {
                                "label": "3:4",
                                "image": "http://www.tad.posicionarweb.cl/wp-content/themes/mv23theme/modules/custom-fields/assets/images/aspect-ratio-3-4.png"
                            },
                            "9/16": {
                                "label": "9:16",
                                "image": "http://www.tad.posicionarweb.cl/wp-content/themes/mv23theme/modules/custom-fields/assets/images/aspect-ratio-9-16.png"
                            },
                            "1/2": {
                                "label": "1:2",
                                "image": "http://www.tad.posicionarweb.cl/wp-content/themes/mv23theme/modules/custom-fields/assets/images/aspect-ratio-1-2.png"
                            },
                            "1/2.5": {
                                "label": "1:2.5",
                                "image": "http://www.tad.posicionarweb.cl/wp-content/themes/mv23theme/modules/custom-fields/assets/images/aspect-ratio-1-2_5.png"
                            },
                            "custom": {
                                "label": "custom",
                                "image": "http://www.tad.posicionarweb.cl/wp-content/themes/mv23theme/modules/custom-fields/assets/images/aspect-ratio-custom.png"
                            }
                        },
                        "show_label": false
                    },
                    {
                        "type": "Text",
                        "name": "custom_aspect_ratio",
                        "label": "Custom Aspect Ratio",
                        "tab": "Tamaño",
                        "validation_rule": "^(\\d+(\\.\\d+)?)(\\s*\\/\\s*(\\d+(\\.\\d+)?))?$",
                        "dependencies": [
                            [
                                {
                                    "field": "aspect_ratio",
                                    "value": "custom",
                                    "compare": "="
                                }
                            ]
                        ]
                    },
                    {
                        "type": "Select",
                        "name": "object_fit",
                        "label": "Object Fit",
                        "tab": "Tamaño",
                        "dependencies": [
                            [
                                {
                                    "field": "aspect_ratio",
                                    "value": "default",
                                    "compare": "!="
                                }
                            ]
                        ],
                        "options": {
                            "cover": "Cover",
                            "contain": "Contain"
                        },
                        "use_select2": false,
                        "input_type": "select",
                        "orientation": "vertical"
                    },
                    {
                        "type": "Tab",
                        "name": "Ajustes",
                        "label": "Ajustes"
                    },
                    {
                        "type": "Common_Settings_Control",
                        "name": "settings",
                        "label": "Settings",
                        "tab": "Ajustes",
                        "field_width": 30,
                        "container": "common_settings_container",
                        "nonce": "6949d7676f",
                        "save_text": "Save Settings",
                        "add_text": "Add Settings"
                    },
                    {
                        "type": "Common_Settings_Control",
                        "name": "scroll_animations_settings",
                        "label": "Scroll Animations Settings",
                        "tab": "Ajustes",
                        "field_width": 30,
                        "container": "scroll_animations_container",
                        "nonce": "49787933d2",
                        "save_text": "Save Settings",
                        "add_text": "Add Scroll Animations"
                    },
                    {
                        "type": "Common_Settings_Control",
                        "name": "actions_settings",
                        "label": "Actions Settings",
                        "tab": "Ajustes",
                        "field_width": 30,
                        "container": "actions_container",
                        "nonce": "53f4a720fe",
                        "save_text": "Save Settings",
                        "add_text": "Add Actions"
                    }
                ],
                "layout": "table",
                "style": "auto",
                "description_position": "input",
                "title_template": "<% if(image){ %>\r\n            <%= image_prepared[0].filename %> \r\n            <% if(aspect_ratio != \"default\"){ %>\r\n                | aspect ratio: <%= aspect_ratio %> \r\n            <% } %>\r\n            <% if(alignment && alignment != \"left\"){ %>\r\n                | Alignment: <%= alignment %>\r\n            <% } %>\r\n        <% } %>",
                "edit_mode": "popup",
                "min_width": 1,
                "max_width": 0
            },
            {
                "id": "spacer",
                "title": "Spacer",
                "description": "",
                "fields": [
                    {
                        "type": "Tab",
                        "name": "Contenido",
                        "label": "Contenido"
                    },
                    {
                        "type": "Number",
                        "name": "height",
                        "label": "Tamaño de alto",
                        "tab": "Contenido",
                        "field_width": 50,
                        "default_value": "30",
                        "minimum": false,
                        "maximum": false,
                        "step": 1,
                        "slider_enabled": false,
                        "prefix": null,
                        "suffix": null,
                        "placeholder": null
                    },
                    {
                        "type": "Text",
                        "name": "unit",
                        "label": "Medida (px,%,vh..)",
                        "tab": "Contenido",
                        "field_width": 50,
                        "default_value": "px"
                    },
                    {
                        "type": "Tab",
                        "name": "Ajustes",
                        "label": "Ajustes"
                    },
                    {
                        "type": "Common_Settings_Control",
                        "name": "settings",
                        "label": "Settings",
                        "tab": "Ajustes",
                        "field_width": 30,
                        "container": "common_settings_container",
                        "nonce": "6949d7676f",
                        "save_text": "Save Settings",
                        "add_text": "Add Settings"
                    },
                    {
                        "type": "Common_Settings_Control",
                        "name": "scroll_animations_settings",
                        "label": "Scroll Animations Settings",
                        "tab": "Ajustes",
                        "field_width": 30,
                        "container": "scroll_animations_container",
                        "nonce": "49787933d2",
                        "save_text": "Save Settings",
                        "add_text": "Add Scroll Animations"
                    },
                    {
                        "type": "Common_Settings_Control",
                        "name": "actions_settings",
                        "label": "Actions Settings",
                        "tab": "Ajustes",
                        "field_width": 30,
                        "container": "actions_container",
                        "nonce": "53f4a720fe",
                        "save_text": "Save Settings",
                        "add_text": "Add Actions"
                    }
                ],
                "layout": "table",
                "style": "auto",
                "description_position": "input",
                "title_template": "<%= height %><%= unit %>",
                "edit_mode": "popup",
                "min_width": 1,
                "max_width": 0
            },
            {
                "id": "button",
                "title": "Button",
                "description": "",
                "fields": [
                    {
                        "type": "Tab",
                        "name": "Contenido",
                        "label": "Contenido"
                    },
                    {
                        "type": "Text",
                        "name": "text",
                        "label": "Texto del botón",
                        "tab": "Contenido",
                        "field_width": 25
                    },
                    {
                        "type": "Select",
                        "name": "style",
                        "label": "Estilo",
                        "tab": "Contenido",
                        "field_width": 25,
                        "default_value": "btn btn--main-color",
                        "options": {
                            "": "Link",
                            "btn": "Botón Simple",
                            "btn btn--main-color": "Botón Corporativo 1",
                            "btn btn--secondary-color": "Botón Corporativo 2",
                            "btn btn--white": "Botón Blanco"
                        },
                        "use_select2": false,
                        "input_type": "select",
                        "orientation": "vertical"
                    },
                    {
                        "type": "Select",
                        "name": "alignment",
                        "label": "Alineación",
                        "tab": "Contenido",
                        "field_width": 25,
                        "options": {
                            "left": "Izquierda",
                            "center": "Centro",
                            "right": "Derecha"
                        },
                        "use_select2": false,
                        "input_type": "radio",
                        "orientation": "horizontal"
                    },
                    {
                        "type": "Select",
                        "name": "size",
                        "label": "Tamaño",
                        "tab": "Contenido",
                        "field_width": 25,
                        "options": {
                            "small": "Normal",
                            "medium": "Mediano",
                            "big": "Grande"
                        },
                        "use_select2": false,
                        "input_type": "radio",
                        "orientation": "horizontal"
                    },
                    {
                        "type": "Checkbox",
                        "name": "fullwidth",
                        "label": "Botón de ancho completo",
                        "tab": "Contenido",
                        "field_width": 25,
                        "fancy": false,
                        "text": "Activar"
                    },
                    {
                        "type": "Select",
                        "name": "type",
                        "label": "Tipo",
                        "tab": "Contenido",
                        "field_width": 25,
                        "options": {
                            "link": "Link",
                            "download": "Descarga"
                        },
                        "use_select2": false,
                        "input_type": "radio",
                        "orientation": "horizontal"
                    },
                    {
                        "type": "File",
                        "name": "file",
                        "label": "File",
                        "tab": "Contenido",
                        "field_width": 25,
                        "dependencies": [
                            [
                                {
                                    "field": "type",
                                    "value": "download",
                                    "compare": "="
                                }
                            ]
                        ],
                        "file_type": "all",
                        "basic": false,
                        "nonce": "d44162be99"
                    },
                    {
                        "type": "Select",
                        "name": "url_type",
                        "label": "Origen:",
                        "tab": "Contenido",
                        "field_width": 25,
                        "dependencies": [
                            [
                                {
                                    "field": "type",
                                    "value": "link",
                                    "compare": "="
                                }
                            ]
                        ],
                        "options": {
                            "interna": "Página Interna",
                            "externa": "Página Externa"
                        },
                        "use_select2": false,
                        "input_type": "radio",
                        "orientation": "horizontal"
                    },
                    {
                        "type": "WP_Object",
                        "name": "post",
                        "label": "URL Interna",
                        "tab": "Contenido",
                        "field_width": 25,
                        "dependencies": [
                            [
                                {
                                    "field": "type",
                                    "value": "link",
                                    "compare": "="
                                },
                                {
                                    "field": "url_type",
                                    "value": "interna",
                                    "compare": "="
                                }
                            ]
                        ],
                        "nonce": "e51e72462d",
                        "multiple": false,
                        "button_text": "Selecciona la página",
                        "hide_filters": false
                    },
                    {
                        "type": "Text",
                        "name": "url",
                        "label": "URL Externa",
                        "tab": "Contenido",
                        "field_width": 25,
                        "dependencies": [
                            [
                                {
                                    "field": "type",
                                    "value": "link",
                                    "compare": "="
                                },
                                {
                                    "field": "url_type",
                                    "value": "externa",
                                    "compare": "="
                                }
                            ]
                        ]
                    },
                    {
                        "type": "Checkbox",
                        "name": "new_tab",
                        "label": "Abrir en una nueva ventana",
                        "tab": "Contenido",
                        "field_width": 25,
                        "fancy": false,
                        "text": "Activar"
                    },
                    {
                        "type": "Tab",
                        "name": "Icono",
                        "label": "Icono"
                    },
                    {
                        "type": "Icon",
                        "name": "icon",
                        "label": "Icono",
                        "tab": "Icono",
                        "field_width": 25,
                        "icon_sets": [
                            "bootstrap-icons",
                            "font-awesome"
                        ]
                    },
                    {
                        "type": "Select",
                        "name": "icon_position",
                        "label": "Posición",
                        "tab": "Icono",
                        "field_width": 25,
                        "options": {
                            "left": "Izquierda",
                            "right": "Derecha"
                        },
                        "use_select2": false,
                        "input_type": "radio",
                        "orientation": "horizontal"
                    },
                    {
                        "type": "Tab",
                        "name": "Mobile Options",
                        "label": "Mobile Options"
                    },
                    {
                        "type": "Checkbox",
                        "name": "add_responsive",
                        "label": "Add Responsive",
                        "tab": "Mobile Options",
                        "hide_label": true,
                        "html_attributes": {
                            "style": "background: #eeeeee; width: 100%"
                        },
                        "fancy": false,
                        "text": "Cambiar alineación en móviles"
                    },
                    {
                        "type": "Select",
                        "name": "tablet_text_align",
                        "label": "Alineación en Tablets",
                        "tab": "Mobile Options",
                        "field_width": 50,
                        "dependencies": [
                            [
                                {
                                    "field": "add_responsive",
                                    "value": true,
                                    "compare": "="
                                }
                            ]
                        ],
                        "options": {
                            "": "Seleccionar",
                            "left": "Izquierda",
                            "center": "Centro",
                            "right": "Derecha"
                        },
                        "use_select2": false,
                        "input_type": "select",
                        "orientation": "vertical"
                    },
                    {
                        "type": "Select",
                        "name": "mobile_text_align",
                        "label": "Alineación en Móviles",
                        "tab": "Mobile Options",
                        "field_width": 50,
                        "dependencies": [
                            [
                                {
                                    "field": "add_responsive",
                                    "value": true,
                                    "compare": "="
                                }
                            ]
                        ],
                        "options": {
                            "": "Seleccionar",
                            "left": "Izquierda",
                            "center": "Centro",
                            "right": "Derecha"
                        },
                        "use_select2": false,
                        "input_type": "select",
                        "orientation": "vertical"
                    },
                    {
                        "type": "Tab",
                        "name": "Attributes",
                        "label": "Attributes"
                    },
                    {
                        "type": "Repeater",
                        "name": "attributes",
                        "label": "Attributos",
                        "tab": "Attributes",
                        "hide_label": true,
                        "groups": [
                            {
                                "id": "item",
                                "title": "Item",
                                "description": "",
                                "fields": [
                                    {
                                        "type": "Text",
                                        "name": "attribute",
                                        "label": "Attribute",
                                        "field_width": 50
                                    },
                                    {
                                        "type": "Text",
                                        "name": "value",
                                        "label": "Value",
                                        "field_width": 50
                                    }
                                ],
                                "layout": "grid",
                                "style": "auto",
                                "description_position": "input",
                                "title_template": "<%= attribute %> : <%= value %>",
                                "edit_mode": "inline"
                            }
                        ],
                        "minimum": 0,
                        "maximum": 0,
                        "chooser_type": "widgets",
                        "add_text": "Agregar",
                        "placeholder_text": "Please click the \"Agregar\" button to add a new entry.",
                        "background": "",
                        "layout": "table"
                    },
                    {
                        "type": "Tab",
                        "name": "Ajustes",
                        "label": "Ajustes"
                    },
                    {
                        "type": "Common_Settings_Control",
                        "name": "settings",
                        "label": "Settings",
                        "tab": "Ajustes",
                        "field_width": 30,
                        "container": "common_settings_container",
                        "nonce": "6949d7676f",
                        "save_text": "Save Settings",
                        "add_text": "Add Settings"
                    },
                    {
                        "type": "Common_Settings_Control",
                        "name": "scroll_animations_settings",
                        "label": "Scroll Animations Settings",
                        "tab": "Ajustes",
                        "field_width": 30,
                        "container": "scroll_animations_container",
                        "nonce": "49787933d2",
                        "save_text": "Save Settings",
                        "add_text": "Add Scroll Animations"
                    },
                    {
                        "type": "Common_Settings_Control",
                        "name": "actions_settings",
                        "label": "Actions Settings",
                        "tab": "Ajustes",
                        "field_width": 30,
                        "container": "actions_container",
                        "nonce": "53f4a720fe",
                        "save_text": "Save Settings",
                        "add_text": "Add Actions"
                    }
                ],
                "layout": "table",
                "style": "auto",
                "description_position": "input",
                "title_template": "<%= text.replace( /(<([^>]+)>)/ig,\"\" ) %>",
                "edit_mode": "popup",
                "min_width": 1,
                "max_width": 0
            }
        ],
        "minimum": 0,
        "maximum": 0,
        "chooser_type": "widgets",
        "add_text": "Add entry",
        "placeholder_text": "Arrastre un componente aquí",
        "background": "",
        "layout": "normal",
        "columns": 12
    }
]