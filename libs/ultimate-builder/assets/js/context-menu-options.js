function create_text_align_actions(component){
    let textAlignAction = {
        type: 'options', title: 'TEXT ALIGN',
        options: [],
    };

    const icon_names = {
        left: 'bi-justify-left',
        center: 'bi-text-center',
        right: 'bi-justify-right'
    };

    ['left','center','right'].forEach( item => {
        let innerAction = (item === 'toggle') 
            ? { type: 'toggle' }
            : { 
                type: 'button', 
                rerender: true,
                class: ()=>{
                    let textAlign = component.getStyle('text-align') || 'left'; // TO CHECK: in mobile getStyle dosnt get upper devices styles
                    let actionClass = (item == textAlign) ? 'active' : '';
                    return actionClass;
                },
                command: 'update-css-property',
                label: '<i class="bi ' + icon_names[item] + '"></i>', 
                args: { property: 'text-align', value: item } 
            };
        textAlignAction.options.push( innerAction );
    });

    return textAlignAction;
}

function layout_options(component, editor){
    const layoutActions = [
        { id: 'layout1', name: 'BOXED' },
        { id: 'layout3', name: 'FULL WIDTH' },
        { type: 'break' },
        { id: 'layout2', name: 'FULL WIDTH STRETCHED' },
    ];
    let layoutOptions = layoutActions.map( layout => {
        if( layout.type && layout.type === 'break' ){
            return { type: 'break' };
        }
        return {
            type: 'button',
            label: layout.name,
            class: ()=>{
                let datastore = editor.getComponentDatastore( component );
                let current_layout = datastore ? (datastore.get('settings') || {}).layout?.key : 'layout1';
                return (layout.id === current_layout) ? 'active' : '';
            },
            rerender: true,
            command: 'set-section-layout',
            args: { layout: layout.id }
        }
    });
    return {
        type: 'options', title: 'LAYOUT',
        options: layoutOptions
    };
}

function color_scheme_options(component, editor){
    const layoutActions = [
        { id: 'light-mode', name: 'LIGHT MODE' },
        { id: 'dark-mode', name: 'DARK MODE' },
    ];
    let colorSchemeOptions = layoutActions.map( scheme => {
        if( scheme.type && scheme.type === 'break' ){
            return { type: 'break' };
        }
        return {
            type: 'button',
            label: scheme.name,
            class: ()=>{
                let datastore = editor.getComponentDatastore( component );
                let current_scheme = datastore ? (datastore.get('settings') || {}).color_scheme?.key : 'light-mode';
                return (scheme.id === current_scheme) ? 'active' : '';
            },
            rerender: true,
            command: 'set-color-scheme',
            args: { scheme: scheme.id }
        }
    });
    return {
        type: 'options', title: 'COLOR SCHEME',
        options: colorSchemeOptions
    };
}

