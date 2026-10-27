window.gjsWrapper = function (editor, options) {
    const domc = editor.DomComponents;

    const __ = editor.createTranslator(editor);

    domc.addType('wrapper', {
        view: {
            onRender({el, model}) {
                // Handle post content migration from classic editor to builder
                this.handle_post_content();
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
                                    {
                                        type: 'container',
                                        components: [
                                            { type: 'text-editor' }
                                        ],
                                    }
                                ]
                            };
                            let section_for_migrated_content;
                            let text_editor_for_migrated_content;

                            // GrapesJS's handleUpdates() uses setTimeout(0) internally, so
                            // changesCount increments fire AFTER clearDirtyCount() runs in the
                            // save command. A reactive listener on change:changesCount is the
                            // only reliable way to keep changesCount at 0 throughout the migration.
                            editor.UndoManager.stop();
                            const keepClean = (model, value) => {
                                if (value > 0) editor.clearDirtyCount();
                            };
                            editor.em.on('change:changesCount', keepClean);

                            if( BUILDER_GLOBALS.is_single_template_cpt ){
                                // Add text-editor component with the post content below post-title
                                const single_main = model.findType('single-main')[0];
                                if (single_main) {
                                    const post_title = model.findType('post-title')[0];
                                    const insertIndex = post_title ? post_title.index() + 1 : 0;
                                    section_for_migrated_content = single_main.append(content_to_insert, { at: insertIndex });
                                }
                            } else {
                                // For non-singular templates, we can add the text-editor as first child of the page content
                                const page_content = model.findType('page-content')[0];
                                section_for_migrated_content = page_content.append(content_to_insert, { at: 0 });
                            }

                            text_editor_for_migrated_content = section_for_migrated_content[0].findType('text-editor')[0];

                            if(text_editor_for_migrated_content){
                                // add the content to the new text-editor's datastore
                                const text_editor_datastore = editor.getComponentDatastore(text_editor_for_migrated_content);
                                if (text_editor_datastore) {
                                    text_editor_datastore.set({
                                        content: BUILDER_GLOBALS.post_content
                                    });
                                }

                                // select and focus the new text-editor
                                editor.select(text_editor_for_migrated_content);
                                editor.trigger('component:toggled', text_editor_for_migrated_content);
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

                            // After all async handlers (handleUpdates, datastore listeners, etc.)
                            // have settled, detach the guard, resume tracking, and confirm clean state.
                            setTimeout(() => {
                                editor.em.off('change:changesCount', keepClean);
                                editor.UndoManager.start();
                                editor.UndoManager.clear();
                                editor.clearDirtyCount();
                                window.onbeforeunload = null;
                            }, 500);
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
};