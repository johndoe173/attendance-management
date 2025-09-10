<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition()
    {
        $workDate = now()->subDays($this->faker->numberBetween(0, 10))->startOfDay();
        $start = (clone $workDate)->addHours($this->faker->numberBetween(8, 11))
                                  ->addMinutes($this->faker->randomElement([0,15,30,45]));
        $end   = (clone $start)->addMinutes($this->faker->numberBetween(7*60, 10*60));

        return [
            'user_id'    => User::factory(),
            'work_date'  => $workDate->toDateString(),
            'start_time' => $start->format('H:i:s'),
            'end_time'   => null, // デフォルトは勤務中
            'status'     => '出勤中',
        ];
    }

    /**
     * 退勤済み状態
     */
    public function finished()
    {
        return $this->state(function (array $attributes) {
            $start = Carbon::parse($attributes['start_time']);
            $end   = (clone $start)->addHours(9);

            return [
                'end_time' => $end->format('H:i:s'),
                'status'   => '退勤済',
            ];
        });
    }

    public function withBreak()
    {
        return $this->afterCreating(function (Attendance $attendance) {
            $attendance->restRecords()->create([
                'break_start' => now()->subHour(),
                'break_end'   => null,
            ]);
        });
    }


    /**
     * 休憩中状態
     */
    public function onBreak()
    {
        return $this->state(function () {
            return [
                'end_time' => null,
                'status'   => '休憩中',
            ];
        });
    }
}
