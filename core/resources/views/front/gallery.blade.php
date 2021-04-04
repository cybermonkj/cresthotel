@extends("front.$version.layout")

@section('pagename')
 - {{__('Gallery')}}
@endsection

@section('meta-keywords', "$be->gallery_meta_keywords")
@section('meta-description', "$be->gallery_meta_description")

@section('breadcrumb-title', $bs->gallery_title)
@section('breadcrumb-subtitle', $bs->gallery_subtitle)
@section('breadcrumb-link', __('GALLERY'))

@section('content')

<!--    Gallery section start   -->
<div class="gallery-section masonry clearfix">
   <div class="container">
        <div class="grid" id="gallery">
            <div class="grid-sizer"></div>
            @foreach ($galleries as $key => $gallery)
            <div class="single-pic">
                <img class="lazy" data-src="{{asset('assets/front/img/gallery/'.$gallery->image)}}" alt="">
                <div class="single-pic-overlay"></div>
                <div class="txt-icon">
                    <div class="outer">
                        <div class="inner">
                        <h4>{{convertUtf8(strlen($gallery->title)) > 20 ? convertUtf8(substr($gallery->title, 0, 20)) . '...' : convertUtf8($gallery->title)}}</h4>
                        <a class="icon-wrapper" href="{{asset('assets/front/img/gallery/'.$gallery->image)}}" data-lightbox="single-pic" data-title="{{convertUtf8($gallery->title)}}">
                        <i class="fas fa-search-plus"></i>
                        </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
      <div class="row mt-5">
         <div class="col-md-12">
            <nav class="pagination-nav">
              {{$galleries->links()}}
            </nav>
         </div>
      </div>
   </div>
</div>
<!--    Gallery section end   -->
@endsection
