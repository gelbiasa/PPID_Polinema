<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;
use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) { // jika sudah login, maka redirect ke halaman home 
            return redirect('/dashboardAdmin');
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $credentials = $request->only('username', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                // Tentukan redirect URL berdasarkan role user
                $redirectUrl = match ($user->level->level_kode) {
                    'ADM' => url('/dashboardAdmin'),
                    'RPN' => url('/dashboardResponden'),
                    'MPU' => url('/dashboardMPU'),
                    'VFR' => url('/dashboardVFR'),
                    default => url('/login') // Default jika role tidak dikenali
                };

                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => $redirectUrl,
                ]);
            }

            Log::error('Login failed for user: ' . $request->username);
            return response()->json([
                'status' => false,
                'message' => 'Login Gagal',
            ]);
        }

        return redirect('auth.auth');
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}
