<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $guarded = [];

    public function product_units()
    {
        return $this->hasMany(ProductUnit::class,'unit_id','id');
    }

    public function stock_units()
    {
        return $this->hasMany(StockUnit::class,'unit_id','id');
    }
}
