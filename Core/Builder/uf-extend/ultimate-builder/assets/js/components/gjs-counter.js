window.gjsCounter = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'counter-component';

    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const compName = __('Counter');

    domc.addType(compClass, {
        extend: 'comp-base',
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: compName,
                tagName: 'div',
                droppable: false,
                classes: [compClass]
            },
        },
    });
}