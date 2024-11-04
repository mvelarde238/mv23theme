document.addEventListener("DOMContentLoaded", () => {
    setTimeout(() => {
        const hash = window.location.hash;
        if (hash) {
            adjustScrollPosition(hash);
        }
    }, 100);
});

function adjustScrollPosition(anchor) {
    const targetElement = document.querySelector(anchor);
    if (targetElement) {
        var bodyStyles = window.getComputedStyle(document.body);
        var sticky_header_height = bodyStyles.getPropertyValue('--sticky-header-height');
        const headerHeight = parseInt(sticky_header_height);
        const elementPosition = targetElement.getBoundingClientRect().top + window.scrollY;

        window.scrollTo({
            top: elementPosition - headerHeight,
            behavior: "smooth"
        });
    }
}
