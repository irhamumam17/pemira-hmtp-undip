<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Paslon;
use App\Models\Pemilihan;
use App\Models\PemilihanTemp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function pemilihan(){
        $cek_vote = PemilihanTemp::where('mahasiswa_id','=',Auth::guard('mahasiswa')->user()->id)->first();
        if(!empty($cek_vote)){
            return redirect(route('mahasiswa.identification_view'));
        }
        return view('mahasiswa.vote');
    }
    
    public function get_calon(){
        $paslon = Paslon::with('ketua','wakil')->orderBy('nomor_urut','asc')->get();
        if(!empty($paslon)){
            return ([
                'status' => 'success',
                'data' => $paslon,
                'message' => 'Berhasil'
            ]);
        }
        return ([
            'status' => 'failed',
            'data' => null,
            'message' => 'Data Gagal Didapatkan'
        ]);
    }

    public function identification_view(){
        $cek_vote = PemilihanTemp::where('mahasiswa_id','=',Auth::guard('mahasiswa')->user()->id)->first();
        if(!$cek_vote){
            return redirect(route('mahasiswa.pemilihan'));
        }
        if($cek_vote->foto != null){
            return redirect(route('mahasiswa.review'));
        }
        return view('mahasiswa.upload_identity');
    }

    public function review(){
        $tempPemilihan = PemilihanTemp::with('mahasiswa','paslon.ketua','paslon.wakil')->where('mahasiswa_id','=',Auth::guard('mahasiswa')->user()->id)->first();
        if(!$tempPemilihan){
            return redirect(route('mahasiswa.pemilihan'));
        }
        if($tempPemilihan->foto == null){
            return redirect(route('mahasiswa.identification_view'));
        }
        return view('mahasiswa.review',compact('tempPemilihan'));
    }

    public function pemilihan_temporary_post(Request $request){
        $validator  = Validator::make($request->all(),[
            'nim' => 'required',
            'id_calon' => 'required',
            'nomor_urut' => 'required'
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

        PemilihanTemp::where('mahasiswa_id','=',Auth::guard('mahasiswa')->user()->id)->delete();
        $tempPemilihan = PemilihanTemp::insertGetId([
            'mahasiswa_id' => Auth::guard('mahasiswa')->user()->id,
            'paslons_id' => $request->id_calon
        ]);

        if($tempPemilihan){
            return ([
                'status' => 'success',
                'data' => null,
                'message' => 'Pemilihan Sukses'
            ]);
        }
        return ([
            'status' => 'failed',
            'data' => null,
            'message' => 'Pemilihan Gagal'
        ]);
    }

    public function identification_upload(Request $request){
        $validator  = Validator::make($request->all(),[
            'foto' => 'mimes:jpeg,jpg,png|required|max:5000',
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
        $name = $request->file('foto')->getClientOriginalName();

        $tempPemilihan = PemilihanTemp::where('mahasiswa_id','=',Auth::guard('mahasiswa')->user()->id)->first();
        if(!empty($tempPemilihan)){
            $file = public_path().'/assets/images/id_card/'.pathinfo($tempPemilihan->foto)['basename'];
            if(file_exists( $file)){
                File::delete($file);
            }
            
            $foto_name = Carbon::now()->format('dmYHis').$name;
            // Storage::put($foto_name, $request->file('foto'));
            $request->file('foto')->move('assets/images/id_card/', $foto_name);
            return ([
                'status' => 'success',
                'data' => $foto_name,
                'message' => 'Berhasil Di Upload'
            ]);
        }
    }
    public function identification_post(Request $request){
        $validator  = Validator::make($request->all(),[
            'foto' => 'required|string',
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
        PemilihanTemp::where('mahasiswa_id','=',Auth::guard('mahasiswa')->user()->id)->update([
            'foto' => $request->foto
        ]);
        return ([
            'status' => 'success',
            'data' => null,
            'message' => 'Sukses'
        ]);
    }
    public function revote(){
        $tempPemilihan = PemilihanTemp::where('mahasiswa_id','=',Auth::guard('mahasiswa')->user()->id)->first();
        try {
            $file = public_path().'/assets/images/id_card/'.pathinfo($tempPemilihan->foto)['basename'];
            if(file_exists( $file)){
                File::delete($file);
            }
            PemilihanTemp::where('mahasiswa_id','=',Auth::guard('mahasiswa')->user()->id)->delete();
            return ([
                'status' => 'success',
                'data' => null,
                'message' => 'Sukses'
            ]);
        } catch (\Throwable $th) {
            return ([
                'status' => 'failed',
                'data' => null,
                'message' => $th
            ]);
        }
    }
    public function pemilihan_post(){
        $tempPemilihan = PemilihanTemp::where('mahasiswa_id','=',Auth::guard('mahasiswa')->user()->id)->first();
        try {
            Pemilihan::insert([
                'mahasiswa_id' => Auth::guard('mahasiswa')->user()->id,
                'paslons_id' => $tempPemilihan->paslons_id,
                'foto' => pathinfo($tempPemilihan->foto)['basename']
            ]);
            PemilihanTemp::where('mahasiswa_id','=',Auth::guard('mahasiswa')->user()->id)->delete();

            Mahasiswa::where('id','=',Auth::guard('mahasiswa')->user()->id)->update([
                'status' => 2
            ]);
            Paslon::where('id','=',$tempPemilihan->paslons_id)->update([
                'jumlah_suara' => DB::raw('jumlah_suara+1')
            ]);
            auth()->guard('mahasiswa')->logout();
            return ([
                'status' => 'success',
                'data' => null,
                'message' => 'Pemilihan Sukses Dilakukan'
            ]);
        } catch (\Throwable $th) {
            return ([
                'status' => 'failed',
                'data' => null,
                'message' => $th
            ]);
        }
    }
    public function coba(){
        $destinationPath = '/assets/images/id_card/';
        $data = File::delete(public_path().$destinationPath.'11112020042345Somad.png');
    }
}
