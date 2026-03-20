window.gjsCompWrapper = function(editor) {
    const domc = editor.DomComponents;
    const compClass = 'components-wrapper';

    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const compName = __('Wrapper');

    // Define the component
    domc.addType(compClass, {
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: compName,
                tagName: 'div',
                classes: [compClass,'component'],
                styles: `
                    .${compClass} {
                        display: flex;
                        flex-direction: column;
                        flex-wrap: wrap;
                    }
                `,
            },
        },
    });

    // Remove the component styles before saving as they are only needed to be presented in the style manager
    editor.on('builder:before-save-editor', () => {
        const css = editor.Css;
        css.remove(`.${compClass}`);
    });
}