(function ($) {
    "use strict";

    setInterval(function() {

        // owl carousel initiated on team section
        var teamCarousel = $('.team-carousel');       
        teamCarousel.each(function() {

            if (!$(this).hasClass('applied')) {                
                $(this).owlCarousel({
                    loop: false,
                    dots: false,
                    margin: 30,
                    autoplay: false,
                    smartSpeed: 1500,
                    startPosition: 2,
                    autoplayTimeout: 5000,
                    autoplayHoverPause: true,
                    nav: true,
                    navText: ["<i class='flaticon-left-arrow'></i>", "<i class='flaticon-right-arrow'></i>"],
                    rtl: true,
                    responsive: {
                        0: {
                            items: 1
                        },
                        576: {
                            items: 2
                        },
                        992: {
                            items: 3
                        },
                        1200: {
                            items: 4
                        }
                    }
                });
                $(this).addClass('applied');
            }

        });


        // accordion collapse on button click
        $(".accordion .card-header button").on('click', function() {
            $(this).parents('.card-header').next().collapse("toggle");
        });

        // statistics jquery circle progressbar initialization           
        $('.round').each(function () {
            if (!$(this).hasClass('applied')) {                
                $(this).circleProgress({
                    animation: {
                        duration: 1500,
                        easing: "circleProgressEasing"
                    }
                }).on('circle-animation-progress', function (event, progress) {
                    $(this).find('strong').text(parseInt(progress * $(this).data('number')) + "+");
                });
                $(this).addClass('applied');
            }
        });

        // portfolios carousel initialization
        $('.case-carousel').each(function() {
            if (!$(this).hasClass('applied')) {                
                $(this).owlCarousel({
                    loop: true,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    autoplaySpeed: 1500,
                    dots: false,
                    nav: true,
                    navText: ["<i class='flaticon-left-arrow'></i>", "<i class='flaticon-right-arrow'></i>"],
                    smartSpeed: 1500,
                    autoplayHoverPause: true,
                    margin: 0,
                    rtl: true,
                    responsive: {
                        0: {
                            items: 1
                        },
                        576: {
                            items: 2
                        },
                        992: {
                            items: 3
                        },
                        1367: {
                            items: 4
                        },
                        1750: {
                            items: 5
                        }
                    }
                });

                $(this).addClass('applied');
            }
        });  

        // testimonial carousel initialization
        $('.testimonial-carousel').each(function() {
            if (!$(this).hasClass('applied')) {                
                $(this).owlCarousel({
                    loop: false,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    autoplaySpeed: 1500,
                    dots: true,
                    nav: false,
                    smartSpeed: 1500,
                    autoplayHoverPause: true,
                    margin: 30,
                    rtl: true,
                    responsive: {
                        0: {
                            items: 1
                        },
                        992: {
                            items: 2
                        },
                    }
                });

                $(this).addClass('applied');
            }
        });       
            


        $(".pricing-carousel").each(function() {
            if (!$(this).hasClass('applied')) {                
                $(this).owlCarousel({
                    loop: true,
                    dots: false,
                    nav: true,
                    navText: ["<i class='flaticon-left-arrow'></i>", "<i class='flaticon-right-arrow'></i>"],
                    autoplay: false,
                    autoplayTimeout: 5000,
                    smartSpeed: 1500,
                    items: 3,
                    rtl: true,
                    responsive : {
                        // breakpoint from 0 up
                        0 : {
                            items : 1,
                            nav: true
                        },
                        // breakpoint from 480 up
                        768 : {
                            items : 2,
                            nav: true
                        },
                        // breakpoint from 768 up
                        992 : {
                            items : 3
                        }
                    }
                });

                $(this).addClass('applied');
            }
        });

        $('.blog-carousel').each(function() {
            if (!$(this).hasClass('applied')) {                
                $(this).owlCarousel({
                    loop: true,
                    dots: false,
                    margin: 22,
                    autoplay: false,
                    smartSpeed: 1500,
                    startPosition: 2,
                    autoplayTimeout: 5000,
                    autoplayHoverPause: true,
                    nav: true,
                    navText: ["<i class='flaticon-left-arrow'></i>", "<i class='flaticon-right-arrow'></i>"],
                    rtl: true,
                    responsive: {
                        0: {
                            items: 1
                        },
                        768: {
                            items: 2
                        },
                        992: {
                            items: 2
                        },
                        1200: {
                            items: 3
                        }
                    }
                });
                $(this).addClass('applied');
            }
        });

        $('.partner-carousel').each(function() {
            if (!$(this).hasClass('applied')) {                
                $(this).owlCarousel({
                    loop: true,
                    dots: false,
                    margin: 30,
                    autoplay: true,
                    smartSpeed: 1500,
                    autoplayTimeout: 3000,
                    autoplaySpeed: 500,
                    autoplayHoverPause: true,
                    nav: true,
                    navText: ["<i class='flaticon-left-arrow'></i>", "<i class='flaticon-right-arrow'></i>"],
                    rtl: true,
                    responsive: {
                        0: {
                            items: 2
                        },
                        576: {
                            items: 3
                        },
                        992: {
                            items: 5
                        },
                    }
                });
                $(this).addClass('applied');
            }
        });
    }, 5000);


}(jQuery));
