window.handleThemeColors = function (editor, options) {
    // Store original datalist only once to avoid stacking duplicates
    let originalDatalist = null;

    editor.on('load theme-colors:update', (obj) => {
        implement_theme_colors_on_colorpicker_swatches(editor);
        // implement_theme_colors_on_stylemanager_datalist(editor);
        inject_css_variables_in_style_manager(editor);
    });

    function inject_css_variables_in_style_manager(editor) {
        const theme_colors_raw = BUILDER_GLOBALS.theme_colors || [];

        if (!theme_colors_raw || theme_colors_raw.length === 0) {
            return;
        }

        const cssProperties = [];
        const requireVariations = ['--primary-color'];

        theme_colors_raw.forEach(color_item => {
            // Only process color type items
            if (color_item.__type === 'color' && color_item.css_property && color_item.color) {
                const base_var = color_item.css_property;
                
                // Add base color variable
                cssProperties.push(`${base_var}:${color_item.color}`);

                // Add variations if enabled or is primary or secondary
                if (color_item.customize_variations || requireVariations.includes(base_var)) {
                    const light_value = color_item.light || 70;
                    const lighter_value = color_item.lighter || 94;
                    const dark_value = color_item.dark || 15;

                    cssProperties.push(`${base_var}-light:color-mix(in srgb, var(${base_var}), white ${light_value}%)`);
                    cssProperties.push(`${base_var}-lighter:color-mix(in srgb, var(${base_var}), white ${lighter_value}%)`);
                    cssProperties.push(`${base_var}-dark:color-mix(in srgb, var(${base_var}), black ${dark_value}%)`);
                }
            }
        });

        if (cssProperties.length === 0) {
            return;
        }

        // Create CSS rule
        const cssRule = `:root{${cssProperties.join(';')}}`;

        // Inject in canvas frame (where components are rendered)
        const canvas = editor.Canvas;
        if (canvas) {
            const canvasDoc = canvas.getDocument();
            if (canvasDoc) {
                let styleEl = canvasDoc.getElementById('theme-colors-vars');
                
                if (!styleEl) {
                    styleEl = canvasDoc.createElement('style');
                    styleEl.id = 'theme-colors-vars';
                    canvasDoc.head.appendChild(styleEl);
                }
                
                styleEl.textContent = cssRule;
            }
        }

        // Also inject in the main document (for style manager preview)
        let mainStyleEl = document.getElementById('theme-colors-vars-main');
        
        if (!mainStyleEl) {
            mainStyleEl = document.createElement('style');
            mainStyleEl.id = 'theme-colors-vars-main';
            document.head.appendChild(mainStyleEl);
        }
        
        mainStyleEl.textContent = cssRule;
    }

    function implement_theme_colors_on_stylemanager_datalist(editor) {
        const theme_colors_raw = BUILDER_GLOBALS.theme_colors || [];

        if (!theme_colors_raw || theme_colors_raw.length === 0) {
            return;
        }

        // Process color items
        const themeColors = [];
        const cssVarsOptions = [];
        const requireVariations = ['--primary-color'];

        theme_colors_raw.forEach(color_item => {
            // Only process color type items
            if (color_item.__type === 'color') {
                // Add to theme colors array
                if (color_item.color) {
                    if (color_item.css_property) {
                        // Has CSS variable - use var as value, hex as label
                        themeColors.push({
                            value: `var(${color_item.css_property})`,
                            label: color_item.color
                        });
                    } else {
                        // No CSS variable - use hex string
                        themeColors.push(color_item.color);
                    }
                }

                // Add to CSS vars if has css_property
                if (color_item.css_property) {
                    const base_var = color_item.css_property;

                    // Add base variable row
                    cssVarsOptions.push([`var(${base_var})`]);

                    // Add variations row if enabled or is primary or secondary
                    if (color_item.customize_variations || requireVariations.includes(base_var)) {
                        cssVarsOptions.push([
                            { value: `var(${base_var}-lighter)`, label: 'lighter' },
                            { value: `var(${base_var}-light)`, label: 'light' },
                            { value: `var(${base_var}-dark)`, label: 'dark' }
                        ]);
                    }
                }
            }
        });

        // Format THEME COLORS options (group colors in batches of 3)
        let groupSize = 3;
        if (themeColors.length <= groupSize) groupSize = 2;

        const themeColorsOptions = [];
        for (let i = 0; i < themeColors.length; i += groupSize) {
            themeColorsOptions.push(themeColors.slice(i, i + groupSize));
        }

        const styleManager = editor.StyleManager;

        // Store original datalist on first run
        if (!originalDatalist) {
            const currentDatalist = styleManager._config.globalDatalist['color'] || [];
            // Deep clone to preserve original
            originalDatalist = JSON.parse(JSON.stringify(currentDatalist));
        }

        // Always start from original datalist to avoid stacking duplicates
        const baseDatalist = JSON.parse(JSON.stringify(originalDatalist));
        
        // Filter out our custom groups from the base
        const existingColors = baseDatalist.filter(group => {
            return group.title !== 'BRAND' && 
                   group.title !== 'CSS VARIABLES' && 
                   group.title !== 'THEME COLORS' && 
                   group.title !== 'CSS VARS';
        });

        // Build the new datalist completely from scratch
        const newDatalist = [];

        // Add THEME COLORS group if we have hex colors
        if (themeColorsOptions.length > 0) {
            newDatalist.push({
                title: 'THEME COLORS',
                options: themeColorsOptions
            });
        }

        // Add CSS VARS group if we have css variables
        if (cssVarsOptions.length > 0) {
            newDatalist.push({
                title: 'CSS VARS',
                options: cssVarsOptions
            });
        }

        // Set the datalist with our groups first, then existing groups
        styleManager._config.globalDatalist['color'] = [
            ...newDatalist,
            ...existingColors
        ];

        // Force Style Manager to refresh if there's a selected component
        // const selected = editor.getSelected();
        // if (selected) {
        //     styleManager.render();
        // }
    }

    function implement_theme_colors_on_colorpicker_swatches(editor) {
        const theme_colors_raw = BUILDER_GLOBALS.theme_colors || [];

        if (!theme_colors_raw || theme_colors_raw.length === 0) {
            return;
        }

        // Process color items
        const themeColors = [];
        const requireVariations = ['--primary-color'];

        theme_colors_raw.forEach(color_item => {
            // Only process color type items
            if (color_item.__type === 'color') {
                // Add to theme colors array
                if (color_item.color) {
                    if (color_item.css_property) {
                        // Has CSS variable
                        const base_var = color_item.css_property;
                        themeColors.push(`var(${base_var})`);

                        // Add variations if enabled or is primary or secondary
                        if (color_item.customize_variations || requireVariations.includes(base_var)) {
                            themeColors.push(`var(${base_var}-lighter)`);
                            themeColors.push(`var(${base_var}-light)`);
                            themeColors.push(`var(${base_var}-dark)`);
                        }
                    } else {
                        // No CSS variable - use hex string
                        themeColors.push(color_item.color);
                    }
                }
            }
        });

        if (themeColors.length > 0) {
            editor.builderApp.setThemeColors(themeColors);
        }
    }
};