/**
 * GJS Dynamic Data Selector Plugin
 *
 * Renders a token-picker widget below any UF field that has `dynamic_data: true`.
 * Tokens come from BUILDER_GLOBALS.context (the Handlebars context object).
 *
 * The widget is inserted once per field when a component is first opened, and
 * re-attached from cache on subsequent opens (aligned with the viewCache strategy
 * used in gjs-extend-components.js).
 *
 * Supported field types: Text, Textarea, WYSIWYG.
 * Insertion strategy per type:
 *   - text / textarea : insertAtCursor() using selectionStart/selectionEnd
 *   - wysiwyg         : tinymce.insertContent() when editor is active,
 *                       otherwise insertAtCursor() on the plain <textarea>
 */
window.gjsDynamicDataSelector = function (editor) {

    // Labels for the ui, using the editor's translator for internationalization.
    var __ = editor.createTranslator(editor, 'ultimate_builder');

    // =========================================================================
    // Helpers
    // =========================================================================

    /**
     * Flattens the nested context object into an array of { label, token } items.
     * Arrays and deeply nested objects (depth > 2) are skipped.
     *
     * @param  {Object} ctx    Context object (BUILDER_GLOBALS.context)
     * @param  {string} prefix Dot-notation prefix accumulated during recursion
     * @param  {number} depth  Current recursion depth (starts at 0)
     * @return {Array}         [{ label: 'post.title', token: '{{post.title}}' }, ...]
     */
    function flattenContext(ctx, prefix, depth) {
        prefix = prefix || '';
        depth  = depth  || 0;
        var items = [];

        if (depth > 2 || typeof ctx !== 'object' || ctx === null || Array.isArray(ctx)) {
            return items;
        }

        Object.keys(ctx).forEach(function (key) {
            var value    = ctx[key];
            var fullPath = prefix ? prefix + '.' + key : key;

            if (value !== null && typeof value === 'object' && !Array.isArray(value)) {
                // Recurse into nested objects
                var nested = flattenContext(value, fullPath, depth + 1);
                items = items.concat(nested);
            } else if (!Array.isArray(value)) {
                items.push({ label: fullPath, token: '{{' + fullPath + '}}' });
            }
        });

        return items;
    }

    /**
     * Inserts text at the cursor position of a plain input/textarea element.
     * Falls back to appending if the browser does not support selectionStart.
     */
    function insertAtCursor(el, text) {
        if (el.selectionStart !== undefined) {
            var start = el.selectionStart;
            var end   = el.selectionEnd;
            var val   = el.value;
            el.value  = val.substring(0, start) + text + val.substring(end);
            el.selectionStart = el.selectionEnd = start + text.length;
        } else {
            el.value += text;
        }
    }

    /**
     * Inserts a token into the field identified by $fieldWrapper.
     * Detects field type via CSS class and uses the appropriate strategy.
     */
    function insertToken($fieldWrapper, token) {
        if ($fieldWrapper.hasClass('uf-field-type-wysiwyg')) {
            // Try active TinyMCE first
            var $editorWrap = $fieldWrapper.find('.wp-editor-wrap');
            var inserted    = false;

            $editorWrap.each(function () {
                var mceId = window.jQuery(this).find('[id$="_ifr"]').attr('id');
                if (mceId) {
                    var editorId = mceId.replace('_ifr', '');
                    if (typeof tinymce !== 'undefined' && tinymce.get(editorId)) {
                        tinymce.get(editorId).insertContent(token);
                        inserted = true;
                        return false; // break jQuery.each
                    }
                }
            });

            if (!inserted) {
                // Fallback: plain textarea (HTML mode)
                var $textarea = $fieldWrapper.find('textarea');
                if ($textarea.length) {
                    insertAtCursor($textarea[0], token);
                    $textarea.trigger('change').trigger('keyup');
                }
            }

        } else {
            // Text / Textarea
            var $input = $fieldWrapper.find('input[type="text"], textarea').first();
            if ($input.length) {
                insertAtCursor($input[0], token);
                $input.trigger('change').trigger('keyup');
            }
        }
    }

    // =========================================================================
    // Widget builder
    // =========================================================================

    /**
     * Builds the token list HTML grouped by top-level namespace.
     * post.meta.* entries are placed in a collapsible sub-group.
     */
    function buildTokenList(tokens) {
        var $list = window.jQuery('<div class="uf-dd-token-list"></div>');

        // Group by top-level key
        var groups = {};
        tokens.forEach(function (item) {
            var parts    = item.label.split('.');
            var groupKey = parts[0];
            if (!groups[groupKey]) groups[groupKey] = [];
            groups[groupKey].push(item);
        });

        Object.keys(groups).forEach(function (groupKey) {
            var items = groups[groupKey];

            if (items.length === 1 && items[0].label === groupKey) {
                // Single flat token (e.g. current_year)
                var $token = window.jQuery('<button type="button" class="uf-dd-token"></button>')
                    .text(items[0].token)
                    .attr('data-token', items[0].token);
                $list.append($token);
                return;
            }

            // Group header + collapsible items
            var $group      = window.jQuery('<div class="uf-dd-group"></div>');
            var $groupTitle = window.jQuery('<button type="button" class="uf-dd-group-title"></button>')
                .text(groupKey)
                .append(window.jQuery('<span class="uf-dd-group-arrow">▾</span>'));
            var $groupItems = window.jQuery('<div class="uf-dd-group-items"></div>');

            items.forEach(function (item) {
                var $token = window.jQuery('<button type="button" class="uf-dd-token"></button>')
                    .text(item.token)
                    .attr('data-token', item.token);
                $groupItems.append($token);
            });

            $groupTitle.on('click', function (e) {
                e.stopPropagation();
                $groupItems.toggleClass('uf-dd-group-items--open');
                $groupTitle.toggleClass('uf-dd-group-title--open');
            });

            $group.append($groupTitle).append($groupItems);
            $list.append($group);
        });

        return $list;
    }

    /**
     * Creates and attaches the dynamic data selector widget to $fieldWrapper.
     */
    function attachSelector($fieldWrapper) {
        // Avoid double-attaching
        if ($fieldWrapper.find('.uf-dynamic-data-selector').length) return;

        var tokens = flattenContext(BUILDER_GLOBALS.context || {});
        if (!tokens.length) return;

        var $selector  = window.jQuery('<div class="uf-dynamic-data-selector"></div>');
        var $toggle    = window.jQuery(
            '<button type="button" class="uf-dd-toggle">' +
            '<span class="dashicons dashicons-database-view"></span> ' + __('Insert Dynamic Data') +
            '</button>'
        );
        var $dropdown  = window.jQuery('<div class="uf-dd-dropdown" hidden></div>');
        var $tokenList = buildTokenList(tokens);

        $dropdown.append($tokenList);
        $selector.append($toggle).append($dropdown);

        // Prevent all widget buttons from stealing focus away from the active input.
        // mousedown fires before focus transfer; preventDefault() cancels it.
        $toggle.on('mousedown', function (e) { e.preventDefault(); });
        $dropdown.on('mousedown', 'button', function (e) { e.preventDefault(); });

        // Toggle dropdown open/close
        $toggle.on('click', function (e) {
            e.stopPropagation();
            var isOpen = !$dropdown.attr('hidden');
            if (isOpen) {
                $dropdown.attr('hidden', '');
                $toggle.removeClass('uf-dd-toggle--open');
            } else {
                $dropdown.removeAttr('hidden');
                $toggle.addClass('uf-dd-toggle--open');
                // Flip upward if the dropdown overflows the bottom of the viewport.
                // getBoundingClientRect() after un-hiding forces a synchronous layout read.
                var rect = $dropdown[0].getBoundingClientRect();
                if (rect.bottom > window.innerHeight) {
                    $dropdown.addClass('uf-dd-dropdown--flip');
                } else {
                    $dropdown.removeClass('uf-dd-dropdown--flip');
                }
            }
        });

        // Insert token on click
        $dropdown.on('click', '.uf-dd-token', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var token = window.jQuery(this).attr('data-token');
            insertToken($fieldWrapper, token);
            $dropdown.attr('hidden', '');
            $toggle.removeClass('uf-dd-toggle--open');
        });

        // Close when clicking outside
        window.jQuery(document).on('click.uf-dd-' + $fieldWrapper.attr('class').replace(/\s+/g, '-'), function () {
            $dropdown.attr('hidden', '');
            $toggle.removeClass('uf-dd-toggle--open');
        });

        $fieldWrapper.append($selector);
    }

    // =========================================================================
    // Hook into builder lifecycle
    // =========================================================================

    /**
     * Reads a property from a field that may be a plain object or a Backbone Model.
     */
    function fieldGet(field, key) {
        return (field && typeof field.get === 'function') ? field.get(key) : field[key];
    }

    /**
     * Recursively collects qualified CSS selectors for fields that have
     * dynamic_data_selector: true. Walking into sub-fields of complex/tab/repeater
     * fields produces scoped selectors (e.g. '.uf-field-name-heading .uf-field-name-content')
     * so that two sub-fields with the same name inside different complex fields
     * are always resolved to the correct DOM element.
     *
     * @param  {Array|Backbone.Collection} fields
     * @param  {string}                    prefix  CSS selector prefix from parent scope
     * @return {string[]}  Qualified CSS selector strings
     */
    function collectDynamicSelectors(fields, prefix) {
        var result = [];
        prefix = prefix || '';
        if (!fields) return result;

        var arr = (fields.models) ? fields.models : (Array.isArray(fields) ? fields : []);

        arr.forEach(function (field) {
            if (!field) return;

            var name        = fieldGet(field, 'name');
            var dynamicData = fieldGet(field, 'dynamic_data_selector');
            var type        = (fieldGet(field, 'type') || '').toLowerCase();
            var selfSelector = (prefix ? prefix + ' ' : '') + '.uf-field-name-' + name;

            if (dynamicData && name) {
                result.push(selfSelector);
            }

            // Recurse into sub-fields of complex / tab / repeater fields.
            // The sub-fields live at group.fields (plain array) on the Backbone Model.
            if (type === 'complex' || type === 'tab' || type === 'repeater') {
                var group     = fieldGet(field, 'group') || fieldGet(field, 'groupModel');
                var subFields = (group && Array.isArray(group.fields)) ? group.fields : null;
                if (subFields) {
                    result = result.concat(collectDynamicSelectors(subFields, selfSelector));
                }
            }
        });

        return result;
    }

    // =========================================================================
    // Hook into builder lifecycle
    // =========================================================================

    /**
     * When a component's datastore panel opens, find all fields with
     * dynamic_data_selector: true and attach the selector widget.
     *
     * Uses a MutationObserver instead of a fixed setTimeout so the widget
     * is attached as soon as each target field element appears in the DOM,
     * regardless of how long UF takes to render tabs or other sub-widgets.
     */
    editor.on('openDatastore', function (group_model) {
        var fields           = group_model.get('fields') || [];
        var dynamicSelectors = collectDynamicSelectors(fields);

        if (!dynamicSelectors.length) return;

        var $wrapper  = window.jQuery('#component-settings');
        var remaining = dynamicSelectors.slice(); // unattached selectors

        function tryAttach() {
            remaining = remaining.filter(function (selector) {
                var $fieldWrap = $wrapper.find(selector).first();
                if ($fieldWrap.length) {
                    attachSelector($fieldWrap);
                    return false; // attached — remove from list
                }
                return true; // still pending
            });
            return remaining.length === 0;
        }

        // Fast path: cached views already have the DOM when the event fires.
        if (tryAttach()) return;

        // Slow path: first-time render — DOM appears after the event.
        // Watch #component-settings for any structural change and try again.
        if (!window.MutationObserver || !$wrapper.length) {
            setTimeout(tryAttach, 400); // basic fallback
            return;
        }

        var observer = new MutationObserver(function () {
            if (tryAttach()) observer.disconnect();
        });

        observer.observe($wrapper[0], { childList: true, subtree: true });

        // Safety valve: stop watching after 2 s even if some selectors were never found.
        setTimeout(function () {
            observer.disconnect();
            if (remaining.length) tryAttach();
        }, 2000);
    });
};
