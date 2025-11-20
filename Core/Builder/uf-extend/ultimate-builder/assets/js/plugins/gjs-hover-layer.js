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
                const isTextSelectedSpot = spot.type === spotTypeName;

                if(isTextSelectedSpot) { 
                    const style = Object.entries(spot.getStyle())
                        .map(([k, v]) => \`\${k}:\${v}\`)
                        .join(';');
            %>
                <div class="spot" style="<%= style %>">
                    <button type="button" class="hover-layer-btn">
                        <i class="bi bi-database" aria-hidden="true"></i> 
                        <span>Edit Component</span>
                    </button>
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
                // Add a new canvas spot for the last hovered component
                componentHovered: (component) => {
                    // If component isn't connected to a datastore, ignore it
                    if (!component.get('__tempID')) return;

                    // Remove all spots related to our custom type
                    editor.Canvas.removeSpots({ type: spotTypeName });
                    // Don't add spot if component is already selected
                    if (component != editor.getSelected()) {
                        editor.Canvas.addSpot({ type: spotTypeName, component });
                        this.model.set({ lastHoveredComponent: component });
                    }
                },
                // Remove all spots related to our custom type when component is toggled (selected/deselected)
                componentToggled: (component) => {
                    editor.Canvas.removeSpots({ type: spotTypeName });
                }
            };

            // Bind events
            editor.on('canvas:spot', this.eventHandlers.spotUpdate);
            editor.on('component:hovered', this.eventHandlers.componentHovered);
            editor.on('component:toggled', this.eventHandlers.componentToggled);

            editor.onReady(() => {
                // Once the editor is ready, append our custom elements to GrapesJS spots container
                editor.Canvas.getSpotsEl().appendChild(this.el);
            });
        },
        render: function () {
            const { spots, spotTypeName } = this.model.toJSON();
            this.$el.html(this.template({ spots, spotTypeName }));
            return this;
        },
        events: {
            "click .hover-layer-btn": 'handleClick'
        },
        handleClick: function (event) {
            event.preventDefault();
            const lastHoveredComponent = this.model.get('lastHoveredComponent');

            if (!lastHoveredComponent) {
                console.warn('No component to select');
                return;
            }

            try {
                editor.select(lastHoveredComponent);
                editor.runCommand('open-datastore');
            } catch (error) {
                console.error('Error selecting component or running command:', error);
            }
        },
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

    // Initialize model and view
    this.model = new HL_Model({});
    this.view = new HL_View({
        model: this.model
    });
}