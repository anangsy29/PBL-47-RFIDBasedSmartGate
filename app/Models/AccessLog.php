<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    use HasFactory;

    protected $table = 'access_logs';
    protected $primaryKey = 'log_id';
    // jika pakai bigIncrements jadi tetap true
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'tags_id',
        'accessed_at',
        'status',
        'note',
    ];

    public function rfidTag()
    {
        return $this->belongsTo(RfidTag::class, 'tags_id', 'tags_id');
    }
}
