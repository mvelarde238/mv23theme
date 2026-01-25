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

window['contextMenuOpts'] = {
    actions: {
        comp_text_editor: function(component){
            let textAlignAction = create_text_align_actions(component);

            const getFontSize = ()=>{
                let value = parseInt(component.getStyle('font-size')) || 17;
                return value;
            };
            return [
                { type:'range', title:'FONT SIZE', command:'update-font-size', min:0, value:getFontSize },
                textAlignAction
            ]
        },
        comp_button: function(component){
            let textAlignAction = create_text_align_actions(component);

            return [
                textAlignAction
            ]
        },
        comp_heading: function(component){
            return [
                { type: 'button', label: 'SELECT HEADING', command: 'query-selector', args: { selector: '.heading__text' } },
                { type: 'button', label: 'SELECT TAGLINE', command: 'query-selector', args: { selector: '.heading__tagline' } }
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
                }
            ]
        },
        ['comp-wrapper']: function(component, editor){
            return [
                layout_options(component, editor),
            ]
        },
        wrapper: function(component, editor){
            let actions = []

            return actions;
        },
        ['icon-and-text']: function(component, editor){
            const iconComponent = component.findType('comp_icon')[0];

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