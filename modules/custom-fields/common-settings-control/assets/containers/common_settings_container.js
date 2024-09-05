var common_settings_container = [
    {
        "type": "Complex",
        "name": "main_attributes",
        "label": "Main Attributes",
        "group": {
            "id": "complex_group",
            "title": "Complex Group",
            "description": "",
            "fields": [
                {
                    "type": "Text",
                    "name": "id",
                    "label": "ID",
                    "validation_rule": "^[a-z][a-za-z0-9_-]+$",
                    "field_width": 50,
                    "description": "<p>Identificador -ID- de la sección, usar solo minúsculas y guiones ( - )</p>\n"
                },
                {
                    "type": "Text",
                    "name": "class",
                    "label": "Class",
                    "field_width": 50,
                    "description": "<p>Clases de la sección, usar solo minúsculas y guiones ( -/_ )</p>\n"
                }
            ],
            "layout": "grid",
            "style": "auto",
            "description_position": "input"
        }
    },
    {
        "type": "Complex",
        "name": "layout",
        "label": "Estructura",
        "group": {
            "id": "complex_group",
            "title": "Complex Group",
            "description": "",
            "fields": [
                {
                    "type": "Checkbox",
                    "name": "use",
                    "label": "Use",
                    "field_width": 50,
                    "fancy": true,
                    "text": ""
                },
                {
                    "type": "Select",
                    "name": "key",
                    "label": "Estructura",
                    "field_width": 50,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            }
                        ]
                    ],
                    "options": {
                        "layout1": "Estándar",
                        "layout2": "Fondo extendido / Contenido centrado",
                        "layout3": "Todo extendido"
                    },
                    "use_select2": false,
                    "input_type": "select",
                    "orientation": "vertical"
                }
            ],
            "layout": "grid",
            "style": "auto",
            "description_position": "input"
        }
    },
    {
        "type": "Complex",
        "name": "helpers",
        "label": "Helpers",
        "group": {
            "id": "complex_group",
            "title": "Complex Group",
            "description": "",
            "fields": [
                {
                    "type": "Checkbox",
                    "name": "use",
                    "label": "Use",
                    "field_width": 50,
                    "fancy": true,
                    "text": ""
                },
                {
                    "type": "Multiselect",
                    "name": "list",
                    "label": "List",
                    "hide_label": true,
                    "field_width": 50,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            }
                        ]
                    ],
                    "options": {
                        "disable-link-to-embed-conversion": "Disable link to embed conversion",
                        "overflow-scroll": "Overflow scroll",
                        "overflow-hidden": "Overflow hidden",
                        "hide-br": "Ocultar saltos de línea en tablet y móviles",
                        "hide-br-tablet": "Ocultar saltos de línea en tablet",
                        "hide-br-mobile": "Ocultar saltos de línea en móviles",
                        "extend-bg-to-left": "Extend background to left",
                        "extend-bg-to-right": "Extend background to right"
                    },
                    "use_select2": true,
                    "input_type": "checkbox",
                    "orientation": "horizontal"
                }
            ],
            "layout": "grid",
            "style": "auto",
            "description_position": "input"
        }
    },
    {
        "type": "Complex",
        "name": "background_color",
        "label": "Color de fondo",
        "group": {
            "id": "complex_group",
            "title": "Complex Group",
            "description": "",
            "fields": [
                {
                    "type": "Checkbox",
                    "name": "use",
                    "label": "Use",
                    "field_width": 20,
                    "fancy": true,
                    "text": ""
                },
                {
                    "type": "Color",
                    "name": "color",
                    "label": "Color",
                    "field_width": 30,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            }
                        ]
                    ]
                },
                {
                    "type": "Number",
                    "name": "alpha",
                    "label": "Opacity",
                    "field_width": 30,
                    "default_value": 100,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            }
                        ]
                    ],
                    "minimum": 0,
                    "maximum": 100,
                    "step": 1,
                    "slider_enabled": true,
                    "prefix": null,
                    "suffix": null,
                    "placeholder": "0"
                }
            ],
            "layout": "grid",
            "style": "auto",
            "description_position": "input"
        }
    },
    {
        "type": "Complex",
        "name": "font_color",
        "label": "Font color",
        "group": {
            "id": "complex_group",
            "title": "Complex Group",
            "description": "",
            "fields": [
                {
                    "type": "Checkbox",
                    "name": "use",
                    "label": "Use",
                    "field_width": 20,
                    "fancy": true,
                    "text": ""
                },
                {
                    "type": "Select",
                    "name": "color_scheme",
                    "label": "Color Scheme",
                    "field_width": 30,
                    "description": "<p>Some components use the .dark-scheme CSS class to adjust their color styles for better appearance in dark mode.</p>\n",
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            }
                        ]
                    ],
                    "options": {
                        "default_scheme": "Default Scheme",
                        "dark_scheme": "Dark Scheme",
                        "custom": "Custom"
                    },
                    "use_select2": false,
                    "input_type": "select",
                    "orientation": "vertical"
                },
                {
                    "type": "Color",
                    "name": "color",
                    "label": "Color",
                    "field_width": 30,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            },
                            {
                                "field": "color_scheme",
                                "value": "custom",
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
        "name": "background_image",
        "label": "Imagen de fondo",
        "group": {
            "id": "complex_group",
            "title": "Complex Group",
            "description": "",
            "fields": [
                {
                    "type": "Checkbox",
                    "name": "use",
                    "label": "Use",
                    "field_width": 20,
                    "fancy": true,
                    "text": ""
                },
                {
                    "type": "Image",
                    "name": "image",
                    "label": "Image",
                    "field_width": 20,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            }
                        ]
                    ],
                    "file_type": "image",
                    "basic": false,
                    "nonce": "b691ccb624"
                },
                {
                    "type": "Complex",
                    "name": "settings",
                    "label": "Settings",
                    "field_width": 20,
                    "dependencies": [
                        [
                            {
                                "field": "image",
                                "value": "0",
                                "compare": ">"
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
                                "name": "size",
                                "label": "Size",
                                "options": {
                                    "cover": "Cover",
                                    "auto": "Auto"
                                },
                                "use_select2": false,
                                "input_type": "select",
                                "orientation": "vertical"
                            },
                            {
                                "type": "Select",
                                "name": "repeat",
                                "label": "Repeat",
                                "options": {
                                    "no-repeat": "No repeat",
                                    "repeat": "Repeat",
                                    "repeat-x": "Repeat X",
                                    "repeat-y": "Repeat Y"
                                },
                                "use_select2": false,
                                "input_type": "select",
                                "orientation": "vertical"
                            },
                            {
                                "type": "Select",
                                "name": "position_x",
                                "label": "Position X",
                                "options": {
                                    "center": "Centrar",
                                    "left": "Izquierda",
                                    "right": "Derecha"
                                },
                                "use_select2": false,
                                "input_type": "select",
                                "orientation": "vertical"
                            },
                            {
                                "type": "Select",
                                "name": "position_y",
                                "label": "Position Y",
                                "options": {
                                    "center": "Centrar",
                                    "top": "Arriba",
                                    "bottom": "Abajo"
                                },
                                "use_select2": false,
                                "input_type": "select",
                                "orientation": "vertical"
                            },
                            {
                                "type": "Checkbox",
                                "name": "parallax",
                                "label": "Parallax",
                                "fancy": false,
                                "text": ""
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
            "description_position": "input"
        }
    },
    {
        "type": "Complex",
        "name": "video_background",
        "label": "Video background",
        "group": {
            "id": "complex_group",
            "title": "Complex Group",
            "description": "",
            "fields": [
                {
                    "type": "Checkbox",
                    "name": "use",
                    "label": "Use",
                    "field_width": 20,
                    "fancy": true,
                    "text": ""
                },
                {
                    "type": "Select",
                    "name": "video_source",
                    "label": "Fuente",
                    "field_width": 30,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            }
                        ]
                    ],
                    "options": {
                        "selfhosted": "Medios",
                        "external": "External"
                    },
                    "use_select2": false,
                    "input_type": "radio",
                    "orientation": "horizontal"
                },
                {
                    "type": "Video",
                    "name": "video",
                    "label": "Video",
                    "field_width": 30,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            },
                            {
                                "field": "video_source",
                                "value": "selfhosted",
                                "compare": "="
                            }
                        ]
                    ],
                    "file_type": "video",
                    "basic": false,
                    "nonce": "7fcf825ff9"
                },
                {
                    "type": "Embed",
                    "name": "external_url",
                    "label": "URL",
                    "field_width": 30,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            },
                            {
                                "field": "video_source",
                                "value": "external",
                                "compare": "="
                            }
                        ]
                    ],
                    "field_index": 4,
                    "nonce": "c0893a6bbe"
                },
                {
                    "type": "Complex",
                    "name": "video_settings",
                    "label": "Video Settings",
                    "hide_label": true,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
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
                                "type": "Color",
                                "name": "background_color",
                                "label": "Background Color",
                                "field_width": 20,
                                "default_value": "#000000"
                            },
                            {
                                "type": "Checkbox",
                                "name": "autoplay",
                                "label": "Autoplay",
                                "field_width": 20,
                                "fancy": false,
                                "text": "Activar"
                            },
                            {
                                "type": "Checkbox",
                                "name": "muted",
                                "label": "Muted",
                                "field_width": 20,
                                "fancy": false,
                                "text": "Activar"
                            },
                            {
                                "type": "Checkbox",
                                "name": "loop",
                                "label": "Loop",
                                "field_width": 20,
                                "fancy": false,
                                "text": "Activar"
                            },
                            {
                                "type": "Number",
                                "name": "opacity",
                                "label": "Opacity",
                                "field_width": 20,
                                "default_value": 100,
                                "minimum": 0,
                                "maximum": 100,
                                "step": 5,
                                "slider_enabled": true,
                                "prefix": null,
                                "suffix": null,
                                "placeholder": null
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
            "description_position": "input"
        }
    },
    {
        "type": "Complex",
        "name": "margin",
        "label": "Margin",
        "group": {
            "id": "complex_group",
            "title": "Complex Group",
            "description": "",
            "fields": [
                {
                    "type": "Checkbox",
                    "name": "use",
                    "label": "Use",
                    "field_width": 20,
                    "fancy": true,
                    "text": ""
                },
                {
                    "type": "Number",
                    "name": "top",
                    "label": "Arriba",
                    "field_width": 20,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            }
                        ]
                    ],
                    "minimum": false,
                    "maximum": false,
                    "step": 1,
                    "slider_enabled": false,
                    "prefix": null,
                    "suffix": "px",
                    "placeholder": "25"
                },
                {
                    "type": "Number",
                    "name": "right",
                    "label": "Derecha",
                    "field_width": 20,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            }
                        ]
                    ],
                    "minimum": false,
                    "maximum": false,
                    "step": 1,
                    "slider_enabled": false,
                    "prefix": null,
                    "suffix": "px",
                    "placeholder": "20"
                },
                {
                    "type": "Number",
                    "name": "bottom",
                    "label": "Abajo",
                    "field_width": 20,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            }
                        ]
                    ],
                    "minimum": false,
                    "maximum": false,
                    "step": 1,
                    "slider_enabled": false,
                    "prefix": null,
                    "suffix": "px",
                    "placeholder": "25"
                },
                {
                    "type": "Number",
                    "name": "left",
                    "label": "Izquierda",
                    "field_width": 20,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            }
                        ]
                    ],
                    "minimum": false,
                    "maximum": false,
                    "step": 1,
                    "slider_enabled": false,
                    "prefix": null,
                    "suffix": "px",
                    "placeholder": "20"
                }
            ],
            "layout": "grid",
            "style": "auto",
            "description_position": "input"
        }
    },
    {
        "type": "Complex",
        "name": "padding",
        "label": "Padding",
        "group": {
            "id": "complex_group",
            "title": "Complex Group",
            "description": "",
            "fields": [
                {
                    "type": "Checkbox",
                    "name": "use",
                    "label": "Use",
                    "field_width": 20,
                    "fancy": true,
                    "text": ""
                },
                {
                    "type": "Number",
                    "name": "top",
                    "label": "Arriba",
                    "field_width": 20,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            }
                        ]
                    ],
                    "minimum": false,
                    "maximum": false,
                    "step": 1,
                    "slider_enabled": false,
                    "prefix": null,
                    "suffix": "px",
                    "placeholder": "0"
                },
                {
                    "type": "Number",
                    "name": "right",
                    "label": "Derecha",
                    "field_width": 20,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            }
                        ]
                    ],
                    "minimum": false,
                    "maximum": false,
                    "step": 1,
                    "slider_enabled": false,
                    "prefix": null,
                    "suffix": "px",
                    "placeholder": "0"
                },
                {
                    "type": "Number",
                    "name": "bottom",
                    "label": "Abajo",
                    "field_width": 20,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            }
                        ]
                    ],
                    "minimum": false,
                    "maximum": false,
                    "step": 1,
                    "slider_enabled": false,
                    "prefix": null,
                    "suffix": "px",
                    "placeholder": "0"
                },
                {
                    "type": "Number",
                    "name": "left",
                    "label": "Izquierda",
                    "field_width": 20,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            }
                        ]
                    ],
                    "minimum": false,
                    "maximum": false,
                    "step": 1,
                    "slider_enabled": false,
                    "prefix": null,
                    "suffix": "px",
                    "placeholder": "0"
                }
            ],
            "layout": "grid",
            "style": "auto",
            "description_position": "input"
        }
    },
    {
        "type": "Complex",
        "name": "border_radius",
        "label": "Border Radius",
        "group": {
            "id": "complex_group",
            "title": "Complex Group",
            "description": "",
            "fields": [
                {
                    "type": "Checkbox",
                    "name": "use",
                    "label": "Use",
                    "field_width": 20,
                    "fancy": true,
                    "text": ""
                },
                {
                    "type": "Number",
                    "name": "top_left",
                    "label": "Arriba a la izquierda",
                    "field_width": 20,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            }
                        ]
                    ],
                    "minimum": false,
                    "maximum": false,
                    "step": 1,
                    "slider_enabled": false,
                    "prefix": null,
                    "suffix": "px",
                    "placeholder": "0"
                },
                {
                    "type": "Number",
                    "name": "top_right",
                    "label": "Arriba a la derecha",
                    "field_width": 20,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            }
                        ]
                    ],
                    "minimum": false,
                    "maximum": false,
                    "step": 1,
                    "slider_enabled": false,
                    "prefix": null,
                    "suffix": "px",
                    "placeholder": "0"
                },
                {
                    "type": "Number",
                    "name": "bottom_right",
                    "label": "Abajo a la derecha",
                    "field_width": 20,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            }
                        ]
                    ],
                    "minimum": false,
                    "maximum": false,
                    "step": 1,
                    "slider_enabled": false,
                    "prefix": null,
                    "suffix": "px",
                    "placeholder": "0"
                },
                {
                    "type": "Number",
                    "name": "bottom_left",
                    "label": "Abajo a la izquierda",
                    "field_width": 20,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            }
                        ]
                    ],
                    "minimum": false,
                    "maximum": false,
                    "step": 1,
                    "slider_enabled": false,
                    "prefix": null,
                    "suffix": "px",
                    "placeholder": "0"
                }
            ],
            "layout": "grid",
            "style": "auto",
            "description_position": "input"
        }
    },
    {
        "type": "Complex",
        "name": "border",
        "label": "Borde",
        "group": {
            "id": "complex_group",
            "title": "Complex Group",
            "description": "",
            "fields": [
                {
                    "type": "Checkbox",
                    "name": "use",
                    "label": "Use",
                    "field_width": 50,
                    "fancy": true,
                    "text": ""
                },
                {
                    "type": "Checkbox",
                    "name": "unlock",
                    "label": "Unlock",
                    "field_width": 50,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            }
                        ]
                    ],
                    "fancy": true,
                    "text": ""
                },
                {
                    "type": "Complex",
                    "name": "top",
                    "label": "Top",
                    "hide_label": true,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
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
                                "type": "Number",
                                "name": "width",
                                "label": "Border Top",
                                "field_width": 10,
                                "default_value": "1",
                                "minimum": false,
                                "maximum": false,
                                "step": 1,
                                "slider_enabled": false,
                                "prefix": null,
                                "suffix": "px",
                                "placeholder": null
                            },
                            {
                                "type": "Select",
                                "name": "style",
                                "label": "Style",
                                "field_width": 20,
                                "options": {
                                    "solid": "solid",
                                    "dotted": "dotted",
                                    "dashed": "dashed",
                                    "double": "double",
                                    "groove": "groove",
                                    "ridge": "ridge",
                                    "inset": "inset",
                                    "outset": "outset"
                                },
                                "use_select2": false,
                                "input_type": "select",
                                "orientation": "vertical"
                            },
                            {
                                "type": "Color",
                                "name": "color",
                                "label": "Color",
                                "field_width": 40
                            }
                        ],
                        "layout": "grid",
                        "style": "auto",
                        "description_position": "input"
                    }
                },
                {
                    "type": "Complex",
                    "name": "right",
                    "label": "Right",
                    "hide_label": true,
                    "dependencies": [
                        [
                            {
                                "field": "unlock",
                                "value": true,
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
                                "type": "Number",
                                "name": "width",
                                "label": "Border Right",
                                "field_width": 10,
                                "default_value": "1",
                                "minimum": false,
                                "maximum": false,
                                "step": 1,
                                "slider_enabled": false,
                                "prefix": null,
                                "suffix": "px",
                                "placeholder": null
                            },
                            {
                                "type": "Select",
                                "name": "style",
                                "label": "Style",
                                "field_width": 20,
                                "options": {
                                    "solid": "solid",
                                    "dotted": "dotted",
                                    "dashed": "dashed",
                                    "double": "double",
                                    "groove": "groove",
                                    "ridge": "ridge",
                                    "inset": "inset",
                                    "outset": "outset"
                                },
                                "use_select2": false,
                                "input_type": "select",
                                "orientation": "vertical"
                            },
                            {
                                "type": "Color",
                                "name": "color",
                                "label": "Color",
                                "field_width": 40
                            }
                        ],
                        "layout": "grid",
                        "style": "auto",
                        "description_position": "input"
                    }
                },
                {
                    "type": "Complex",
                    "name": "bottom",
                    "label": "Bottom",
                    "hide_label": true,
                    "dependencies": [
                        [
                            {
                                "field": "unlock",
                                "value": true,
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
                                "type": "Number",
                                "name": "width",
                                "label": "Border Bottom",
                                "field_width": 10,
                                "default_value": "1",
                                "minimum": false,
                                "maximum": false,
                                "step": 1,
                                "slider_enabled": false,
                                "prefix": null,
                                "suffix": "px",
                                "placeholder": null
                            },
                            {
                                "type": "Select",
                                "name": "style",
                                "label": "Style",
                                "field_width": 20,
                                "options": {
                                    "solid": "solid",
                                    "dotted": "dotted",
                                    "dashed": "dashed",
                                    "double": "double",
                                    "groove": "groove",
                                    "ridge": "ridge",
                                    "inset": "inset",
                                    "outset": "outset"
                                },
                                "use_select2": false,
                                "input_type": "select",
                                "orientation": "vertical"
                            },
                            {
                                "type": "Color",
                                "name": "color",
                                "label": "Color",
                                "field_width": 40
                            }
                        ],
                        "layout": "grid",
                        "style": "auto",
                        "description_position": "input"
                    }
                },
                {
                    "type": "Complex",
                    "name": "left",
                    "label": "Left",
                    "hide_label": true,
                    "dependencies": [
                        [
                            {
                                "field": "unlock",
                                "value": true,
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
                                "type": "Number",
                                "name": "width",
                                "label": "Border Left",
                                "field_width": 10,
                                "default_value": "1",
                                "minimum": false,
                                "maximum": false,
                                "step": 1,
                                "slider_enabled": false,
                                "prefix": null,
                                "suffix": "px",
                                "placeholder": null
                            },
                            {
                                "type": "Select",
                                "name": "style",
                                "label": "Style",
                                "field_width": 20,
                                "options": {
                                    "solid": "solid",
                                    "dotted": "dotted",
                                    "dashed": "dashed",
                                    "double": "double",
                                    "groove": "groove",
                                    "ridge": "ridge",
                                    "inset": "inset",
                                    "outset": "outset"
                                },
                                "use_select2": false,
                                "input_type": "select",
                                "orientation": "vertical"
                            },
                            {
                                "type": "Color",
                                "name": "color",
                                "label": "Color",
                                "field_width": 40
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
            "description_position": "input"
        }
    },
    {
        "type": "Complex",
        "name": "box_shadow",
        "label": "Box Shadow",
        "group": {
            "id": "complex_group",
            "title": "Complex Group",
            "description": "",
            "fields": [
                {
                    "type": "Checkbox",
                    "name": "use",
                    "label": "Use",
                    "fancy": true,
                    "text": ""
                },
                {
                    "type": "Repeater",
                    "name": "box_shadow",
                    "label": "",
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            }
                        ]
                    ],
                    "groups": [
                        {
                            "id": "shadow",
                            "title": "Shadow",
                            "description": "",
                            "fields": [
                                {
                                    "type": "Tab",
                                    "name": "Básico",
                                    "label": "Básico"
                                },
                                {
                                    "type": "Text",
                                    "name": "h-offset",
                                    "label": "Distancia Horizontal",
                                    "tab": "Básico",
                                    "field_width": 15,
                                    "suffix": "px"
                                },
                                {
                                    "type": "Text",
                                    "name": "v-offset",
                                    "label": "Distancia Vertical",
                                    "tab": "Básico",
                                    "field_width": 15,
                                    "suffix": "px"
                                },
                                {
                                    "type": "Text",
                                    "name": "blur",
                                    "label": "Desenfoque",
                                    "tab": "Básico",
                                    "field_width": 15,
                                    "default_value": "15",
                                    "suffix": "px"
                                },
                                {
                                    "type": "Color",
                                    "name": "color",
                                    "label": "Color",
                                    "tab": "Básico",
                                    "field_width": 30,
                                    "default_value": "#232323"
                                },
                                {
                                    "type": "Tab",
                                    "name": "Avanzado",
                                    "label": "Avanzado"
                                },
                                {
                                    "type": "Number",
                                    "name": "alpha",
                                    "label": "Porcentaje de Intensidad",
                                    "tab": "Avanzado",
                                    "field_width": 50,
                                    "default_value": 15,
                                    "minimum": 0,
                                    "maximum": 100,
                                    "step": 5,
                                    "slider_enabled": true,
                                    "prefix": null,
                                    "suffix": null,
                                    "placeholder": null
                                },
                                {
                                    "type": "Select",
                                    "name": "position",
                                    "label": "Posición",
                                    "tab": "Avanzado",
                                    "field_width": 25,
                                    "options": {
                                        "outset": "Exterior",
                                        "inset": "Interior"
                                    },
                                    "use_select2": false,
                                    "input_type": "select",
                                    "orientation": "vertical"
                                },
                                {
                                    "type": "Text",
                                    "name": "spread",
                                    "label": "Spread",
                                    "tab": "Avanzado",
                                    "field_width": 25,
                                    "description": "<p>A positive value increases the size of the shadow, a negative value decreases the size of the shadow</p>\n",
                                    "suffix": "px"
                                }
                            ],
                            "layout": "grid",
                            "style": "auto",
                            "description_position": "input",
                            "title_template": "<%= h-offset.replace( /(<([^>]+)>)/ig,\"\" ) %>",
                            "edit_mode": "inline"
                        }
                    ],
                    "minimum": 0,
                    "maximum": 0,
                    "chooser_type": "widgets",
                    "add_text": "Agregar",
                    "placeholder_text": "Please click the \"Agregar\" button to add a new entry.",
                    "background": "",
                    "layout": "normal"
                }
            ],
            "layout": "grid",
            "style": "auto",
            "description_position": "input"
        }
    },
    {
        "type": "Complex",
        "name": "visibility",
        "label": "Visibility",
        "group": {
            "id": "complex_group",
            "title": "Complex Group",
            "description": "",
            "fields": [
                {
                    "type": "Checkbox",
                    "name": "use",
                    "label": "Use",
                    "field_width": 50,
                    "fancy": true,
                    "text": ""
                },
                {
                    "type": "Select",
                    "name": "key",
                    "label": "Visibility",
                    "field_width": 50,
                    "dependencies": [
                        [
                            {
                                "field": "use",
                                "value": true,
                                "compare": "="
                            }
                        ]
                    ],
                    "options": {
                        "all": "Visible para todos los usuarios",
                        "user_is_logged_in": "Visible para usuarios registrados",
                        "user_is_not_logged_in": "Visible para usuarios no registrados",
                        "is_private": "Solo visible para usuarios admin."
                    },
                    "use_select2": false,
                    "input_type": "select",
                    "orientation": "vertical"
                }
            ],
            "layout": "grid",
            "style": "auto",
            "description_position": "input"
        }
    },
    {
        "type": "Complex",
        "name": "responsive",
        "label": "Responsive",
        "group": {
            "id": "complex_group",
            "title": "Complex Group",
            "description": "",
            "fields": [
                {
                    "type": "Checkbox",
                    "name": "hide_on_mobile",
                    "label": "Hide On Mobile",
                    "field_width": 30,
                    "fancy": true,
                    "text": ""
                },
                {
                    "type": "Checkbox",
                    "name": "hide_on_tablet",
                    "label": "Hide On Tablet",
                    "field_width": 30,
                    "fancy": true,
                    "text": ""
                },
                {
                    "type": "Checkbox",
                    "name": "hide_on_desktop",
                    "label": "Hide On Desktop",
                    "field_width": 30,
                    "fancy": true,
                    "text": ""
                }
            ],
            "layout": "grid",
            "style": "auto",
            "description_position": "input"
        }
    }
];