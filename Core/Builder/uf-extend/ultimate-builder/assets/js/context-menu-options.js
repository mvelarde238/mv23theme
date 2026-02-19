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
                command: 'update-text-align',
                label: '<i class="bi ' + icon_names[item] + '"></i>', 
                args: { align: item } 
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
        { id: 'dark-mode', name: 'DARK MODE' },
        { id: 'light-mode', name: 'LIGHT MODE' },
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

window['contextMenuOpts'] = {
    actions: {
        ['text-editor']: function(component){
            let textAlignAction = create_text_align_actions(component);

            const getFontSize = ()=>{
                let value = parseInt(component.getStyle('font-size')) || 17;
                console.log('getFontSize', value);
                return value;
            };
            return [
                { type:'range', title:'FONT SIZE', command:'update-font-size', min:0, value:getFontSize },
                textAlignAction
            ]
        },
        button: function(component){
            let textAlignAction = create_text_align_actions(component);

            return [
                textAlignAction
            ]
        },
        heading: function(component){
            return [
                { type: 'button', label: 'SELECT HEADING', command: 'query-selector', args: { selector: '.heading__text' } },
                { type: 'button', label: 'SELECT TAGLINE', command: 'query-selector', args: { selector: '.heading__tagline' } }
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
                    

            const topSvg = '<i class="bi bi-align-top"></i>';
            const middleSvg = '<i class="bi bi-align-middle"></i>';
            const bottomSvg = '<i class="bi bi-align-bottom"></i>';
            // const betweenSvg = '<i class="bi bi-align-center"></i>';
            const betweenSvg = '<svg version="1.1" id="Capa_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 50" enable-background="new 0 0 50 50" xml:space="preserve"><line fill="none" stroke="#FFFFFF" stroke-width="2" stroke-miterlimit="10" x1="5" y1="2.5" x2="44" y2="2.5"/><line fill="none" stroke="#FFFFFF" stroke-width="2" stroke-miterlimit="10" x1="5" y1="47.5" x2="44" y2="47.5"/><path fill="#FFFFFF" d="M28.7,45h-7.4c-0.72,0-1.3-0.58-1.3-1.3v-7.4c0-0.72,0.58-1.3,1.3-1.3h7.4c0.72,0,1.3,0.58,1.3,1.3v7.4C30,44.42,29.42,45,28.7,45z"/><path fill="#FFFFFF" d="M28.7,15h-7.4c-0.72,0-1.3-0.58-1.3-1.3V6.3C20,5.58,20.58,5,21.3,5h7.4C29.42,5,30,5.58,30,6.3v7.4C30,14.42,29.42,15,28.7,15z"/></svg>';
            const startSvg = '<i class="bi bi-align-start"></i>';
            const centerSvg = '<i class="bi bi-align-center"></i>';
            const endSvg = '<i class="bi bi-align-end"></i>';

            const ccaCmd = 'update-content-alignment';
            const contentAlignmentOptions = {
                type: 'options',
                title: 'CONTENT ALIGNMENT',
                options: [
                    { type:'button', label:topSvg, titleTooltip: 'Top', command:ccaCmd, args:{ property: 'justify-content', alignment:'flex-start' } },
                    { type:'button', label:middleSvg, titleTooltip: 'Middle', command:ccaCmd, args:{ property: 'justify-content', alignment:'center' } },
                    { type:'button', label:bottomSvg, titleTooltip: 'Bottom', command:ccaCmd, args:{ property: 'justify-content', alignment:'flex-end' } },
                    { type:'button', label:betweenSvg, titleTooltip: 'Between', command:ccaCmd, args:{ property: 'justify-content', alignment:'space-between' } },
                    { type:'break' },
                    { type:'button', label:startSvg, titleTooltip: 'Start', command:ccaCmd, args:{ property: 'align-items', alignment:'flex-start' } },
                    { type:'button', label:centerSvg, titleTooltip: 'Center', command:ccaCmd, args:{ property: 'align-items', alignment:'center' } },
                    { type:'button', label:endSvg, titleTooltip: 'End', command:ccaCmd, args:{ property: 'align-items', alignment:'flex-end' } },
                    { type:'toggle' },
                    { type:'button', label:'Around', command:ccaCmd, args:{ property: 'justify-content', alignment:'space-around' } },
                    { type:'button', label:'Evenly', command:ccaCmd, args:{ property: 'justify-content', alignment:'space-evenly' } }
                ]
            };

            const getGap = ()=>{
                let value = parseInt(component.getStyle('gap'));
                if(isNaN(value)) value = 24;
                return value;
            };

            actions_group_1.push(layout_options(component, editor));
            actions_group_1.push({
                type: 'options', title: 'FLEX DIRECTION',
                options: [
                    { type: 'button', label: 'HORIZONTAL', command: 'update-flex-direction', args: { direction:'row' } },
                    { type: 'button', label: 'VERTICAL', command: 'update-flex-direction', args: { direction:'column' } },
                ]
            });

            actions_group_2.push(contentAlignmentOptions);
            actions_group_2.push({ type: 'range', title:'SPACE BETWEEN COMPONENTS', command: 'update-gap-property', min:0, value:getGap });

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
            const iconComponent = component.findType('icon')[0];

            const getIconSize = ()=>{
                let value = parseInt(iconComponent.getStyle('--icon-size')) || 40;
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
                                    type: 'range', title: 'ICON SIZE', command: 'update-icon-property', 
                                    value:getIconSize, min:15, max:200, args: { property:'--icon-size' }
                                },
                                {
                                    type: 'options', title: 'BACKGROUND & COLOR',
                                    options: [
                                        {
                                            type:'color', command:'update-icon-property', args: { property:'background-color' },
                                            value: ()=>{ 
                                                const backgroundColor = iconComponent.getStyle('background-color') || '';
                                                return backgroundColor; 
                                            }, 
                                        },
                                        { 
                                            type:'color', command:'update-icon-property', args: { property:'color' },
                                            value: ()=>{
                                                const color = iconComponent.getStyle('color') || '#000000';
                                                return color; 
                                            }, 
                                        },
                                    ],
                                },
                                { 
                                    type: 'range', title: 'SPACE AROUND ICON', command: 'update-icon-property', 
                                    min:0, max:100, args: { property:'padding' },
                                    value: ()=>{
                                        const padding = parseInt(iconComponent.getStyle('padding')) || 0;
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
                                    type: 'range', title: 'ROUNDED CORNERS', command: 'update-icon-property', 
                                    min:0, max:100, args: { property:'border-radius' },
                                    value: ()=>{
                                        const radius = parseInt(iconComponent.getStyle('border-radius')) || 0;
                                        return radius;
                                    }, 
                                },
                                {
                                    type: 'options', title: 'BORDER SIZE & COLOR',
                                    options: [
                                        { 
                                            type: 'range', command: 'update-icon-property', 
                                            min:0, max:50, args: { property:'border-width' },
                                            value: ()=>{
                                                const borderWidth = parseInt(iconComponent.getStyle('border-width')) || 0;
                                                return borderWidth;
                                            }, 
                                        },
                                        { 
                                            type:'color', command:'update-icon-property', args: { property:'border-color' },
                                            value: ()=>{
                                                const color = iconComponent.getStyle('border-color') || '#000000';
                                                return color; 
                                            }, 
                                        },
                                    ]
                                },
                                {
                                    type: 'range', title: 'GAP', command: 'update-icon-property', 
                                    min:0, max:100, args: { property:'gap' }, titleTooltip: 'Space between Icon and Text',
                                    value: ()=>{
                                        const gap = parseInt(component.getStyle('gap')) || 20;
                                        return gap;
                                    },
                                },
                                // { type: 'button', label: 'SELECT ICON', command: 'query-selector', args: { selector: '.icon-cmp' } }
                            ]
                        }
                    ] 
                },
            ];   
        }
    }
};