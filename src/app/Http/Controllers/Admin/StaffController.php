<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * スタッフ一覧（管理者）
     */
    public function index(Request $request)
    {
        // 検索キーワードがあれば名前やメールで絞り込み
        $query = User::query()
            ->where('role', 'user'); // 役割で絞る（一般スタッフのみ）

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // ページネーション
        $staffs = $query->orderBy('name')->paginate(20);

        return view('admin.staff.index', compact('staffs', 'search'));
    }
}
