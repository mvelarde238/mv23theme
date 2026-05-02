window['Handlebars'] = (function(){
    const Handlebars = {};

    Handlebars.parse = function(string){
        const context = BUILDER_GLOBALS.context;

		const resolvePath = (path, ctx) => {
		    return path.split('.').reduce((acc, key) => {
		        return (acc !== null && acc !== undefined && typeof acc === 'object') ? acc[key] : undefined;
		    }, ctx);
		};

		return string.replace(/\{\{([^}]+)\}\}/g, (match, token) => {
	        const value = resolvePath(token.trim(), context);
	        if (value === undefined || value === null) return match;
	        if (Array.isArray(value)) return value.join(', ');
	        return String(value);
	    });
    }

    return Handlebars;
})();