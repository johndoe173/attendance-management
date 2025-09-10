<?php

namespace Tests\Feature\Email;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class VerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 認証メールが送信される()
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->get('/email/verify')
            ->assertStatus(200)
            ->assertSee('認証はこちらから');
    }

    /** @test */
    public function メール認証リンクが正しく処理される()
    {
        Event::fake();

        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $this->assertStringStartsWith('/attendance', parse_url($response->headers->get('Location'), PHP_URL_PATH));
    }

    /** @test */
    public function 無効な署名のURLではエラーが返る()
    {
        $user = User::factory()->unverified()->create();

        $invalidUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->subMinutes(1), // 過去の時刻にして無効化
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->actingAs($user)
            ->get($invalidUrl)
            ->assertStatus(403);
    }

    /** @test */
    public function 認証済みユーザーが認証ページにアクセスするとリダイレクトされる()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($user)
            ->get('/email/verify')
            ->assertRedirect('/attendance/punch');
    }
}
