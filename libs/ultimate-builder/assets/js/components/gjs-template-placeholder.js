window.gjsTemplatePlaceholder = function (editor) {
    const domc = editor.DomComponents;
        css = editor.Css;
        editorConfig = editor.getConfig(),
        initial_components_data = editorConfig.initial_components_data,
        builderInstance = editorConfig.builderInstance;

    let modalContent = null;
    let modal = editor.Modal;

    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const modalTitle = __('Templates Library', 'templates_library');
    const loadingMessage = __('Loading templates...', 'loading_templates');
    const deleteConfirmation = __('Are you sure you want to delete this template?', 'delete_template_confirmation');
    const selectImageTitle = __('Select Template Thumbnail', 'select_image_title');
    const selectImageButton = __('Select', 'select_image_button');
    const invalid_image_file_msg = __('Please select a valid image file (jpg, png, gif, svg).', 'templates_system_invalid_image_file_msg');

    // Define the component
    domc.addType('template-placeholder', {
        model: {
            defaults: {
                tagName: 'div',
                '__temp-template-selected': false,
            }
        },
        view: {
            onRender({ el, model }) {
                // Flag to avoid undo/redo actions triggering the selector modal
                const templateSelected = model.get('__temp-template-selected');
                if(templateSelected) return;
                model.set('__temp-template-selected', true);

                const that = this;
                modalContent = document.createElement('div');
                modalContent.className = 'templates-library-gallery-wrapper';
                modalContent.innerHTML = `<p>${loadingMessage}</p>`;

                modal.onceOpen(() => {
                    // Load templates and display them in the modal
                    jQuery.ajax({
					    type: 'POST',
					    dataType : "json",
					    url: BUILDER_GLOBALS.ajax_url,
					    data : { 
					        action:'load_templates_library_gallery',
					        categories: [ 'components' ] // for now we just have one category but this can be extended in the future
					    },
					    beforeSend: function(){
                            modalContent.classList.add('templates_system_loading');
                        },
					    success: function(response){
                            modalContent.classList.remove('templates_system_loading');

					        if(response.status == "success") {
                                modalContent.innerHTML = response.content;

                                modalContent.querySelectorAll('.templates-library-btn').forEach(button => {
                                    button.addEventListener('click', (ev) => {
                                        ev.preventDefault();
                                        const itemWrapper = button.closest('.templates-library__item-wrapper');
                                        const action = button.getAttribute('data-action');
                                        const postId = button.getAttribute('data-id');

                                        if(action === 'insert' || action === 'delete' || action === 'remove-thumbnail') {
                                            that.handleItemActions(itemWrapper, postId, action);

                                        } else if(action === 'add-thumbnail') {
                                            var custom_uploader;

			                                custom_uploader = wp.media.frames.file_frame = wp.media({
			                                	title: selectImageTitle,
			                                	button: {text: selectImageButton},
			                                	multiple: false,
			                                	library: { type: 'image' }
			                                });

			                                custom_uploader.on('select', function() {
			                                	attachment = custom_uploader.state().get('selection').first().toJSON();
                                                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'];
                                                if (allowedTypes.includes(attachment.mime)) {
			                                		const thumb_url = attachment.url;
                                                    const thumb_id = attachment.id;
                                                    that.handleItemActions(itemWrapper, postId, action, { thumb_url, thumb_id });
                                                } else {
                                                    alert(invalid_image_file_msg);
                                                }
                                            });

                                            custom_uploader.open();
                                        }
                                    });
                                });
					        } else {
                                modalContent.innerHTML = `<div class="templates-system-response-msg"><p>${response.message}</p></div>`;
					        }
					    }
					});
                });

                modal.onceClose(() => {
                    // remove the "templates" component as it is just a placeholder and should not be saved as part of the template structure
                    that.model.remove();
                });

                modal.open({
                    title: modalTitle,
                    content: modalContent,
                });
            },
            /**
	 	     * handle item actions (insert, delete)
	 	     */
		    handleItemActions: function(item, post_id, action, additional_data = {}){
                const that = this;

                if(action === 'delete'){
                    if(!confirm(deleteConfirmation)) {
                        return;
                    }
                }

                let payload = { 
		    	    action:'templates_library_item_action',
		    	    post_id: post_id,
		    	    item_action: action
		    	}
                payload = { ...payload, ...additional_data };

                jQuery.ajax({
		    		type: 'POST',
		    		dataType : "json",
		    		url: BUILDER_GLOBALS.ajax_url,
		    		data : payload,
		    		beforeSend: function(){
		    			modalContent.classList.add('templates_system_loading');
		    		},
		    		success: function(response){
		    			modalContent.classList.remove('templates_system_loading');

		    			if(response.status == "success") {
		    				if(action === 'insert') {
                                const template_data = JSON.parse(response.template_data);
                                that.insertTemplate(template_data);
                            } else if(action === 'delete') {
                                that.removeTemplate(item, post_id);
                            } else if(action === 'add-thumbnail') {
                                that.addThumbnail(item, post_id, additional_data);
                            } else if(action === 'remove-thumbnail') {
                                that.removeThumbnail(item, post_id);
                            }
		    			} else {
                            modalContent.innerHTML = `<div class="templates-system-response-msg"><p>${response.message}</p></div>`;
		    			}
		    		}
		    	});
		    	return false;
		    },
            /**
             * Insert the template structure into the editor, right after the currently selected component.
             * Process order: 1. Register datastores, 2. Apply styles, 3. Insert full template as a single block
             */
            insertTemplate: function(template_data) {
                const selectedComponent = this.model;
                const selectedIndex = selectedComponent.index();
                const wrapper = selectedComponent.parent();

                if(Array.isArray(template_data.structure) && template_data.structure.length > 0) {
                    // Phase 1: Register all datastores throughout the tree
                    template_data.structure.forEach(componentData => {
                        this.registerDatastores(componentData);
                    });

                    // Phase 2: Apply all styles throughout the tree
                    template_data.structure.forEach(componentData => {
                        this.registerStyles(componentData);
                    });

                    // Phase 3: Build the full component tree and insert as a single block
                    template_data.structure.forEach((componentData, i) => {
                        const fullTree = this.buildComponentTree(componentData);
                        wrapper.append(fullTree, { at: selectedIndex + 1 + i });
                    });
                }

		    	modal.close();		    			
            },
            /**
             * Recursively walk the template tree and register all datastores,
             * pre-assigning a unique ID to each component that has a datastore
             */
            registerDatastores: function(componentData) {
                if(componentData.datastore) {
                    if(!componentData._generatedId) {
                        componentData._generatedId = builderInstance.generateId();
                    }
                    initial_components_data[componentData._generatedId] = componentData.datastore;
                }

                if(Array.isArray(componentData.components) && componentData.components.length > 0) {
                    componentData.components.forEach(nested => {
                        this.registerDatastores(nested);
                    });
                }
            },
            /**
             * Recursively walk the template tree and apply all CSS styles,
             * generating IDs for components that have styles but don't have one yet
             */
            registerStyles: function(componentData) {
                if(Array.isArray(componentData.styles) && componentData.styles.length > 0) {
                    if(!componentData._generatedId) {
                        componentData._generatedId = builderInstance.generateId();
                    }

                    componentData.styles.forEach(style => {
                        let ruleOpts = {};
                        if(style.atRuleType) {
                            ruleOpts.atRuleType = style.atRuleType;
                            ruleOpts.atRuleParams = style.mediaText;
                        }
                        let selector = `#${componentData._generatedId}`;
                        if(style.state) {
                            selector += `:${style.state}`;
                        }
                        css.setRule(selector, style.style, ruleOpts);
                    });
                }

                if(Array.isArray(componentData.components) && componentData.components.length > 0) {
                    componentData.components.forEach(nested => {
                        this.registerStyles(nested);
                    });
                }
            },
            /**
             * Recursively build the full component tree preserving the complete type + components structure,
             * so the editor receives the entire hierarchy as a single block
             */
            buildComponentTree: function(componentData) {
                const treeNode = {
                    type: componentData.type,
                };

                if(componentData._generatedId) {
                    treeNode.__id = componentData._generatedId;
                    treeNode.attributes = { id: componentData._generatedId };
                }

                if(Array.isArray(componentData.components) && componentData.components.length > 0) {
                    treeNode.components = componentData.components.map(nested => {
                        return this.buildComponentTree(nested);
                    });
                } else if(typeof componentData.components === 'string') {
                    treeNode.components = componentData.components;
                }

                return treeNode;
            },
            /**
             * Remove the template item from the DOM after deletion
             */
            removeTemplate: function(item, post_id) {
                item.remove();
            },
            /**
             * Add thumbnail to the template item in the library after setting it, using the URL provided in additional_data
             */
            addThumbnail: function(item, post_id, additional_data) {
                item.classList.add('has-post-thumbnail');
                item.querySelector('.templates-library__thumb').style.backgroundImage = `url(${additional_data.thumb_url})`;
            },
            /**
             * Remove thumbnail from the template item in the library after removing it, also remove the background image style
             */
            removeThumbnail: function(item, post_id) {
                item.classList.remove('has-post-thumbnail');
                item.querySelector('.templates-library__thumb').style.backgroundImage = '';
            }
        }
    });

    // Before saving remove the template placeholder component 
    // as it is just a trigger for the selector modal 
    // and should not be saved as part of the template structure
    editor.on('builder:before-save-editor', () => {
        const wrapper = editor.getWrapper();
        const container = wrapper.findType('container')[0];
        const templatePlaceholders = container.findType('template-placeholder');
        templatePlaceholders.forEach(placeholder => {
            placeholder.remove({silent:true});
        });
    });
}