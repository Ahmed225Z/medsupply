<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\BrandCollection;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Utility\SearchUtility;
use Cache;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brand_query = Brand::query();
        if ($request->name != "" || $request->name != null) {
            $brand_query->where('name', 'like', '%' . $request->name . '%');
            SearchUtility::store($request->name);
        }
        return new BrandCollection($brand_query->paginate(10));
    }

    public function top()
    {
        return Cache::remember('app.top_brands', 86400, function () {
            return new BrandCollection(Brand::where('top', 1)->get());
        });
    }
    public function topHome()
    {
        $gettopBrand = get_setting('top_brands');
        $topBrand = $gettopBrand != null ? json_decode($gettopBrand, true) : [];
        $topBrand_b = Brand::whereIn('id', $topBrand)->get();

        // استخدام BrandCollection لإرجاع البيانات بنفس التنسيق
        return new BrandCollection($topBrand_b);
    }
}
