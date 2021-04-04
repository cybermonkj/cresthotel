@extends('front.cleaning.layout')

@section('pagename')
 - {{__('Packages')}}
@endsection

@section('meta-keywords', "$be->packages_meta_keywords")
@section('meta-description', "$be->packages_meta_description")

@section('breadcrumb-title', convertUtf8($be->pricing_title))
@section('breadcrumb-subtitle', $be->pricing_subtitle)
@section('breadcrumb-link', __('Packages'))

@section('content')


<!-- Start finlance_pricing section -->
<section class="pt-90 pb-30">
    <div class="container">
        <div class="price-carousel-active">
            <div class="row">
                @foreach ($packages as $key => $package)
                    <div class="col-lg-4 col-md-6 mb-5">
                        <div class="single-price-item text-center">
                            <div class="price-heading">
                                <h3>{{convertUtf8($package->title)}}</h3>
                                @if ($bex->recurring_billing == 1)
                                    <span class="text-capitalize">{{$package->duration == 'monthly' ? __('Monthly') : __('Yearly')}}</span>
                                @endif
                            </div>
                            <h1 class="bg-1" style="background: #{{$package->color}};">{{$bex->base_currency_symbol_position == 'left' ? $bex->base_currency_symbol : ''}}{{$package->price}}{{$bex->base_currency_symbol_position == 'right' ? $bex->base_currency_symbol : ''}}</h1>
                            <div class="price-cata mb-4">
                                {!! replaceBaseUrl(convertUtf8($package->description)) !!}
                            </div>

                            @if ($bex->recurring_billing == 1)
                                @auth
                                    @if ($activeSub->count() > 0 && empty($activeSub->first()->next_package_id))
                                        @if ($activeSub->first()->current_package_id == $package->id)
                                            <a href="{{route('front.packageorder.index',$package->id)}}" class="main-btn price-btn">{{__('Extend')}}</a>
                                        @else
                                            <a href="{{route('front.packageorder.index',$package->id)}}" class="main-btn price-btn">{{__('Change')}}</a>
                                        @endif
                                    @elseif ($activeSub->count() == 0)
                                        <a href="{{route('front.packageorder.index',$package->id)}}" class="main-btn price-btn">{{__('Purchase')}}</a>
                                    @endif
                                @endauth
                                @guest
                                    <a href="{{route('front.packageorder.index',$package->id)}}" class="main-btn price-btn">{{__('Purchase')}}</a>
                                @endguest
                            @else
                                @if ($package->order_status != 0)
                                    @php
                                        if($package->order_status == 1) {
                                            $link = route('front.packageorder.index', $package->id);
                                        } elseif ($package->order_status == 2) {
                                            $link = $package->link;
                                        }
                                    @endphp
                                    <div class="pricing_button">
                                        <a href="{{ $link }}" @if($package->order_status == 2) target="_blank" @endif class="main-btn price-btn">{{__('Place Order')}}</a>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</section>
<!-- End finlance_pricing section -->
@endsection
