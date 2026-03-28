// Extend the built-in style manager properties
window.gjsExtendSmProperties = function (editor) {
    // editor.on('load', () => {
        // const styleManager = editor.StyleManager;
        // console.log(styleManager);
    
        // Extend 'display' property with 'grid' option, works on editor load
        // const displayProperty = styleManager.getProperty('display', 'display');
        // displayProperty.addOption({ id: 'grid', label: 'grid' });
    // });

    // Extend 'gap' property with specific requirements, works
    // editor.Styles.addBuiltIn('gap', { 
        // requires: { display: ['flex', 'inline-flex', 'grid'] },
    // });

    // not working as expected, needs further investigation
    // editor.Styles.addBuiltIn('background-repeat', { default: 'no-repeat' });
    // editor.Styles.addBuiltIn('background-position', { default: 'center center' });
}