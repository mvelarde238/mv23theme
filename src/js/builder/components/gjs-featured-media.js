window.gjsFeaturedMedia = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'featured-media';

    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const compName = __('Featured Media');

    domc.addType(compClass, {
        extend: 'image-component',
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: compName,
                tagName: 'img',
                classes: [compClass,'component']
            },
        },
    });
};