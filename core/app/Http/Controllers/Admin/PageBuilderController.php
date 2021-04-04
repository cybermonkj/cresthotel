<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Language;
use App\Page;
use App\Home;

class PageBuilderController extends Controller
{
    public function save(Request $request)
    {
        if ($request->type == 'page') {
            $data = Page::findOrFail($request->id);
        } elseif ($request->type == 'themeHome') {
            $data = Home::findOrFail($request->id);
        }

        $html = str_replace(url('/'), "{base_url}", $request->html);
        $data->html = "<div class='pagebuilder-content'>" . $html . "</div>";

        $css = str_replace(url('/'), "{base_url}", $request->css);
        $data->css = $css;

        $components = str_replace(url('/'), "{base_url}", $request->components);
        $data->components = $components;

        $styles = str_replace(url('/'), "{base_url}", $request->styles);
        $data->styles = $styles;

        $data->save();

        return "success";
    }

    public function content(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        if ($request->type == 'page') {
            $data = Page::findOrFail($request->id);
        } elseif ($request->type == 'themeHome') {
            // if the theme doesn't exist for that language, then create one
            $theme = Home::where('language_id', $lang->id)->where('theme', $request->theme);
            if ($theme->count() > 0) {
                $data = $theme->first();
            } else {
                $theme = new Home;
                $theme->language_id = $lang->id;
                $theme->theme = $request->theme;
                $theme->save();
                $data = $theme;
            }
        }

        $data['id'] = $data->id;
        $data['lang'] = $lang;
        $rtl = $lang->rtl;
        $data['rtl'] = $rtl;

        $bs = $lang->basic_setting;
        $data['abs'] = $bs;
        $be = $lang->basic_extended;
        $data['abe'] = $be;
        $bex = $lang->basic_extra;
        $version = getVersion($be->theme_version);

        $introsec = "";
        $approachsec = "";
        $scatsec = "";
        $servicesSec = "";
        $portfoliosSec = "";
        $teamSec = "";
        $statisticSec = "";
        $faqSec = "";
        $testimonialSec = "";
        $packageSec = "";
        $blogSec = "";
        $ctaSec = "";
        $partnerSec = "";


        if (!empty($lang->points)) {
            $points = $lang->points()->orderBy('serial_number', 'ASC')->get();
        } else {
            $points = [];
        }

        if (!empty($lang->scategories)) {
            $scats = $lang->scategories()->where('status', 1)->orderBy('serial_number', 'ASC')->get();
        } else {
            $scats = [];
        }

        if (!empty($lang->services)) {
            $services = $lang->services()->where('feature', 1)->orderBy('serial_number', 'ASC')->get();
        } else {
            $services = [];
        }

        if (!empty($lang->portfolios)) {
            $portfolios = $lang->portfolios()->where('feature', 1)->orderBy('serial_number', 'ASC')->get();
        } else {
            $portfolios = [];
        }

        if (!empty($lang->members)) {
            $members = $lang->members()->where('feature', 1)->get();
        } else {
            $members = [];
        }

        if (!empty($lang->statistics)) {
            $statistics = $lang->statistics()->orderBy('serial_number', 'ASC')->get();
        } else {
            $statistics = [];
        }

        if (!empty($lang->faqs)) {
            $faqs = $lang->faqs()->orderBy('serial_number', 'ASC')->get();
        } else {
            $faqs = [];
        }

        if (!empty($lang->testimonials)) {
            $testimonials = $lang->testimonials()->orderBy('serial_number', 'ASC')->get();
        } else {
            $testimonials = [];
        }

        if (!empty($lang->packages)) {
            $packages = $lang->packages()->orderBy('serial_number', 'ASC')->get();
        } else {
            $packages = [];
        }

        if (!empty($lang->blogs)) {
            $blogs = $lang->blogs()->orderBy('serial_number', 'ASC')->get();
        } else {
            $blogs = [];
        }

        if (!empty($lang->partners)) {
            $partners = $lang->partners()->orderBy('serial_number', 'ASC')->get();
        } else {
            $partners = [];
        }




        // FAQ section (All Versions)
        $faqSec = "<div class='container pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "' style='padding: 60px 0px;'>
            <div class='faq-section' style='padding: 0px;'>
                <div class='row justify-content-center text-center' style='margin-bottom: 60px;'>
                    <div class='col-lg-6'>
                        <span class='section-title'>F.A.Q</span>
                        <h2 class='section-summary' style='margin-top: 20px;'>Frequently Asked Questions</h2>
                    </div>
                </div>
                <div class='row' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                    <div class='col-lg-6'>
                        <div class='accordion' id='accordionExample1'>";

        for ($i = 0; $i < ceil(count($faqs) / 2); $i++) {
            $faqSec .= "<div class='card'>
                                    <div class='card-header' id='heading" . $faqs[$i]->id . "'>
                                        <h2 class='mb-0'>
                                            <button class='btn btn-link collapsed btn-block text-left' type='button' data-toggle='collapse' data-target='#collapse" . $faqs[$i]->id . "' aria-expanded='false' aria-controls='collapse" . $faqs[$i]->id . "'>" .
                convertUtf8($faqs[$i]->question)
                . "</button>
                                        </h2>
                                    </div>
                                    <div id='collapse" . $faqs[$i]->id . "' class='collapse' aria-labelledby='heading" . $faqs[$i]->id . "' data-parent='#accordionExample1'>
                                        <div class='card-body'>" .
                convertUtf8($faqs[$i]->answer) .
                "</div>
                                    </div>
                                </div>";
        }

        $faqSec .= "</div>
                    </div>
                    <div class='col-lg-6'>
                        <div class='accordion' id='accordionExample2'>";
        for ($i = ceil(count($faqs) / 2); $i < count($faqs); $i++) {
            $faqSec .= "<div class='card'>
                                <div class='card-header' id='heading" . $faqs[$i]->id . "'>
                                    <h2 class='mb-0'>
                                        <button class='btn btn-link collapsed btn-block text-left' type='button' data-toggle='collapse' data-target='#collapse" . $faqs[$i]->id . "' aria-expanded='false' aria-controls='collapse" . $faqs[$i]->id . "'>" . convertUtf8($faqs[$i]->question) .
                "</button>
                                    </h2>
                                </div>
                                <div id='collapse" . $faqs[$i]->id . "' class='collapse' aria-labelledby='heading" . $faqs[$i]->id . "' data-parent='#accordionExample2'>
                                    <div class='card-body'>" .
                convertUtf8($faqs[$i]->answer) .
                "</div>
                                </div>
                            </div>";
        }
        $faqSec .= "</div>
                    </div>
                </div>
            </div>
        </div>";


        // For Default & Dark Version
        if ($version == 'default' || $version == 'dark') {
            // intro Section (Default Version)
            $introsec = "<div class='pb-mb30'>
                <div class='container " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                    <div class='row'>
                        <div class='col-lg-6 " . ($rtl == 1 ? 'pl-lg-0' : 'pr-lg-0') . "'>
                        <div class='intro-txt'>
                            <span class='section-title'>" . convertUtf8($bs->intro_section_title) . "</span>
                            <h2 class='section-summary'>" . convertUtf8($bs->intro_section_text) . " </h2>";
            if (!empty($bs->intro_section_button_url) && !empty($bs->intro_section_button_text)) {
                $introsec .= "<a href='" . $bs->intro_section_button_url . "' class='intro-btn' target='_blank'><span>" . convertUtf8($bs->intro_section_button_text) . "</span></a>";
            }
            $introsec .= "</div>
                        </div>
                        <div class='col-lg-6 " . ($rtl == 1 ? 'pr-lg-0' : 'pl-lg-0') . " px-md-3 px-0'>
                        <div class='intro-bg lazy' data-bg='" . url('assets/front/img/' . $bs->intro_bg) . "' style='background-size: cover;'>";
            if (!empty($bs->intro_section_video_link)) {
                $introsec .= "<a id='play-video' class='video-play-button' href='" . $bs->intro_section_video_link . "'>
                                    <span></span>
                                </a>";
            }
            $introsec .= "</div>
                        </div>
                    </div>

                </div>
            </div>";


            $approachsec = "<div class='approach-section " . ($rtl == 1 ? 'pb-rtl' : '') . " pb-mb30'>
                <div class='container'>
                <div class='row'>
                    <div class='col-lg-6'>
                        <div class='approach-summary'>
                            <span class='section-title'>" . convertUtf8($bs->approach_title) . "</span>
                            <h2 class='section-summary'>" . convertUtf8($bs->approach_subtitle) . "</h2>";
            if (!empty($bs->approach_button_url) && !empty($bs->approach_button_text)) {
                $approachsec .= "<a href='" . $bs->approach_button_url . "' class='boxed-btn' target='_blank'><span>" . convertUtf8($bs->approach_button_text) . "</span></a>";
            }
            $approachsec .= "</div>
                    </div>
                    <div class='col-lg-6'>
                        <ul class='approach-lists'>";
            foreach ($points as $key => $point) {
                $approachsec .= "<li class='single-approach' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                    <div class='approach-icon-wrapper'><i class='" . $point->icon . "'></i></div>
                                    <div class='approach-text'>
                                        <h4>" . convertUtf8($point->title) . "</h4>
                                        <p>" . convertUtf8($point->short_text) . "</p>
                                    </div>
                                </li>";
            }
            $approachsec .= "</ul>
                    </div>
                </div>
                </div>
            </div>";


            // Service Categories Section (Default Version)
            $scatsec = "<div class='pb-mb30'>
                <div class='container " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                    <div class='service-categories'>
                        <div class='row justify-content-center text-center premade'>
                            <div class='col-lg-6'>
                                <span class='section-title'>" . convertUtf8($bs->service_section_title) . "</span>
                                <h2 class='section-summary'>" . convertUtf8($bs->service_section_subtitle) . "</h2>
                            </div>
                        </div>
                        <div class='row premade' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">";
            foreach ($scats as $key => $scategory) {
                $scatsec .= "<div class='col-xl-3 col-lg-4 col-sm-6'>
                                <div class='single-category'>";
                if (!empty($scategory->image)) {
                    $scatsec .= "<div class='img-wrapper'>
                                            <img class='lazy' data-src='" . url("assets/front/img/service_category_icons/$scategory->image") . "' alt=''>
                                        </div>";
                }
                $scatsec .= "<div class='text'>
                                        <h4>" . convertUtf8($scategory->name) . "</h4>
                                        <p>" . convertUtf8($scategory->short_text) . "</p>
                                        <a href='" . route('front.services', ['category' => $scategory->id]) . "' class='readmore'>" . __('View Services') . "</a>
                                    </div>
                                </div>
                            </div>";
            }
            $scatsec .= "</div>
                    </div>
                </div>
            </div>";


            // Featured Services Section (Default Version)
            $servicesSec = "<section class='services-area pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row justify-content-center text-center'>
                        <div class='col-lg-6'>
                            <span class='section-title'>" . convertUtf8($bs->service_section_title) . "</span>
                            <h2 class='section-summary'>" . convertUtf8($bs->service_section_subtitle) . "</h2>
                        </div>
                    </div>
                    <div class='row premade justify-content-center' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">";
            foreach ($services as $service) {
                $servicesSec .= "<div class='col-lg-4 col-md-6 col-sm-8'>
                                <div class='services-item mt-30'>
                                    <div class='services-thumb'>
                                        <img class='lazy' data-src='" . url('assets/front/img/services/' . $service->main_image) . "' alt='service' />
                                    </div>
                                    <div class='services-content'>
                                        <a class='title'";
                if ($service->details_page_status == 1) {
                    $servicesSec .= "href='" . route('front.servicedetails', [$service->slug, $service->id]) . "'";
                }
                $servicesSec .= "><h4>" . convertUtf8($service->title) . "</h4></a>
                                        <p>" . convertUtf8($service->summary) . "</p>";
                if ($service->details_page_status == 1) {
                    $servicesSec .= "<a href='" . route('front.servicedetails', [$service->slug, $service->id]) . "'>" . __('Read More') . " <i class='fas fa-plus'></i></a>";
                }
                $servicesSec .= "</div>
                                </div>
                            </div>";
            }
            $servicesSec .= "</div>
                </div>
            </section>";



            // Featured Portfolios Section (Default Version)
            $portfoliosSec = "<div class='case-section pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row justify-content-center text-center'>
                        <div class='col-lg-6'>
                            <span class='section-title'>" . convertUtf8($bs->portfolio_section_title) . "</span>
                            <h2 class='section-summary'>" . convertUtf8($bs->portfolio_section_text) . "</h2>
                        </div>
                    </div>
                </div>
                <div class='row' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                    <div class='col-md-12'>
                        <div class='case-carousel owl-carousel owl-theme'>";
            foreach ($portfolios as $key => $portfolio) {
                $portfoliosSec .= "<div class='single-case single-case-bg-1 lazy' data-bg='" . url('assets/front/img/portfolios/featured/' . $portfolio->featured_image) . "'>
                                    <div class='outer-container'>
                                        <div class='inner-container'>
                                        <h4>";
                $portfoliosSec .= convertUtf8(strlen($portfolio->title)) > 36 ? convertUtf8(substr($portfolio->title, 0, 36)) . '...' : convertUtf8($portfolio->title) . "</h4>";
                if (!empty($portfolio->service)) {
                    $portfoliosSec .= "<p>" . convertUtf8($portfolio->service->title) . "</p>";
                }

                $portfoliosSec .= "<a href='" . route('front.portfoliodetails', [$portfolio->slug, $portfolio->id]) . "' class='readmore-btn'><span>" . __('Read More') . "</span></a>;

                                        </div>
                                    </div>
                                </div>";
            }
            $portfoliosSec .= "</div>
                    </div>
                </div>
            </div>";




            // Team Section (Default Version)
            $teamSec = "<div class='team-section section-padding pb-mb30 lazy " . ($rtl == 1 ? 'pb-rtl' : '') . "' data-bg='" . url('assets/front/img/' . $bs->team_bg) . "' style='background-size:cover;'>
                <div class='team-content'>
                <div class='container'>
                    <div class='row justify-content-center text-center'>
                        <div class='col-lg-6'>
                            <span class='section-title'>" . convertUtf8($bs->team_section_title) . "</span>
                            <h2 class='section-summary'>" . convertUtf8($bs->team_section_subtitle) . "</h2>
                        </div>
                    </div>
                    <div class='row' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                        <div class='team-carousel common-carousel owl-carousel owl-theme'>";
            foreach ($members as $key => $member) {
                $teamSec .= "<div class='single-team-member'>
                            <div class='team-img-wrapper'>
                                <img class='lazy' data-src='" . url('assets/front/img/members/' . $member->image) . "' alt=''>
                                <div class='social-accounts'>
                                    <ul class='social-account-lists'>";
                if (!empty($member->facebook)) {
                    $teamSec .= "<li class='single-social-account'><a href='" . $member->facebook . "'><i class='fab fa-facebook-f'></i></a></li>";
                }
                if (!empty($member->twitter)) {
                    $teamSec .= "<li class='single-social-account'><a href='" . $member->twitter . "'><i class='fab fa-twitter'></i></a></li>";
                }
                if (!empty($member->linkedin)) {
                    $teamSec .= "<li class='single-social-account'><a href='" . $member->linkedin . "'><i class='fab fa-linkedin-in'></i></a></li>";
                }
                if (!empty($member->instagram)) {
                    $teamSec .= "<li class='single-social-account'><a href='" . $member->instagram . "'><i class='fab fa-instagram'></i></a></li>";
                }
                $teamSec .= "</ul>
                                </div>
                            </div>
                            <div class='member-info'>
                                <h5 class='member-name'>" . convertUtf8($member->name) . "</h5>
                                <small>" . convertUtf8($member->rank) . "</small>
                            </div>
                            </div>";
            }
            $teamSec .= "</div>
                    </div>
                </div>
                </div>
                <div class='team-overlay' style='background-color: #" . $be->team_overlay_color . ";opacity: " . $be->team_overlay_opacity . ";'></div>
            </div>";


            // Statistics Section (Default Version)
            $statisticSec = "<div class='statistics-section pb-mb30 lazy " . ($rtl == 1 ? 'pb-rtl' : '') . "' data-bg='" . url('assets/front/img/' . $be->statistics_bg) . "' style='background-size:cover;' id='statisticsSection'>
                <div class='statistics-container' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                    <div class='container'>
                        <div class='row no-gutters'>";
            foreach ($statistics as $key => $statistic) {
                $statisticSec .= "<div class='col-lg-3 col-md-6'>
                                            <div class='round' data-value='1' data-number='" . convertUtf8($statistic->quantity) . "' data-size='200' data-thickness='6' data-fill='{&quot;color&quot;: &quot;#" . $bs->base_color . "&quot;}'>
                                            <strong></strong>
                                            <h5><i class='" . $statistic->icon . "'></i> " . convertUtf8($statistic->title) . "</h5>
                                            </div>
                                        </div>";
            }
            $statisticSec .= "</div>
                    </div>
                </div>
                <div class='statistic-overlay' style='background-color: #" . $be->statistics_overlay_color . ";opacity: " . $be->statistics_overlay_opacity . ";'></div>
            </div>";




            // Testimonial Section (Default Version)
            $testimonialSec = "<div class='testimonial-section pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row justify-content-center text-center'>
                        <div class='col-lg-6'>
                            <span class='section-title'>" . convertUtf8($bs->testimonial_title) . "</span>
                            <h2 class='section-summary'>" . convertUtf8($bs->testimonial_subtitle) . "</h2>
                        </div>
                    </div>
                    <div class='row' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                        <div class='col-md-12'>
                            <div class='testimonial-carousel owl-carousel owl-theme'>";
            foreach ($testimonials as $key => $testimonial) {
                $testimonialSec .= "<div class='single-testimonial'>
                                        <div class='img-wrapper'><img class='lazy' data-src='" . url('assets/front/img/testimonials/' . $testimonial->image) . "' alt=''></div>
                                        <div class='client-desc'>
                                            <p class='comment'>" . convertUtf8($testimonial->comment) . "</p>
                                            <h6 class='name'>" . convertUtf8($testimonial->name) . "</h6>
                                            <p class='rank'>" . convertUtf8($testimonial->rank) . "</p>
                                        </div>
                                    </div>";
            }
            $testimonialSec .= "</div>
                        </div>
                    </div>
                </div>
            </div>";




            // Featured Package Section (Default Version)
            $packageSec = "<div class='pricing-tables pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                <div class='row justify-content-center text-center'>
                    <div class='col-lg-6'>
                        <span class='section-title'>" . convertUtf8($be->pricing_title) . "</span>
                        <h2 class='section-summary'>" . convertUtf8($be->pricing_subtitle) . "</h2>
                    </div>
                </div>
                <div class='pricing-carousel common-carousel owl-carousel owl-theme' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">";
            foreach ($packages as $key => $package) {
                $packageSec .= "<div class='single-pricing-table'>
                            <span class='title'>" . convertUtf8($package->title) . "</span>";
                            if($bex->recurring_billing == 1) {
                                $packageSec .= "<small>" . ($package->duration == 'monthly' ? __('Monthly') : __('Yearly')) . "</small>";
                            }
                            $packageSec .= "<div class='price'>
                                <h1>" . ($bex->base_currency_symbol_position == 'left' ? $bex->base_currency_symbol : '') . $package->price . ($bex->base_currency_symbol_position == 'right' ? $bex->base_currency_symbol : '') . "</h1>
                            </div>
                            <div class='features'>" . replaceBaseUrl(convertUtf8($package->description)) . "</div>";

                if ($package->order_status == 1) {
                    $packageSec .= "<a href='" . route('front.packageorder.index', $package->id) . "' class='pricing-btn'>" . __('Place Order') . "</a>";
                }

                $packageSec .= "</div>";
            }
            $packageSec .= "</div>
                </div>
            </div>";




            // Latest Blogs Section (Default Version)
            $blogSec = "<div class='blog-section section-padding pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row justify-content-center text-center'>
                        <div class='col-lg-6'>
                            <span class='section-title'>" . convertUtf8($bs->blog_section_title) . "</span>
                            <h2 class='section-summary'>" . convertUtf8($bs->blog_section_subtitle) . "</h2>
                        </div>
                    </div>
                    <div class='blog-carousel owl-carousel owl-theme common-carousel' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">";
            foreach ($blogs as $key => $blog) {
                $blogSec .= "<div class='single-blog'>
                                <div class='blog-img-wrapper'>
                                    <img src='" . url('assets/front/img/blogs/' . $blog->main_image) . "' alt=''>
                                </div>
                                <div class='blog-txt'>";

                $blogDate = \Carbon\Carbon::parse($blog->created_at)->locale("$lang->code");
                $blogDate = $blogDate->translatedFormat('jS F, Y');


                $blogSec .= "<p class='date'><small>" . __('By') .  " <span class='username'>" . __('Admin') . "</span></small> | <small>" . $blogDate . "</small> </p>

                                <h4 class='blog-title'><a href='" . route('front.blogdetails', [$blog->slug, $blog->id]) . "'>" . (convertUtf8(strlen($blog->title)) > 40 ? convertUtf8(substr($blog->title, 0, 40)) . '...' : convertUtf8($blog->title)) . "</a></h4>


                                <p class='blog-summary'>" . (convertUtf8(strlen(strip_tags($blog->content)) > 100) ? convertUtf8(substr(strip_tags($blog->content), 0, 100)) . '...' : convertUtf8(strip_tags($blog->content))) . "</p>


                                <a href='" . route('front.blogdetails', [$blog->slug, $blog->id]) . "' class='readmore-btn'><span>" . __('Read More') . "</span></a>

                                </div>
                            </div>";
            }
            $blogSec .= "</div>
                    </div>
                </div>";



            $ctaSec = "<div class='cta-section pb-mb30 lazy " . ($rtl == 1 ? 'pb-rtl' : '') . "' data-bg='" . url('assets/front/img/' . $bs->cta_bg) . "' style='background-size:cover;'>
                <div class='container'>
                    <div class='cta-content'>
                        <div class='row'>
                            <div class='col-md-9 col-lg-7'>
                                <h3>" . convertUtf8($bs->cta_section_text) . "</h3>
                            </div>
                            <div class='col-md-3 col-lg-5 contact-btn-wrapper'>
                                <a href='" . $bs->cta_section_button_url . "' class='boxed-btn contact-btn'><span>" . convertUtf8($bs->cta_section_button_text) . "</span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='cta-overlay' style='background-color: #" . $be->cta_overlay_color . ";opacity: " . $be->cta_overlay_opacity . ";'></div>
            </div>";




            // Partners Section (Default Version)
            $partnerSec = "<div class='partner-section pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container " . (!isDark($be->theme_version) ? 'top-border' : '') . "'>
                    <div class='row' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                        <div class='col-md-12'>
                            <div class='partner-carousel owl-carousel owl-theme common-carousel'>";
            foreach ($partners as $key => $partner) {
                $partnerSec .= "<a class='single-partner-item d-block' href='" . $partner->url . "' target='_blank'>
                                        <div class='outer-container'>
                                            <div class='inner-container'>
                                                <img class='lazy' data-src='" . url('assets/front/img/partners/' . $partner->image) . "' alt=''>
                                            </div>
                                        </div>
                                    </a>";
            }
            $partnerSec .= "</div>
                        </div>
                    </div>
                </div>
            </div>";
        }


        // For Gym Version
        if ($version == 'gym') {
            // Intro Section (Gym Version)
            $introsec = "<section class='finlance_about about_v1 gray_bg pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "' style='padding:60px 0px;'>
                <div class='container'>
                    <div class='row align-items-center'>
                        <div class='col-lg-6'>
                            <div class='finlance_box_img'>
                                <div class='finlance_img'>
                                    <img data-src='" . url('assets/front/img/' . $bs->intro_bg) . "' class='img-fluid lazy' alt=''>
                                </div>
                                <div class='play_box'>
                                    <a href='" . $bs->intro_section_video_link . "' class='play_btn'><i class='fas fa-play'></i></a>
                                </div>
                            </div>
                        </div>
                        <div class='col-lg-6'>
                            <div class='finlance_content_box'>
                                <div class='section_title'>
                                    <span>" . convertUtf8($bs->intro_section_title) . "</span>
                                    <h2>" . convertUtf8($bs->intro_section_text) . "</h2>
                                    <span class='line-circle'></span>
                                </div>";
            if (!empty($bs->intro_section_button_url) && !empty($bs->intro_section_button_text)) {
                $introsec .= "<div class='button_box'>
                                        <a href='" . $bs->intro_section_button_url . "' class='finlance_btn' target='_blank'>" . convertUtf8($bs->intro_section_button_text) . "</a>
                                    </div>";
            }
            $introsec .= "</div>
                        </div>
                    </div>
                </div>
            </section>";


            // Approach Section (Gym Version)
            $approachsec = "<section class='finlance_we_do we_do_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-6'>
                            <div class='finlance_content_box'>
                                <div class='section_title'>
                                    <span>" . convertUtf8($bs->approach_title) . "</span>
                                    <h2>" . convertUtf8($bs->approach_subtitle) . "</h2>
                                    <span class='line-circle'></span>
                                </div>";
            if (!empty($bs->approach_button_url) && !empty($bs->approach_button_text)) {
                $approachsec .= "<div class='button_box'>
                                        <a href='" . $bs->approach_button_url . "' class='finlance_btn'>" . convertUtf8($bs->approach_button_text) . "</a>
                                    </div>";
            }
            $approachsec .= "</div>
                        </div>
                        <div class='col-lg-6'>
                            <div class='finlance_icon_box'>";
            foreach ($points as $key => $point) {
                $approachsec .= "<div class='icon_list d-flex' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                        <div class='icon'>
                                            <i class='" . $point->icon . "'></i>
                                        </div>
                                        <div class='text'>
                                            <h3>" . convertUtf8($point->title) . "</h3>
                                            <p>" . convertUtf8($point->short_text) . "</p>
                                        </div>
                                    </div>";
            }
            $approachsec .= "</div>
                        </div>
                    </div>
                </div>
            </section>";

            // Service Categories Section (Gym Version)
            $scatsec = "<section class='finlance_service service_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row justify-content-center'>
                        <div class='col-lg-6'>
                            <div class='section_title text-center'>
                                <span>" . convertUtf8($bs->service_section_title) . "</span>
                                <h2>" . convertUtf8($bs->service_section_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='service_slide service-slick' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">";
            foreach ($scats as $key => $scat) {
                $scatsec .= "<div class='grid_item'>
                                <div class='grid_inner_item'>";
                if (!empty($scat->image)) {
                    $scatsec .= "<div class='finlance_img'>
                                            <img data-src='" . url('assets/front/img/service_category_icons/' . $scat->image) . "' class='img-fluid lazy' alt=''>
                                            <div class='service_overlay'>
                                                <div class='button_box'>
                                                    <a href='" . route('front.services', ['category' => $scat->id]) . "' class='more_icon'><i class='fas fa-angle-double-right'></i></a>
                                                </div>
                                            </div>
                                        </div>";
                }
                $scatsec .= "<div class='finlance_content'>
                                        <h3><a href='" . route('front.services', ['category' => $scat->id]) . "'>" . convertUtf8($scat->name) . "</a></h3>
                                    </div>
                                    <div class='summary text-center mt-2'>";
                if (strlen(convertUtf8($scat->short_text)) > 112) {
                    $scatsec .= substr(convertUtf8($scat->short_text), 0, 112);
                } else {
                    $scatsec .= convertUtf8($scat->short_text);
                }
                $scatsec .= "</div>
                                </div>
                            </div>";
            }
            $scatsec .= "</div>
                </div>
            </section>";

            // Featured Services Section (Gym Version)
            $servicesSec = "<section class='finlance_service service_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row justify-content-center'>
                        <div class='col-lg-6'>
                            <div class='section_title text-center'>
                                <span>" . convertUtf8($bs->service_section_title) . "</span>
                                <h2>" . convertUtf8($bs->service_section_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='service_slide service-slick' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">";
            foreach ($services as $key => $service) {
                $servicesSec .= "<div class='grid_item'>
                                <div class='grid_inner_item'>";
                if (!empty($service->main_image)) {
                    $servicesSec .= "<div class='finlance_img'>
                                            <img class='lazy' data-src='" . url('assets/front/img/services/' . $service->main_image) . "' alt='service' />";
                    if ($service->details_page_status == 1) {
                        $servicesSec .= "<div class='service_overlay'>
                                                    <div class='button_box'>
                                                        <a href='" . route('front.servicedetails', [$service->slug, $service->id]) . "' class='more_icon'><i class='fas fa-angle-double-right'></i></a>
                                                    </div>
                                                </div>";
                    }
                    $servicesSec .= "</div>";
                }
                $servicesSec .= "<div class='finlance_content'>
                                        <h3><a ";
                if ($service->details_page_status == 1) {
                    $servicesSec .= " href='" . route('front.servicedetails', [$service->slug, $service->id]) . "'";
                }
                $servicesSec .= ">" . convertUtf8($service->title) . "</a></h3>
                                    </div>
                                    <div class='summary text-center mt-2'>";
                if (strlen(convertUtf8($service->summary)) > 100) {
                    $servicesSec .= substr(convertUtf8($service->summary), 0, 100);
                } else {
                    $servicesSec .= convertUtf8($service->summary);
                }
                $servicesSec .= "</div>
                                </div>
                            </div>";
            }
            $servicesSec .= "</div>
                </div>
            </section>";


            // Featured Portfolios Section (Gym Version)
            $portfoliosSec = "<section class='finlance_project project_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "' style='padding-bottom: 60px;'>
                <div class='container-full'>
                    <div class='row justify-content-center'>
                        <div class='col-lg-6'>
                            <div class='section_title text-center'>
                                <span>" . convertUtf8($bs->portfolio_section_title) . "</span>
                                <h2>" . convertUtf8($bs->portfolio_section_text) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='project_slide project-slick'>";
            foreach ($portfolios as $key => $portfolio) {
                $portfoliosSec .= "<div class='grid_item' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='grid_inner_item'>
                                    <div class='finlance_img'>
                                        <img data-src='" . url('assets/front/img/portfolios/featured/' . $portfolio->featured_image) . "' class='img-fluid lazy' alt=''>
                                        <div class='project_overlay'>
                                            <div class='finlance_content'>
                                                <a href='" . route('front.portfoliodetails', [$portfolio->slug, $portfolio->id]) . "' class='more_icon'><i class='fas fa-angle-double-right'></i></a>
                                                <h3><a href='" . route('front.portfoliodetails', [$portfolio->slug, $portfolio->id]) . "'>" . (convertUtf8(strlen($portfolio->title)) > 36 ? convertUtf8(substr($portfolio->title, 0, 36)) . '...' : convertUtf8($portfolio->title)) . "</a></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>";
            }
            $portfoliosSec .= "</div>
                </div>
            </section>";


            // Team Section (Gym Version)
            $teamSec = "<section class='finlance_team team_v1 gray_bg pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row justify-content-center'>
                        <div class='col-lg-6'>
                            <div class='section_title text-center'>
                                <span>" . convertUtf8($bs->team_section_title) . "</span>
                                <h2>" . convertUtf8($bs->team_section_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='team_slide team_slick' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">";
            foreach ($members as $key => $member) {
                $teamSec .= "<div class='grid_item'>
                                <div class='grid_inner_item'>
                                    <div class='finlance_img'>
                                        <img data-src='" . url('assets/front/img/members/' . $member->image) . "' class='img-fluid lazy' alt=''>
                                        <div class='team_overlay'>
                                            <div class='finlance_content'>
                                                <h3>" . convertUtf8($member->name) . "</h3>
                                                <p>" . convertUtf8($member->rank) . "</p>
                                                <ul class='social_box'>";
                if (!empty($member->facebook)) {
                    $teamSec .= "<li><a href='" . $member->facebook . "' target='_blank'><i class='fab fa-facebook-f'></i></a></li>";
                }
                if (!empty($member->twitter)) {
                    $teamSec .= "<li><a href='" . $member->twitter . "' target='_blank'><i class='fab fa-twitter'></i></a></li>";
                }
                if (!empty($member->linkedin)) {
                    $teamSec .= "<li><a href='" . $member->linkedin . "' target='_blank'><i class='fab fa-linkedin-in'></i></a></li>";
                }
                if (!empty($member->instagram)) {
                    $teamSec .= "<li><a href='" . $member->instagram . "' target='_blank'><i class='fab fa-instagram'></i></a></li>";
                }
                $teamSec .= "</ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>";
            }
            $teamSec .= "</div>
                </div>
            </section>";


            // Statistics Section (Gym Version)
            $statisticSec = "<section class='finlance_fun finlance_fun_v1 bg_image pb-mb30 lazy " . ($rtl == 1 ? 'pb-rtl' : '') . "' data-bg='" . url('assets/front/img/' . $be->statistics_bg) . "' style='background-size:cover; padding: 100px 0px;' id='statisticsSection'>
                <div class='bg_overlay' style='background: #" . $be->statistics_overlay_color . ";opacity: " . $be->statistics_overlay_opacity . ";'></div>
                <div class='container'>
                    <div class='row' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">";
            foreach ($statistics as $key => $statistic) {
                $statisticSec .= "<div class='col-lg-3 col-md-6 col-sm-12'>
                                <div class='counter_box'>
                                    <div class='icon'>
                                        <i class='" . $statistic->icon . "'></i>
                                    </div>
                                    <h2><span class='counter'>" . convertUtf8($statistic->quantity) . "</span>+</h2>
                                    <h4>" . convertUtf8($statistic->title) . "</h4>
                                </div>
                            </div>";
            }
            $statisticSec .= "</div>
                </div>
            </section>";


            // Testimonial Section (Gym Version)
            $testimonialSec = "<section class='finlance_testimonial testimonial_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row justify-content-center'>
                        <div class='col-lg-6'>
                            <div class='section_title text-center'>
                                <span>" . convertUtf8($bs->testimonial_title) . "</span>
                                <h2>" . convertUtf8($bs->testimonial_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='testimonial_slide' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">";
            foreach ($testimonials as $key => $testimonial) {
                $testimonialSec .= "<div class='testimonial_box'>
                                <div class='row align-items-center'>
                                    <div class='col-lg-5 col-md-5'>
                                        <div class='finlance_img'>
                                            <img data-src='" . url('assets/front/img/testimonials/' . $testimonial->image) . "' class='img-fluid lazy' alt=''>
                                        </div>
                                    </div>
                                    <div class='col-lg-7 col-md-7'>
                                        <div class='finlance_content'>
                                            <img class='lazy' data-src='" . url('assets/front/img/quote.png') . "' alt=''>
                                            <p>" . convertUtf8($testimonial->comment) . "</p>
                                            <h3>" . convertUtf8($testimonial->name) . "</h3>
                                            <h6>" . convertUtf8($testimonial->rank) . "</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>";
            }
            $testimonialSec .= "</div>
                </div>
            </section>";




            // Featured Package Section (Gym Version)
            $packageSec = "<section class='logistics_pricing pricing_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row justify-content-center'>
                        <div class='col-lg-6'>
                            <div class='section_title text-center'>
                                <span>" . convertUtf8($be->pricing_title) . "</span>
                                <h2>" . convertUtf8($be->pricing_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='pricing_slide pricing_slick' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">";
            foreach ($packages as $key => $package) {
                $packageSec .= "<div class='pricing_box text-center'>
                                <div class='pricing_title'>
                                    <h3>" . convertUtf8($package->title) . "</h3>";
                                    if($bex->recurring_billing == 1) {
                                        $packageSec .= "<p>" . ($package->duration == 'monthly' ? __('Monthly') : __('Yearly')) . "</p>";
                                    }
                                $packageSec .= "</div>
                                <div class='pricing_price'>
                                    <h3>" . ($bex->base_currency_symbol_position == 'left' ? $bex->base_currency_symbol : '') . $package->price . ($bex->base_currency_symbol_position == 'right' ? $bex->base_currency_symbol : '') . "</h3>
                                </div>
                                <div class='pricing_body'>" . replaceBaseUrl(convertUtf8($package->description)) . "</div>
                                <div class='pricing_button'>";
                if ($package->order_status == 1) {
                    $packageSec .= "<a href='" . route('front.packageorder.index', $package->id) . "' class='finlance_btn'>" . __('Place Order') . "</a>";
                }
                $packageSec .= "</div>
                            </div>";
            }
            $packageSec .= "</div>
                </div>
            </section>";

            // Latest Blogs Section (Gym Version)
            $blogSec = "<section class='finlance_blog blog_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-6'>
                            <div class='section_title'>
                                <span>" . convertUtf8($bs->blog_section_title) . "</span>
                                <h2>" . convertUtf8($bs->blog_section_subtitle) . "</h2>
                                <span class='line-circle'></span>
                            </div>
                        </div>
                    </div>
                    <div class='blog_slide blog_slick' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">";
            foreach ($blogs as $key => $blog) {
                $blogSec .= "<div class='grid_item'>
                                <div class='grid_inner_item'>
                                    <div class='finlance_img'>
                                        <a href='" . route('front.blogdetails', [$blog->slug, $blog->id]) . "'><img src='" . url('assets/front/img/blogs/' . $blog->main_image) . "' class='img-fluid' alt=''></a>
                                        <div class='blog-overlay'>
                                            <div class='finlance_content'>";

                $blogDate = \Carbon\Carbon::parse($blog->created_at)->locale("$lang->code");
                $blogDate = $blogDate->translatedFormat('jS F, Y');

                $blogSec .= "<div class='post_meta'>
                                                    <span><i class='far fa-user'></i><a href='#'>" . __('By') . " " . __('Admin') . "</a></span>
                                                    <span><i class='far fa-calendar-alt'></i><a href='#'>" . $blogDate . "</a></span>
                                                </div>
                                                <h3 class='post_title'><a href='" . route('front.blogdetails', [$blog->slug, $blog->id]) . "'>" . (convertUtf8(strlen($blog->title)) > 40 ? convertUtf8(substr($blog->title, 0, 40)) . '...' : convertUtf8($blog->title)) . "</a></h3>
                                                <p>" . (convertUtf8(strlen(strip_tags($blog->content)) > 100) ? convertUtf8(substr(strip_tags($blog->content), 0, 100)) . '...' : convertUtf8(strip_tags($blog->content))) . "</p>
                                                <a href='" . route('front.blogdetails', [$blog->slug, $blog->id]) . "' class='btn_link'>" . __('Read More') . "</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>";
            }
            $blogSec .= "</div>
                </div>
            </section>";



            // CTA Section (Gym Version)
            $ctaSec = "<section class='finlance_cta cta_v1 main_bg pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "' style='padding: 70px 0px;'>
                <div class='container' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                    <div class='row align-items-center'>
                        <div class='col-lg-8'>
                            <div class='section_title'>
                                <h2 class='text-white'>" . convertUtf8($bs->cta_section_text) . "</h2>
                            </div>
                        </div>
                        <div class='col-lg-4'>
                            <div class='button_box'>
                                <a href='" . $bs->cta_section_button_url . "' class='finlance_btn'>" . convertUtf8($bs->cta_section_button_text) . "</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>";


            // Partners Section (Gym Version)
            $partnerSec = "<section class='finlance_partner partner_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "' style='padding: 80px 0px;'>
                <div class='container'>
                    <div class='partner_slide' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">";
            foreach ($partners as $key => $partner) {
                $partnerSec .= "<div class='single_partner'>
                                <a href='" . $partner->url . "' target='_blank'><img data-src='" . url('assets/front/img/partners/' . $partner->image) . "' class='img-fluid lazy' alt=''></a>
                            </div>";
            }
            $partnerSec .= "</div>
                </div>
            </section>";
        }


        // For Car Version
        if ($version == 'car') {

            $introsec = "<section class='finlance_about about_v1 bg_image pb-mb30 lazy " . ($rtl == 1 ? 'pb-rtl' : '') . "' data-bg='" . url('assets/front/img/' . $bs->intro_bg) . "' style='background-size: cover;padding: 120px 0px;'>
                <div class='bg_overlay' style='background-color: #" . $be->intro_overlay_color . ";opacity: " . $be->intro_overlay_opacity . ";'></div>
                <div class='container'>
                    <div class='row align-items-center'>
                        <div class='col-lg-4'>
                            <div class='play_box text-center'>
                                <a href='" . $bs->intro_section_video_link . "' class='play_btn'><i class='fas fa-play'></i></a>
                            </div>
                        </div>
                        <div class='col-lg-8'>
                            <div class='finlance_content_box'>
                                <div class='section_title'>
                                    <span>" . convertUtf8($bs->intro_section_title) . "</span>
                                    <h2>" . convertUtf8($bs->intro_section_text) . "</h2>
                                    <span class='line-circle'></span>
                                </div>";
            if (!empty($bs->intro_section_button_url) && !empty($bs->intro_section_button_text)) {
                $introsec .= "<div class='button_box'>
                                        <a href='" . $bs->intro_section_button_url . "' class='finlance_btn'>" . convertUtf8($bs->intro_section_button_text) . "</a>
                                    </div>";
            }
            $introsec .= "</div>
                        </div>
                    </div>
                </div>
            </section>";

            $approachsec = "<section class='finlance_we_do we_do_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-6'>
                            <div class='finlance_content_box'>
                                <div class='section_title'>
                                    <span>" . convertUtf8($bs->approach_title) . "</span>
                                    <h2>" . convertUtf8($bs->approach_subtitle) . "</h2>
                                    <span class='line-circle'></span>
                                </div>";
            if (!empty($bs->approach_button_url) && !empty($bs->approach_button_text)) {
                $approachsec .= "<div class='button_box'>
                                        <a href='" . $bs->approach_button_url . "' class='finlance_btn'>" . convertUtf8($bs->approach_button_text) . "</a>
                                    </div>";
            }
            $approachsec .= "</div>
                        </div>
                        <div class='col-lg-6'>
                            <div class='finlance_icon_box'>";
            foreach ($points as $key => $point) {
                $approachsec .= "<div class='icon_list d-flex' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                        <div class='icon'>
                                            <i class='" . $point->icon . "'></i>
                                        </div>
                                        <div class='text'>
                                            <h4>" . convertUtf8($point->title) . "</h4>
                                            <p>" . convertUtf8($point->short_text) . "</p>
                                        </div>
                                    </div>";
            }
            $approachsec .= "</div>
                        </div>
                    </div>
                </div>
            </section>";

            // Service Categories Section (Car Version)
            $scatsec = "<section class='finlance_service service_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row justify-content-center' style='margin-bottom: 70px;'>
                        <div class='col-lg-6'>
                            <div class='section_title text-center'>
                                <span>" . convertUtf8($bs->service_section_title) . "</span>
                                <h2>" . convertUtf8($bs->service_section_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='row'>";
            foreach ($scats as $key => $scat) {
                $scatsec .= "<div class='col-lg-4 col-md-6 col-sm-12 mb-5' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='grid_item text-center'>
                                    <div class='grid_inner_item'>";
                if (!empty($scat->image)) {
                    $scatsec .= "<div class='finlance_icon'>
                                                <img data-src='" . url('assets/front/img/service_category_icons/' . $scat->image) . "' class='img-fluid lazy' alt=''>
                                            </div>";
                }
                $scatsec .= "<div class='finlance_content'>
                                            <h4>" . convertUtf8($scat->name) . "</h4>
                                            <p>";
                if (strlen(convertUtf8($scat->short_text)) > 112) {
                    $scatsec .= substr(convertUtf8($scat->short_text), 0, 112);
                } else {
                    $scatsec .= convertUtf8($scat->short_text);
                }
                $scatsec .= "</p>
                                            <a href='" . route('front.services', ['category' => $scat->id]) . "' class='btn_link'>" . __('View Services') . "</a>
                                        </div>
                                    </div>
                                </div>
                            </div>";
            }
            $scatsec .= "</div>
                </div>
            </section>";


            // Featured Services Section (Car Version)
            $servicesSec = "<section class='finlance_service service_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row justify-content-center'>
                        <div class='col-lg-6'>
                            <div class='section_title text-center' style='margin-bottom: 70px;'>
                                <span>" . convertUtf8($bs->service_section_title) . "</span>
                                <h2>" . convertUtf8($bs->service_section_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='row'>";
            foreach ($services as $key => $service) {
                $servicesSec .= "<div class='col-lg-4 col-md-6 col-sm-12 mb-5' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='grid_item text-center'>
                                    <div class='grid_inner_item'>";
                if (!empty($service->main_image)) {
                    $servicesSec .= "<div class='finlance_icon' style='margin-bottom: 20px;'>
                                                <img class='lazy' data-src='" . url('assets/front/img/services/' . $service->main_image) . "' alt='service' />
                                            </div>";
                }
                $servicesSec .= "<div class='finlance_content'>
                                            <h4>" . convertUtf8($service->title) . "</h4>
                                            <p>";
                if (strlen(convertUtf8($service->summary)) > 100) {
                    $servicesSec .= substr(convertUtf8($service->summary), 0, 100);
                } else {
                    $servicesSec .= convertUtf8($service->summary);
                }
                $servicesSec .= "</p>";
                if ($service->details_page_status == 1) {
                    $servicesSec .= "<a href='" . route('front.servicedetails', [$service->slug, $service->id]) . "' class='btn_link'>" . __('Read More') . "</a>";
                }
                $servicesSec .= "</div>
                                    </div>
                                </div>
                            </div>";
            }

            $servicesSec .= "</div>
                </div>
            </section>";



            // Featured Portfolios Section (Car Version)
            $portfoliosSec = "<section class='finlance_project project_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "' style='padding-bottom: 60px;'>
                <div class='container-full'>
                    <div class='row justify-content-center'>
                        <div class='col-lg-6'>
                            <div class='section_title text-center' style='margin-bottom: 70px;'>
                                <span>" . convertUtf8($bs->portfolio_section_title) . "</span>
                                <h2>" . convertUtf8($bs->portfolio_section_text) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='project_slide project_slick'>";
            foreach ($portfolios as $key => $portfolio) {
                $portfoliosSec .= "<div class='grid_item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='grid_inner_item'>
                                    <div class='finlance_img'>
                                        <img data-src='" . url('assets/front/img/portfolios/featured/' . $portfolio->featured_image) . "' class='img-fluid lazy' alt=''>
                                        <div class='project_overlay'>
                                            <div class='finlance_content'>
                                                <a href='" . route('front.portfoliodetails', [$portfolio->slug, $portfolio->id]) . "' class='more_icon'><i class='fas fa-angle-double-right'></i></a>

                                                <h3><a href='" . route('front.portfoliodetails', [$portfolio->slug, $portfolio->id]) . "'>" . (convertUtf8(strlen($portfolio->title)) > 25 ? convertUtf8(substr($portfolio->title, 0, 25)) . '...' : convertUtf8($portfolio->title)) . "</a></h3>";


                if (!empty($portfolio->service)) {
                    $portfoliosSec .= "<p>" . convertUtf8($portfolio->service->title) . "</p>";
                }
                $portfoliosSec .= "</div>
                                        </div>
                                    </div>
                                </div>
                            </div>";
            }
            $portfoliosSec .= "</div>
                </div>
            </section>";


            // Team Section (Car Version)
            $teamSec = "<section class='finlance_team team_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row justify-content-center'>
                        <div class='col-lg-6'>
                            <div class='section_title text-center' style='margin-bottom: 70px;'>
                                <span>" . convertUtf8($bs->team_section_title) . "</span>
                                <h2>" . convertUtf8($bs->team_section_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='team_slide team_slick'>";
            foreach ($members as $key => $member) {
                $teamSec .= "<div class='grid_item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='grid_inner_item'>
                                    <div class='finlance_img'>
                                        <img data-src='" . url('assets/front/img/members/' . $member->image) . "' class='img-fluid lazy' alt=''>
                                        <div class='team_overlay'>
                                            <ul class='social_box'>";
                if (!empty($member->facebook)) {
                    $teamSec .= "<li><a href='" . $member->facebook . "' target='_blank'><i class='fab fa-facebook-f'></i></a></li>";
                }
                if (!empty($member->twitter)) {
                    $teamSec .= "<li><a href='" . $member->twitter . "' target='_blank'><i class='fab fa-twitter'></i></a></li>";
                }
                if (!empty($member->linkedin)) {
                    $teamSec .= "<li><a href='" . $member->linkedin . "' target='_blank'><i class='fab fa-linkedin-in'></i></a></li>";
                }
                if (!empty($member->instagram)) {
                    $teamSec .= "<li><a href='" . $member->instagram . "' target='_blank'><i class='fab fa-instagram'></i></a></li>";
                }
                $teamSec .= "</ul>
                                        </div>
                                    </div>
                                    <div class='finlance_content lazy' data-bg='" . url('assets/front/img/pattern_bg.jpg') . "'>
                                        <h4>" . convertUtf8($member->name) . "</h4>
                                        <p>" . convertUtf8($member->rank) . "</p>
                                    </div>
                                </div>
                            </div>";
            }
            $teamSec .= "</div>
                </div>
            </section>";


            // Statistics Section (Car Version)
            $statisticSec = "<section class='finlance_fun finlance_fun_v1 pb-mb30 lazy " . ($rtl == 1 ? 'pb-rtl' : '') . "' data-bg='" . url('assets/front/img/pattern_bg_2.jpg') . "' style='padding: 100px 0px;'>
                <div class='container' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                    <div class='row'>";
            foreach ($statistics as $key => $statistic) {
                $statisticSec .= "<div class='col-lg-3 col-md-6 col-sm-12'>
                                <div class='counter_box'>
                                    <div class='icon'>
                                        <i class='" . $statistic->icon . "'></i>
                                    </div>
                                    <h2><span class='counter'>" . convertUtf8($statistic->quantity) . "</span>+</h2>
                                    <h4>" . convertUtf8($statistic->title) . "</h4>
                                </div>
                            </div>";
            }
            $statisticSec .= "</div>
                </div>
            </section>";




            // Testimonial Section (Car Version)
            $testimonialSec = "<section class='finlance_testimonial testimonial_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row justify-content-center'>
                        <div class='col-lg-6'>
                            <div class='section_title text-center' style='margin-bottom: 75px;'>
                                <span>" . convertUtf8($bs->testimonial_title) . "</span>
                                <h2>" . convertUtf8($bs->testimonial_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='testimonial_slide' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">";
            foreach ($testimonials as $key => $testimonial) {
                $testimonialSec .= "<div class='testimonial_box'>
                                <div class='quote'>
                                    <img class='lazy' data-src='" . url('assets/front/img/quote.png') . "' alt=''>
                                </div>
                                <div class='client_box'>
                                    <div class='thumb'>
                                        <img class='lazy' data-src='" . url('assets/front/img/testimonials/' . $testimonial->image) . "' alt=''>
                                    </div>
                                    <div class='info'>
                                        <h3>" . convertUtf8($testimonial->name) . "</h3>
                                        <h6>" . convertUtf8($testimonial->rank) . "</h6>
                                    </div>
                                </div>
                                <div class='finlance_content'>
                                    <p>" . convertUtf8($testimonial->comment) . "</p>
                                </div>
                            </div>";
            }
            $testimonialSec .= "</div>
                </div>
            </section>";




            // Featured Package Section (Car Version)
            $packageSec = "<section class='finlance_pricing pricing_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='pricing-bg lazy' data-bg='" . url('assets/front/img/' . $be->package_background) . "' style='background-size: cover;'>
                    <div class='bg_overlay' style='background-color: #" . $be->pricing_overlay_color . ";opacity: " . $be->pricing_overlay_opacity . ";'></div>
                </div>
                <div class='container'>
                    <div class='row justify-content-center'>
                        <div class='col-lg-6'>
                            <div class='section_title text-center' style='margin-bottom: 60px;'>
                                <span>" . convertUtf8($be->pricing_title) . "</span>
                                <h2>" . convertUtf8($be->pricing_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='pricing_slide pricing_slick'>";
            foreach ($packages as $key => $package) {
                $packageSec .= "<div class='pricing_box text-center' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='pricing_title'>
                                    <h3>" . convertUtf8($package->title) . "</h3>
                                </div>
                                <div class='pricing_price'>
                                    <h2>" . ($bex->base_currency_symbol_position == 'left' ? $bex->base_currency_symbol : '') . $package->price . ($bex->base_currency_symbol_position == 'right' ? $bex->base_currency_symbol : '') . "</h2>";
                                    if($bex->recurring_billing == 1) {
                                        $packageSec .= "<p>" . ($package->duration == 'monthly' ? __('Monthly') : __('Yearly')) . "</p>";
                                    }
                                $packageSec .= "</div>
                                <div class='pricing_body'>" . replaceBaseUrl(convertUtf8($package->description)) . "</div>
                                <div class='pricing_button'>";
                if ($package->order_status == 1) {
                    $packageSec .= "<a href='" . route('front.packageorder.index', $package->id) . "' class='finlance_btn'>" . __('Place Order') . "</a>";
                }
                $packageSec .= "</div>
                            </div>";
            }
            $packageSec .= "</div>
                </div>
            </section>";


            // Latest Blogs Section (Car Version)
            $blogSec = "<section class='finlance_blog blog_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row justify-content-center'>
                        <div class='col-lg-6'>
                            <div class='section_title text-center mb-70'>
                                <span>" . convertUtf8($bs->blog_section_title) . "</span>
                                <h2>" . convertUtf8($bs->blog_section_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='blog_slide'>";
            foreach ($blogs as $key => $blog) {
                $blogSec .= "<div class='grid_item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='grid_inner_item'>
                                    <div class='row align-items-end no-gutters'>
                                        <div class='col-lg-6'>
                                            <div class='finlance_content'>";

                $blogDate = \Carbon\Carbon::parse($blog->created_at)->locale("$lang->code");
                $blogDate = $blogDate->translatedFormat('jS F, Y');

                $blogSec .= "<div class='post_meta'>
                                                    <span><i class='far fa-user'></i><a href='#'>" . __('By') . " " . __('Admin') . "</a></span>
                                                    <span><i class='far fa-calendar-alt'></i><a href='#'>" . $blogDate . "</a></span>
                                                </div>
                                                <h3 class='post_title'><a href='" . route('front.blogdetails', [$blog->slug, $blog->id]) . "'>" . (convertUtf8(strlen($blog->title)) > 40 ? convertUtf8(substr($blog->title, 0, 40)) . '...' : convertUtf8($blog->title)) . "</a></h3>
                                                <p>" . (convertUtf8(strlen(strip_tags($blog->content)) > 100) ? convertUtf8(substr(strip_tags($blog->content), 0, 100)) . '...' : convertUtf8(strip_tags($blog->content))) . "</p>
                                                <a href='" . route('front.blogdetails', [$blog->slug, $blog->id]) . "' class='finlance_btn'>" . __('Read More') . "</a>
                                            </div>
                                        </div>
                                        <div class='col-lg-6'>
                                            <div class='finlance_img'>
                                                <a href='" . route('front.blogdetails', [$blog->slug, $blog->id]) . "'><img src='" . url('assets/front/img/blogs/' . $blog->main_image) . "' class='img-fluid' alt=''></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>";
            }
            $blogSec .= "</div>
                </div>
            </section>";



            // CTA Section (Car Version)
            $ctaSec = "<section class='finlance_cta cta_v1 pb-mb30 lazy " . ($rtl == 1 ? 'pb-rtl' : '') . "' data-bg='" . url('assets/front/img/pattern_bg_2.jpg') . "' style='padding: 70px 0px;'>
                <div class='container'>
                    <div class='row align-items-center'>
                        <div class='col-lg-8'>
                            <div class='section_title'>
                                <h2>" . convertUtf8($bs->cta_section_text) . "</h2>
                            </div>
                        </div>
                        <div class='col-lg-4'>
                            <div class='button_box'>
                                <a href='" . $bs->cta_section_button_url . "' class='finlance_btn'>" . convertUtf8($bs->cta_section_button_text) . "</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>";




            // Partners Section (Car Version)
            $partnerSec = "<section class='finlance_partner partner_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "' style='padding: 100px 0px;'>
                <div class='container'>
                    <div class='partner_slide'>";
            foreach ($partners as $key => $partner) {
                $partnerSec .= "<div class='single_partner' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <a href='" . $partner->url . "' target='_blank'><img data-src='" . url('assets/front/img/partners/' . $partner->image) . "' class='img-fluid lazy' alt=''></a>
                            </div>";
            }
            $partnerSec .= "</div>
                </div>
            </section>";
        }


        // For Cleaning Version
        if ($version == 'cleaning') {

            // Intro Section (Cleaning Version)
            $introsec = "<section class='project-video-area pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                    <div class='container'>
                        <div class='row justify-content-center'>
                            <div class='col-lg-8'>
                                <div class='section-title-one text-center'>
                                    <span>" . convertUtf8($bs->intro_section_title) . "</span>
                                    <h1>" . convertUtf8($bs->intro_section_text) . "</h1>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-lg-12'>
                                <div class='video-content lazy' data-bg='" . url('assets/front/img/' . $bs->intro_bg) . "' style='background-size:cover;'>
                                    <div class='video-content-overlay' style='background: #" . $be->intro_overlay_color . "; opacity: " . $be->intro_overlay_opacity . ";'></div>
                                    <a href='" . $bs->intro_section_video_link . "' class='play-btn video-popup'><i class='fa fa-play'></i></a>
                                </div>";
            if (!empty($bs->intro_section_button_url) && !empty($bs->intro_section_button_text)) {
                $introsec .= "<div class='video-btn-area'>
                                        <a href='" . $bs->intro_section_button_url . "' class='main-btn video-btn' target='_blank'>" . convertUtf8($bs->intro_section_button_text) . "</a>
                                    </div>";
            }
            $introsec .= "</div>
                        </div>
                    </div>
                </section>";



            // Approach Section (Cleaning Version)
            $approachsec = "<section class='about-area pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-6'>
                            <div class='section-title-two'>
                                <span>" . convertUtf8($bs->approach_title) . "</span>
                                <h1>" . convertUtf8($bs->approach_subtitle) . "</h1>
                            </div>";
            if (!empty($bs->approach_button_url) && !empty($bs->approach_button_text)) {
                $approachsec .= "<div class='button_box'>
                                    <a href='" . $bs->approach_button_url . "' class='main-btn about-btn'>" . convertUtf8($bs->approach_button_text) . "</a>
                                </div>";
            }
            $approachsec .= "</div>
                        <div class='col-lg-6'>";
            foreach ($points as $key => $point) {
                $approachsec .= "<div class='single-about-item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                    <p  class='bg-1' style='color: #" . $point->color . "; background: #" . $point->color . "2a;'><span><i class='" . $point->icon . "'></i></span></p>
                                    <h4>" . convertUtf8($point->title) . "
                                        <span>" . convertUtf8($point->short_text) . "</span>
                                    </h4>
                                </div>";
            }
            $approachsec .= "</div>
                    </div>
                </div>
            </section>";


            // Service Categories Section (Cleaning Version)
            $scatsec = "<section class='service-area pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "' style='padding: 0;'>
                <div class='container'>
                    <div class='row justify-content-center'>
                        <div class='col-lg-8'>
                            <div class='section-title-one text-center'>
                                <span>" . convertUtf8($bs->service_section_title) . "</span>
                                <h1>" . convertUtf8($bs->service_section_subtitle) . "</h1>
                            </div>
                        </div>
                    </div>
                    <div class='service-carousel-active service-slick'>";

            foreach ($scats as $key => $scat) {
                $scatsec .= "<div class='single-service-item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">";
                if (!empty($scat->image)) {
                    $scatsec .= "<div class='single-service-bg'>
                                        <img data-src='" . url('assets/front/img/service_category_icons/' . $scat->image) . "' class='img-fluid lazy' alt=''>
                                        <span><i class='fas fa-quidditch'></i></span>
                                        <div class='single-service-link'>
                                            <a href='" . route('front.services', ['category' => $scat->id]) . "' class='main-btn service-btn'>" . __('View Services') . "</a>
                                        </div>
                                    </div>
                                    <div class='single-service-content'>
                                        <h4>" . convertUtf8($scat->name) . "</h4>
                                        <p>" . convertUtf8($scat->short_text) . "</p>
                                    </div>";
                }
                $scatsec .= "</div>";
            }


            $scatsec .= "</div>
                </div>
            </section>";


            // Featured Services Section (Cleaning Version)
            $servicesSec = "<section class='service-area pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "' style='padding: 0;'>
                <div class='container'>
                    <div class='row justify-content-center'>
                        <div class='col-lg-8'>
                            <div class='section-title-one text-center'>
                                <span>" . convertUtf8($bs->service_section_title) . "</span>
                                <h1>" . convertUtf8($bs->service_section_subtitle) . "</h1>
                            </div>
                        </div>
                    </div>
                    <div class='service-carousel-active service-slick'>";

            foreach ($services as $key => $service) {
                $servicesSec .= "<div class='single-service-item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">";
                if (!empty($service->main_image)) {
                    $servicesSec .= "<div class='single-service-bg'>
                                            <img class='lazy' data-src='" . url('assets/front/img/services/' . $service->main_image) . "' alt=''>
                                            <span><i class='fas fa-quidditch'></i></span>";
                    if ($service->details_page_status == 1) {
                        $servicesSec .= "<div class='single-service-link'>
                                                    <a href='" . route('front.servicedetails', [$service->slug, $service->id]) . "' class='main-btn service-btn'>" . __('View More') . "</a>
                                                </div>";
                    }
                    $servicesSec .= "</div>
                                        <div class='single-service-content'>
                                            <h4>" . convertUtf8($service->title) . "</h4>
                                            <p>" . convertUtf8($service->summary) . "</p>
                                        </div>";
                }
                $servicesSec .= "</div>";
            }

            $servicesSec .= "</div>
                </div>
            </section>";

            // Featured Portfolios Section (Cleaning Version)
            $portfoliosSec = "<section class='project-area pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-8'>
                            <div class='section-title-two'>
                                <span>" . convertUtf8($bs->portfolio_section_title) . "</span>
                                <h1>" . convertUtf8($bs->portfolio_section_text) . "</h1>
                            </div>
                        </div>
                        <div class='col-lg-4 text-right'>
                            <a href='" . route('front.portfolios') . "' class='project-btn'>" . __('View More') . " <i class='fa fa-arrow-right'></i></a>
                        </div>
                    </div>
                </div>
                <div class='container-fluid'>
                    <div class='project-slider-active project-slick'>";
            foreach ($portfolios as $key => $portfolio) {
                $portfoliosSec .= "<div class='single-project-item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <img class='lazy' data-src='" . url('assets/front/img/portfolios/featured/' . $portfolio->featured_image) . "' alt=''>
                                <div class='project-link text-center'>
                                    <h4>" . (convertUtf8(strlen($portfolio->title)) > 36 ? convertUtf8(substr($portfolio->title, 0, 36)) . '...' : convertUtf8($portfolio->title)) . "</h4>";
                if (!empty($portfolio->service)) {
                    $portfoliosSec .= "<span>" . convertUtf8($portfolio->service->title) . "</span>";
                }
                $portfoliosSec .= "<a href='" . route('front.portfoliodetails', [$portfolio->slug, $portfolio->id]) . "' class='main-btn project-link-btn'>" . __('View Details') . "</a>
                                </div>
                            </div>";
            }
            $portfoliosSec .= "</div>
                </div>
            </section>";

            // Featured Team Section (Cleaning Version)
            $teamSec = "<section class='team-area pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row justify-content-center'>
                        <div class='col-lg-8'>
                            <div class='section-title-one text-center'>
                                <span>" . convertUtf8($bs->team_section_title) . "</span>
                                <h1>" . convertUtf8($bs->team_section_subtitle) . "</h1>
                            </div>
                        </div>
                    </div>
                    <div class='team-carousel-active team-slick'>";
            foreach ($members as $key => $member) {
                $teamSec .= "<div class='single-team-item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <img class='lazy' data-src='" . url('assets/front/img/members/' . $member->image) . "' alt=''>
                                <div class='single-team-content'>
                                    <div class='single-team-member-details'>
                                        <h4>" . convertUtf8($member->name) . "</h4>
                                        <p>" . convertUtf8($member->rank) . "</p>
                                    </div>
                                    <ul class='team-social-links'>";
                if (!empty($member->facebook)) {
                    $teamSec .= "<li><a href='" . $member->facebook . "' target='_blank'><i class='fab fa-facebook-f'></i></a></li>";
                }
                if (!empty($member->twitter)) {
                    $teamSec .= "<li><a href='" . $member->twitter . "' target='_blank'><i class='fab fa-twitter'></i></a></li>";
                }
                if (!empty($member->linkedin)) {
                    $teamSec .= "<li><a href='" . $member->linkedin . "' target='_blank'><i class='fab fa-linkedin-in'></i></a></li>";
                }
                if (!empty($member->instagram)) {
                    $teamSec .= "<li><a href='" . $member->instagram . "' target='_blank'><i class='fab fa-instagram'></i></a></li>";
                }
                $teamSec .= "</ul>
                                </div>
                            </div>";
            }
            $teamSec .= "</div>
                </div>
            </section>";


            // Counter Section (Cleaning Version)
            $statisticSec = "<section class='project-counter-area pb-mb30 lazy " . ($rtl == 1 ? 'pb-rtl' : '') . "' data-bg='" . url('assets/front/img/' . $be->statistics_bg) . "' style='padding: 100px 0px;'>
                <div class='project-counter-overlay' style='background: #" . $be->statistics_overlay_color . "; opacity: " . $be->statistics_overlay_opacity . ";'></div>
                <div class='container'>
                    <div class='row'>";
            foreach ($statistics as $key => $statistic) {
                $statisticSec .= "<div class='col-lg-3 col-md-6' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='single-counter-item'>
                                    <span><i class='" . $statistic->icon . "'></i></span>
                                    <h1><span class='count'>" . convertUtf8($statistic->quantity) . "</span>   +</h1>
                                    <p>" . convertUtf8($statistic->title) . "</p>
                                </div>
                            </div>";
            }
            $statisticSec .= "</div>
                </div>
            </section>";


            // Testimonial Section (Cleaning Version)
            $testimonialSec = "<section class='testimonial-area pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row justify-content-center'>
                        <div class='col-lg-8'>
                            <div class='section-title-one text-center'>
                                <span>" . convertUtf8($bs->testimonial_title) . "</span>
                                <h1>" . convertUtf8($bs->testimonial_subtitle) . "</h1>
                            </div>
                        </div>
                    </div>
                    <div class='testimonial-active'>";
            foreach ($testimonials as $key => $testimonial) {
                $testimonialSec .= "<div class='single-testimonial-item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='testimonial-author-img'>
                                    <img data-src='" . url('assets/front/img/testimonials/' . $testimonial->image) . "' class='img-fluid lazy' alt=''>
                                </div>
                                <div class='testimonial-author-details'>
                                    <h4>" . convertUtf8($testimonial->name) . " <span>" . convertUtf8($testimonial->rank) . "</span></h4>
                                    <p>" . convertUtf8($testimonial->comment) . "</p>
                                </div>
                            </div>";
            }
            $testimonialSec .= "</div>
                </div>
            </section>";

            // Featured Package Section (Cleaning Version)
            $packageSec = "<section class='price-area pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row justify-content-center'>
                        <div class='col-lg-8'>
                            <div class='section-title-one text-center'>
                                <span>" . convertUtf8($be->pricing_title) . "</span>
                                <h1>" . convertUtf8($be->pricing_subtitle) . "</h1>
                            </div>
                        </div>
                    </div>
                    <div class='price-carousel-active pricing-slick'>";
            foreach ($packages as $key => $package) {
                $packageSec .= "<div class='single-price-item text-center' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='price-heading'>
                                    <h3>" . convertUtf8($package->title) . "</h3>";
                                    if($bex->recurring_billing == 1) {
                                        $packageSec .= "<p>" . ($package->duration == 'monthly' ? __('Monthly') : __('Yearly')) . "</p>";
                                    }
                                $packageSec .= "</div>
                                <h1 class='bg-1' style='background: #" . $package->color . ";'>" . ($bex->base_currency_symbol_position == 'left' ? $bex->base_currency_symbol : '') . $package->price . ($bex->base_currency_symbol_position == 'right' ? $bex->base_currency_symbol : '') . "</h1>
                                <div class='price-cata mb-4'>" . replaceBaseUrl(convertUtf8($package->description)) . "</div>";
                if ($package->order_status == 1) {
                    $packageSec .= "<a href='" . route('front.packageorder.index', $package->id) . "' class='main-btn price-btn'>" . __('Place Order') . "</a>";
                }
                $packageSec .= "</div>";
            }
            $packageSec .= "</div>
                </div>
            </section>";

            // Latest Blogs Section (Cleaning Version)
            $blogSec = "<section class='blog-area pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-7'>
                            <div class='section-title-two'>
                                <span>" . convertUtf8($bs->blog_section_title) . "</span>
                                <h1>" . convertUtf8($bs->blog_section_subtitle) . "</h1>
                            </div>
                        </div>
                        <div class='col-lg-5 text-right'>
                            <a href='" . route('front.blogs') . "' class='blog-link'>" . __('View More') . " <i class='fa fa-arrow-right'></i></a>
                        </div>
                    </div>
                    <div class='blog-carousel-active blog-slick'>";
            foreach ($blogs as $key => $blog) {

                $blogSec .= "<div class='single-blog-item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='single-blog-img'>
                                    <img src='" . url('assets/front/img/blogs/' . $blog->main_image) . "' alt=''>
                                </div>
                                <div class='single-blog-details'>";

                $blogDate = \Carbon\Carbon::parse($blog->created_at)->locale("$lang->code");
                $blogDate = $blogDate->translatedFormat('d M. Y');

                $blogSec .= "<span><i class='fa fa-arrow-right'></i>" . __('By') . " " . __('Admin') . "</span>
                                    <span><i class='fa fa-arrow-right'></i>" . $blogDate . "</span>
                                    <h4>" . (convertUtf8(strlen($blog->title)) > 40 ? convertUtf8(substr($blog->title, 0, 40)) . '...' : convertUtf8($blog->title)) . "</h4>
                                    <p>" . (convertUtf8(strlen(strip_tags($blog->content)) > 100) ? convertUtf8(substr(strip_tags($blog->content), 0, 100)) . '...' : convertUtf8(strip_tags($blog->content))) . "</p>
                                    <a href='" . route('front.blogdetails', [$blog->slug, $blog->id]) . "' class='blog-btn'>" . __('Read More') . " <i class='fa fa-arrow-right'></i></a>
                                </div>
                            </div>";
            }
            $blogSec .= "</div>
                </div>
            </section>";

            // CTA Section (Cleaning Version)
            $ctaSec = "<section class='cta-area pb-mb30 lazy " . ($rtl == 1 ? 'pb-rtl' : '') . "' data-bg='" . url('assets/front/img/' . $bs->cta_bg) . "' style='background-size:cover;'>
                <div class='cta-overlay' style='background: #" . $be->cta_overlay_color . "; opacity: " . $be->cta_overlay_opacity . ";'></div>
                <div class='container'>
                    <div class='row align-items-center'>
                        <div class='col-lg-8'>
                            <h1>" . convertUtf8($bs->cta_section_text) . "</h1>
                        </div>
                        <div class='col-lg-4 text-center'>
                            <a href='" . $bs->cta_section_button_url . "' class='main-btn cta-btn'>" . convertUtf8($bs->cta_section_button_text) . "</a>
                        </div>
                    </div>
                </div>
            </section>";

            // Partner Section (Cleaning Version)
            $partnerSec = "<section class='bran-area pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "' style='padding: 100px 0;'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-12'>
                            <div class='brand-container brand-carousel-active'>";
            foreach ($partners as $key => $partner) {
                $partnerSec .= "<div class='single-brand-logo' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                        <a class='d-block' href='" . $partner->url . "' target='_blank'><img data-src='" . url('assets/front/img/partners/' . $partner->image) . "' class='img-fluid lazy' alt=''></a>
                                    </div>";
            }
            $partnerSec .= "</div>
                        </div>
                    </div>
                </div>
            </section>";
        }



        // For Construction Version
        if ($version == 'construction') {

            // Intro Section (Construction Version)
            $introsec = "<section class='finlance_about about_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row align-items-center'>
                        <div class='col-lg-6'>
                            <div class='finlance_box_img'>
                                <div class='finlance_img'>
                                    <img data-src='" . url('assets/front/img/' . $bs->intro_bg) . "' class='img-fluid lazy' alt=''>
                                </div>
                                <div class='play_box'>
                                    <a href='" . $bs->intro_section_video_link . "' class='play_btn'><i class='fas fa-play'></i></a>
                                </div>
                            </div>
                        </div>
                        <div class='col-lg-6'>
                            <div class='finlance_content_box'>
                                <div class='section_title'>
                                    <span>" . convertUtf8($bs->intro_section_title) . "</span>
                                    <h2>" . convertUtf8($bs->intro_section_text) . "</h2>
                                </div>";
            if (!empty($bs->intro_section_button_url) && !empty($bs->intro_section_button_text)) {
                $introsec .= "<div class='button_box'>
                                        <a href='" . $bs->intro_section_button_url . "' class='finlance_btn'>" . convertUtf8($bs->intro_section_button_text) . "</a>
                                    </div>";
            }
            $introsec .= "</div>
                        </div>
                    </div>
                </div>
            </section>";


            // Approach Section (Construction Version)
            $approachsec = "<section class='finlance_we_do we_do_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-6'>
                            <div class='finlance_content_box'>
                                <div class='section_title'>
                                    <span>" . convertUtf8($bs->approach_title) . "</span>
                                    <h2>" . convertUtf8($bs->approach_subtitle) . "</h2>
                                </div>";
            if (!empty($bs->approach_button_url) && !empty($bs->approach_button_text)) {
                $approachsec .= "<div class='button_box  wow fadeInUp' data-wow-delay='.4s'>
                                        <a href='" . $bs->approach_button_url . "' class='finlance_btn'>" . convertUtf8($bs->approach_button_text) . "</a>
                                    </div>";
            }
            $approachsec .= "</div>
                        </div>
                        <div class='col-lg-6'>
                            <div class='finlance_icon_box'>";
            foreach ($points as $key => $point) {
                $approachsec .= "<div class='icon_list d-flex' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                        <div class='icon'>
                                            <i class='" . $point->icon . "'></i>
                                        </div>
                                        <div class='text'>
                                            <h4>" . convertUtf8($point->title) . "</h4>
                                            <p>" . convertUtf8($point->short_text) . "</p>
                                        </div>
                                    </div>";
            }
            $approachsec .= "</div>
                        </div>
                    </div>
                </div>
            </section>";


            // Service Category Section (Construction Version)
            $scatsec = "<section class='finlance_service service_v1 gray_bg  pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-12'>
                            <div class='section_title'>
                                <span>" . convertUtf8($bs->service_section_title) . "</span>
                                <h2>" . convertUtf8($bs->service_section_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='service_slide service-slick'>";
            foreach ($scats as $key => $scat) {
                $scatsec .= "<div class='grid_item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='grid_inner_item'>
                                    <div class='finlance_icon'>
                                        <img data-src='" . url('assets/front/img/service_category_icons/' . $scat->image) . "' class='img-fluid lazy' alt=''>
                                    </div>
                                    <div class='finlance_content'>
                                        <h4>" . convertUtf8($scat->name) . "</h4>
                                        <p class='mb-0'>" . convertUtf8($scat->short_text) . "</p>
                                        <a href='" . route('front.services', ['category' => $scat->id]) . "' class='btn_link d-inline-block mt-35'>" . __('View Services') . "</a>
                                    </div>
                                </div>
                            </div>";
            }
            $scatsec .= "</div>
                </div>
            </section>";


            $servicesSec = "<section class='finlance_service service_v1 gray_bg pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-12'>
                            <div class='section_title'>
                                <span>" . convertUtf8($bs->service_section_title) . "</span>
                                <h2>" . convertUtf8($bs->service_section_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='service_slide service-slick'>";

            foreach ($services as $key => $service) {
                $servicesSec .= "<div class='grid_item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                    <div class='grid_inner_item'>";
                if (!empty($service->main_image)) {
                    $servicesSec .= "<div class='finlance_icon' style='margin-bottom: 20px;'>
                                                <img class='lazy' data-src='" . url('assets/front/img/services/' . $service->main_image) . "' alt='service' />
                                            </div>";
                }
                $servicesSec .= "<div class='finlance_content'>
                                            <h4>" . convertUtf8($service->title) . "</h4>
                                            <p class='mb-0'>" . convertUtf8($service->summary) . "</p>";
                if ($service->details_page_status == 1) {
                    $servicesSec .= "<a href='" . route('front.servicedetails', [$service->slug, $service->id]) . "' class='btn_link d-inline-block mt-35'>" . __('Read More') . "</a>";
                }
                $servicesSec .= "</div>
                                    </div>
                                </div>";
            }


            $servicesSec .= "</div>
                </div>
            </section>";


            $portfoliosSec = "<section class='finlance_project project_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-12'>
                            <div class='section_title'>
                                <span>" . convertUtf8($bs->portfolio_section_title) . "</span>
                                <h2>" . convertUtf8($bs->portfolio_section_text) . "</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='container-fluid'>
                    <div class='project_slide project-slick'>";
            foreach ($portfolios as $key => $portfolio) {
                $portfoliosSec .= "<div class='grid_item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='grid_inner_item'>
                                    <div class='finlance_img'>
                                        <img data-src='" . url('assets/front/img/portfolios/featured/' . $portfolio->featured_image) . "' class='img-fluid lazy' alt=''>
                                        <div class='overlay_img'></div>
                                        <div class='overlay_content'>
                                            <div class='button_box'>
                                                <a href='" . route('front.portfoliodetails', [$portfolio->slug, $portfolio->id]) . "' class='finlance_btn'>" . __('View More') . "</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='finlance_content'>
                                        <h4>" . (convertUtf8(strlen($portfolio->title)) > 25 ? convertUtf8(substr($portfolio->title, 0, 25)) . '...' : convertUtf8($portfolio->title)) . "</h4>";

                if (!empty($portfolio->service)) {
                    $portfoliosSec .= "<p>" . convertUtf8($portfolio->service->title) . "</p>";
                }
                $portfoliosSec .= "</div>
                                </div>
                            </div>";
            }
            $portfoliosSec .= "</div>
                </div>
            </section>";


            $teamSec = "<section class='finlance_team team_v1 gray_bg pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row align-items-center'>
                        <div class='col-lg-8'>
                            <div class='section_title'>
                                <span>" . convertUtf8($bs->team_section_title) . "</span>
                                <h2>" . convertUtf8($bs->team_section_subtitle) . "</h2>
                            </div>
                        </div>
                        <div class='col-lg-4'>
                            <div class='button_box'>
                                <a href='" . route('front.team') . "' class='btn_link'>" . __('View More') . "</a>
                            </div>
                        </div>
                    </div>
                    <div class='team_slide team-slick'>";
            foreach ($members as $key => $member) {
                $teamSec .= "<div class='grid_item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='grid_inner_item'>
                                    <div class='finlance_img'>
                                        <img data-src='" . url('assets/front/img/members/' . $member->image) . "' class='img-fluid lazy' alt=''>
                                        <div class='overlay_content'>
                                            <div class='social_box'>
                                                <ul>";
                if (!empty($member->facebook)) {
                    $teamSec .= " <li><a href='" . $member->facebook . "' target='_blank'><i class='fab fa-facebook-f'></i></a></li>";
                }
                if (!empty($member->twitter)) {
                    $teamSec .= "  <li><a href='" . $member->twitter . "' target='_blank'><i class='fab fa-twitter'></i></a></li>";
                }
                if (!empty($member->linkedin)) {
                    $teamSec .= " <li><a href='" . $member->linkedin . "' target='_blank'><i class='fab fa-linkedin-in'></i></a></li>";
                }
                if (!empty($member->instagram)) {
                    $teamSec .= "<li><a href='" . $member->instagram . "' target='_blank'><i class='fab fa-instagram'></i></a></li>";
                }
                $teamSec .= "</ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='finlance_content text-center'>
                                        <h4>" . convertUtf8($member->name) . "</h4>
                                        <p>" . convertUtf8($member->rank) . "</p>
                                    </div>
                                </div>
                            </div>";
            }
            $teamSec .= "</div>
                </div>
            </section>";


            $statisticSec = "<section class='finlance_fun finlance_fun_v1 bg_image pb-mb30 lazy " . ($rtl == 1 ? 'pb-rtl' : '') . "' data-bg='" . url('assets/front/img/' . $be->statistics_bg) . "' style='background-size:cover; padding: 100px 0px;' id='statisticsSection'>
                <div class='bg_overlay' style='background: #" . $be->statistics_overlay_color . ";opacity: " . $be->statistics_overlay_opacity . ";'></div>
                <div class='container' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                    <div class='row'>";
            foreach ($statistics as $key => $statistic) {
                $statisticSec .= "<div class='col-lg-3 col-md-6 col-sm-12'>
                                <div class='counter_box'>
                                    <div class='icon'>
                                        <i class='" . $statistic->icon . "'></i>
                                    </div>
                                    <h2><span class='counter'>" . convertUtf8($statistic->quantity) . "</span>+</h2>
                                    <p>" . convertUtf8($statistic->title) . "</p>
                                </div>
                            </div>";
            }
            $statisticSec .= "</div>
                </div>
            </section>";


            $testimonialSec = "<section class='finlance_testimonial testimonial_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-12'>
                            <div class='section_title text-center'>
                                <span>" . convertUtf8($bs->testimonial_title) . "</span>
                                <h2>" . convertUtf8($bs->testimonial_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='testimonial_slide'>";
            foreach ($testimonials as $key => $testimonial) {
                $testimonialSec .= "<div class='testimonial_box d-flex align-items-center' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='finlance_img'>
                                    <img data-src='" . url('assets/front/img/testimonials/' . $testimonial->image) . "' class='img-fluid lazy' alt=''>
                                </div>
                                <div class='finlance_content'>
                                    <h4>" . convertUtf8($testimonial->name) . "</h4>
                                    <h6>" . convertUtf8($testimonial->rank) . "</h6>
                                    <p>" . convertUtf8($testimonial->comment) . "</p>
                                </div>
                            </div>";
            }
            $testimonialSec .= "</div>
                </div>
            </section>";


            $packageSec = "<section class='finlance_pricing pricing_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-12'>
                            <div class='section_title text-center'>
                                <span>" . convertUtf8($be->pricing_title) . "</span>
                                <h2>" . convertUtf8($be->pricing_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='pricing_slide pricing-slick'>";
            foreach ($packages as $key => $package) {
                $packageSec .= "<div class='pricing_box text-center' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='pricing_title'>
                                    <h3>" . convertUtf8($package->title) . "</h3>";
                                    if($bex->recurring_billing == 1) {
                                        $packageSec .= "<p>" . ($package->duration == 'monthly' ? __('Monthly') : __('Yearly')) . "</p>";
                                    }
                                $packageSec .= "</div>
                                <div class='pricing_price'>
                                    <h3>" . ($bex->base_currency_symbol_position == 'left' ? $bex->base_currency_symbol : '') . " " . $package->price . " " . ($bex->base_currency_symbol_position == 'right' ? $bex->base_currency_symbol : '') . "</h3>
                                </div>
                                <div class='pricing_body'>" . replaceBaseUrl(convertUtf8($package->description)) . "</div>
                                <div class='pricing_button'>";
                if ($package->order_status == 1) {
                    $packageSec .= "<a href='" . route('front.packageorder.index', $package->id) . "' class='finlance_btn'>" . __('Place Order') . "</a>";
                }
                $packageSec .= "</div>
                            </div>";
            }
            $packageSec .= "</div>
                </div>
            </section>";


            $blogSec = "<section class='finlance_blog blog_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-12'>
                            <div class='section_title text-center'>
                                <span>" . convertUtf8($bs->blog_section_title) . "</span>
                                <h2>" . convertUtf8($bs->blog_section_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='blog_slide blog-slick'>";
            foreach ($blogs as $key => $blog) {
                $blogSec .= "<div class='grid_item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='grid_inner_item'>
                                    <div class='finlance_img'>
                                        <a href='" . route('front.blogdetails', [$blog->slug, $blog->id]) . "'><img src='" . url('assets/front/img/blogs/' . $blog->main_image) . "' class='img-fluid' alt=''></a>
                                    </div>
                                    <div class='finlance_content'>
                                        <div class='post_meta'>";

                $blogDate = \Carbon\Carbon::parse($blog->created_at)->locale("$lang->code");
                $blogDate = $blogDate->translatedFormat('d M. Y');

                $blogSec .= "<span><i class='far fa-user'></i><a href='#'>" . __('By') . " " . __('Admin') . "</a></span>
                                            <span><i class='far fa-calendar-alt'></i><a href='#'>" . $blogDate . "</a></span>
                                        </div>
                                        <h3 class='post_title'><a href='" . route('front.blogdetails', [$blog->slug, $blog->id]) . "'>" . (convertUtf8(strlen($blog->title)) > 40 ? convertUtf8(substr($blog->title, 0, 40)) . '...' : convertUtf8($blog->title)) . "</a></h3>
                                        <a href='" . route('front.blogdetails', [$blog->slug, $blog->id]) . "' class='btn_link'>" . __('Read More') . "</a>
                                    </div>
                                </div>
                            </div>";
            }
            $blogSec .= "</div>
                </div>
            </section>";


            $ctaSec = "<section class='finlance_cta cta_v1 main_bg pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "' style='padding: 60px 0px;'>
                <div class='container'>
                    <div class='row align-items-center'>
                        <div class='col-lg-7'>
                            <div class='section_title'>
                                <h2 class='text-white'>" . convertUtf8($bs->cta_section_text) . "</h2>
                            </div>
                        </div>
                        <div class='col-lg-5'>
                            <div class='button_box'>
                                <a href='" . $bs->cta_section_button_url . "' class='finlance_btn'>" . convertUtf8($bs->cta_section_button_text) . "</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>";


            $partnerSec = "<section class='finlance_partner partner_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "' style='padding: 100px 0px;'>
                <div class='container'>
                    <div class='partner_slide'>";
            foreach ($partners as $key => $partner) {
                $partnerSec .= "<div class='single_partner' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <a href='" . $partner->url . "'><img data-src='" . url('assets/front/img/partners/' . $partner->image) . "' class='img-fluid lazy' alt=''></a>
                            </div>";
            }
            $partnerSec .= "</div>
                </div>
            </section>";
        }


        // For Logistic Version
        if ($version == 'logistic') {

            $introsec = "<section class='logistics_about about_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row align-items-center'>
                        <div class='col-lg-6'>
                            <div class='logistics_box_img'>
                                <div class='logistics_img'>
                                    <img data-src='" . url('assets/front/img/' . $bs->intro_bg) . "' class='img-fluid lazy' alt=''>
                                </div>
                                <div class='logistics_img'>
                                    <img data-src='" . url('assets/front/img/' . $be->intro_bg2) . "' class='img-fluid lazy' alt=''>
                                    <div class='play_box'>
                                        <a href='" . $bs->intro_section_video_link . "' class='play_btn'><i class='fas fa-play'></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='col-lg-6'>
                            <div class='logistics_content_box'>
                                <div class='section_title'>
                                    <span>" . convertUtf8($bs->intro_section_title) . "</span>
                                    <h2>" . convertUtf8($bs->intro_section_text) . "</h2>
                                </div>
                                <div class='button_box'>
                                    <a href='" . $bs->intro_section_button_url . "' class='logistics_btn'>" . convertUtf8($bs->intro_section_button_text) . "</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>";


            $approachsec = "<section class='logistics_we_do we_do_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-6'>
                            <div class='logistics_content_box'>
                                <div class='section_title'>
                                    <span>" . convertUtf8($bs->approach_title) . "</span>
                                    <h2>" . convertUtf8($bs->approach_subtitle) . "</h2>
                                </div>";
            if (!empty($bs->approach_button_url) && !empty($bs->approach_button_text)) {
                $approachsec .= "<div class='button_box'>
                                        <a href='" . $bs->approach_button_url . "' class='logistics_btn'>" . convertUtf8($bs->approach_button_text) . "</a>
                                    </div>";
            }
            $approachsec .= "</div>
                        </div>
                        <div class='col-lg-6'>
                            <div class='logistics_icon_box'>";
            foreach ($points as $key => $point) {
                $approachsec .= "<div class='icon_list d-flex' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                        <div class='icon'>
                                            <i class='" . $point->icon . "'></i>
                                        </div>
                                        <div class='text'>
                                            <h4>" . convertUtf8($point->title) . "</h4>
                                            <p>" . convertUtf8($point->short_text) . "</p>
                                        </div>
                                    </div>";
            }
            $approachsec .= "</div>
                        </div>
                    </div>
                </div>
            </section>";


            $scatsec = "<section class='logistics_service service_v1 dark_bg pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-12'>
                            <div class='section_title'>
                                <span>" . convertUtf8($bs->service_section_title) . "</span>
                                <h2>" . convertUtf8($bs->service_section_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='service_slide service-slick'>";
            foreach ($scats as $key => $scat) {
                $scatsec .= "<div class='grid_item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='grid_inner_item'>
                                    <div class='logistics_icon'>
                                        <img data-src='" . url('assets/front/img/service_category_icons/' . $scat->image) . "' class='img-fluid lazy' alt=''>
                                    </div>
                                    <div class='logistics_content'>
                                        <h4>" . convertUtf8($scat->name) . "</h4>
                                        <p>" . convertUtf8($scat->short_text) . "</p>
                                        <a href='" . route('front.services', ['category' => $scat->id]) . "' class='btn_link'>" . __('View Services') . "</a>
                                    </div>
                                </div>
                            </div>";
            }
            $scatsec .= "</div>
                </div>
            </section>";


            $servicesSec = "<section class='logistics_service service_v1 dark_bg pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-12'>
                            <div class='section_title'>
                                <span>" . convertUtf8($bs->service_section_title) . "</span>
                                <h2>" . convertUtf8($bs->service_section_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='service_slide service-slick'>";
            foreach ($services as $key => $service) {
                $servicesSec .= "<div class='grid_item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='grid_inner_item'>";

                if (!empty($service->main_image)) {
                    $servicesSec .= "<div class='logistics_icon'>
                                            <img data-src='" . url('assets/front/img/services/' . $service->main_image) . "' class='img-fluid lazy' alt=''>
                                        </div>";
                }

                $servicesSec .= "<div class='logistics_content'>
                                        <h4>" . convertUtf8($service->title) . "</h4>
                                        <p>" . convertUtf8($service->summary) . "</p>";

                if ($service->details_page_status == 1) {
                    $servicesSec .= "<a href='" . route('front.servicedetails', [$service->slug, $service->id]) . "' class='btn_link'>" . __('View Services') . "</a>";
                }

                $servicesSec .= "</div>
                                </div>
                            </div>";
            }
            $servicesSec .= "</div>
                </div>
            </section>";


            $portfoliosSec = "<section class='logistics_project project_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-12'>
                            <div class='section_title'>
                                <span>" . convertUtf8($bs->portfolio_section_title) . "</span>
                                <h2>" . convertUtf8($bs->portfolio_section_text) . "</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='container-fluid'>
                    <div class='project_slide project-slick'>";
            foreach ($portfolios as $key => $portfolio) {
                $portfoliosSec .= "<div class='grid_item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='grid_inner_item'>
                                    <div class='logistics_img'>
                                        <img data-src='" . url('assets/front/img/portfolios/featured/' . $portfolio->featured_image) . "' class='img-fluid lazy' alt=''>
                                        <div class='overlay_img'></div>
                                        <div class='overlay_content'>
                                            <div class='button_box'>
                                                <a href='" . route('front.portfoliodetails', [$portfolio->slug, $portfolio->id]) . "' class='logistics_btn'>" . __('View More') . "</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='logistics_content'>
                                        <h4>" . (convertUtf8(strlen($portfolio->title)) > 25 ? convertUtf8(substr($portfolio->title, 0, 25)) . '...' : convertUtf8($portfolio->title)) . "</h4>";

                if (!empty($portfolio->service)) {
                    $portfoliosSec .= "<p>" . convertUtf8($portfolio->service->title) . "</p>";
                }
                $portfoliosSec .= "</div>
                                </div>
                            </div>";
            }
            $portfoliosSec .= "</div>
                </div>
            </section>";


            $teamSec = "<section class='logistics_team team_v1 gray_bg pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row align-items-center'>
                        <div class='col-lg-8'>
                            <div class='section_title'>
                                <span>" . convertUtf8($bs->team_section_title) . "</span>
                                <h2>" . convertUtf8($bs->team_section_subtitle) . "</h2>
                            </div>
                        </div>
                        <div class='col-lg-4'>
                            <div class='button_box'>
                                <a href='" . route('front.team') . "' class='btn_link'>" . __('View More') . "</a>
                            </div>
                        </div>
                    </div>
                    <div class='team_slide team-slick'>";
            foreach ($members as $key => $member) {
                $teamSec .= "<div class='grid_item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='grid_inner_item'>
                                    <div class='logistics_img'>
                                        <img data-src='" . url('assets/front/img/members/' . $member->image) . "' class='img-fluid lazy' alt=''>
                                        <div class='overlay_content'>
                                            <div class='social_box'>
                                                <ul>";
                if (!empty($member->facebook)) {
                    $teamSec .= "<li><a href='" . $member->facebook . "' target='_blank'><i class='fab fa-facebook-f'></i></a></li>";
                }
                if (!empty($member->twitter)) {
                    $teamSec .= "<li><a href='" . $member->twitter . "' target='_blank'><i class='fab fa-twitter'></i></a></li>";
                }
                if (!empty($member->linkedin)) {
                    $teamSec .= "<li><a href='" . $member->linkedin . "' target='_blank'><i class='fab fa-linkedin-in'></i></a></li>";
                }
                if (!empty($member->instagram)) {
                    $teamSec .= "<li><a href='" . $member->instagram . "' target='_blank'><i class='fab fa-instagram'></i></a></li>";
                }
                $teamSec .= "</ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='logistics_content text-center'>
                                        <h4>" . convertUtf8($member->name) . "</h4>
                                        <p>" . convertUtf8($member->rank) . "</p>
                                    </div>
                                </div>
                            </div>";
            }
            $teamSec .= "</div>
                </div>
            </section>";


            $statisticSec = "<section class='logistics_fun logistics_fun_v1 bg_image pb-mb30 lazy " . ($rtl == 1 ? 'pb-rtl' : '') . "' data-bg='" . url('assets/front/img/' . $be->statistics_bg) . "' style='background-size:cover; padding: 100px 0px;' id='statisticsSection'>
                <div class='bg_overlay' style='background: #" . $be->statistics_overlay_color . ";opacity: " . $be->statistics_overlay_opacity . ";'></div>
                <div class='container' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                    <div class='row'>";
            foreach ($statistics as $key => $statistic) {
                $statisticSec .= "<div class='col-lg-3 col-md-6 col-sm-12'>
                                <div class='counter_box'>
                                    <div class='icon'>
                                        <i class='" . $statistic->icon . "'></i>
                                    </div>
                                    <h2><span class='counter'>" . convertUtf8($statistic->quantity) . "</span>+</h2>
                                    <p>" . convertUtf8($statistic->title) . "</p>
                                </div>
                            </div>";
            }
            $statisticSec .= "</div>
                </div>
            </section>";


            $testimonialSec = "<section class='logistics_testimonial testimonial_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-12'>
                            <div class='section_title text-center'>
                                <span>" . convertUtf8($bs->testimonial_title) . "</span>
                                <h2>" . convertUtf8($bs->testimonial_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='testimonial_slide'>";
            foreach ($testimonials as $key => $testimonial) {
                $testimonialSec .= "<div class='testimonial_box d-lg-flex align-items-lg-center' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='logistics_img'>
                                    <img data-src='" . url('assets/front/img/testimonials/' . $testimonial->image) . "' class='img-fluid lazy' alt='' width='100%'>
                                </div>
                                <div class='logistics_content'>
                                    <h4>" . convertUtf8($testimonial->name) . "</h4>
                                    <h6>" . convertUtf8($testimonial->rank) . "</h6>
                                    <p>" . convertUtf8($testimonial->comment) . "</p>
                                </div>
                            </div>";
            }
            $testimonialSec .= "</div>
                </div>
            </section>";


            $packageSec = "<section class='logistics_pricing pricing_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-12'>
                            <div class='section_title text-center'>
                                <span>" . convertUtf8($be->pricing_title) . "</span>
                                <h2>" . convertUtf8($be->pricing_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='pricing_slide pricing-slick'>";
            foreach ($packages as $key => $package) {
                $packageSec .= "<div class='pricing_box text-center' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='pricing_title'>
                                    <h3>" . convertUtf8($package->title) . "</h3>";
                                    if($bex->recurring_billing == 1) {
                                        $packageSec .= "<p>" . ($package->duration == 'monthly' ? __('Monthly') : __('Yearly')) . "</p>";
                                    }
                                $packageSec .= "</div>
                                <div class='pricing_price'>
                                    <h3>" . ($bex->base_currency_symbol_position == 'left' ? $bex->base_currency_symbol : '') . " " . $package->price . " " . ($bex->base_currency_symbol_position == 'right' ? $bex->base_currency_symbol : '') . "</h3>
                                </div>
                                <div class='pricing_body'>" . replaceBaseUrl(convertUtf8($package->description)) . "</div>
                                <div class='pricing_button'>";
                if ($package->order_status == 1) {
                    $packageSec .= "<a href='" . route('front.packageorder.index', $package->id) . "' class='logistics_btn'>" . __('Place Order') . "</a>";
                }
                $packageSec .= "</div>
                            </div>";
            }
            $packageSec .= "</div>
                </div>
            </section>";


            $blogSec = "<section class='logistics_blog blog_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-12'>
                            <div class='section_title text-center'>
                                <span>" . convertUtf8($bs->blog_section_title) . "</span>
                                <h2>" . convertUtf8($bs->blog_section_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='blog_slide blog-slick'>";
            foreach ($blogs as $key => $blog) {
                $blogSec .= "<div class='grid_item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='grid_inner_item'>
                                    <div class='logistics_img'>
                                        <a href='" . route('front.blogdetails', [$blog->slug, $blog->id]) . "'><img src='" . url('assets/front/img/blogs/' . $blog->main_image) . "' class='img-fluid' alt=''></a>
                                    </div>
                                    <div class='logistics_content'>
                                        <div class='post_meta'>";

                $blogDate = \Carbon\Carbon::parse($blog->created_at)->locale("$lang->code");
                $blogDate = $blogDate->translatedFormat('d M. Y');

                $blogSec .= "<span><i class='far fa-user'></i><a href='#'>" . __('By') . " " . __('Admin') . "</a></span>
                                            <span><i class='far fa-calendar-alt'></i><a href='#'>" . $blogDate . "</a></span>
                                        </div>
                                        <h3 class='post_title'><a href='" . route('front.blogdetails', [$blog->slug, $blog->id]) . "'>" . (convertUtf8(strlen($blog->title)) > 40 ? convertUtf8(substr($blog->title, 0, 40)) . '...' : convertUtf8($blog->title)) . "</a></h3>
                                        <a href='" . route('front.blogdetails', [$blog->slug, $blog->id]) . "' class='btn_link'>" . __('Read More') . "</a>
                                    </div>
                                </div>
                            </div>";
            }
            $blogSec .= "</div>
                </div>
            </section>";


            $ctaSec = "<section class='logistics_cta cta_v1 main_bg pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "' style='padding: 70px 0px;'>
                <div class='container'>
                    <div class='row align-items-center'>
                        <div class='col-lg-9'>
                            <div class='section_title'>
                                <h2 class='text-white'>" . convertUtf8($bs->cta_section_text) . "</h2>
                            </div>
                        </div>
                        <div class='col-lg-3'>
                            <div class='button_box'>
                                <a href='" . $bs->cta_section_button_url . "' class='logistics_btn'>" . convertUtf8($bs->cta_section_button_text) . "</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>";


            $partnerSec = "<section class='logistics_partner partner_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "' style='padding: 100px 0px;'>
                <div class='container'>
                    <div class='partner_slide'>";
            foreach ($partners as $key => $partner) {
                $partnerSec .= "<div class='single_partner' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <a href='" . $partner->url . "'><img data-src='" . url('assets/front/img/partners/' . $partner->image) . "' class='img-fluid lazy' alt=''></a>
                            </div>";
            }
            $partnerSec .= "</div>
                </div>
            </section>";
        }


        // For Lawyer Version
        if ($version == 'lawyer') {

            $introsec = "<section class='lawyer_about about_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "' id='about_v1'>
                <div class='container'>
                    <div class='row align-items-center'>
                        <div class='col-lg-7'>
                            <div class='lawyer_box_img'>
                                <div class='lawyer_img'>
                                    <img data-src='" . url('assets/front/img/' . $bs->intro_bg) . "' class='img-fluid lazy' alt=''>
                                </div>
                                <div class='lawyer_img'>
                                    <img data-src='" . url('assets/front/img/' . $be->intro_bg2) . "' class='img-fluid lazy' alt=''>
                                    <div class='play_box'>
                                        <a href='" . $bs->intro_section_video_link . "' class='play_btn'><i class='fas fa-play'></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='col-lg-5'>
                            <div class='lawyer_content_box'>
                                <div class='section_title'>
                                    <span>" . convertUtf8($bs->intro_section_title) . "</span>
                                    <h2>" . convertUtf8($bs->intro_section_text) . "</h2>
                                </div>
                                <div class='button_box'>
                                    <a href='" . $bs->intro_section_button_url . "' class='lawyer_btn'>" . convertUtf8($bs->intro_section_button_text) . "</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>";


            $approachsec = "<section class='lawyer_we_do we_do_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-6'>
                            <div class='lawyer_content_box'>
                                <div class='section_title'>
                                    <span>" . convertUtf8($bs->approach_title) . "</span>
                                    <h2>" . convertUtf8($bs->approach_subtitle) . "</h2>
                                </div>";
            if (!empty($bs->approach_button_url) && !empty($bs->approach_button_text)) {
                $approachsec .= "<div class='button_box  wow fadeInUp' data-wow-delay='.4s'>
                                        <a href='" . $bs->approach_button_url . "' class='lawyer_btn'>" . convertUtf8($bs->approach_button_text) . "</a>
                                    </div>";
            }
            $approachsec .= "</div>
                        </div>
                        <div class='col-lg-6'>
                            <div class='lawyer_icon_box'>";
            foreach ($points as $key => $point) {
                $approachsec .= "<div class='icon_list d-flex' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                        <div class='icon'>
                                            <i class='" . $point->icon . "'></i>
                                        </div>
                                        <div class='text'>
                                            <h4>" . convertUtf8($point->title) . "</h4>
                                            <p>" . convertUtf8($point->short_text) . "</p>
                                        </div>
                                    </div>";
            }
            $approachsec .= "</div>
                        </div>
                    </div>
                </div>
            </section>";


            $scatsec = "<section class='lawyer_service service_v1 gray_bg pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-12'>
                            <div class='section_title text-center'>
                                <span>" . convertUtf8($bs->service_section_title) . "</span>
                                <h2>" . convertUtf8($bs->service_section_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='service_slide service-slick'>";
            foreach ($scats as $key => $scat) {
                $scatsec .= "<div class='grid_item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                    <div class='grid_inner_item'>
                                        <div class='lawyer_img'>
                                            <img data-src='" . url('assets/front/img/service_category_icons/' . $scat->image) . "' class='img-fluid lazy' alt=''>
                                        </div>
                                        <div class='lawyer_content'>
                                            <h4>" . convertUtf8($scat->name) . "</h4>
                                            <p>" . convertUtf8($scat->short_text) . "</p>
                                            <a href='" . route('front.services', ['category' => $scat->id]) . "' class='lawyer_btn'>" . __('View Services') . "</a>
                                        </div>
                                    </div>
                                </div>";
            }
            $scatsec .= "</div>
                </div>
            </section>";

            $servicesSec = "<section class='lawyer_service service_v1 gray_bg pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-12'>
                            <div class='section_title text-center'>
                                <span>" . convertUtf8($bs->service_section_title) . "</span>
                                <h2>" . convertUtf8($bs->service_section_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='service_slide service-slick'>";
            foreach ($services as $key => $service) {
                $servicesSec .= "<div class='grid_item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='grid_inner_item'>
                                    <div class='lawyer_img'>";
                if (!empty($service->main_image)) {
                    $servicesSec .= "<div class='logistics_icon'>
                                                <img data-src='" . url('assets/front/img/services/' . $service->main_image) . "' class='img-fluid lazy' alt=''>
                                            </div>";
                }
                $servicesSec .= "</div>
                                    <div class='lawyer_content'>
                                        <h4>" . convertUtf8($service->title) . "</h4>
                                        <p>" . convertUtf8($service->summary) . "</p>";
                if ($service->details_page_status == 1) {
                    $servicesSec .= "<a href='" . route('front.servicedetails', [$service->slug, $service->id]) . "' class='lawyer_btn'>" . __('Read More') . "</a>";
                }
                $servicesSec .= "</div>
                                </div>
                            </div>";
            }

            $servicesSec .= "</div>
                </div>
            </section>";


            $portfoliosSec = "<section class='lawyer_project project_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-12'>
                            <div class='section_title'>
                                <span>" . convertUtf8($bs->portfolio_section_title) . "</span>
                                <h2>" . convertUtf8($bs->portfolio_section_text) . "</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='container-fluid'>
                    <div class='project_slide project-slick'>";
            foreach ($portfolios as $key => $portfolio) {
                $portfoliosSec .= "<div class='grid_item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='grid_inner_item'>
                                    <div class='lawyer_img'>
                                        <img data-src='" . url('assets/front/img/portfolios/featured/' . $portfolio->featured_image) . "' class='img-fluid lazy' alt=''>
                                        <div class='overlay_img'></div>
                                        <div class='overlay_content'>
                                            <h3><a href='" . route('front.portfoliodetails', [$portfolio->slug, $portfolio->id]) . "'>" . (convertUtf8(strlen($portfolio->title)) > 25 ? convertUtf8(substr($portfolio->title, 0, 25)) . '...' : convertUtf8($portfolio->title)) . "</a></h3>";
                if (!empty($portfolio->service)) {
                    $portfoliosSec .= "<p>" . convertUtf8($portfolio->service->title) . "</p>";
                }
                $portfoliosSec .= "<a href='" . route('front.portfoliodetails', [$portfolio->slug, $portfolio->id]) . "' class='lawyer_btn'>" . __('View More') . "</a>
                                        </div>
                                    </div>
                                </div>
                            </div>";
            }
            $portfoliosSec .= "</div>
                </div>
            </section>";


            $teamSec = "<section class='lawyer_team team_v1 gray_bg pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row align-items-center'>
                        <div class='col-lg-12'>
                            <div class='section_title text-center'>
                                <span>" . convertUtf8($bs->team_section_title) . "</span>
                                <h2>" . convertUtf8($bs->team_section_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='team_slide team-slick'>";
            foreach ($members as $key => $member) {
                $teamSec .= "<div class='grid_item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='grid_inner_item'>
                                    <div class='lawyer_img'>
                                        <img data-src='" . url('assets/front/img/members/' . $member->image) . "' class='img-fluid lazy' alt=''>
                                    </div>
                                    <div class='lawyer_content text-center'>
                                        <h4>" . convertUtf8($member->name) . "</h4>
                                        <p>" . convertUtf8($member->rank) . "</p>
                                        <ul class='social'>";
                if (!empty($member->facebook)) {
                    $teamSec .= "<li><a href='" . $member->facebook . "' target='_blank'><i class='fab fa-facebook-f'></i></a></li>";
                }
                if (!empty($member->twitter)) {
                    $teamSec .= "<li><a href='" . $member->twitter . "' target='_blank'><i class='fab fa-twitter'></i></a></li>";
                }
                if (!empty($member->linkedin)) {
                    $teamSec .= "<li><a href='" . $member->linkedin . "' target='_blank'><i class='fab fa-linkedin-in'></i></a></li>";
                }
                if (!empty($member->instagram)) {
                    $teamSec .= "<li><a href='" . $member->instagram . "' target='_blank'><i class='fab fa-instagram'></i></a></li>";
                }
                $teamSec .= "</ul>
                                    </div>
                                </div>
                            </div>";
            }
            $teamSec .= "</div>
                </div>
            </section>";


            $statisticSec = "<section class='lawyer_fun lawyer_fun_v1 bg_image pb-mb30 lazy " . ($rtl == 1 ? 'pb-rtl' : '') . "' data-bg='" . url('assets/front/img/' . $be->statistics_bg) . "' style='background-size:cover; padding: 100px 0px;' id='statisticsSection'>
                <div class='bg_overlay' style='background: #" . $be->statistics_overlay_color . ";opacity: " . $be->statistics_overlay_opacity . ";'></div>
                <div class='container'>
                    <div class='row'>";
            foreach ($statistics as $key => $statistic) {
                $statisticSec .= "<div class='col-lg-3 col-md-6 col-sm-12' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='counter_box'>
                                    <div class='icon'>
                                        <i class='" . $statistic->icon . "'></i>
                                    </div>
                                    <h2><span class='counter'>" . convertUtf8($statistic->quantity) . "</span>+</h2>
                                    <h4>" . convertUtf8($statistic->title) . "</h4>
                                </div>
                            </div>";
            }
            $statisticSec .= "</div>
                </div>
            </section>";

            $testimonialSec = "<section class='lawyer_testimonial testimonial_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-12'>
                            <div class='section_title'>
                                <span>" . convertUtf8($bs->testimonial_title) . "</span>
                                <h2>" . convertUtf8($bs->testimonial_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='testimonial_slide'>";
            foreach ($testimonials as $key => $testimonial) {
                $testimonialSec .= "<div class='testimonial_box' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='lawyer_content_box'>
                                    <img class='lazy' data-src='" . url('assets/front/img/quote_1.png') . "' alt=''>
                                    <p>" . convertUtf8($testimonial->comment) . "</p>
                                    <div class='admin_box d-flex align-items-center'>
                                        <div class='thumb'>
                                            <img data-src='" . url('assets/front/img/testimonials/' . $testimonial->image) . "' class='img-fluid lazy' alt=''>
                                        </div>
                                        <div class='info'>
                                            <h4>" . convertUtf8($testimonial->name) . "</h4>
                                            <p>" . convertUtf8($testimonial->rank) . "</p>
                                        </div>
                                    </div>
                                </div>
                            </div>";
            }
            $testimonialSec .= "</div>
                </div>
            </section>";


            $packageSec = "<section class='lawyer_pricing pricing_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-12'>
                            <div class='section_title text-center'>
                                <span>" . convertUtf8($be->pricing_title) . "</span>
                                <h2>" . convertUtf8($be->pricing_subtitle) . "</h2>
                            </div>
                        </div>
                    </div>
                    <div class='pricing_slide pricing-slick'>";
            foreach ($packages as $key => $package) {
                $packageSec .= "<div class='pricing_box text-center' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='pricing_title'>";
                if (!empty($package->image)) {
                    $packageSec .= "<img class='lazy' data-src='" . url('assets/front/img/packages/' . $package->image) . "' alt=''>";
                }
                                $packageSec .= "<h3>" . convertUtf8($package->title) . "</h3>";
                                if($bex->recurring_billing == 1) {
                                    $packageSec .= "<p>" . ($package->duration == 'monthly' ? __('Monthly') : __('Yearly')) . "</p>";
                                }
                                $packageSec .= "</div>
                                <div class='pricing_price'>
                                    <h3>" . ($bex->base_currency_symbol_position == 'left' ? $bex->base_currency_symbol : '') . " " . $package->price . " " . ($bex->base_currency_symbol_position == 'right' ? $bex->base_currency_symbol : '') . "</h3>
                                </div>
                                <div class='pricing_body'>" . replaceBaseUrl(convertUtf8($package->description)) . "</div>
                                <div class='pricing_button'>";
                if ($package->order_status == 1) {
                    $packageSec .= "<a href='" . route('front.packageorder.index', $package->id) . "' class='lawyer_btn'>" . __('Place Order') . "</a>";
                }
                $packageSec .= "</div>
                            </div>";
            }
            $packageSec .= "</div>
                </div>
            </section>";


            $blogSec = "<section class='lawyer_blog blog_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "'>
                <div class='container'>
                    <div class='row align-items-center'>
                        <div class='col-lg-6'>
                            <div class='section_title'>
                                <span>" . convertUtf8($bs->blog_section_title) . "</span>
                                <h2>" . convertUtf8($bs->blog_section_subtitle) . "</h2>
                            </div>
                        </div>
                        <div class='col-lg-6'>
                            <div class='button_box float-lg-right'>
                                <a href='" . route('front.blogs') . "' class='btn_link'>" . __('View More') . "</a>
                            </div>
                        </div>
                    </div>
                    <div class='blog_slide blog-slick'>";
            foreach ($blogs as $key => $blog) {
                $blogSec .= "<div class='grid_item' data-gjs-draggable='false' data-gjs-editable='false' data-gjs-removable='false' data-gjs-propagate=" . '["removable","editable","draggable"]' . ">
                                <div class='grid_inner_item'>
                                    <div class='lawyer_img'>
                                        <a href='" . route('front.blogdetails', [$blog->slug, $blog->id]) . "'><img src='" . url('assets/front/img/blogs/' . $blog->main_image) . "' class='img-fluid' alt=''></a>
                                    </div>
                                    <div class='lawyer_content'>
                                        <div class='post_meta'>";

                $blogDate = \Carbon\Carbon::parse($blog->created_at)->locale("$lang->code");
                $blogDate = $blogDate->translatedFormat('d M. Y');

                $blogSec .= "<span><i class='far fa-user'></i><a href='#'>" . __('By') . " " . __('Admin') . "</a></span>
                                            <span><i class='far fa-calendar-alt'></i><a href='#'>" . $blogDate . "</a></span>
                                        </div>
                                        <h3 class='post_title'><a href='" . route('front.blogdetails', [$blog->slug, $blog->id]) . "'>" . (convertUtf8(strlen($blog->title)) > 40 ? convertUtf8(substr($blog->title, 0, 40)) . '...' : convertUtf8($blog->title)) . "</a></h3>
                                        <p>" . (convertUtf8(strlen(strip_tags($blog->content)) > 100) ? convertUtf8(substr(strip_tags($blog->content), 0, 100)) . '...' : convertUtf8(strip_tags($blog->content))) . "</p>
                                        <a href='" . route('front.blogdetails', [$blog->slug, $blog->id]) . "' class='btn_link'>" . __('Read More') . "</a>
                                    </div>
                                </div>
                            </div>";
            }
            $blogSec .= "</div>
                </div>
            </section>";


            $ctaSec = "<section class='lawyer_cta cta_v1 bg_image pb-mb30 lazy " . ($rtl == 1 ? 'pb-rtl' : '') . "' data-bg='" . url('assets/front/img/' . $bs->cta_bg) . "' style='background-size:cover; padding: 70px 0px;'>
                <div class='bg_overlay' style='background-color: #" . $be->cta_overlay_color . ";opacity: " . $be->cta_overlay_opacity . ";'></div>
                <div class='container'>
                    <div class='row align-items-center'>
                        <div class='col-lg-7'>
                            <div class='section_title'>
                                <h2 class='text-white'>" . convertUtf8($bs->cta_section_text) . "</h2>
                            </div>
                        </div>
                        <div class='col-lg-5'>
                            <div class='button_box'>
                                <a href='" . $bs->cta_section_button_url . "' class='lawyer_btn'>" . convertUtf8($bs->cta_section_button_text) . "</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>";


            $partnerSec = "<section class='lawyer_partner partner_v1 pb-mb30 " . ($rtl == 1 ? 'pb-rtl' : '') . "' style='padding: 90px 0px;'>
                <div class='container'>
                    <div class='partner_slide'>";
            foreach ($partners as $key => $partner) {
                $partnerSec .= "<div class='single_partner'>
                                <a href='" . $partner->url . "'><img data-src='" . url('assets/front/img/partners/' . $partner->image) . "' class='img-fluid lazy' alt=''></a>
                            </div>";
            }
            $partnerSec .= "</div>
                </div>
            </section>";
        }

        $data['introsec'] = $introsec;
        $data['approachsec'] = $approachsec;
        $data['scatsec'] = $scatsec;
        $data['servicesSec'] = $servicesSec;
        $data['portfoliosSec'] = $portfoliosSec;
        $data['teamSec'] = $teamSec;
        $data['statisticSec'] = $statisticSec;
        $data['faqSec'] = $faqSec;
        $data['testimonialSec'] = $testimonialSec;
        $data['packageSec'] = $packageSec;
        $data['blogSec'] = $blogSec;
        $data['ctaSec'] = $ctaSec;
        $data['partnerSec'] = $partnerSec;


        $data['version'] = $version;

        $components = !empty($data['components']) ? json_decode($data['components'], true) : [];
        $components = str_replace("{base_url}", url('/'), json_encode($components));
        $data['components'] = json_decode($components, true);

        $styles = !empty($data['styles']) ? json_decode($data['styles'], true) : [];
        $styles = str_replace("{base_url}", url('/'), json_encode($styles));
        $data['styles'] = json_decode($styles, true);

        return view('admin.pagebuilder.content', $data);
    }
}
