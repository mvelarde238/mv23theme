/**
 * GJS Datastore Undo Plugin
 *
 * Injects UltimateFields datastore changes into the native GrapesJS UndoManager
 * stack so that Ctrl+Z / Ctrl+Y and the History panel cover field edits made
 * in the component-settings sidebar.
 *
 * Strategy: register a custom backbone-undo type ('datastoreChange') and push
 * plain-object entries into the native stack.  backbone-undo's collection
 * converts them to proper entry models (its internal I class), which guarantees
 * that undo()/redo() dispatch through the C() function with the correct
 * undoTypes reference – avoiding instanceof mismatches between the bundled
 * Backbone and the global window.Backbone.
 */
window.gjsDatastoreUndo = function (editor) {

    /* ------------------------------------------------------------------ */
    /*  Backbone-undo internals                                            */
    /* ------------------------------------------------------------------ */

    /** The raw backbone-undo UndoManager instance. */
    const um    = editor.UndoManager.getInstance();

    /** The internal stack (a Backbone.Collection of entry models). */
    const stack = editor.UndoManager.getStack();

    /** The undoTypes registry – must be stored in every entry's attributes
     *  so that the dispatch function C() can look up handlers. */
    const undoTypes = um.undoTypes;

    /**
     * Sequential counter for our magicFusionIndex values.
     * We use a string prefix ('ds_') so our indices never collide with
     * backbone-undo's native integer counter (b()), which independently
     * increments 0, 1, 2, …  String keys are treated as distinct from
     * integer keys by Object.keys() and Backbone's where().
     */
    let _dsSeq = 0;
    function nextFusionIndex() {
        return 'ds_' + (++_dsSeq);
    }

    /**
     * Fusion window (ms): consecutive datastore changes on the same
     * component within this interval are merged into a single undo entry
     * instead of creating one entry per keystroke.
     */
    var FUSION_WINDOW = 1000;

    /**
     * Per-component merge state.
     * { [compId]: { lastTime, stackIndex, fusionIndex } }
     */
    var _fusionState = {};

    /** Reference to editor config where temporalCompStore lives. */
    const editorConfig = editor.getConfig();

    /* ------------------------------------------------------------------ */
    /*  Patch getGroupedStack for correct chronological ordering            */
    /* ------------------------------------------------------------------ */

    /**
     * getGroupedStack() groups entries by magicFusionIndex and returns them
     * in Object.keys() order.  Integer keys are sorted ascending first,
     * then string keys in insertion order — so mixed native (int) and
     * datastore (string) indices break chronological display.
     *
     * We monkey-patch getGroupedStack to sort the result by each group's
     * `index` (= stack position of its last action), which IS chronological.
     */
    var _origGetGroupedStack = editor.UndoManager.getGroupedStack
        .bind(editor.UndoManager);
    editor.UndoManager.getGroupedStack = function () {
        var groups = _origGetGroupedStack();
        groups.sort(function (a, b) { return a.index - b.index; });
        return groups;
    };

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                            */
    /* ------------------------------------------------------------------ */

    /**
     * Find a GrapesJS component by its __tempID.
     */
    function findComponentByTempId(tempId) {
        let found = null;
        const wrapper = editor.DomComponents.getWrapper();
        if (!wrapper) return null;
        wrapper.onAll(function (comp) {
            if (comp.attributes && comp.attributes.__tempID === tempId) {
                found = comp;
            }
        });
        return found;
    }

    /**
     * Re-render a component's canvas view (if it exists).
     */
    function rerenderComponent(compId) {
        const comp = findComponentByTempId(compId);
        if (comp && comp.view && typeof comp.view.render === 'function') {
            try { comp.view.render(); } catch (e) { /* noop */ }
        }
    }

    /**
     * Deep-clone a value so the snapshot is immutable.
     */
    function snapshot(val) {
        if (val == null || typeof val !== 'object') return val;
        try { return JSON.parse(JSON.stringify(val)); } catch (e) { return val; }
    }

    /* ------------------------------------------------------------------ */
    /*  Force sidebar re-render after undo/redo                             */
    /* ------------------------------------------------------------------ */

    /**
     * After a datastore.set() during undo/redo the cached GroupView's DOM
     * inputs still hold stale values (UF views bind one-way: input → datastore).
     * Complex fields like WYSIWYG/TinyMCE cannot be patched individually.
     *
     * The reliable fix is to invalidate the cached view and force a full
     * deselect → re-select cycle.  The fresh GroupView reads current values
     * from the datastore, so every field type is guaranteed to be correct.
     *
     * For components that are NOT currently selected we just delete the
     * cache entry; the next time the user clicks the component it will
     * take the "first-time render" path.
     */
    function forceReselectComponent(compId) {
        var vc = editorConfig.viewCache;

        var selected = editor.getSelected();
        var isActive = selected
            && selected.attributes
            && selected.attributes.__tempID === compId;

        if (isActive) {
            // Deselect triggers component:deselected which detaches the
            // cached DOM, unbinds the change handler and destroys MCE.
            editor.selectRemove(selected);

            // Delete cache so the next select takes the fresh-render path.
            if (vc && vc[compId]) delete vc[compId];

            // Re-select immediately — a brand-new GroupView is created,
            // rendered with current datastore values, and appended to the
            // sidebar.  All field types (including WYSIWYG) are correct.
            editor.select(selected);
        } else {
            // Not the active component: just drop the stale cache entry.
            if (vc && vc[compId]) delete vc[compId];
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Register custom undo type                                          */
    /* ------------------------------------------------------------------ */

    um.addUndoType('datastoreChange', {
        /** Called by C() when undoing a datastoreChange entry. */
        undo: function (object, before, after, options) {
            // Pause tracking so the debounced changeHandler (100 ms) in
            // gjs-extend-components and any 'datastoreChanged' listeners
            // that call component.set() don't create ghost native entries.
            var wasTracking = stack.track;
            stack.track = false;

            if (object && before) {
                object.set(before);
            }
            if (options && options.compId) {
                rerenderComponent(options.compId);
                forceReselectComponent(options.compId);
            }

            // Restore tracking after the debounced handler has settled,
            // then refresh the History panel GUI.
            setTimeout(function () {
                stack.track = wasTracking;
                editor.trigger('update');
            }, 150);

            // Decrement dirty counter (undoing a change = one less unsaved change)
            var em = editor.em || editor.getModel();
            if (em) {
                var curr = em.get('changesCount') || 0;
                if (curr > 0) em.set('changesCount', curr - 1);
            }
        },
        /** Called by C() when redoing a datastoreChange entry. */
        redo: function (object, before, after, options) {
            var wasTracking = stack.track;
            stack.track = false;

            if (object && after) {
                object.set(after);
            }
            if (options && options.compId) {
                rerenderComponent(options.compId);
                forceReselectComponent(options.compId);
            }

            setTimeout(function () {
                stack.track = wasTracking;
                editor.trigger('update');
            }, 150);

            // Re-increment dirty counter (redoing = change is back)
            var em = editor.em || editor.getModel();
            if (em) {
                var curr = em.get('changesCount') || 0;
                em.set('changesCount', curr + 1);
            }
        },
        /**
         * The 'on' handler is called by backbone-undo's L() for auto-tracking.
         * We never auto-track (we push entries manually), so return false.
         */
        on: function () { return false; }
    });

    /* ------------------------------------------------------------------ */
    /*  Core: add a datastore change to the native undo stack              */
    /* ------------------------------------------------------------------ */

    function pushToStack(compId, datastore, before, after, gjsComponentName = '') {
        var now = Date.now();
        var fs  = _fusionState[compId];

        // ── Merge path ──────────────────────────────────────────────
        // If the previous entry for this component is still the last
        // item in the stack AND we are within the fusion window, update
        // its `after` snapshot instead of creating a new entry.
        // This collapses an entire typing session into one undo step.
        if (fs
            && (now - fs.lastTime) < FUSION_WINDOW
            && fs.stackIndex === stack.length - 1
            && fs.stackIndex === stack.pointer) {

            var lastEntry = stack.at(fs.stackIndex);
            if (lastEntry && lastEntry.get('type') === 'datastoreChange') {
                var existingBefore = lastEntry.get('before');
                var existingAfter  = lastEntry.get('after');

                // Keep the earliest `before` per key (original state)
                for (var key in before) {
                    if (!(key in existingBefore)) {
                        existingBefore[key] = before[key];
                    }
                }
                // Always update `after` to the latest value
                for (var key in after) {
                    existingAfter[key] = after[key];
                }

                fs.lastTime = now;
                editor.trigger('update');
                return;
            }
        }

        // ── Normal push (no merge) ──────────────────────────────────

        // Trim any redo entries that sit above the current pointer
        // (same logic backbone-undo uses internally in L())
        while (stack.length - 1 > stack.pointer) {
            stack.pop();
        }

        var fusionIndex = nextFusionIndex();

        // Push a plain object – the stack collection will create a proper
        // backbone-undo entry model (I) from it via _prepareModel().
        // This avoids instanceof Backbone.Model mismatches between the
        // global Backbone and the bundled copy.
        stack.pointer = stack.length;
        stack.add({
            type:              'datastoreChange',
            object:            datastore,
            before:            before,
            after:             after,
            magicFusionIndex:  fusionIndex,
            undoTypes:         undoTypes,
            options:           { compId: compId, action: 'field-change', gjsComponentName: gjsComponentName }
        });

        // Respect maximum stack length (if set)
        if (stack.maximumStackLength && stack.length > stack.maximumStackLength) {
            stack.shift();
            stack.pointer--;
        }

        // Save fusion state for potential merging of subsequent changes
        _fusionState[compId] = {
            lastTime:   now,
            stackIndex: stack.length - 1,
            fusionIndex: fusionIndex
        };

        // Notify the editor so the React History panel refreshes immediately.
        editor.trigger('update');

        // Increment the editor's dirty counter so that datastore changes
        // are included in the unsaved-changes / beforeunload warning.
        var em = editor.em || editor.getModel();
        if (em) {
            var curr = em.get('changesCount') || 0;
            em.set('changesCount', curr + 1);
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Track a datastore's changes                                        */
    /* ------------------------------------------------------------------ */

    /**
     * Attach a `change` listener to a datastore that records undo entries.
     * Called once per component (when the datastore is created).
     */
    function trackDatastore(compId, datastore, gjsComponentName = '') {
        datastore.on('change', function () {
            // Skip if backbone-undo is currently executing an undo/redo —
            // prevents re-recording the change we are restoring.
            if (stack.isCurrentlyUndoRedoing) return;

            // Skip if the native UndoManager tracking is paused
            // (e.g. during column resize where stop()/start() is used)
            if (!stack.track) return;

            var changed = datastore.changedAttributes();
            if (!changed) return;

            // Filter out internal UF keys that start with "__"
            var keys = Object.keys(changed).filter(function (k) {
                return k.indexOf('__') !== 0;
            });
            if (keys.length === 0) return;

            // Build before/after snapshots only for the changed keys
            var prev = datastore.previousAttributes();
            var before = {};
            var after  = {};
            for (var i = 0; i < keys.length; i++) {
                var k = keys[i];
                before[k] = snapshot(prev[k]);
                after[k]  = snapshot(changed[k]);
            }

            pushToStack(compId, datastore, before, after, gjsComponentName);
        });
    }

    /* ------------------------------------------------------------------ */
    /*  Hook into component lifecycle                                      */
    /* ------------------------------------------------------------------ */

    /**
     * After gjs-extend-components has created the datastore (it fires first
     * because it is registered as a plugin before this one), we start
     * tracking the datastore for undo purposes.
     */
    editor.on('component:create', function (gjsComponent) {
        var tempId = gjsComponent.attributes && gjsComponent.attributes.__tempID;
        if (!tempId) return;

        var store = editorConfig.temporalCompStore || {};
        var model = store[tempId];
        if (model && model.datastore) {
            var gjsComponentName = gjsComponent.attributes?.name || gjsComponent.attributes.type;
            trackDatastore(tempId, model.datastore, gjsComponentName);
        }
    });
};
