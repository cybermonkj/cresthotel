<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BasicSetting;
use App\BasicExtended;
use App\BasicExtra;
use App\Language;
use App\Service;
use App\Home;
use App\Timezone;
use Session;
use Validator;
use Config;
use Artisan;

class BasicController extends Controller
{
    public function favicon(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abs'] = $lang->basic_setting;
        return view('admin.basic.favicon', $data);
    }

    public function updatefav(Request $request, $langid)
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
            return response()->json(['errors' => $validator->errors(), 'id' => 'favicon']);
        }

        if ($request->hasFile('file')) {
            $bs = BasicSetting::where('language_id', $langid)->firstOrFail();
            @unlink('assets/front/img/' . $bs->favicon);
            $filename = uniqid() .'.'. $img->getClientOriginalExtension();
            $img->move('assets/front/img/', $filename);

            $bs->favicon = $filename;
            $bs->save();

        }

        return response()->json(['status' => "success", 'image' => 'favicon']);
    }

    public function logo(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abs'] = $lang->basic_setting;

        return view('admin.basic.logo', $data);
    }

    public function updatelogo(Request $request, $langid)
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
            return response()->json(['errors' => $validator->errors(), 'id' => 'logo']);
        }

        if ($request->hasFile('file')) {

            $bs = BasicSetting::where('language_id', $langid)->firstOrFail();
            // only remove the the previous image, if it is not the same as default image or the first image is being updated
            @unlink('assets/front/img/' . $bs->logo);
            $filename = uniqid() .'.'. $img->getClientOriginalExtension();
            $img->move('assets/front/img/', $filename);

            $bs->logo = $filename;
            $bs->save();

        }
        return response()->json(['status' => "success", 'image' => 'logo']);
    }


    public function featuresettings()
    {
        $data['abex'] = BasicExtra::first();

        return view('admin.basic.features', $data);
    }

    public function updatefeatrue(Request $request)
    {

        $bexs = BasicExtra::all();

        foreach ($bexs as $key => $bex) {
            $bex->is_user_panel = $request->is_user_panel;
            $bex->save();
        }

        Session::flash('success', 'Updated successfully!');
        return back();
    }

    public function updatethemeversion(Request $request)
    {
        $bes = BasicExtended::all();

        foreach ($bes as $key => $be) {
            $be->theme_version = $request->theme_version;
            $be->save();
        }

        Session::flash('success', "$request->theme_version version activated successfully!");
        return "success";
    }


    public function homeTheme(Request $request)
    {
        $data['lang'] = Language::where('code', $request->language)->firstOrFail();
        $data['abs'] = BasicSetting::first();
        $data['abe'] = BasicExtended::first();
        $data['themes'] = Home::all();

        return view('admin.basic.themeHome.index', $data);
    }

    public function updateHomeTheme(Request $request)
    {
        $bss = BasicSetting::all();
        $bes = BasicExtended::all();

        foreach ($bss as $key => $bs) {
            $bs->home_version = $request->home_version;
            $bs->save();
        }
        foreach ($bes as $key => $be) {
            $be->theme_version = $request->theme_version;
            $be->save();
        }

        Session::flash('success', "Home & Theme updated!");
        return back();
    }

    public function preloader(Request $request)
    {
        return view('admin.basic.preloader');
    }

    public function updatepreloader(Request $request)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg', 'gif');

        $rules = [
            'preloader_status' => 'required',
            'file' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only gif, png, jpg, jpeg image is allowed");
                        }
                    }
                },
            ],
        ];

        $request->validate($rules);

        $bexs = BasicExtra::all();

        if ($request->hasFile('file')) {
            $filename = uniqid() . '.' . $img->getClientOriginalExtension();
            $img->move('assets/front/img/', $filename);
        }

        foreach ($bexs as $key => $bex) {
            $bex->preloader_status = $request->preloader_status;
            if ($request->hasFile('file')) {
                @unlink('assets/front/img/' . $bex->preloader);
                $bex->preloader = $filename;
            }
            $bex->save();
        }
        Session::flash('success', 'Preloader updated successfully.');
        return back();
    }


    public function basicinfo(Request $request)
    {
        $data['abs'] = BasicSetting::first();
        $data['abe'] = BasicExtended::first();
        $data['abx'] = BasicExtra::first();
        $data['timezones'] = Timezone::all();

        return view('admin.basic.basicinfo', $data);
    }

    public function updatebasicinfo(Request $request)
    {
        $rules = [
            'website_title' => 'required',
            'base_color' => 'required',
            'secondary_base_color' => 'required',
            'hero_area_overlay_color' => 'required',
            'hero_area_overlay_opacity' => 'required|numeric|max:1|min:0',
            'statistics_area_overlay_color' => 'required',
            'statistics_area_overlay_opacity' => 'required|numeric|max:1|min:0',
            'team_area_overlay_color' => 'required',
            'team_area_overlay_opacity' => 'required|numeric|max:1|min:0',
            'cta_area_overlay_color' => 'required',
            'cta_area_overlay_opacity' => 'required|numeric|max:1|min:0',
            'breadcrumb_area_overlay_color' => 'required',
            'breadcrumb_area_overlay_opacity' => 'required|numeric|max:1|min:0',
            'base_currency_symbol' => 'required',
            'base_currency_symbol_position' => 'required',
            'base_currency_text' => 'required',
            'base_currency_text_position' => 'required',
            'base_currency_rate' => 'required|numeric',
        ];

        $be = BasicExtended::first();

        if (getVersion($be->theme_version) == 'cleaning' || getVersion($be->theme_version) == 'logistic') {
            $rules["hero_area_overlay_color"] = 'nullable';
            $rules["hero_area_overlay_opacity"] = 'nullable';
        }

        if (isDark($be->theme_version) || getVersion($be->theme_version) == 'gym' || getVersion($be->theme_version) == 'car' || getVersion($be->theme_version) == 'construction' || getVersion($be->theme_version) == 'lawyer') {
            $rules["secondary_base_color"] = 'nullable';
        }

        if (getVersion($be->theme_version) == 'car') {
            $rules["statistics_area_overlay_color"] = 'nullable';
            $rules["statistics_area_overlay_opacity"] = 'nullable';
        }

        if (getVersion($be->theme_version) == 'car' || getVersion($be->theme_version) == 'cleaning') {
            $rules["intro_area_overlay_color"] = 'required';
            $rules["intro_area_overlay_opacity"] = 'required|numeric|max:1|min:0';
        }

        if (getVersion($be->theme_version) == 'car') {
            $rules["pricing_area_overlay_color"] = 'required';
            $rules["pricing_area_overlay_opacity"] = 'required|numeric|max:1|min:0';
        }

        if (getVersion($be->theme_version) == 'gym' || getVersion($be->theme_version) == 'car' || getVersion($be->theme_version) == 'cleaning' || getVersion($be->theme_version) == 'construction' || getVersion($be->theme_version) == 'logistic' || getVersion($be->theme_version) == 'lawyer') {
            $rules["team_area_overlay_color"] = 'nullable';
            $rules["team_area_overlay_opacity"] = 'nullable';
        }

        if (getVersion($be->theme_version) == 'car' || getVersion($be->theme_version) == 'construction' || getVersion($be->theme_version) == 'logistic') {
            $rules["cta_area_overlay_color"] = 'nullable';
            $rules["cta_area_overlay_opacity"] = 'nullable';
        }


        $request->validate($rules);

        $bss = BasicSetting::all();
        foreach ($bss as $key => $bs) {
            $bs->website_title = $request->website_title;
            $bs->base_color = $request->base_color;

            if (!isDark($be->theme_version) && getVersion($be->theme_version) != 'gym' && getVersion($be->theme_version) != 'car' && getVersion($be->theme_version) != 'construction' && getVersion($be->theme_version) != 'lawyer') {
                $bs->secondary_base_color = $request->secondary_base_color;
            }


            $bs->save();
        }


        $bes = BasicExtended::all();
        foreach ($bes as $key => $be) {
            if (getVersion($be->theme_version) != 'cleaning' && getVersion($be->theme_version) != 'logistic') {
                $be->hero_overlay_color = $request->hero_area_overlay_color;
                $be->hero_overlay_opacity = $request->hero_area_overlay_opacity;
            }

            if (getVersion($be->theme_version) == 'car' || getVersion($be->theme_version) == 'cleaning') {
                $be->intro_overlay_color = $request->intro_area_overlay_color;
                $be->intro_overlay_opacity = $request->intro_area_overlay_opacity;
            }

            if (getVersion($be->theme_version) == 'car') {
                $be->pricing_overlay_color = $request->pricing_area_overlay_color;
                $be->pricing_overlay_opacity = $request->pricing_area_overlay_opacity;
            }

            if (getVersion($be->theme_version) != 'car') {
                $be->statistics_overlay_color = $request->statistics_area_overlay_color;
                $be->statistics_overlay_opacity = $request->statistics_area_overlay_opacity;
            }

            if (getVersion($be->theme_version) != 'gym' && getVersion($be->theme_version) != 'car' && getVersion($be->theme_version) != 'cleaning' && getVersion($be->theme_version) != 'construction' && getVersion($be->theme_version) != 'logistic' && getVersion($be->theme_version) != 'lawyer') {
                $be->team_overlay_color = $request->team_area_overlay_color;
                $be->team_overlay_opacity = $request->team_area_overlay_opacity;
            }

            if (getVersion($be->theme_version) != 'car' && getVersion($be->theme_version) != 'construction' && getVersion($be->theme_version) != 'logistic') {
                $be->cta_overlay_color = $request->cta_area_overlay_color;
                $be->cta_overlay_opacity = $request->cta_area_overlay_opacity;
            }


            $be->breadcrumb_overlay_color = $request->breadcrumb_area_overlay_color;
            $be->breadcrumb_overlay_opacity = $request->breadcrumb_area_overlay_opacity;
            $be->save();
        }


        $bexs = BasicExtra::all();
        foreach ($bexs as $key => $bex) {
            $bex->base_currency_symbol = $request->base_currency_symbol;
            $bex->base_currency_symbol_position = $request->base_currency_symbol_position;
            $bex->base_currency_text = $request->base_currency_text;
            $bex->base_currency_text_position = $request->base_currency_text_position;
            $bex->base_currency_rate = $request->base_currency_rate;
            $bex->timezone = $request->timezone;
            $bex->save();
        }

        // set timezone in .env
        if ($request->has('timezone') && $request->filled('timezone')) {
            $arr = ['TIMEZONE' => $request->timezone];
            setEnvironmentValue($arr);
            \Artisan::call('config:clear');
        }

        Session::flash('success', 'Basic informations updated successfully!');
        return back();
    }

    public function seo(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abe'] = $lang->basic_extended;

        return view('admin.basic.seo', $data);
    }

    public function updateseo(Request $request, $langid)
    {
        $be = BasicExtended::where('language_id', $langid)->firstOrFail();
        $be->home_meta_keywords = $request->home_meta_keywords;
        $be->home_meta_description = $request->home_meta_description;
        $be->services_meta_keywords = $request->services_meta_keywords;
        $be->services_meta_description = $request->services_meta_description;
        $be->packages_meta_keywords = $request->packages_meta_keywords;
        $be->packages_meta_description = $request->packages_meta_description;
        $be->portfolios_meta_keywords = $request->portfolios_meta_keywords;
        $be->portfolios_meta_description = $request->portfolios_meta_description;
        $be->team_meta_keywords = $request->team_meta_keywords;
        $be->team_meta_description = $request->team_meta_description;
        $be->career_meta_keywords = $request->career_meta_keywords;
        $be->career_meta_description = $request->career_meta_description;
        $be->calendar_meta_keywords = $request->calendar_meta_keywords;
        $be->calendar_meta_description = $request->calendar_meta_description;
        $be->gallery_meta_keywords = $request->gallery_meta_keywords;
        $be->gallery_meta_description = $request->gallery_meta_description;
        $be->faq_meta_keywords = $request->faq_meta_keywords;
        $be->faq_meta_description = $request->faq_meta_description;
        $be->blogs_meta_keywords = $request->blogs_meta_keywords;
        $be->blogs_meta_description = $request->blogs_meta_description;
        $be->rss_meta_keywords = $request->rss_meta_keywords;
        $be->rss_meta_description = $request->rss_meta_description;
        $be->contact_meta_keywords = $request->contact_meta_keywords;
        $be->contact_meta_description = $request->contact_meta_description;
        $be->quote_meta_keywords = $request->quote_meta_keywords;
        $be->quote_meta_description = $request->quote_meta_description;
        $be->products_meta_keywords = $request->products_meta_keywords;
        $be->products_meta_description = $request->products_meta_description;
        $be->cart_meta_keywords = $request->cart_meta_keywords;
        $be->cart_meta_description = $request->cart_meta_description;
        $be->checkout_meta_keywords = $request->checkout_meta_keywords;
        $be->checkout_meta_description = $request->checkout_meta_description;
        $be->login_meta_keywords = $request->login_meta_keywords;
        $be->login_meta_description = $request->login_meta_description;
        $be->register_meta_keywords = $request->register_meta_keywords;
        $be->register_meta_description = $request->register_meta_description;
        $be->forgot_meta_keywords = $request->forgot_meta_keywords;
        $be->forgot_meta_description = $request->forgot_meta_description;
        $be->events_meta_keywords = $request->events_meta_keywords;
        $be->events_meta_description = $request->events_meta_description;
        $be->causes_meta_keywords = $request->causes_meta_keywords;
        $be->causes_meta_description = $request->causes_meta_description;
        $be->save();

        Session::flash('success', 'SEO informations updated successfully!');
        return back();
    }

    public function support(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abs'] = $lang->basic_setting;

        return view('admin.basic.support', $data);
    }

    public function updatesupport(Request $request, $langid)
    {
        $request->validate([
            'support_email' => 'required|email|max:100',
            'support_phone' => 'required|max:30',
        ]);

        $bs = BasicSetting::where('language_id', $langid)->firstOrFail();
        $bs->support_email = $request->support_email;
        $bs->support_phone = $request->support_phone;
        $bs->save();

        Session::flash('success', 'Support Informations updated successfully!');
        return back();
    }

    public function breadcrumb(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abs'] = $lang->basic_setting;

        return view('admin.basic.breadcrumb', $data);
    }

    public function updatebreadcrumb(Request $request, $langid)
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
            return response()->json(['errors' => $validator->errors(), 'id' => 'breadcrumb']);
        }


        if ($request->hasFile('file')) {

            $bs = BasicSetting::where('language_id', $langid)->firstOrFail();
            @unlink('assets/front/img/' . $bs->breadcrumb);
            $filename = uniqid() .'.'. $img->getClientOriginalExtension();
            $img->move('assets/front/img/', $filename);

            $bs->breadcrumb = $filename;
            $bs->save();
        }

        return response()->json(['status' => "success", 'image' => 'breadcrumb']);
    }

    public function heading(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abs'] = $lang->basic_setting;
        $data['abe'] = $lang->basic_extended;
        $data['abex'] = $lang->basic_extra;

        return view('admin.basic.headings', $data);
    }

    public function updateheading(Request $request, $langid)
    {
        $request->validate([
            'service_title' => 'nullable|max:30',
            'service_subtitle' => 'nullable|max:40',
            'career_title' => 'nullable|max:30',
            'career_subtitle' => 'nullable|max:40',
            'event_calendar_title' => 'nullable|max:30',
            'event_calendar_subtitle' => 'nullable|max:40',
            'service_details_title' => 'nullable|max:30',
            'portfolio_title' => 'nullable|max:30',
            'portfolio_subtitle' => 'nullable|max:40',
            'portfolio_details_title' => 'nullable|max:40',
            'blog_details_title' => 'nullable|max:30',
            'rss_details_title' => 'nullable|max:30',
            'contact_title' => 'nullable|max:30',
            'contact_subtitle' => 'nullable|max:40',
            'gallery_title' => 'nullable|max:30',
            'gallery_subtitle' => 'nullable|max:40',
            'team_title' => 'nullable|max:30',
            'team_subtitle' => 'nullable|max:40',
            'faq_title' => 'nullable|max:30',
            'faq_subtitle' => 'nullable|max:40',
            'pricing_title' => 'nullable|max:30',
            'pricing_subtitle' => 'nullable|max:40',
            'blog_title' => 'nullable|max:30',
            'blog_subtitle' => 'nullable|max:40',
            'rss_title' => 'nullable|max:30',
            'rss_subtitle' => 'nullable|max:40',
            'quote_title' => 'nullable|max:30',
            'quote_subtitle' => 'nullable|max:40',
            'error_title' => 'nullable|max:30',
            'error_subtitle' => 'nullable|max:40',
            'product_title' => 'nullable|max:30',
            'product_subtitle' => 'nullable|max:40',
            'product_details_title' => 'nullable|max:30',
            // 'product_details_subtitle' => 'nullable|max:40',
            'cart_title' => 'nullable|max:30',
            'cart_subtitle' => 'nullable|max:40',
            'checkout_title' => 'nullable|max:30',
            'checkout_subtitle' => 'nullable|max:40',
            'event_title' => 'nullable|max:30',
            'event_subtitle' => 'nullable|max:40',
            'cause_title' => 'nullable|max:30',
            'cause_subtitle' => 'nullable|max:40',
            'knowledgebase_title' => 'nullable|max:70',
            'knowledgebase_subtitle' => 'nullable|max:70',
            'knowledgebase_details_title' => 'nullable|max:70'
        ]);

        $bs = BasicSetting::where('language_id', $langid)->firstOrFail();
        $be = BasicExtended::where('language_id', $langid)->firstOrFail();
        $bex = BasicExtra::where('language_id', $langid)->firstOrFail();

        $bs->service_title = $request->service_title;
        $bs->service_subtitle = $request->service_subtitle;
        $bs->service_details_title = $request->service_details_title;
        $bs->portfolio_title = $request->portfolio_title;
        $bs->portfolio_subtitle = $request->portfolio_subtitle;
        $bs->portfolio_details_title = $request->portfolio_details_title;
        $bs->blog_details_title = $request->blog_details_title;
        $bs->contact_title = $request->contact_title;
        $bs->contact_subtitle = $request->contact_subtitle;
        $bs->gallery_title = $request->gallery_title;
        $bs->gallery_subtitle = $request->gallery_subtitle;
        $bs->team_title = $request->team_title;
        $bs->team_subtitle = $request->team_subtitle;
        $bs->faq_title = $request->faq_title;
        $bs->faq_subtitle = $request->faq_subtitle;
        $bs->blog_title = $request->blog_title;
        $bs->blog_subtitle = $request->blog_subtitle;
        $bs->quote_title = $request->quote_title;
        $bs->quote_subtitle = $request->quote_subtitle;
        $bs->error_title = $request->error_title;
        $bs->error_subtitle = $request->error_subtitle;
        $bs->event_title = $request->event_title;
        $bs->event_subtitle = $request->event_subtitle;
        $bs->event_details_title = $request->event_details_title;
        $bs->cause_title = $request->cause_title;
        $bs->cause_subtitle = $request->cause_subtitle;
        $bs->cause_details_title = $request->cause_details_title;
        $bs->save();


        $be->pricing_title = $request->pricing_title;
        $be->pricing_subtitle = $request->pricing_subtitle;
        $be->career_title = $request->career_title;
        $be->career_subtitle = $request->career_subtitle;
        $be->event_calendar_title = $request->event_calendar_title;
        $be->event_calendar_subtitle = $request->event_calendar_subtitle;
        $be->rss_title = $request->rss_title;
        $be->rss_subtitle = $request->rss_subtitle;
        $be->rss_details_title = $request->blog_details_title;
        $be->product_title = $request->product_title;
        $be->product_subtitle = $request->product_subtitle;
        $be->product_details_title = $request->product_details_title;
        // $be->product_details_subtitle = $request->product_details_subtitle;
        $be->cart_title = $request->cart_title;
        $be->cart_subtitle = $request->cart_subtitle;
        $be->checkout_title = $request->checkout_title;
        $be->checkout_subtitle = $request->checkout_subtitle;
        $be->save();

        $bex->course_title = $request->course_title;
        $bex->course_subtitle = $request->course_subtitle;
        $bex->course_details_title = $request->course_details_title;
        $bex->knowledgebase_title = $request->knowledgebase_title;
        $bex->knowledgebase_subtitle = $request->knowledgebase_subtitle;
        $bex->knowledgebase_details_title = $request->knowledgebase_details_title;
        $bex->save();

        Session::flash('success', 'Page title & subtitles updated successfully!');
        return back();
    }

    public function script()
    {
        return view('admin.basic.scripts');
    }

    public function updatescript(Request $request)
    {

        $bss = BasicSetting::all();

        foreach ($bss as $bs) {
            $bs->tawk_to_script = $request->tawk_to_script;
            $bs->is_tawkto = $request->is_tawkto;
            $bs->is_disqus = $request->is_disqus;
            $bs->disqus_script = $request->disqus_script;
            $bs->google_analytics_script = $request->google_analytics_script;
            $bs->is_analytics = $request->is_analytics;
            $bs->appzi_script = $request->appzi_script;
            $bs->is_appzi = $request->is_appzi;
            $bs->addthis_script = $request->addthis_script;
            $bs->is_addthis = $request->is_addthis;
            $bs->is_recaptcha = $request->is_recaptcha;
            $bs->google_recaptcha_site_key = $request->google_recaptcha_site_key;
            $bs->google_recaptcha_secret_key = $request->google_recaptcha_secret_key;
            $bs->save();
        }


        $bes = BasicExtended::all();
        foreach ($bes as $key => $be) {
            $be->facebook_pexel_script = $request->facebook_pexel_script;
            $be->is_facebook_pexel = $request->is_facebook_pexel;
            $be->save();
        }

        $bexs = BasicExtra::all();
        foreach ($bexs as $key => $bex) {
            $bex->is_facebook_login = $request->is_facebook_login;
            $bex->facebook_app_id = $request->facebook_app_id;
            $bex->facebook_app_secret = $request->facebook_app_secret;

            $bex->is_google_login = $request->is_google_login;
            $bex->google_client_id = $request->google_client_id;
            $bex->google_client_secret = $request->google_client_secret;

            $bex->save();
        }
        Session::flash('success', 'Scripts updated successfully!');
        return back();
    }

    public function maintainance()
    {
        return view('admin.basic.maintainance');
    }

    public function updatemaintainance(Request $request)
    {
        $bss = BasicSetting::all();
        foreach ($bss as $bs) {
            $bs->maintainance_text = $request->maintainance_text;
            $bs->maintainance_mode = $request->maintainance_mode;
            $bs->ips = $request->ips;
            $bs->save();
        }

        $down = "down";
        if ($request->filled('ips')) {
            $ips = explode(",", $request->ips);
            foreach ($ips as $key => $ip) {
                $down .= " --allow=" . $ip;
            }
        }
        // return $down;

        if ($request->maintainance_mode == 1) {
            @unlink('core/storage/framework/down');
            Artisan::call($down);
        } else {
            Artisan::call('up');
        }

        Session::flash('success', 'Maintanance mode & page updated successfully!');
        return "success";
    }

    public function uploadmaintainance(Request $request)
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
            return response()->json(['errors' => $validator->errors(), 'id' => 'maintainance_img']);
        }

        @unlink("assets/front/img/maintainance.png");
        $request->file('file')->move('assets/front/img/', 'maintainance.png');
        return response()->json(['status' => "success", 'image' => 'Maintanance image']);
    }

    public function announcement(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abs'] = $lang->basic_setting;

        return view('admin.basic.announcement', $data);
    }

    public function updateannouncement(Request $request, $langid)
    {

        $bs = BasicSetting::where('language_id', $langid)->firstOrFail();
        if ($request->filled('announcement_delay')) {
            $bs->announcement_delay = $request->announcement_delay;
        } else {
            $bs->announcement_delay = 0.00;
        }
        $bs->is_announcement = $request->is_announcement;
        $bs->save();

        Session::flash('success', 'Updated successfully!');
        return "success";
    }

    public function uploadannouncement(Request $request, $langid)
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
            return response()->json(['errors' => $validator->errors(), 'id' => 'announcement_img']);
        }

        if ($request->hasFile('file')) {
            $bs = BasicSetting::where('language_id', $langid)->firstOrFail();
            @unlink('assets/front/img/' . $bs->announcement);
            $filename = uniqid() .'.'. $img->getClientOriginalExtension();
            $img->move('assets/front/img/', $filename);

            $bs->announcement = $filename;
            $bs->save();

        }

        return response()->json(['status' => "success", 'image' => 'Announcement image']);
    }

    public function cookiealert(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abe'] = $lang->basic_extended;

        return view('admin.basic.cookie', $data);
    }

    public function updatecookie(Request $request, $langid)
    {
        $request->validate([
            'cookie_alert_status' => 'required',
            'cookie_alert_text' => 'required',
            'cookie_alert_button_text' => 'required|max:25',
        ]);

        $be = BasicExtended::where('language_id', $langid)->firstOrFail();
        $be->cookie_alert_status = $request->cookie_alert_status;
        $be->cookie_alert_text = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->cookie_alert_text);
        $be->cookie_alert_button_text = $request->cookie_alert_button_text;
        $be->save();

        Session::flash('success', 'Cookie alert updated successfully!');
        return back();
    }
}
