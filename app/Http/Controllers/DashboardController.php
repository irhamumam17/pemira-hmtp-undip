<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Paslon;
use App\Models\Pemilihan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        return view('admin.index');
    }

    public function getDataPemilihan(){
        $mhs_total = Mahasiswa::count();
        $mhs_belum = Mahasiswa::where('status','=',0)->count();
        $mhs_sedang = Mahasiswa::where('status','=',1)->count();
        $mhs_sudah = Mahasiswa::where('status','=',2)->count();

        $data = [];
        $paslon = Paslon::all();
        foreach($paslon as $p){
            $suara_paslon = Pemilihan::where('paslons_id','=',$p->id)->count();
            array_push($data,[$p->nomor_urut,$suara_paslon]);
        }
        return ([
            'status' => 'success',
            'data' => (object)[
                'mhs_total' => $mhs_total,
                'mhs_belum' => $mhs_belum,
                'mhs_sedang' => $mhs_sedang,
                'mhs_sudah' => $mhs_sudah,
                'data_suara' => $data
            ],
            'message' => 'Data Berhasil Didapatkan'
        ]);
    }
}
