<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    protected $guarded = [];

    public function products()
    {
        return $this->belongsTo(Product::class,'product_code','product_code');
    }

    public function units()
    {
        return $this->belongsTo(Unit::class,'unit_id','id');
    }

    public function stock_units()
    {
        return $this->belongsTo(StockUnit::class,'product_code','product_code');
    }
}
