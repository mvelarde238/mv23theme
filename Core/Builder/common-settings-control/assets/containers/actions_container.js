var actions_container = [
    {
        "type": "Repeater",
        "name": "actions",
        "label": "Actions",
        "groups": [
            {
                "id": "action",
                "title": "Action",
                "description": "",
                "fields": [
                    {
                        "type": "Select",
                        "name": "trigger",
                        "label": "Trigger",
                        "field_width": 25,
                        "options": {
                            "click": "Click"
                        },
                        "use_select2": false,
                        "input_type": "select",
                        "orientation": "vertical"
                    },
                    {
                        "type": "Select",
                        "name": "action",
                        "label": "Action",
                        "field_width": 75,
                        "options": {
                            "": "Seleccionar",
                            "open-page": "Abrir nueva página",
                            "open-image-popup": "Mostrar imágen en pop up",
                            "open-video-popup": "Mostrar video en pop up",
                            "toggle-box": "Mostrar / Ocultar Sección",
                            "offcanvas-element": "Mostrar Off-Canvas Element"
                        },
                        "use_select2": false,
                        "input_type": "select",
                        "orientation": "vertical"
                    },
                    {
                        "type": "Complex",
                        "name": "enlace",
                        "label": "Enlace",
                        "hide_label": true,
                        "dependencies": [
                            [
                                {
                                    "field": "action",
                                    "value": "open-page",
                                    "compare": "="
                                }
                            ]
                        ],
                        "group": {
                            "id": "complex_group",
                            "title": "Complex Group",
                            "description": "",
                            "fields": [
                                {
                                    "type": "Select",
                                    "name": "url_type",
                                    "label": "Seleccione que contenido se abrirá al hacer clic:",
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
                                    "dependencies": [
                                        [
                                            {
                                                "field": "url_type",
                                                "value": "interna",
                                                "compare": "="
                                            }
                                        ]
                                    ],
                                    "nonce": "118deb26d1",
                                    "multiple": false,
                                    "button_text": "Selecciona la página",
                                    "hide_filters": false
                                },
                                {
                                    "type": "Text",
                                    "name": "url",
                                    "label": "URL Externa",
                                    "dependencies": [
                                        [
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
                                    "label": "New Tab",
                                    "hide_label": true,
                                    "fancy": false,
                                    "text": "Abrir en una nueva ventana."
                                }
                            ],
                            "layout": "rows",
                            "style": "auto",
                            "description_position": "input"
                        }
                    },
                    {
                        "type": "Complex",
                        "name": "image_popup",
                        "label": "Image Popup",
                        "hide_label": true,
                        "dependencies": [
                            [
                                {
                                    "field": "action",
                                    "value": "open-image-popup",
                                    "compare": "="
                                }
                            ]
                        ],
                        "group": {
                            "id": "complex_group",
                            "title": "Complex Group",
                            "description": "",
                            "fields": [
                                {
                                    "type": "Image",
                                    "name": "internal_image",
                                    "label": "Internal Image",
                                    "hide_label": true,
                                    "file_type": "image",
                                    "basic": false,
                                    "nonce": "c82be97836"
                                }
                            ],
                            "layout": "rows",
                            "style": "auto",
                            "description_position": "input"
                        }
                    },
                    {
                        "type": "Complex",
                        "name": "video_popup",
                        "label": "Video Popup",
                        "hide_label": true,
                        "dependencies": [
                            [
                                {
                                    "field": "action",
                                    "value": "open-video-popup",
                                    "compare": "="
                                }
                            ]
                        ],
                        "group": {
                            "id": "complex_group",
                            "title": "Complex Group",
                            "description": "",
                            "fields": [
                                {
                                    "type": "Select",
                                    "name": "video_source",
                                    "label": "Seleccione el origen del video:",
                                    "field_width": 50,
                                    "options": {
                                        "selfhosted": "Medios",
                                        "external": "Externo"
                                    },
                                    "use_select2": false,
                                    "input_type": "radio",
                                    "orientation": "horizontal"
                                },
                                {
                                    "type": "Embed",
                                    "name": "external_video",
                                    "label": "External Video",
                                    "field_width": 50,
                                    "dependencies": [
                                        [
                                            {
                                                "field": "video_source",
                                                "value": "external",
                                                "compare": "="
                                            }
                                        ]
                                    ],
                                    "field_index": 3,
                                    "nonce": "44d4643e74"
                                },
                                {
                                    "type": "Video",
                                    "name": "internal_video",
                                    "label": "Internal Video",
                                    "field_width": 50,
                                    "dependencies": [
                                        [
                                            {
                                                "field": "video_source",
                                                "value": "selfhosted",
                                                "compare": "="
                                            }
                                        ]
                                    ],
                                    "file_type": "video",
                                    "basic": false,
                                    "nonce": "db7450401e"
                                }
                            ],
                            "layout": "rows",
                            "style": "auto",
                            "description_position": "input"
                        }
                    },
                    {
                        "type": "Complex",
                        "name": "toggle_box_settings",
                        "label": "Toggle Box Settings",
                        "hide_label": true,
                        "dependencies": [
                            [
                                {
                                    "field": "action",
                                    "value": "toggle-box",
                                    "compare": "="
                                }
                            ]
                        ],
                        "group": {
                            "id": "complex_group",
                            "title": "Complex Group",
                            "description": "",
                            "fields": [
                                {
                                    "type": "Text",
                                    "name": "selector",
                                    "label": "Selector",
                                    "validation_rule": "^[a-z][a-za-z0-9_-]+$",
                                    "field_width": 50,
                                    "description": "<p>Selector -ID o CLASS- de la sección que se va mostrar / ocultar, usar solo minúsculas y guiones ( - )</p>\n"
                                },
                                {
                                    "type": "Checkbox",
                                    "name": "scroll_to_box",
                                    "label": "Scroll To Box",
                                    "fancy": false,
                                    "text": "Scroll page to box."
                                }
                            ],
                            "layout": "rows",
                            "style": "auto",
                            "description_position": "input"
                        }
                    },
                    {
                        "type": "Complex",
                        "name": "offcanvas_elements_settings",
                        "label": "Offcanvas Elements Settings",
                        "hide_label": true,
                        "dependencies": [
                            [
                                {
                                    "field": "action",
                                    "value": "offcanvas-element",
                                    "compare": "="
                                }
                            ]
                        ],
                        "group": {
                            "id": "complex_group",
                            "title": "Complex Group",
                            "description": "",
                            "fields": [
                                {
                                    "type": "WP_Object",
                                    "name": "id",
                                    "label": "Id",
                                    "hide_label": true,
                                    "nonce": "508aaa1c70",
                                    "multiple": false,
                                    "button_text": "Select",
                                    "hide_filters": false
                                }
                            ],
                            "layout": "rows",
                            "style": "auto",
                            "description_position": "input"
                        }
                    }
                ],
                "layout": "grid",
                "style": "auto",
                "description_position": "input",
                "title_template": "",
                "edit_mode": "inline"
            }
        ],
        "minimum": 0,
        "maximum": 0,
        "chooser_type": "widgets",
        "add_text": "Add Action",
        "placeholder_text": "Please click the \"Add Action\" button to add a new entry.",
        "background": "",
        "layout": "normal"
    }
];