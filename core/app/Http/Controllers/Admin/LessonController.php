<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lesson;
use App\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
  public function index($id)
  {
    $module = Module::findOrFail($id);
    $lessons = Lesson::where('module_id', $module->id)->paginate(10);

    return view('admin.course.lesson.index', compact('module', 'lessons'));
  }

  public function store(Request $request)
  {
    $videoFile = $request->file('video_file');
    $videoLink = $request->video_link;

    $rules = [
      'name' => 'required',
      'video' => function ($attribute, $value, $fail) use ($videoFile, $videoLink) {
        if (empty($videoFile) && empty($videoLink)) {
          $fail('The video field is required');
        }
      },
      'video_file' => function ($attribute, $value, $fail) use ($videoFile) {
        $file_size = $videoFile->getSize();
        $file_size = number_format($file_size / 1048576, 2);

        if ($file_size > 10.00) {
          $fail('Supported file size upto 10 mb');
        }
      },
      'duration' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      $validator->getMessageBag()->add('error', 'true');
      return response()->json($validator->errors());
    }

    $lesson = new Lesson;
    $lesson->module_id = $request->module_id;
    $lesson->name = $request->name;

    if ($request->hasFile('video_file')) {
      $video = $request->file('video_file');
      $video_name = time() . '.' . $video->getClientOriginalExtension();
      $directory = 'assets/front/video/lesson_videos/';
      @mkdir($directory, 0775, true);
      $video->move($directory, $video_name);
      $lesson->video_file = $video_name;
    } else {
      $lesson->video_file = null;
    }

    $lesson->video_link = $request->video_link;

    $lesson->duration = $request->duration;
    $lesson->save();

    Session::flash('success', 'Lesson Added Successfully');

    return 'success';
  }

  public function update(Request $request)
  {
    $videoOptionVal = $request->edit_video;
    $videoFile = $request->file('edit_video_file');
    $videoLink = $request->edit_video_link;

    $rules = [
      'name' => 'required',
      'edit_video' => function ($attribute, $value, $fail) use ($videoOptionVal, $videoLink) {
        if ($videoOptionVal == 2 && empty($videoLink)) {
          $fail('The video field is required');
        }
      },
      'edit_video_file' => function ($attribute, $value, $fail) use ($videoFile) {
        $file_size = $videoFile->getSize();
        $file_size = number_format($file_size / 1048576, 2);

        if ($file_size > 10.00) {
          $fail('Supported file size upto 10 mb');
        }
      },
      'duration' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      $validator->getMessageBag()->add('error', 'true');
      return response()->json($validator->errors());
    }

    $lesson = Lesson::findOrFail($request->lesson_id);
    $lesson->name = $request->name;

    if ($request->hasFile('edit_video_file')) {
      $video = $request->file('edit_video_file');

      if (File::exists('assets/front/video/lesson_videos/' . $lesson->video_file)) {
        File::delete('assets/front/video/lesson_videos/' . $lesson->video_file);
      }

      $video_name = time() . '.' . $video->getClientOriginalExtension();
      $directory = 'assets/front/video/lesson_videos/';
        @mkdir($directory, 0775, true);
      $video->move($directory, $video_name);

      // if there has video file then video link will be null in the database
      $lesson->video_file = $video_name;
      $lesson->video_link = null;
    } else if (!empty($request->edit_video_link)) {
      if (File::exists('assets/front/video/lesson_videos/' . $lesson->video_file)) {
        File::delete('assets/front/video/lesson_videos/' . $lesson->video_file);
      }

      $lesson->video_link = $request->edit_video_link;

      // if there has video link then video file will be null in the database
      $lesson->video_file = null;
    }

    $lesson->duration = $request->duration;
    $lesson->save();

    Session::flash('success', 'Lesson Updated Successfully');

    return 'success';
  }

  public function delete(Request $request)
  {
    $lesson = Lesson::findOrFail($request->lesson_id);

    if (File::exists('assets/front/video/lesson_videos/' . $lesson->video_file)) {
      File::delete('assets/front/video/lesson_videos/' . $lesson->video_file);
    }

    $lesson->delete();

    Session::flash('success', 'Lesson Deleted Successfully');

    return back();
  }

  public function bulkDelete(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $lesson = Lesson::findOrFail($id);

      if (File::exists('assets/front/video/lesson_videos/' . $lesson->video_file)) {
        File::delete('assets/front/video/lesson_videos/' . $lesson->video_file);
      }

      $lesson->delete();
    }

    Session::flash('success', 'Lessons Deleted Successfully');

    return 'success';
  }
}
