(function( $ ){

	var uf                         = window.UltimateFields,
		field                      = uf.Field,
		commonSettingsControlField = field.Common_Settings_Control = {};

	/**
	 * A basic model for the "field" with some default values.
	 */
	commonSettingsControlField.Model = field.Model.extend({
		ajax: function( action, args ) {
			var options = _.extend( {
				url:      window.location.href,
				type:     'post',
				dataType: 'json'
			}, args, {
				data: _.extend({
					nonce:     this.get( 'nonce' ),
					uf_ajax:   true,
					uf_action: action + '_' + this.get( 'name' )
				}, args.data || {} )
			});

			return $.ajax( options );
		}
	});

	/**
	 * A view, which will do most of the heavy lifting
	 */
	commonSettingsControlField.View = field.View.extend({
		/**
		 * Renders the input of the field.
		 */
		render: function() {
			var that = this,
				tmpl = UltimateFields.template('common-settings-control');

			// Start with the base
			this.$el.html( tmpl( this.model.toJSON() ) );
            
            // add a button to open a pop up
			var addButton = new UltimateFields.Button({
                text: this.model.get( 'add_text' ),
				type: 'primary',
                icon: 'dashicons-migrate',
				callback: _.bind( this.openPopUp, this )
			});

			addButton.$el.appendTo( this.$el.find( '.common-settings-control__add' ) );
			addButton.render();
		},

		/**
		 * Once the "add settings" button is clicked, open a pop up
		 */
		openPopUp() {
            var that = this, overlayLayer;

			// Create the view
			view = new commonSettingsControlField.PopupView({
				model: this.model
			});

			// Show the overlay
			overlayLayer = UltimateFields.Overlay.show({
				view: view,
				title: UltimateFields.L10N.localize( this.model.get( 'add_text' ) ),
				buttons: view.getButtons()
			});

			// Listen for saving
			view.on( 'save', function() {
                let raw_data = that.model.datastore.get( 'raw_data' );

                let settings_control = that.filterData( this.model.get( 'name' ), raw_data );

                that.model.datastore.set( this.model.get( 'name' ), settings_control );

				// Remove the overlay
				overlayLayer.removeScreen();
			});
		},

        /**
		 * Filter the raw settings data cleaning empty or not used setting options
		 */
        filterData( settings_name, raw_data ) {
			if( settings_name == 'settings' || settings_name == 'page_header_settings' ){
            	Object.entries(raw_data).forEach(entry => {
            	   if( entry[0] == 'main_attributes'  ){
            	        if( entry[1].id == '' && entry[1].class == '' ){
            	            delete raw_data.main_attributes;
            	        } else {
            	            if( entry[1].id == '' ) delete raw_data.main_attributes.id;
            	            if( entry[1].class == '' ) delete raw_data.main_attributes.class;
            	        }
            	    } else if (entry[0] == 'other_settings' ){
            	       if( entry[1].visibility == 'all' && entry[1].layout == 'layout1' ){
            	           delete raw_data.other_settings;
            	       } else {
            	            if( entry[1].visibility == 'all' ) delete raw_data.other_settings.visibility;
            	            if( entry[1].layout == 'layout1' ) delete raw_data.other_settings.layout;
            	       }
            	   } else {
            	        if( !entry[1].hasOwnProperty('use' ) || entry[1].use == false ){
							delete raw_data[ entry[0] ];
						} 
            	   };
            	});
			}

			// if( settings_name == 'actions_settings' ){
			// 	Object.entries(raw_data).forEach(entry => {
			// 		if( entry[0] == 'actions' && entry[1].length === 0 ){
			// 			delete raw_data.actions;
			// 		}
			// 	});
			// }

			// if( settings_name == 'scroll_animations_settings' ){
			// 	Object.entries(raw_data).forEach(entry => {
			// 		if( entry[0] == 'groups' && entry[1].length === 0 ){
			// 			delete raw_data.groups;
			// 		}
			// 	});
			// }

            return raw_data;
        }
	});

    commonSettingsControlField.PopupView = Backbone.View.extend({
        className : 'uf-boxed-fields',
		/**
		 * Renders the popup.
		 */
		render: function() {
			this.addContainer();
		},

		/**
		 * Returns the buttons for the popup.
		 */
		getButtons: function() {
            var that = this;

            return [
                {
                    text: UltimateFields.L10N.localize( this.model.get( 'save_text' ) ),
                    icon: 'dashicons-yes',
                    type: 'primary',
                    callback: function() { that.trigger( 'save' ); }
                }
			];
		},

		/**
		 * Renders the fields in the pop up
		 */
		addContainer: function() {
			var that = this, fields, fieldname, storedValues, containerName;

            fieldname = this.model.get( 'name' );
            containerName = this.model.get( 'container' );

            storedValues = ( that.model.datastore.get( fieldname ) ) ? 
                that.model.datastore.get( fieldname ) :
                {};

            // set a initial raw_data state
            that.model.datastore.set( 'raw_data', storedValues );

            fields = ( window[containerName] ) 
                ? window[containerName] 
                : [];

			this.$el.container({
				fields: fields,
				id:     'common_settings_container',
				layout: 'row'
			}, storedValues );

			this.$el.on( 'values-changed', function( e, values ) {
                that.model.datastore.set( 'raw_data', values );
			});
		}
	});

})( jQuery );
