<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\SliderCollection;
use Cache;

class SliderController extends Controller
{
    public function sliders()
    {
        $get_images = get_setting('home_slider_images', null, request()->header('App-Language'));
        $images = $get_images != null ? json_decode($get_images, true) : [];
    
        $get_links = get_setting('home_slider_links', null, request()->header('App-Language'));
        $links = ($get_images != null && $get_links != null) ? json_decode($get_links, true) : [];
    
        if (empty($images) || empty($links)) {
            return response()->json([
                'message' => 'No Content',
            ], 204);
        }
    
        // إنشاء مصفوفة اللافتات
        $sliders = [];
        for ($i = 0; $i < count($images); $i++) {
            $sliders[$i] = ['link' => $links[$i], 'image' => $images[$i]];
        }
    
        // إرجاع مجموعة اللافتات
        return new SliderCollection($sliders);
    }
    
    

    public function bannerOne()
    {
        $getImages = get_setting('home_banner1_images', null, request()->header('App-Language'));
        $images = $getImages != null ? json_decode($getImages, true) : [];
    
        $getLinks = get_setting('home_banner1_links', null, request()->header('App-Language'));
        $links = ($getImages != null && $getLinks != null) ? json_decode($getLinks, true) : [];
    
        if (empty($images) || empty($links)) {
            return response()->json([
                'message' => 'No Content',
            ], 204);
        }
    
        $banners = [];
        for ($i = 0; $i < count($images); $i++) {
            $banners[$i] = ['link' => $links[$i], 'image' => $images[$i]];
        }
    
        return new SliderCollection($banners);
    }
    

    public function bannerTwo()
    {
        $getImages = get_setting('home_banner2_images', null, request()->header('App-Language'));
        $images = $getImages != null ? json_decode($getImages, true) : [];
    
        $getLinks = get_setting('home_banner2_links', null, request()->header('App-Language'));
        $links = ($getImages != null && $getLinks != null) ? json_decode($getLinks, true) : [];
    
        if (empty($images) || empty($links)) {
            return response()->json([
                'message' => 'No Content',
            ], 204);
        }
    
        $banners = [];
        for ($i = 0; $i < count($images); $i++) {
            $banners[$i] = ['link' => $links[$i], 'image' => $images[$i]];
        }
    
        return new SliderCollection($banners);
    }
    

    public function bannerThree()
    {
        $getImages = get_setting('home_banner3_images', null, request()->header('App-Language'));
        $images = $getImages != null ? json_decode($getImages, true) : [];
    
        $getLinks = get_setting('home_banner3_links', null, request()->header('App-Language'));
        $links = ($getImages != null && $getLinks != null) ? json_decode($getLinks, true) : [];
    
        if (empty($images) || empty($links)) {
            return response()->json([
                'message' => 'No Content',
            ], 204);
        }
    
        $banners = [];
        for ($i = 0; $i < count($images); $i++) {
            $banners[$i] = ['link' => $links[$i], 'image' => $images[$i]];
        }
    
        return new SliderCollection($banners);
    }
    
    public function headerLogo()
    {
        $getImages = get_setting('header_logo', null, request()->header('App-Language'));
        $image = $getImages != null ? json_decode($getImages, true) : null;
        if ($image != null) {
            $image = uploaded_asset($image);
        }
        return response()->json($image);
    }


    public function headerMenu()
    {
        $getImages = get_setting('header_menu_images', null, request()->header('App-Language'));
        $images = $getImages != null ? json_decode($getImages, true) : [];
        $getLinks = get_setting('header_menu_links', null, request()->header('App-Language'));
        $links = ($getImages != null && $getLinks != null) ? json_decode($getLinks, true) : [];
        $getLabels = get_setting('header_menu_labels', null, request()->header('App-Language'));
        $labels = ($getImages != null && $getLabels != null) ? json_decode($getLabels, true) : [];

        $header_menu = [];
        for ($i = 0; $i < count($images); $i++) {
            $header_menu[] = [
                'image' => uploaded_asset($images[$i] ?? ''),
                'label' => $labels[$i] ?? '',
                'link' => $links[$i] ?? ''
            ];
        }

        return response()->json($header_menu);
    }
}
