window.gjsHeading = function (editor) {
    const domc = editor.DomComponents;
    const compClass = 'heading';

    domc.addType(compClass, {
        isComponent: el => el.classList && el.classList.contains(compClass) && el.classList.contains('component'),
        model: {
            defaults: {
                name: 'Heading',
                tagName: 'div',
                droppable: false,
                classes: ['component', compClass],
            },
        },
        view: {
            onRender({ el, model }) {
                const datastore = editor.getComponentDatastore(model);
                if (!datastore) return;

                const {
                    heading,
                    tagline,
                    text_align,
                    preset,
                    accent_color,
                    highlighted_element,
                    tagline_position,
                    add_tagline,
                } = datastore.toJSON();

                // --- classes ---
                const alignClasses = ['left-align', 'center-align', 'right-align'];
                const presetClasses = Array.from(el.classList).filter(c => c.startsWith('heading--'));
                el.classList.remove(...alignClasses, ...presetClasses);

                const align = text_align || 'center';
                el.classList.add(`${align}-align`);

                const activePreset = preset || 'default';
                el.classList.add(`heading--${activePreset}`);

                // --- accent color CSS variable ---
                el.style.removeProperty('--accent-color');
                if (accent_color) {
                    const { color_variable, color } = accent_color;
                    if (color_variable === 'Use ColorPicker') {
                        if (color) el.style.setProperty('--accent-color', color);
                    } else if (color_variable) {
                        const val = color_variable.startsWith('--')
                            ? `var(${color_variable})`
                            : color_variable;
                        el.style.setProperty('--accent-color', val);
                    }
                }

                // --- inner HTML ---
                const headingContent = heading?.content || '';
                const headingTag     = heading?.html_tag || 'h2';
                const taglineContent = tagline?.content  || '';
                const taglineTag     = tagline?.html_tag  || 'p';

                const highlighted    = highlighted_element || 'heading';
                const wrappedPresets = ['style7', 'style8'];
                const wrap           = wrappedPresets.includes(activePreset);

                const buildTag = (tag, content, classes) => {
                    content = __handlebars(content);
                    const inner = wrap ? `<span>${content}</span>` : content;
                    return `<${tag} class="${classes.join(' ')}">${inner}</${tag}>`;
                };

                const hClasses = ['heading__text'];
                if (highlighted === 'heading') hClasses.push('highlighted');

                const tClasses = ['heading__tagline'];
                if (highlighted === 'tagline') tClasses.push('highlighted');

                const hHtml = headingContent ? buildTag(headingTag, headingContent, hClasses) : '';
                const tHtml = (add_tagline && taglineContent) ? buildTag(taglineTag, taglineContent, tClasses) : '';

                const taglinePos = tagline_position || 'after';
                el.innerHTML = taglinePos === 'before'
                    ? tHtml + hHtml
                    : hHtml + tHtml;
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
