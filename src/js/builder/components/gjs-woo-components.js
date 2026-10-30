window.gjsWooComponents = function (editor, options) {
    const domc = editor.DomComponents;

    domc.addType('woo-product-wrapper', {
        model: {
            defaults: {
                name: 'Product Wrapper',
                tagName: 'div',
                classes: ['woo-product-wrapper', 'components-wrapper']
            },
        },
        view: {
            onRender({view, el}) {
                el.classList.add('product');
            }
        }
    });

    domc.addType('woo-product-summary', {
        model: {
            defaults: {
                name: 'Product Summary',
                tagName: 'div',
                classes: ['woo-product-summary', 'components-wrapper']
            },
        },
    });

    domc.addType('woo-breadcrumbs', {
        extend: 'comp-base',
        model: {
            defaults: {
                name: 'Breadcrumbs',
                tagName: 'div',
                classes: ['woo-breadcrumbs','component']
            },
        },
        view: {
            __onRenderCallback({ el, model }) {
                const datastore = editor.getComponentDatastore(model);
                if (!datastore) return;

                const { template } = datastore.toJSON();
                if(template != 'template0') el.classList.add(template);
            },
        }
    });

    domc.addType('woo-product-tabs', {
        extend: 'woo-breadcrumbs',
        model: {
            defaults: {
                name: 'Product Tabs',
                classes: ['woo-product-tabs','component']
            },
        },
    });
};