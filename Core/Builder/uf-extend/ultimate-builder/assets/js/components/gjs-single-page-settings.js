window.gjsSinglePageSettings = function (editor, options) {
    const domc = editor.DomComponents;

    domc.addType('single_page_settings', {
        model: {
            defaults: {
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