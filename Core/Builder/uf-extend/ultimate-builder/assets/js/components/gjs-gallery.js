window.gjsGallery = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'gallery';

    // add custom css to canvasCss
    let config = editor.getConfig();
    config.canvasCss = config.canvasCss || '';
    config.canvasCss += `.gallery * {pointer-events: none;}`;
    editor.canvasCss = config.canvasCss;

    domc.addType(compClass, {
        extend: 'async-component-abstract',
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: 'Gallery',
                tagName: 'div',
                classes: [compClass, 'component'],
                __onSuccessCallback: (response, model, editor) => {
                    const el = model.getEl();
                    const temp = document.createElement('div');
                    temp.innerHTML = response.data;
                    const firstChild = temp.firstElementChild;
                    
                    if (firstChild) {
                        firstChild.removeAttribute('id');
                        el.innerHTML = temp.innerHTML;
                    }
                },
            },
        },
    });
}