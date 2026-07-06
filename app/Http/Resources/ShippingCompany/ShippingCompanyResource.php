<?php

namespace App\Http\Resources\ShippingCompany;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingCompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'phone' => $this->phone,
            'status' => $this->status,
            'prices' => $this->whenLoaded('prices', function () {
                return $this->prices->map(function ($price) {
                    return [
                        'id' => $price->id,
                        'city_id' => $price->city_id,
                        'price' => $price->price,
                        'city' => $price->city ? [
                            'id' => $price->city->id,
                            'name' => $price->city->name,
                        ] : null,
                    ];
                });
            }),
        ];
    }
}
