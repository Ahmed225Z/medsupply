<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\CouponCollection;
use App\Http\Resources\V2\ProductMiniCollection;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function info()
    {
        // Get the Footer logo
        $getlogo = get_setting('footer_logo');
        $logo = $getlogo != null ? json_decode($getlogo, true) : null;
        $logo = $logo != null ? uploaded_asset($logo) : null;

        // Get the about_us_description
        $About_us_description = get_setting('about_us_description');
        $About_us = $About_us_description != null ? $About_us_description : null;

        // Get the play_store_link
        $play_store_link = get_setting('play_store_link');
        $play_store = $play_store_link != null ? $play_store_link : null;

        // Get the app_store_link
        $app_store_link = get_setting('app_store_link');
        $app_store = $app_store_link != null ? $app_store_link : null;

        // Get the contact_address
        $contact_address = get_setting('contact_address');
        $sub_contact_address = $contact_address != null ? $contact_address : null;

        // Get the contact_phone
        $contact_phone = get_setting('contact_phone');
        $sub_contact_phone = $contact_phone != null ? $contact_phone : null;

        // Get the contact_email
        $contact_email = get_setting('contact_email');
        $sub_contact_email = $contact_email != null ? $contact_email : null;

        // Get social links visibility setting
        $show_social_links = get_setting('show_social_links');

        // Get the social links
        $facebook_link = get_setting('facebook_link');
        $sub_facebook_link = $facebook_link != null ? $facebook_link : null;

        $twitter_link = get_setting('twitter_link');
        $sub_twitter_link = $twitter_link != null ? $twitter_link : null;

        $instagram_link = get_setting('instagram_link');
        $sub_instagram_link = $instagram_link != null ? $instagram_link : null;

        $youtube_link = get_setting('youtube_link');
        $sub_youtube_link = $youtube_link != null ? $youtube_link : null;

        $linkedin_link = get_setting('linkedin_link');
        $sub_linkedin_link = $linkedin_link != null ? $linkedin_link : null;

        // Get widget one labels and links
        $get_widget_one_labels = get_setting('widget_one_labels', null, request()->header('App-Language'));
        $widget_one_labels = $get_widget_one_labels != null ? json_decode($get_widget_one_labels, true) : [];

        $get_widget_one_links = get_setting('widget_one_links', null, request()->header('App-Language'));
        $widget_one_links = ($get_widget_one_labels != null && $get_widget_one_links != null) ? json_decode($get_widget_one_links, true) : [];

        $widget_one = [];
        for ($i = 0; $i < count($widget_one_labels); $i++) {
            $widget_one[$i] = ['link' => $widget_one_links[$i] ?? '', 'labels' => $widget_one_labels[$i] ?? ''];
        }

        $widget_one_title = get_setting('widget_one');
        $title_widget_one = $widget_one_title != null ? $widget_one_title : null;

        $frontend_copyright_text = get_setting('frontend_copyright_text');
        $s_frontend_copyright_text = $frontend_copyright_text != null ? $frontend_copyright_text : null;


        $payment_method_images = get_setting('payment_method_images');
        $payment_method_images_logo = $payment_method_images != null ? json_decode($payment_method_images, true) : null;
        $payment_method_images_logo = $payment_method_images_logo != null ? uploaded_asset($payment_method_images_logo) : null;

        // Prepare the response data
        $data = [
            'About Widget' => [
                'footer_logo' => $logo,
                'description' => $About_us,
                'app_store_link' => $app_store,
                'play_store_link' => $play_store,
            ]
        ];

        // Add social links if show_social_links is 'on'
        if ($show_social_links === 'on') {
            $data['Social links'] = [
                'facebook_link' => $sub_facebook_link,
                'twitter_link' => $sub_twitter_link,
                'instagram_link' => $sub_instagram_link,
                'youtube_link' => $sub_youtube_link,
                'linkedin_link' => $sub_linkedin_link,
            ];
        }

        // Add widget one data
        $data['widget_one'] = [
            'Title' => $title_widget_one,
            'Content' => $widget_one,
        ];

        $data['contacts'] = [
            'address' => $sub_contact_address,
            'phone' => $sub_contact_phone,
            'email' => $sub_contact_email,
        ];

        $data['Copyright Widget'] = $s_frontend_copyright_text;

        $data['Payment Method Images Logo'] = $payment_method_images_logo;

        // Return the data as JSON
        return response()->json($data);
    }
}
