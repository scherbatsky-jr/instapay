<?php

namespace Api\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Api\Stores\Models\Store;
use Api\Users\Models\User;

class Order extends Model
{
    protected $fillable = [
        'store_id',
        'user_id',
        'status',
        'total_price',
        'notes',
        'address_id'
    ];

    protected $table = 'orders';

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
