window.gjsMenu = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'menu';

    domc.addType(compClass, {
        extend: 'async-component-abstract',
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: 'Menu',
                tagName: 'nav',
                classes: ['menu-comp', 'component'],
            },
        },
    });
};