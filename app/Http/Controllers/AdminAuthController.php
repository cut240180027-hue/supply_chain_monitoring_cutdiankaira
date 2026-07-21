<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Port;
use App\Models\Article;
use App\Models\Country;
use App\Models\Shipment;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    // ===== LOGIN =====
    public function showLogin()
    {
        // If already logged in, redirect to admin dashboard
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_id' => 'required|string',
            'password' => 'required|string|min:4',
        ], [
            'login_id.required' => 'Username atau Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $loginId = $request->login_id;
        $user = User::where('email', $loginId)
            ->orWhere('name', $loginId)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withInput(['login_id' => $loginId])
                ->with('error', 'Username/Email atau password salah. Silakan coba lagi.');
        }

        // Store admin session
        session([
            'admin_logged_in' => true,
            'admin_user_id'   => $user->id,
            'admin_user_name' => $user->name,
            'admin_user_email'=> $user->email,
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['admin_logged_in', 'admin_user_id', 'admin_user_name', 'admin_user_email']);

        return redirect()->route('admin.login')
            ->with('success', 'Anda berhasil logout dari panel admin.');
    }
}
