<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\FlashDealCollection;
use App\Http\Resources\V2\ProductCollection;
use App\Http\Resources\V2\ProductMiniCollection;
use App\Models\FlashDeal;
use App\Models\Product;

class FlashDealController extends Controller
{
    public function index()
    {
        $flash_deals = FlashDeal::where('status', 1)
            ->where('start_date', '<=', strtotime(date('d-m-Y')))
            ->where('end_date', '>=', strtotime(date('d-m-Y')))
            ->get();

        return new FlashDealCollection($flash_deals);
    }
    public function info($slug)
    {
        $flash_deals = FlashDeal::where('slug', $slug)->where('status', 1)
            ->where('start_date', '<=', strtotime(date('d-m-Y')))
            ->where('end_date', '>=', strtotime(date('d-m-Y')))
            ->get();

        return new FlashDealCollection($flash_deals);
    }

    public function products($id)
{
    $flash_deal = FlashDeal::find($id); 

    if (!$flash_deal) {
        return response()->json([
            'message' => 'Flash Deal not found'
        ], 404);
    }

    $products = collect();
    foreach ($flash_deal->flash_deal_products as $flash_deal_product) {
        $product = Product::find($flash_deal_product->product_id);
        if ($product) {
            $products->push($product);
        }
    }
    return new ProductMiniCollection($products);
}

    
    public function flashdeal()
    {
        $bgColor = get_setting('flash_deal_bg_color');
        $bgColor = $bgColor != null ? $bgColor : null;

        // Get the banner text color
        $textColor = get_setting('flash_deal_banner_menu_text');
        $textColor = $textColor != null ? $textColor : null;

        $flash_deals = FlashDeal::where('status', 1)->where('featured', 1)
            ->where('start_date', '<=', strtotime(date('d-m-Y')))
            ->where('end_date', '>=', strtotime(date('d-m-Y')))
            ->get();

        $flash_deals_return = new FlashDealCollection($flash_deals);

        $data = [
            'bg_color' => $bgColor,
            'text_color' => $textColor,
            'Flash_deal_details' => $flash_deals_return,
        ];

        return response()->json($data);
    }
}
