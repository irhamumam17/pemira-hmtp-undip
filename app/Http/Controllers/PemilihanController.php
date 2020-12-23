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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class PemilihanController extends Controller
{
    public function dataPemilihan(){
        $data = Pemilihan::with('mahasiswa','paslon')->get();
        return view('admin.pemilihan.index',compact('data'));
    }

    public function getDataPemilihan(){
        $data = Pemilihan::with('mahasiswa','paslon')->get();
        return ([
            'status' => 'success',
            'data' => $data,
            'message' => 'Data Berhasil Didapatkan'
        ]);
    }

    public function validVote($id){
        $pemilihan = Pemilihan::find($id);
        try {
            $pemilihan->update([
                'status' => 0
            ]);
            Paslon::where('id','=',$pemilihan->paslons_id)->update([
                'jumlah_suara' => DB::raw('jumlah_suara+1')
            ]);
            return ([
                'status' => 'success',
                'data' => null,
                'message' => 'Suara mahasiswa berhasil diubah menjadi valid'
            ]);
        } catch (\Throwable $th) {
            return ([
                'status' => 'failed',
                'data' => null,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function invalidVote($id){
        $pemilihan = Pemilihan::find($id);
        try {
            $pemilihan->update([
                'status' => 1
            ]);
            Paslon::where('id','=',$pemilihan->paslons_id)->update([
                'jumlah_suara' => DB::raw('jumlah_suara-1')
            ]);
            return ([
                'status' => 'success',
                'data' => null,
                'message' => 'Suara mahasiswa berhasil diubah menjadi invalid'
            ]);
        } catch (\Throwable $th) {
            return ([
                'status' => 'failed',
                'data' => null,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function quickcount(){
        return view('quickcount.index');
    }

    public function getDataQuickcount(){
        $data = Paslon::with('ketua','wakil')->withCount('pemilihan')->get();
        $total_mhs = Mahasiswa::count();
        $suara_masuk = Pemilihan::count();
        return ([
            'status' => 'success',
            'data' => ([
                'suara' =>  $data,
                'total_mhs' => $total_mhs,
                'suara_masuk' => $suara_masuk
            ]),
            'message' => 'Berhasil'
        ]);
    }
    public function index(){
        $cek_vote = PemilihanTemp::where('mahasiswa_id','=',Auth::guard('mahasiswa')->user()->id)->first();
        if(!empty($cek_vote)){
            return redirect(route('mahasiswa.identification_view'));
        }
        $paslon = Paslon::with('ketua','wakil')->orderBy('nomor_urut','asc')->get();
        return view('mahasiswa.vote',compact('paslon'));
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
            return redirect(route('mahasiswa.pemilihan'))->withErrors(['error' => implode(', ', $error_msg)]);   
            // return ([
            //     'status' => 'failed',
            //     'data' => null,
            //     'message' => implode(', ', $error_msg)
            // ]);
        }

        PemilihanTemp::where('mahasiswa_id','=',Auth::guard('mahasiswa')->user()->id)->delete();
        $tempPemilihan = PemilihanTemp::insertGetId([
            'mahasiswa_id' => Auth::guard('mahasiswa')->user()->id,
            'paslons_id' => $request->id_calon
        ]);

        if($tempPemilihan){ 
            // return ([
            //     'status' => 'success',
            //     'data' => null,
            //     'message' => 'Pemilihan Sukses'
            // ]);
            return redirect(route('mahasiswa.identification_view'));   
        }
        return redirect(route('mahasiswa.pemilihan'))->withErrors(['error' => "gagal"]);   
    }

    public function identification_upload(Request $request){
        $validator  = Validator::make($request->all(),[
            'foto' => 'mimes:jpeg,jpg,png|required|max:4000',
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
            // 'foto' => 'required|string',
            'foto' => 'mimes:jpeg,jpg,png|required|max:4000',
        ]);
        if($validator->fails()){
            $error_msg = array_map(function ($object) {
                return $object;
            }, $validator->errors()->all());
            // return ([
            //     'status' => 'failed',
            //     'data' => null,
            //     'message' => implode(', ', $error_msg)
            // ]);
            return redirect(route('mahasiswa.identification_view'))->withErrors(['error' => implode(', ', $error_msg)]);
        }
        try{
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
                PemilihanTemp::where('mahasiswa_id','=',Auth::guard('mahasiswa')->user()->id)->update([
                    'foto' => $foto_name
                ]);
                return redirect(route('mahasiswa.review')); 
            }
            return redirect(route('mahasiswa.identification_view'))->withErrors(['error' => "Terjadi Kesalahan"]);
        } catch (\Throwable $th) {
            return redirect(route('mahasiswa.identification_view'))->withErrors(['error' => $th->getMessage()]);
        }
    }
    public function revote(){
        $tempPemilihan = PemilihanTemp::where('mahasiswa_id','=',Auth::guard('mahasiswa')->user()->id)->first();
        try {
            $file = public_path().'/assets/images/id_card/'.pathinfo($tempPemilihan->foto)['basename'];
            if(file_exists( $file)){
                File::delete($file);
            }
            PemilihanTemp::where('mahasiswa_id','=',Auth::guard('mahasiswa')->user()->id)->delete();
            // return ([
            //     'status' => 'success',
            //     'data' => null,
            //     'message' => 'Sukses'
            // ]);
            return redirect(route('mahasiswa.pemilihan')); 
        } catch (\Throwable $th) {
            return redirect(route('mahasiswa.review'))->withErrors(['error' => $th->getMessage()]); 
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
                'status' => 2,
                'auth_session' => null,
            ]);
            Paslon::where('id','=',$tempPemilihan->paslons_id)->update([
                'jumlah_suara' => DB::raw('jumlah_suara+1')
            ]);
            auth()->guard('mahasiswa')->logout();
            return redirect(route('home'));
        } catch (\Throwable $th) {
            return redirect(route('mahasiswa.review'))->withErrors(['error' => $th->getMessage()]); 
        }
    }
}
