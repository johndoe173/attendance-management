<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use App\Models\Attendance;
use App\Models\StampCorrectionRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class StampApproveTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 管理者は修正申請を承認できる()
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => Carbon::today(),
            'start_time' => '09:00:00',
            'end_time' => '18:00:00',
        ]);

        $request = StampCorrectionRequest::factory()->create([
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
            'requested_start_time' => '08:30:00',
            'requested_end_time' => '17:30:00',
            'requested_note' => '早出のため',
            'status' => '承認待ち',
        ]);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.stamp_request.approve', $request->id), [
                '_token' => csrf_token()
            ])
            ->assertRedirect(route('admin.stamp_request.index'));

        $this->assertDatabaseHas('attendances', [
            'id' => $attendance->id,
            'start_time' => '08:30:00',
            'end_time' => '17:30:00',
        ]);

        $this->assertDatabaseHas('stamp_correction_requests', [
            'id' => $request->id,
            'status' => '承認済み',
        ]);
    }

    /** @test */
    public function 非ログイン管理者は承認処理にアクセスできない()
    {
        $request = StampCorrectionRequest::factory()->create();

        $this->post(route('admin.stamp_request.approve', $request->id))
            ->assertRedirect('/admin/login');
    }
}
