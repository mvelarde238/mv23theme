/**
 * Shared Templates and Resources for GrapesJS Components
 * This module provides common templates, CSS, and helper functions
 * to be used by section, container, and other components.
 */
window.gjsSharedResources = {
    /**
     * Base section layout templates
     * These can be used directly by section component or wrapped by container
     */
    templates: {
        '2-cols': {
            label: '2 Columns',
            visual: '<div class="template-visual-container"><div class="template-visual-col"></div><div class="template-visual-col"></div></div>',
            components: [{
                type: 'row-component',
                components: [
                    { type: 'column' },
                    { type: 'column' }
                ]
            }]
        },
        '3-cols': {
            label: '3 Columns',
            visual: '<div class="template-visual-container"><div class="template-visual-col"></div><div class="template-visual-col"></div><div class="template-visual-col"></div></div>',
            components: [{
                type: 'row-component',
                components: [
                    { type: 'column' },
                    { type: 'column' },
                    { type: 'column' }
                ]
            }]
        },
        '4-cols': {
            label: '4 Columns',
            visual: '<div class="template-visual-container"><div class="template-visual-col"></div><div class="template-visual-col"></div><div class="template-visual-col"></div><div class="template-visual-col"></div></div>',
            components: [{
                type: 'row-component',
                components: [
                    { type: 'column' },
                    { type: 'column' },
                    { type: 'column' },
                    { type: 'column' }
                ]
            }]
        },
        '1-3_2-3': {
            label: '1/3 - 2/3',
            visual: '<div class="template-visual-container"><div class="template-visual-col"></div><div class="template-visual-col template-visual-col-2"></div></div>',
            components: [{
                type: 'row-component',
                style : { gap: '1%', ['flex-wrap']: 'nowrap' },
                components: [
                    { type: 'column', style: { width: '33%' } },
                    { type: 'column', style: { width: '66%' } }
                ]
            }],
            onInsert: function(editor, sectionComponent) {
                window.gjsSharedResources.applyResponsiveToRow(editor, sectionComponent);
            }
        },
        '2-3_1-3': {
            label: '2/3 - 1/3',
            visual: '<div class="template-visual-container"><div class="template-visual-col template-visual-col-2"></div><div class="template-visual-col"></div></div>',
            components: [{
                type: 'row-component',
                style : { gap: '1%', ['flex-wrap']: 'nowrap' },
                components: [
                    { type: 'column', style: { width: '66%' } },
                    { type: 'column', style: { width: '33%' } }
                ]
            }],
            onInsert: function(editor, sectionComponent) {
                window.gjsSharedResources.applyResponsiveToRow(editor, sectionComponent);
            }
        },
        'empty': {
            label: 'Empty Section',
            visual: '<div class="template-visual-empty">Empty</div>',
            components: []
        }
    },

    /**
     * Create a template button content (inner HTML)
     * @param {string} templateKey - The key of the template
     * @param {Object} template - The template object with label and visual
     * @returns {string} HTML string for the button content
     */
    createTemplateButton: function(templateKey, template) {
        return `${template.visual}<span class="template-btn-label">${template.label}</span>`;
    },

    /**
     * Create a quick-add button content (for container)
     * @param {string} templateKey - The key of the template
     * @param {Object} template - The template object with label and visual
     * @returns {string} HTML string for the button content
     */
    createQuickAddButton: function(templateKey, template) {
        return `
            <div class="quick-add-btn-visual">
                ${template.visual}
            </div>
            <span class="quick-add-btn-label">${template.label}</span>
        `;
    },

    /**
     * Apply responsive styles to row and columns for mobile
     * Helper function to avoid code duplication in template callbacks
     * @param {Object} editor - GrapesJS editor instance
     * @param {Object} sectionComponent - Section component model
     * @param {string} widthMedia - Media query breakpoint (default: '768px')
     */
    applyResponsiveToRow: function(editor, sectionComponent, widthMedia) {
        widthMedia = widthMedia || '768px';
        const rowComponent = sectionComponent.components().at(0);
        
        if (rowComponent) {
            const css = editor.Css;
            const rowId = rowComponent.getId();
            
            // Apply flex-wrap: wrap to row in mobile
            css.setRule(`#${rowId}`, { 'flex-wrap': 'wrap' }, {
                atRuleType: 'media',
                atRuleParams: `(max-width: ${widthMedia})`
            });
            
            // Apply width: 100% to all columns in mobile
            rowComponent.components().forEach(column => {
                const columnId = column.getId();
                css.setRule(`#${columnId}`, { width: '100%' }, {
                    atRuleType: 'media',
                    atRuleParams: `(max-width: ${widthMedia})`
                });
            });
        }
    }
};
