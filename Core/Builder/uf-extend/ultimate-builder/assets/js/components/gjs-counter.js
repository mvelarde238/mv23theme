window.gjsCounter = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'counter-component';

    domc.addType(compClass, {
        extend: 'comp-base',
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: 'Counter',
                tagName: 'div',
                droppable: false,
                classes: [compClass]
            },
        },
    });
}