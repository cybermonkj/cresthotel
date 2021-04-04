<?php

namespace App\Http\Controllers\Admin;

use App\BasicExtra;
use App\Event;
use App\EventCategory;
use App\EventDetail;
use App\Http\Requests\Event\EventStoreRequest;
use App\Http\Requests\Event\EventUpdateRequest;
use App\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Front\EventController as FrontEventController;
use Illuminate\Support\Facades\Session;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Validator;
use DB;
use PDF;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return
     */
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->first();
        $lang_id = $lang->id;
        $data['lang_id'] = $lang_id;
        $data['abx'] = $lang->basic_extra;
        $data['events'] = Event::where('lang_id', $lang_id)->orderBy('id', 'DESC')->paginate(10);
        $data['event_categories'] = EventCategory::where('lang_id', $lang_id)->where('status', '1')->get();
        return view('admin.event.event.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventStoreRequest $request)
    {
        $event = Event::create($request->except('image') + [
                'slug' => make_slug($request->title),
                'image' => json_encode($request->image),
                'content' => str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->content)
            ]);
        Session::flash('success', 'Event added successfully!');
        return "success";
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return
     */
    public function edit($id)
    {
        $data['event'] = Event::findOrFail($id);
        $data['event_categories'] = EventCategory::where('lang_id', $data['event']->lang_id)->where('status', '1')->get();
        $data['abx'] = BasicExtra::select('base_currency_text')->where('language_id', $data['event']->lang_id)->first();
        return view('admin.event.event.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(EventUpdateRequest $request)
    {
        $event = Event::findOrFail($request->event_id)->update($request->except('image') + [
                'slug' => make_slug($request->title),
                'content' => str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->content)
            ]);
        $event = Event::findOrFail($request->event_id);
        if (empty(json_decode($event->image, true)) && !$request->has('image')) {
            $validator = $request->validate([
                'image' => 'required',
            ]);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
        }
        if ($request->has('image')) {
            $images = json_decode($event->image, true);
            $newImages = array_merge(json_decode($event->image, true), $request->image);
            $event->image = json_encode($newImages);
            $event->save();
        }
        Session::flash('success', 'Event updated successfully!');
        return "success";
    }

    public function uploadUpdate(Request $request, $id)
    {
        $rules = [
            'file' => 'required | mimes:jpeg,jpg,png',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json(['errors' => $validator->errors(), 'id' => 'blog']);
        }
        $img = $request->file('file');
        $event = Event::findOrFail($id);
        if ($request->hasFile('file')) {
            $filename = time() . '.' . $img->getClientOriginalExtension();
            $request->file('file')->move('assets/front/img/events/', $filename);
            @unlink('assets/front/img/events/' . $event->image);
            $event->image = $filename;
            $event->save();
        }

        return response()->json(['status' => "success", "image" => "Event image", 'event' => $event]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getCategories($lang_id)
    {
        return EventCategory::where('lang_id', $lang_id)->where('status', '1')->get();
    }

    public function upload(Request $request)
    {
        $rules = ['upload_video' => 'mimes:mp4|required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json(['errors' => $validator->errors(), 'id' => 'blog']);
        }
        $img = $request->file('upload_video');
        $filename = uniqid("event-") . '.' . $img->getClientOriginalExtension();
        //if directory not exist than create directory with permission
        $directory = "assets/front/img/events/videos/";
        if (!file_exists($directory)) mkdir($directory, 0777, true);
        $img->move($directory, $filename);
        return response()->json(['filename' => $filename, 'status' => 200]);
    }

    public function slider(Request $request)
    {
        $img = $request->file('file');
        $rules = ['file' => 'mimes:jpg,jpeg,png|required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $filename = uniqid() . "." . $img->extension();
        $directory = 'assets/front/img/events/sliders/';
        if (!file_exists($directory)) mkdir($directory, 0777, true);
        $img->move($directory, $filename);
        return response()->json(['status' => 'success', 'file_id' => $filename]);
    }

    public function sliderRemove(Request $request)
    {
        $event = Event::findOrFail($request->id);
        $images = json_decode($event->image, true);
        @unlink('assets/front/img/events/sliders/' . $images["$request->key"]);
        unset($images["$request->key"]);
        $newImages = array_values($images);
        $event->image = json_encode($newImages);
        $event->save();
        return response()->json(['status' => 200, 'message' => 'success']);
    }

    public function delete(Request $request)
    {
        $event = Event::findOrFail($request->event_id);
        $images = json_decode($event->image, true);
        if (count($images) > 0) {
            foreach ($images as $image) {
                $directory = 'assets/front/img/events/sliders/' . $image;
                if (file_exists($directory)) {
                    @unlink($directory);
                }
            }
        }
        if (!is_null($event->video)) {
            $directory = "assets/front/img/events/videos/" . $event->video;
            if (file_exists($directory)) {
                @unlink($directory);
            }
        }
        $event_details = EventDetail::query()->where('event_id',$event->id)->get();
        foreach ($event_details as $event_detail){
            if(!is_null($event_detail->receipt)){
                $directory = "assets/front/img/events/receipt/".$event_detail->receipt;
                if (file_exists($directory)) {
                    @unlink($directory);
                }
            }
            $event_detail->delete();
        }
        $event->delete();
        Session::flash('success', 'Event deleted successfully!');
        return back();
    }

    public function bulkDelete(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $ids = $request->ids;
            foreach ($ids as $id) {
                $event = Event::findOrFail($id);
                $images = json_decode($event->image, true);
                if (count($images) > 0) {
                    foreach ($images as $image) {
                        $directory = 'assets/front/img/events/sliders/' . $image;
                        if (file_exists($directory)) {
                            @unlink($directory);
                        }
                    }
                }
                if (!is_null($event->video)) {
                    $directory = "assets/front/img/events/videos/" . $event->video;
                    if (file_exists($directory)) {
                        @unlink($directory);
                    }
                }
                $event_details = EventDetail::where('event_id',$event->id)->get();
                foreach ($event_details as $event_detail){
                    if(!is_null($event_detail->receipt)){
                        $directory = "assets/front/img/events/receipt/".$event_detail->receipt;
                        if (file_exists($directory)) {
                            @unlink($directory);
                        }
                    }
                    $event_detail->delete();
                }
                $event->delete();
            }
            Session::flash('success', 'Events deleted successfully!');
            return "success";
        });
    }

    public function paymentLog(Request $request)
    {
        $search = $request->search;
        $data['events'] = EventDetail::when($search, function ($query, $search) {
            return $query->where('transaction_id', $search);
        })
            ->orderBy('id', 'DESC')
            ->paginate(10);
        return view('admin.event.payment.index', $data);
    }

    public function paymentLogDelete(Request $request) {
        $payment = EventDetail::findOrFail($request->payment_id);
        @unlink('assets/front/img/events/receipt', $payment->receipt);
        $payment->delete();

        $request->session()->flash('success', 'Payment deleted successfully!');
        return back();
    }


    public function paymentLogBulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $payment = EventDetail::findOrFail($id);
            @unlink('assets/front/img/events/receipt', $payment->receipt);
            $payment->delete();
        }

        Session::flash('success', 'Payments deleted successfully!');
        return "success";
    }

    public function paymentLogUpdate(Request $request)
    {
        $currentLang = session()->has('lang') ?
            (Language::where('code', session()->get('lang'))->first())
            : (Language::where('is_default', 1)->first());
        $be = $currentLang->basic_extended;
        if ($request->status == "success") {
            $event_details = EventDetail::query()
                ->findOrFail($request->id);
            if ($event_details->status == "Rejected") {
                $event = Event::query()->findOrFail($event_details->event_id);
                $event->available_tickets = $event->available_tickets - $event_details->quantity;
                $event->save();
            }
            $event_details->status = "Success";
            $event_details->save();
            $event = new FrontEventController;
            $fileName = $event->makeInvoice($event_details);
            $request['name'] = $event_details->name;
            $request['email'] = $event_details->email;
            $event->sendMailPHPMailer($request, $fileName, $be);
            Session::flash('success', 'Event payment updated successfully!');
        } elseif ($request->status == "rejected") {
            $event_details = EventDetail::query()->findOrFail($request->id);
            $event_details->status = "Rejected";
            $event_details->save();
            $event = Event::query()->findOrFail($event_details->event_id);
            $event->available_tickets = $event->available_tickets + $event_details->quantity;
            $event->save();
            Session::flash('success', 'Event payment rejected successfully!');
        } else {
            $event_details = EventDetail::query()
                ->findOrFail($request->id)
                ->update(['status' => 'Pending']);
            Session::flash('success', 'Event payment to pending successfully!');
        }

        $sub = "Ticket Booking Status Changed";
        if (!empty($event_details->email)) {
            // Send Mail to Customer
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
                    $mail->addAddress($event_details->email, $event_details->name);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = $sub;
                    $mail->Body    = 'Hello <strong>' . $event_details->name . '</strong>,<br/><br>Your ticket booking status of <strong>' . $event_details->event->title . '</strong> is changed to: <strong>'.ucfirst($request->status).'</strong>.<br/><br>Thank you.';
                    $mail->send();
                } catch (Exception $e) {
                    // die($e->getMessage());
                }
            } else {
                try {

                    //Recipients
                    $mail->setFrom($be->from_mail, $be->from_name);
                    $mail->addAddress($event_details->email, $event_details->name);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = $sub ;
                    $mail->Body    = 'Hello <strong>' . $event_details->name . '</strong>,<br/><br>Your ticket booking status of <strong>' . $event_details->event->title . '</strong> is changed to: <strong>'.ucfirst($request->status).'</strong>.<br/><br>Thank you.';
                    $mail->send();
                } catch (Exception $e) {
                    // die($e->getMessage());
                }
            }
        }
        return redirect()->route('admin.event.payment.log');
    }

    public function settings() {
        $data['abex'] = BasicExtra::first();
        return view('admin.event.settings', $data);
    }

    public function updateSettings(Request $request) {
        $bexs = BasicExtra::all();
        foreach($bexs as $bex) {
            $bex->event_guest_checkout = $request->event_guest_checkout;
            $bex->is_event = $request->is_event;
            $bex->save();
        }

        $request->session()->flash('success', 'Settings updated successfully!');
        return back();
    }
}
