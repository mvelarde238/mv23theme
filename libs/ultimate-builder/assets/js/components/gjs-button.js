window.gjsButton = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'button';

    // Labels for the ui, using the editor's translator for internationalization
    const __ = editor.createTranslator(editor);
    const buttonName = __('Button');

    domc.addType(compClass, {
        isComponent: el => el.classList && el.classList.contains(compClass),
        model: {
            defaults: {
                name: buttonName,
                tagName: 'a',
                droppable: false,
                classes: [compClass]
            },
        },
        view: {
            onRender({ el, model }) {
                // get datastore values and update the icon and styles accordingly
                const datastore = editor.getComponentDatastore(model);
                if (datastore) {
                    const { icon, icon_position, fullwidth, text, button_style } = datastore.toJSON();

                    // transform button_style ('btn btn--main-color',etc.) to array
                    const additionalClasses = button_style ? button_style.split(' ') : [];
                    if (icon && icon_position) additionalClasses.push("btn--icon-" + icon_position);
                    if (fullwidth) additionalClasses.push("btn-block");
                    el.classList.add(...additionalClasses);

                    // If icon or text is present, add the icon element and text content
                    if (icon || text) {
                        if (text) {
                            const filtered_content = __handlebars(text);
                            el.textContent = filtered_content;
                        }
                        if (icon && icon_position === "left") {
                            const iconEl = document.createElement('i');
                            iconEl.className = (icon.startsWith("fa") ? "fa " : "bi ") + icon;
                            el.prepend(iconEl);
                        }
                        if (icon && icon_position === "right") {
                            const iconEl = document.createElement('i');
                            iconEl.className = (icon.startsWith("fa") ? "fa " : "bi ") + icon;
                            el.appendChild(iconEl);
                        }
                    } else {
                        el.textContent = buttonName;
                        el.style.opacity = '0.5';
                    }
                }
            },
            events: {
                dblclick: 'onActive'
            },
            onActive() {
                editor.runCommand('open-datastore');
            }
        }
    });
}