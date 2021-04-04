@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">Home Versions</h4>
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
        <a href="#">Basic Settings</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">Home Versions</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3>Home & Theme</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <form action="{{route('admin.homeTheme.update')}}" id="themeForm" method="POST">
                            @csrf
                            <div class="row no-gutters">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Select a Theme **</label>
                                        <select class="form-control" name="theme_version">
                                            <option value="" selected disabled>Select a Theme</option>
                                            <option value="default_service_category" {{$abe->theme_version == 'default_service_category' ? 'selected' : ''}}>Default Version (With Service Category)</option>
                                            <option value="default_no_category" {{$abe->theme_version == 'default_no_category' ? 'selected' : ''}}>Default Version (Without Service Category)</option>
                                            <option value="dark_service_category" {{$abe->theme_version == 'dark_service_category' ? 'selected' : ''}}>Dark Version (With Service Category)</option>
                                            <option value="dark_no_category" {{$abe->theme_version == 'dark_no_category' ? 'selected' : ''}}>Dark Version (Without Service Category)</option>
                                            <option value="gym_service_category" {{$abe->theme_version == 'gym_service_category' ? 'selected' : ''}}>Gym Version (With Service Category)</option>
                                            <option value="gym_no_category" {{$abe->theme_version == 'gym_no_category' ? 'selected' : ''}}>Gym Version (Without Service Category)</option>
                                            <option value="car_service_category" {{$abe->theme_version == 'car_service_category' ? 'selected' : ''}}>Car Version (With Service Category)</option>
                                            <option value="car_no_category" {{$abe->theme_version == 'car_no_category' ? 'selected' : ''}}>Car Version (Without Service Category)</option>
                                            <option value="cleaning_service_category" {{$abe->theme_version == 'cleaning_service_category' ? 'selected' : ''}}>Cleaning Version (With Service Category)</option>
                                            <option value="cleaning_no_category" {{$abe->theme_version == 'cleaning_no_category' ? 'selected' : ''}}>Cleaning Version (Without Service Category)</option>
                                            <option value="construction_service_category" {{$abe->theme_version == 'construction_service_category' ? 'selected' : ''}}>Construction Version (With Service Category)</option>
                                            <option value="construction_no_category" {{$abe->theme_version == 'construction_no_category' ? 'selected' : ''}}>Construction Version (Without Service Category)</option>
                                            <option value="logistic_service_category" {{$abe->theme_version == 'logistic_service_category' ? 'selected' : ''}}>Logistic Version (With Service Category)</option>
                                            <option value="logistic_no_category" {{$abe->theme_version == 'logistic_no_category' ? 'selected' : ''}}>Logistic Version (Without Service Category)</option>
                                            <option value="lawyer_service_category" {{$abe->theme_version == 'lawyer_service_category' ? 'selected' : ''}}>Lawyer Version (With Service Category)</option>
                                            <option value="lawyer_no_category" {{$abe->theme_version == 'lawyer_no_category' ? 'selected' : ''}}>Lawyer Version (Without Service Category)</option>
                                        </select>
                                      </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Select a Home Version</label>
                                        <select name="home_version" class="form-control">
                                            <option value="" selected disabled>Select a Home Version</option>
                                            <option value="static" {{$bs->home_version == 'static' ? 'selected' : ''}}>Static</option>
                                            <option value="slider" {{$bs->home_version == 'slider' ? 'selected' : ''}}>Slider</option>
                                            <option value="video" {{$bs->home_version == 'video' ? 'selected' : ''}}>Video</option>
                                            <option value="water" {{$bs->home_version == 'water' ? 'selected' : ''}}>Water</option>
                                            <option value="particles" {{$bs->home_version == 'particles' ? 'selected' : ''}}>Particles</option>
                                            <option value="parallax" {{$bs->home_version == 'parallax' ? 'selected' : ''}}>Parallax</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-success" form="themeForm">Update</button>
            </div>
        </div>

        @includeIf('admin.basic.themeHome.homeContent')
    </div>
  </div>



@endsection


@section('scripts')
  <script>
  $('.confirmbtn').on('click', function(e) {
    e.preventDefault();
    swal({
      title: 'Are you sure?',
      text: "You want to make this version as your home!",
      type: 'warning',
      buttons:{
        confirm: {
          text : 'Confirm!',
          className : 'btn btn-success'
        },
        cancel: {
          visible: true,
          className: 'btn btn-danger'
        }
      }
    }).then((Delete) => {
      if (Delete) {
        $(this).parent(".deleteform").submit();
      } else {
        swal.close();
      }
    });
  });
  </script>
@endsection
