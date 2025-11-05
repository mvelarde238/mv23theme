function create_text_align_actions(component){
    let textAlignAction = {
        type: 'options', title: 'TEXT ALIGN',
        options: [],
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
                label: '<i class="material-symbols-outlined">format_align_'+item+'</i>', 
                args: { align: item } 
            };
        textAlignAction.options.push( innerAction );
    });

    return textAlignAction;
}

const contextMenuOpts = {
    actions: {
        // wrapper: function(component){
        //     return [
        //         { type:'button', label:'Open Style Manager', command:'open-style-manager' }
        //     ]
        // },
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
        }
    }
};

window.contextMenuOpts = contextMenuOpts;