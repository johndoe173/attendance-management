<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WorkTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkTimeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_start_work()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->post('/work/start', [
            'work_date' => now()->toDateString(),
            'start_time' => now()->format('H:i:s'),
        ]);

        $response->assertStatus(302); // リダイレクトを確認
        $this->assertDatabaseHas('work_times', [
            'user_id' => $user->id,
            'work_date' => now()->toDateString(),
            'start_time' => now()->format('H:i:s'),
        ]);
    }

    /** @test */
    public function a_user_can_end_work()
    {
        $user = User::factory()->create();
        $workTime = WorkTime::factory()->create([
            'user_id' => $user->id,
            'start_time' => now()->subHours(8)->format('H:i:s'),
        ]);

        $this->actingAs($user);
        $response = $this->post('/work/end', [
            'id' => $workTime->id,
            'end_time' => now()->format('H:i:s'),
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('work_times', [
            'id' => $workTime->id,
            'end_time' => now()->format('H:i:s'),
        ]);
    }
}
