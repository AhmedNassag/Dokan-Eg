<?php

namespace App\Http\Resources\Translation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TranslationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'language_id' => $this->language_id,
            'group' => $this->group,
            'key' => $this->key,
            'value' => $this->value,
            'language' => $this->whenLoaded('language', function () {
                return [
                    'id' => $this->language->id,
                    'name' => $this->language->name,
                    'code' => $this->language->code,
                ];
            }),
        ];
    }
}
