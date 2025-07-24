<?php

namespace Tests\Feature\Attendance;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 勤怠詳細ページが正しく表示される()
    {
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => Carbon::today(),
        ]);

        $this->actingAs($user)
            ->get(route('attendance.show', $attendance->id))
            ->assertStatus(200)
            ->assertSee($user->name)
            ->assertSee($attendance->work_date->format('Y年n月j日'));
    }

    /** @test */
    public function 修正申請時に出勤時間が未入力だとバリデーションエラーになる()
    {
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post(route('stamp_requests.store'), [
                'attendance_id' => $attendance->id,
                'requested_start_time' => null,
                'requested_end_time' => '18:00',
                'requested_note' => '修正お願いします',
            ])
            ->assertSessionHasErrors('requested_start_time');
    }

    /** @test */
    public function 修正申請時に備考が未入力だとバリデーションエラーになる()
    {
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post(route('stamp_requests.store'), [
                'attendance_id' => $attendance->id,
                'requested_start_time' => '09:00',
                'requested_end_time' => '18:00',
                'requested_note' => '',
            ])
            ->assertSessionHasErrors('requested_note');
    }

    /** @test */
    public function 修正申請が正常に保存される()
    {
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post(route('stamp_requests.store'), [
                'attendance_id' => $attendance->id,
                'requested_start_time' => '09:00',
                'requested_end_time' => '18:00',
                'requested_note' => '打刻ミスがありました',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('stamp_correction_requests', [
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
            'requested_start_time' => '09:00:00',
            'requested_end_time' => '18:00:00',
            'requested_note' => '打刻ミスがありました',
            'status' => '承認待ち',
        ]);
    }
}
