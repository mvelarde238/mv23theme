window.handleCommonSettings = function (editor, options) {

    editor.handleCommonSettings = function (component) {
        const datastore = editor.getComponentDatastore(component);

        if (datastore) {
            const settings = datastore.get('settings') || {};

            if (settings.id) {
                component.setId(settings.id);
            }

            if (settings.classes) {
                const classes_list = settings.classes.split(' ').filter(c => c.trim() !== '');
                if (classes_list.length) {
                    classes_list.forEach(cls => {
                        component.getEl().classList.add(cls);
                    });
                }
            }

            let has_full_width_layout = false;
            if (settings.layout) {
                const layout = (settings.layout.use) ? settings.layout.key : null;
                if (layout === 'layout2') {
                    // full width stretched — needs container
                    has_full_width_layout = true;    
                    component.getEl().classList.add('full-width');
                    const firstChild = component.components().at(0);
                    if (!firstChild || firstChild.get('type') !== 'container') {
                        const children = [...component.components().models];
                        const [container] = component.append({
                            type:'container', 
                            draggable: false, selectable: false, removable: false, 
                            copyable: false, badgable: false
                        });
                        children.forEach(child => container.append(child));
                    }
                    component.set('droppable', false);
                } else {
                    // layout1 (boxed) or layout3 (full width) — no container
                    if (layout === 'layout3') {
                        has_full_width_layout = true;
                        component.getEl().classList.add('full-width');
                    } else if (layout === 'layout1') {
                        has_full_width_layout = false;
                        component.getEl().classList.remove('full-width');
                    }
                    // unwrap children from container and remove it if present
                    const firstChild = component.components().at(0);
                    if (firstChild && firstChild.get('type') === 'container') {
                        const inner = [...firstChild.components().models];
                        inner.forEach(child => component.append(child));
                        firstChild.remove();
                    }
                    component.set('droppable', true);
                }
                if (layout) {
                    component.getEl().classList.add(layout);
                }
            }

            const utility_classes = [
                'overflow-scroll','overflow-hidden','hide-br','hide-br-tablet','hide-br-mobile',
                'extend-bg-to-left','extend-bg-to-right','full-height','full-width','sticky'
            ];
            if (settings.utility_classes && Array.isArray(settings.utility_classes) && settings.utility_classes.length) {
                const selected_utility_classes = settings.utility_classes || [];
                if (selected_utility_classes.length) {
                    selected_utility_classes.forEach(utility_class => {
                        component.getEl().classList.add(utility_class);
                    });
                } else {
                    utility_classes.forEach(utility_class => {
                        // dont remove full-width if has full width layout
                        if (!(has_full_width_layout && utility_class === 'full-width')) {
                            component.getEl().classList.remove(utility_class);
                        }
                    });
                }
            } else {
                utility_classes.forEach(utility_class => {
                    // dont remove full-width if has full width layout
                    if (!(has_full_width_layout && utility_class === 'full-width')) {
                        component.getEl().classList.remove(utility_class);
                    }
                });
            }

            if (settings.color_scheme && settings.color_scheme.use) {
                const scheme = settings.color_scheme.key;
                component.getEl().classList.remove('dark-mode', 'light-mode');
                component.getEl().classList.add(scheme);
            } else {
                component.getEl().classList.remove('dark-mode', 'light-mode');
            }

            if (settings.video_background && settings.video_background.use) {
                // TO DO: we dont have the video_prepared on init? (bug occurs just after migration)
                let video_el = null;
                const video_source = settings.video_background.video_source ?? 'selfhosted';

                if (video_source === 'selfhosted' && settings.video_background.video_prepared) {
                    const video_url = settings.video_background.video_prepared[0].url;
                    if (video_url) {
                        video_el = document.createElement('video');
                        video_el.className = "video-background";
                        video_el.src = video_url;
                        video_el.autoplay = true;
                        video_el.muted = true;
                        video_el.loop = true;
                        video_el.playsInline = true;
                        component.getEl().prepend(video_el);
                    }
                }

                if (video_source === 'external' && settings.video_background.external_url) {
                    const external_url = settings.video_background.external_url;
                    video_el = document.createElement('iframe');
                    video_el.className = "video-background";
                    video_el.src = external_url;
                    video_el.frameBorder = "0";
                    video_el.allow = "autoplay; fullscreen";
                    video_el.allowFullscreen = true;
                    component.getEl().prepend(video_el);
                }

                if (video_el) {
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

            if (settings.hide_on) {
                const responsive = settings.hide_on;
                if (responsive.desktop) component.getEl().classList.add('hide-on-large-only');
                if (responsive.tablet) component.getEl().classList.add('hide-on-med-only');
                if (responsive.mobile) component.getEl().classList.add('hide-on-small-only');
            }
        }
    }

    editor.on('component:render', (obj) => {
        // obj -> component, view, el
        const component = obj.component;
        editor.handleCommonSettings(component);
    });
};