<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    //menampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    //menghandle request login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            //jika login berhasil, kita akan mengatur session
            $request->session()->regenerate();
            //ambil user yang sedang login
            $user = Auth::user();

            //redirect berdasarkan role user
            if ($user->isSuperAdmin() || $user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }
            return redirect()->route('home');
        }
        //jika login gagal
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    //menampilkan halaman register
    public function showRegister()
    {
        return view('auth.register');
    }

   // menghandle request register
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'nrp' => ['required', 'string', 'max:255', 'unique:users'],
            'jurusan' => ['required', 'string', 'max:255'],
            'line_id' => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'terms' => ['required', 'accepted'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nrp' => $request->nrp,
            'jurusan' => $request->jurusan,
            'role' => 'user', //default role buat user
            'line_id' => $request->line_id,
            'whatsapp' => $request->whatsapp,

        ]);
        //user coba login setelah register
        Auth::login($user);

        return redirect()->route('dashboard');
    }

    //menghandle logout (hapus session dan token CSRF)
    //user harus login lagi untuk mengakses halaman yang membutuhkan autentikasi
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}