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

    // Remove the component styles before saving as they are only needed to be presented in the style manager
    editor.on('builder:before-save-editor', () => {
        const css = editor.Css;
        css.remove(`.${compClass}`);
    });
};