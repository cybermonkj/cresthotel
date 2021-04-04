(function ($) {
    "use strict";

    setInterval(function() {

        $('.service-slick').each(function() {
            if (!$(this).hasClass('applied')) {                
                $(this).slick({
                    dots: false,
                    infinite: true,
                    autoplay: false,
                    autoplaySpeed: 3000,
                    arrows: true,
                    prevArrow: '<span class="prev"><i class="fas fa-arrow-left"></i></span>',
                    nextArrow: '<span class="next"><i class="fas fa-arrow-right"></i></span>',
                    speed: 1500,
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    responsive: [
                        {
                            breakpoint: 1201,
                            settings: {
                                slidesToShow: 3,
                            }
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 2,
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1,
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: 1,
                            }
                        }
                    ]
                });
                $(this).addClass('applied');
            }
        });

        $('.team-slick').each(function() {
            if (!$(this).hasClass('applied')) {                
                $(this).slick({
                    dots: false,
                    infinite: true,
                    autoplay: false,
                    autoplaySpeed: 3000,
                    arrows: true,
                    prevArrow: '<span class="prev"><i class="fas fa-arrow-left"></i></span>',
                    nextArrow: '<span class="next"><i class="fas fa-arrow-right"></i></span>',
                    speed: 1500,
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    responsive: [
                        {
                            breakpoint: 1201,
                            settings: {
                                slidesToShow: 3,
                            }
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 2,
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1,
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: 1,
                            }
                        }
                    ]
                });
                $(this).addClass('applied');
            }
        });

        $('.pricing-slick').each(function() {
            if (!$(this).hasClass('applied')) {                
                $(this).slick({
                    dots: false,
                    infinite: true,
                    autoplay: false,
                    autoplaySpeed: 3000,
                    arrows: true,
                    prevArrow: '<span class="prev"><i class="fas fa-arrow-left"></i></span>',
                    nextArrow: '<span class="next"><i class="fas fa-arrow-right"></i></span>',
                    speed: 1500,
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    responsive: [
                        {
                            breakpoint: 1201,
                            settings: {
                                slidesToShow: 3,
                            }
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 2,
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1,
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: 1,
                            }
                        }
                    ]
                });
                $(this).addClass('applied');
            }
        });

        $('.blog-slick').each(function() {
            if (!$(this).hasClass('applied')) {                
                $(this).slick({
                    dots: false,
                    infinite: true,
                    autoplay: false,
                    autoplaySpeed: 3000,
                    arrows: true,
                    prevArrow: '<span class="prev"><i class="fas fa-arrow-left"></i></span>',
                    nextArrow: '<span class="next"><i class="fas fa-arrow-right"></i></span>',
                    speed: 1500,
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    responsive: [
                        {
                            breakpoint: 1201,
                            settings: {
                                slidesToShow: 3,
                            }
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 2,
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1,
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: 1,
                            }
                        }
                    ]
                });
                $(this).addClass('applied');
            }
        });

        
        //===== projects slick slider
        $('.project-slick').each(function() {
            if (!$(this).hasClass('applied')) {                
                $(this).slick({
                    dots: false,
                    infinite: true,
                    autoplay: false,
                    autoplaySpeed: 3000,
                    arrows: true,
                    prevArrow: '<span class="prev"><i class="fas fa-arrow-left"></i></span>',
                    nextArrow: '<span class="next"><i class="fas fa-arrow-right"></i></span>',
                    speed: 1500,
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    responsive: [
                        {
                            breakpoint: 1700,
                            settings: {
                                slidesToShow: 3,
                            }
                        },
                        {
                            breakpoint: 1401,
                            settings: {
                                slidesToShow: 2,
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1,
                            }
                        }
                    ]
                });
                $(this).addClass('applied');
            }
        });



        //===== testimonial slick slider
        $('.testimonial-active').each(function() {
            if (!$(this).hasClass('applied')) {
                $(this).slick({
                    dots: true,
                    infinite: true,
                    autoplay: false,
                    autoplaySpeed: 3000,
                    arrows: false,
                    prevArrow: '<span class="prev"><i class="fas fa-arrow-left"></i></span>',
                    nextArrow: '<span class="next"><i class="fas fa-arrow-right"></i></span>',
                    speed: 1500,
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    responsive: [
                        {
                            breakpoint: 1201,
                            settings: {
                                slidesToShow: 2,
                            }
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 1,
                            }
                        }
                    ]
                });
                $(this).addClass('applied');
            }
        });


        //===== brand-carousel-active slick slider
        $('.brand-carousel-active').each(function() {
            if (!$(this).hasClass('applied')) {
                $(this).slick({
                    dots: true,
                    infinite: true,
                    autoplay: false,
                    autoplaySpeed: 3000,
                    arrows: false,
                    prevArrow: '<span class="prev"><i class="fas fa-arrow-left"></i></span>',
                    nextArrow: '<span class="next"><i class="fas fa-arrow-right"></i></span>',
                    speed: 1500,
                    slidesToShow: 5,
                    slidesToScroll: 1,
                    responsive: [
                        {
                            breakpoint: 1201,
                            settings: {
                                slidesToShow: 5,
                            }
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 4,
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 2,
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: 1,
                            }
                        }
                    ]
                });
                $(this).addClass('applied');
            }
        });

        //===== counter up
        $('.count').each(function() {
            if (!$(this).hasClass('applied')) {
                $(this).counterUp({
                    delay: 10,
                    time: 2000
                });
                $(this).addClass('applied');
            }
        });

        // accordion collapse on button click
        $(".accordion .card-header button").on('click', function() {
            $(this).parents('.card-header').next().collapse("toggle");
        });

    }, 5000);


}(jQuery));
