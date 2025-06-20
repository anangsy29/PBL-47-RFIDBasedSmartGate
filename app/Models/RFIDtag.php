<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RFIDtag extends Model
{
    use HasFactory;

    protected $table = 'rfid_tags';
    protected $primaryKey = 'tags_id';
    // jika pakai bigIncrements jadi tetap true
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'vehicles_id',
        'tag_uid',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicles_id', 'vehicles_id');
    }

    public function accessLogs()
    {
        return $this->hasMany(AccessLog::class, 'tags_id');
    }
}
