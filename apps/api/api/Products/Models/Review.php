<?php

namespace Api\Products\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'product_id',
        'rating',
        'comment'
    ];

    protected $table = 'reviews';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
