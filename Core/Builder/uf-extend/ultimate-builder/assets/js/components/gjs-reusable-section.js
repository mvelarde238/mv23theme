window.gjsReusableSection = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'reusable-section';

    domc.addType(compClass, {
        extend: 'async-component-abstract',
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: 'ReusableSection',
                tagName: 'div',
                classes: [compClass, 'component', 'components-wrapper'],
            },
        },
    });
}