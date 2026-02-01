window.gjsWrapper = function (editor, options) {
    const domc = editor.DomComponents;

    /* Extend Wrapper (Body) Component */
    domc.addType('wrapper', {
        model: {
            defaults: {
                name: 'Page',
                droppable: false,
                highlightable: false,
                stylable: true,
                unstylable: []
            },
        }
    });
}