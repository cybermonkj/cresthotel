<?php

namespace App\Http\Controllers\Admin;

use App\BasicExtended;
use App\BasicExtra;
use App\Course;
use App\CourseCategory;
use App\CoursePurchase;
use App\Http\Controllers\Controller;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $language = Language::where('code', $request->language)->first();
        $language_id = $language->id;

        $courses = Course::where('language_id', $language_id)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.course.course.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.course.course.create');
    }

    public function uploadCourseImage(Request $request)
    {

        $course_img = $request->file('file');
        $img_name = time() . '.' . $course_img->getClientOriginalExtension();
        $dir = 'assets/front/img/courses/';
        @mkdir($dir, 0777, true);
        $course_img->move($dir, $img_name);
        $request->session()->put('course_image', $img_name);

        return response()->json([
            'status' => 'session_put'
        ]);
    }

    public function uploadInstructorImage(Request $request)
    {

        $instructor_img = $request->file('file');
        $img_name = time() . '.' . $instructor_img->getClientOriginalExtension();
        $directory = "assets/front/img/instructors/";
        @mkdir($directory, 0775, true);
        $instructor_img->move($directory, $img_name);
        $request->session()->put('instructor_image', $img_name);

        return response()->json([
            'status' => 'session_put'
        ]);
    }

    public function getCategories($langId)
    {
        $course_categories = CourseCategory::where('language_id', $langId)
            ->where('status', 1)
            ->get();

        return $course_categories;
    }

    public function store(Request $request)
    {
        $rules = [
            // 'course_image' => 'required',
            'language_id' => 'required',
            'course_category_id' => 'required',
            'title' => 'required',
            'duration' => 'required',
            'video_link' => 'required',
            'overview' => 'required',
            'instructor_name' => 'required',
            'instructor_occupation' => 'required',
            'instructor_details' => 'required',
            // 'instructor_image' => 'required'
        ];

        $messages = [
            'language_id.required' => 'The language field is required',
            'course_category_id.required' => 'The course category field is required'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $course = new Course;
        $course->language_id = $request->language_id;
        $course->course_category_id = $request->course_category_id;
        $course->title = $request->title;
        $course->slug = slug_create($request->title);
        $course->duration = $request->duration;
        $course->current_price = $request->current_price;
        $course->previous_price = $request->previous_price;
        $course->summary = $request->summary;
        $course->course_image = session('course_image');

        $link = $request->video_link;

        if (strpos($link, "&") != 0) {
            $custom_link = substr($link, 0, strpos($link, "&"));
            $course->video_link = $custom_link;
        } else {
            $course->video_link = $request->video_link;
        }

        $course->overview = $request->overview;
        $course->instructor_image = session('instructor_image');
        $course->instructor_name = $request->instructor_name;
        $course->instructor_occupation = $request->instructor_occupation;
        $course->instructor_details = $request->instructor_details;
        $course->instructor_facebook = $request->instructor_facebook;
        $course->instructor_instagram = $request->instructor_instagram;
        $course->instructor_twitter = $request->instructor_twitter;
        $course->instructor_linkedin = $request->instructor_linkedin;
        $course->save();

        Session::flash('success', 'Course Added Successfully');

        return 'success';
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $course_categories = CourseCategory::where('language_id', $course->language_id)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.course.course.edit', compact('course', 'course_categories'));
    }

    public function updateCourseImage(Request $request, $id)
    {
        if ($request->hasFile('file')) {
            $course_img = $request->file('file');
            $course = Course::findOrFail($id);

            if (File::exists('assets/front/img/courses/' . $course->course_image)) {
                File::delete('assets/front/img/courses/' . $course->course_image);
            }

            $img_name = time() . '.' . $course_img->getClientOriginalExtension();
            $directory = 'assets/front/img/courses/';
            @mkdir($directory, 0775, true);
            $course_img->move($directory, $img_name);
            $course->course_image = $img_name;
            $course->save();
        }

        return response()->json([
            'status' => 'success',
            'image' => 'Course image'
        ]);
    }

    public function updateInstructorImage(Request $request, $id)
    {
        if ($request->hasFile('file')) {
            $instructor_img = $request->file('file');
            $course = Course::findOrFail($id);

            if (File::exists('assets/front/img/instructors/' . $course->instructor_image)) {
                File::delete('assets/front/img/instructors/' . $course->instructor_image);
            }

            $img_name = time() . '.' . $instructor_img->getClientOriginalExtension();
            $directory = 'assets/front/img/instructors/';
            @mkdir($directory, 0775, true);
            $instructor_img->move($directory, $img_name);
            $course->instructor_image = $img_name;
            $course->save();
        }

        return response()->json([
            'status' => 'success',
            'image' => 'Instructor image'
        ]);
    }

    public function update(Request $request)
    {
        $rules = [
            'title' => 'required',
            'duration' => 'required',
            'video_link' => 'required',
            'overview' => 'required',
            'instructor_name' => 'required',
            'instructor_occupation' => 'required',
            'instructor_details' => 'required'
        ];

        $messages = [
            'language_id.required' => 'The language field is required',
            'course_category_id.required' => 'The course category field is required'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $course = Course::findOrFail($request->course_id);
        $course->course_category_id = $request->course_category_id;
        $course->title = $request->title;
        $course->slug = slug_create($request->title);
        $course->current_price = $request->current_price;
        $course->previous_price = $request->previous_price;
        $course->duration = $request->duration;
        $course->summary = $request->summary;

        $link = $request->video_link;

        if (strpos($link, "&") != 0) {
            $custom_link = substr($link, 0, strpos($link, "&"));
            $course->video_link = $custom_link;
        } else {
            $course->video_link = $request->video_link;
        }

        $course->overview = $request->overview;
        $course->instructor_name = $request->instructor_name;
        $course->instructor_occupation = $request->instructor_occupation;
        $course->instructor_details = $request->instructor_details;
        $course->instructor_facebook = $request->instructor_facebook;
        $course->instructor_instagram = $request->instructor_instagram;
        $course->instructor_twitter = $request->instructor_twitter;
        $course->instructor_linkedin = $request->instructor_linkedin;
        $course->save();

        Session::flash('success', 'Course Updated Successfully');

        return 'success';
    }

    public function delete(Request $request)
    {
        $course = Course::findOrFail($request->course_id);

        if ($course->modules->count() > 1) {
            Session::flash('warning', 'First Delete All The Modules of This Course');

            return back();
        }

        if (File::exists('assets/front/img/courses/' . $course->course_image)) {
            File::delete('assets/front/img/courses/' . $course->course_image);
        }
        if (File::exists('assets/front/img/instructors/' . $course->instructor_image)) {
            File::delete('assets/front/img/instructors/' . $course->instructor_image);
        }

        $course->delete();

        Session::flash('success', 'Course Deleted Successfully');

        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $course = Course::findOrFail($id);

            if ($course->modules->count() > 1) {
                Session::flash('warning', 'First Delete All The Modules of Those Courses');

                return 'success';
            }
        }

        foreach ($ids as $id) {
            $course = Course::findOrFail($id);

            if (File::exists('assets/front/img/courses/' . $course->course_image)) {
                File::delete('assets/front/img/courses/' . $course->course_image);
            }
            if (File::exists('assets/front/img/instructors/' . $course->instructor_image)) {
                File::delete('assets/front/img/instructors/' . $course->instructor_image);
            }

            $course->delete();
        }

        Session::flash('success', 'Courses Deleted Successfully');

        return 'success';
    }

    public function featured(Request $request)
    {
        $course = Course::findOrFail($request->course_id);
        $course->is_featured = $request->is_featured;
        $course->save();

        if ($request->is_featured == 1) {
            Session::flash('success', 'This Course Has Featured');
        } else {
            Session::flash('success', 'This Course Has Unfeatured');
        }

        return back();
    }

    public function settings()
    {
        $data['abex'] = BasicExtra::first();
        return view('admin.course.settings', $data);
    }

    public function updateSettings(Request $request)
    {
        $bexs = BasicExtra::all();
        foreach ($bexs as $bex) {
            $bex->is_course = $request->is_course;
            $bex->save();
        }

        $request->session()->flash('success', 'Settings updated successfully!');
        return back();
    }

    public function purchaseLog(Request $request) {
        $orderNum = $request->order_number;
        $data['purchases'] = CoursePurchase::orderBy('id', 'DESC')
                            ->when($orderNum, function ($query, $orderNum) {
                                return $query->where('order_number', $orderNum);
                            })
                            ->paginate(9);
        return view('admin.course.course.purchase', $data);
    }


    public function purchasePaymentStatus(Request $request)
    {
        $purchase = CoursePurchase::findOrFail($request->purchase_id);
        $purchase->payment_status = $request->payment_status;
        $purchase->save();

        $be = BasicExtended::first();
        $sub = 'Payment Status Updated';

        $to = $purchase->email;
        $fname = $purchase->first_name;

        // Send Mail to Buyer
        $mail = new PHPMailer(true);
        if ($be->is_smtp == 1) {
            try {
                $mail->isSMTP();
                $mail->Host       = $be->smtp_host;
                $mail->SMTPAuth   = true;
                $mail->Username   = $be->smtp_username;
                $mail->Password   = $be->smtp_password;
                $mail->SMTPSecure = $be->encryption;
                $mail->Port       = $be->smtp_port;

                //Recipients
                $mail->setFrom($be->from_mail, $be->from_name);
                $mail->addAddress($to, $fname);

                // Content
                $mail->isHTML(true);
                $mail->Subject = $sub;
                $mail->Body    = 'Hello <strong>' . $fname . '</strong>,<br/>Your payment status for course <strong>' . $purchase->course->title . '</strong> is changed to ' . $request->payment_status . '.<br/>Thank you.';
                $mail->send();
            } catch (Exception $e) {
                // die($e->getMessage());
            }
        } else {
            try {

                //Recipients
                $mail->setFrom($be->from_mail, $be->from_name);
                $mail->addAddress($to, $fname);


                // Content
                $mail->isHTML(true);
                $mail->Subject = $sub;
                $mail->Body    = 'Hello <strong>' . $fname . '</strong>,<br/>Your payment status for course <strong>' . $purchase->course->title . '</strong> is changed to ' . $request->payment_status . '.<br/>Thank you.';

                $mail->send();
            } catch (Exception $e) {
                // die($e->getMessage());
            }
        }

        Session::flash('success', 'Payment status changed successfully!');
        return back();
    }


    public function purchaseBulkOrderDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $purchase = CoursePurchase::findOrFail($id);
            @unlink('assets/front/receipt/'.$purchase->receipt);
            @unlink('assets/front/invoices/course/'.$purchase->invoice);
            $purchase->delete();
        }

        Session::flash('success', 'Deleted successfully!');
        return "success";
    }

    public function purchaseDelete(Request $request)
    {
        $purchase = CoursePurchase::findOrFail($request->purchase_id);
        @unlink('assets/front/invoices/course/'.$purchase->invoice);
        @unlink('assets/front/receipt/'.$purchase->receipt);
        $purchase->delete();

        Session::flash('success', 'Deleted successfully!');
        return back();
    }
}
