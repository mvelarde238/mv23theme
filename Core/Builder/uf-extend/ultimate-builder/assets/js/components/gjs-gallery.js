window.gjsGallery = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'gallery';

    // add custom css to canvasCss
    let config = editor.getConfig();
    config.canvasCss = config.canvasCss || '';
    config.canvasCss += `.gallery * {pointer-events: none;}`;
    editor.canvasCss = config.canvasCss;

    // Define the component
    domc.addType(compClass, {
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: 'Gallery',
                tagName: 'div',
                classes: [compClass,'component']
            },
        },
        view: {
            onRender({el, model}) {                    
                const datastore = editor.getComponentDatastore(model);

				if (datastore) {
                    const data = datastore.toJSON();
                    data['action'] = 'get_component_view';
                    jQuery.ajax({
                        type: "POST",
                        dataType: "json",
                        url: MV23_GLOBALS.ajaxUrl,
                        data: data,
                        success: function(response) {
                            el.innerHTML = response.data;
                        },
                        error: function(xhr, status, error) {
                            console.error(`Error loading ${compClass} component view:`, error);
                        }
                    });
                }
            },
        },
    });
}