<?php

namespace Api\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Api\Stores\Models\Store;
// use Api\Products\Models\Brand;
use Api\Products\Models\Review;

class Product extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'stock',
        'brand',
        'store_id',
        'created_by'
    ];

    protected $table = 'products';

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    // public function brand()
    // {
    //     return $this->belongsTo(Brand::class, 'brand_id', 'id');
    // }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }
}
