@extends('front.default.layout')

@section('pagename')
 - {{__('Packages')}}
@endsection

@section('meta-keywords', "$be->packages_meta_keywords")
@section('meta-description', "$be->packages_meta_description")


@section('breadcrumb-title', convertUtf8($be->pricing_title))
@section('breadcrumb-subtitle', $be->pricing_subtitle)
@section('breadcrumb-link', __('Packages'))


@section('content')

<!--    Packages section start   -->
<div class="pricing-tables pricing-page">
   <div class="container">
     <div class="row">
       @foreach ($packages as $key => $package)
         <div class="col-lg-4 col-md-6">
           <div class="single-pricing-table">
              <span class="title">{{convertUtf8($package->title)}}</span>
              @if ($bex->recurring_billing == 1)
                <small class="text-capitalize">{{$package->duration == 'monthly' ? __('Monthly') : __('Yearly')}}</small>
              @endif
              <div class="price">
                 <h1>{{$bex->base_currency_symbol_position == 'left' ? $bex->base_currency_symbol : ''}}{{$package->price}}{{$bex->base_currency_symbol_position == 'right' ? $bex->base_currency_symbol : ''}}</h1>
              </div>
              <div class="features">
                 {!! replaceBaseUrl(convertUtf8($package->description)) !!}
              </div>

              @if ($bex->recurring_billing == 1)
                @auth
                    @if ($activeSub->count() > 0 && empty($activeSub->first()->next_package_id))
                        @if ($activeSub->first()->current_package_id == $package->id)
                            <a href="{{route('front.packageorder.index',$package->id)}}" class="pricing-btn">{{__('Extend')}}</a>
                        @else
                            <a href="{{route('front.packageorder.index',$package->id)}}" class="pricing-btn">{{__('Change')}}</a>
                        @endif
                    @elseif ($activeSub->count() == 0)
                        <a href="{{route('front.packageorder.index',$package->id)}}" class="pricing-btn">{{__('Purchase')}}</a>
                    @endif
                @endauth
                @guest
                    <a href="{{route('front.packageorder.index',$package->id)}}" class="pricing-btn">{{__('Purchase')}}</a>
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
                    <a href="{{ $link }}" @if($package->order_status == 2) target="_blank" @endif class="pricing-btn">{{__('Place Order')}}</a>
                @endif
              @endif

           </div>
         </div>
       @endforeach
     </div>
   </div>
</div>
<!--    Packages section end   -->
@endsection
