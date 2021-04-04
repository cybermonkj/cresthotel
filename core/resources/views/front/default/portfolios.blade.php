@extends('front.default.layout')

@section('pagename')
 -
 @if (empty($category))
 {{__('All')}}
 @else
 {{convertUtf8($category->name)}}
 @endif
 {{__('Portfolios')}}
@endsection

@section('meta-keywords', "$be->portfolios_meta_keywords")
@section('meta-description', "$be->portfolios_meta_description")

@section('breadcrumb-title', convertUtf8($bs->portfolio_title))
@section('breadcrumb-subtitle', convertUtf8($bs->portfolio_subtitle))
@section('breadcrumb-link', __('Portfolios'))

@section('content')

  <!--    case lists start   -->
  <div class="case-lists section-padding case-page">
     <div class="container">
        @if (hasCategory($be->theme_version))
            <div class="row">
            <div class="col-xl-12">
                <div class="case-types">
                    <ul class="text-center">
                        <li class="@if(empty(request()->input('category'))) active @endif"><a href="{{route('front.portfolios')}}">{{__('All')}}</a></li>

                        @foreach ($scats as $key => $scat)
                            <li class="@if(request()->input('category') == $scat->id) active @endif"><a href="{{route('front.portfolios', ['category'=>$scat->id])}}">{{convertUtf8($scat->name)}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            </div>
        @endif
        <div class="cases">
           <div class="row">
             @if (count($portfolios) == 0)
               <div class="col-lg-12 py-5 bg-light text-center mb-4">
                 <h3>{{__('NO PORTFOLIO FOUND')}}</h3>
               </div>
             @else
               @foreach ($portfolios as $key => $portfolio)
                 <div class="col-lg-4 col-md-6">
                   <div class="single-case lazy" data-bg="{{asset('assets/front/img/portfolios/featured/'.$portfolio->featured_image)}}">
                      <div class="outer-container">
                         <div class="inner-container">
                            <h4>{{convertUtf8(strlen($portfolio->title)) > 36 ? convertUtf8(substr($portfolio->title, 0, 36)) . '...' : convertUtf8($portfolio->title)}}</h4>
                            @if (!empty($portfolio->service))
                            <p>{{$portfolio->service->title}}</p>
                            @endif

                            <a href="{{route('front.portfoliodetails', [$portfolio->slug, $portfolio->id])}}" class="readmore-btn"><span>{{__('Read More')}}</span></a>

                         </div>
                      </div>
                   </div>
                 </div>
               @endforeach
             @endif
           </div>
        </div>
        @if ($portfolios->total() > 6)
          <div class="row">
             <div class="col-md-12">
                <nav class="pagination-nav {{$portfolios->total() > 6 ? 'mb-4' : ''}}">
                  {{$portfolios->appends(['category' => request()->input('category')])->links()}}
                </nav>
             </div>
          </div>
        @endif
     </div>
  </div>
  <!--    case lists end   -->
@endsection
