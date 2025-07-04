<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'email',
        'name',
        'password',
        'address',
        'phone_number',
        'fcm_token',
    ];

    protected $hidden = [
        'password',
    ];

    public function getAuthIdentifierName()
    {
        return 'user_id';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function rfidTags()
    {
        return $this->hasMany(\App\Models\RfidTag::class, 'user_id');
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'user_id', 'user_id');
    }
}
