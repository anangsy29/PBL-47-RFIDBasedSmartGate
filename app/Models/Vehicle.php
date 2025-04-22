<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    protected $table = 'vehicles';
    protected $primaryKey = 'vehicles_id';
    protected $keyType = 'int';
    // jika pakai bigIncrements jadi tetap true
    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'plate_number',
        'vehicle_type',
        'brand',
        'color',
    ];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
