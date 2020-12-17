<?php

namespace App\Mail;

use App\Models\AppConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailSendAccount extends Mailable
{
    use Queueable, SerializesModels;

    protected $mahasiswa;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mahasiswa)
    {
        $this->mahasiswa = $mahasiswa;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $ui = AppConfiguration::where('name','=','ui')->first();
        if($ui){
            $icon = url('/assets/pemira/images/logo/'.$ui->detail["icon"]);
        }else{
            $icon = null;
        }
        return $this->view('admin.email.index')
        ->with('user',$this->mahasiswa)
        ->with('icon',$icon);
    }
}
