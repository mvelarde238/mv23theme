<div class="uf-repeater-prototypes-column">
	<div class="uf-columns-layout-element uf-columns-layout-element-prototype" data-type="<%= type %>">
		<div class="uf-group uf-group-prototype">
			<div class="uf-group-header">
				<div class="uf-group-number">
					<% if( icon ) { %>
						<strong class="dashicons <%= icon %>"></strong>
					<% } else { %>
						<span class="dashicons dashicons-plus"></span>
					<% } %>
				</div>

				<h3 class="uf-group-title"><%= title %></h3>
			</div>
		</div>
	</div>
</div>
