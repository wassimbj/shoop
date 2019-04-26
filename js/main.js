
(function ($) {
    "use strict";
    $(".slider").not('.slick-initialized').slick()
    /*[ Back to top ]
    ===========================================================*/
    var windowH = $(window).height()/2;

    $(window).on('scroll',function(){
        if ($(this).scrollTop() > windowH) {
            $("#myBtn").css('display','flex');
        } else {
            $("#myBtn").css('display','none');
        }
    });

    $('#myBtn').on("click", function(){
        $('html, body').animate({scrollTop: 0}, 300);
    });


    /*==================================================================
    [ Fixed Header ]*/
    var headerDesktop = $('.container-menu-desktop');
    var wrapMenu = $('.wrap-menu-desktop');

    if($('.top-bar').length > 0) {
        var posWrapHeader = $('.top-bar').height();
    }
    else {
        var posWrapHeader = 0;
    }
    

    if($(window).scrollTop() > posWrapHeader) {
        $(headerDesktop).addClass('fix-menu-desktop');
        $(wrapMenu).css('top',0); 
    }  
    else {
        $(headerDesktop).removeClass('fix-menu-desktop');
        $(wrapMenu).css('top',posWrapHeader - $(this).scrollTop()); 
    }

    $(window).on('scroll',function(){
        if($(this).scrollTop() > posWrapHeader) {
            $(headerDesktop).addClass('fix-menu-desktop');
            $(wrapMenu).css('top',0);
        } 
        else {
            $(headerDesktop).removeClass('fix-menu-desktop');
            $(wrapMenu).css('top',posWrapHeader - $(this).scrollTop());
        } 
    });



    /*==================================================================
    [ Show / hide modal search ]*/
    // hide the search bar
    // $('.modal-search-header').hide();
    $('.js-show-modal-search').on('click', function(){
        $('.modal-search-header').fadeIn(250);
    });

    $('.js-hide-modal-search').on('click', function(){
        $('.modal-search-header').fadeOut(200);
    });

    $('.container-search-header').on('click', function(e){
        e.stopPropagation();
    });



    /*==================================================================
    [ Cart ]*/
    $('.js-show-cart').on('click',function(){
        $('.js-panel-cart').addClass('show-header-cart');
    });

    $('.js-hide-cart').on('click',function(){
        $('.js-panel-cart').removeClass('show-header-cart');
    });

    /*==================================================================
    [ Cart ]*/
    $('.js-show-sidebar').on('click',function(){
        $('.js-sidebar').addClass('show-sidebar');
    });

    $('.js-hide-sidebar').on('click',function(){
        $('.js-sidebar').removeClass('show-sidebar');
    });
    
    /*==================================================================
    [ Show modal ]*/
    $(document).on('click', '.js-show-modal1', function(e){
        e.preventDefault();
        // $('.js-modal1').addClass('show-modal1');
        $($(this).data('target')).addClass('show-modal1')
    });

    $(document).on('click', '.js-hide-modal1', function(){
        $($(this).data('target')).removeClass('show-modal1');
    });

    // =====================================================================
    // Gallerys 
    $('.gallery-lb').each(function () { // the containers for all your galleries
        $(this).magnificPopup({
            delegate: 'a', // the selector for gallery item
            type: 'image',
            gallery: {
                enabled: true
            },
            mainClass: 'mfp-fade'
        });
    });

})(jQuery);