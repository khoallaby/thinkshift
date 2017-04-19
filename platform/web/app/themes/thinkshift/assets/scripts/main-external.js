// new-age.js -- used for external sites

(function($) {
    "use strict"; // Start of use strict

    // jQuery for page scrolling feature - requires jQuery Easing plugin
    $(document).on('click', 'a.page-scroll', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: ($($anchor.attr('href')).offset().top - 50)
        }, 1250, 'easeInOutExpo');
        event.preventDefault();
    });

    // Highlight the top nav as scrolling occurs
    $('body').scrollspy({
        target: '.navbar-fixed-top',
        offset: 100
    });

    // Closes the Responsive Menu on Menu Item Click
    $('.navbar-collapse ul li a').click(function() {
        $('.navbar-toggle:visible').click();
    });

    /*
    // Offset for Main Navigation
    // @todo: update with bootstrap 4 version
    $('#mainNav').affix({
        offset: {
            top: 50
        }
    });
    */



    // stops video from playing when modal closed
    // @todo: move this to one shared place/file
    $(".modal-video").on('hide.bs.modal', function(){
        //$("#cartoonVideo").attr('src', '');
        $(this).find('iframe').attr('src', '');
    });

    $(".modal-video").on('show.bs.modal', function(){
        var iframe = $(this).find('iframe');
        var url = iframe.attr('data-src');
        iframe.attr('src', url);
    });


})(jQuery); // End of use strict

