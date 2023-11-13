<?php

namespace Api\Orders\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'status',
        'total_amount',
        'payment_type'
    ];

    protected $table = 'orders';

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
