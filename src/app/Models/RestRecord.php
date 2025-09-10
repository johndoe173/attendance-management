<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestRecord extends Model
{
    use HasFactory;

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    protected $fillable = [
    'attendance_id',
    'break_start',
    'break_end',
    ];

    public function getRestMinutesAttribute()
    {
        if ($this->break_start && $this->break_end) {
            return \Carbon\Carbon::parse($this->break_start)->diffInMinutes($this->break_end);
        }
        return 0;
    }
}
