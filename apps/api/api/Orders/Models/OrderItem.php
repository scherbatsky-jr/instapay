<?php

namespace Api\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Api\Products\Models\Product;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'count'
    ];

    protected $table = 'order_items';

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id', 'id');
    }
}