function content_alignment_options(component, editor, flex_direction){
    const alignment_options = [];
    const ccaCmd = 'update-css-property';

    const componentType = component.getType();
    const defaultValues = {
        'justify-content': (componentType.startsWith('flipbox')) ? 'safe center' : 'flex-start',
        'align-items': (componentType.startsWith('flipbox')) ? 'safe center' : 'stretch',
    };

    const centerValue = (componentType.startsWith('flipbox')) ? 'safe center' : 'center';

    const contentAlignmentOptions = [
        { property:'justify-content', value:'flex-start', tooltip:'Top', icon:'' },
        { property:'justify-content', value:centerValue, tooltip:'Middle', icon:'' },
        { property:'justify-content', value:'flex-end', tooltip:'Bottom', icon:'' },
        { type:'break' },
        { property:'align-items', value:'flex-start', tooltip:'Start', icon:'' },
        { property:'align-items', value:centerValue, tooltip:'Center', icon:'' },
        { property:'align-items', value:'flex-end', tooltip:'End', icon:'' },
        { type:'break' },
        { property:'justify-content', value:'space-around', tooltip:'Around', icon:'' },
        { property:'justify-content', value:'space-between', tooltip:'Between', icon:'' },
        { property:'justify-content', value:'space-evenly', tooltip:'Evenly', icon:'' },
        { type:'break' },
        { property:'align-items', value:'stretch', tooltip:'Stretch', icon:'' },
    ];

    contentAlignmentOptions.forEach(option => {
        if( option.type && option.type === 'break' ){
            alignment_options.push({ type: 'break' });
            return;
        }
        if( option.type && option.type === 'toggle' ){
            alignment_options.push({ type: 'toggle' });
            return;
        }

        // Determine icon based on flex direction and alignment value
        let icon = '';
        if( option.property === 'justify-content' ){
            if( option.value === 'flex-start' ) icon = (flex_direction === 'column') ? 'bi-align-top' : 'bi-align-start';
            else if( option.value === 'center' || option.value === 'safe center' ) icon = (flex_direction === 'column') ? 'bi-align-middle' : 'bi-align-center';
            else if( option.value === 'flex-end' ) icon = (flex_direction === 'column') ? 'bi-align-bottom' : 'bi-align-end';
            else if( option.value === 'space-between' ) icon = (flex_direction === 'column') ? 'bi-distribute-vertical' : 'bi-distribute-horizontal';
        }
        else if( option.property === 'align-items' ){
            if( option.value === 'flex-start' ) icon = (flex_direction === 'column') ? 'bi-align-start' : 'bi-align-top';
            else if( option.value === 'center' || option.value === 'safe center' ) icon = (flex_direction === 'column') ? 'bi-align-center' : 'bi-align-middle';
            else if( option.value === 'flex-end' ) icon = (flex_direction === 'column') ? 'bi-align-end' : 'bi-align-bottom';
        }

        // Determine if the current option is active based on the component's styles
        let className = '';
        let currentValue = editor.getComponentStyle(component, option.property, defaultValues[option.property]);
        if( currentValue === option.value ){
            className = 'active';
        }

        alignment_options.push({ 
            type: 'button', 
            label: icon ? '<i class="bi ' + icon + '"></i>' : option.tooltip,
            titleTooltip: option.tooltip,
            class: className,
            rerender: {full: true},
            command: ccaCmd, 
            args:{ property: option.property, value: option.value }
        });
    });

    return {
        type: 'options',
        title: 'CONTENT ALIGNMENT',
        options: alignment_options
    };
}

function get_locked_cmps_action(component){
    return {
        type: 'button', command: 'locked-components-toggle', rerender:{partial:true},
        class: ()=>{
            const lockedComponents = component.get('lockedComponents');
            return (lockedComponents) ? 'active' : ''; 
        }, 
        label: ()=>{
            const lockedComponents = component.get('lockedComponents');
            return (lockedComponents) ? 'UNLOCK INNER COMPONENTS' : 'LOCK INNER COMPONENTS'; 
        }, 
    };
}

function get_flipbox_side_options(component, editor){
    return [
        {
            type: 'options', title: 'BACKGROUND & COLOR', titleKey: 'background_and_color',
            options: [
                {
                    type:'color', command:'update-css-property', args: { property:'background-color' },
                    rerender: { partial: true },
                    value: ()=>{ 
                        const backgroundColor = component.getStyle('background-color') || '';
                        return backgroundColor; 
                    }, 
                },
                { 
                    type:'color', command:'update-css-property', args: { property:'color' },
                    rerender: { partial: true },
                    value: ()=>{
                        const color = component.getStyle('color') || '#000000';
                        return color; 
                    }, 
                },
            ],
        },
        content_alignment_options(component, editor, 'column'),
    ]
}

