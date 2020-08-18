<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockUnit extends Model
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
    
    public function product_units()
    {
        return $this->hasMany(ProductStock::class,'product_code','product_code');
    }
}
