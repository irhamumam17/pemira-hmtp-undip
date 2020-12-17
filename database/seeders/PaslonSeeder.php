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
            [
                'nomor_urut' => 1,
                'ketua_mahasiswa_id' => 1,
                'wakil_mahasiswa_id' => 2,
                'visi' => 'Jujur Dan Amanah Dalam Pelaksanaan Tugas Diiringi Teknologi Dan Berlandaskan Agama',
                'misi' => '<ol><li>Menggunakan Teknologi Dalam Setiap Kegiatan</li><li>Menanamkan Sifat Jujur Dalam Setiap Penyampaian Informasi</li><li>Menjaga Komunikasi Dari Setiap Anggota</li><li>Menerapkan Kehidupan Yang Agamis Di Lingkungan Organisasi</li></ol>',
                'foto' => 'presma.png'
            ],
            [
                'nomor_urut' => 2,
                'ketua_mahasiswa_id' => 3,
                'wakil_mahasiswa_id' => 4,
                'visi' => 'Maju Dalam Teknologi Informasi Dan Komunikasi Yang Berlandaskan Pancasila',
                'misi' => '<ol><li>Menggunakan Teknologi Dalam Setiap Kegiatan</li><li>Menanamkan Sifat Jujur Dalam Setiap Penyampaian Informasi</li><li>Menjaga Komunikasi Dari Setiap Anggota</li><li>Menerapkan Kehidupan Yang Agamis Di Lingkungan Organisasi</li></ol>',
                'foto' => 'presma.png'
            ],
        ]);
    }
}
