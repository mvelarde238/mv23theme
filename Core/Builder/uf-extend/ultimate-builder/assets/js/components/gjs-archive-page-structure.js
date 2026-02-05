window.gjsArchivePageStructure = function (editor, options) {
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

    const archivePageStructureComponents = [
        { 
            type: 'archive-main',
            components: [
                { type: 'archive-title' },
                { type: 'archive-posts' }
            ]
        },
        { type: 'sidebar' }
    ];

    domc.addType('archive-main', {
        model: {
            defaults: Object.assign({}, notSelectableComponent, {
                name: 'Archive Main',
                tagName: 'main',
                classes: ['archive-main', 'main'],
                droppable: true
            }),
        }
    });

    const get_additional_data_callback = function(model, editor) {
        $additionalData = {};
        $archive_page_structure = model.closestType('archive-page-structure');
        const datastore = editor.getComponentDatastore($archive_page_structure);
        if (datastore) {
            const data = datastore.toJSON();
            $additionalData['archive_page_settings'] = data;
        }
        return $additionalData;
    };

    domc.addType('archive-title', {
        extend: 'async-component-abstract',
        model: {
            defaults: Object.assign({}, notSelectableComponent, {
                name: 'Archive Title',
                classes: ['archive-title', 'component'],
                __additionalDataCallback: get_additional_data_callback
            }),
        }
    });

    domc.addType('archive-posts', {
        extend: 'async-component-abstract',
        model: {
            defaults: Object.assign({}, notSelectableComponent, {
                name: 'Archive Posts',
                classes: ['archive-posts'],
                __additionalDataCallback: get_additional_data_callback
            }),
        }
    });

    domc.addType('archive-page-structure', {
        model: {
            defaults: Object.assign({}, notSelectableComponent, {
                name: 'Archive Page Structure',
                classes: ['archive-page-structure', 'main-content', 'main-content--sidebar-right'],
                selectable: true,
                hoverable: true,
                components: archivePageStructureComponents
            }),
        },
        view: {
            onRender({el, model}) {
                // Initial handling of datastore data
                this.handle_datastore_data();
            },
            custom_datastore_change_callback(changed) {
                const model = this.model;
                
                // Ignore changes that only affect __tab (tab switching)
                const changed_keys = Object.keys(changed);
                if (changed_keys.length && changed_keys[0] === '__tab') return;
                
                const datastore = editor.getComponentDatastore(model);
                const data = datastore.toJSON();
                const posttype = data.connected_posttype;
                const taxonomy = data['connected_'+posttype+'_taxonomy'];
                // const terms = data['connected_'+taxonomy+'_terms'];

                const archive_title = model.findType('archive-title')[0];
                const archive_posts = model.findType('archive-posts')[0];

                if ( changed.connected_posttype ) {
                    archive_title.getView().render();
                }
                if ( changed_keys[0] === 'connected_'+posttype+'_taxonomy' ) {
                    archive_title.getView().render();
                }

                $rerender_archive_posts_on_change = [
                    'connected_posttype',
                    'connected_'+taxonomy+'_terms', 
                    'listing_template',
                    'postcard_settings',
                    'show_filter',
                    'pagination_type',
                    'filters'
                ];
                if ( $rerender_archive_posts_on_change.includes( changed_keys[0] ) ) {
                    archive_posts.getView().render();
                }

                if (changed_keys[0] === 'columns' || changed_keys[0] === 'columns_gap') {
                    // Update CSS properties for columns and gap
                    const listingEl = model.getEl().querySelector('.posts-listing');
                    const devices = ['desktop', 'laptop', 'tablet', 'mobile'];
                    const columns = data.columns || {};
                    const gaps = data.columns_gap || {};
                    if (listingEl) {
                        devices.forEach(device => {
                            listingEl.style.setProperty(`--${device[0]}-columns`, columns[device]);
                            listingEl.style.setProperty(`--${device[0]}-gap`, gaps[device]+'px');
                        });
                    }
                }

                // for other changes (sidebar, hide elements, etc), just handle datastore data
                this.handle_datastore_data();
            },
            handle_datastore_data() {
                const model = this.model;

                const datastore = editor.getComponentDatastore(model);
                if (datastore) {
                    const data = datastore.toJSON();

                    const sidebar = model.findType('sidebar')[0];
                    const archive_title = model.findType('archive-title')[0];

                    // Handle post title visibility
                    const archive_title_display = data['hide_archive_title'] ? 'none' : 'block';
                    if(archive_title) archive_title.setStyle({ display: archive_title_display });

                    // Handle sidebar visibility
                    const sidebar_display = (data['page_template'] === 'main-content--sidebarless' ) ? 'none' : 'block';
                    if(sidebar) sidebar.setStyle({ display: sidebar_display });

                    // Handle page template
                    model.set('classes', ['archive-page-structure', 'main-content', data['page_template']]);
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
        if( BUILDER_GLOBALS.is_archive ){
            const wrapper = editor.getWrapper();
            const container = wrapper.findType('container')[0];

            // Check if container has any component
            const existingComponents = container.components();
            if (existingComponents.length > 0) {

                const archive_page_structure_exists = container.findType('archive-page-structure')[0];
                if ( archive_page_structure_exists ) {
                    // Ensuring correct structure: main, sidebar, archive-title, archive-posts, etc.
                    ensureComponentStructure(archive_page_structure_exists, archivePageStructureComponents);
                    return;
                }

                // if container has components, insert archive-page-structure and move existing components into archive-main
                container.append({ type: 'archive-page-structure' });
                const archive_page_structure = container.findType('archive-page-structure')[0];
                const archive_main = archive_page_structure.findType('archive-main')[0];

                existingComponents.models.forEach(element => {
                    if ( element.is('archive-page-structure')) return;
                    element.move(archive_main);
                });

            } else {
                // if container is empty, just insert archive-page-structure
                container.append({ type: 'archive-page-structure' });
            }

            container.set({
                droppable: false,
                selectable: false,
            });
        }
    });
}