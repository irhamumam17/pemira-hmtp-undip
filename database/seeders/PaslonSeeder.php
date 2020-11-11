<?php

namespace Database\Seeders;

use App\Models\Paslon;
use Illuminate\Database\Seeder;

class PaslonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Paslon::insert([
            'nomor_urut' => 1,
            'ketua_mahasiswa_id' => 1,
            'wakil_mahasiswa_id' => 2,
            'visi' => 'Jujur dan Berhasil',
            'misi' => 'Berkata Tidak Bohong',
            'foto' => 'presma.png'
        ]);
    }
}
