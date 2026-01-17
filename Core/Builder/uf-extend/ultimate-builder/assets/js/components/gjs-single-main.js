window.gjsSingleMain = function (editor, options) {
    const domc = editor.DomComponents;

    domc.addType('single_main', {
        model: {
            defaults: {
                name: 'Single Main',
                tagName: 'main',
                classes: ['single-main', 'main'],
                droppable: false,
                stylable: false,
                removable: false,
                copyable: false,
                draggable: false,
                badgable: false,
                highlightable: false
            },
        }
    });
}