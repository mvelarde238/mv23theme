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
                classes: ['single-main', 'main'],
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
                this.handle_post_content();
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
            handle_post_content() {
                const post_content = BUILDER_GLOBALS.post_content || '';
                
                if (!post_content || post_content.trim() === '') {
                    return;
                }

                // Check if migration dialog already exists
                if (this.migrationDialogShown) {
                    return;
                }
                this.migrationDialogShown = true;

                // Create modal overlay
                const overlay = document.createElement('div');
                overlay.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.7);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 99999;
                `;

                // Create modal dialog
                const dialog = document.createElement('div');
                dialog.style.cssText = `
                    background: #fff;
                    padding: 30px;
                    border-radius: 8px;
                    max-width: 500px;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
                `;

                dialog.innerHTML = `
                    <h2 style="margin-top: 0; color: #333; font-size: 20px;">Contenido Detectado</h2>
                    <p style="color: #666; line-height: 1.6; margin: 15px 0;">
                        Se ha detectado contenido guardado en este post. Para editarlo en el builder, 
                        es necesario migrarlo. Esta acción moverá el contenido al builder y lo eliminará 
                        del editor clásico.
                    </p>
                    <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                        <button id="migrate-cancel" style="
                            padding: 10px 20px;
                            border: 1px solid #ddd;
                            background: #fff;
                            border-radius: 4px;
                            cursor: pointer;
                            font-size: 14px;
                        ">No Migrar</button>
                        <button id="migrate-accept" style="
                            padding: 10px 20px;
                            border: none;
                            background: #2271b1;
                            color: #fff;
                            border-radius: 4px;
                            cursor: pointer;
                            font-size: 14px;
                        ">Migrar Contenido</button>
                    </div>
                `;

                overlay.appendChild(dialog);
                document.body.appendChild(overlay);

                // Handle cancel button
                document.getElementById('migrate-cancel').addEventListener('click', () => {
                    document.body.removeChild(overlay);
                });

                // Handle accept button
                document.getElementById('migrate-accept').addEventListener('click', () => {
                    const acceptBtn = document.getElementById('migrate-accept');
                    acceptBtn.textContent = 'Migrando...';
                    acceptBtn.disabled = true;

                    // Call AJAX to migrate content
                    fetch(BUILDER_GLOBALS.ajax_url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            action: 'migrate_post_content_to_builder',
                            nonce: BUILDER_GLOBALS.nonce,
                            post_id: BUILDER_GLOBALS.post_id
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Add text-editor component with the post content below post-title
                            const model = this.model;
                            const single_main = model.findType('single-main')[0];
                            const post_title = model.findType('post-title')[0];
                            
                            if (single_main) {
                                const insertIndex = post_title ? post_title.index() + 1 : 0;
                                const text_editor = single_main.append({
                                    type: 'text-editor'
                                }, { at: insertIndex, silent: true });

                                // select and focus the new text-editor
                                editor.select(text_editor[0]);
                                editor.trigger('component:toggled', text_editor[0]);

                                const text_editor_datastore = editor.getComponentDatastore(text_editor[0]);
                                if (text_editor_datastore) {
                                    text_editor_datastore.set({
                                        content: BUILDER_GLOBALS.post_content
                                    });
                                }
                            }

                            // Clear the global post_content
                            BUILDER_GLOBALS.post_content = '';

                            // Close modal
                            document.body.removeChild(overlay);

                            // Show success notification
                            const successMessage = document.createElement('div');
                            successMessage.className = 'uf-form-success';
                            successMessage.innerHTML = '<p>El contenido ha sido migrado al builder correctamente.</p>';
                            document.body.appendChild(successMessage);

                            // Save the editor
                            editor.runCommand('builder:save-editor');
                        } else {
                            alert('Error al migrar el contenido: ' + (data.data || 'Error desconocido'));
                            acceptBtn.textContent = 'Migrar Contenido';
                            acceptBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al migrar el contenido');
                        acceptBtn.textContent = 'Migrar Contenido';
                        acceptBtn.disabled = false;
                    });
                });
            }
        }
    });

    // Recursively ensure the component structure exists
    const ensureComponentStructure = (parent, structureArray) => {
        structureArray.forEach((componentDef, index) => {
            const { type, components } = componentDef;
            
            // Check if a component of this type already exists in the parent
            let existingComponent = parent.findType(type)[0];
            
            // If it doesn't exist, create it
            if (!existingComponent) {
                console.log(`Creating missing component: ${type}`);
                parent.append({ type }, { at: index });
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

                const single_page_structure_exists = container.findType('single-page-structure')[0];
                if ( single_page_structure_exists ) {
                    // Ensuring correct structure: main, sidebar, post-title, social-share, related-posts, etc.
                    ensureComponentStructure(single_page_structure_exists, singlePageStructureComponents);
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