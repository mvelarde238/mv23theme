window.gjsFlipbox = function (editor) {
    const domc = editor.DomComponents;
    const cmpClass = 'flipbox';

    domc.addType('flipbox-inner', {
        model: {
            defaults: {
                tagName: 'div',
                name: 'Flip Box Inner',
                classes: ['flipbox-inner'],
                draggable: false,
                droppable: false,
                delegate: {
                    // Delegate these commands to the parent
                    select: (cmp) => cmp.findFirstType('flipbox-front')
                },
                selectable: false,
                hoverable: false,
                stylable: false
            }
        },
    });

    domc.addType('flipbox-front', {
        isComponent: el => el.classList && el.classList.contains('flipbox-front'),
        model: {
            defaults: {
                name: 'Flip Box Front',
                tagName: 'div',
                draggable: false,
                classes: ['flipbox-front'],
                delegate: {
                    // Delegate these commands to the parent
                    remove: (cmp) => cmp.closestType('flipbox'),
                    copy: (cmp) => cmp.closestType('flipbox'),
                },
                components: [
                    { 
                        type: 'text-editor',
                        style: { 'text-align': 'center' },
                    }
                ],
            },
        },
    });

    domc.addType('flipbox-back', {
        isComponent: el => el.classList && el.classList.contains('flipbox-back'),
        extend: 'flipbox-front',
        model: {
            defaults: {
                name: 'Flip Box Back',
                classes: ['flipbox-back'],
            },
        },
    });
    
    domc.addType(cmpClass, {
        isComponent: el => el.classList && el.classList.contains(cmpClass),
        model: {
            defaults: {
                name: 'Flip Box',
                tagName: 'div',
                droppable: false,
                classes: [cmpClass, 'component'],
                styles: `
                    .flipbox-front, .flipbox-back {
                        display: flex;
                        justify-content: safe center;
                        align-items: safe center;
                    }
                `,
                components: [
                    { 
                        type: 'flipbox-inner', 
                        components: [
                            { type: 'flipbox-front' },
                            { type: 'flipbox-back' }
                        ] 
                    }
                ],
            },
            init(){},
        },
        view: {
            onRender({ el, model }) {
                const datastore = editor.getComponentDatastore(model);
                const preview_flip_effects = model.get('__temp_preview_flip_effects') || false;
                        
                if (datastore){
                    const {aspect_ratio, flip_effect, custom_aspect_ratio} = datastore.toJSON();

                    if (aspect_ratio != 'custom'){
                        el.style.setProperty('--flip-box-aspect-ratio', aspect_ratio);
                    } else {
                        el.style.setProperty('--flip-box-aspect-ratio', custom_aspect_ratio);
                    }

                    if ( preview_flip_effects && flip_effect){
                        el.classList.add(flip_effect);
                    }
                }

                // Append a button to toggle view
                const button = document.createElement("button");
                button.appendChild(document.createTextNode("Toggle FRONT/BACK"));
                button.classList = 'cmp-action toggle-flipbox';
                el.append(button);

                // Append a button to preview flip effect
                const previewButton = document.createElement("button");
                previewButton.appendChild(document.createTextNode("Preview Flip Effect"));
                previewButton.classList = 'cmp-action preview-flipbox';
                el.append(previewButton);

                // Show/hide toggle button based on preview state
                if (preview_flip_effects){
                    // add active class for the selected flip effect
                    this.el.querySelector('.preview-flipbox').classList.add('active');

                    // hide toggle button in preview mode
                    this.el.querySelector('.toggle-flipbox').style.display = 'none';

                    // show both front and back in preview mode
                    el.setAttribute('data-visible', 'both'); 
                } else {
                    // remove active class from preview button
                    this.el.querySelector('.preview-flipbox').classList.remove('active');

                    // show toggle button when not in preview mode
                    this.el.querySelector('.toggle-flipbox').style.display = 'block';

                    // hide back
                    el.setAttribute('data-visible', 'front');
                }
            },
            events: {
                'click .toggle-flipbox' : "toggle_flipbox",
                'click .preview-flipbox' : "preview_flipbox",
            },
            toggle_flipbox: function(){
                const el = this.model.getEl();
                const currentVisible = el.getAttribute('data-visible');
                const newVisible = currentVisible === 'front' ? 'back' : 'front';
                el.setAttribute('data-visible', newVisible);
                // select the side that is now visible
                setTimeout(() => {
                    const sideType = newVisible === 'front' ? 'flipbox-front' : 'flipbox-back';
                    const sideComponent = this.model.findFirstType(sideType);
                    editor.select(sideComponent);
                }, 0);
            },
            preview_flipbox: function(){
                const preview_flip_effects = this.model.get('__temp_preview_flip_effects') || false;
                this.model.set('__temp_preview_flip_effects', !preview_flip_effects);

                // select the component
                editor.select(this.model);
                this.render();
            }
        }
    });

    // Set default content for text-editor inside front and back of flipbox
    UltimateFields.addFilter('before_group_create', function(args) {
        const comp = args.component;
        if (!comp) return;

        const type = comp.get('type');

        // Set default content for text-editor inside front and back of flipbox
        if (type === 'text-editor' && (comp.parent()?.getType() === 'flipbox-front' || comp.parent()?.getType() === 'flipbox-back')) {
            if (!args.datastore.get('content')) {
                const sideContent = comp.parent().getType() === 'flipbox-front' ? '<b>Front Side Content</b>' : '<b>Back Side Content</b>';
                args.datastore.set('content', sideContent);
            }
        }
    });

    // Remove the component styles before saving as they are only needed to be presented in the style manager
    editor.on('builder:before-save-editor', () => {
        const css = editor.Css;
        css.remove(`.flipbox-front`);
        css.remove(`.flipbox-back`);
    });
}