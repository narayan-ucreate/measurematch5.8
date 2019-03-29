$(document).ready(function () {
    var buyer_address = $('#buyerAddress').val();
    $("#faqbtn").click(function () {
        $('html, body').animate({
            scrollTop: $("#faqpanel").offset().top
        }, 1000);
    });
    var str = String(window.location.href.split('/').splice(-1, 1));
    if (str.indexOf('seeallfaqs') != -1) {
        $('#faqbtn').trigger('click');
    }
    if (str.indexOf('accordionOne') != -1) {
        $('html, body').animate({
            scrollTop: ($('a[href="#accordionOne"]').offset().top - 60)
        }, 1000);
        $('a[href="#accordionOne"]').trigger('click');
    }
    if (str.indexOf('accordionTwo') != -1) {
        $('html, body').animate({
            scrollTop: ($('a[href="#accordionTwo"]').offset().top - 60)
        }, 1000);
        $('a[href="#accordionTwo"]').trigger('click');
    }
    if (str.indexOf('accordionThree') != -1) {
        $('html, body').animate({
            scrollTop: ($('a[href="#accordionThree"]').offset().top - 60)
        }, 1000);
        $('a[href="#accordionThree"]').trigger('click');
    }
    if (str.indexOf('accordionfour') != -1) {
        $('html, body').animate({
            scrollTop: ($('a[href="#accordionfour"]').offset().top - 60)
        }, 1000);
        $('a[href="#accordionfour"]').trigger('click');
    }
    $('#faq_link').addClass('active');
    $('.faq-close').on('click', function (e) {
        $('.panel-anchor').removeClass('active-panel');
        $('.panel-collapse').removeClass('in');
    });

    $('.panel-anchor').on('click', function (e) {
        if ($(this).hasClass('active-panel')) {
            $(this).removeClass('active-panel');
            $('.panel-anchor').removeClass('active-panel');
        } else {
            $('.panel-anchor').removeClass('active-panel');
            $(this).toggleClass('active-panel');
        }

    });
});