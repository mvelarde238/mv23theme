<div class="uf-map">
    <ul class="uf-map-tab uf-radio uf-radio-orientation-horizontal">
        <% var uniqid = _.uniqueId('uf-map-radio-'); %>
        <li>
            <label>
                <input type="radio" value="leaflet" class="uf-map-radio" name="<%= uniqid %>" <%= provider === 'leaflet' ? 'checked' : '' %>>Leaflet
            </label>
        </li>
        <li>
            <label>
                <input type="radio" value="google" class="uf-map-radio" name="<%= uniqid %>" <%= provider === 'google' ? 'checked' : '' %>>Google Maps
            </label>
        </li>
    </ul>
    <br>
    <div class="uf-map-provider uf-map-google" style="display: <%= provider === 'google' ? 'block' : 'none' %>;">
        <div class="uf-map-ui">
            <div></div>
        </div>
        <div class="uf-map-controls">
            <div class="uf-map-field">
                <input type="text" placeholder="Enter address to search..." class="uf-map-input" />
            </div>
        </div>
    </div>
    <div class="uf-map-provider uf-map-leaflet" style="display: <%= provider === 'leaflet' ? 'block' : 'none' %>;">
        <div class="uf-map-ui">
            <div></div>
        </div>
    </div>
</div>