<?php

namespace Tests\Feature\Attendance;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use App\Models\RestRecord;


class PunchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 出勤処理が成功する()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/attendance/start');

        $response->assertRedirect();
        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'status' => '出勤中',
        ]);
    }

    /** @test */
    public function 退勤処理が成功する()
    {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => '出勤中',
            'start_time' => now()->subHours(8),
        ]);

        $this->actingAs($user);

        $response = $this->post('/attendance/end');

        $response->assertRedirect();
        $this->assertDatabaseHas('attendances', [
            'id' => $attendance->id,
            'status' => '退勤済',
        ]);
    }

    /** @test */
    public function 休憩開始処理が成功する()
    {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => '出勤中',
            'start_time' => now()->subHours(4),
        ]);

        $this->actingAs($user);

        $response = $this->post('/attendance/break_start');

        $response->assertRedirect();
        $this->assertDatabaseHas('attendances', [
            'id' => $attendance->id,
            'status' => '休憩中',
        ]);
    }

    /** @test */
    public function 休憩終了処理が成功する()
    {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => '休憩中',
            'start_time' => now()->subHours(6),
        ]);

        // 🔑 未終了の休憩を作成
        $attendance->restRecords()->create([
            'break_start' => now()->subHour(),
            'break_end'   => null,
        ]);

        $this->actingAs($user);

        $response = $this->post('/attendance/break_end');

        $response->assertRedirect();
        $this->assertDatabaseHas('attendances', [
            'id' => $attendance->id,
            'status' => '出勤中',
        ]);
    }

}
