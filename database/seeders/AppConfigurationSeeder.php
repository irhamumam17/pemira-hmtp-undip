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
            [
                'name' => 'ui',
                'detail' => '{"app_name":"PEMIRA PWK 2020","icon":"icon.png","logo":"logo.png"}'
            ],
            [
                'name' => 'email',
                'detail' => '{"email":"no-reply@pemira.miu.co.id","sender_name":"Admin PEMIRA PWK Undip","host":"smtp-relay.sendinblue.com","port":"587","username":"coklatbrown356@gmail.com","password":"xsmtpsib-f6e37206f562c9e252073ec77e32ddeb6e7fb7dc09ebdbeb8ba5243c2b73b20f-dwsHNO8jyTMR4nGX","encryption":"tls"}'
            ]
        ]);
    }
}
