<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_using_email_and_redirects_to_admin_dashboard(): void
    {
        $user = User::query()->create([
            'name'     => 'Mohammad Yusril musyafak',
            'login'    => 'yus',
            'email'    => 'f.yusril703@gmail.com',
            'role'     => 'admin',
            'password' => Hash::make('knjtbadak'),
        ]);

        $response = $this->post('/login', [
            'login'    => 'f.yusril703@gmail.com',
            'password' => 'knjtbadak',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertEquals($user->id, session('auth.user_id'));
        $this->assertEquals('admin', session('auth.role'));
    }

    public function test_user_can_login_using_username(): void
    {
        $user = User::query()->create([
            'name'     => 'Mohammad Yusril musyafak',
            'login'    => 'yus',
            'email'    => 'f.yusril703@gmail.com',
            'role'     => 'admin',
            'password' => Hash::make('knjtbadak'),
        ]);

        $response = $this->post('/login', [
            'login'    => 'yus',
            'password' => 'knjtbadak',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertEquals($user->id, session('auth.user_id'));
    }

    public function test_login_fails_with_invalid_password(): void
    {
        User::query()->create([
            'name'     => 'Mohammad Yusril musyafak',
            'login'    => 'yus',
            'email'    => 'f.yusril703@gmail.com',
            'role'     => 'admin',
            'password' => Hash::make('knjtbadak'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'login'    => 'f.yusril703@gmail.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('login');
    }
}
