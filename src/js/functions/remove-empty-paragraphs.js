function remove_empty_paragraphs() {
    // Selecciona todos los elementos <p> en el documento
    const paragraphs = document.querySelectorAll('p');

    // Itera sobre cada elemento <p>
    paragraphs.forEach((p) => {
        // Verifica si el <p> está vacío o contiene solo espacios
        if ( p.innerHTML.trim() === '' && !p.hasAttributes() ) {
            // Elimina el elemento <p> del DOM
            p.remove();
        }
    });
}