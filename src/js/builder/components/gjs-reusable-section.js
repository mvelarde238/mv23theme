window.gjsReusableSection = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'reusable-section';

    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const compName = __('Reusable Section');

    domc.addType(compClass, {
        extend: 'async-component-abstract',
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: compName,
                tagName: 'div',
                classes: [compClass, 'component', 'components-wrapper'],
            },
        },
    });
};