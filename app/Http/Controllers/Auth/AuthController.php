<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\RouteHelpers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Redirect user to their respective dashboard based on session role.
     */
    public function rootRedirect(): RedirectResponse
    {
        return RouteHelpers::redirectByRole();
    }

    /**
     * Show the main login selection/form page.
     */
    public function showLogin(): View
    {
        return view('auth.login', [
            'pageTitle' => 'Login Portal Arena Gym',
            'roles'     => [
                [
                    'value'       => 'member',
                    'label'       => 'Member',
                    'description' => 'Akses dashboard member dan informasi gym.',
                    'icon'        => 'user',
                    'img'         => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&q=80&w=400',
                ],
                [
                    'value'       => 'admin',
                    'label'       => 'Admin',
                    'description' => 'Kelola member, check-in, dan operasional.',
                    'icon'        => 'shield-check',
                    'img'         => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&q=80&w=400',
                ],
                [
                    'value'       => 'master_admin',
                    'label'       => 'Master Admin',
                    'description' => 'Akses penuh seluruh modul sistem.',
                    'icon'        => 'key',
                    'img'         => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&q=80&w=400',
                ],
                [
                    'value'       => 'cashier',
                    'label'       => 'Kasir',
                    'description' => 'Kelola transaksi dan pembayaran harian.',
                    'icon'        => 'wallet',
                    'img'         => 'https://images.unsplash.com/photo-1556742044-3c52d6e88c62?auto=format&fit=crop&q=80&w=400',
                ],
            ],
        ]);
    }

    /**
     * Handle login submission.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required'],
        ]);

        $identifier = trim((string) ($request->input('login') ?: $request->input('email')));
        $password   = (string) $request->input('password');

        if ($identifier === '') {
            return back()
                ->withErrors([
                    $request->has('email') ? 'email' : 'login' => 'Username atau Email wajib diisi.'
                ])
                ->withInput();
        }

        // Cari user berdasarkan login (username) maupun email
        $user = User::query()
            ->where('login', $identifier)
            ->orWhere('email', $identifier)
            ->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            return back()
                ->withErrors([
                    $request->filled('login') ? 'login' : 'email' => 'Username/Email atau password tidak sesuai.'
                ])
                ->withInput();
        }

        $request->session()->regenerate();

        // Pastikan session kompatibel untuk seluruh modul (admin, cashier, member)
        $sessionData = [
            'id'      => $user->id,
            'user_id' => $user->id,
            'role'    => $user->role,
            'login'   => $user->login,
            'email'   => $user->email,
            'name'    => $user->name,
        ];

        $request->session()->put('auth', $sessionData);

        if ($user->role === 'member') {
            $request->session()->put('show_whatsapp_channel_prompt', true);
            return redirect()->route('member.dashboard');
        }

        return in_array($user->role, ['admin', 'master_admin'], true)
            ? redirect()->route('admin.dashboard')
            : redirect()->route('cashier.dashboard');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
