window.gjsPostcard = ( editor ) => {
    const domc = editor.DomComponents;

    // Define the postcard component
    domc.addType('postcard', {
        model: {
            defaults: {
                tagName: 'div',
                classes: ['postcard','components-wrapper'],
                removable: false,
                draggable: false,
                copyable: false,
                droppable: true,
                styles: `
                    .postcard {
                        max-width: 400px;
                        margin: 0 auto;
                    }
                `,
            }
        },
    });

    // On builder loaded, customize the canvas
    editor.on('builder:loaded', () => {
        if ( BUILDER_GLOBALS.posttype !== 'postcard' ) return;

        const wrapper = editor.getWrapper();
        const container = wrapper.findType('container')[0];

        // Add postcard element to the canvas if not present
        if (!wrapper.findType('postcard').length) {
            container.append({ type: 'postcard' });
        }

        // gjs wrapper shouldn't be selectable/removable
        wrapper.set({ 
            selectable: false, 
            removable: false,
            hoverable: false
        });
        container.set({
            droppable: false,
            selectable: false,
        });
    });

    // Before saving remove styles that are only needed for the style manager
    editor.on('builder:before-save-editor', () => {
        if ( BUILDER_GLOBALS.posttype !== 'postcard' ) return;

        // Remove postcard styles before saving as they are only needed to be presented in the style manager
        const css = editor.Css;
        css.remove(`.postcard`);
    });
}