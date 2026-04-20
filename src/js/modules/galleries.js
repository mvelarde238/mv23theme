(function ($, c) {
    $(function () {
        // ****************************************************************************************************
        // Init GridStack.js Galleries
        // ****************************************************************************************************

        const galleryEls = document.querySelectorAll('.theme-gallery--grid');
        galleryEls.forEach(galleryEl => {
            GridStack.init({
                staticGrid: true,
                margin: 5,
            }, galleryEl);
        });

        // ****************************************************************************************************
        // OPEN FANCYBOX GALLERY BY SLUG WITH A LINK: 
        // .show-gallery--{gallery_id} 
        // ****************************************************************************************************

        var prefijo = 'show-gallery--';

        $(document).on('click', 'a[class*=' + prefijo + ']', function (event) {
            // event.preventDefault();
            const clases = event.target.classList;

            // Iterar sobre todas las clases
            for (let i = 0; i < clases.length; i++) {
                const clase = clases[i];
                // Verificar si la clase comienza con el prefijo
                if (clase.startsWith(prefijo)) {
                    const gallerySlug = clase.substring(prefijo.length);
                    Fancybox.fromSelector('[data-fancybox="' + gallerySlug + '"]');
                }
            }
        });

        // ****************************************************************************************************
    });
})(jQuery, console.log);