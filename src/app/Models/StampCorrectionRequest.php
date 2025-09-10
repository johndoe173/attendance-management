<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StampCorrectionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_id',
        'requested_start_time',
        'requested_end_time',
        'requested_note',
        'status',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    const STATUS_PENDING = '承認待ち';
    const STATUS_APPROVED = '承認済み';
    const STATUS_REJECTED = '却下';

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

}

