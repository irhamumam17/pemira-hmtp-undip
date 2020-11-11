<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Mahasiswa::insert([
            [
                'nim' => '111111111',
                'name' => 'Mahasiswa',
                'angkatan' => 2016,
                'kelas' => 'IK-1A',
                'password' => bcrypt('12345'),
                'start_session' => null,
                'end_session' => null,
                'status' => 0
            ],
            [
                'nim' => '22222222',
                'name' => 'Mahasiswa2',
                'angkatan' => 2017,
                'kelas' => 'IK-1B',
                'password' => bcrypt('12345'),
                'start_session' => null,
                'end_session' => null,
                'status' => 0
            ]
        ]);
    }
}
