@extends('admin.layout')

@if(!empty($event->language) && $event->language->rtl == 1)
@section('styles')
<style>
    form input,
    form textarea,
    form select {
        direction: rtl;
    }
    form .note-editor.note-frame .note-editing-area .note-editable {
        direction: rtl;
        text-align: right;
    }
</style>
@endsection
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">Edit Event</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{route('admin.dashboard')}}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">Event Page</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">Edit Event</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">Edit Event</div>
          <a class="btn btn-info btn-sm float-right d-inline-block" href="{{route('admin.event.index') . '?language=' . request()->input('language')}}">
            <span class="btn-label">
              <i class="fas fa-backward" style="font-size: 12px;"></i>
            </span>
            Back
          </a>
        </div>
        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">
                {{-- Slider images upload start --}}
                <div class="px-2">
                    <label for="" class="mb-2"><strong>Slider Images **</strong></label>
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped" id="imgtable">
                                @if (!is_null($event->image))
                                    @foreach(json_decode($event->image) as $key => $img)
                                        <tr class="trdb" id="trdb{{$key}}">
                                            <td>
                                                <div class="thumbnail">
                                                    <img style="width:150px;" src="{{asset('assets/front/img/events/sliders/'.$img)}}" alt="Ad Image">
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger pull-right rmvbtndb" onclick="rmvdbimg({{$key}},{{$event->id}})">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                        </div>
                    </div>
                    <form action="" id="my-dropzone" enctype="multipart/formdata" class="dropzone create">
                        @csrf
                        <div class="fallback">
                        </div>
                    </form>
                    <p class="em text-danger mb-0" id="errimage"></p>
                </div>
                {{-- Slider images upload end --}}
                <form class="mb-3 dm-uploader modal-form" enctype="multipart/form-data" action="{{route('admin.event.upload')}}" method="POST" id="video-frm">
                    <div class="form-row px-2">
                        <div class="col-12 mb-2">
                            <label for=""><strong>Video</strong></label>
                        </div>
                        <div class="col-sm-12">
                            <div class="from-group mb-2">
                               @if(!is_null($event->video))
                                    <video width="320" height="240" controls id="video_src">
                                        <source src="{{ asset("assets/front/img/events/videos/".$event->video)}}" type="video/mp4">
                                    </video>
                                @else
                                   No video uploaded yet
                                @endif
                            </div>
                            <div class="mt-4">
                                <div role="button" class="btn btn-primary mr-2">
                                    <i class="fa fa-folder-o fa-fw"></i> Browse Files
                                    <input type="file" title='Click to add Files' id="upload-video" name="upload-video" />
                                </div>
                                <small class="status text-muted">Select a file or drag it over this area..</small>
                                <p class="em text-danger mb-0" id="errblog"></p>
                            </div>
                        </div>
                    </div>
                </form>
              <form id="ajaxForm" class="" action="{{route('admin.event.update')}}" method="post">
                @csrf
                <input type="hidden" name="event_id" value="{{$event->id}}">
                <input type="hidden" name="lang_id" value="{{$event->lang_id}}">
                <input type="hidden" id="my_video" name="video" value="{{$event->video}}">
                <div id="sliders"></div>

                <div class="form-group">
                  <label for="">Title **</label>
                  <input type="text" class="form-control" name="title" value="{{$event->title}}" placeholder="Enter title">
                  <p id="errtitle" class="mb-0 text-danger em"></p>
                </div>
                <div class="form-group">
                  <label for="">Category **</label>
                  <select class="form-control" name="cat_id">
                    <option value="" selected disabled>Select a category</option>
                    @foreach ($event_categories as $key => $event_category)
                      <option value="{{$event_category->id}}" {{$event_category->id == $event->eventCategories->id ? 'selected' : ''}}>{{$event_category->name}}</option>
                    @endforeach
                  </select>
                  <p id="errcat_id" class="mb-0 text-danger em"></p>
                </div>
                <div class="form-group">
                  <label for="">Content **</label>
                  <textarea class="form-control summernote" name="content" data-height="300" placeholder="Enter content">{{replaceBaseUrl($event->content)}}</textarea>
                  <p id="errcontent" class="mb-0 text-danger em"></p>
                </div>
                  <div class="form-group">
                      <label for="">Date</label>
                      <input type="date" class="form-control ltr" name="date" value="{{$event->date}}" placeholder="Enter Event Date">
                      <p id="errdate" class="mb-0 text-danger em"></p>
                  </div>
                  <div class="form-group">
                      <label for="">Time</label>
                      <input type="time" class="form-control ltr" name="time" value="{{\Carbon\Carbon::parse($event->time)->format('H:i:s')}}" placeholder="Enter Event Time">
                      <p id="errtime" class="mb-0 text-danger em"></p>
                  </div>
                  <div class="form-group">
                      <label for="">Cost (in {{$abx->base_currency_text}}) **</label>
                      <input type="number" class="form-control ltr" name="cost" value="{{$event->cost}}" placeholder="Enter Ticket Cost">
                      <p id="errcost" class="mb-0 text-danger em"></p>
                  </div>
                <div class="form-group">
                  <label for="">Available Tickets **</label>
                  <input type="number" class="form-control ltr" name="available_tickets" value="{{$event->available_tickets}}" placeholder="Enter Number of available tickets">
                  <p id="erravailable_tickets" class="mb-0 text-danger em"></p>
                </div>
                  <div class="form-group">
                      <label for="">Organizer</label>
                      <input type="text" class="form-control ltr" name="organizer" value="{{$event->organizer}}" placeholder="Event Organizer">
                      <p id="errorganizer" class="mb-0 text-danger em"></p>
                  </div>
                  <div class="form-group">
                      <label for="">Organizer Email</label>
                      <input type="text" class="form-control ltr" name="organizer_email" value="{{$event->organizer_email}}" placeholder="Organizer Email">
                      <p id="errorganizer_email" class="mb-0 text-danger em"></p>
                  </div>
                  <div class="form-group">
                      <label for="">Organizer Phone</label>
                      <input type="text" class="form-control ltr" name="organizer_phone" value="{{$event->organizer_phone}}" placeholder="Organizer Email">
                      <p id="errorganizer_phone" class="mb-0 text-danger em"></p>
                  </div>
                  <div class="form-group">
                      <label for="">Organizer Website</label>
                      <input type="text" class="form-control ltr" name="organizer_website" value="{{$event->organizer_website}}" placeholder="Organizer Website">
                      <p id="errserial_number" class="mb-0 text-danger em"></p>
                  </div>
                  <div class="form-group">
                      <label for="">Venue</label>
                      <input type="text" class="form-control ltr" name="venue" value="{{$event->venue}}" placeholder="Enter Venue">
                      <p id="errvenue" class="mb-0 text-danger em"></p>
                  </div>
                  <div class="form-group">
                      <label for="">Venue Location</label>
                      <input type="text" class="form-control ltr" name="venue_location" value="{{$event->venue_location}}" placeholder="Venue Location">
                      <p id="errvenue_location" class="mb-0 text-danger em"></p>
                  </div>
                  <div class="form-group">
                      <label for="">Venue Phone</label>
                      <input type="text" class="form-control ltr" name="venue_phone" value="{{$event->venue_phone}}" placeholder="Venue Phone">
                      <p id="errvenue_phone" class="mb-0 text-danger em"></p>
                  </div>
                <div class="form-group">
                  <label for="">Meta Keywords</label>
                  <input type="text" class="form-control" name="meta_tags" value="{{$event->meta_tags}}" data-role="tagsinput">
                  <p id="errmeta_keywords" class="mb-0 text-danger em"></p>
                </div>
                <div class="form-group">
                  <label for="">Meta Description</label>
                  <textarea type="text" class="form-control" name="meta_description" rows="5">{{$event->meta_description}}</textarea>
                  <p id="errmeta_description" class="mb-0 text-danger em"></p>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="form">
            <div class="form-group from-show-notify row">
              <div class="col-12 text-center">
                <button type="submit" id="submitBtn" class="btn btn-success">Update</button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $("select[name='lang_id']").on('change', function() {
                $("#bcategory").removeAttr('disabled');
                let langid = $(this).val();
                let url = "{{url('/')}}/admin/event/" + langid + "/get-categories";
                $.get(url, function(data) {
                    console.log(data);
                    let options = `<option value="" disabled selected>Select a category</option>`;
                    for (let i = 0; i < data.length; i++) {
                        options += `<option value="${data[i].id}">${data[i].name}</option>`;
                    }
                    $("#bcategory").html(options);

                });
            });

            // make input fields RTL
            $("select[name='lang_id']").on('change', function() {
                $(".request-loader").addClass("show");
                let url = "{{url('/')}}/admin/rtlcheck/" + $(this).val();
                $.get(url, function(data) {
                    $(".request-loader").removeClass("show");
                    if (data == 1) {
                        $("form input").each(function() {
                            if (!$(this).hasClass('ltr')) {
                                $(this).addClass('rtl');
                            }
                        });
                        $("form select").each(function() {
                            if (!$(this).hasClass('ltr')) {
                                $(this).addClass('rtl');
                            }
                        });
                        $("form textarea").each(function() {
                            if (!$(this).hasClass('ltr')) {
                                $(this).addClass('rtl');
                            }
                        });
                        $("form .summernote").each(function() {
                            $(this).siblings('.note-editor').find('.note-editable').addClass('rtl text-right');
                        });

                    } else {
                        $("form input, form select, form textarea").removeClass('rtl');
                        $("form.modal-form .summernote").siblings('.note-editor').find('.note-editable').removeClass('rtl text-right');
                    }
                })
            });

            // translatable portfolios will be available if the selected language is not 'Default'
            $("#language").on('change', function() {
                let language = $(this).val();
                if (language == 0) {
                    $("#translatable").attr('disabled', true);
                } else {
                    $("#translatable").removeAttr('disabled');
                }
            });

            $("#upload-video").on('change',function (event){
                let formData = new FormData($('#video-frm')[0]);
                let file = $('input[type=file]')[0].files[0];
                // formData.append('upload_video', file, file.name);
                formData.append('upload_video', file);
                $.ajax({
                    url: '{{route('admin.event.upload')}}',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    cache: false,
                    data: formData,
                    success: function(data) {
                        console.log(data.filename,"edit");
                        $("#my_video").val(data.filename);
                        var url = '{{ asset("assets/front/img/events/videos/filename") }}';
                        url = url.replace('filename', data.filename);
                        $("#video_src").attr('src',url);
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })
            })
        });
        function rmvdbimg(key,id) {
            // console.log("image id: ", key,id);
            $(".request-loader").addClass("show");
            $.ajax({
                url: "{{route('admin.event.slider-remove')}}",
                type: 'POST',
                data: {
                    _token: "{{csrf_token()}}",
                    key: key,
                    id: id
                },
                success: function(data) {
                    console.log(data);
                    $(".request-loader").removeClass("show");
                    $("#trdb"+key).remove();
                    var content = {};

                    content.message = 'Slider image deleted successfully!';
                    content.title = 'Success';
                    content.icon = 'fa fa-bell';

                    $.notify(content,{
                        type: 'success',
                        placement: {
                            from: 'top',
                            align: 'right'
                        },
                        time: 1000,
                        delay: 0,
                    });
                }
            });

        }
        function rmvimg(file_id) {
            $(`input[value='${file_id}']`).remove();
        }
        // myDropzone is the configuration for the element that has an id attribute
        // with the value my-dropzone (or myDropzone)
        Dropzone.options.myDropzone = {
            acceptedFiles: '.png, .jpg, .jpeg',
            url: "{{route('admin.event.slider')}}",
            success: function(file, response){
                console.log(response.file_id,"file id");
                $("#sliders").append(`<input type="hidden" name="image[]" id="slider${response.file_id}" value="${response.file_id}">`);
                // Create the remove button
                var removeButton = Dropzone.createElement("<button class='rmv-btn'><i class='fa fa-times'></i></button>");
                // Capture the Dropzone instance as closure.
                var _this = this;
                // Listen to the click event
                removeButton.addEventListener("click", function(e) {
                    // Make sure the button click doesn't submit the form:
                    e.preventDefault();
                    e.stopPropagation();
                    _this.removeFile(file);
                    rmvimg(response.file_id);
                });
                // Add the button to the file preview element.
                file.previewElement.appendChild(removeButton);
                if(typeof response.error != 'undefined') {
                    if (typeof response.file != 'undefined') {
                        document.getElementById('errpreimg').innerHTML = response.file[0];
                    }
                }
            }
        };
    </script>
@endsection
