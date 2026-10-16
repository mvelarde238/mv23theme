window.gjsSection = function(editor) {
    const domc = editor.DomComponents;
    const compClass = 'page-module';

    // Labels for the ui, using the editor's translator for internationalization.
    const __ = editor.createTranslator(editor, 'ultimate_builder');
    const compName = __('Section');

    // Add the section type to the DomComponents
    domc.addType('section', {
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: compName,
                tagName: 'div',
                draggable: true,
                classes: [compClass],
                cssSpots: ['padding', 'margin'],
                styles: `
                    .${compClass} {
                        padding: 40px 0px 40px 0px;
                    }
                `,
                components: [
                    { type: 'container' }
                ],
            },
        },
    });

    // Set the section padding based on the builder settings
    editor.on('component:create', (model) => {
        if (model.get('type') === 'section') {

            // Check if this is the first time the component is being created,
            // __id is a unique identifier for the component, generated during saving process.
            const isFirstCreation = !model.get('__id');
            
            if ( isFirstCreation ){ 
                const builderSettings = editor.getConfig().builderSettings || {};
                const padding = builderSettings.default_sections_padding || {};
                const paddingValue = `${padding.top}px ${padding.right}px ${padding.bottom}px ${padding.left}px`;
        
                if (paddingValue !== '40px 0px 40px 0px') {
                    model.addStyle({
                        'padding': paddingValue,
                    }, { noUndo: true });
                }
            }
        }
    });

    // Remove the component styles before saving as they are only needed to be presented in the style manager
    editor.on('builder:before-save-editor', () => {
        const css = editor.Css;
        css.remove(`.${compClass}`);
    });
};