window.gjsWrapper = function (editor, options) {
    const domc = editor.DomComponents;

    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const compName = __('Page');

    /* Extend Wrapper (Body) Component */
    domc.addType('wrapper', {
        model: {
            defaults: {
                name: compName,
                droppable: false,
                highlightable: false,
                stylable: true,
                unstylable: []
            },
        },
        view: {
            onRender({el, model}) {
                // Handle post content migration from classic editor to builder
                this.handle_post_content();

                // Initial handling of datastore data
                setTimeout(() => {
                    this.handle_datastore_data();
                }, 100);
            },
            custom_datastore_change_callback(changed) {
                const model = this.model;
                
                // Ignore changes that only affect __tab (tab switching)
                const changed_keys = Object.keys(changed);
                if (changed_keys.length && changed_keys[0] === '__tab') return;

                // NOTE: We specifically check for the first changed key to be 'custom_header_post' because when this value changes can be "falsy"
                // for example when the custom header post is unset. We want to make sure to still trigger the header preview update in that case.
                if (changed_keys[0] === 'custom_header_post') {
                    // If header post is changed, we need to trigger a full re-render to update the header preview component with the new header post data.
                    const header_preview = model.findType('header-preview')[0];
                    if (header_preview) {
                        if( changed.custom_header_post ){
                            header_preview.set('filters_to_apply', [
                                {
                                    'name': 'pre_option_theme_header_post',
                                    'value': changed.custom_header_post
                                }, 
                            ]);
                        } else {
                            header_preview.set('filters_to_apply', []);
                        }
                        header_preview.view.render();
                    }
                }

                if( changed_keys.includes('settings') ){
                    editor.handleCommonSettings(model);
                } else {
                    this.handle_datastore_data();
                }
            },
            handle_datastore_data() {
                const model = this.model;

                const datastore = editor.getComponentDatastore(model);
                if (datastore) {
                    const data = datastore.toJSON();
                    
                    // Handle placing content under header by removing padding top from canvas body
                    const { place_content_under_header, hide_footer } = data;
                    const canvas = editor.Canvas;
                    const canvasBody = canvas.getBody();
                    const wrapper = editor.getWrapper();
                    
                    if (place_content_under_header) {
                        wrapper.getEl().style.paddingTop = '0px';
                    } else {
                        wrapper.getEl().style.paddingTop = 'var(--static-header-height)';
                    }

                    // Handle hiding/showing static and sticky header and their logos by adding/removing classes to canvas body
                    const headerKeys = ['static', 'sticky'];
                    for (const key of headerKeys) {
                        const hideHeader = data['hide_' + key + '_header'] ?? false;
                        if (hideHeader) {
                            canvasBody.classList.add('hide-' + key + '-header');
                        } else {
                            canvasBody.classList.remove('hide-' + key + '-header');
                        }
                        
                        const hideHeaderLogo = data['hide_' + key + '_header_logo'] ?? false;
                        if (hideHeaderLogo) {
                            canvasBody.classList.add('hide-' + key + '-header-logo');
                        } else {
                            canvasBody.classList.remove('hide-' + key + '-header-logo');
                        }
                    }

                    // Handle hiding sticky header on builder by adding/removing class to canvas body
                    if ( BUILDER_GLOBALS.posttype != 'header' ){
                        const hideStickyHeaderOnBuilder = data['hide_sticky_header_on_builder'] ?? true;
                        if (hideStickyHeaderOnBuilder) {
                            canvasBody.classList.add('hide-sticky-header');
                        } else {
                            canvasBody.classList.remove('hide-sticky-header');
                        }
                    }

                    // Handle hiding footer by adding/removing class to canvas body
                    if ( hide_footer ) {
                        canvasBody.classList.add('hide-footer');
                    } else {
                        canvasBody.classList.remove('hide-footer');
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

                // Labels for the form, using the editor's translator for internationalization
                const __ = editor.createTranslator(editor);
                const modalTitle = __('Content Detected', 'content_migration_modal_title');
                const modalMessage = __('Saved content has been detected in the WordPress classic editor for this post. To edit it in the builder, it needs to be migrated. This action will move the content to the builder and remove it from the classic editor.', 'content_migration_modal_message');
                const cancelButtonLabel = __('Do Not Migrate', 'content_migration_cancel_button');
                const acceptButtonLabel = __('Migrate Content', 'content_migration_accept_button');
                const migratingMessage = __('Migrating...', 'content_migration_migrating_message');
                const migrationErrorMessage = __('Error migrating content. Please try again.', 'content_migration_error_message');
                const migrationSuccessMessage = __('Content has been successfully migrated to the builder.', 'content_migration_success_message');
                const unknownErrorMessage = __('Unknown error occurred.', 'content_migration_unknown_error_message');

                dialog.innerHTML = `
                    <h2 style="margin-top: 0; color: #333; font-size: 20px;">${modalTitle}</h2>
                    <p style="color: #666; line-height: 1.6; margin: 15px 0;">
                        ${modalMessage}
                    </p>
                    <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                        <button id="migrate-cancel" style="
                            padding: 10px 20px;
                            border: 1px solid #ddd;
                            background: #fff;
                            border-radius: 4px;
                            cursor: pointer;
                        ">${cancelButtonLabel}</button>
                        <button id="migrate-accept" style="
                            padding: 10px 20px;
                            border: none;
                            background: #2271b1;
                            color: #fff;
                            border-radius: 4px;
                            cursor: pointer;
                            font-size: 14px;
                        ">${acceptButtonLabel}</button>
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
                    acceptBtn.textContent = migratingMessage;
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
                            const model = this.model;
                            const content_to_insert = {
                                type: 'section',
                                components: [
                                    { type: 'text-editor' }
                                ]
                            };
                            let section_for_migrated_content;

                            if( BUILDER_GLOBALS.is_singular ){
                                // Add text-editor component with the post content below post-title
                                const single_main = model.findType('single-main')[0];
                                if (single_main) {
                                    const post_title = model.findType('post-title')[0];
                                    const insertIndex = post_title ? post_title.index() + 1 : 0;
                                    section_for_migrated_content = single_main.append(content_to_insert, { at: insertIndex, silent: true });
                                }
                            } else {
                                // For non-singular templates, we can add the text-editor as first child of the container
                                const container = model.findType('container')[0];
                                section_for_migrated_content = container.append(content_to_insert, { at: 0, silent: true });
                            }

                            const text_editor_for_migrated_content = section_for_migrated_content[0].findType('text-editor')[0];

                            if(text_editor_for_migrated_content){
                                // select and focus the new text-editor
                                editor.select(text_editor_for_migrated_content);
                                editor.trigger('component:toggled', text_editor_for_migrated_content);

                                // add the content to the new text-editor's datastore
                                const text_editor_datastore = editor.getComponentDatastore(text_editor_for_migrated_content);
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
                            successMessage.innerHTML = '<p>' + migrationSuccessMessage + '</p>';
                            document.body.appendChild(successMessage);

                            // Save the editor
                            editor.runCommand('builder:save-editor');
                        } else {
                            alert(migrationErrorMessage + ': ' + (data.data || unknownErrorMessage));
                            acceptBtn.textContent = acceptButtonLabel;
                            acceptBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert(migrationErrorMessage);
                        acceptBtn.textContent = acceptButtonLabel;
                        acceptBtn.disabled = false;
                    });
                });
            }
        }
    });
}