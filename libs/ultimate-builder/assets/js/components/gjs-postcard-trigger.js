window.gjsPostcardTrigger = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'postcard-trigger';

    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const compName = __('Postcard Trigger');

    domc.addType(compClass, {
        extend: 'button',
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: compName,
                tagName: 'div',
                classes: [compClass,'component'],
            },
        },
    });
}