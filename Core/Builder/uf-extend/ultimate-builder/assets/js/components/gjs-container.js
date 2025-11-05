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
                attributes: {
                    class: compClass,
                },
                // style: { 
                //     margin: '0 auto',
                //     width: '98%',
                //     ['min-height']: '100vh',
                //     ['max-width']: '1220px' 
                // },
            },
        },
    });
}