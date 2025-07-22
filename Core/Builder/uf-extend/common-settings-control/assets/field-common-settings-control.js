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
				tmpl = UltimateFields.template('common-settings-control'),
				has_value_class, storedValues, fieldname = this.model.get( 'name' );

			// Start with the base
			this.$el.html( tmpl( this.model.toJSON() ) );

			storedValues = ( this.model.datastore.get( fieldname ) ) ? 
                this.model.datastore.get( fieldname ) :
                {};
			has_value_class = ( !_.isEmpty(storedValues) ) ? 'has-values' : '';
            
            // add a button to open a pop up
			var addButton = new UltimateFields.Button({
                text: this.model.get( 'add_text' ),
				type: 'primary',
                icon: this.model.get( 'icon' ),
				callback: _.bind( this.openPopUp, this ),
				cssClass: has_value_class
			});

			addButton.$el.appendTo( this.$el.find( '.common-settings-control__add' ) );
			addButton.render();
		},

		/**
		 * Once the "add settings" button is clicked, open a pop up
		 */
		openPopUp() {
            var that = this, overlayLayer, storedValues, fieldname;

			fieldname = this.model.get( 'name' );
			storedValues = ( this.model.datastore.get( fieldname ) ) ? 
                this.model.datastore.get( fieldname ) :
                {};
			this.model.set( 'storedValues', storedValues );

			// Create the view
			view = new commonSettingsControlField.PopupView({
				model: this.model
			});

			// Show the overlay
			var overlayLayer = UltimateFields.Overlay.show({
				view: view,
				title: UltimateFields.L10N.localize( this.model.get( 'add_text' ) ),
				buttons: view.getButtons()
			});

			// save a reference to the uf overlay
			this.model.set( '_overlayLayer', overlayLayer);

			// save a reference to the trigger button
			this.model.set( '_triggerButton', this.$el.find('.uf-button') );

			// Listen for saving
			view.on( 'save', function(e, filtered_data) {
				that.model.datastore.set( this.model.get( 'name' ), filtered_data );
				overlayLayer.removeScreen();
			});
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
					callback: (e) => {
						var processed_data, 
							raw_data = this.model.get('raw_data');

						this.$el.find('.wp-color-picker').wpColorPicker('close');

						var errors = this.model.get('_popup_container').model.validate();

						if( ! errors ) {
							
							this.$el.find('.colour-picker').iris('hide');

                			processed_data = that.filterData( this.model.get( 'container' ), raw_data );

							// check has-values class
							const _button = $( this.model.get('_triggerButton') );
							if( _.isEmpty(processed_data) ) {
								_button?.removeClass('has-values');
							} else {
								_button?.addClass('has-values');
							}

                			that.trigger( 'save', e, processed_data );
							
						} else {
							var $body = $( '<div />' ), $ul = $( '<ul />' );
						
							_.each( errors, function( error ) {
								$( '<li />' )
									.appendTo( $ul )
									.html( error );
							});
						
							$ul.appendTo( $body );
						
							$body.append( $( '<p />' ).text( UltimateFields.L10N.localize( 'error-corrections' ) ) );
						
							this.model.get( '_overlayLayer').alert({
								title: UltimateFields.L10N.localize( 'container-issues-title' ),
								body:  $body.children()
							});
						}
					}
                }
			];
		},

		/**
		 * Renders the fields in the pop up
		 */
		addContainer: function() {
			var that = this, fields, storedValues, containerName, hiddenFields;

            containerName = this.model.get( 'container' );
			storedValues = this.model.get( 'storedValues' );
			hiddenFields = this.model.get( 'hidden_fields' );

            // set a initial raw_data state
            that.model.set( 'raw_data', storedValues );

			fields = POPUP_CONTAINERS[containerName] ?? [];

			var _popup_container = this.$el.container({
				fields: fields,
				id:     'popup_container',
				layout: 'row',
				validation_enabled: true
			}, storedValues );

			// hide some fields if needed
			if( hiddenFields && hiddenFields.length > 0 ){
				hiddenFields.forEach(element => {
					this.$el.find( '.uf-field-name-' + element ).hide();
				});
			}

			// save a reference to the uf container to validate it
			this.model.set( '_popup_container', _popup_container);
			
			this.$el.on( 'values-changed', function( e, values ) {				
                that.model.set( 'raw_data', values );
			});
		},

		/**
		 * Filter the raw settings data cleaning empty or not used setting options
		 */
        filterData( container_name, raw_data ) {
			if( container_name == 'common_settings_container' ){
            	Object.entries(raw_data).forEach(entry => {
            	   if( entry[0] == 'main_attributes'  ){
            	        if( entry[1].id == '' && entry[1].class == '' ){
            	            delete raw_data.main_attributes;
            	        } else {
            	            if( entry[1].id == '' ) delete raw_data.main_attributes.id;
            	            if( entry[1].class == '' ) delete raw_data.main_attributes.class;
            	        }
            	    } else if (entry[0] == 'responsive' ){
						if( 
							( !entry[1].hide_on_desktop && !entry[1].hide_on_tablet && !entry[1].hide_on_mobile ) 
							|| !entry[1]
						){
            	            delete raw_data.responsive;
            	        } else {
							if( !entry[1].hide_on_desktop ) delete raw_data.responsive.hide_on_desktop;
            	            if( !entry[1].hide_on_tablet ) delete raw_data.responsive.hide_on_tablet;
            	            if( !entry[1].hide_on_mobile ) delete raw_data.responsive.hide_on_mobile;
						}
            	    } else if (entry[0] == 'other_settings' ){ // ???
            	       if( entry[1].visibility == 'all' && entry[1].layout == 'layout1' ){
            	           delete raw_data.other_settings;
            	       } else {
            	            if( entry[1].visibility == 'all' ) delete raw_data.other_settings.visibility;
            	            if( entry[1].layout == 'layout1' ) delete raw_data.other_settings.layout;
            	       }
            	   } else {
            	        if( _.isObject(entry[1]) && (!entry[1].hasOwnProperty('use' ) || entry[1].use == false) ){
							delete raw_data[ entry[0] ];
						} 
            	   };
            	});
			}

			if( container_name == 'actions_container' ){
				Object.entries(raw_data).forEach(entry => {
					if( entry[0] == 'actions' && entry[1].length === 0 ){
						delete raw_data.actions;
					}
				});
			}

			if( container_name == 'scroll_animations_container' ){
				Object.entries(raw_data).forEach(entry => {
					if( entry[0] == 'groups' && entry[1].length === 0 ){
						delete raw_data.groups;
					}
				});
			}

            return raw_data;
        }
	});

})( jQuery );
