<?php

namespace Api\Stores\Models;

use Illuminate\Database\Eloquent\Model;
use Api\Users\Models\User;
use Api\Orders\Models\Order;

class Store extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'instagram',
        'tiktok',
        'website',
        'user_id',
    ];

    protected $table = 'stores';

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function orders() {
        return $this->hasMany(Order::class, 'store_id', 'id');
    }
}
