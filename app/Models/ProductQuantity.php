<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductQuantity extends Model
{
    protected $guarded =[];

    public function sizes(){
        return $this->belongsTo(ProductSize::class,'product_size_id')->with('sizeDetail');
    }
    public function colors(){
        return $this->belongsTo(ProductColor::class,'product_color_id')->with('colorDetail');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
}
