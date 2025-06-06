<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\ProductCollection;
use App\Models\FlashDeal;
use App\Models\Product;
use GrahamCampbell\ResultType\Success;

class FlashDealCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'slug' => $data->slug,
                    'title' => $data->title,
                    'date' => (int) $data->end_date,
                    'banner' => uploaded_asset($data->banner),
                    'products' => new FlashDealProductCollection($data->flash_deal_products()->get())
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'message' => 'Success',
            'status' => 200
        ];
    }
}
