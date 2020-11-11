<?php

namespace Database\Seeders;

use App\Models\AppConfiguration;
use Illuminate\Database\Seeder;

class AppConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AppConfiguration::insert([
            'app_name' => 'PEMIRA PWK 2020',
            'icon' => 'icon.png',
            'logo' => "['logo.png']"
        ]);
    }
}
