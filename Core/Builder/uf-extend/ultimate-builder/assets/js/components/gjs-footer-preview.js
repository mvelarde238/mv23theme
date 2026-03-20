window.gjsFooterPreview = function (editor, options) {
    const domc = editor.DomComponents;
    const compClass = 'footer-preview';

    let notSelectableComponent = {
        tagName: 'div',
        droppable: false,
        stylable: false,
        removable: false,
        copyable: false,
        draggable: false,
        badgable: false,
        highlightable: false,
        selectable: false,
        hoverable: false,
        layerable: false,
    };

    const __ = editor.createTranslator(editor);
    const footerPreviewName = __('Footer Preview');

    domc.addType(compClass, {
        extend: 'async-component-abstract',
        model: {
            defaults: Object.assign({}, notSelectableComponent, {
                name: footerPreviewName,
                tagName: 'section',
            }),
        },
    });

    // On builder loaded, append to wrapper component
    editor.on('builder:loaded', () => {
        if ( BUILDER_GLOBALS.posttype === 'footer' ) return;

        const wrapper = editor.getWrapper();
        const footer_preview_exists = wrapper.findType('footer-preview')[0];
        
        if (!footer_preview_exists) {
            wrapper.append({ type: 'footer-preview' });
        }
    });

    // Before saving remove footer preview component as it's only for previewing and shouldn't be saved in the content
    editor.on('builder:before-save-editor', () => {
        if ( BUILDER_GLOBALS.posttype === 'footer' ) return;

        const wrapper = editor.getWrapper();
        const footer_preview = wrapper.findType('footer-preview')[0];
        if (footer_preview) {
            footer_preview.remove({ silent: true });
        }
    });
}