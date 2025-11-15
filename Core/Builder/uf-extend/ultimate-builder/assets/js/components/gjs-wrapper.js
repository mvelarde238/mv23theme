window.gjsWrapper = function (editor, options) {
    const domc = editor.DomComponents;

    /* Extend Wrapper (Body) Component */
    domc.addType('wrapper', {
        model: {
            defaults: {
                droppable: false,
                stylable: true,
                unstylable: []
            },
        }
    });
}