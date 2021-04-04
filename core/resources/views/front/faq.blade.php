@extends("front.$version.layout")

@section('pagename')
 - {{__('FAQ')}}
@endsection

@section('meta-keywords', "$be->faq_meta_keywords")
@section('meta-description', "$be->faq_meta_description")


@section('breadcrumb-title', convertUtf8($bs->faq_title))
@section('breadcrumb-subtitle', convertUtf8($bs->faq_subtitle))
@section('breadcrumb-link', __('FAQS'))


@section('content')


<!--   FAQ section start   -->
<div class="faq-section">
   <div class="container">
      <div class="row">
         <div class="col-lg-6">
            <div class="accordion" id="accordionExample1">
               @for ($i=0; $i < ceil(count($faqs)/2); $i++)
               <div class="card">
                  <div class="card-header" id="heading{{$faqs[$i]->id}}">
                     <h2 class="mb-0">
                        <button class="btn btn-link collapsed btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse{{$faqs[$i]->id}}" aria-expanded="false" aria-controls="collapse{{$faqs[$i]->id}}">
                        {{convertUtf8($faqs[$i]->question)}}
                        </button>
                     </h2>
                  </div>
                  <div id="collapse{{$faqs[$i]->id}}" class="collapse" aria-labelledby="heading{{$faqs[$i]->id}}" data-parent="#accordionExample1">
                     <div class="card-body">
                        {{convertUtf8($faqs[$i]->answer)}}
                     </div>
                  </div>
               </div>
               @endfor
            </div>
         </div>
         <div class="col-lg-6">
            <div class="accordion" id="accordionExample2">
               @for ($i=ceil(count($faqs)/2); $i < count($faqs); $i++)
               <div class="card">
                  <div class="card-header" id="heading{{$faqs[$i]->id}}">
                     <h2 class="mb-0">
                        <button class="btn btn-link collapsed btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse{{$faqs[$i]->id}}" aria-expanded="false" aria-controls="collapse{{$faqs[$i]->id}}">
                        {{convertUtf8($faqs[$i]->question)}}
                        </button>
                     </h2>
                  </div>
                  <div id="collapse{{$faqs[$i]->id}}" class="collapse" aria-labelledby="heading{{$faqs[$i]->id}}" data-parent="#accordionExample2">
                     <div class="card-body">
                        {{convertUtf8($faqs[$i]->answer)}}
                     </div>
                  </div>
               </div>
               @endfor
            </div>
         </div>
      </div>
   </div>
</div>
<!--   FAQ section end   -->
@endsection
