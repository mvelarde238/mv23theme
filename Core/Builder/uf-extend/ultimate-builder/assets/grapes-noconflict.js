(function() {
    'use strict';
    
    // Guardar las versiones originales de WordPress antes de que GrapesJS las sobrescriba
    var wpBackbone = window.Backbone;
    var wpUnderscore = window._;
    var wpJQuery = window.jQuery;
    
    // Verificar que GrapesJS se haya cargado
    if (!window.grapesjs) {
        console.error('GrapesJS not loaded. Make sure grapes.min.js is loaded before this script.');
        return;
    }
    
    // Crear un espacio de nombres aislado para GrapesJS
    window.GrapesJSIsolated = {
        Backbone: window.Backbone,
        _: window._,
        grapesjs: window.grapesjs,
        jQuery: window.jQuery || window.$
    };
    
    // Restaurar las versiones originales de WordPress
    if (wpBackbone) {
        window.Backbone = wpBackbone;
    }
    if (wpUnderscore) {
        window._ = wpUnderscore;
    }
    if (wpJQuery) {
        window.jQuery = wpJQuery;
        window.$ = wpJQuery;
    }
    
    // Asegurar que wp.media y otros objetos de WordPress sigan funcionando
    if (window.wp) {
        // Restaurar wp.media si existe
        if (window.wp.media && wpBackbone) {
            // Asegurar que wp.media.view mantenga su funcionalidad
            if (window.wp.media.view) {
                window.wp.media.view.l10n = window.wp.media.view.l10n || {};
                
                // Verificar que los métodos críticos existan
                if (window.wp.media.view.Spinner && !window.wp.media.view.Spinner.prototype.show) {
                    console.warn('wp.media.view.Spinner.show method missing, restoring...');
                    window.wp.media.view.Spinner.prototype.show = function() {
                        this.$el.show();
                        return this;
                    };
                    window.wp.media.view.Spinner.prototype.hide = function() {
                        this.$el.hide();
                        return this;
                    };
                }
            }
        }
        
        // Restaurar otros componentes de wp si es necesario
        if (window.wp.backbone && wpBackbone) {
            window.wp.backbone.View = wpBackbone.View;
            window.wp.backbone.Model = wpBackbone.Model;
            window.wp.backbone.Collection = wpBackbone.Collection;
        }
    }
    
    // Función helper para usar GrapesJS de forma aislada
    window.createIsolatedGrapesJS = function(config) {
        // Temporalmente restaurar las librerías de GrapesJS
        var originalBackbone = window.Backbone;
        var originalUnderscore = window._;
        var originalJQuery = window.jQuery;
        
        window.Backbone = window.GrapesJSIsolated.Backbone;
        window._ = window.GrapesJSIsolated._;
        if (window.GrapesJSIsolated.jQuery) {
            window.jQuery = window.GrapesJSIsolated.jQuery;
            window.$ = window.GrapesJSIsolated.jQuery;
        }
        
        // Crear la instancia de GrapesJS
        var editor = window.GrapesJSIsolated.grapesjs.init(config);
        
        // Restaurar las versiones de WordPress
        window.Backbone = originalBackbone;
        window._ = originalUnderscore;
        if (originalJQuery) {
            window.jQuery = originalJQuery;
            window.$ = originalJQuery;
        }
        
        return editor;
    };
    
    // Exponer grapesjs de forma segura
    window.grapesjs = {
        init: window.createIsolatedGrapesJS,
        // Exponer otros métodos de GrapesJS si es necesario
        plugins: window.GrapesJSIsolated.grapesjs.plugins || {},
        $: window.GrapesJSIsolated.grapesjs.$ || null
    };
    
    console.log('GrapesJS noConflict mode activated. Use window.grapesjs.init() or window.createIsolatedGrapesJS()');
    
})();
