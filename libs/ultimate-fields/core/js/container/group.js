(function( $ ){

	/**
	 * This file handles the Group container of Ultimate Fields that is used for repeaters.
	 */
	var container = UltimateFields.Container,
		group     = container.Group = {};

	group.Model = container.Base.Model.extend({
		/**
		 * Add more defaults for repeater groups.
		 */
		defaults: _.extend({
			duplicateable:    true,
			deleteable:       true,
			maximum:          0,
			title_background: false,
			title_color:      false,
			icon:             false
		}, container.Base.Model.prototype.defaults ),

		backupState: function() {
			var temp = this.datastore.clone();
			temp.parent = this.datastore.parent;
			this.realDatastore = this.datastore;
			this.setDatastore( temp );
		},

		saveState: function() {
			var newData = this.datastore.toJSON();

			if( '__tab' in newData )
				delete newData.__tab;

			this.realDatastore.set( newData );
			delete this.datastore;
			this.setDatastore( this.realDatastore );
			this.trigger( 'stateSaved' );
		},

		restoreState: function() {
			this.setDatastore( this.realDatastore );
		},

		/**
		 * Binds a group to it's type, so the group can toggle its cotnrols.
		 */
		bindToGroupType: function( type ) {
			var that = this;

			if( ! type ) {
				return;
			}

			// Collect initial values
			this.set({
				duplicateable: type.canBeAdded(),
				deleteable:    type.canBeRemoved()
			});

			type.on( 'change', function() {
				that.set({
					duplicateable: type.canBeAdded(),
					deleteable:    type.canBeRemoved()
				});
			});
		}
	});

	group.View = container.Base.View.extend({
		className: 'uf-group',

		events: {
			'uf-sorted': 'saveSort'
		},

		render: function() {
			var that = this,
				tmpl = UltimateFields.template( 'repeater-group' ),
				clicks, background, color;

			this.$el.html( tmpl({
				title:      this.model.get( 'title' ),
				type:       this.model.get( 'type' ),
				icon:       this.model.get( 'icon' ),
				edit_mode:  this.model.get( 'edit_mode' ),
				number:     this.$el.index() + 1 - this.$el.prevAll( '.uf-repeater-placeholder' ).length
			}));

			// Add the necessary style-settings
			this.addStyles();

			// Bind control clicks
			this.bindClicks();

			// Bind the destroyer
			this.model.on( 'destroy', this.remove.bind( this ) );

			// Add inline fields
			if( 'popup' != this.model.get( 'edit_mode' ) ) {
				this.addInlineElements();
			} else {
				this.$el.addClass( 'uf-group-hidden' );
			}

			// When values change, change the title
			this.addTitleListener();

			// Toggle button
			this.toggleElements();
			this.model.on( 'change:duplicateable', _.bind( this.toggleElements, this ) );
			this.model.on( 'change:deleteable', _.bind( this.toggleElements, this ) );

			// Whenever there are errors, add some styles
			this.addValidationStateListener();
		},

		/**
		 * Adds color styles to the group.
		 */
		addStyles: function() {
			var background, color, border;

			// Style the title if needed
			if( background = this.model.get( 'title_background' ) ) {
				this.$el.find( '.uf-group-title' ).css( 'background-color', background );
			}

			if( color = this.model.get( 'title_color' ) ) {
				this.$el.find( '.uf-group-title' ).css( 'color', color );
			}

			if( border = this.model.get( 'border_color' ) ) {
				this.$el.css( 'border-color', border );
			}
		},

		/**
		 * Binds the clicks for basic elements.
		 */
		bindClicks: function() {
			var that = this, clicks;

			// Assign first-level events before rendering the content/fields
			clicks = {
				'h3':                          'toggle',
				'.uf-group-control-close':     'close',
				'.uf-group-control-open':      'open',
				'.uf-group-control-remove':    'delete',
				'.uf-group-control-popup':     'openPopup',
				'.uf-group-control-duplicate': 'duplicate',
				'.uf-group-control-copy': 'copy',
				'.uf-group-control-paste': 'paste',
				'.uf-group-control-save': 'save',
				'.uf-group-control-open-menu': 'openContextMenu'
			}

			_.each( clicks, function( handler, className ) {
				that.$el.find( className ).on( 'click', function( e ) {
					e.preventDefault();

					// When dragging and etc, don't let the buttons work
					if( that.$el.is( '.no-click' ) )
						return;

					that[ handler ]();
				});
			});

			window.addEventListener('click', function(e){
    			if ( !$(e.target).hasClass('uf-group-control') ){
     				that.hideContextMenu();
  				} 
			})
		},

		/**
		 * Adds inline elements like fields and etc..
		 *
		 * This is only used when the group can be edited without a popup.
		 */
		addInlineElements: function() {
			var that = this;

			if( ! this.fieldsRendered && ! this.model.datastore.get( '__hidden' ) ) {
				this.addFields();
				this.fieldsRendered = true;
				UltimateFields.ContainerLayout.DOMUpdated();
			}

			// When values change, re-render the fields
			this.model.on( 'stateSaved', function() {
				if( ! that.fieldsRendered ) {
					return;
				}

				that.$fields.empty();
				that.addFields( that.$fields );
			});

			// Hide/show content
			if( this.model.datastore.get( '__hidden' ) ) {
				this.$el.addClass( 'uf-group-hidden' );
			}
		},

		/**
		 * Listens for changes the title.
		 */
		addTitleListener: function() {
			var that = this;

			this.model.datastore.on( 'change', function() {
				that.updateTitlePreview();
			});

			that.updateTitlePreview();
		},

		/**
		 * Adds a listener, which monitors the validation state of the group.
		 */
		addValidationStateListener: function() {
			var that = this;

			this.model.on( 'change:validationErrors', function() {
				var method = that.model.get( 'validationErrors' ).length
					? 'addClass'
					: 'removeClass';

				that.$el[ method ]( 'uf-group-invalid' );
			});
		},

		delete: function() {
			this.hideContextMenu();
			if (confirm("Are you sure you want to delete this element? This CANNOT be undone.")) { //mv23
				this.model.datastore.destroy();
				this.model.destroy();
				this.remove();
			}
		},

		duplicate: function() {
			this.hideContextMenu();

			this.trigger( 'uf-duplicate', {
				datastore: this.model.datastore.clone()
			});
		},

		copiarAlPortapapeles: function(texto) {
			if (!navigator.clipboard) {
				// Si el API del portapapeles no está disponible, utiliza execCommand
				const textarea = document.createElement('textarea');
				textarea.value = texto;
				document.body.appendChild(textarea);
				textarea.select();
				document.execCommand('copy');
				document.body.removeChild(textarea);
			} else {
				// Utiliza el API del portapapeles si está disponible
				navigator.clipboard.writeText(texto).then(
					() => {
						console.log('Texto copiado al portapapeles');
					},
					err => {
						console.error('No se pudo copiar el texto: ', err);
					}
				);
			}
		},

		copy: function() {
			var settings = this.model.datastore.clone();

			this.copiarAlPortapapeles( JSON.stringify(settings.attributes) ); 
			// get the copied text is dificult without navigator api so it not implemented in "paste" method

			localStorage.setItem('copied_settings', JSON.stringify(settings.attributes));
			this.hideContextMenu();
		},

		paste: function() {
			this.hideContextMenu();
			var copied_settings = localStorage.getItem('copied_settings');
			if (copied_settings) {
				var settings = JSON.parse(copied_settings),
					component_type = this.model.datastore.attributes.__type;
				
				if( component_type == settings.__type ){
					// fix group position
					settings.__index = this.model.datastore.attributes.__index;

					this.model.datastore.set( settings );

					// used by repeater.js to REFRESH the brand new group
					this.trigger( 'uf-paste', {});

					// show the group
					this.openPopup();

				} else {
					alert('The settings of a "'+settings.__type+'" component cannot be pasted in a "'+component_type+'" component.');
				}
			} else {
				alert('There are no settings to paste.');
			}
		},

		save: function() {
			var settings = this.model.datastore.clone();
			this.hideContextMenu();

			var saveForm = '<div class="mv23-library-save-form">';
			saveForm += '<h2>Guardar en la libreria</h2>';
			saveForm += '<p>Ingrese el nombre del item:</p>';
			saveForm += '<form action="">';
			saveForm += '<input type="text" name="title" placeholder="Library item name...">';
			saveForm += '<button class="button-primary" type="submit">Guardar</button>'; 
			saveForm += '<br/>'; 
			saveForm += '<input type="hidden" name="cat">';
			saveForm += '<textarea style="display:none" name="settings"></textarea>'; 
			saveForm += '</form>'; 
			saveForm += '</div>'; 

			Modal_v23.open('mv23-library-modal save-form-modal');
			Modal_v23.fillWithHTMLContent(saveForm);

			$form = $('.mv23-library-save-form form');
			$form.find('textarea').val( JSON.stringify(settings.attributes) );
			$form.find('input[name=cat]').val( settings.attributes.__type );

			$form.on('submit',function(ev){
				ev.preventDefault();
				var cat = $(this).find('input[name=cat]').val(),
					title = $(this).find('input[name=title]').val(),
					settings = $(this).find('textarea').val();

				if (title == '') {
					alert('Ingrese un titulo para el nuevo item...');
					return false;
				}

				$.ajax({
					type: 'POST',
					dataType : "json",
					url: MV23_GLOBALS.ajaxUrl,
					data : { 
					    action:'mv23_library_save_item',
					    title: title,
					    cat: cat,
					    settings: settings,
					},
					beforeSend: function(){
						Modal_v23.addClass('loading');
					},
					success: function(response){
						$('.mv23-library-modal').removeClass('loading');
						Modal_v23.fillWithHTMLContent('<div class="ajax-message '+response.status+'"><p>'+response.message+'</p></div>');
					}
				});
			})
		},

		openContextMenu: function(ev){
			var $contextMenu = this.$el.find('.context-menu').eq( 0 );
			$contextMenu.toggle();

			// $contextMenu.find('a').eq(0).focus();

			// $contextMenu.find('a').eq(0).focusout(function(){
			// 	$contextMenu.hide();
			// });
		},

		hideContextMenu: function(){
			this.$el.find('.context-menu').eq( 0 ).hide();
		},

		saveSort: function() {
			var displayed;

			this.model.datastore.set( '__index', this.$el.index(), {
				silent: true
			});

			displayed = this.$el.index() + 1 - this.$el.prevAll( '.uf-repeater-placeholder' ).length;
			this.$el.find( '.uf-group-number-inside' ).eq( 0 ).text( displayed );
		},

		openPopup: function() {
			var that = this,
				view;

			// Save the state of the datastore
			this.model.backupState();

			view = new group.fullScreenView({
				model: this.model
			});

			UltimateFields.Overlay.show({
				view: view,
				title: this.model.datastore.get( 'title' ) || this.model.get( 'title' ),
				buttons: view.getbuttons()
			});
		},

		/**
		 * Closes the group.
		 */
		close: function() {
			var that    = this,
				$inside = this.$el.find( '.uf-group-inside' ).eq( 0 );

			this.$el.addClass( 'uf-group-hidden' );
			this.model.datastore.set( '__hidden', true );
		},

		/**
		 * Opens the group.
		 */
		open: function() {
			var that    = this,
				$inside;

			if( 'popup' == this.model.get( 'edit_mode' ) ) {
				this.openPopup();
				return;
			}

			$inside = this.$el.find( '.uf-group-inside' ).eq( 0 );
			this.$el.removeClass( 'uf-group-hidden' );
			this.model.datastore.set( '__hidden', false );

			// Render fields if required
			if( ! this.fieldsRendered && ! this.model.datastore.get( '__hidden' ) ) {
				this.addFields();
				this.fieldsRendered = true;
			}

			UltimateFields.ContainerLayout.DOMUpdated( true );
			this.focusFirstField();
		},

		/**
		 * Toggles the visiblity of the group.
		 */
		toggle: function() {
			if( this.model.datastore.get( '__hidden' ) ) {
				this.open();
			} else {
				this.close();
			}
		},

		updateTitlePreview: function() {
			var tmpl  = _.template( this.model.get( 'title_template' ) ),
				$em   = this.$el.find( '.uf-group-title-preview' ).eq( 0 ),
				title, prefix;

			try {
				var data = this.model.datastore.toJSON();
				// data.fields = this.model.get( 'fields' );

				// if(data.hasOwnProperty('file')){
					// title = data.file_prepared[0].filename;
				// } else 
				if( 
					data.__type == 'page_module' && 
					data.hasOwnProperty('settings') && 
					data.settings.hasOwnProperty('main_attributes') &&
					data.settings.main_attributes.hasOwnProperty('id') 
				) {
					title = data.settings.main_attributes.id;
				} else {
					title = tmpl( data );
				}
			} catch( e ){
				title = '';
			}

			prefix = this.model.get( 'title' ) ? ': ' : '';

			if( title.length ) {
				$em.show().html( prefix + title );
			} else {
				$em.hide().empty();
			}
		},

		/**
		 * Toggles elements based on settings.
		 */
		toggleElements: function() {
			this.$el.find( '.uf-group-control-duplicate' )[ this.model.get( 'duplicateable' ) ? 'show' : 'hide' ]();
			this.$el.find( '.uf-group-control-remove' )[ this.model.get( 'deleteable' ) ? 'show' : 'hide' ]();
		}
	});

	/**
	 * Handles the view of the group within a table layout
	 */
	group.RowView = group.View.extend({
		className: 'uf-group uf-table-row',

		/**
		 * Renders the view.
		 */
		render: function() {
			var that = this,
				tmpl = UltimateFields.template( 'table-row' ),
				clicks, background, color;

			this.$el.html( tmpl({
				title:      this.model.get( 'title' ),
				type:       this.model.get( 'type' ),
				icon:       this.model.get( 'icon' ),
				edit_mode:  this.model.get( 'edit_mode' ),
				number:     this.$el.index() + 1 - this.$el.prevAll( '.uf-repeater-placeholder' ).length
			}));

			// Add the necessary style-settings
			this.addStyles();

			// Bind control clicks
			this.bindClicks();

			this.model.on( 'destroy', this.remove.bind( this ) );

			// Add inline fields
			this.addInlineElements();

			// Toggle button
			this.toggleElements();
			this.model.on( 'change:duplicateable', _.bind( this.toggleElements, this ) );
			this.model.on( 'change:deleteable', _.bind( this.toggleElements, this ) );

			// Whenever there are errors, add some styles
			this.addValidationStateListener();
		},

		/**
		 * Adds inline elements like fields and etc..
		 *
		 * This is only used when the group can be edited without a popup.
		 */
		addInlineElements: function() {
			var that = this;

			this.addFields( null, {
				wrap: UltimateFields.Field.TableWrap
			});
		}
	});

	/**
	 * Handles the display of groups within an overlay.
	 */
	group.fullScreenView = UltimateFields.Container.Group.View.extend({
		tagName:   'form',
		className: 'uf-popup uf-popup-group',

		render: function() {
			var that = this,
				tmpl = UltimateFields.template( 'popup-group' )

			this.$el.attr({
				action: window.location.href,
				method: 'post'
			});

			this.$el.html(tmpl({
				title: this.model.get( 'title' )
			}));

			this.$fields = this.$el.find( '.uf-fields' );
			this.addFields( this.$fields, {
				tabs: false
			});

			this.focusFirstField();
		},

		getbuttons: function() {
			var that = this, buttons = [];

			buttons.push({
				type: 'primary',
				cssClass: 'uf-button-save-popup',
				text: UltimateFields.L10N.localize( 'repeater-save' ).replace( '%s', this.model.get( 'title' ) ),
				icon: 'dashicons-category',
				callback: _.bind( this.save, this )
			});

			if( this.model.get( 'deleteable' ) ) {
				buttons.push({
					type: 'secondary',
					cssClass: 'uf-button-delete-popup',
					text: UltimateFields.L10N.localize( 'repeater-delete' ).replace( '%s', this.model.get( 'title' ) ),
					icon: 'dashicons-no-alt',
					callback: function() { that.delete(); return true; }
				});
			}

			return buttons;
		},

		save: function( overlay ) {
			var that   = this,
				errors = this.model.validate();

			if( ! errors ) {
				this.model.saveState();
				return true;
			} else {
				var $body = $( '<div />' ), $ul = $( '<ul />' );

				_.each( errors, function( error ) {
					$( '<li />' )
						.appendTo( $ul )
						.html( error );
				});

				$ul.appendTo( $body );

				$body.append( $( '<p />' ).text( UltimateFields.L10N.localize( 'error-corrections' ) ) );

				overlay.alert({
					title: UltimateFields.L10N.localize( 'container-issues-title' ),
					body:  $body.children()
				});
			}
		},

		close: function( e ) {
			this.model.restoreState();
		},

		delete: function() {
			if (confirm("Are you sure you want to delete this element? This CANNOT be undone.")) { //mv23
				this.model.restoreState();
				this.model.datastore.destroy();
				this.model.destroy();
			}
		},

		remove: function() {
			this.$el.remove();
			this.model.restoreState();
		},

		/**
		 * Attaches the view to an overlay
		 */
		 attachToOverlay: function( overlay ) {
 			var that = this;

 			this.$el.on( 'submit', function( e ) {
 				e.preventDefault();

 				if( that.save( overlay ) ) {
 					overlay.removeScreen();
 				}
 			});
 		}
	});

	// Complex groups
	group.ComplexView = container.Base.View.extend({
		className: 'uf-complex-fields',

		render: function() {
			var that = this,
				tmpl = UltimateFields.template( 'complex-group' );

			this.$el.html( tmpl() );

			this.model.on( 'destroy', function() {
				that.remove();
			});

 			// Add normal fields
 			this.addFields();
		},

		/**
		 * Indicates whether the container supports inline tabs.
		 */
		allowsInlineTabs() {
			return false;
		}
	});


})( jQuery );
