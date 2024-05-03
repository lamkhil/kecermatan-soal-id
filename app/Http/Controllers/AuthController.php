<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{
    public function login()
    {
        if(Auth::check()){


            if(Auth()->user()->role == 'member'){

                return redirect('/member/dashboard');

            }

            return redirect('/admin/dashboard');

        }

        return view('auth.login');
    }

    public function auth(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ],
        [
            'email.required'    => 'Email harus diisi!',
            'email.email'       => 'Email tidak valid!',
            'password.required' => 'Password harus diisi!',
        ]
        );

        if(Auth::attempt($data,$request->remember)){
                
            if(Auth()->user()->role == 'member'){

                return redirect('/member/dashboard');

            }

            return redirect('/admin/dashboard');

        }
        
            return back()->with('error','Pengguna belum terdaftar!');

    }

    public function register()
    {
        return view('auth.register');
    }

    public function saveRegister(Request $request)
    {
        $data = $request->validate([
            'fullname' => 'required',
            'email'    => 'required|email:rfc,dns|unique:users,email',
            'no_hp'    => 'required|numeric',
            'password' => 'required|confirmed'
        ],
        [
            'fullname.required' => 'Nama lengkap harus diisi!',
            'email.required'    => 'Email harus diisi!',
            'email.email'       => 'Email tidak valid!',
            'email.unique'      => 'Email sudah terdaftar!',
            'no_hp.required'    => 'No HP harus diisi!',
            'no_hp.numeric'     => 'No HP tidak valid!',
            'password.required' => 'Password harus diisi!',
            'password.confirmed' => 'Konfirmasi password tidak sama!'
        ]
        );

        $creds = \App\Models\User::create([
            'name' => $data['fullname'],
            'email' => $data['email'],
            'no_hp' => $data['no_hp'],
            'password' => bcrypt($data['password']),
            'id_package' => 1
        ]);

        Auth::login($creds,true);


        return redirect('/member/dashboard');
        
    

    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }


}
