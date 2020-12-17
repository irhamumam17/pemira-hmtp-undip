<?php

namespace App\Providers;

use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if(Schema::hasTable('app_configurations')){
            $email_config = DB::table('app_configurations')->where('name','=','email')->first();
            if(!empty($email_config)){
                $data = json_decode($email_config->detail,true);
                $config = array(
                    'driver'     => 'smtp',
                    'host'       => $data['host'],
                    'port'       => $data['port'],
                    'from'       => array('address' => $data['email'], 'name' => $data['sender_name']),
                    'encryption' => $data['encryption'],
                    'username'   => $data['username'],
                    'password'   => $data['password'],
                    'sendmail'   => '/usr/sbin/sendmail -bs',
                    'pretend'    => false,
                );
                Config::set('mail', $config);
            }   
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
