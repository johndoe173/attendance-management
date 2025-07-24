<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; 
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $staffs = User::all(); // 必要に応じて where('role', 'staff') なども追加
        return view('admin.attendance.staff', compact('staffs'));
    }
}
