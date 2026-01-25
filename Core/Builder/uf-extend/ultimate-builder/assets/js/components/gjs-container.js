window.gjsContainer = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'container';

    // Transform shared templates to wrap in section components
    const containerTemplates = {};
    Object.keys(window.gjsSharedResources.templates).forEach(key => {
        const template = window.gjsSharedResources.templates[key];
        containerTemplates[key] = {
            label: template.label,
            visual: template.visual,
            components: [{
                type: 'section',
                'template-selected': true, // Prevent section from showing its own selector
                components: template.components
            }]
        };
    });

    domc.addType(compClass, {
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: 'Container',
                tagName: 'div',
                dropable: true,
                draggable: false,
                removable: false,
                copyable: false,
                selectable: false,
                hoverable: false,
                highlightable: true,
                classes: [compClass]
            },
        },
        view: {
            init() {
                // Listen to component changes to maintain quick-add at the end
                this.listenTo(this.model.components(), 'add remove reset', this.ensureQuickAddAtEnd);
                
                // Listen to undo/redo to maintain quick-add position
                this.listenTo(this.em, 'undo redo', () => {
                    setTimeout(() => this.ensureQuickAddAtEnd(), 0);
                });
            },

            onRender({ el, model }) {
                // Ensure quick-add is always at the end on render
                this.ensureQuickAddAtEnd();
            },

            ensureQuickAddAtEnd() {
                if( BUILDER_GLOBALS.is_singular ) return; // Disable quick-add in single post/page builder

                // Remove existing quick-add element from DOM
                const existingQuickAdd = this.el.querySelector('.container-quick-add');
                if (existingQuickAdd) {
                    existingQuickAdd.remove();
                }

                // Create and append new quick-add element
                this.showQuickAdd();
            },

            showQuickAdd() {
                // Create main container
                const container = document.createElement('div');
                container.classList.add('container-quick-add');

                // Add title
                const title = document.createElement('div');
                title.classList.add('container-quick-add-title');
                title.textContent = '+ Quick Add Section';
                container.appendChild(title);

                // Create wrapper for buttons with max-width
                const buttonsWrapper = document.createElement('div');
                buttonsWrapper.classList.add('template-buttons-wrapper');

                // Create button for each template
                Object.keys(containerTemplates).forEach(templateKey => {
                    const template = containerTemplates[templateKey];
                    const btn = this.createQuickAddButton(templateKey, template);
                    buttonsWrapper.appendChild(btn);
                });

                container.appendChild(buttonsWrapper);
                // Append to the end of container
                this.el.appendChild(container);
            },

            createQuickAddButton(templateKey, template) {
                const btn = document.createElement('button');
                btn.classList.add('quick-add-btn');
                btn.dataset.template = templateKey;
                btn.type = 'button';
                
                btn.innerHTML = window.gjsSharedResources.createQuickAddButton(templateKey, template);
                
                return btn;
            },

            events: {
                'click .quick-add-btn': 'addTemplate'
            },

            addTemplate(ev) {
                ev.preventDefault();
                ev.stopPropagation();
                
                // Find the button element
                const button = ev.target.closest('.quick-add-btn');
                if (!button) return;
                
                const templateKey = button.dataset.template;
                const template = containerTemplates[templateKey];
                
                if (template && template.components) {
                    // Append new components at the end
                    const addedComponents = this.model.append(template.components);
                    
                    // Execute the original template callback if defined
                    // We need to get the original template to access its callback
                    const originalTemplate = window.gjsSharedResources.templates[templateKey];
                    if (originalTemplate && originalTemplate.onInsert && addedComponents && addedComponents.length > 0) {
                        const sectionComponent = addedComponents[0];
                        originalTemplate.onInsert(this.em, sectionComponent);
                    }
                    
                    // Quick-add will be automatically repositioned by ensureQuickAddAtEnd listener
                }
            }
        }
    });
}