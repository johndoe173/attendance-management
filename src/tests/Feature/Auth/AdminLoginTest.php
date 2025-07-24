<?php

namespace Tests\Feature\Auth;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 管理者ログイン時にメールアドレスが未入力だとバリデーションエラーになる()
    {
        $response = $this->post('/admin/login', [
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function 管理者ログイン時にパスワードが未入力だとバリデーションエラーになる()
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function 管理者ログイン情報が誤っている場合は認証されない()
    {
        $response = $this->post('/admin/login', [
            'email' => 'notfound@example.com',
            'password' => 'invalid-password',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest('admin');
    }

    /** @test */
    public function 正しい情報で管理者ログインできる()
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('secret1234'),
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'secret1234',
        ]);

        $this->assertAuthenticatedAs($admin, 'admin');
        $response->assertRedirect(route('admin.attendance.index'));
    }
}
