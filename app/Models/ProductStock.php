<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    protected $guarded = [];

    public function products()
    {
        return $this->belongsTo(Product::class,'product_code','product_code');
    }

    public function stock_units()
    {
        return $this->belongsTo(Product::class,'product_code','product_code');
    }
}
