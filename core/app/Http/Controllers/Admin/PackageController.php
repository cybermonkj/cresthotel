<?php

namespace App\Http\Controllers\Admin;

use App\BasicExtended;
use App\BasicExtra;
use App\BasicSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Package;
use App\PackageOrder;
use App\Language;
use App\PackageInput;
use App\PackageInputOption;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Validator;
use Session;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->first();

        $lang_id = $lang->id;
        $data['packages'] = Package::where('language_id', $lang_id)->orderBy('id', 'DESC')->paginate(10);
        $data['abx'] = $lang->basic_extra;

        $data['lang_id'] = $lang_id;

        return view('admin.package.index', $data);
    }

    public function edit($id)
    {
        $data['package'] = Package::findOrFail($id);
        $abe = BasicExtended::where('language_id', $data['package']->language_id)->first();
        $abx = BasicExtra::select('base_currency_text')->where('language_id', $data['package']->language_id)->first();

        $data['abe'] = $abe;
        $data['abx'] = $abx;
        return view('admin.package.edit', $data);
    }

    public function form(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abs'] = $lang->basic_setting;
        $data['inputs'] = PackageInput::where('language_id', $data['lang_id'])->get();

        $data['ndaIn'] = PackageInput::find(1);
        return view('admin.package.form', $data);
    }

    public function formstore(Request $request)
    {

        $inname = make_input_name($request->label);
        $inputs = PackageInput::where('language_id', $request->language_id)->get();

        $messages = [
            'options.*.required_if' => 'Options are required if field type is select dropdown/checkbox',
            'placeholder.required_unless' => 'The placeholder field is required unless field type is Checkbox or File'
        ];

        $rules = [
            'label' => [
                'required',
                function ($attribute, $value, $fail) use ($inname, $inputs) {
                    foreach ($inputs as $key => $input) {
                        if ($input->name == $inname) {
                            $fail("Input field already exists.");
                        }
                    }
                },
            ],
            'placeholder' => 'required_unless:type,3,5',
            'type' => 'required',
            'options.*' => 'required_if:type,2,3'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $input = new PackageInput;
        $input->language_id = $request->language_id;
        $input->type = $request->type;
        $input->label = $request->label;
        $input->name = $inname;
        $input->placeholder = $request->placeholder;
        $input->required = $request->required;
        $input->save();

        if ($request->type == 2 || $request->type == 3) {
            $options = $request->options;
            foreach ($options as $key => $option) {
                $op = new PackageInputOption;
                $op->package_input_id = $input->id;
                $op->name = $option;
                $op->save();
            }
        }

        Session::flash('success', 'Input field added successfully!');
        return "success";
    }

    public function inputDelete(Request $request)
    {
        $input = PackageInput::find($request->input_id);
        $input->package_input_options()->delete();
        $input->delete();
        Session::flash('success', 'Input field deleted successfully!');
        return back();
    }

    public function inputEdit($id)
    {
        $data['input'] = PackageInput::find($id);
        if (!empty($data['input']->package_input_options)) {
            $options = $data['input']->package_input_options;
            $data['options'] = $options;
            $data['counter'] = count($options);
        }
        return view('admin.package.form-edit', $data);
    }

    public function inputUpdate(Request $request)
    {
        $inname = make_input_name($request->label);
        $input = PackageInput::find($request->input_id);
        $inputs = PackageInput::where('language_id', $input->language_id)->get();

        // return $request->options;
        $messages = [
            'options.required_if' => 'Options are required',
            'placeholder.required_unless' => 'Placeholder is required'
        ];

        $rules = [
            'label' => [
                'required',
                function ($attribute, $value, $fail) use ($inname, $inputs, $input) {
                    foreach ($inputs as $key => $in) {
                        if ($in->name == $inname && $inname != $input->name) {
                            $fail("Input field already exists.");
                        }
                    }
                },
            ],
            'placeholder' => 'required_unless:type,3,5',
            'options' => [
                'required_if:type,2,3',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->type == 2 || $request->type == 3) {
                        foreach ($request->options as $option) {
                            if (empty($option)) {
                                $fail('All option fields are required.');
                            }
                        }
                    }
                },
            ]
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }


        $input->label = $request->label;
        $input->name = $inname;

        // if input is checkbox then placeholder is not required
        if ($request->type != 3 && $request->type != 5) {
            $input->placeholder = $request->placeholder;
        }
        $input->required = $request->required;
        $input->save();

        if ($request->type == 2 || $request->type == 3) {
            $input->package_input_options()->delete();
            $options = $request->options;
            foreach ($options as $key => $option) {
                $op = new PackageInputOption;
                $op->package_input_id = $input->id;
                $op->name = $option;
                $op->save();
            }
        }

        Session::flash('success', 'Input field updated successfully!');
        return "success";
    }

    public function options($id)
    {
        $options = PackageInputOption::where('package_input_id', $id)->get();
        return $options;
    }

    public function upload(Request $request)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                    }
                },
            ],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json(['errors' => $validator->errors(), 'id' => 'package']);
        }

        $filename = time() . '.' . $img->getClientOriginalExtension();
        $request->session()->put('package_image', $filename);
        $request->file('file')->move('assets/front/img/packages/', $filename);
        return response()->json(['status' => "session_put", "image" => "package_image", 'filename' => $filename]);
    }

    public function store(Request $request)
    {

        $rules = [
            'language_id' => 'required',
            'title' => 'required|max:40',
            'price' => 'required|numeric',
            'description' => 'required',
            'serial_number' => 'required|integer'
        ];


        $bex = BasicExtra::first();
        if ($bex->recurring_billing == 0) {
            $rules['order_status'] = 'required';
            $rules['link'] = 'required_if:order_status,2';
        } else {
            $rules['duration'] = 'required';
        }

        $messages = [
            'language_id.required' => 'The language field is required'
        ];

        if ($bex->recurring_billing == 0) {
            $messages['link.required_if'] = 'External link is required';
        }

        $be = BasicExtended::first();
        $version = getVersion($be->theme_version);

        if ($version == 'cleaning') {
            $rules['color'] = 'required';
        }

        if ($version == 'lawyer') {
            $rules['package_image'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $package = new Package;
        $package->language_id = $request->language_id;
        $package->title = $request->title;
        if ($version == 'lawyer') {
            $package->image = $request->package_image;
        }
        $package->price = $request->price;
        $package->serial_number = $request->serial_number;
        $package->meta_keywords = $request->meta_keywords;
        $package->meta_description = $request->meta_description;
        $package->description = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->description);

        if ($bex->recurring_billing == 0) {
            $package->order_status = $request->order_status;
            if ($request->order_status == 2) {
                $package->link = $request->link;
            }
        } else {
            $package->duration = $request->duration;
        }


        if ($version == 'cleaning') {
            $package->color = $request->color;
        }
        $package->save();

        Session::flash('success', 'Package added successfully!');
        return "success";
    }

    public function update(Request $request)
    {
        $package = Package::findOrFail($request->package_id);
        $bex = BasicExtra::first();

        $rules = [
            'title' => 'required|max:40',
            'price' => 'required|numeric',
            'description' => 'required',
            'serial_number' => 'required|integer'
        ];

        if ($bex->recurring_billing == 0) {
            $rules['order_status'] = 'required';
            $rules['link'] = 'required_if:order_status,2';
        } else {
            $rules['duration'] = 'required';
        }

        $messages = [];
        if ($bex->recurring_billing == 0) {
            $messages['link.required_if'] = 'External link is required';
        }

        $be = BasicExtended::first();
        $version = getVersion($be->theme_version);

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $package->title = $request->title;

        if ($version == 'cleaning') {
            $package->color = $request->color;
        }

        $package->price = $request->price;
        $package->serial_number = $request->serial_number;
        $package->meta_keywords = $request->meta_keywords;
        $package->meta_description = $request->meta_description;
        $package->description = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->description);

        if ($bex->recurring_billing == 0) {
            $package->order_status = $request->order_status;
            if ($request->order_status == 2) {
                $package->link = $request->link;
            }
        } else {
            $package->duration = $request->duration;
        }
        $package->save();

        Session::flash('success', 'Package updated successfully!');
        return "success";
    }


    public function uploadUpdate(Request $request, $id)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                    }
                },
            ],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json(['errors' => $validator->errors(), 'id' => 'package_image']);
        }

        $package = Package::findOrFail($id);
        if ($request->hasFile('file')) {
            $filename = time() . '.' . $img->getClientOriginalExtension();
            $request->file('file')->move('assets/front/img/packages/', $filename);
            @unlink('assets/front/img/packages/' . $package->image);
            $package->image = $filename;
            $package->save();
        }

        return response()->json(['status' => "success", "image" => "Package image", 'package' => $package]);
    }


    public function delete(Request $request)
    {
        $package = Package::findOrFail($request->package_id);
        // if the package has any currently active subscription / subscription requests, then it cannot be deleted
        if ($package->current_subscriptions()->where('status', 1)->count() > 0 || $package->pending_subscriptions()->count() > 0) {
            Session::flash('warning', 'Please delete the active subscriptions & subscription requests of this package first');
            return back();
        }
        $package->current_subscriptions()->delete();
        $package->next_subscriptions()->delete();
        $package->pending_subscriptions()->delete();
        $package->delete();

        Session::flash('success', 'Package deleted successfully!');
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $package = Package::findOrFail($id);
            // if the package has any currently active subscription / subscription requests, then it cannot be deleted
            if ($package->current_subscriptions()->where('status', 1)->count() > 0 || $package->pending_subscriptions()->count() > 0) {
                Session::flash('warning', 'Please delete the active subscriptions & subscription requests of '. $package->title .' package first');
                return "success";
            }
            $package->delete();
        }

        Session::flash('success', 'Packages deleted successfully!');
        return "success";
    }

    public function all(Request $request)
    {
        $term = $request->term;
        $data['orders'] = PackageOrder::when($term, function ($query, $term) {
            return $query->where('order_number', $term);
        })->orderBy('id', 'DESC')->paginate(10);
        return view('admin.package.orders', $data);
    }

    public function pending(Request $request)
    {
        $term = $request->term;
        $data['orders'] = PackageOrder::when($term, function ($query, $term) {
            return $query->where('order_number', $term);
        })->where('status', 0)->orderBy('id', 'DESC')->paginate(10);
        return view('admin.package.orders', $data);
    }

    public function processing(Request $request)
    {
        $term = $request->term;
        $data['orders'] = PackageOrder::when($term, function ($query, $term) {
            return $query->where('order_number', $term);
        })->where('status', 1)->orderBy('id', 'DESC')->paginate(10);
        return view('admin.package.orders', $data);
    }

    public function completed(Request $request)
    {
        $term = $request->term;
        $data['orders'] = PackageOrder::when($term, function ($query, $term) {
            return $query->where('order_number', $term);
        })->where('status', 2)->orderBy('id', 'DESC')->paginate(10);
        return view('admin.package.orders', $data);
    }

    public function rejected(Request $request)
    {
        $term = $request->term;
        $data['orders'] = PackageOrder::when($term, function ($query, $term) {
            return $query->where('order_number', $term);
        })->where('status', 3)->orderBy('id', 'DESC')->paginate(10);
        return view('admin.package.orders', $data);
    }

    public function status(Request $request)
    {
        $po = PackageOrder::find($request->order_id);
        $po->status = $request->status;
        $po->save();

        Session::flash('success', 'Order status changed successfully!');
        return back();
    }

    public function mail(Request $request)
    {
        $rules = [
            'email' => 'required',
            'subject' => 'required',
            'message' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $be = BasicExtended::first();
        $from = $be->from_mail;

        $sub = $request->subject;
        $msg = $request->message;
        $to = $request->email;

        // Mail::to($to)->send(new ContactMail($from, $sub, $msg));

        // Send Mail
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
                $mail->setFrom($from);
                $mail->addAddress($to);

                // Content
                $mail->isHTML(true);
                $mail->Subject = $sub;
                $mail->Body    = $msg;

                $mail->send();
            } catch (Exception $e) { }
        } else {
            try {

                //Recipients
                $mail->setFrom($from);
                $mail->addAddress($to);

                // Content
                $mail->isHTML(true);
                $mail->Subject = $sub;
                $mail->Body    = $msg;

                $mail->send();
            } catch (Exception $e) { }
        }

        Session::flash('success', 'Mail sent successfully!');
        return "success";
    }

    public function orderDelete(Request $request)
    {
        $order = PackageOrder::findOrFail($request->order_id);
        @unlink('assets/front/ndas/' . $order->nda);
        @unlink('assets/front/receipt/' . $order->receipt);
        $order->delete();

        Session::flash('success', 'Package order deleted successfully!');
        return back();
    }

    public function bulkOrderDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $order = PackageOrder::findOrFail($id);
            @unlink('assets/front/ndas/' . $order->nda);
            @unlink('assets/front/receipt/' . $order->receipt);
            $order->delete();
        }

        Session::flash('success', 'Orders deleted successfully!');
        return "success";
    }

    public function feature(Request $request)
    {
        $package = Package::find($request->package_id);
        $package->feature = $request->feature;
        $package->save();

        if ($request->feature == 1) {
            Session::flash('success', 'Featured successfully!');
        } else {
            Session::flash('success', 'Unfeatured successfully!');
        }

        return back();
    }

    public function background(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abe'] = $lang->basic_extended;

        return view('admin.home.package-background', $data);
    }

    public function uploadBackground(Request $request, $langid)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                    }
                },
            ],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json(['errors' => $validator->errors(), 'id' => 'package_background']);
        }

        if ($request->hasFile('file')) {
            $be = BasicExtended::where('language_id', $langid)->firstOrFail();
            @unlink('assets/front/img/' . $be->package_background);
            $filename = uniqid() . '.' . $img->getClientOriginalExtension();
            $img->move('assets/front/img/', $filename);

            $be->package_background = $filename;
            $be->save();
        }

        return response()->json(['status' => "success", 'image' => 'Package section background']);
    }

    public function settings()
    {
        $data['abex'] = BasicExtra::first();
        return view('admin.package.settings', $data);
    }

    public function updateSettings(Request $request)
    {
        $bexs = BasicExtra::all();
        foreach ($bexs as $bex) {
            $bex->recurring_billing = $request->recurring_billing;
            $bex->expiration_reminder = $request->expiration_reminder;
            $bex->package_guest_checkout = $request->package_guest_checkout;
            $bex->save();
        }

        $request->session()->flash('success', 'Settings updated successfully!');
        return back();
    }

    public function paymentStatus(Request $request)
    {
        $po = PackageOrder::find($request->order_id);
        $po->payment_status = $request->payment_status;
        $po->save();

        $be = BasicExtended::first();
        $sub = 'Payment Status Updated';

        $to = $po->email;
        $fname = $po->name;

        if ($request->payment_status == 1) {
            $status = 'Completed';
        } elseif ($request->payment_status == 0) {
            $status = 'Pending';
        }

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
                $mail->Body    = 'Hello <strong>' . $fname . '</strong>,<br/>Your payment status is changed to ' . $status . '.<br/>Thank you.';
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
                $mail->Body    = 'Hello <strong>' . $fname . '</strong>,<br/>Your payment status is changed to ' . $status . '.<br/>Thank you.';

                $mail->send();
            } catch (Exception $e) {
                // die($e->getMessage());
            }
        }

        Session::flash('success', 'Payment status updated!');
        return back();
    }
}
