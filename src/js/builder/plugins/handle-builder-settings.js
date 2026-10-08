window.handleBuilderSettings = function (editor) {
    const editorConfig = editor.getConfig();
    const __type = 'builder-settings';
    const Group_Model = UltimateFields.Container.Group.Model;
    const Group_View = UltimateFields.Container.Group.View;

    // find the group settings associated with the builder settings type
    const groups = editorConfig.groups || [],
        groupSettings = groups.find(g => g.id === __type);

    if (!groupSettings) {
        console.error('[handleBuilderSettings] No se encontró la definición de grupo "' + __type + '".');
        return;
    }

    // create the builder settings model using the group settings
    const builderSettingsModel = new Group_Model(_.extend({ __type: __type }, groupSettings));

    // create a datastore for the builder settings model and associate it.
    // Note: builder-settings doesn't persist through components_data (it's
    // saved separately as a WP option, see Builder_Settings::change_datastore),
    // so there's nothing to restore here manually. The group's fields already
    // carry the currently saved values as their `default_value` (set in
    // Builder_Settings::get_fields() via get_option('builder_settings', ...)),
    // and UltimateFields.Field.setDatastore() -> useDefaultValueIfNeeded()
    // writes that default into the datastore as soon as it's attached below.
    const defaults = { __type: __type };
    const datastore = new UltimateFields.Datastore(defaults);
    builderSettingsModel.setDatastore(datastore);

    // Expose only the actual settings values (not the {__type, builder_settings}
    // datastore wrapper), so consumers like gjs-section.js can read e.g.
    // editorConfig.builderSettings.default_sections_padding directly.
    // gjs-commands.js re-wraps this value with __type when saving, since
    // that's the shape the backend expects (see Field.php::save()).
    function getBuilderSettingsValue() {
        return datastore.get('builder_settings') || {};
    }

    // keep editorConfig.builderSettings always in sync with the datastore,
    // even before the user changes anything, so saving right away persists
    // the current values (already populated above via default_value) instead
    // of an empty object. Registered once here (not inside
    // 'global-settings:open') to avoid stacking a new 'change' listener on
    // every panel open.
    editorConfig.builderSettings = getBuilderSettingsValue();
    datastore.on('change', () => {
        editorConfig.builderSettings = getBuilderSettingsValue();
    });

    // The view is created once and reused on every open instead of being
    // recreated each time, which would leak the previous instance and its
    // bound TinyMCE/field listeners.
    let groupSettingView = null;

    // render (or re-attach) the view inside the provided container
    editor.on('global-settings:open', (payload) => {
        const container = payload && payload.container;
        if (!container) {
            console.error('[handleBuilderSettings] "global-settings:open" fue disparado sin un "container" válido.');
            return;
        }

        if (!groupSettingView) {
            groupSettingView = new Group_View({ model: builderSettingsModel });
            groupSettingView.render();
        } else if (groupSettingView.$el.parent().length) {
            // Detach (not remove) from its previous location to preserve
            // bound events/field state before moving it into the container.
            groupSettingView.$el.detach();
        }

        groupSettingView.$el.appendTo(container);

        // Trigger resize to fix grid field widths
        window.dispatchEvent(new Event('resize'));
    });

    // editor.on('builder-settings:close', ({ container }) => {});
};