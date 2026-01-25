window.gjsExtendEditor = function (editor) {

    // extend editor with a mehtod to get component datastore
    editor.getComponentDatastore = function(component) {
        const componentId = component.attributes.__tempID,
            editorConfig = editor.getConfig(),
            temporalCompStore = editorConfig.temporalCompStore || {};
        return temporalCompStore[componentId]?.datastore || null;
    }

    editor.getBuilderCompModel = function(component) {
        const componentId = component.attributes.__tempID,
            editorConfig = editor.getConfig(),
            temporalCompStore = editorConfig.temporalCompStore || {};
        return temporalCompStore[componentId] || null;
    }

    editor.updateBuilderCompModelField = function(builder_comp_model, fieldName) {
        const fields = builder_comp_model.get('fields') || {};
	    const fields_models = fields.models || [];
	    const field_model = fields_models.find(fm => fm.get('name') == fieldName);
	    if (field_model) {
	        field_model.trigger('update-views');
        }
    }

    editor.getPreparedFileObject = function( file_id ) {
        if ( typeof UltimateFields === 'undefined' || !UltimateFields.Field || !UltimateFields.Field.File ) {
            console.warn('UltimateFields or UltimateFields.Field.File is not available.');
            return null;
        }

        return UltimateFields.Field.File.Cache.get(file_id);
    }
}