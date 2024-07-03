(function($,c){    
    $(function() {
        // ****************************************************************************************************
        // CONVERTIR ENLACES A PDF EN PDF
        // ****************************************************************************************************
        var is_single = $('body').hasClass('single');
        
        function convertir_links_en_pdf(links){
            if (links.length > 0) {
                for (var i = 0; i < links.length; i++) {
                    var href = $(links[i]).attr('href');
                    $(links[i]).append(' <i class="fa fa-level-down"></i>');
                    $('<div class="pdf-responsive"><embed src="' + href + '" width="670" height="500" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html"></div>').insertAfter($(links[i]).parent());
                }
            }
        }
        if (is_single) {

            $('.disable-link-to-embed-conversion a').addClass('normal-link');

            var links = $('.main').find('a[href*=".pdf"]:not(.normal-link)');
            convertir_links_en_pdf(links);
        }

        // ****************************************************************************************************
        // CONVERTIR ENLACES A DOCUMENTOS EN UN VISOR
        // ****************************************************************************************************
        function convertir_docs(links){
            if (links.length > 0) {
                for (var i = 0; i < links.length; i++) {
                    var href = $(links[i]).attr('href');
                    $(links[i]).append(' <i class="fa fa-level-down"></i>');
                    $('<div class="pdf-responsive"><iframe src="https://view.officeapps.live.com/op/embed.aspx?src='+href+'" width="100%" height="565px" frameborder="0"></iframe></div>').insertAfter($(links[i]).parent());
                }
            }
        }
        if (is_single) {

            $('.disable-link-to-embed-conversion a').addClass('normal-link');

            var links = $('.main').find('a[href*=".docx"]:not(.normal-link), a[href*=".pptx"]:not(.normal-link), a[href*=".xlsxs"]:not(.normal-link)');
            convertir_docs(links);
        }
        // ****************************************************************************************************
    });
})(jQuery,console.log);