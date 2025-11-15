window.handleCommonSettings = function (editor, options) {
    editor.on('component:render', (obj) => {
        // obj -> component, view, el
        const component = obj.component;

        const editorConfig = editor.getConfig(),
        	temporalCompStore = editorConfig.temporalCompStore || {},
			__tempID = component.get('__tempID');

		if (__tempID && temporalCompStore[__tempID]) {
			const datastore = temporalCompStore[__tempID].datastore;
			if (datastore) {
                const settings = datastore.get('settings') || {};

                if( settings.id ) {
                    component.setId( settings.id );
                }

                if (settings.classes) {
                    const classes_list = settings.classes.split(' ').filter(c => c.trim() !== '');
                    if(classes_list.length){
                        classes_list.forEach(cls => {
                            obj.el.classList.add(cls);
                        });
                    }
                }

                if (settings.layout && settings.layout.use) {
                    const layout = settings.layout.key;
                    if (layout === 'layout2' || layout === 'layout3') {
                        obj.el.classList.add('full-width');
                    } else {
                        obj.el.classList.remove('full-width');
                    }
                    obj.el.classList.add(layout);
                }

                if( settings.helpers && settings.helpers.use ) {
                    const helpers_list = settings.helpers.list || [];
                    if(helpers_list.length){
                        helpers_list.forEach(helper_class => {
                            obj.el.classList.add(helper_class);
                        });
                    }
                }

                if( settings.video_background && settings.video_background.use ) {
                    // TO DO: we dont have the video_prepared on init? (bug occurs just after migration)
                    let video_el = null;
                    const video_source = settings.video_background.video_source ?? 'selfhosted';

                    if( video_source === 'selfhosted' && settings.video_background.video_prepared ) {
                        const video_url = settings.video_background.video_prepared[0].url;
                        if( video_url ) {
                            video_el = document.createElement('video');
                            video_el.className = "video-background";
                            video_el.src = video_url;
                            video_el.autoplay = true;
                            video_el.muted = true;
                            video_el.loop = true;
                            video_el.playsInline = true;
                            obj.el.prepend( video_el );
                        }
                    }

                    if( video_source === 'external' && settings.video_background.external_url ) {
                        const external_url = settings.video_background.external_url;
                        video_el = document.createElement('iframe');
                        video_el.className = "video-background";
                        video_el.src = external_url;
                        video_el.frameBorder = "0";
                        video_el.allow = "autoplay; fullscreen";
                        video_el.allowFullscreen = true;
                        obj.el.prepend( video_el );
                    }

                    if( video_el ) {
                        const video_settings = settings.video_background.video_settings || {};
						// if video_settings.bgc is set, apply it to iframe or video background
						if (video_settings.bgc) {
							video_el.style.backgroundColor = video_settings.bgc;
						}
						// if video_settings.opacity is set, apply it to iframe or video opacity
						// opacity is a number from 0 to 100
						if (video_settings.opacity) {
							video_el.style.opacity = video_settings.opacity / 100;
						}
                    }
                }

                if( settings.hide_on ) {
                    const responsive = settings.hide_on;
                    if( responsive.desktop ) obj.el.classList.add('hide-on-large-only');
                    if( responsive.tablet ) obj.el.classList.add('hide-on-med-only');
                    if( responsive.mobile ) obj.el.classList.add('hide-on-small-only');
                }
            }
        }
    });

    // editor.on('openDatastore', (builder_comp_model, component) => {
    //     // save the component's id to the datastore to show it in the common settings popup
    //     const componentId = component.getId();
    //     builder_comp_model.datastore.attributes.settings.id = componentId;
    // });

    // editor.on('popupDatastoreChanged', (builder_comp_model, component) => {
    //     // get the changed fields in the datastore and update the component accordingly
    //     const changed = builder_comp_model.datastore.changed;
    //     if( changed['settings'] ) {
    //         const settings = builder_comp_model.datastore.attributes.settings || {};
    //         if( settings.id ) component.setId( settings.id );
    //     }
    // });

    // editor.on('datastoreRestored', (builder_comp_model, component) => {
    //     // restore the component using the restored datastore values
    //     const settings = builder_comp_model.datastore.attributes.settings || {};
    //     if( settings.id ) component.setId( settings.id );
	// });
}