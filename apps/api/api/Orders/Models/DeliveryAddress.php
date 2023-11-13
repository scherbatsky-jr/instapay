<?php

namespace Api\Orders\Models;

use Illuminate\Database\Eloquent\Model;
use Api\Orders\Models\Order;
use Api\Users\Models\User;

class DeliveryAddress extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'contact',
        'email',
        'street',
        'area',
        'city',
        'state',
        'landmarks'
    ];

    protected $table = 'delivery_addresses';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'. 'id');
    }
}
