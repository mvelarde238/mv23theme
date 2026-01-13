window.gjsContainer = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'container';

    domc.addType(compClass, {
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: 'Container',
                tagName: 'div',
                dropable: true,
                draggable: false,
                removable: false,
                copyable: false,
                selectable: false,
                hoverable: false,
                highlightable: true,
                classes: [compClass]
            },
        },
    });
}