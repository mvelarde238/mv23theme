window.gjsSinglePageStructure = function (editor, options) {
    const domc = editor.DomComponents;

    domc.addType('single_page_structure', {
        model: {
            defaults: {
                name: 'Single Page Structure',
                tagName: 'div',
                classes: ['single-page-structure', 'main-content', 'main-content--sidebar-right'],
                droppable: false,
                stylable: false,
                removable: false,
                copyable: false,
                draggable: false,
                badgable: false,
                highlightable: false,
                components: [
                    { 
                        type: 'single_main',
                        components: [
                            { type: 'single_page_header' },
                            { type: 'single_page_content' },
                            { type: 'comments_area' },
                            { type: 'related_posts'}
                        ]
                    },
                    { type: 'single_sidebar' }
                ]
            },
        }
    });
}