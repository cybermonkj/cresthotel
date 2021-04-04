(function ($) {
    "use strict";

    setInterval(function() {

        $('.service-slick').each(function() {
            if (!$(this).hasClass('applied')) {
                $(this).slick({
                    dots: false,
                    arrows: true,
                    infinite: true,
                    speed: 300,
                    autoplay: false,
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    rtl: true,
                    responsive: [
                      {
                        breakpoint: 992,
                        settings: {
                          slidesToShow: 2,
                          slidesToScroll: 2,
                        }
                      },
                      {
                        breakpoint: 780,
                        settings: {
                          slidesToShow: 1,
                          slidesToScroll: 1
                        }
                      },
                      {
                        breakpoint: 480,
                        settings: {
                          slidesToShow: 1,
                          slidesToScroll: 1
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
                    arrows: true,
                    infinite: true,
                    speed: 300,
                    autoplay: false,
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    rtl: true,
                    responsive: [
                      {
                        breakpoint: 992,
                        settings: {
                          slidesToShow: 2,
                          slidesToScroll: 2,
                        }
                      },
                      {
                        breakpoint: 780,
                        settings: {
                          slidesToShow: 1,
                          slidesToScroll: 1
                        }
                      },
                      {
                        breakpoint: 480,
                        settings: {
                          slidesToShow: 1,
                          slidesToScroll: 1
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
                    arrows: true,
                    infinite: true,
                    speed: 300,
                    autoplay: false,
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    rtl: true,
                    responsive: [
                      {
                        breakpoint: 992,
                        settings: {
                          slidesToShow: 2,
                          slidesToScroll: 2,
                        }
                      },
                      {
                        breakpoint: 780,
                        settings: {
                          slidesToShow: 1,
                          slidesToScroll: 1
                        }
                      },
                      {
                        breakpoint: 480,
                        settings: {
                          slidesToShow: 1,
                          slidesToScroll: 1
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
                    arrows: true,
                    infinite: true,
                    speed: 300,
                    autoplay: false,
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    rtl: true,
                    responsive: [
                      {
                        breakpoint: 992,
                        settings: {
                          slidesToShow: 2,
                          slidesToScroll: 2,
                        }
                      },
                      {
                        breakpoint: 780,
                        settings: {
                          slidesToShow: 1,
                          slidesToScroll: 1
                        }
                      },
                      {
                        breakpoint: 480,
                        settings: {
                          slidesToShow: 1,
                          slidesToScroll: 1
                        }
                      }
                    ]
                });
                $(this).addClass('applied');
            }
        });


        $('.testimonial_slide').each(function() {
            if (!$(this).hasClass('applied')) {
                $(this).slick({
                    dots: false,
                    arrows: false,
                    infinite: true,
                    speed: 300,
                    autoplay: true,
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    rtl: true,
                    responsive: [
                      {
                        breakpoint: 992,
                        settings: {
                          slidesToShow: 2,
                          slidesToScroll: 2,
                        }
                      },
                      {
                        breakpoint: 780,
                        settings: {
                          slidesToShow: 1,
                          slidesToScroll: 1
                        }
                      },
                      {
                        breakpoint: 480,
                        settings: {
                          slidesToShow: 1,
                          slidesToScroll: 1
                        }
                      }
                    ]
                });
                $(this).addClass('applied');
            }
        });

        $('.project-slick').each(function() {
            if (!$(this).hasClass('applied')) {
                $(this).slick({
                    dots: false,
                    arrows: true,
                    infinite: true,
                    speed: 300,
                    autoplay: false,
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    rtl: true,
                    responsive: [
                      {
                        breakpoint: 1024,
                        settings: {
                          slidesToShow: 3,
                          slidesToScroll: 2,
                        }
                      },
                      {
                        breakpoint: 780,
                        settings: {
                          slidesToShow: 2,
                          slidesToScroll: 1
                        }
                      },
                      {
                        breakpoint: 480,
                        settings: {
                          slidesToShow: 1,
                          slidesToScroll: 1
                        }
                      }
                    ]
                });
                $(this).addClass('applied');
            }
        })

        $('.partner_slide').each(function() {
            if (!$(this).hasClass('applied')) {
                $(this).slick({
                    dots: false,
                    arrows: false,
                    infinite: true,
                    speed: 600,
                    autoplay: true,
                    slidesToShow: 5,
                    slidesToScroll: 1,
                    rtl: true,
                    responsive: [
                      {
                        breakpoint: 1024,
                        settings: {
                          slidesToShow: 3,
                          slidesToScroll: 3,
                        }
                      },
                      {
                        breakpoint: 600,
                        settings: {
                          slidesToShow: 2,
                          slidesToScroll: 2
                        }
                      },
                      {
                        breakpoint: 480,
                        settings: {
                          slidesToShow: 1,
                          slidesToScroll: 1
                        }
                      }
                    ]
                });
                $(this).addClass('applied');
            }
        });

        $('.counter').each(function() {
          if (!$(this).hasClass('applied')) {            
            $(this).counterUp({
                delay: 50,
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
