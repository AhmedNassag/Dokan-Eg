<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingCompanyPrice extends Model
{
    protected $table = 'shipping_company_prices';

    protected $fillable = [
        'shipping_company_id',
        'city_id',
        'price',
    ];

    public function shippingCompany()
    {
        return $this->belongsTo(ShippingCompany::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
