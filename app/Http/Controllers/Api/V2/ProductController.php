<?php

namespace App\Http\Controllers\Api\V2;

use Cache;
use App\Models\Shop;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\FlashDeal;
use Illuminate\Http\Request;
use App\Utility\SearchUtility;
use App\Utility\CategoryUtility;
use App\Http\Resources\V2\FlashDealCollection;
use App\Http\Resources\V2\ProductMiniCollection;
use App\Http\Resources\V2\Seller\BrandCollection;
use App\Http\Resources\V2\ProductDetailCollection;
use App\Http\Resources\V2\LastViewedProductCollection;

class ProductController extends Controller
{
    public function index()
    {
        return new ProductMiniCollection(Product::latest()->paginate(10));
    }

    public function product_details($slug, $user_id)
    {
        $product = Product::where('slug', $slug)->first(); // استخدم first() بدلاً من get()
    
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404); // إذا لم يتم العثور على المنتج
        }
    
        if (get_setting('last_viewed_product_activation') == 1 && $user_id != null) {
            lastViewedProducts($product->id, $user_id);
        }
    
        return new ProductDetailCollection(collect([$product])); // تحويل المنتج إلى مجموعة وتغليفه في ProductDetailCollection
    }
 
    public function getStocksPrice(Request $request)
    {
        // التحقق من وجود المنتج
        $product = Product::where("slug", $request->slug)->first();
        if (!$product) {
            return response()->json([
                'error' => 'no product'
            ], 404); // إرسال خطأ 404 مع رسالة "no product"
        }
    
        $str = '';
        $tax = 0;
        $quantity = 1;
    
        if ($request->has('quantity') && $request->quantity != null) {
            $quantity = $request->quantity;
        }
    
        if ($request->has('color') && $request->color != null) {
            $str = Color::where('code', '#' . $request->color)->first()->name;
        }
    
        $var_str = str_replace(',', '-', $request->variants);
        $var_str = str_replace(' ', '', $var_str);
    
        if ($var_str != "") {
            $temp_str = $str == "" ? $var_str : '-' . $var_str;
            $str .= $temp_str;
        }
    
        $product_stock = $product->stocks->where('variant', $str)->first();
        if (!$product_stock) {
            return response()->json([
                'error' => 'Stock for this variant not found'
            ], 404); // إرسال خطأ 404 مع رسالة في حال عدم وجود المخزون
        }
    
        $price = $product_stock->price;
    
        if ($product->wholesale_product) {
            $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $quantity)->where('max_qty', '>=', $quantity)->first();
            if ($wholesalePrice) {
                $price = $wholesalePrice->price;
            }
        }
    
        $stock_qty = $product_stock->qty;
        $stock_txt = $product_stock->qty;
        $max_limit = $product_stock->qty;
    
        if ($stock_qty >= 1 && $product->min_qty <= $stock_qty) {
            $in_stock = 1;
        } else {
            $in_stock = 0;
        }
    
        // Product Stock Visibility
        if ($product->stock_visibility_state == 'text') {
            if ($stock_qty >= 1 && $product->min_qty < $stock_qty) {
                $stock_txt = translate('In Stock');
            } else {
                $stock_txt = translate('Out Of Stock');
            }
        }
    
        // discount calculation
        $discount_applicable = false;
    
        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
        ) {
            $discount_applicable = true;
        }
    
        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }
    
        // taxes
        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }
    
        $price += $tax;
    
        return response()->json([
            'result' => true,
            'data' => [
                'price' => single_price($price * $quantity),
                'stock' => $stock_qty,
                'stock_txt' => $stock_txt,
                'digital' => $product->digital,
                'variant' => $str,
                'variation' => $str,
                'max_limit' => $max_limit,
                'in_stock' => $in_stock,
                'image' => $product_stock->image == null ? "" : uploaded_asset($product_stock->image)
            ]
        ]);
    }
    

    public function seller($id, Request $request)
    {
        $shop = Shop::findOrFail($id);
        $seller = Seller::where('user_id', $shop->user_id)->first();
    
        $products = Product::where('added_by', 'seller')
            ->where('user_id', $shop->user_id)
            ->where('published', 1);
    
        if (!empty($request->name)) {
            $products = $products->where('name', 'like', '%' . $request->name . '%');
        }
    
        $products = $products->latest()->paginate(10);
    
        return response()->json([
            'shop' => [
                'id' => $shop->id,
                'user_id' => $shop->user_id,
                'name' => $shop->name,
                'logo' => uploaded_asset($shop->logo),
                'sliders' => $shop->sliders,
                'top_banner' => $shop->top_banner,
                'banner_full_width_1' => $shop->banner_full_width_1,
                'banners_half_width' => $shop->banners_half_width,
                'banner_full_width_2' => $shop->banner_full_width_2,
                'phone' => $shop->phone,
                'address' => $shop->address,
                'rating' => $shop->rating,
                'num_of_reviews' => $shop->num_of_reviews,
                'num_of_sale' => $shop->num_of_sale,
                'seller_package_id' => $shop->seller_package_id,
                'product_upload_limit' => $shop->product_upload_limit,
                'package_invalid_at' => $shop->package_invalid_at,
                'verification_status' => $shop->verification_status,
                'verification_info' => $shop->verification_info,
                'cash_on_delivery_status' => $shop->cash_on_delivery_status,
                'admin_to_pay' => $shop->admin_to_pay,
                'facebook' => $shop->facebook,
                'instagram' => $shop->instagram,
                'google' => $shop->google,
                'twitter' => $shop->twitter,
                'youtube' => $shop->youtube,
                'slug' => $shop->slug,
                'meta_title' => $shop->meta_title,
                'meta_description' => $shop->meta_description,
                'pick_up_point_id' => $shop->pick_up_point_id,
                'shipping_cost' => $shop->shipping_cost,
                'delivery_pickup_latitude' => $shop->delivery_pickup_latitude,
                'delivery_pickup_longitude' => $shop->delivery_pickup_longitude,
                'bank_name' => $shop->bank_name,
                'bank_acc_name' => $shop->bank_acc_name,
                'bank_acc_no' => $shop->bank_acc_no,
                'bank_routing_no' => $shop->bank_routing_no,
                'bank_payment_status' => $shop->bank_payment_status,
                'created_at' => $shop->created_at,
                'updated_at' => $shop->updated_at,
            ],
            'seller' => [
                'id' => $seller->id,
                'user_id' => $seller->user_id,
                'rating' => $seller->rating,
                'num_of_reviews' => $seller->num_of_reviews,
                'num_of_sale' => $seller->num_of_sale,
                'verification_status' => $seller->verification_status,
                'verification_info' => $seller->verification_info,
                'cash_on_delivery_status' => $seller->cash_on_delivery_status,
                'admin_to_pay' => $seller->admin_to_pay,
                'bank_name' => $seller->bank_name,
                'bank_acc_name' => $seller->bank_acc_name,
                'bank_acc_no' => $seller->bank_acc_no,
                'bank_routing_no' => $seller->bank_routing_no,
                'bank_payment_status' => $seller->bank_payment_status,
                'created_at' => $seller->created_at,
                'updated_at' => $seller->updated_at,
            ],
            'products' => new ProductMiniCollection($products),
            'message' => 'Success',
            'status' => 200,
        ]);
    }
    
    

    public function categoryProducts($id, Request $request)
    {
        $category = Category::with('childrenCategories')->find($id);
    
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
    
        $products = $category->products();
    
        if ($request->name) {
            $products = $products->where('name', 'like', '%' . $request->name . '%');
        }
    
        return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    }
    

    public function brand($slug, Request $request)
    {
        $brand = Brand::where('slug', $slug)->first();
        $products = Product::where('brand_id', $brand->id)->physical();
        if ($request->name != "" || $request->name != null) {
            $products = $products->where('name', 'like', '%' . $request->name . '%');
        }
        return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    }

    public function getBrands()
    {
        $brands = Brand::all();

        return BrandCollection::collection($brands);
    }

    public function todaysDeal()
    {
        $products = Product::where('todays_deal', 1)->physical();
        return new ProductMiniCollection(filter_products($products)->limit(20)->latest()->get());
    }

    public function flashDeal()
    {
        return Cache::remember('app.flash_deals', 86400, function () {
            $flash_deals = FlashDeal::where('status', 1)->where('featured', 1)->where('start_date', '<=', strtotime(date('d-m-Y')))->where('end_date', '>=', strtotime(date('d-m-Y')))->get();
            return new FlashDealCollection($flash_deals);
        });
    }

    public function featured()
    {
        $products = Product::where('featured', 1)->physical();
        $paginatedProducts = filter_products($products)->latest()->paginate(5);
    
        if ($paginatedProducts->isEmpty()) {
            return response()->json([
                'message' => 'No Content',
            ], 204);
        }
    
        return new ProductMiniCollection($paginatedProducts);
    }
    

    public function featuredHome()
    {
        $products = Product::where('featured', 1)->get();
        return response()->json($products);
    }

    // public function AllProduct()
    // {
    //     $products = Product::all();
    //     return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    // }
    public function inhouse()
    {
        $products = Product::where('added_by', 'admin');
        $paginatedProducts = filter_products($products)->latest()->paginate(10);
    
        if ($paginatedProducts->isEmpty()) {
            return response()->json([
                'message' => 'No Content',
            ], 204);
        }
    
        return new ProductMiniCollection($paginatedProducts);
    }
    

    public function digital()
    {
        $products = Product::digital();
        return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    }

    public function bestSeller()
    {
        $products = Product::orderBy('num_of_sale', 'desc')->physical();
        return new ProductMiniCollection(filter_products($products)->limit(20)->get());
    }

    public function frequentlyBought($slug)
    {
        $product = Product::where("slug", $slug)->first();
        $products = get_frequently_bought_products($product);
        return new ProductMiniCollection($products);
    }

    public function topFromSeller($slug)
    {
        $product = Product::where("slug", $slug)->first();
        $products = Product::where('user_id', $product->user_id)->orderBy('num_of_sale', 'desc')->physical();
        return new ProductMiniCollection(filter_products($products)->limit(10)->get());
    }


    public function search(Request $request)
    {
        $category_ids = [];
        $brand_ids = [];

        if ($request->categories != null && $request->categories != "") {
            $category_ids = explode(',', $request->categories);
        }

        if ($request->brands != null && $request->brands != "") {
            $brand_ids = explode(',', $request->brands);
        }

        $sort_by = $request->sort_key;
        $name = $request->name;
        $min = $request->min;
        $max = $request->max;


        $products = Product::query();

        $products->where('published', 1)->physical();

        if (!empty($brand_ids)) {
            $products->whereIn('brand_id', $brand_ids);
        }

        if (!empty($category_ids)) {
            $n_cid = [];
            foreach ($category_ids as $cid) {
                $n_cid = array_merge($n_cid, CategoryUtility::children_ids($cid));
            }

            if (!empty($n_cid)) {
                $category_ids = array_merge($category_ids, $n_cid);
            }

            $products->whereIn('category_id', $category_ids);
        }

        if ($name != null && $name != "") {
            $products->where(function ($query) use ($name) {
                foreach (explode(' ', trim($name)) as $word) {
                    $query->where('name', 'like', '%' . $word . '%')->orWhere('tags', 'like', '%' . $word . '%')->orWhereHas('product_translations', function ($query) use ($word) {
                        $query->where('name', 'like', '%' . $word . '%');
                    });
                }
            });
            SearchUtility::store($name);
            $case1 = $name . '%';
            $case2 = '%' . $name . '%';

            $products->orderByRaw("CASE 
                WHEN name LIKE '$case1' THEN 1 
                WHEN name LIKE '$case2' THEN 2 
                ELSE 3 
                END");
        }

        if ($min != null && $min != "" && is_numeric($min)) {
            $products->where('unit_price', '>=', $min);
        }

        if ($max != null && $max != "" && is_numeric($max)) {
            $products->where('unit_price', '<=', $max);
        }



        switch ($sort_by) {
            case 'price_low_to_high':
                $products->orderBy('unit_price', 'asc');
                break;

            case 'price_high_to_low':
                $products->orderBy('unit_price', 'desc');
                break;

            case 'new_arrival':
                $products->orderBy('created_at', 'desc');
                break;

            case 'popularity':
                $products->orderBy('num_of_sale', 'desc');
                break;

            case 'top_rated':
                $products->orderBy('rating', 'desc');
                break;

            default:
                $products->orderBy('created_at', 'desc');
                break;
        }

        return new ProductMiniCollection(filter_products($products)->paginate(10));
    }

    public function variantPrice(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $str = '';
        $tax = 0;

        if ($request->has('color') && $request->color != "") {
            $str = Color::where('code', '#' . $request->color)->first()->name;
        }

        $var_str = str_replace(',', '-', $request->variants);
        $var_str = str_replace(' ', '', $var_str);

        if ($var_str != "") {
            $temp_str = $str == "" ? $var_str : '-' . $var_str;
            $str .= $temp_str;
        }
        return   $this->calc($product, $str, $request, $tax);
    }

    public function lastViewedProducts()
    {
        $lastViewedProducts = getLastViewedProducts();
        return new LastViewedProductCollection($lastViewedProducts);
    }
    public function todaysDealDetails()
    {
        // جلب صورة البانر الكبيرة
        $getMainImage = get_setting('todays_deal_banner');
        $mainImage = $getMainImage != null ? json_decode($getMainImage, true) : null;
        $mainImage = $mainImage != null ? uploaded_asset($mainImage) : null;
    
        // جلب صورة البانر الصغيرة
        $getSmallImage = get_setting('todays_deal_banner_small');
        $smallImage = $getSmallImage != null ? json_decode($getSmallImage, true) : null;
        $smallImage = $smallImage != null ? uploaded_asset($smallImage) : null;
    
        // جلب لون الخلفية
        $bgColor = get_setting('todays_deal_bg_color');
        $bgColor = $bgColor != null ? $bgColor : null;
    
        // جلب لون النص
        $textColor = get_setting('todays_deal_banner_text_color');
        $textColor = $textColor != null ? $textColor : null;
    
        // جلب المنتجات
        $productsQuery = Product::where('todays_deal', 1)->physical();
        $products = new ProductMiniCollection(filter_products($productsQuery)->limit(20)->latest()->get());
    
        // تحضير بيانات الـ response
        $data = [
            'large_image' => $mainImage,
            'small_image' => $smallImage,
            'bg_color' => $bgColor,
            'text_color' => $textColor,
            'products' => $products,
            'message' => 'Success',
            'status' => 200,
        ];
    
        // إرجاع البيانات كـ JSON
        return response()->json($data);
    }
    

    public function subCategory($id, Request $request)
{
    $subCategory = Category::with('childrenCategories')->find($id);

    if (!$subCategory) {
        return response()->json(['message' => 'Sub-category not found'], 404);
    }

    // Assuming that sub-categories are also linked to products
    $products = $subCategory->products();

    if ($request->name) {
        $products = $products->where('name', 'like', '%' . $request->name . '%');
    }

    return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
}
public function subSubCategory($id, Request $request)
{
    $subSubCategory = Category::with('childrenCategories')->find($id);

    if (!$subSubCategory) {
        return response()->json(['message' => 'Sub-sub-category not found'], 404);
    }

    $products = $subSubCategory->products();

    if ($request->name) {
        $products = $products->where('name', 'like', '%' . $request->name . '%');
    }

    return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
}
public function showProduct($id)
{
    $product = Product::find($id); // العثور على المنتج باستخدام الـ ID

    if (!$product) {
        return response()->json([
            'message' => translate('Product not found'),
            'success' => false
        ], 404);
    }

    return new ProductDetailCollection([$product]);
}



}
