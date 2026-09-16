# Screen_Helper Class Documentation

## Overview

The `Screen_Helper` class extends WordPress's `WP_Screen` object with custom properties to easily detect when the current admin screen is the Ultimate Fields Builder editor.

## Features

- ✅ Automatically adds `is_uf_builder_editor` property to all `WP_Screen` objects
- ✅ Provides `builder_meta_field` and `builder_post_id` properties when in builder
- ✅ Static helper methods for standalone usage
- ✅ No manual initialization required (auto-initialized in `Ultimate_Builder::__construct()`)

## Available Properties

### `$screen->is_uf_builder_editor`
**Type:** `bool`  
**Description:** True if current screen is the Ultimate Fields Builder editor, false otherwise.

### `$screen->builder_meta_field`
**Type:** `string`  
**Description:** The meta field name being edited in the builder (empty string if not in builder).

### `$screen->builder_post_id`
**Type:** `int`  
**Description:** The post ID being edited in the builder (0 if not in builder).

## Usage Examples

### Example 1: Using WP_Screen Properties (Recommended)

```php
<?php
/**
 * Enqueue scripts only in the builder editor
 */
add_action('admin_enqueue_scripts', 'my_builder_scripts');
function my_builder_scripts($hook) {
    $screen = get_current_screen();
    
    if ($screen->is_uf_builder_editor) {
        wp_enqueue_script(
            'my-builder-cleanup',
            get_stylesheet_directory_uri() . '/assets/js/builder-cleanup.js',
            ['uf-core'],
            '1.0.0',
            true
        );
        
        // Pass data to JavaScript
        wp_localize_script('my-builder-cleanup', 'BUILDER_DATA', [
            'meta_field' => $screen->builder_meta_field,
            'post_id' => $screen->builder_post_id
        ]);
    }
}
```

### Example 2: Using Static Helper Method

```php
<?php
use Ultimate_Fields\Ultimate_Builder\Screen_Helper;

/**
 * Check builder status without WP_Screen object
 */
add_action('admin_init', 'my_early_builder_check');
function my_early_builder_check() {
    // This works even before get_current_screen() is available
    if (Screen_Helper::is_builder_editor()) {
        // Do something
    }
}
```

### Example 3: Conditional Content in Templates

```php
<?php
$screen = get_current_screen();

if ($screen->is_uf_builder_editor) {
    echo '<div class="builder-notice">You are editing: ' . esc_html($screen->builder_meta_field) . '</div>';
}
```

### Example 4: Adding Inline Scripts

```php
<?php
add_action('admin_footer', 'inject_builder_filters');
function inject_builder_filters() {
    $screen = get_current_screen();
    
    if (!$screen->is_uf_builder_editor) {
        return;
    }
    ?>
    <script>
    (function($) {
        'use strict';
        
        // Add cleanup filter for builder components
        UltimateFields.addFilter('builder_component_cleanup', function(data) {
            // Remove legacy properties
            if (data.builderComponent && 'posts_cached' in data.builderComponent) {
                delete data.builderComponent.posts_cached;
            }
            if (data.componentDataStore && 'posts_cached' in data.componentDataStore) {
                delete data.componentDataStore.posts_cached;
            }
        });
        
        console.log('✓ Builder cleanup filter registered');
        
    })(jQuery);
    </script>
    <?php
}
```

### Example 5: Using Helper Methods to Get Builder Info

```php
<?php
use Ultimate_Fields\Ultimate_Builder\Screen_Helper;

add_action('admin_notices', 'show_builder_info');
function show_builder_info() {
    if (!Screen_Helper::is_builder_editor()) {
        return;
    }
    
    $meta_field = Screen_Helper::get_builder_meta_field();
    $post_id = Screen_Helper::get_builder_post_id();
    
    echo '<div class="notice notice-info">';
    echo '<p>Editing meta field: <strong>' . esc_html($meta_field) . '</strong> ';
    echo 'for post ID: <strong>' . esc_html($post_id) . '</strong></p>';
    echo '</div>';
}
```

### Example 6: Conditional Asset Loading

```php
<?php
add_action('admin_enqueue_scripts', 'conditional_builder_assets');
function conditional_builder_assets($hook) {
    $screen = get_current_screen();
    
    // Regular admin scripts
    wp_enqueue_script('my-admin-script', '...', [], '1.0', true);
    
    // Builder-specific scripts
    if ($screen->is_uf_builder_editor) {
        wp_enqueue_script('builder-extensions', '...', ['uf-core'], '1.0', true);
        wp_enqueue_style('builder-custom-styles', '...', [], '1.0');
        
        // Add builder-specific data
        wp_add_inline_script('builder-extensions', sprintf(
            'const BUILDER_CONTEXT = {metaField: "%s", postId: %d};',
            esc_js($screen->builder_meta_field),
            (int) $screen->builder_post_id
        ), 'before');
    }
}
```

### Example 7: Debugging and Logging

```php
<?php
add_action('admin_init', 'log_builder_access');
function log_builder_access() {
    $screen = get_current_screen();
    
    if ($screen && $screen->is_uf_builder_editor) {
        error_log(sprintf(
            'Builder editor accessed: Meta=%s, Post ID=%d, User=%d',
            $screen->builder_meta_field,
            $screen->builder_post_id,
            get_current_user_id()
        ));
    }
}
```

## Static Methods Reference

### `Screen_Helper::init()`
Initializes the screen helper. Called automatically, no need to call manually.

### `Screen_Helper::is_builder_editor($screen = null)`
**Parameters:**
- `$screen` (WP_Screen|null) - Optional. Screen object to check. Defaults to current screen.

**Returns:** `bool` - True if builder editor, false otherwise.

### `Screen_Helper::get_builder_meta_field()`
**Returns:** `string` - The meta field being edited, or empty string.

### `Screen_Helper::get_builder_post_id()`
**Returns:** `int` - The post ID being edited, or 0.

## Migration Guide

### Before (Old Method)
```php
// ❌ Manual detection, error-prone
if ($screen && $screen->base === 'post' 
    && isset($_GET['action']) 
    && $_GET['action'] === 'ultimate-builder') {
    // Do something
}
```

### After (New Method)
```php
// ✅ Clean, reliable detection
if ($screen->is_uf_builder_editor) {
    // Do something
}
```

## Notes

- The `Screen_Helper::init()` is called automatically in `Ultimate_Builder::__construct()`, so you don't need to initialize it manually.
- Properties are always set on the `WP_Screen` object, even when not in builder (they'll be empty/false).
- All methods are static, so you can use them without instantiating the class.
- The `current_screen` action runs with priority 5 to ensure properties are available early.

## Support

For issues or questions, refer to the Ultimate Fields Builder documentation or the main theme documentation.
