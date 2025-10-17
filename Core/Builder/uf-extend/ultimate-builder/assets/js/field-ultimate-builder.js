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
            var $action = urlParams.get('action'); // edit or add
            $action = $action ? $action : 'edit';

            if( $action === 'edit' ){
                // add the builder link
                this.$el.append('<p><a href="' + builder_link + '" class="button button-secondary">Open Builder Interface</a></p>');
            } else {
                // add the builder container
                this.$el.append('<div id="app"></div>');
                
                console.log( builder_data );
                console.log( components_data );

                // Start the external builder script
                this.$el.builder({
                    groups: this.model.get('groups'),
                    uf_field_model: uf_field_model,
                    initial_components_data: components_data,
                    builder_data: builder_data
                });
            }

            return this;
        }
    });

})(jQuery);
