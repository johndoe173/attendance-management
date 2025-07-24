<?php

namespace Tests\Feature\StampCorrection;

use App\Models\User;
use App\Models\Admin;
use App\Models\Attendance;
use App\Models\StampCorrectionRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class RequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 修正申請フォームが表示される()
    {
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('attendance.show', $attendance->id))
            ->assertStatus(200)
            ->assertSee('修正');
    }

    /** @test */
    public function 修正申請にバリデーションエラーがあると保存されない()
    {
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('stamp_requests.store'), [
            'attendance_id' => $attendance->id,
            'requested_start_time' => '',
            'requested_end_time' => '',
            'requested_note' => '',
        ]);

        $response->assertSessionHasErrors([
            'requested_start_time',
            'requested_end_time',
            'requested_note',
        ]);
    }

    /** @test */
    public function 正常に修正申請が送信されデータベースに保存される()
    {
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->post(route('stamp_requests.store'), [
            'attendance_id' => $attendance->id,
            'requested_start_time' => '09:00',
            'requested_end_time' => '18:00',
            'requested_note' => '退勤の記録が漏れました',
        ])->assertRedirect();

        $this->assertDatabaseHas('stamp_correction_requests', [
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
            'requested_note' => '退勤の記録が漏れました',
            'status' => '承認待ち',
        ]);
    }

    /** @test */
    public function 管理者は修正申請一覧ページで申請を確認できる()
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create(['user_id' => $user->id]);
        $request = StampCorrectionRequest::factory()->create([
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
            'status' => '承認待ち',
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.stamp_request.index'))
            ->assertStatus(200)
            ->assertSee($user->name)
            ->assertSee('承認待ち');
    }
}
