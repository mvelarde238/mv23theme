var scroll_animations_container = [
    {
        "type": "Repeater",
        "name": "groups",
        "label": "Scroll Animations",
        "groups": [
            {
                "id": "group1",
                "title": "Scroll Animation",
                "description": "",
                "fields": [
                    {
                        "type": "Complex",
                        "name": "settings",
                        "label": "Settings",
                        "hide_label": true,
                        "field_width": 50,
                        "group": {
                            "id": "complex_group",
                            "title": "Complex Group",
                            "description": "",
                            "fields": [
                                {
                                    "type": "Complex",
                                    "name": "trigger-element",
                                    "label": "Trigger Element",
                                    "field_width": 50,
                                    "group": {
                                        "id": "complex_group",
                                        "title": "Complex Group",
                                        "description": "",
                                        "fields": [
                                            {
                                                "type": "Select",
                                                "name": "el",
                                                "label": "El",
                                                "hide_label": true,
                                                "field_width": 50,
                                                "options": {
                                                    "this": "Componente",
                                                    "selector": "Elemento interno"
                                                },
                                                "use_select2": false,
                                                "input_type": "select",
                                                "orientation": "vertical"
                                            },
                                            {
                                                "type": "Text",
                                                "name": "selector",
                                                "label": "Selector",
                                                "hide_label": true,
                                                "field_width": 50,
                                                "dependencies": [
                                                    [
                                                        {
                                                            "field": "el",
                                                            "value": "selector",
                                                            "compare": "="
                                                        }
                                                    ]
                                                ]
                                            }
                                        ],
                                        "layout": "grid",
                                        "style": "auto",
                                        "description_position": "input"
                                    }
                                },
                                {
                                    "type": "Complex",
                                    "name": "element",
                                    "label": "Element",
                                    "field_width": 50,
                                    "group": {
                                        "id": "complex_group",
                                        "title": "Complex Group",
                                        "description": "",
                                        "fields": [
                                            {
                                                "type": "Select",
                                                "name": "el",
                                                "label": "El",
                                                "hide_label": true,
                                                "field_width": 50,
                                                "options": {
                                                    "this": "Componente",
                                                    "selector": "Elemento interno",
                                                    "outer_selector": "Elemento externo"
                                                },
                                                "use_select2": false,
                                                "input_type": "select",
                                                "orientation": "vertical"
                                            },
                                            {
                                                "type": "Text",
                                                "name": "selector",
                                                "label": "Selector",
                                                "hide_label": true,
                                                "field_width": 50,
                                                "dependencies": [
                                                    [
                                                        {
                                                            "field": "el",
                                                            "value": "this",
                                                            "compare": "!="
                                                        }
                                                    ]
                                                ]
                                            }
                                        ],
                                        "layout": "grid",
                                        "style": "auto",
                                        "description_position": "input"
                                    }
                                },
                                {
                                    "type": "Select",
                                    "name": "trigger-hook",
                                    "label": "Trigger Hook",
                                    "field_width": 25,
                                    "options": {
                                        "onEnter": "onEnter",
                                        "onCenter": "onCenter",
                                        "onLeave": "onLeave"
                                    },
                                    "use_select2": false,
                                    "input_type": "select",
                                    "orientation": "vertical"
                                },
                                {
                                    "type": "Text",
                                    "name": "duration",
                                    "label": "Duration",
                                    "field_width": 25,
                                    "default_value": "200"
                                },
                                {
                                    "type": "Text",
                                    "name": "offset",
                                    "label": "Offset",
                                    "field_width": 25,
                                    "default_value": "100px"
                                },
                                {
                                    "type": "Checkbox",
                                    "name": "set_pin",
                                    "label": "Pinned",
                                    "fancy": false,
                                    "text": "Set Pin"
                                },
                                {
                                    "type": "Checkbox",
                                    "name": "trigger_carrusel",
                                    "label": "Trigger Carrusel",
                                    "fancy": false,
                                    "text": "Activar"
                                },
                                {
                                    "type": "Checkbox",
                                    "name": "turn_off_in_mobile",
                                    "label": "Mobile",
                                    "fancy": false,
                                    "text": "Desactivar en móviles"
                                },
                                {
                                    "type": "Checkbox",
                                    "name": "add_indicators",
                                    "label": "Indicadores",
                                    "field_width": 25,
                                    "fancy": false,
                                    "text": "Activar"
                                }
                            ],
                            "layout": "rows",
                            "style": "auto",
                            "description_position": "input"
                        }
                    },
                    {
                        "type": "Complex",
                        "name": "animated_properties",
                        "label": "Animated Properties",
                        "hide_label": true,
                        "field_width": 50,
                        "group": {
                            "id": "complex_group",
                            "title": "Complex Group",
                            "description": "",
                            "fields": [
                                {
                                    "type": "Repeater",
                                    "name": "from",
                                    "label": "Valores CSS iniciales (from)",
                                    "groups": [
                                        {
                                            "id": "property",
                                            "title": "Property",
                                            "description": "",
                                            "fields": [
                                                {
                                                    "type": "Select",
                                                    "name": "property",
                                                    "label": "Property",
                                                    "field_width": 50,
                                                    "options": {
                                                        "opacity": "Opacity",
                                                        "scale": "Scale",
                                                        "x": "Eje Horizontal",
                                                        "y": "Eje Vertical",
                                                        "backgroundColor": "Color de fondo",
                                                        "backgroundPosition": "Posición del fondo",
                                                        "color": "Color de texto",
                                                        "letterSpacing": "Espacio entre letras",
                                                        "rotation": "Rotación",
                                                        "repeat": "Repetir",
                                                        "padding": "Padding",
                                                        "border": "Border",
                                                        "margin": "Margin",
                                                        "boxShadow": "Box Shadow",
                                                        "borderRadius": "Border Radius",
                                                        "filter": "Image Filter",
                                                        "className": "Toggle (+/-=class)"
                                                    },
                                                    "use_select2": false,
                                                    "input_type": "select",
                                                    "orientation": "vertical"
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
                                            "title_template": "<%= value.replace( /(<([^>]+)>)/ig,\"\" ) %>",
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
                                    "type": "Repeater",
                                    "name": "to",
                                    "label": "Valores CSS finales (to)",
                                    "groups": [
                                        {
                                            "id": "property",
                                            "title": "Property",
                                            "description": "",
                                            "fields": [
                                                {
                                                    "type": "Select",
                                                    "name": "property",
                                                    "label": "Property",
                                                    "field_width": 50,
                                                    "options": {
                                                        "opacity": "Opacity",
                                                        "scale": "Scale",
                                                        "x": "Eje Horizontal",
                                                        "y": "Eje Vertical",
                                                        "backgroundColor": "Color de fondo",
                                                        "backgroundPosition": "Posición del fondo",
                                                        "color": "Color de texto",
                                                        "letterSpacing": "Espacio entre letras",
                                                        "rotation": "Rotación",
                                                        "repeat": "Repetir",
                                                        "padding": "Padding",
                                                        "border": "Border",
                                                        "margin": "Margin",
                                                        "boxShadow": "Box Shadow",
                                                        "borderRadius": "Border Radius",
                                                        "filter": "Image Filter",
                                                        "className": "Toggle (+/-=class)"
                                                    },
                                                    "use_select2": false,
                                                    "input_type": "select",
                                                    "orientation": "vertical"
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
                                            "title_template": "<%= value.replace( /(<([^>]+)>)/ig,\"\" ) %>",
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
                                }
                            ],
                            "layout": "grid",
                            "style": "auto",
                            "description_position": "input"
                        }
                    }
                ],
                "layout": "grid",
                "style": "auto",
                "description_position": "input",
                "title_template": "<%= settings[\"element\"][\"selector\"] %>",
                "edit_mode": "popup"
            }
        ],
        "minimum": 0,
        "maximum": 0,
        "chooser_type": "widgets",
        "add_text": "Add animation",
        "placeholder_text": "Please click the \"Add animation\" button to add a new entry.",
        "background": "",
        "layout": "normal"
    }
];