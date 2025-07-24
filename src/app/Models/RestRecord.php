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

}
