var blocks_layout_settings_container = [
    {
        "type": "Select",
        "name": "layout",
        "label": "Layout",
        "options": {
            "grid": "Grid",
            "flex": "Flex"
        },
        "use_select2": false,
        "input_type": "radio",
        "orientation": "horizontal"
    },
    {
        "type": "Message",
        "name": "hint_1",
        "label": "Hint 1",
        "hide_label": true,
        "description": "<p>The width of the components will be as configured</p>\n",
        "dependencies": [
            [
                {
                    "field": "layout",
                    "value": "grid",
                    "compare": "="
                }
            ]
        ]
    },
    {
        "type": "Message",
        "name": "hint_2",
        "label": "Hint 2",
        "hide_label": true,
        "description": "<p>The width of the components will be determined by their content.</p>\n",
        "dependencies": [
            [
                {
                    "field": "layout",
                    "value": "flex",
                    "compare": "="
                }
            ]
        ]
    },
    {
        "type": "Select",
        "name": "justify_content",
        "label": "Justify Content",
        "options": {
            "flex-start": "Start",
            "center": "Centrar",
            "space-between": "Space Between",
            "flex-end": "End"
        },
        "use_select2": false,
        "input_type": "radio",
        "orientation": "horizontal"
    },
    {
        "type": "Select",
        "name": "align_items",
        "label": "Align Items",
        "options": {
            "flex-start": "Start",
            "center": "Centrar",
            "space-between": "Space Between",
            "flex-end": "End"
        },
        "use_select2": false,
        "input_type": "radio",
        "orientation": "horizontal"
    }
];