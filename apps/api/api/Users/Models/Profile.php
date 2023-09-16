<?php

namespace Api\Users\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'given_name',
        'surname',
    ];

    protected $table = 'profiles';

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }
}
