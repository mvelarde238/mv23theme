window.gjsHeroSection = function (editor) {
    const domc = editor.DomComponents;

    const heroTemplates = {
        'hero1': {
            components: [
                { 
                    type:'row-component',
                    components: [
                        {
                            type: 'column', components: [
                                { type: 'heading' },
                                { type: 'text-editor' },
                                { type: 'button' }
                            ]
                        },
                        { 
                            type: 'column', components: [
                                { type: 'image-component' }
                            ]
                        }
                    ] 
                }
            ],
            heroStyle: { padding: '8% 0' }
        },
        'hero2': {
            components: [
                { type: 'heading' },
                { type: 'text-editor' },
                { type: 'button' },
            ],
            heroStyle: { padding: '15% 8%' }
        },
    };

    // Define the component
    domc.addType('hero-section', {
        model: {
            defaults: {
                tagName: 'div',
                classes: ['hero-section'],
                // styles:`
                //     .hero-section{
                //         padding: 40px 0px 40px 0px;
                //     }
                // `,
            }
        },
        view: {
            onRender({ el, model }) {
                const datastore = editor.getComponentDatastore(model);
				if (datastore) {
                    const template = datastore.get('template') || 'hero1';
                    const heroTemplate = heroTemplates[template] || [];

                    // Clear existing components
                    model.components( heroTemplate.components || []);
                    if (heroTemplate.heroStyle) {
                        model.setStyle( heroTemplate.heroStyle );
                    }
                }
            },
        }
    });
}