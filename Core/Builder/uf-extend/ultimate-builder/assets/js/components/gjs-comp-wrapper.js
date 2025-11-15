window.gjsCompWrapper = function(editor) {
    const domc = editor.DomComponents;
    const compClass = 'comp-wrapper';

    // Make classes private
    const privateCls = ['.comp-wrapper'];
    editor.on('selector:add', selector => privateCls.indexOf(selector.getFullName()) >= 0 && selector.set('private', 1));

    // Define the component
    domc.addType(compClass, {
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: 'Wrapper',
                tagName: 'div',
                attributes: {
                    class: compClass,
                },
            },
        },
    });
}