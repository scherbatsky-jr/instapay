<?php

namespace Api\Orders\Models;

use Illuminate\Database\Eloquent\Model;
use Api\Orders\Models\Order;

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
        return $this->belongsTo(Order::class,'product_id', 'id');
    }
}
