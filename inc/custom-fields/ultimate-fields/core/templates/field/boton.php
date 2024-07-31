<div class="uf-boton">
	<div class="uf-boton-top">
		<div class="uf-boton-chooser" style="display: flex">
            <div class="link-helper" style="margin-right: 5px"></div>
            <button class="button-secondary link-selector"><span class="dashicons dashicons-edit"></span></button>
			<input type="hidden" class="uf-boton-url-input" />
            <input type="hidden" class="uf-boton-text-input" />
            <textarea name="" id="fake-editor" cols="1" style="display:none"></textarea>
		</div>
	</div>

	<% if( target_control ) { %>
	<label class="uf-boton-new-tab" style="display:none">
		<input type="checkbox" class="uf-boton-new-tab-input" />
	</label>
	<% } %>
</div>
