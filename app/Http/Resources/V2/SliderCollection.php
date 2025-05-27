<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SliderCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'image' => $this->collection->map(function ($data) {
                return uploaded_asset($data['image']);
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
