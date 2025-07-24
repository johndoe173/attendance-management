<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AttendanceListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 管理者は勤怠一覧画面にアクセスできる()
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.attendance.index'))
            ->assertStatus(200)
            ->assertSee('勤怠一覧');
    }

    /** @test */
    public function 勤怠一覧に全ユーザーの勤怠情報が表示される()
    {
        $admin = Admin::factory()->create();
        $user1 = User::factory()->create(['name' => '山田 太郎']);
        $user2 = User::factory()->create(['name' => '鈴木 花子']);

        Attendance::factory()->create([
            'user_id' => $user1->id,
            'work_date' => Carbon::today(),
            'start_time' => '09:00',
            'end_time' => '18:00',
            'status' => '退勤済',
        ]);

        Attendance::factory()->create([
            'user_id' => $user2->id,
            'work_date' => Carbon::today(),
            'start_time' => '10:00',
            'end_time' => '19:00',
            'status' => '退勤済',
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.attendance.index'))
            ->assertStatus(200)
            ->assertSee('山田 太郎')
            ->assertSee('鈴木 花子')
            ->assertSee('09:00')
            ->assertSee('10:00');
    }

    /** @test */
    public function 未ログインの状態では管理者勤怠一覧にアクセスできない()
    {
        $this->get(route('admin.attendance.index'))
            ->assertRedirect('/admin/login');
    }
}
