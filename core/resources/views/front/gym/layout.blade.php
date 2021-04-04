<!DOCTYPE html>
<html lang="en">
   <head>
      <!--Start of Google Analytics script-->
      @if ($bs->is_analytics == 1)
      {!! $bs->google_analytics_script !!}
      @endif
      <!--End of Google Analytics script-->

      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <meta name="description" content="@yield('meta-description')">
      <meta name="keywords" content="@yield('meta-keywords')">

      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>{{$bs->website_title}} @yield('pagename')</title>
      <!-- favicon -->
      <link rel="shortcut icon" href="{{asset('assets/front/img/'.$bs->favicon)}}" type="image/x-icon">
      <!-- bootstrap css -->
      <link rel="stylesheet" href="{{asset('assets/front/css/bootstrap.min.css')}}">
      <!-- plugin css -->
      <link rel="stylesheet" href="{{asset('assets/front/css/plugin.min.css')}}">
      <!--default css-->
      <link rel="stylesheet" href="{{asset('assets/front/css/default.css')}}">
      <!-- main css -->
      <link rel="stylesheet" href="{{asset('assets/front/css/gym-style.css')}}">
      <!-- common css -->
      <link rel="stylesheet" href="{{asset('assets/front/css/common-style.css')}}">
      <!-- main css -->
      <link rel="stylesheet" href="{{asset('assets/front/css/gym-responsive.css')}}">

      @if ($bs->is_tawkto == 1)
      <style>
      #scroll_up {
          bottom: 50px;
      }
      #scroll_up {
          right: 20px;
      }
      </style>
      @endif
      @if (count($langs) == 0)
      <style media="screen">
      .support-bar-area ul.social-links li:last-child {
          margin-right: 0px;
      }
      .support-bar-area ul.social-links::after {
          display: none;
      }
      </style>
      @endif

      <!-- responsive css -->
      <link rel="stylesheet" href="{{asset('assets/front/css/responsive.css')}}">
      <!-- common base color change -->
      <link href="{{url('/')}}/assets/front/css/common-base-color.php?color={{$bs->base_color}}" rel="stylesheet">
      <!-- base color change -->
      <link href="{{url('/')}}/assets/front/css/gym-base-color.php?color={{$bs->base_color}}" rel="stylesheet">


      @if ($rtl == 1)
      <!-- RTL css -->
      <link rel="stylesheet" href="{{asset('assets/front/css/rtl.css')}}">
      <link rel="stylesheet" href="{{asset('assets/front/css/gym-rtl.css')}}">
      <link rel="stylesheet" href="{{asset('assets/front/css/pb-rtl.css')}}">
      @endif

      @yield('styles')

      <!-- jquery js -->
      <script src="{{asset('assets/front/js/jquery-3.3.1.min.js')}}"></script>

      @if ($bs->is_appzi == 1)
      <!-- Start of Appzi Feedback Script -->
      <script async src="https://app.appzi.io/bootstrap/bundle.js?token={{$bs->appzi_token}}"></script>
      <!-- End of Appzi Feedback Script -->
      @endif

      <!-- Start of Facebook Pixel Code -->
      @if ($be->is_facebook_pexel == 1)
        {!! $be->facebook_pexel_script !!}
      @endif
      <!-- End of Facebook Pixel Code -->

      <!--Start of Appzi script-->
      @if ($bs->is_appzi == 1)
      {!! $bs->appzi_script !!}
      @endif
      <!--End of Appzi script-->
   </head>



   <body @if($rtl == 1) dir="rtl" @endif>


    <!-- Start finlance_header area -->
    @includeIf('front.gym.partials.navbar')
    <!-- End finlance_header area -->

    @if (!request()->routeIs('front.index') && !request()->routeIs('front.packageorder.confirmation'))
        <!--   breadcrumb area start   -->
        <div class="breadcrumb-area cases lazy" data-bg="{{asset('assets/front/img/' . $bs->breadcrumb)}}" style="background-size:cover;">
            <div class="container">
            <div class="breadcrumb-txt">
                <div class="row">
                    <div class="col-xl-7 col-lg-8 col-sm-10">
                        <span>@yield('breadcrumb-title')</span>
                        <h1>@yield('breadcrumb-subtitle')</h1>
                        <ul class="breadcumb">
                        <li><a href="{{route('front.index')}}">{{__('Home')}}</a></li>
                        <li>@yield('breadcrumb-link')</li>
                        </ul>
                    </div>
                </div>
            </div>
            </div>
            <div class="breadcrumb-area-overlay" style="background-color: #{{$be->breadcrumb_overlay_color}};opacity: {{$be->breadcrumb_overlay_opacity}};"></div>
        </div>
        <!--   breadcrumb area end    -->
    @endif

    @yield('content')


    <!--    announcement banner section start   -->
    <a class="announcement-banner" href="{{asset('assets/front/img/'.$bs->announcement)}}"></a>
    <!--    announcement banner section end   -->


		<!-- Start finlance_footer section -->
		<footer class="finlance_footer footer_v1 dark_bg">
			<div class="footer_top pt-120 pb-120">
				<div class="container">
					<div class="row">
						<div class="col-lg-3 col-md-6 col-sm-12">
							<div class="widget_box about_widget">
                                <a href="{{route('front.index')}}">
                                    <img class="lazy" data-src="{{asset('assets/front/img/'.$bs->footer_logo)}}" alt="">
                                </a>
								<p>
                                    @if (strlen(convertUtf8($bs->footer_text)) > 170)
                                       {{substr(convertUtf8($bs->footer_text), 0, 170)}}<span style="display: none;">{{substr(convertUtf8($bs->footer_text), 170)}}</span>
                                       <a href="#" class="see-more">see more...</a>
                                    @else
                                       {{convertUtf8($bs->footer_text)}}
                                    @endif
                                </p>
							</div>
						</div>
						<div class="col-lg-3 col-md-6 col-sm-12">
							<div class="widget_box contact_widget">
								<h4 class="widget_title">{{__('Contact Us')}}</h4>
								<p>{{convertUtf8($bs->contact_address)}}</p>
								<p>{{__('Phone')}}: <a href="#">{{convertUtf8($bs->contact_number)}} </a></p>
								<p>{{__('Email')}}: <a href="#">{{convertUtf8($be->to_mail)}}</a></p>
							</div>
						</div>
						<div class="col-lg-3 col-md-6 col-sm-12">
							<div class="widget_box">
								<h4 class="widget_title">{{__('Useful Links')}}</h4>
								<ul class="widget_link">
                                    @foreach ($ulinks as $key => $ulink)
                                        <li><a href="{{$ulink->url}}">{{convertUtf8($ulink->name)}}</a></li>
                                    @endforeach
								</ul>
							</div>
						</div>
						<div class="col-lg-3 col-md-6 col-sm-12">
							<div class="widget_box newsletter_box">
								<h4 class="widget_title">{{__('Newsletter')}}</h4>
								<p>{{convertUtf8($bs->newsletter_text)}}</p>
								<form id="footerSubscribeForm" action="{{route('front.subscribe')}}" method="post">
                                    @csrf
									<div class="form_group">
										<input type="email" class="form_control" placeholder="{{__('Enter Email Address')}}" name="email" required>
                                        <p id="erremail" class="text-danger mb-0 err-email"></p>
										<button type="submit" class="finlance_btn py-0">{{__('Subscribe')}}</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
            </div>


			<div class="footer_bottom">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-lg-6">
							<div class="copyright_text">
								<p>{!! replaceBaseUrl(convertUtf8($bs->copyright_text)) !!}</p>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="social_box">
								<ul>
                                    @foreach ($socials as $key => $social)
                                        <li><a target="_blank" href="{{$social->url}}"><i class="{{$social->icon}}"></i></a></li>
                                    @endforeach
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</footer><!-- End finlance_footer section -->

        @if ($bex->is_shop == 1)
            <div id="cartIconWrapper">
                <a class="d-block" id="cartIcon" href="{{route('front.cart')}}">
                    <div class="cart-length">
                        <i class="fas fa-cart-plus"></i>
                        <span class="length">{{cartLength()}} {{__('ITEMS')}}</span>
                    </div>
                    <div class="cart-total">
                        {{$bex->base_currency_symbol_position == 'left' ? $bex->base_currency_symbol : ''}}
                        {{cartTotal()}}
                        {{$bex->base_currency_symbol_position == 'right' ? $bex->base_currency_symbol : ''}}
                    </div>
                </a>
            </div>
        @endif


    <!--====== PRELOADER PART START ======-->
    @if ($bex->preloader_status == 1)
    <div id="preloader">
        <div class="loader revolve">
            <img src="{{asset('assets/front/img/' . $bex->preloader)}}" alt="">
        </div>
    </div>
    @endif
    <!--====== PRELOADER PART ENDS ======-->

    <!--Scroll-up-->
    <a id="scroll_up" ><i class="fas fa-angle-up"></i></a>


    {{-- Cookie alert dialog start --}}
    @if ($be->cookie_alert_status == 1)
    @include('cookieConsent::index')
    @endif
    {{-- Cookie alert dialog end --}}

      @php
        $mainbs = [];
        $mainbs['is_announcement'] = $bs->is_announcement;
        $mainbs['announcement_delay'] = $bs->announcement_delay;
        $mainbs = json_encode($mainbs);
      @endphp
      <script>
        var lat = {{$bs->latitude}};
        var lng = {{$bs->longitude}};
        var mainbs = {!! $mainbs !!};

        var rtl = {{ $rtl }};
      </script>
      <!-- popper js -->
      <script src="{{asset('assets/front/js/popper.min.js')}}"></script>
      <!-- bootstrap js -->
      <script src="{{asset('assets/front/js/bootstrap.min.js')}}"></script>
      <!-- Plugin js -->
      <script src="{{asset('assets/front/js/plugin.min.js')}}"></script>
      @if (request()->path() == 'contact')
      <!-- google map api -->
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC7eALQrRUekFNQX71IBNkxUXcz-ALS-MY&callback=initMap" async defer></script>
      <!-- google map activate js -->
      <script src="{{asset('assets/front/js/google-map-activate.min.js')}}"></script>
      @endif
      <!-- main js -->
      <script src="{{asset('assets/front/js/gym-main.js')}}"></script>
      <!-- pagebuilder custom js -->
      <script src="{{asset('assets/front/js/common-main.js')}}"></script>

      @yield('scripts')

      @if (session()->has('success'))
      <script>
         toastr["success"]("{{__(session('success'))}}");
      </script>
      @endif

      @if (session()->has('error'))
      <script>
         toastr["error"]("{{__(session('error'))}}");
      </script>
      @endif

      <!--Start of subscribe functionality-->
      <script>
        $(document).ready(function() {
          $("#subscribeForm, #footerSubscribeForm").on('submit', function(e) {
            // console.log($(this).attr('id'));

            e.preventDefault();

            let formId = $(this).attr('id');
            let fd = new FormData(document.getElementById(formId));
            let $this = $(this);

            $.ajax({
              url: $(this).attr('action'),
              type: $(this).attr('method'),
              data: fd,
              contentType: false,
              processData: false,
              success: function(data) {
                // console.log(data);
                if ((data.errors)) {
                  $this.find(".err-email").html(data.errors.email[0]);
                } else {
                  toastr["success"]("You are subscribed successfully!");
                  $this.trigger('reset');
                  $this.find(".err-email").html('');
                }
              }
            });
          });

            // lory slider responsive
            $(".gjs-lory-frame").each(function() {
                let id = $(this).parent().attr('id');
                $("#"+id).attr('style', 'width: 100% !important');
            });
        });
      </script>
      <!--End of subscribe functionality-->

      <!--Start of Tawk.to script-->
      @if ($bs->is_tawkto == 1)
      {!! $bs->tawk_to_script !!}
      @endif
      <!--End of Tawk.to script-->

      <!--Start of AddThis script-->
      @if ($bs->is_addthis == 1)
      {!! $bs->addthis_script !!}
      @endif
      <!--End of AddThis script-->
   </body>
</html>
