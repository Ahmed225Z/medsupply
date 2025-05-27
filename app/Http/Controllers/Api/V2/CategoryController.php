<?php

namespace App\Http\Controllers\Api\V2;

use Cache;
use App\Models\Category;
use App\Models\BusinessSetting;
use App\Utility\CategoryUtility;
use App\Http\Resources\V2\CategoryCollection;
use App\Http\Resources\V2\ProductMiniCollection;

class CategoryController extends Controller
{

    public function index($parent_id = 0)
    {
        if (request()->has('parent_id') && request()->parent_id) {
            $category = Category::where('slug', request()->parent_id)->first();
            $parent_id = $category->id;
        }

        // return Cache::remember("app.categories-$parent_id", 86400, function () use ($parent_id) {
        return new CategoryCollection(Category::where('parent_id', $parent_id)->whereDigital(0)->get());
        // });
    }

    public function info($slug)
    {
        return new CategoryCollection(Category::where('slug', $slug)->get());
    }

    public function featured()
    {
        $categories = Category::where('featured', 1)->latest()->paginate(10);

        if ($categories->isEmpty()) {
            return response()->json([
                'message' => 'No Content',
            ], 204);
        }

        $data = $categories->map(function ($data) {
            return [
                'id' => $data->id,
                'slug' => $data->slug,
                'name' => $data->getTranslation('name'),
                'banner' => uploaded_asset($data->banner),
                'icon' => uploaded_asset($data->icon),
                'number_of_children' => CategoryUtility::get_immediate_children_count($data->id),
                'sub_categories' => $data->categories->map(function ($subCategory) {
                    return [
                        'id' => $subCategory->id,
                        'name' => $subCategory->getTranslation('name'),
                    ];
                })->toArray(),
                'links' => [
                    'products' => route('api.products.categories', $data->id),
                    'sub_categories' => route('subCategories.index', $data->id)
                ]
            ];
        });

        return response()->json([
            'data' => $data,
            'message' => 'Success',
            'status' => 200,
        ]);
    }




    public function level()
    {
        return new CategoryCollection(Category::where('level', 0)->get());
    }

    public function home()
    {
        // استرجاع الفئات مع المنتجات الخاصة بها
        $categories = Category::whereIn('id', json_decode(get_setting('home_categories')))
            ->with(['main_products' => function ($query) {
                // تحديد حد أقصى لعدد المنتجات المسترجعة لكل فئة
                $query->limit(5);
            }])
            ->get();

        // استخدام ProductMiniCollection لتحويل المنتجات الخاصة بكل فئة
        $categoriesWithProducts = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'slug' => $category->slug,
                'name' => $category->name,
                'banner' => uploaded_asset($category->banner),
                'icon' => $category->icon,
                'number_of_children' => $category->number_of_children,
                'products' => new ProductMiniCollection($category->products), // استخدام ProductMiniCollection
            ];
        });

        return response()->json([
            'data' => $categoriesWithProducts,
            'message' => 'Success',
            'status' => 200
        ]);
    }




    public function top()
    {
        // return Cache::remember('app.top_categories', 86400, function () {
        return new CategoryCollection(Category::whereIn('id', json_decode(get_setting('home_categories')))->limit(20)->get());
        // });
    }
    public function featuredHome()
    {
        // جلب الفئات المميزة (featured categories) مع المنتجات التابعة
        $featuredCategories = Category::where('featured', 1)
            ->with(['products' => function ($query) {
                $query->take(5); // تحديد عدد المنتجات المعروضة إلى 5 فقط
            }])
            ->get();

        // تحقق من وجود فئات مميزة
        if ($featuredCategories->isEmpty()) {
            return response()->json([
                'message' => 'No Content',
            ], 204);
        }

        // تحضير النتيجة لعرضها في الـ response
        $result = $featuredCategories->map(function ($category) {
            return [
                'id' => $category->id,
                'slug' => $category->slug,
                'name' => $category->getTranslation('name'),
                'banner' => uploaded_asset($category->banner),
                'icon' => uploaded_asset($category->icon),
                'number_of_children' => $category->childrenCategories->count(),
                'sub_categories' => [], // يمكن تعديل هذا الجزء لإضافة التصنيفات الفرعية إذا أردت
                'products' => $category->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'slug' => $product->slug,
                        'name' => $product->getTranslation('name'),
                        'thumbnail_image' => uploaded_asset($product->thumbnail_img),
                        'has_discount' => home_base_price($product, false) != home_discounted_base_price($product, false),
                        'discount' => "-" . discount_in_percentage($product) . "%",
                        'stroked_price' => home_base_price($product),
                        'main_price' => home_discounted_base_price($product),
                        'rating' => (float) $product->rating,
                        'sales' => (int) $product->num_of_sale,
                        'is_wholesale' => $product->wholesale_product == 1,
                        'links' => [
                            'details' => route('products.show', $product->id),
                        ]
                    ];
                }),
                'links' => [
                    'products' => route('api.products.category', $category->id),
                    'sub_categories' => route('subCategories.index', $category->id),
                ]
            ];
        });

        return response()->json([
            'data' => $result,
            'message' => 'Success',
            'status' => 200,
        ]);
    }
    public function allCategories()
{
    // استرجاع الفئات الرئيسية مع الفئات الفرعية والفئات الفرعية التابعة لها
    $categories = Category::where('parent_id', 0)
        ->with(['childrenCategories.childrenCategories']) // لاسترجاع الفئات الفرعية والفئات الفرعية التابعة
        ->get();

    // تحضير النتيجة لعرضها في الـ response
    $result = $categories->map(function ($category) {
        return [
            'id' => $category->id,
            'slug' => $category->slug,
            'name' => $category->getTranslation('name'),
            'banner' => uploaded_asset($category->banner),
            'icon' => uploaded_asset($category->icon),
            'number_of_children' => $category->childrenCategories->count(),
            'sub_categories' => $category->childrenCategories->map(function ($childCategory) {
                return [
                    'id' => $childCategory->id,
                    'name' => $childCategory->getTranslation('name'),
                    'sub_sub_categories' => $childCategory->childrenCategories->map(function ($secondLevelCategory) {
                        return [
                            'id' => $secondLevelCategory->id,
                            'name' => $secondLevelCategory->getTranslation('name'),
                        ];
                    }),
                ];
            }),
        ];
    });

    return response()->json([
        'data' => $result,
        'message' => 'Success',
        'status' => 200,
    ]);
}

}
