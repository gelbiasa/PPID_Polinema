<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;
use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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

    public function register()
    {
        $level = LevelModel::all(); // Fetch level from database
        return view('auth.register', compact('level')); // Pass levels to the view
    }

    public function postRegister(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|min:4|max:20|unique:m_user,username',
                'nama' => 'required|min:2|max:50',
                'password' => 'required|min:5|max:20|confirmed',
                'password_confirmation' => 'required',
                'level_id' => 'required|exists:m_level,level_id',
                'no_hp' => 'required|digits_between:4,15',
                'email' => 'required|email|unique:m_user,email' 
            ], [
                'username.unique' => 'Username sudah digunakan, silakan pilih username lain.',
                'username.min' => 'Username minimal harus 4 karakter.',
                'username.max' => 'Username maksimal 20 karakter.',
                'nama.min' => 'Nama minimal harus 2 karakter.',
                'nama.max' => 'Nama maksimal 50 karakter.',
                'password.min' => 'Password minimal harus 5 karakter.',
                'password.max' => 'Password maksimal 20 karakter.',
                'password.confirmed' => 'Verifikasi password tidak sesuai dengan password baru.',
                'no_hp.required' => 'Nomor handphone wajib diisi.',
                'no_hp.digits_between' => 'Nomor handphone harus terdiri dari 4 hingga 15 digit.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah digunakan, silakan gunakan email lain.' 
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            UserModel::create([
                'username' => $request->username,
                'nama' => $request->nama,
                'password' => bcrypt($request->password),
                'level_id' => $request->level_id,
                'no_hp' => $request->no_hp,
                'email' => $request->email
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Register Berhasil',
                'redirect' => url('login')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat memproses registrasi'
            ], 500);
        }
    }
}
