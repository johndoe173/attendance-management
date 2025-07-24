<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;

class StampCorrectionRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'attendance_id' => Attendance::factory(),
            'requested_start_time' => $this->faker->time(),
            'requested_end_time' => $this->faker->time(),
            'requested_note' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['承認待ち', '承認済み', '否認']),
        ];
    }

}
