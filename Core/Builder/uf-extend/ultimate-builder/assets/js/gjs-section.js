function gjsSection(editor) {
    const domc = editor.DomComponents;
    const compClass = 'section';

    // Make classes private
    const privateCls = ['.section','.section--layout1','.section--layout2','.section--layout3'];
    editor.on('selector:add', selector => privateCls.indexOf(selector.getFullName()) >= 0 && selector.set('private', 1));

    editor.Commands.add('update-section-layout', (editor, sender, options = {}) => {
        let component = options.component,
            layout = options.layout;
        component.set({layout:layout});
    });

    const layoutOptions = [
        { id: 'layout1', name: 'Default' },
        // { id: 'layout2', name: 'Full Width' },
        // { id: 'layout3', name: 'Full Width Stretched' }
    ];

    domc.addType(compClass, {
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: 'Section',
                tagName: 'div',
                // droppable: false,
                draggable: function (target, destination) {
                    console.log('target', destination);
                    return (destination.attributes.type) == 'wrapper'; // wrapper is body
                    // return (destination.attributes.type) == 'container';
                },
                layout: 'layout1',
                attributes: {
                    class: compClass,
                },
                style: { 
                    padding: '55px 0px 55px 0px',
                    width: '100%',
                    ['min-height'] : '100px'
                },
                // styles:`
                //     .section--layout2,
                //     .section--layout3{
                //         width: 100vw !important;
	            //         position: relative;
	            //         left: 50%;
	            //         right: 50%;
	            //         margin-left: -50vw !important;
	            //         margin-right: -50vw !important;
                //     }
                //     .section--layout3>div{
                //         width: 98%;
                //         max-width: 1280px;
                //         margin: 0 auto;
                //     }
                // `,
                traits: [
                    'id',
                    {
                        type: 'select',
                        label: 'Layout',
                        name: 'layout',
                        changeProp: 1,
                        options: layoutOptions,
                    }
                ],
                contextMenu: function(component){
                    let layoutActions = {
                        type: 'options',
                        title: 'LAYOUT',
                        options: []
                    };
                    layoutOptions.forEach((l)=>{
                        layoutActions.options.push({ 
                            type:'button', 
                            command:'update-section-layout', 
                            args:{ layout:l.id },
                            label:l.name.toUpperCase(), 
                            class: ()=>{
                                const layout = component.get('layout');
                                return (l.id == layout) ? 'active' : '';
                            }
                        });
                    });

                    return [ layoutActions ];
                }
            },
            init: function(){
                this.on('change:layout', this.handleLayoutChange);
            },
            handleLayoutChange: function(){
                this.addAttributes({ 'class': `${compClass} ${compClass}--${this.get('layout')}` });
            }
        },
    });

    // Add a block in structure category
    editor.Blocks.add( compClass, {
        label: 'Section',
        media: '<i class="dashicons dashicons-align-wide"></i>',
        category: 'Structure',
        content: { type: compClass }
    });
}

window.gjsSection = gjsSection;