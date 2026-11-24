window.gjsCompWrapper = function(editor) {
    const domc = editor.DomComponents;
    const compClass = 'components-wrapper';

    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const compName = __('Wrapper');
    const layoutProperties = ['display', 'flex-direction', 'justify-content', 'flex-wrap', 'gap'];

    const updateHighlight = (el, model, changedStyles = {}) => {
        const hasComponents = model.components().length > 0;
        const hasStyles = Object.keys(model.getStyle() || {}).some(property => !layoutProperties.includes(property)) ||
            Object.entries(changedStyles).some(([property, value]) => {
                return !layoutProperties.includes(property) && !property.startsWith('__') && value != null && value !== '';
            });
        el.classList.toggle('highlight-on-empty', !hasComponents && !hasStyles);
    };

    // Define the component
    domc.addType(compClass, {
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: compName,
                tagName: 'div',
                classes: [compClass],
                // these styles affects all the components that use the class .components-wrapper:
                styles: `
                    .${compClass} {
                        display: flex;
                        flex-direction: column;
                        justify-content: flex-start;
                        flex-wrap: nowrap;
                        gap: 24px;
                    }
                `,
            },
        },
        view: {
            onRender({el, model}) {
                updateHighlight(el, model);
            }
        }
    });

    editor.on('component:styleUpdate', (model, {style} = {}) => {
        if (!model.is(compClass)) return;
        const el = model.getEl();
        if (el) updateHighlight(el, model, style);
    });

    // Remove the component styles before saving as they are only needed to be presented in the style manager
    editor.on('builder:before-save-editor', () => {
        const css = editor.Css;
        css.remove(`.${compClass}`);
    });
};