function remove_empty_paragraphs() {
    // Select all <p> elements in the document
    const paragraphs = document.querySelectorAll('p');

    // Iterate over each <p> element
    paragraphs.forEach((p) => {
        // Check if the <p> is empty or contains only spaces
        if ( p.innerHTML.trim() === '' && !p.hasAttributes() ) {
            // Remove the <p> element from the DOM
            p.remove();
        }
    });
}