window['contextMenuOpts'] = {
    actions: {
        ['text-editor']: function(component){
            let textAlignAction = create_text_align_actions(component);

            const getFontSize = ()=>{
                let value = parseInt(component.getStyle('font-size')) || 17;
                return value;
            };
            return [
                { type:'range', title:'FONT SIZE', command:'update-css-property', min:0, value:getFontSize, args: { property: 'font-size', unit: 'px' } },
                textAlignAction,
                { 
                    type: 'color', title: 'TEXT COLOR', command: 'update-css-property', rerender: { partial: true },
                    args: { property: 'color' }, value: ()=>{ return component.getStyle('color') || ''; } 
                },
            ]
        },
        heading: function(component){
            return [
                {
                    type: 'options', title: 'SELECT ELEMENT',
                    options: [
                        { type: 'button', label: 'HEADING', command: 'query-selector', args: { selector: '.heading__text' } },
                        { type: 'button', label: 'TAGLINE', command: 'query-selector', args: { selector: '.heading__tagline' } },
                    ]
                },
                { 
                    type: 'range', title: 'SPACE BETWEEN ELEMENTS', command: 'update-css-property', min:0, max:100, 
                    args: { property:'gap', unit:'px' }, value: ()=>{
                        let value = parseInt(component.getStyle('gap')) || 8;
                        return value;
                    }
                },
            ]
        },
        button: function(component){
            return [
                {
                    type: 'options', title: 'BACKGROUND & COLOR', titleKey: 'background_and_color',
                    options: [
                        { 
                            type:'color', command:'update-css-property', args: { property:'background-color' },
                            rerender: { partial: true },
                            value: ()=>{ 
                                const backgroundColor = component.getStyle('background-color') || '';
                                return backgroundColor; 
                            }, 
                        },
                        {
                            type:'color', command:'update-css-property', args: { property:'color' },
                            rerender: { partial: true },
                            value: ()=>{
                                const color = component.getStyle('color') || '#000000';
                                return color;
                            }, 
                        },
                    ],
                },
                {
                    type: 'range', title: 'FONT SIZE', command: 'update-css-property', min:0, max:100, args: { property:'font-size', unit:'px' },
                    value: ()=>{
                        let value = parseInt(component.getStyle('font-size')) || 17;
                        return value;
                    },
                }
            ]
        },
        menu: function(component){
            return [
                { type: 'button', label: 'SELECT LINKS', command: 'query-selector', args: { selector: 'a' } },
                { type: 'button', label: 'SELECT HOVERED LINKS', command: 'query-selector', args: { selector: 'a:hover' } },
            ]
        },
        section: function(component, editor){
            return [
                layout_options(component, editor),
                {
                    type: 'options', title: 'ADD SECTION',
                    options: [
                        {
                            type: 'button',
                            label: 'ABOVE',
                            command: 'add-section',
                            args: { position: 'above' }
                        },
                        {
                            type: 'button',
                            label: 'BELOW',
                            command: 'add-section',
                            args: { position: 'below' }
                        }
                    ]
                },
                color_scheme_options(component, editor),
            ]
        },
        ['components-wrapper']: function(component, editor){
            let actions = [],
                actions_group_1 = [],
                actions_group_2 = [];
            
            const flex_direction = editor.getComponentStyle(component, 'flex-direction', 'column');

            const contentAlignmentOptions = content_alignment_options(component, editor, flex_direction);

            const getGap = ()=>{
                let value = parseInt(component.getStyle('gap'));
                if(isNaN(value)) value = 12;
                return value;
            };

            actions_group_1.push(layout_options(component, editor));
            actions_group_1.push({ 
                type: 'range', title:'SPACE BETWEEN COMPONENTS', command: 'update-css-property', min:0, max:100, 
                args: { property:'gap', unit:'px' }, value:getGap 
            });

            actions_group_1.push(get_locked_cmps_action(component));

            actions_group_2.push({
                type: 'options', title: 'FLEX DIRECTION',
                options: [
                    { 
                        type: 'button', label: 'VERTICAL', 
                        class: (flex_direction === 'column') ? 'active' : '', 
                        command: 'update-css-property', rerender: {full:true}, args: { property:'flex-direction', value:'column' } 
                    },
                    { 
                        type: 'button', label: 'HORIZONTAL',
                        class: (flex_direction === 'row') ? 'active' : '',
                        command: 'update-css-property', rerender: {full:true}, args: { property:'flex-direction', value:'row' } 
                    },
                ]
            });
            actions_group_2.push(contentAlignmentOptions);

            actions.push({
                type: 'options',
                gap: 30,
                options: [
                    {
                        type: 'options',
                        class: 'column',
                        options: actions_group_1
                    },
                    {
                        type: 'options',
                        class: 'column',
                        options: actions_group_2
                    },
                ]
            });

            return actions;
        },
        flipbox: function(component, editor){
            return [
                get_locked_cmps_action(component)
            ]
        },
        wrapper: function(component, editor){
            let actions = [
                {
                    type: 'button',
                    label: 'EDIT THEME OPTIONS',
                    command: 'select-theme-options'
                },
                color_scheme_options(component, editor),
            ];

            return actions;
        },
        ['icon-and-text']: function(component, editor){
            return [
                {
                    type: 'range', title: 'SPACE BETWEEN ELEMENTS', command: 'update-css-property', 
                    min:0, max:100, args: { property:'gap', unit:'px' }, titleTooltip: 'Space between Icon and Text',
                    value: ()=>{
                        const gap = parseInt(component.getStyle('gap')) || 20;
                        return gap;
                    },
                },
                { type: 'button', label: 'SELECT ICON ELEMENT', command: 'query-selector', args: { selector: '.icon-box__icon' } }
            ];   
        },
        ['icon-box']: function(component, editor){
            const getIconSize = ()=>{
                let value = parseInt(component.getStyle('font-size')) || 40;
                return value;
            };

            return [
                { 
                    type: 'options',
                    gap: 30,
                    options: [
                        {
                            type: 'options',
                            class: 'column',
                            options: [
                                { 
                                    type: 'range', title: 'ICON SIZE', command: 'update-css-property', 
                                    value:getIconSize, min:15, max:200, args: { property:'font-size', unit:'px' }
                                },
                                {
                                    type: 'options', title: 'BACKGROUND & COLOR', titleKey: 'background_and_color',
                                    options: [
                                        {
                                            type:'color', command:'update-css-property', args: { property:'background-color' },
                                            rerender: { partial: true },
                                            value: ()=>{ 
                                                const backgroundColor = component.getStyle('background-color') || '';
                                                return backgroundColor; 
                                            }, 
                                        },
                                        { 
                                            type:'color', command:'update-css-property', args: { property:'color' },
                                            rerender: { partial: true },
                                            value: ()=>{
                                                const color = component.getStyle('color') || '#000000';
                                                return color; 
                                            }, 
                                        },
                                    ],
                                },
                                { 
                                    type: 'range', title: 'SPACE AROUND ICON', command: 'update-css-property', 
                                    min:0, max:100, args: { property:'padding', unit:'px' },
                                    value: ()=>{
                                        const padding = parseInt(component.getStyle('padding')) || 0;
                                        return padding;
                                    }, 
                                },
                            ]
                        },
                        {
                            type: 'options',
                            class: 'column',
                            options: [
                                { 
                                    type: 'range', title: 'ROUNDED CORNERS', command: 'update-css-property', 
                                    min:0, max:100, args: { property:'border-radius', unit:'px' },
                                    value: ()=>{
                                        const radius = parseInt(component.getStyle('border-radius')) || 0;
                                        return radius;
                                    }, 
                                },
                                {
                                    type: 'options', title: 'BORDER SIZE & COLOR', titleKey: 'border_size_and_color',
                                    options: [
                                        { 
                                            type: 'range', command: 'update-css-property', 
                                            min:0, max:50, args: { 
                                                property:'border-width', unit:'px', 
                                                additionalProperties: ['border-style'], additionalValues: ['solid'] 
                                            },
                                            value: ()=>{
                                                const borderWidth = parseInt(component.getStyle('border-width')) || 0;
                                                return borderWidth;
                                            }, 
                                        },
                                        { 
                                            type:'color', command:'update-css-property', args: { property:'border-color' },
                                            rerender: { partial: true },
                                            value: ()=>{
                                                const color = component.getStyle('border-color') || '#000000';
                                                return color; 
                                            }, 
                                        },
                                    ]
                                },
                                { type: 'button', label: 'SELECT ICON ELEMENT', command: 'query-selector', args: { selector: '.icon-box__icon' } }
                            ]
                        }
                    ] 
                },
            ];   
        },
        ['oce-element']: function(component, editor){
            return [
                color_scheme_options(component, editor),
            ]
        },
        flipbox: function(component){
            return [
                {
                    type: 'options', title: 'SELECT FLIPBOX SIDE',
                    options: [
                        { type: 'button', label: 'FRONT', command: 'select-flipbox-side', args: { side: 'front' } },
                        { type: 'button', label: 'BACK', command: 'select-flipbox-side', args: { side: 'back' } },
                    ]
                },
            ]
        },
        ['flipbox-front']: function(component, editor){
            return get_flipbox_side_options(component, editor);
        },
        ['flipbox-back']: function(component, editor){
            return get_flipbox_side_options(component, editor);
        },
        ['counter-component']: function(component){
            let textAlignAction = create_text_align_actions(component);

            const getFontSize = ()=>{
                let value = parseInt(component.getStyle('font-size')) || 17;
                return value;
            };
            return [
                { type:'range', title:'FONT SIZE', command:'update-css-property', args: { property: 'font-size', unit: 'px' }, min:0, value:getFontSize },
                textAlignAction,
                { 
                    type: 'color', title: 'TEXT COLOR', command: 'update-css-property', rerender: { partial: true },
                    args: { property: 'color' }, value: ()=>{ return component.getStyle('color') || ''; } 
                },
            ]
        },
        ['carousel-wrapper']: function(component, editor){
            let actions = [
                { type: 'button', label: 'SELECT ALL ITEMS', command: 'query-selector', args: { selector: '.carousel__item' } },
            ];

            return actions;
        },
        ['carousel-item']: function(component, editor){
            let actions = [];

            const itemsQuantity = component.parent().components();
            if(itemsQuantity.length > 1) actions.push({ type: 'button', command: 'remove-carousel-item', label: 'REMOVE ITEM', class:"danger" });

            actions.push({
                type: 'button', command: 'carousel-locked-components-toggle', rerender:{partial:true},
                class: ()=>{
                    const lockedComponents = component.get('lockedComponents');
                    return (lockedComponents) ? 'active' : ''; 
                }, 
                label: ()=>{
                    const lockedComponents = component.get('lockedComponents');
                    return (lockedComponents) ? 'UNLOCK INNER COMPONENTS' : 'LOCK INNER COMPONENTS'; 
                }, 
            });

            return actions;
        },
        ['testimonial-header']: function(component){
            return [
                { 
                    type: 'options', 
                    title: 'IMAGE POSITION', 
                    options: [
                        { type: 'button', label: 'LEFT', command: 'update-testimonial-image-position', args: { position: 'left' } },
                        { type: 'button', label: 'RIGHT', command: 'update-testimonial-image-position', args: { position: 'right' } },
                        { type: 'break' },
                        { type: 'button', label: 'TOP', command: 'update-testimonial-image-position', args: { position: 'top' } },
                        { type: 'button', label: 'BOTTOM', command: 'update-testimonial-image-position', args: { position: 'bottom' } },
                    ]
                }
            ];
        },
        ['icon-list']: function(component){
            return [
                {
                    type: 'options',
                    options:[
                        { type: 'button', label: 'ADD ITEM', command: 'icon-list-actions', args: { action: 'add' } },
                        { type: 'button', label: 'REMOVE LAST ITEM', command: 'icon-list-actions', args: { action: 'remove' }, class:'danger' },
                    ]    
                },
                {
                    type: 'button', 
                    command: 'icon-list-actions', args: { action: 'toggle-multiple-edit' }, rerender: { full: true }, 
                    label: ()=>{
                        const multipleEditActive = component.get('__temp_multipleEdit');
                        return multipleEditActive ? 'DEACTIVATE MULTIPLE EDITING' : 'ACTIVATE MULTIPLE EDITING';
                    },
                    class: () => {
                        const multipleEditActive = component.get('__temp_multipleEdit');
                        return multipleEditActive ? 'active' : '';
                    }
                },
                {
                    type: 'range', title: 'SPACE BETWEEN ITEMS', command: 'update-css-property', min:0, max:100, 
                    args: { property:'gap', unit:'px' }, titleTooltip: 'Space between list items',
                    value: ()=>{
                        return parseInt(component.getStyle('gap')) || 2;
                    }
                }
            ]
        }
    }
};