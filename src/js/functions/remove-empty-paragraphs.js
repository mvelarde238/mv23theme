function remove_empty_paragraphs() {
    const paragraphs = document.querySelectorAll('p');

    paragraphs.forEach((p) => {
        if (p.textContent.trim() === '') {
            p.remove();
        }
    });
}