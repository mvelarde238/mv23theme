window.gjsExtendEditor = function (editor) {

    // extend editor with a mehtod to get component datastore
    editor.getComponentDatastore = function(component) {
        const componentId = component.attributes.__tempID,
            editorConfig = editor.getConfig(),
            temporalCompStore = editorConfig.temporalCompStore || {};
        return temporalCompStore[componentId].datastore || null;
    }
}
