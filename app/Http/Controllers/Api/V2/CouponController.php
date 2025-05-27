<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\CouponCollection;
use App\Http\Resources\V2\ProductMiniCollection;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->first();

        if ($coupon != null && strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date && CouponUsage::where('user_id', auth()->user()->id)->where('coupon_id', $coupon->id)->first() == null) {
            $couponDetails = json_decode($coupon->details);
            if ($coupon->type == 'cart_base') {
                $sum = Cart::where('user_id', auth()->user()->id)->active()->sum('price');
                if ($sum > $couponDetails->min_buy) {
                    if ($coupon->discount_type == 'percent') {
                        $couponDiscount =  ($sum * $coupon->discount) / 100;
                        if ($couponDiscount > $couponDetails->max_discount) {
                            $couponDiscount = $couponDetails->max_discount;
                        }
                    } elseif ($coupon->discount_type == 'amount') {
                        $couponDiscount = $coupon->discount;
                    }
                    if ($this->isCouponAlreadyApplied(auth()->user()->id, $coupon->id)) {
                        return response()->json([
                            'success' => false,
                            'message' => translate('The coupon is already applied. Please try another coupon')
                        ]);
                    } else {
                        return response()->json([
                            'success' => true,
                            'discount' => (float) $couponDiscount
                        ]);
                    }
                }
            } elseif ($coupon->type == 'product_base') {
                $couponDiscount = 0;
                $cartItems = Cart::where('user_id', auth()->user()->id)->active()->get();
                foreach ($cartItems as $key => $cartItem) {
                    foreach ($couponDetails as $key => $couponDetail) {
                        if ($couponDetail->product_id == $cartItem->product_id) {
                            if ($coupon->discount_type == 'percent') {
                                $couponDiscount += $cartItem->price * $coupon->discount / 100;
                            } elseif ($coupon->discount_type == 'amount') {
                                $couponDiscount += $coupon->discount;
                            }
                        }
                    }
                }
                if ($this->isCouponAlreadyApplied(auth()->user()->id, $coupon->id)) {
                    return response()->json([
                        'success' => false,
                        'message' => translate('The coupon is already applied. Please try another coupon')
                    ]);
                } else {
                    return response()->json([
                        'success' => true,
                        'discount' => (float) $couponDiscount,
                        'message' => translate('Coupon code applied successfully')
                    ]);
                }
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => translate('The coupon is invalid')
            ]);
        }
    }

    protected function isCouponAlreadyApplied($userId, $couponId)
    {
        return CouponUsage::where(['user_id' => $userId, 'coupon_id' => $couponId])->count() > 0;
    }


    public function couponList()
    {
        $coupons = Coupon::where('start_date', '<=', strtotime(date('d-m-Y')))->where('end_date', '>=', strtotime(date('d-m-Y')))->paginate(10);
        return new CouponCollection($coupons);
    }

    public function getCouponProducts($id)
    {
        $coupon = Coupon::where('id', $id)->first();
        if ($coupon->type == 'product_base') {
            $products = json_decode($coupon->details);
            $coupon_products = [];
            foreach ($products as $product) {
                array_push($coupon_products, $product->product_id);
            }
            $products = get_multiple_products($coupon_products);
            return new ProductMiniCollection($products);
        }
        return $this->failed(translate('Something went wrong'));
    }
    public function couponDetails()
    {
        // Get the main banner image
        $getMainImage = get_setting('coupon_background_image');
        $mainImage = $getMainImage != null ? json_decode($getMainImage, true) : null;
        $mainImage = $mainImage != null ? uploaded_asset($mainImage) : null;

        // Get the background color
        $bgColor = get_setting('cupon_background_color');
        $bgColor = $bgColor != null ? $bgColor : null;

        // Get the cupon_title
        $textColor = get_setting('cupon_title');
        $title = $textColor != null ? $textColor : null;

        // Get the cupon_subtitle
        $sub_title_copon = get_setting('cupon_subtitle');
        $sub_title = $sub_title_copon != null ? $sub_title_copon : null;


        // Prepare the response data
        $data = [
            'image' => $mainImage,
            'bg_color' => $bgColor,
            'Title' => $title,
            'sub_title' => $sub_title,
        ];

        // Return the data as JSON
        return response()->json($data);
    }
    public function couponListWithProducts()
{
    $coupons = Coupon::where('start_date', '<=', strtotime(date('d-m-Y')))
        ->where('end_date', '>=', strtotime(date('d-m-Y')))
        ->get();

    $couponDetails = $coupons->map(function ($coupon) {
        $products = [];
        
        if ($coupon->type == 'product_base') {
            $couponProducts = json_decode($coupon->details);
            $productIds = array_map(function($product) {
                return $product->product_id;
            }, $couponProducts);
            $products = get_multiple_products($productIds);
        }
        
        return [
            'coupon' => $coupon,
            'products' => new ProductMiniCollection($products)
        ];
    });

    return response()->json([
        'data' => $couponDetails,
        'message' => 'Success',
        'status' => 200,
    ]);
}

}
