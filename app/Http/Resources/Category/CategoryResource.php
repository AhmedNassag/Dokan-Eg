<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'is_active' => $this->is_active,
            'created_by' => $this->created_by,
            'parent' => $this->whenLoaded('parent', function () {
                return ['id' => $this->parent->id, 'name' => $this->parent->name];
            }),
        ];
    }
}
