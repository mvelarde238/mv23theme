window.gjsFlipbox = function (editor) {
    const domc = editor.DomComponents;
    const cmpClass = 'flipbox';

    // add custom css to canvasCss
    let config = editor.getConfig();
    config.canvasCss = config.canvasCss || '';
    config.canvasCss += `
        .flipbox .cmp-action{ opacity:0; }
        .flipbox:hover .cmp-action{ opacity:1; }
        [data-visible="front"]>.flipbox-inner>.flipbox-back{ display:none !important; }
        [data-visible="back"]>.flipbox-inner>.flipbox-front{ display:none !important; }
        .toggle-flipbox{ position:absolute; top:5px; right:50%; transform:translateX(50%); z-index:9999; }
        .preview-flipbox{ position:absolute; bottom:5px; right:50%; transform:translateX(50%); z-index:9999; }
    `;
    editor.canvasCss = config.canvasCss;

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
                    select: (cmp) => cmp.closestType(cmpClass),
                },
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
                droppable: true,
                removable: false,
                copyable: false,
                selectable: true,
                hoverable: true,
                classes: ['flipbox-front'],
                components: [
                    { 
                        type: 'figure',
                        style: { width: '100%' },
                        components: [
                            { 
                                type: 'image-component',
                                style: { 'aspect-ratio': '4/3' },
                            },
                            { type: 'figcaption' }
                        ]
                    }
                ],
            },
        }
    });

    domc.addType('flipbox-back', {
        isComponent: el => el.classList && el.classList.contains('flipbox-back'),
        extend: 'flipbox-front',
        model: {
            defaults: {
                name: 'Flip Box Back',
                classes: ['flipbox-back'],
                components: [
                    { 
                        type: 'text-editor',
                        style: { 'text-align': 'center' },
                    }
                ],
            },
        }
    });
    
    domc.addType(cmpClass, {
        isComponent: el => el.classList && el.classList.contains(cmpClass),
        model: {
            defaults: {
                name: 'Flip Box',
                tagName: 'div',
                draggable: true,
                droppable: false,
                removable: true,
                copyable: true,
                selectable: true,
                hoverable: true,
                classes: [cmpClass, 'component'],
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
            init({model}){
            },
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
}