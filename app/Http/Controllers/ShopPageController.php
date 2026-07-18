<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;

class ShopPageController extends Controller
{
    public function show(string $slug)
    {
        $shop = Shop::withoutGlobalScopes()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $theme = $shop->theme->value;

        $sections = $shop->sections()->ordered()->get();

        $staticProducts = [
            [
                'id'    => 1,
                'name'  => 'Premium Wireless Headphones',
                'price' => 129.99,
                'image' => 'https://picsum.photos/seed/prod1/400/400',
                'badge' => 'New',
            ],
            [
                'id'    => 2,
                'name'  => 'Smart Watch Pro',
                'price' => 249.99,
                'image' => 'https://picsum.photos/seed/prod2/400/400',
                'badge' => '-20%',
            ],
            [
                'id'    => 3,
                'name'  => 'Leather Crossbody Bag',
                'price' => 89.50,
                'image' => 'https://picsum.photos/seed/prod3/400/400',
                'badge' => null,
            ],
            [
                'id'    => 4,
                'name'  => 'Running Shoes Ultra',
                'price' => 159.00,
                'image' => 'https://picsum.photos/seed/prod4/400/400',
                'badge' => 'Best Seller',
            ],
            [
                'id'    => 5,
                'name'  => 'Minimalist Desk Lamp',
                'price' => 64.99,
                'image' => 'https://picsum.photos/seed/prod5/400/400',
                'badge' => null,
            ],
            [
                'id'    => 6,
                'name'  => 'Ceramic Coffee Set',
                'price' => 45.00,
                'image' => 'https://picsum.photos/seed/prod6/400/400',
                'badge' => 'Sale',
            ],
        ];

        return view("shop.themes.{$theme}", compact('shop', 'staticProducts', 'sections'));
    }
}
