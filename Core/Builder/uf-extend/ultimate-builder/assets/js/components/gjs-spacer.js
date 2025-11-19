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
                },
                styles: `
                    .spacer{
                        height: 30px;
                        position: relative;
                    }
                    .spacer::after{
                        content: '';
                        position: absolute;
                        width: 100%;
                        height: 100%;
                        top: 0;
                        left: 0;
                        background: repeating-linear-gradient(
                            45deg,
                            transparent,
                            rgba(150, 150, 150, 0.1) 3px,
                            transparent 3px,
                            transparent 8px,
                            rgba(150, 150, 150, 0.1) 11px,
                            transparent 11px
                        );
                    }
                `,
            },
        },
    });
}