window.gjsSection = function(editor) {
    const domc = editor.DomComponents;

    // Use shared templates from global resource
    const sectionTemplates = window.gjsSharedResources.templates;

    // Add the section type to the DomComponents
    domc.addType('section', {
        isComponent: el => el.classList && el.classList.contains('page-module'),
        model: {
            defaults: {
                name: 'Section',
                tagName: 'div',
                draggable: function (target, destination) {
                    return (destination.attributes.type) == 'container';
                },
                classes: ['page-module'],
                styles: `
                    .page-module {
                        padding: 40px 0 40px 0;
                    }
                `,
                // Attribute to track if a template has been selected
                // This allows the UndoManager to register the action even for "Empty Section"
                'template-selected': false,
            },
        },
        view: {
            init() {
                // Listen to component changes to hide selector when components are added
                this.listenTo(this.model.components(), 'add remove reset', this.handleComponentsChange);
                
                // Listen to undo/redo events to update selector visibility
                this.listenTo(this.em, 'undo redo', this.handleUndoRedo);
            },

            onRender({ el, model }) {
                this.updateTemplateSelector();
            },

            handleComponentsChange() {
                this.updateTemplateSelector();
            },

            handleUndoRedo() {
                // Use setTimeout to ensure the undo/redo action has completed
                setTimeout(() => {
                    this.updateTemplateSelector();
                }, 0);
            },

            updateTemplateSelector() {
                const components = this.model.components();
                const existingSelector = this.el.querySelector('.section-template-selector');
                const hasSelectedTemplate = this.model.get('template-selected');
                
                // Remove selector if components exist OR if user already selected a template
                if ((components.length > 0 || hasSelectedTemplate) && existingSelector) {
                    existingSelector.remove();
                }
                
                // Show selector only when section is empty AND no template has been selected
                if (components.length === 0 && !hasSelectedTemplate && !existingSelector) {
                    this.showTemplateSelector();
                }
            },

            showTemplateSelector() {
                // Remove any existing selector first
                const existingSelector = this.el.querySelector('.section-template-selector');
                if (existingSelector) {
                    existingSelector.remove();
                }

                // Create main container
                const container = document.createElement('div');
                container.classList.add('section-template-selector');

                // Create wrapper for buttons with max-width
                const buttonsWrapper = document.createElement('div');
                buttonsWrapper.classList.add('template-buttons-wrapper');

                // Create button for each template
                Object.keys(sectionTemplates).forEach(templateKey => {
                    const template = sectionTemplates[templateKey];
                    const btn = this.createTemplateButton(templateKey, template);
                    buttonsWrapper.appendChild(btn);
                });

                container.appendChild(buttonsWrapper);
                this.el.appendChild(container);
            },

            createTemplateButton(templateKey, template) {
                const btn = document.createElement('button');
                btn.classList.add('template-btn');
                btn.dataset.template = templateKey;
                btn.type = 'button';
                
                btn.innerHTML = window.gjsSharedResources.createTemplateButton(templateKey, template);
                
                return btn;
            },

            events: {
                'click .template-btn': 'insertTemplate'
            },

            insertTemplate(ev) {
                ev.preventDefault();
                ev.stopPropagation();
                
                // Find the button element (in case click was on child elements)
                const button = ev.target.closest('.template-btn');
                if (!button) return;
                
                const templateKey = button.dataset.template;
                const template = sectionTemplates[templateKey];
                
                if (template) {
                    // Mark that a template has been selected
                    // This is registered in the UndoManager, allowing undo even for "Empty Section"
                    this.model.set('template-selected', true);
                    
                    // Add the template components to the section (if any)
                    if (template.components && template.components.length > 0) {
                        this.model.components(template.components);
                        
                        // Execute template callback if defined
                        if (template.onInsert) {
                            template.onInsert(this.em, this.model);
                        }
                    }
                    
                    // Remove the template selector UI immediately
                    const selector = this.el.querySelector('.section-template-selector');
                    if (selector) {
                        selector.remove();
                    }
                }
            }
        }
    });

    // Add a block in structure category
    editor.Blocks.add('section', {
        label: 'Section',
        media: '<i class="dashicons dashicons-align-wide"></i>',
        category: 'Structure',
        content: { type: 'section' }
    });
}