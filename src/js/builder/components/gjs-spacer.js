window.gjsSpacer = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'spacer';

    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const compName = __('Spacer');

    domc.addType(compClass, {
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: compName,
                tagName: 'div',
                droppable: false,
                resizable: {
                    ratioDefault: true,
                    currentUnit: 1,
                    keepAutoWidth: true,
                    cl: false,
                    cr: false,
                    tl: false,
                    tc: false,
                    tr: false,
                    bl: false,
                    bc: true,
                    br: false,
                    maxDim: null,
                    minDim: 1
                },
                classes: [compClass],
                style:{ 
                    width: '100%',
                    height: '30px'
                }
            },
        },
    });
};