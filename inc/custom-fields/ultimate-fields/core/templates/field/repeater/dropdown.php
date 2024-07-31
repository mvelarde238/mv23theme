<div class="uf-repeater-dropdown">
	<div style="zoom:.9;">
		<select>
			<% _.each( groups, function( group ) { %>
			<option value="<%= group.id %>"><%= group.title %></option>
			<% }) %>
		</select>
	
		<button type="button" class="button-primary uf-button uf-repeater-add-button">
			<span class="dashicons dashicons-plus uf-button-icon"></span>
			<%= text %>
		</button>
	
		<button type="button" class="button uf-button uf-repeater-library-button">
			<span class="dashicons dashicons-open-folder uf-button-icon" style="margin-right: 0 !important;"></span>
		</button>
	</div>
</div>
