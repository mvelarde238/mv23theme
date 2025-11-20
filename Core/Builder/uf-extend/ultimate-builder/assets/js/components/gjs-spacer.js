window.gjsSpacer = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'spacer';

    domc.addType(compClass, {
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: 'Spacer',
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
                attributes: {
                    class: compClass,
                },
                style:{ 
                    width: '100%'
                }
            },
        },
    });
}