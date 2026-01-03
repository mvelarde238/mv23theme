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
                layout_options(component, editor)
            ]
        },
        ['comp-wrapper']: function(component, editor){
            return [
                layout_options(component, editor)
            ]
        }
    }
};