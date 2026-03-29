<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Admin;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function registerSiswa(Request $request) {
        $request->validate(['nis' => 'required|unique:siswa,nis', 'nama' => 'required', 'password' => 'required|min:3']);

        $nama_foto = null;
        if ($request->hasFile('foto_profile')) {
            $file = $request->file('foto_profile');
            $nama_foto = $request->nis . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/profiles'), $nama_foto);
        }

        Siswa::create([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'password' => Hash::make($request->password),
            'foto_profile' => $nama_foto ? 'profiles/' . $nama_foto : null,
        ]);

        return redirect('/login')->with('success', 'Berhasil daftar, silakan login!');
    }

    public function login(Request $request) {
        // Cek Admin
        $admin = Admin::where('username', $request->id_user)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            Session::put('login_admin', true);
            Session::put('username', $admin->username);
            LogAktivitas::create(['username' => $admin->username, 'aktivitas' => 'Login ke sistem']);
            return redirect('/admin/dashboard');
        }

        // Cek Siswa
        $siswa = Siswa::where('nis', $request->id_user)->first();
        if ($siswa && Hash::check($request->password, $siswa->password)) {
            Session::put('login_siswa', true);
            Session::put('nis', $siswa->nis);
            Session::put('nama', $siswa->nama);
            LogAktivitas::create(['nis' => $siswa->nis, 'aktivitas' => 'Login ke sistem']);
            return redirect('/siswa/dashboard');
        }
        return back()->with('error', 'Login Gagal!');
    }

    public function logout() {
        if(Session::has('login_admin')) {
            LogAktivitas::create(['username' => session('username'), 'aktivitas' => 'Logout dari sistem']);
        } else if(Session::has('login_siswa')) {
            LogAktivitas::create(['nis' => session('nis'), 'aktivitas' => 'Logout dari sistem']);
        }
        Session::flush();
        return redirect('/');
    }
}