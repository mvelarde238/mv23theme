window.gjsSinglePageStructure = function (editor, options) {
    const domc = editor.DomComponents;

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
    };

    const singlePageStructureComponents = [
        { 
            type: 'single-main',
            components: [
                { type: 'post-title' },
                { type: 'post-content' },
                { type: 'social-share' },
                { type: 'related-posts' },
                { type: 'comments-area' }
            ]
        },
        { type: 'sidebar' }
    ];

    domc.addType('single-main', {
        model: {
            defaults: Object.assign({}, notSelectableComponent, {
                name: 'Single Main',
                tagName: 'main',
                classes: ['single-main', 'main', 'components-wrapper'],
                droppable: true
            }),
        }
    });

    domc.addType('single-page-structure', {
        model: {
            defaults: Object.assign({}, notSelectableComponent, {
                name: 'Single Page Structure',
                tagName: 'div',
                classes: ['single-page-structure', 'main-content', 'main-content--sidebar-right'],
                selectable: true,
                hoverable: true,
                components: singlePageStructureComponents
            }),
        },
        view: {
            onRender({el, model}) {
                // Initial handling of datastore data
                this.handle_datastore_data();
            },
            custom_datastore_change_callback(changed) {
                // Handle datastore data changes
                this.handle_datastore_data();
            },
            handle_datastore_data() {
                const model = this.model;

                const datastore = editor.getComponentDatastore(model);
                if (datastore) {
                    const data = datastore.toJSON();
                    const hidden_info = data['hidden_info'] || {};
                    const setting_name = hidden_info['setting_name'] || '';
                    if(data[setting_name]) {
                        const settings = data[setting_name];

                        const sidebar = model.findType('sidebar')[0];
                        const post_title = model.findType('post-title')[0];
                        const social_share = model.findType('social-share')[0];
                        const related_posts = model.findType('related-posts')[0];
                        const comments_area = model.findType('comments-area')[0];

                        // Handle post title visibility
                        const post_title_display = settings['hide_post_title'] ? 'none' : 'block';
                        if(post_title) post_title.setStyle({ display: post_title_display });

                        // Handle social share visibility
                        const social_share_display = settings['hide_social_share'] ? 'none' : 'block';
                        if(social_share) social_share.setStyle({ display: social_share_display });

                        // Handle related posts visibility
                        const related_posts_display = settings['hide_related_posts'] ? 'none' : 'block';
                        if(related_posts) related_posts.setStyle({ display: related_posts_display });

                        // Handle sidebar visibility
                        const sidebar_display = (settings['page_template'] === 'main-content--sidebarless' ) ? 'none' : 'block';
                        if(sidebar) sidebar.setStyle({ display: sidebar_display });

                        // Handle comments area visibility
                        const comments_area_display = settings['hide_comments_area'] ? 'none' : 'block';
                        if(comments_area) comments_area.setStyle({ display: comments_area_display });

                        // Handle page template
                        model.set('classes', ['single-page-structure', 'main-content', settings['page_template']]);
                    }
                }
            },
        }
    });

    // On builder loaded, customize the canvas
    editor.on('builder:loaded', () => {
        if( BUILDER_GLOBALS.is_singular ){
            const wrapper = editor.getWrapper();
            const container = wrapper.findType('container')[0];

            // Check if container has any component
            const existingComponents = container.components();
            if (existingComponents.length > 0) {

                const single_page_structure_exists = container.findType('single-page-structure')[0];
                if ( single_page_structure_exists ) {
                    // Ensuring correct structure: main, sidebar, post-title, social-share, related-posts, etc.
                    editor.ensureComponentStructure(single_page_structure_exists, singlePageStructureComponents);
                } else {
                    // if container has components, insert single-page-structure and move existing components into single-main just after post-title
                    container.append({ type: 'single-page-structure' });
                    const single_page_structure = container.findType('single-page-structure')[0];
                    const single_main = single_page_structure.findType('single-main')[0];
                    const post_title = single_page_structure.findType('post-title')[0];
    
                    existingComponents.models.forEach(element => {
                        if ( element.is('single-page-structure')) return;
                        element.move(single_main, { at: post_title ? post_title.index() + 1 : 0 });
                    });
                }

            } else {
                // if container is empty, just insert single-page-structure with a default section just after post-title
                container.append({ type: 'single-page-structure' });
                const single_page_structure = container.findType('single-page-structure')[0];
                const single_main = single_page_structure.findType('single-main')[0];
                const post_title = single_page_structure.findType('post-title')[0];
                single_main.append(
                    { type: 'section' },
                    { at: post_title ? post_title.index() + 1 : 0 } 
                );
            }

            // Add class to wrapper for single page styling
            wrapper.addClass(['single', 'single-'+BUILDER_GLOBALS.posttype]);

            // Make container non-droppable and non-selectable
            // in order to work only within single-page-structure
            container.set({
                droppable: false,
                selectable: false,
            });
        }
    });
}