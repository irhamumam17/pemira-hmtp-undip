<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Mahasiswa;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function index(){
        return view('mahasiswa.index');
    }
    public function admin_login_view(){
        if(auth()->guard('admin')->check()){
            return redirect(route('admin.home'));
        }
        return view('authentification.admin_login');
    }
    public function admin_login_post(Request $request){
        $validator  = Validator::make($request->all(),[
            'username' => 'required|string|exists:admins,username',
            'password' => 'required|string'
        ]);
        if($validator->fails()){
            return ([
                'status' => 'failed',
                'data' => null,
                'message' => $validator->errors()
            ]);
        }
        $auth = $request->only('username', 'password');
        $auth['status'] = 1;
        if(auth()->guard('admin')->attempt($auth)){
            return ([
                'status' => 'success',
                'data' => Auth::guard('admin'),
                'message' => 'Login Berhasil'
            ]);
        }
        return ([
            'status' => 'error',
            'data' => null,
            'message' => 'Email atau Password Salah'
        ]);
    }
    public function mahasiswa_login_view(){
        if(auth()->guard('admin')->check()){
            return redirect(route('mahasiswa.home'));
        }
        return view('authentification.mahasiswa_login');
    }
    public function mahasiswa_login_post(Request $request){
        // return $request->all();
        $validator  = Validator::make($request->all(),[
            'nim' => 'required|string',
            'password' => 'required|string'
        ]);
        if($validator->fails()){
            $error_msg = array_map(function ($object) { return $object; }, $validator->errors()->all());
            return ([
                'status' => 'failed',
                'data' => null,
                'message' => implode(', ', $error_msg)
            ]);
        }
        $auth = $request->only('nim', 'password');
        // $auth['status'] = 0;
        if(Mahasiswa::where('status',1)->count() > 40){
            return ([
                'status' => 'failed',
                'data' => null,
                'message' => 'Server Sedang Sibuk. Coba Beberapa Saat Lagi'
            ]);
        }
        if(auth()->guard('mahasiswa')->attempt($auth)){
            if(auth()->guard('mahasiswa')->user()->status == 1){
                auth()->guard('mahasiswa')->logout();
                return ([
                    'status' => 'failed',
                    'data' => null,
                    'message' => 'Anda Sedang Login di Sesi Lain.'
                ]);
            }
            if(auth()->guard('mahasiswa')->user()->status == 2){
                auth()->guard('mahasiswa')->logout();
                return ([
                    'status' => 'failed',
                    'data' => null,
                    'message' => 'Anda Sudah Menggunakan Hak Pilih Anda.'
                ]);
            }
            Mahasiswa::where('id','=',Auth::guard('mahasiswa')->user()->id)->update([
                'status' => 1
            ]);
            return ([
                'status' => 'success',
                'data' => Auth::guard('mahasiswa'),
                'message' => 'Login Berhasil'
            ]);
        }
        auth()->guard('mahasiswa')->logout();
        return ([
            'status' => 'failed',
            'data' => null,
            'message' => 'NIM atau Password Salah'
        ]);
    }
    public function admin_logout(){
        auth()->guard('admin')->logout();
        return redirect(route('home'));
    }
    public function mahasiswa_logout(){
        auth()->guard('mahasiswa')->logout();
        return redirect(route('mahasiswa.login'));
    }
}