<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function categories()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function product_stocks()
    {
        return $this->hasMany(ProductStock::class,"product_code",'product_code');
    }

    public function stock_units()
    {
        return $this->hasMany(StockUnit::class,"product_code",'product_code');
    }

    public function product_units()
    {
        return $this->hasMany(ProductUnit::class,'product_code','product_code');
    }
}
