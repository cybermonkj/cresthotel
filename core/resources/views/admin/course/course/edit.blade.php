@extends('admin.layout')

@if(!empty($course->language) && $course->language->rtl == 1)
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
  <h4 class="page-title">Edit Course</h4>
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
      <a href="#">Course Page</a>
    </li>
    <li class="separator">
      <i class="flaticon-right-arrow"></i>
    </li>
    <li class="nav-item">
      <a href="#">Edit Course</a>
    </li>
  </ul>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <div class="card-title d-inline-block">Edit Course</div>
        <a
          class="btn btn-info btn-sm float-right d-inline-block"
          href="{{route('admin.course.index') . '?language=' . request()->input('language')}}"
        >
          <span class="btn-label">
            <i
              class="fas fa-backward"
              style="font-size: 12px;"
            ></i>
          </span>
          Back
        </a>
      </div>

      <div class="card-body pt-5 pb-5">
        <div class="row">
          <div class="col-lg-6 offset-lg-3">
            <form
              class="mb-3 dm-uploader drag-and-drop-zone"
              enctype="multipart/form-data"
              action="{{route('admin.course_image.update', $course->id)}}"
              method="POST"
            >
              <div class="form-row px-2">
                <div class="col-12 mb-2">
                  <label for=""><strong>Course Image **</strong></label>
                </div>

                <div class="col-md-12 d-md-block d-sm-none mb-3">
                  <img
                    src="{{asset('assets/front/img/courses/'.$course->course_image)}}"
                    alt="..."
                    class="img-thumbnail"
                  >
                </div>

                <div class="col-sm-12">
                  <div class="from-group mb-2">
                    <input
                      type="text"
                      class="form-control progressbar"
                      aria-describedby="fileHelp"
                      placeholder="No image uploaded"
                      readonly="readonly"
                    />
                    <div class="progress mb-2 d-none">
                      <div
                        class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                        role="progressbar"
                        style="width: 0%;"
                        aria-valuenow="0"
                        aria-valuemin="0"
                        aria-valuemax="0"
                      >
                        0%
                      </div>
                    </div>
                  </div>

                  <div class="mt-4">
                    <div
                      role="button"
                      class="btn btn-primary mr-2"
                    >
                      Browse File
                      <input
                        type="file"
                        title='Click to add file'
                      />
                    </div>
                    <small class="status text-muted">Select a file or drag it over on this area.</small>
                    <p class="mb-0 text-danger em"></p>
                  </div>
                </div>
              </div>
            </form>

            <form
              id="ajaxForm"
              action="{{route('admin.course.update')}}"
              method="POST"
              enctype="multipart/form-data"
            >
              @csrf
              <input
                type="hidden"
                id="image"
                name="course_id"
                value="{{ $course->id }}"
              >

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Course Category **</label>
                    <select
                      id="course_category_id"
                      class="form-control"
                      name="course_category_id"
                    >
                      <option
                        value=""
                        selected
                        disabled
                      >Select a Category</option>
                      @foreach ($course_categories as $course_category)
                      <option
                        value={{ $course_category->id }}
                        {{ $course_category->id == $course->course_category_id ? 'selected' : '' }}
                      >{{ $course_category->name }}</option>
                      @endforeach
                    </select>
                    <p
                      id="errcourse_category_id"
                      class="mb-0 text-danger em"
                    ></p>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Course Title **</label>
                    <input
                      type="text"
                      class="form-control"
                      name="title"
                      placeholder="Enter Title"
                      value="{{ $course->title }}"
                    >
                    <p
                      id="errtitle"
                      class="mb-0 text-danger em"
                    ></p>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Current Price ({{$bex->base_currency_text}})</label>
                    <input
                      type="number"
                      class="form-control ltr"
                      name="current_price"
                      placeholder="Enter Current Price"
                      value="{{ $course->current_price }}"
                    >
                    <p class="mb-0 text-danger em"></p>
                    <p class="mb-0 text-warning">Leave it blank if it's a free course</p>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Previous Price ({{$bex->base_currency_text}})</label>
                    <input
                      type="number"
                      class="form-control ltr"
                      name="previous_price"
                      placeholder="Enter Previous Price"
                      value="{{ $course->previous_price }}"
                    >
                    <p class="mb-0 text-danger em"></p>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Course Duration **</label>
                    <input
                      type="text"
                      class="form-control ltr"
                      name="duration"
                      placeholder="eg: 10h 15m"
                      value="{{ $course->duration }}"
                    >
                    <p
                      id="errduration"
                      class="mb-0 text-danger em"
                    ></p>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="">Course Summary</label>
                <textarea
                  class="form-control"
                  name="summary"
                  rows="6"
                  cols="80"
                  placeholder="Enter Course Summary"
                >{{ $course->summary }}</textarea>
                <p class="mb-0 text-danger em"></p>
              </div>

              <div class="form-group mb-1">
                <label for="">Course Video **</label>
                <input
                  type="text"
                  class="form-control ltr"
                  name="video_link"
                  placeholder="Enter YouTube Video Link"
                  value="{{ $course->video_link }}"
                >
                <p
                  id="errvideo_link"
                  class="mb-0 text-danger em"
                ></p>
              </div>

              <div class="form-group">
                <label for="">Course Overview **</label>
                <textarea
                  class="form-control summernote"
                  name="overview"
                  rows="8"
                  cols="80"
                  placeholder="Enter Overview"
                >{{ $course->overview }}</textarea>
                <p
                  id="erroverview"
                  class="mb-0 text-danger em"
                ></p>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Instructor Name **</label>
                    <input
                      type="text"
                      class="form-control"
                      name="instructor_name"
                      placeholder="Enter Instructor Name"
                      value="{{ $course->instructor_name }}"
                    >
                    <p
                      id="errinstructor_name"
                      class="mb-0 text-danger em"
                    ></p>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Instructor Occupation **</label>
                    <input
                      type="text"
                      class="form-control"
                      name="instructor_occupation"
                      placeholder="Enter Instructor Occupation"
                      value="{{ $course->instructor_occupation }}"
                    >
                    <p
                      id="errinstructor_occupation"
                      class="mb-0 text-danger em"
                    ></p>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="">Instructor Details **</label>
                <textarea
                  class="form-control"
                  name="instructor_details"
                  rows="6"
                  cols="80"
                  placeholder="Enter Instructor Details"
                >{{ $course->instructor_details }}</textarea>
                <p
                  id="errinstructor_details"
                  class="mb-0 text-danger em"
                ></p>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Instructor Facebook</label>
                    <input
                      type="text"
                      class="form-control"
                      name="instructor_facebook"
                      placeholder="Enter Faecbook ID"
                      value="{{ $course->instructor_facebook }}"
                    >
                    <p class="mb-0 text-danger em"></p>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Instructor Instagram</label>
                    <input
                      type="text"
                      class="form-control"
                      name="instructor_instagram"
                      placeholder="Enter Instagram ID"
                      value="{{ $course->instructor_instagram }}"
                    >
                    <p class="mb-0 text-danger em"></p>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Instructor Twitter</label>
                    <input
                      type="text"
                      class="form-control"
                      name="instructor_twitter"
                      placeholder="Enter Twitter ID"
                      value="{{ $course->instructor_twitter }}"
                    >
                    <p class="mb-0 text-danger em"></p>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Instructor LinkedIn</label>
                    <input
                      type="text"
                      class="form-control"
                      name="instructor_linkedin"
                      placeholder="Enter LinkedIn ID"
                      value="{{ $course->instructor_linkedin }}"
                    >
                    <p class="mb-0 text-danger em"></p>
                  </div>
                </div>
              </div>
            </form>

            <form
              class="mb-3 mt-2 dm-uploader drag-and-drop-zone"
              action="{{route('admin.instructor_image.update', $course->id)}}"
              method="POST"
              enctype="multipart/form-data"
            >
              <div class="form-row px-2">
                <div class="col-12 mb-2">
                  <label for=""><strong>Instructor Image **</strong></label>
                </div>

                <div class="col-md-12 d-md-block d-sm-none mb-3">
                  <img
                    src="{{asset('assets/front/img/instructors/'.$course->instructor_image)}}"
                    alt="..."
                    class="img-thumbnail"
                  >
                </div>

                <div class="col-sm-12">
                  <div class="from-group mb-2">
                    <input
                      type="text"
                      class="form-control progressbar"
                      aria-describedby="fileHelp"
                      placeholder="No image uploaded"
                      readonly="readonly"
                    />
                    <div class="progress mb-2 d-none">
                      <div
                        class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                        role="progressbar"
                        style="width: 0%;"
                        aria-valuenow="0"
                        aria-valuemin="0"
                        aria-valuemax="0"
                      >
                        0%
                      </div>
                    </div>
                  </div>

                  <div class="mt-4">
                    <div
                      role="button"
                      class="btn btn-primary mr-2"
                    >
                      Browse File
                      <input
                        type="file"
                        title='Click to add file'
                      />
                    </div>
                    <small class="status text-muted">Select a file or drag it over on this area.</small>
                    <p class="em text-danger mb-0"></p>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="card-footer">
        <div class="form">
          <div class="form-group from-show-notify row">
            <div class="col-12 text-center">
              <button
                type="submit"
                id="submitBtn"
                class="btn btn-success"
              >Update</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
