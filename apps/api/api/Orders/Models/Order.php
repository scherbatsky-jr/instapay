<?php

namespace Api\Orders\Models;

use Illuminate\Database\Eloquent\Model;
use Api\Stores\Models\Store;
use Api\Users\Models\User;
use Api\Orders\Models\DeliveryAddress;

class Order extends Model
{
    public const STATUS_NEW = 0;
    public const STATUS_PAYMENT_PENDING = 1;

    public const STATUS_PAYMENT_SUCCESS = 2;

    public const STATUS_PAYMENT_FAILED = 10;

    public const STATUS_SHIPPED = 3;
    
    public const STATUS_DELIVERED = 4;

    protected $fillable = [
        'store_id',
        'user_id',
        'status',
        'total_amount',
        'notes',
        'created_by',
        'address_id',
        'tracking_number'
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

    public function deliveryAddress()
    {
        return $this->belongsTo(DeliveryAddress::class, 'address_id', 'id');
    }
}
