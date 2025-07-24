<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
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
            'work_date' => $this->faker->date(),
            'start_time' => $this->faker->time(),
            'end_time' => $this->faker->time(),
            'status' => $this->faker->randomElement(['勤務外', '出勤中', '休憩中', '退勤済']),
        ];
    }

}
