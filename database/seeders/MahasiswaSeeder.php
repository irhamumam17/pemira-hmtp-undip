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
                'email' => 'birublue05@gmail.com',
                'password' => bcrypt('12345'),
                'hint_password' => '12345',
                'start_session' => null,
                'end_session' => null,
                'status' => 0
            ],
            [
                'nim' => '22222222',
                'name' => 'Mahasiswa2',
                'angkatan' => 2017,
                'email' => 'kuningyellow67@gmail.com',
                'password' => bcrypt('12345'),
                'hint_password' => '12345',
                'start_session' => null,
                'end_session' => null,
                'status' => 0
            ],
            [
                'nim' => '333333333',
                'name' => 'Mahasiswa3',
                'angkatan' => 2018,
                'email' => 'hijaugreen139@gmail.com',
                'password' => bcrypt('12345'),
                'hint_password' => '12345',
                'start_session' => null,
                'end_session' => null,
                'status' => 0
            ],
            [
                'nim' => '444444444',
                'name' => 'Mahasiswa4',
                'angkatan' => 2019,
                'email' => 'redmerah215@gmail.com',
                'password' => bcrypt('12345'),
                'hint_password' => '12345',
                'start_session' => null,
                'end_session' => null,
                'status' => 0
            ],
            [
                'nim' => '21040118140100',
                'name' => 'Mufti Muttaqi',
                'angkatan' => 2020,
                'email' => 'luqmanhasyim5@gmail.com',
                'password' => bcrypt('12345'),
                'hint_password' => '12345',
                'start_session' => null,
                'end_session' => null,
                'status' => 0
            ]
        ]);
    }
}
