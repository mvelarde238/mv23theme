window.gjsExtendEditor = function (editor) {

    // extend editor with a mehtod to get component datastore
    editor.getComponentDatastore = function(component) {
        const componentId = component.attributes.__tempID,
            editorConfig = editor.getConfig(),
            temporalCompStore = editorConfig.temporalCompStore || {};
        return temporalCompStore[componentId].datastore || null;
    }

    editor.getBuilderCompModel = function(component) {
        const componentId = component.attributes.__tempID,
            editorConfig = editor.getConfig(),
            temporalCompStore = editorConfig.temporalCompStore || {};
        return temporalCompStore[componentId] || null;
    }
}

// global function to get "prepared" file object from Ultimate Fields File field cache
get_prepared_file_object = function( file_id ) {
    if ( typeof UltimateFields === 'undefined' || !UltimateFields.Field || !UltimateFields.Field.File ) {
        console.warn('UltimateFields or UltimateFields.Field.File is not available.');
        return null;
    }

    return UltimateFields.Field.File.Cache.get(file_id);
}