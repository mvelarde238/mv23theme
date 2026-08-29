window['Handlebars'] = (function(){
    const Handlebars = {};

    // Private: resolves a dot-notation path against a context object.
    // Returns undefined if the path cannot be resolved.
    const _resolvePath = (path, ctx) => {
        return path.split('.').reduce((acc, key) => {
            return (acc !== null && acc !== undefined && typeof acc === 'object') ? acc[key] : undefined;
        }, ctx);
    };

    // Private: returns false for null, undefined, false, '', 0, '0', and empty arrays.
    const _isTruthy = (value) => {
        if (value === null || value === undefined || value === false || value === '' || value === 0 || value === '0') return false;
        if (Array.isArray(value) && value.length === 0) return false;
        return true;
    };

    // Private: applies a named filter to a string value.
    // Supported: uppercase, lowercase, capitalize, capitalize_words, truncate:N, number_format[:dec[:dec_sep[:thou_sep]]], slug, nl2br, spans[:class], join.
    // Array-aware filters (spans[:class[:sep]], join[:sep]) are handled in _resolveWithFilter before reaching here.
    const _applyFilter = (value, filterExpr) => {
        const colon = filterExpr.indexOf(':');
        const name  = (colon !== -1 ? filterExpr.slice(0, colon) : filterExpr).trim();
        const arg   = colon !== -1 ? filterExpr.slice(colon + 1) : null;

        switch (name) {
            case 'uppercase':      return value.toUpperCase();
            case 'lowercase':      return value.toLowerCase();
            case 'capitalize':     // Only capitalize first character
                return value.length === 0 ? value : value.charAt(0).toUpperCase() + value.slice(1);
            case 'capitalize_words':
                return value.replace(/\b\w/g, c => c.toUpperCase());
            case 'truncate': {
                const len = arg !== null ? parseInt(arg, 10) : 100;
                return value.length > len ? value.slice(0, len) + '...' : value;
            }
            case 'number_format': {
                const parts   = arg !== null ? arg.split(':') : [];
                const dec     = parts[0] !== undefined && parts[0] !== '' ? parseInt(parts[0], 10) : 0;
                const decSep  = parts[1] !== undefined && parts[1] !== '' ? parts[1] : ',';
                const thouSep = parts[2] !== undefined && parts[2] !== '' ? parts[2] : '.';
                const num     = parseFloat(value);
                if (isNaN(num)) return value;
                const fixed = num.toFixed(dec);
                const [intPart, decPart] = fixed.split('.');
                const intFormatted = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, thouSep);
                return decPart !== undefined ? intFormatted + decSep + decPart : intFormatted;
            }
            case 'slug':
                return value.toLowerCase().trim()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9\s-]/g, '').replace(/[\s-]+/g, '-');
            case 'nl2br':
                return value.replace(/\n/g, '<br>');
            case 'spans': {
                // spans[:class[:separator]] — scalar version wraps the value in a single <span>
                const args    = arg !== null ? arg.split(':', 2) : [];
                const cls     = args[0] && args[0] !== '' ? args[0] : null;
                const attr    = cls ? ` class="${cls}"` : '';
                return `<span${attr}>${value}</span>`;
            }
            case 'join':
                // join on a scalar is a no-op (nothing to join)
                return value;
            default:
                return value;
        }
    };

    // Private: resolves {{path|filter}} and {{path|filter:arg}} tokens.
    const _resolveWithFilter = (token, ctx) => {
        const pipe       = token.indexOf('|');
        const path       = token.slice(0, pipe).trim();
        const filterExpr = token.slice(pipe + 1).trim();
        const raw        = _resolvePath(path, ctx);
        if (!_isTruthy(raw)) return '';

        // Array-aware filters: intercept before join so each item stays separate.
        if (Array.isArray(raw)) {
            const colon      = filterExpr.indexOf(':');
            const filterName = (colon !== -1 ? filterExpr.slice(0, colon) : filterExpr).trim();
            const filterArgs = colon !== -1 ? filterExpr.slice(colon + 1) : null;

            if (filterName === 'spans') {
                // spans[:class[:separator]]
                const args      = filterArgs !== null ? filterArgs.split(':', 2) : [];
                const cls       = args[0] && args[0] !== '' ? args[0] : null;
                const separator = args[1] !== undefined ? args[1] : '';
                const attr      = cls ? ` class="${cls}"` : '';
                return raw.filter(Boolean).map(t => `<span${attr}>${t}</span>`).join(separator);
            }

            if (filterName === 'join') {
                // join[:separator] — default separator is empty string
                const separator = filterArgs !== null ? filterArgs : '';
                return raw.filter(Boolean).join(separator);
            }

            const value = raw.join(', ');
            return _applyFilter(value, filterExpr);
        }

        return _applyFilter(String(raw), filterExpr);
    };

    // Private: formats a resolved raw value to string. Returns null if unresolved.
    const _formatValue = (value) => {
        if (value === undefined || value === null) return null;
        if (Array.isArray(value)) return value.join(', ');
        return String(value);
    };

    // Private: handles {{primary ?? fallback}} tokens.
    // Fallback can be a quoted string literal (single or double quotes) or another dot-notation path.
    const _resolveFallback = (token, ctx) => {
        const [primary, fallback] = token.split('??').map(s => s.trim());
        const value = _resolvePath(primary, ctx);
        if (_isTruthy(value)) return _formatValue(value);

        const literalMatch = fallback.match(/^['"](.*)['"]\s*$/);
        if (literalMatch) return literalMatch[1];

        const fbValue = _resolvePath(fallback, ctx);
        return _isTruthy(fbValue) ? _formatValue(fbValue) : '';
    };

    // Private: evaluates a condition expression (plain path or comparison) against context.
    // Supports: >, >=, <, <=, ==, !=
    // Right-hand side may be a quoted string literal, a numeric literal, or a dot-notation path.
    const _evaluateCondition = (expr, ctx) => {
        // Multi-char operators must come before single-char ones to avoid partial matches.
        const operators = ['>=', '<=', '!=', '==', '>', '<'];
        for (const op of operators) {
            const pos = expr.indexOf(op);
            if (pos === -1) continue;

            const leftPath  = expr.slice(0, pos).trim();
            const rightExpr = expr.slice(pos + op.length).trim();

            let left  = _resolvePath(leftPath, ctx);
            let right;

            // Right side: quoted string literal, numeric literal, or another path.
            const literalMatch = rightExpr.match(/^['"](.*)['"]\s*$/);
            if (literalMatch) {
                right = literalMatch[1];
            } else if (!isNaN(rightExpr) && rightExpr !== '') {
                right = Number(rightExpr);
                left  = isNaN(Number(left)) ? left : Number(left);
            } else {
                right = _resolvePath(rightExpr, ctx);
            }

            switch (op) {
                case '>':  return left > right;
                case '>=': return left >= right;
                case '<':  return left < right;
                case '<=': return left <= right;
                case '==': return left == right; // eslint-disable-line eqeqeq
                case '!=': return left != right; // eslint-disable-line eqeqeq
            }
        }

        // No operator found — fall back to simple truthy check.
        return _isTruthy(_resolvePath(expr, ctx));
    };

    // Private: processes {{#if expr}}...{{else}}...{{/if}} blocks iteratively to support nesting.
    // The expression may be a plain path (truthy check) or a comparison:
    //   {{#if post.meta.precio >= 100}}Caro{{else}}Barato{{/if}}
    const _parseConditionals = (string, context) => {
        const ifPattern = /\{\{#if\s+([^}]+)\}\}([\s\S]*?)(?:\{\{else\}\}([\s\S]*?))?\{\{\/if\}\}/g;
        let maxPasses = 10, prev;
        do {
            prev = string;
            string = string.replace(ifPattern, (match, expr, ifBlock, elseBlock = '') => {
                return _evaluateCondition(expr.trim(), context) ? ifBlock : elseBlock;
            });
        } while (string !== prev && --maxPasses > 0);
        return string;
    };

    Handlebars.parse = function(string){
        const context = BUILDER_GLOBALS.context;

        // First pass: resolve {{#if}}...{{else}}...{{/if}} blocks
        string = _parseConditionals(string, context);

        // Second pass: resolve {{token}}, {{token ?? fallback}}, {{token|filter}} expressions
        return string.replace(/\{\{([^}]+)\}\}/g, (match, token) => {
            token = token.trim();
            if (token.includes('??')) return _resolveFallback(token, context);
            if (token.includes('|'))  return _resolveWithFilter(token, context);
            const value = _resolvePath(token, context);
            if (value === undefined || value === null) return match;
            return _formatValue(value);
        });
    };

    return Handlebars;
})();