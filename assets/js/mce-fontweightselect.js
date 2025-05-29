(function() {
    tinymce.create('tinymce.plugins.FontWeightSelect', {
        init: function(editor) {
            editor.addButton('fontweightselect', {
                type: 'listbox',
                text: 'Font Weight',
                icon: false,
                onselect: function(e) {
                    const value = this.value();
                    const node = editor.selection.getNode();

                    // Limpiar otras clases fw-*
                    editor.dom.removeClass(node, 'fw-light');
                    editor.dom.removeClass(node, 'fw-normal');
                    editor.dom.removeClass(node, 'fw-bold');

                    if (value) {
                        editor.dom.addClass(node, value);
                    } else {
                        // Si eligió "Quitar", eliminar cualquier <span> vacío
                        if (node.tagName === 'SPAN' && node.className.trim() === '') {
                            const parent = node.parentNode;
                            while (node.firstChild) parent.insertBefore(node.firstChild, node);
                            parent.removeChild(node);
                        }
                    }
                },
                values: [
                    { text: 'Light', value: 'fw-light' },
                    { text: 'Normal', value: 'fw-normal' },
                    { text: 'Bold', value: 'fw-bold' },
                    { text: 'Quitar', value: '' }
                ],
                onPostRender: function() {
                    const self = this;

                    editor.on('NodeChange', function(e) {
                        const node = editor.selection.getNode();
                        if (editor.dom.hasClass(node, 'fw-light')) {
                            self.value('fw-light');
                        } else if (editor.dom.hasClass(node, 'fw-normal')) {
                            self.value('fw-normal');
                        } else if (editor.dom.hasClass(node, 'fw-bold')) {
                            self.value('fw-bold');
                        } else {
                            self.value('');
                        }
                    });
                }
            });
        },
        createControl: function() { return null; }
    });

    tinymce.PluginManager.add('fontweightselect', tinymce.plugins.FontWeightSelect);
})();
