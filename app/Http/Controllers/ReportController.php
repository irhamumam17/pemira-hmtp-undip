<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Paslon;
use App\Models\Pemilihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    //
    public function index(){
        return view('admin.report.index');
    }

    public function getData(){
        try {
            $dpt = Mahasiswa::count();
            $suara_masuk = Pemilihan::count();
            $suara_sah = Pemilihan::where('status',0)->count();
            $suara_tidak_sah = Pemilihan::where('status',1)->count();
            $golput = Mahasiswa::where('status','!=',2)->count();
            return ([
                'status' => 'success',
                'data' => ([
                    'dpt' => $dpt,
                    'suara_masuk' => $suara_masuk,
                    'suara_sah' => $suara_sah,
                    'suara_tidak_sah' => $suara_tidak_sah,
                    'golput' => $golput
                ]),
                'message' => 'Data Berhasil Didapatkan'
            ]);
        } catch (\Throwable $th) {
            return ([
                'status' => 'failed',
                'data' => null,
                'message' => 'Data gagal didapatkan'
            ]);
        }
    }
    public function getPerolehanSuara(){
        try {
            $data = Paslon::with('ketua','wakil')->get();
            return ([
                'status' => 'success',
                'data' => $data,
                'message' => 'Data Berhasil Didapatkan'
            ]);
        } catch (\Throwable $th) {
            return ([
                'status' => 'failed',
                'data' => null,
                'message' => 'Data gagal didapatkan'
            ]);
        }
    }
}
