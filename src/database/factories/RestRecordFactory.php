<?php

namespace Database\Factories;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;

class RestRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'attendance_id' => Attendance::factory(),
            'break_start' => $this->faker->time('H:i:s'),
            'break_end' => $this->faker->time('H:i:s'),
        ];
    }

}
