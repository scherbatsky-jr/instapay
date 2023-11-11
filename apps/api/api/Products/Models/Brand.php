<?php

namespace Api\Products\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'code'
    ];

    protected $table = 'brands';

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id', 'id');
    }
}
