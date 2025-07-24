<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function restRecords()
    {
        return $this->hasMany(RestRecord::class);
    }

    public function getTotalTimeAttribute()
    {
        if ($this->start_time && $this->end_time) {
            return \Carbon\Carbon::parse($this->start_time)->diffInMinutes(\Carbon\Carbon::parse($this->end_time)) / 60 . ':00';
        }
        return '--:--';
    }

    public function correctionRequests()
    {
        return $this->hasMany(StampCorrectionRequest::class);
    }

    protected $fillable = [
    'user_id', 'work_date', 'start_time', 'end_time','status','note'
    ];

    protected $casts = [
    'work_date' => 'datetime:y-m-d',
    'start_time' => 'datetime:H:i',
    'end_time' => 'datetime:H:i',
    ];

}
