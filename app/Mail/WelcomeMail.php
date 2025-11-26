<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Personel;

//class WelcomeMail extends Mailable
class WelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $personel; // Veriyi burada taşıyacağız

    // Dışarıdan bir Personel alacağız
    public function __construct(Personel $personel)
    {
        $this->personel = $personel;
    }

    // Mailin içeriğini belirlediğimiz yer
    public function build()
    {
        return $this->subject('ATC Yazılım Ailesine Hoş Geldin!')
            ->view('emails.welcome');
    }

}
