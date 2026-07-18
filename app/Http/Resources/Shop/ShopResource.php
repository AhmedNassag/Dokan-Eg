<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'user_id'          => $this->user_id,
            'name'             => $this->name,
            'slug'             => $this->slug,
            'description'      => $this->description,
            'logo'             => $this->logo ? asset($this->logo) : null,
            'cover'            => $this->cover ? asset($this->cover) : null,
            'theme'            => $this->theme,
            'primary_color'    => $this->primary_color,
            'secondary_color'  => $this->secondary_color,
            'font_family'      => $this->font_family,
            'phone'            => $this->phone,
            'email'            => $this->email,
            'website'          => $this->website,
            'social_links'     => $this->social_links,
            'meta_title'       => $this->meta_title,
            'meta_description' => $this->meta_description,
            'is_active'        => (bool) $this->is_active,
            'is_featured'      => (bool) $this->is_featured,
            'published_at'     => $this->published_at,
            'user'             => $this->whenLoaded('user', function () {
                return [
                    'id'    => $this->user->id,
                    'name'  => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),
        ];
    }
}
