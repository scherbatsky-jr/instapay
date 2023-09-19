<?php

namespace Api\Stores\Models;

use Illuminate\Database\Eloquent\Model;
use Api\Users\Models\User;

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
}
