window.gjsCarouselParts = function(editor) {
    const domc = editor.DomComponents;

    domc.addType('carousel-controls', {
        isComponent: el => el.classList && el.classList.contains('carousel__controls'),
        model: {
            defaults: {
                tagName: 'div',
                classes: ['tns-controls', 'carousel__controls'],
                droppable: false,
                copyable: false,
                removable: false,
                draggable: false,
                badgable: false,
                highlightable: false,
                layerable: false,
                propagate: ['layerable', 'draggable', 'removable', 'copyable', 'badgable', 'highlightable'],
                components: [
                    { 
                        type: 'icon-box', 
                        attributes: { 'data-controls': 'prev' }, 
                        datastoreDefaults: { icon: BUILDER_GLOBALS.prev_carousel_icon },  
                    },
                    { 
                        type: 'icon-box', 
                        attributes: { 'data-controls': 'next' }, 
                        datastoreDefaults: { icon: BUILDER_GLOBALS.next_carousel_icon },  
                    },
                ],
            },
        },
    });

    domc.addType('carousel-nav', {
        isComponent: el => el.classList && el.classList.contains('carousel__nav'),
        model: {
            defaults: {
                tagName: 'div',
                classes: ['carousel__nav', 'tns-nav'],
                droppable: false,
                copyable: false,
                removable: false,
                draggable: false,
                badgable: false,
                highlightable: false,
                layerable: false,
                selectable: false,
            },
        },
        view: {
            onRender({model, el}) {
                const carouselWrapper = model.closestType('carousel-wrapper'); 
                if (carouselWrapper) {
                    const totalPages = carouselWrapper.getView().getTotalPages();
                    
                    for (let i = 0; i < totalPages; i++) {
                        const btn = document.createElement('button');
                        btn.setAttribute('data-nav', i);
                        if (i === 0) btn.className = 'tns-nav-active';
                        el.appendChild(btn);
                    }
                }
            }
        }
    });
};