<div class="uf-group-header uf-columns-layout-group-header">
	<div class="uf-group-number uf-columns-layout-group-number">
		<% if( icon ) { %>
		<strong class="dashicons <%= icon %>"></strong>
		<% } else { %>
		<strong class="uf-group-number-inside"><%= number %></strong>
		<% } %>
		<span class="dashicons dashicons-move"></span>
	</div>

	<div class="uf-group-controls uf-columns-layout-group-controls">
		<a href="#" class="uf-group-control uf-group-control-open-menu" title="<?php esc_attr_e( 'Menu', 'ultimate-fields' ); ?>">
			<span class="dashicons dashicons-menu"></span>
		</a>
		<div class="context-menu" style="display: none">
			<a href="#" class="uf-group-control uf-group-control-copy" title="<?php esc_attr_e( 'Copy', 'ultimate-fields' ); ?>">
				Copiar
			</a>
			<a href="#" class="uf-group-control uf-group-control-paste" title="<?php esc_attr_e( 'Paste', 'ultimate-fields' ); ?>">
				Pegar
			</a>
			<a href="#" class="uf-group-control uf-group-control-duplicate" title="<?php esc_attr_e( 'Duplicate', 'ultimate-fields' ); ?>">
				Duplicar
				<!-- <span class="dashicons dashicons-admin-page"></span> -->
			</a>
			<a href="#" class="uf-group-control uf-group-control-save" title="<?php esc_attr_e( 'Save', 'ultimate-fields' ); ?>">
				Guardar
			</a>
			<a href="#" class="uf-group-control uf-group-control-remove" title="<?php esc_attr_e( 'Remove', 'ultimate-fields' ); ?>">
				Eliminar
				<!-- <span class="dashicons dashicons-trash"></span> -->
			</a>
		</div>
		<% if( 'inline' != edit_mode ) { %>
		<a href="#" class="uf-group-control uf-group-control-popup" title="<?php esc_attr_e( 'Open overlay', 'ultimate-fields' ); ?>">
			<span class="dashicons <%= 'popup' == edit_mode ? 'dashicons-edit' : 'dashicons-editor-expand' %>"></span>
		</a>
		<% } %>
		<% if( 'popup' != edit_mode ){ %>
		<a href="#" class="uf-group-control uf-group-control-close" title="<?php esc_attr_e( 'Collapse', 'ultimate-fields' ); ?>">
			<span class="dashicons dashicons-arrow-up"></span>
		</a>
		<a href="#" class="uf-group-control uf-group-control-open" title="<?php esc_attr_e( 'Expand', 'ultimate-fields' ); ?>">
			<span class="dashicons dashicons-arrow-down"></span>
		</a>
		<% } %>
	</div>

	<h3 class="uf-group-title"><%= title %><em class="uf-group-title-preview"></em></h3>
</div>