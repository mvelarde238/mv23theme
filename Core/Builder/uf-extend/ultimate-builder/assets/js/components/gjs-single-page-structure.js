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
            type: 'single_main',
            components: [
                { type: 'post_title' },
                { type: 'single_main_content' },
                { type: 'social_share' },
                { type: 'related_posts' }
            ]
        },
        { type: 'sidebar' }
    ];

    domc.addType('single_main', {
        model: {
            defaults: Object.assign({}, notSelectableComponent, {
                name: 'Single Main',
                tagName: 'main',
                classes: ['single-main', 'main'],
            }),
        }
    });

    domc.addType('single_main_content', {
        model: {
            defaults: Object.assign({}, notSelectableComponent, {
                name: 'Single Main Content',
                droppable: true
            }),
        }
    });

    domc.addType('single_page_structure', {
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
            custom_datastore_change_callback() {
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
                        const post_title = model.findType('post_title')[0];
                        const social_share = model.findType('social_share')[0];
                        const related_posts = model.findType('related_posts')[0];

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

                        // Handle page template
                        model.set('classes', ['single-page-structure', 'main-content', settings['page_template']]);
                    }
                }
            }
        }
    });

    // Recursively ensure the component structure exists
    const ensureComponentStructure = (parent, structureArray) => {
        structureArray.forEach(componentDef => {
            const { type, components } = componentDef;
            
            // Check if a component of this type already exists in the parent
            let existingComponent = parent.findType(type)[0];
            
            // If it doesn't exist, create it
            if (!existingComponent) {
                console.log(`Creating missing component: ${type}`);
                parent.append({ type });
                existingComponent = parent.findType(type)[0];
            }
            
            // If it has child components, call recursively
            if (components && components.length > 0 && existingComponent) {
                ensureComponentStructure(existingComponent, components);
            }
        });
    };

    // On builder loaded, customize the canvas
    editor.on('builder:loaded', () => {
        if( BUILDER_GLOBALS.is_singular ){
            const wrapper = editor.getWrapper();
            const container = wrapper.findType('container')[0];

            // Check if container has any component
            const existingComponents = container.components();
            if (existingComponents.length > 0) {

                const single_page_structure_exists = container.findType('single_page_structure')[0];
                if ( single_page_structure_exists ) {
                    // Ensuring correct structure: main, sidebar, post_title, single_main_content, social_share, related_posts, etc.
                    ensureComponentStructure(single_page_structure_exists, singlePageStructureComponents);
                    return;
                }

                // if container has components, insert single_page_structure and move existing components into single_main_content
                container.append({ type: 'single_page_structure' });
                const single_page_structure = container.findType('single_page_structure')[0];
                const single_main_content = single_page_structure.findType('single_main_content')[0];

                existingComponents.models.forEach(element => {
                    if ( element.is('single_page_structure')) return;
                    element.move(single_main_content);
                });

            } else {
                // if container is empty, just insert single_page_structure with a default section inside single_main_content
                container.append({ type: 'single_page_structure' });
                const single_page_structure = container.findType('single_page_structure')[0];
                const single_main_content = single_page_structure.findType('single_main_content')[0];
                single_main_content.append({ type: 'section' });
            }

            container.set({
                droppable: false,
                selectable: false,
            });
        }
    });
}