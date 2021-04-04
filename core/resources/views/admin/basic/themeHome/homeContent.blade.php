<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-4">
                <div class="card-title">Setup Content of Selected Home</div>
            </div>
            <div class="col-lg-3 offset-lg-5">
                @if (!empty($langs))
                    <select name="language" class="form-control" onchange="window.location='{{url()->current() . '?language='}}'+this.value">
                        <option value="" selected disabled>Select a Language</option>
                        @foreach ($langs as $lang)
                            <option value="{{$lang->code}}" {{$lang->code == request()->input('language') ? 'selected' : ''}}>{{$lang->name}}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>
    </div>
<div class="card-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-striped mt-3">
                  <thead>
                      <tr>
                          <th scope="col">Theme</th>
                          <th scope="col">Contents</th>
                      </tr>
                  </thead>
                  <tbody>
                      @if ($be->theme_version == 'default_service_category')
                        <tr>
                            <td>Default Version (With Service Category)</td>
                            <td>
                                <a target="_blank" href="{{route('admin.pagebuilder.content', ['type' => 'themeHome', 'theme' => 'default_service_category', 'language' => request()->input('language')])}}" class="btn btn-warning">
                                    Edit
                                </a>
                            </td>
                        </tr>
                      @elseif ($be->theme_version == 'default_no_category')
                        <tr>
                            <td>Default Version (Without Service Category)</td>
                            <td>
                                <a target="_blank" href="{{route('admin.pagebuilder.content', ['type' => 'themeHome', 'theme' => 'default_no_category', 'language' => request()->input('language')])}}" class="btn btn-warning">
                                    Edit
                                </a>
                            </td>
                        </tr>
                      @elseif ($be->theme_version == 'dark_service_category')
                        <tr>
                            <td>Dark Version (With Service Category)</td>
                            <td>
                                <a target="_blank" href="{{route('admin.pagebuilder.content', ['type' => 'themeHome', 'theme' => 'dark_service_category', 'language' => request()->input('language')])}}" class="btn btn-warning">
                                    Edit
                                </a>
                            </td>
                        </tr>
                      @elseif ($be->theme_version == 'dark_no_category')
                        <tr>
                            <td>Dark Version (Without Service Category)</td>
                            <td>
                                <a target="_blank" href="{{route('admin.pagebuilder.content', ['type' => 'themeHome', 'theme' => 'dark_no_category', 'language' => request()->input('language')])}}" class="btn btn-warning">
                                    Edit
                                </a>
                            </td>
                        </tr>
                      @elseif ($be->theme_version == 'gym_service_category')
                        <tr>
                            <td>Gym Version (With Service Category)</td>
                            <td>
                                <a target="_blank" href="{{route('admin.pagebuilder.content', ['type' => 'themeHome', 'theme' => 'gym_service_category', 'language' => request()->input('language')])}}" class="btn btn-warning">
                                    Edit
                                </a>
                            </td>
                        </tr>
                      @elseif ($be->theme_version == 'gym_no_category')
                        <tr>
                            <td>Gym Version (Without Service Category)</td>
                            <td>
                                <a target="_blank" href="{{route('admin.pagebuilder.content', ['type' => 'themeHome', 'theme' => 'gym_no_category', 'language' => request()->input('language')])}}" class="btn btn-warning">
                                    Edit
                                </a>
                            </td>
                        </tr>
                      @elseif ($be->theme_version == 'car_service_category')
                        <tr>
                            <td>Car Version (With Service Category)</td>
                            <td>
                                <a target="_blank" href="{{route('admin.pagebuilder.content', ['type' => 'themeHome', 'theme' => 'car_service_category', 'language' => request()->input('language')])}}" class="btn btn-warning">
                                    Edit
                                </a>
                            </td>
                        </tr>
                      @elseif ($be->theme_version == 'car_no_category')
                          <tr>
                              <td>Car Version (Without Service Category)</td>
                              <td>
                                  <a target="_blank" href="{{route('admin.pagebuilder.content', ['type' => 'themeHome', 'theme' => 'car_no_category', 'language' => request()->input('language')])}}" class="btn btn-warning">
                                      Edit
                                  </a>
                              </td>
                          </tr>
                        @elseif ($be->theme_version == 'cleaning_service_category')
                            <tr>
                                <td>Cleaning Version (With Service Category)</td>
                                <td>
                                    <a target="_blank" href="{{route('admin.pagebuilder.content', ['type' => 'themeHome', 'theme' => 'cleaning_service_category', 'language' => request()->input('language')])}}" class="btn btn-warning">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @elseif ($be->theme_version == 'cleaning_no_category')
                            <tr>
                                <td>Cleaning Version (Without Service Category)</td>
                                <td>
                                    <a target="_blank" href="{{route('admin.pagebuilder.content', ['type' => 'themeHome', 'theme' => 'cleaning_no_category', 'language' => request()->input('language')])}}" class="btn btn-warning">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @elseif ($be->theme_version == 'construction_service_category')
                            <tr>
                                <td>Construction Version (With Service Category)</td>
                                <td>
                                    <a target="_blank" href="{{route('admin.pagebuilder.content', ['type' => 'themeHome', 'theme' => 'construction_service_category', 'language' => request()->input('language')])}}" class="btn btn-warning">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @elseif ($be->theme_version == 'construction_no_category')
                            <tr>
                                <td>Construction Version (Without Service Category)</td>
                                <td>
                                    <a target="_blank" href="{{route('admin.pagebuilder.content', ['type' => 'themeHome', 'theme' => 'construction_no_category', 'language' => request()->input('language')])}}" class="btn btn-warning">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @elseif ($be->theme_version == 'logistic_service_category')
                            <tr>
                                <td>Logistic Version (With Service Category)</td>
                                <td>
                                    <a target="_blank" href="{{route('admin.pagebuilder.content', ['type' => 'themeHome', 'theme' => 'logistic_service_category', 'language' => request()->input('language')])}}" class="btn btn-warning">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @elseif ($be->theme_version == 'logistic_no_category')
                            <tr>
                                <td>Logistic Version (Without Service Category)</td>
                                <td>
                                    <a target="_blank" href="{{route('admin.pagebuilder.content', ['type' => 'themeHome', 'theme' => 'logistic_no_category', 'language' => request()->input('language')])}}" class="btn btn-warning">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @elseif ($be->theme_version == 'lawyer_service_category')
                            <tr>
                                <td>Lawyer Version (With Service Category)</td>
                                <td>
                                    <a target="_blank" href="{{route('admin.pagebuilder.content', ['type' => 'themeHome', 'theme' => 'lawyer_service_category', 'language' => request()->input('language')])}}" class="btn btn-warning">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @elseif ($be->theme_version == 'lawyer_no_category')
                            <tr>
                                <td>Lawyer Version (Without Service Category)</td>
                                <td>
                                    <a target="_blank" href="{{route('admin.pagebuilder.content', ['type' => 'themeHome', 'theme' => 'lawyer_no_category', 'language' => request()->input('language')])}}" class="btn btn-warning">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                      @endif
                    </tbody>
                  </table>
              </div>
        </div>
    </div>
</div>
</div>
