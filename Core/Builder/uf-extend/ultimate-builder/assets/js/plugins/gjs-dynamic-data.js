window.gjsDynamicData = function (editor) {
    const domc = editor.DomComponents;
}

__handlePlhs = (string) => {
    // replace placeholders on string
    const placeholders = {
        '{{page_title}}': BUILDER_GLOBALS.page_title
    }
    Object.keys(placeholders).forEach( placeholder => {
        const value = placeholders[placeholder];
        string = string.replaceAll( placeholder, value );
    });
    return string;
}