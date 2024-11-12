<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\RestTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestTimeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_start_rest()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->post('/rest/start', [
            'start_time' => now()->format('H:i:s'),
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('rest_times', [
            'work_time_id' => $user->id,
            'start_time' => now()->format('H:i:s'),
        ]);
    }

    /** @test */
    public function a_user_can_end_rest()
    {
        $user = User::factory()->create();
        $restTime = RestTime::factory()->create([
            'user_id' => $user->id,
            'start_time' => now()->subMinutes(30)->format('H:i:s'),
        ]);

        $this->actingAs($user);
        $response = $this->post('/rest/end', [
            'id' => $restTime->id,
            'end_time' => now()->format('H:i:s'),
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('rest_times', [
            'id' => $restTime->id,
            'end_time' => now()->format('H:i:s'),
        ]);
    }
}
