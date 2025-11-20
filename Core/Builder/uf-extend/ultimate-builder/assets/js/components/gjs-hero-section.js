window.gjsHeroSection = function (editor) {
    const domc = editor.DomComponents;

    const heroTemplates = {
        'hero1': {
            components: [
                { 
                    type:'row2',
                    components: [
                        {
                            type: 'column', components: [
                                { type: 'comp_heading' },
                                { type: 'comp_text_editor' },
                                { type: 'comp_button' }
                            ]
                        },
                        { 
                            type: 'column', components: [
                                { type: 'image2' }
                            ]
                        }
                    ] 
                }
            ],
            heroStyle: { padding: '8% 0' }
        },
        'hero2': {
            components: [
                { type: 'comp_heading' },
                { type: 'comp_text_editor' },
                { type: 'comp_button' },
            ],
            heroStyle: { padding: '15% 8%' }
        },
    };

    // Define the component
    domc.addType('hero-section', {
        model: {
            defaults: {
                tagName: 'div',
                attributes: {
                    class: 'hero-section',
                },
                styles:`
                    .hero-section{
                        padding: 40px 0px 40px 0px;
                    }
                `,
            }
        },
        view: {
            onRender({ el, model }) {
                const editorConfig = editor.getConfig(),
                	temporalCompStore = editorConfig.temporalCompStore || {},
					__tempID = model.get('__tempID');

				if (__tempID && temporalCompStore[__tempID]) {
					const datastore = temporalCompStore[__tempID].datastore;
					if (datastore) {
                        const template = datastore.get('template') || 'hero1';
                        const heroTemplate = heroTemplates[template] || [];

                        // Clear existing components
                        model.components( heroTemplate.components || []);
                        if (heroTemplate.heroStyle) {
                            model.setStyle( heroTemplate.heroStyle );
                        }
                    }
                }
            },
        }
    });
}