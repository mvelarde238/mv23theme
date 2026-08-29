window.gjsHoverLayer = function (editor) {
    if (!editor) {
        throw new Error('Editor instance is required');
    }

    const name = 'hover-layer-spot';

    const HL_Model = Backbone.Model.extend({
        defaults: {
            spots: [],
            spotTypeName: name,
            lastHoveredComponent: null,
            editor: editor
        }
    });

    const HL_View = Backbone.View.extend({
        el: `.${name}-container`,
        template: _.template(`
            <% spots.forEach((spot, index) => { 
                const isHoverLayerSpot = spot.type === spotTypeName;

                if(isHoverLayerSpot) { 
                    const style = Object.entries(spot.getStyle())
                        .map(([k, v]) => \`\${k}:\${v}\`)
                        .join(';');
                %>
                    <div class="spot" style="<%= style %>">
                        <a href="<%= edit_header_url %>" class="button button-primary hover-layer-btn edit-header"><%= editHeaderText %></a>
                        <% if (posttype != 'footer') { %>
                            <a href="<%= edit_footer_url %>" class="button button-primary hover-layer-btn edit-footer"><%= editFooterText %></a>
                        <% } %>
                    </div>
                <% }
            }) %>
        `),
        initialize: function () {
            this.render();
            const { editor, spotTypeName } = this.model.toJSON();

            // Store event handlers for later removal if needed
            this.eventHandlers = {
                // Catch-all event for any spot updates
                spotUpdate: () => {
                    this.model.set({ spots: editor.Canvas.getSpots() });
                    this.render();
                },
                // Add a new canvas spot only for the wrapper component
                componentHovered: (component) => {
                    editor.Canvas.removeSpots({ type: spotTypeName });

                    if (!component.is('wrapper')) return;

                    editor.Canvas.addSpot({ type: spotTypeName, component });
                    this.model.set({ lastHoveredComponent: component });
                },
                // Remove the spot when the wrapper is selected
                componentToggled: () => {
                    editor.Canvas.removeSpots({ type: spotTypeName });
                }
            };

            // Bind events
            editor.on('canvas:spot', this.eventHandlers.spotUpdate);
            editor.on('component:hovered', this.eventHandlers.componentHovered);
            // editor.on('component:toggled', this.eventHandlers.componentToggled);

            editor.onReady(() => {
                // Once the editor is ready, append our custom elements to GrapesJS spots container
                editor.Canvas.getSpotsEl().appendChild(this.el);
            });
        },
        render: function () {
            const { spots, spotTypeName } = this.model.toJSON();
            const { edit_header_url, edit_footer_url, posttype } = BUILDER_GLOBALS;

            const __ = editor.createTranslator(editor);
            const editHeaderText = __('Edit Header');
            const editFooterText = __('Edit Footer');

            this.$el.html(this.template({ 
                spots, 
                spotTypeName, 
                edit_header_url, 
                edit_footer_url, 
                posttype, 
                editHeaderText, 
                editFooterText 
            }));

            return this;
        }
    });

    const createContainer = () => {
        const appElement = document.querySelector('#app');
        if (!appElement) {
            console.error('App element not found');
            return null;
        }
        
        // Check if container already exists
        let container = document.querySelector(`.${name}-container`);
        if (!container) {
            container = document.createElement('div');
            container.className = `${name}-container`;
            appElement.insertAdjacentElement('afterend', container);
        }
        return container;
    };

    const container = createContainer();
    if (!container) return null;

    const model = new HL_Model({});
    new HL_View({ model: model });
};