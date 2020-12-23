<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Paslon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PaslonController extends Controller
{
    public function index()
    {
        return view('admin.paslon.index');
    }
    public function getData(){
        $data = Paslon::with('ketua','wakil')->get();
        return ([
            'status' => 'success',
            'data' => $data,
            'message' => 'Data Calon Berhasil Didapatkan'
        ]);
    }

    public function getCalonKetua(){
        // $data = Mahasiswa::doesntHave('calon_ketua')->doesntHave('calon_wakil')->get();
        $data = Mahasiswa::all();
        return ([
            'status' => 'success',
            'data' => $data,
            'message' => 'Data Berhasil Didapatkan'
        ]);
    }
    public function getCalonWakil(Request $request){
        // $data = Mahasiswa::doesntHave('calon_ketua')->doesntHave('calon_wakil')->where('id','!=',$request->ketua_mahasiswa_id)->get();
        $data = Mahasiswa::where('id','!=',$request->ketua_mahasiswa_id)->get();
        return ([
            'status' => 'success',
            'data' => $data,
            'message' => 'Data Berhasil Didapatkan'
        ]);
    }
    public function create()
    {
        //
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
            'nomor_urut' => 'required|integer|min:1|unique:paslons,nomor_urut,NULL,id,deleted_at,NULL',
            'ketua_mahasiswa_id' => 'required|unique:paslons,ketua_mahasiswa_id,NULL,id,deleted_at,NULL|different:wakil_mahasiswa_id',
            'wakil_mahasiswa_id' => 'required|unique:paslons,wakil_mahasiswa_id,NULL,id,deleted_at,NULL|different:ketua_mahasiswa_id',
            'visi' => 'required',
            'misi' => 'required',
            'foto' => 'mimes:jpeg,jpg,png|required|max:4000',
        ]);
        $cek_ketua = Paslon::where('ketua_mahasiswa_id',$request->ketua_mahasiswa_id)->orWhere('wakil_mahasiswa_id',$request->ketua_mahasiswa_id)->first();
        if($cek_ketua){
            return ([
                'status' => 'failed',
                'data' => null,
                'message' => "Calon ketua telah terdaftar di paslon lain"
            ]);
        }
        $cek_wakil = Paslon::where('wakil_mahasiswa_id',$request->wakil_mahasiswa_id)->orWhere('ketua_mahasiswa_id',$request->wakil_mahasiswa_id)->first();
        if($cek_wakil){
            return ([
                'status' => 'failed',
                'data' => null,
                'message' => "Calon wakil telah terdaftar di paslon lain"
            ]);
        }
        try {
            $name = $request->file('foto')->getClientOriginalName();
            $foto_name = Carbon::now()->format('dmYHis').$name;
            $request->file('foto')->move('assets/pemira/images/calon/', $foto_name);
            $data = Paslon::create([
                'nomor_urut' => $request->nomor_urut,
                'ketua_mahasiswa_id' => $request->ketua_mahasiswa_id,
                'wakil_mahasiswa_id' => $request->wakil_mahasiswa_id,
                'visi' => $request->visi,
                'misi' => $request->misi,
                'foto' => $foto_name,
            ]);
            return ([
                'status' => 'success',
                'data' => $data,
                'message' => 'Paslon Berhasil Dimasukkan'
            ]);
        } catch (\Throwable $th) {
            return ([
                'status' => 'failed',
                'data' => $request,
                'message' => $th->getMessage()
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
    public function update(Request $request, Paslon $id)
    {
        $paslon = Paslon::find($id)[0];
        $request->validate([
            'nomor_urut' => 'required|integer|min:1|unique:paslons,nomor_urut,'.$paslon->id.',id,deleted_at,NULL',
            'ketua_mahasiswa_id' => 'required|unique:paslons,ketua_mahasiswa_id,'.$paslon->id.',id,deleted_at,NULL',
            'wakil_mahasiswa_id' => 'required|unique:paslons,wakil_mahasiswa_id,'.$paslon->id.',id,deleted_at,NULL',
            'visi' => 'required',
            'misi' => 'required',
            'foto' => 'nullable|max:4000|mimes:jpeg,jpg,png',
        ]);
        if($request->ketua_mahasiswa_id != $paslon->ketua_mahasiswa_id){
            $cek_ketua = Paslon::where('ketua_mahasiswa_id',$request->ketua_mahasiswa_id)->orWhere('wakil_mahasiswa_id',$request->ketua_mahasiswa_id)->first();
            if($cek_ketua){
                return ([
                    'status' => 'failed',
                    'data' => null,
                    'message' => "Calon ketua telah terdaftar di paslon lain"
                ]);
            }
        }
        if($request->ketua_mahasiswa_id != $paslon->ketua_mahasiswa_id){
            $cek_wakil = Paslon::where('wakil_mahasiswa_id',$request->wakil_mahasiswa_id)->orWhere('ketua_mahasiswa_id',$request->wakil_mahasiswa_id)->first();
            if($cek_wakil){
                return ([
                    'status' => 'failed',
                    'data' => null,
                    'message' => "Calon wakil telah terdaftar di paslon lain"
                ]);
            }
        }
        $foto_name = '';
        if($request->foto != ''){
            $name = $request->file('foto')->getClientOriginalName();
            $foto_name = Carbon::now()->format('dmYHis').$name;
            $request->file('foto')->move('assets/pemira/images/calon/', $foto_name);
        }else{
            $foto_name = pathinfo($paslon->foto)['basename'];
        }
        try {
            $file = public_path().'/assets/pemira/images/calon/'.pathinfo($paslon->foto)['basename'];
            if(file_exists( $file)){
                File::delete($file);
            }
            $data = $paslon->update([
                'nomor_urut' => $request->nomor_urut,
                'ketua_mahasiswa_id' => $request->ketua_mahasiswa_id,
                'wakil_mahasiswa_id' => $request->wakil_mahasiswa_id,
                'visi' => $request->visi,
                'misi' => $request->misi,
                'foto' => $foto_name,
            ]);
            return ([
                'status' => 'success',
                'data' => $data,
                'message' => 'Paslon Berhasil Diubah'
            ]);
        } catch (\Throwable $th) {
            return ([
                'status' => 'failed',
                'data' => $request,
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
    public function destroy(Paslon $paslon)
    {
        if(!$paslon->pemilihan()->exists()){
            try {
                $file = public_path().'/assets/pemira/images/calon/'.pathinfo($paslon->foto)['basename'];
                if(file_exists( $file)){
                    File::delete($file);
                }
                $paslon->delete();
                return ([
                    'status' => 'success',
                    'data' => null,
                    'message' => 'Paslon Berhasil Dihapus'
                ]);
            } catch (\Throwable $th) {
                return ([
                    'status' => 'failed',
                    'data' => null,
                    'message' => $th->getMessage()
                ]);
            }
        }else{
            return ([
                'status' => 'failed',
                'data' => $paslon->pemilihan(),
                'message' => 'Paslon Sedang Digunakan dalam proses pemilihan'
            ]);
        }
    }
}
