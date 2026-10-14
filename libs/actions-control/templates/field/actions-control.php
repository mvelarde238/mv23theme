<div class="actions-control">
    <div class="actions-control__actions">
        <% _.each( actions, function( action ) { 
            $button_class = action.class ? action.class : 'uf-button button-secondary';
            %>
            <button class="action-trigger <%= $button_class %>" data-action="<%= action.command %>" data-args='<%= JSON.stringify(action.args) %>'>
                <% if( action.image ) { %>
                    <img src="<%= action.image %>" alt="<%= action.label %>">
                <% } %>
                <% if( action.label && !action.hide_label ) { %>
                    <span class="action-trigger__label"><%= action.label %></span>
                <% } %>
            </button>
        <% }); %>
    </div>
</div>