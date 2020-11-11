<?php

namespace Database\Seeders;

use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::insert([
            'username' => 'admin',
            'password' => bcrypt('rahasia'),
            'status' => 0,
            'last_login' => Carbon::now()
        ]);
    }
}
