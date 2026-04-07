window.saveTemplateSystem = function (editor, options) {

    /* TO DO LIST:
    - add a pagination system for the template library modal in case there are a lot of templates saved
    - add a category selector to the save template form and filter templates by category in the library
    - expand thumbnail on a modal window for better previsualization or make it bigger
    - Take the screenshot with background instead of transparent, so it looks better in the library. This can be done by temporarily setting a white/dark background (depending on the context) on the component before capture, then removing it after.
    */

    const commands = editor.Commands;

    // Labels for the form, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const saveTitle = __('Save as Template', 'save_as_template');
    const saveTagline = __('Enter the name of the item:', 'save_as_template_tagline');
    const titlePlaceholder = __('Template title', 'save_as_template_title_placeholder');
    const saveLabel = __('Save', 'save_as_template_save_label');
    const successMessage = __('Template saved successfully!', 'template_saved_successfully');

    /**
     * Recursively extracts all template data from the live GJS component tree, 
     * including nested components, while also handling datastore references and styles. 
     * The extracted data is structured in a way that allows it to be easily re-inserted into the editor later when the template is used.
     * @param {Component} componentModel - The root component model to extract data from
     * @returns {Object} An object containing the structured template data, each component in the structure includes:
     * {
     *   type: string, // the type of the component
     *   attributes: object, // the component's attributes, excluding the id
     *   components: array|string, // the nested components, either as an array of component data or as raw HTML if it was a string in the original model
     *   datastore: object|null, // the datastore data if this component has a datastore reference
     *   styles: array // an array of style rules associated with this component's original id (if it had one), including any media query information for responsive styles
     * }
     */
    function extractData(componentModel) {
        function walk(model) {
            const node = model.toJSON ? model.toJSON() : model;
            const { type, attributes = {}, __id } = node;

            const originalId = attributes.id || null;

            const safeAttributes = { ...attributes };
            if (safeAttributes.id) {
                delete safeAttributes.id;
            }

            let datastore = null;
            const datastoreModel = editor.getComponentDatastore(model);
            if (datastoreModel) {
                datastore = datastoreModel.toJSON();
                // we can remove the *_prepared properties as they are only used for the live editing experience and not needed for the template structure
                Object.keys(datastore).forEach(key => {
                    if (key.endsWith('_prepared')) {
                        delete datastore[key];
                    }
                });
            }

            let styles = [];
            if (originalId) {
                const idSelector = '#' + originalId;

                editor.Css.getRules().forEach(rule => {
                    const ruleJson = rule.toJSON();
                    const selectorsAdd = ruleJson.selectorsAdd || '';
                    const style = ruleJson.style;
                    const mediaText = ruleJson.mediaText;

                    // selectors is a Backbone Collection, read models directly
                    const selectorModels = rule.get('selectors');
                    const matchesSelectors = selectorModels && selectorModels.some(sel => sel.get('name') === originalId);

                    // Match rules where selectorsAdd contains the id (e.g. "#iyvp .carousel__item")
                    const matchesSelectorsAdd = selectorsAdd && selectorsAdd.includes(idSelector);

                    if (matchesSelectors || matchesSelectorsAdd) {
                        const ruleData = { style };
                        if (selectorsAdd) ruleData.selectorsAdd = selectorsAdd.replaceAll(idSelector, '#%comp_id%');
                        if (matchesSelectors) ruleData.selectors = selectorModels.map(sel => sel.get('name'));
                        if (mediaText) ruleData.mediaText = mediaText;
                        styles.push(ruleData);
                    }
                });
            }

            let safeComponents;
            const rawComponents = node.components;
            if (typeof rawComponents === 'string') {
                safeComponents = rawComponents;
            } else {
                const childModels = model.components ? model.components().models || [] : [];
                safeComponents = childModels.map(child => walk(child));
            }

            const structure = { type, attributes: safeAttributes, components: safeComponents, datastore, styles };

            return structure;
        }

        return {
            structure: [walk(componentModel)]
        };
    }

    /**
     * Captures a screenshot of the given component using html-to-image (window.htmlToImage.toPng).
     * The element is passed directly so html-to-image can resolve styles from its ownerDocument
     * (the GrapeJS canvas iframe), which avoids the unstyled-clone problem.
     * @param {Component} component - The GrapeJS component to capture
     * @returns {Promise<string|null>} A promise that resolves to a PNG data URL, or null on failure
     */
    function captureComponent(component) {
        const el = component.getEl();

        if (!el) {
            return Promise.resolve(null);
        }

        if (typeof htmlToImage === 'undefined' || typeof htmlToImage.toPng !== 'function') {
            console.warn('captureComponent: html-to-image library is not available.');
            return Promise.resolve(null);
        }

        // Suppress non-fatal SecurityErrors thrown when html-to-image tries to
        // read cssRules from cross-origin stylesheets (e.g. Font Awesome CDN).
        // Those errors are caught internally and do not affect the output — the
        // fonts still render because the browser fetches the font files from cache.
        const originalConsoleError = console.error;
        console.error = function (...args) {
            // html-to-image logs non-fatal SecurityErrors when trying to read
            // cssRules from cross-origin stylesheets. Filter any argument that
            // mentions cssRules, SecurityError, or cross-origin CSS inlining.
            const combined = args.map(a => (a && a.toString ? a.toString() : '')).join(' ');
            if (combined.includes('cssRules') || combined.includes('SecurityError') || combined.includes('inlining remote css')) return;
            originalConsoleError.apply(console, args);
        };

        return htmlToImage.toPng(el, { pixelRatio: 1 })
            .then(function (dataUrl) {
                return dataUrl;
            })
            .catch(function (err) {
                console.warn('captureComponent: could not capture screenshot.', err);
                return null;
            })
            .finally(function () {
                console.error = originalConsoleError;
            });
    }

    /** 
     * Command to save the currently selected component as a template. 
     * It extracts the component's data, shows a form in a modal to enter the template name, 
     * and sends the data to the server via AJAX to be saved in the library. 
     */
    commands.add('save-as-template', (editor, sender, options = {}) => {
        const component = editor.getSelected();
        const templateData = extractData(component);

        // create a simple form for the modal content
        const form_wrapper = document.createElement('div');
        form_wrapper.classList.add('templates-system-save-form');
        form_wrapper.innerHTML = `
            <h2>${saveTitle}</h2>
            <p>${saveTagline}</p>
            <form action="">
                <input type="text" name="title" placeholder="${titlePlaceholder}" required>
                <button class="button-primary" type="submit">${saveLabel}</button>
                <br/>
                <input type="hidden" name="category" value="components"></input>
                <textarea style="display:none" name="template_data">${JSON.stringify(templateData)}</textarea>
            </form>
        `;

        const modal = editor.Modal;

        modal.onceOpen(() => {
            setTimeout(() => {
                // focus the input
                form_wrapper.querySelector('input[name="title"]').focus();
            }, 100);

            // handle form submission
            const form = form_wrapper.querySelector('form');
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                const title = formData.get('title');
                const templateData = formData.get('template_data');

                if (title == '') {
                    alert('Please enter a name for the template.');
                    form.querySelector('input[name="title"]').focus();
                    return;
                }

                const payload = {
                    action: 'templates_library_save_item',
                    title,
                    template_data: templateData,
                };

                const category = formData.get('category');
                if(category) {
                    payload.category = category;
                }

                form_wrapper.classList.add('templates_system_loading');

                // Capture the screenshot at submit time so it doesn't interfere
                // with typing. The AJAX request is sent once the capture resolves.
                captureComponent(component).then(function (thumbnailDataUrl) {
                    if (thumbnailDataUrl) {
                        payload.thumbnail = thumbnailDataUrl;
                    }

                    jQuery.ajax({
				    	type: 'POST',
				    	dataType : "json",
				    	url: BUILDER_GLOBALS.ajax_url,
				    	data: payload,
				    	success: function(response){
                            if(response && response.status === 'success'){
                                const successMessageWrapper = document.createElement('div');
                                successMessageWrapper.className = 'uf-form-success';
                                successMessageWrapper.innerHTML = `<p>${successMessage}</p>`;
                                document.body.appendChild(successMessageWrapper);

                                setTimeout(() => {
                                    form_wrapper.classList.remove('templates_system_loading');
                                    modal.close();
                                }, 1000);
                            } else {
                                alert('Error saving template: ' + (response?.message || 'Unknown error'));
                            }
				    	}
				    });
                }); // end captureComponent.then
            });
        });

        modal.open({
            title: saveTitle,
            content: form_wrapper,
        });
    });
}