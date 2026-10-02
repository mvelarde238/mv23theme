window.gjsContainer = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'container';

    domc.addType(compClass, {
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: 'Container',
                tagName: 'div',
                droppable: true,
                highlightable: true,
                classes: [compClass]
            },
        }
    });
};