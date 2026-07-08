<?php

namespace App\Http\Resources\Unit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'code' => $this->code,
            'short_name' => $this->short_name,
            'base_unit' => $this->base_unit,
            'operator' => $this->operator,
            'operator_value' => $this->operator_value,
            'base_unit_info' => $this->whenLoaded('baseUnit', function () {
                return [
                    'id' => $this->baseUnit->id,
                    'name' => $this->baseUnit->name,
                    'code' => $this->baseUnit->code,
                    'short_name' => $this->baseUnit->short_name,
                ];
            }),
        ];
    }
}
