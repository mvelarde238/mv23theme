window.gjsMenu = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'menu';

    domc.addType(compClass, {
        extend: 'async-component-abstract',
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: 'Menu',
                tagName: 'div',
                classes: [compClass, 'component'],
                __onSuccessCallback: (response, model, editor) => {
                    const el = model.getEl();
                    // Create temporary container to parse response HTML
                    const temp = document.createElement('div');
                    temp.innerHTML = response.data;
                    const firstChild = temp.firstElementChild;
                    
                    // Remove id attribute from component to avoid duplicates and style conflicts
                    if (firstChild) {
                        firstChild.removeAttribute('id');
                        el.innerHTML = temp.innerHTML;
                    }
                },
            },
        },
    });
}