<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
{
    // 管理者アカウント手動作成（オプション）
    \App\Models\User::factory()->create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => bcrypt('admin1234'),
        'role' => 'admin',
    ]);

    // ユーザー10人分と勤怠・申請・休憩レコードを自動生成
    \App\Models\User::factory(10)->create()->each(function ($user) {
        $attendances = \App\Models\Attendance::factory(3)->create(['user_id' => $user->id]);

        foreach ($attendances as $attendance) {
            \App\Models\RestRecord::factory(2)->create(['attendance_id' => $attendance->id]);
            \App\Models\StampCorrectionRequest::factory(1)->create([
                'user_id' => $user->id,
                'attendance_id' => $attendance->id,
            ]);
        }
    });
}

}
