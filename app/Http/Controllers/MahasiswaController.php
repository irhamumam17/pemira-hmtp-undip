<?php

namespace App\Http\Controllers;

use App\Imports\MahasiswaImport;
use App\Mail\MailSendAccount;
use App\Models\Mahasiswa;
use App\Models\Paslon;
use App\Models\Pemilihan;
use App\Models\PemilihanTemp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Stmt\TryCatch;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.mahasiswa.index');
    }

    public function getDataAll(){
        $mahasiswa = Mahasiswa::all();
        return ([
            'status' => 'success',
            'data' => $mahasiswa,
            'message' => 'Data Berhasil Didapatkan'
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|min:5|unique:mahasiswas,nim,NULL,id,deleted_at,NULL',
            'name' => 'required',
            'angkatan' => 'required|integer|min:1900|max:2021',
            'email' => 'required|email|unique:mahasiswas,email,NULL,id,deleted_at,NULL',
            'start_session' => 'required|date_format:Y-m-d H:i',
            'end_session' => 'required|date_format:Y-m-d H:i',
        ]);
        try {
            $mahasiswa = Mahasiswa::create([
                'nim' => $request->nim,
                'name' => $request->nim,
                'angkatan' => $request->angkatan,
                'hint_password' => Str::random(6),
                'start_session' => $request->start_session,
                'end_session' => $request->end_session,
            ]);
            return ([
                'status' => 'success',
                'data' => $mahasiswa,
                'message' => 'Data Berhasil Disimpan'
            ]);
        } catch (\Throwable $th) {
            return ([
                'status' => 'failed',
                'data' => $th->getMessage(),
                'message' => 'Data Gagal Disimpan'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        //
        $request->validate([
            'nim' => 'required|min:5|unique:mahasiswas,nim,'.$mahasiswa->id.',id,deleted_at,NULL',
            'name' => 'required',
            'angkatan' => 'required|integer|min:1900|max:2021',
            'email' => 'required|email|unique:mahasiswas,email,'.$mahasiswa->id.',id,deleted_at,NULL',
            'start_session' => 'required|date_format:Y-m-d H:i',
            'end_session' => 'required|date_format:Y-m-d H:i',
        ]);
        try {
            $mahasiswa->update($request->all());
            return ([
                'status' => 'success',
                'data' => $mahasiswa,
                'message' => 'Mahasiswa Berhasil Diubah'
            ]);
        } catch (\Throwable $th) {
            return ([
                'status' => 'failed',
                'data' => null,
                'message' => $th->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        //
        try {
            Paslon::whereHas('pemilihan',function($q) use ($mahasiswa){
                $q->where('mahasiswa_id','=',$mahasiswa->id);
            })->update([
                'jumlah_suara' => DB::raw('jumlah_suara-1')
            ]);
            Pemilihan::where('mahasiswa_id','=',$mahasiswa->id)->delete();
            PemilihanTemp::where('mahasiswa_id','=',$mahasiswa->id)->delete();
            $mahasiswa->delete();

            return ([
                'status' => 'success',
                'data' => null,
                'message' => 'Mahasiswa Berhasil Dihapus'
            ]);
        } catch (\Throwable $th) {
            return ([
                'status' => 'failed',
                'data' => null,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function get_mahasiswa(){
        $data = Mahasiswa::all();
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => 'Data Sukses Di Dapatkan.'
        ]);
    }

    public function import(Request $request){
        $validator  = Validator::make($request->all(),[
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);
        if($validator->fails()){
            $error_msg = array_map(function ($object) {
                return $object;
            }, $validator->errors()->all());
            return ([
                'status' => 'failed',
                'data' => null,
                'message' => implode(', ', $error_msg)
            ]);
        }
        try {
            $file = $request->file('file');
            $nama_file = rand().$file->getClientOriginalName();
            Excel::import(new MahasiswaImport, $file);
            $file->move('assets/file/import/',$nama_file);
            return ([
                'status' => 'success',
                'data' => null,
                'message' => 'Berhasil Import Data Mahasiswa'
            ]);
        } catch (\Throwable $th) {
            return ([
                'status' => 'failed',
                'data' => null,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function reset_session($id){
        $mahasiswa = Mahasiswa::find($id);
        $user_session = $mahasiswa->auth_session;
        if($user_session){
            $mahasiswa->update([
                'status' => 0,
                'auth_session' => null
            ]);
            Session::getHandler()->destroy($user_session);
            return ([
                'status' => 'success',
                'data' => null,
                'message' => 'Akun Mahasiswa Berhasil Di Reset'
            ]);
        }
        return ([
            'status' => 'failed',
            'data' => null,
            'message' => 'Sesi Mahasiswa Tidak Ditemukan.'
        ]);
    }

    public function sendEmailAccount(Request $request){
        try {
            $mahasiswa = Mahasiswa::find($request->id);
            Mail::to($mahasiswa->email)->send(new MailSendAccount($mahasiswa));
            return ([
                'status' => 'success',
                'data' => null,
                'message' => 'Akun Mahasiswa Berhasil Di Kirim'
            ]);
        } catch (\Throwable $th) {
            return ([
                'status' => 'failed',
                'data' => null,
                'message' => $th->getMessage()
            ]);
        }
    }
}
