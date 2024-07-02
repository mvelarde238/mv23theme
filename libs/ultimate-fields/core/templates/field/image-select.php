<% _.each( options, function( option, key ){ %>
<label>
	<input type="radio" value="<%= key %>" name="<%= inputId %>" />
	<span><img src="<%= option.image %>" alt="<%= option.label %>" label="<%= option.title %>" /></span>
	<% if( show_label ) { %>
		<span style="text-align:center; font-weight:bold; font-size:12px; display:block;"><%= option.label %></span>
	<% } %>
</label>
<% }) %>