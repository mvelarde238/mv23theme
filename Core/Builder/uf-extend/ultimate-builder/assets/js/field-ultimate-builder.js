(function ($) {

    var uf = window.UltimateFields,
        field = uf.Field,
        repeater = field.Repeater,
        builderField = field.ultimate_builder = {};

    builderField.Model = repeater.Model.extend({
        /**
         * Overwrite the datastore method in order to avoid working
         * with values before there is a datastore to save them in.
         */
        setDatastore: function (datastore) {
            var that = this;

            // Do the normal initialization
            field.Model.prototype.setDatastore.call(this, datastore);
        }
    });

    builderField.View = repeater.View.extend({
        initialize: function () {
            var that = this;

            // Do the standard initialization
            field.View.prototype.initialize.apply(this, arguments);
        },

        /**
         * Renders the view.
         */
        render: function () {
            var tmpl = UltimateFields.template('ultimate-builder'),
                uf_field_model = this.model,
                builder_data = this.model.getValue(),
                field_name = this.model.get('name'),
                components_data = this.model.datastore.get( field_name + '_components' ),
                styles = this.model.datastore.get( field_name + '_styles' ),
                builder_link = this.model.datastore.get( field_name + '_builder_link' );

            // force initial field values
            this.model.setValue({
                'builder_data': builder_data,
                'components_data': components_data,
                'css': styles
            });
            this.model.trigger('value-saved');

            // Add the CSS class and the basic layout
            this.$el.addClass('uf-ultimate-builder');
            this.$el.html(tmpl());

            // get action from URL
            var urlParams = new URLSearchParams(window.location.search);
            var $action = urlParams.get('action') ?? 'edit';

            if( $action === 'edit' ){
                // add the builder button
                var $wrapper = jQuery('<p></p>');
                var $button = jQuery('<button type="button" class="button button-secondary uf-open-builder">Open Builder Interface</button>');
                $wrapper.append($button);
                this.$el.append($wrapper);

                $button.on('click', function (e) {
                    e.preventDefault();
                    // check the post param on url
                    var post_id = urlParams.get('post');
                    if ( ! post_id ) {
                        alert('Publish the post before opening the builder.');
                        return;
                    } else{
                        window.location.href = builder_link;
                    }
                });
            }

            if( $action === 'ultimate-builder' ){
                // add the builder container
                this.$el.append('<div id="app"></div>');

                // Start the external builder script
                this.$el.builder({
                    groups: this.model.get('groups'),
                    uf_field_model: uf_field_model,
                    initial_components_data: components_data,
                    builder_data: builder_data,
                    theme_styles: this.model.datastore.get( field_name + '_theme_styles' ),
                    theme_scripts: this.model.datastore.get( field_name + '_theme_scripts' ),
                    gjs_plugins: this.model.datastore.get( field_name + '_gjs_plugins' )
                });

                // Configure WordPress Heartbeat for post lock monitoring
                jQuery(document).ready(function($) {
                    if (typeof wp !== 'undefined' && wp.heartbeat && $('#post-lock-dialog').length) {
                        // Force start the heartbeat
                        wp.heartbeat.connectNow();
                        // Set the heartbeat interval to 15 seconds for post lock dialogs
                        wp.heartbeat.interval(15);
                    } else {
                        console.error('Heartbeat not available:', {
                            wp: typeof wp !== 'undefined',
                            heartbeat: typeof wp !== 'undefined' && wp.heartbeat,
                            dialog: $('#post-lock-dialog').length
                        });
                    }
                });
            }

            return this;
        }
    });    
})(jQuery);
