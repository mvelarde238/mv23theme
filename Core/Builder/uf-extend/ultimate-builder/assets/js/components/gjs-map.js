window.gjsMap = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'map2';

    domc.addType(compClass, {
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: 'Map',
                tagName: 'div',
                dropable: false,
                resizable: {
                    ratioDefault: true,
                    currentUnit: 1,
                    keepAutoWidth: true,
                    cl: false,
                    cr: false,
                    tl: false,
                    tc: false,
                    tr: false,
                    bl: false,
                    bc: true,
                    br: false,
                    maxDim: null,
                    minDim: 50,
                    onEnd: (ev, opts) => {
                        const selected = editor.getSelected();
                        if (selected) {
                            selected.view.render();
                        }
                    }
                },
                attributes: {
                    class: compClass,
                },
                style:{ width: '100%' },
                styles: `
                    .map2 {
                        min-height: 200px;
                    }
                    .map-wrapper {
                        width: 100%;
                        height: 100%;
                        pointer-events: none;
                    }
                    .map-wrapper.loading {
                        background: #f0f0f0 url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' style='margin: auto; background: none; display: block;' width='100px' height='100px' viewBox='0 0 100 100' preserveAspectRatio='xMidYMid'%3E%3Ccircle cx='50' cy='50' r='32' stroke-width='8' stroke='%23cccccc' stroke-dasharray='50.26548245743669 50.26548245743669' fill='none' stroke-linecap='round'%3E%3CanimateTransform attributeName='transform' type='rotate' repeatCount='indefinite' dur='1s' keyTimes='0;1' values='0 50 50;360 50 50'%3E%3C/animateTransform%3E%3C/circle%3E%3C/svg%3E") center center no-repeat;
                        background-size: 40px 40px;
                    }
                    .map-wrapper.loading>div{ opacity: 0; transition: opacity 0.5s; }
                `,
            },
        },
        view: {
            onRender({el, model}) {
                let default_position = { lat: 40.416775, lng: -3.703790, zoom: 8 },
                    center = [default_position.lat, default_position.lng],
                    zoom = default_position.zoom,
                    icon_url = null,
                    info_window_content = null,
                    icon_size = [38, 38],
                    provider = 'leaflet';

                // check datastore for position
                const editorConfig = editor.getConfig(),
                	temporalCompStore = editorConfig.temporalCompStore || {},
					__tempID = model.get('__tempID');

				if (__tempID && temporalCompStore[__tempID]) {
					const datastore = temporalCompStore[__tempID].datastore;
					if (datastore) {
						const location = datastore.get('location');
						if (location) {
                            if (location.latLng) center = [location.latLng.lat, location.latLng.lng];
                            zoom = location.zoom;

                            provider = location.provider || 'leaflet';
                        }

                        const icon_data = datastore.get('icon_data') || null;
                        if (icon_data && icon_data.icon) {
                            icon_prepared = icon_data.icon_prepared || null;
                            if (Array.isArray(icon_prepared) && icon_prepared.length > 0) {
                                icon_url = icon_prepared[0].url || '';
                                icon_size = [
                                    parseInt(icon_data.width) || 38,
                                    parseInt(icon_data.height) || 38
                                ];
                            }
                        }

                        info_window_content = datastore.get('info_window_content') || null;
					}
				}

                // create a map wrapper inside this element
                const mapContainer = document.createElement('div');
                mapContainer.classList.add('map-wrapper');
                mapContainer.classList.add('loading');
                // if (map_height) mapContainer.style.height = map_height;
                el.appendChild(mapContainer);
            
                // Initialize the map
                if (provider === 'leaflet') {
                    const map = L.map(mapContainer).setView(center, zoom);

                    // Add a tile layer to the map
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                    }).addTo(map);

                    // Add a marker to the map
                    const marker = L.marker(center).addTo(map);

                    if (icon_url) {
                        marker.setIcon(L.icon({
                            iconUrl: icon_url,
                            iconSize: icon_size
                        }));
                    } 

                    if(info_window_content){
						marker.bindPopup(info_window_content).openPopup();
					}

                    // Invalidate size to ensure proper rendering
                    setTimeout(function() {
				    	mapContainer.classList.remove('loading');
				    	map.invalidateSize();
				    }, 1000);
                }

                if (provider === 'google') {
                    const map = new google.maps.Map(mapContainer, {
                        center: { lat: center[0], lng: center[1] },
                        zoom: zoom,
                    });

                    // Add a marker to the map
                    let marker_options = {
                        position: { lat: center[0], lng: center[1] },
                        map: map,
                    };
                    if (icon_url) marker_options.icon = icon_url;
                    new google.maps.Marker(marker_options);

                    if(info_window_content){
                        var infoWindow = new google.maps.InfoWindow({map: map});
                        infoWindow.setPosition(marker_options.position);
                        infoWindow.setContent( info_window_content );
                    }

                    // Invalidate size to ensure proper rendering
                    setTimeout(function(){
                        mapContainer.classList.remove('loading');
					    google.maps.event.trigger( map, 'resize' );
				    }, 1000 );
                }
            },
        },
    });
}