function adjustScrollPosition(anchor) {
    const targetElement = document.querySelector(anchor);
    if (targetElement) {
        let header = document.getElementById("header"),
            sticky_header_height = header.offsetHeight;

        const headerHeight = parseInt(sticky_header_height);
        const elementPosition = targetElement.getBoundingClientRect().top + window.scrollY;

        window.scrollTo({
            top: elementPosition - headerHeight,
            behavior: "smooth"
        });
    }
}
