<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\RaporMail;

class RaporGonder extends Command
{
    // 1. Komutun adı (Terminalde ne yazacağız?)
    protected $signature = 'rapor:gonder';

    // 2. Komutun açıklaması
    protected $description = 'Yöneticilere haftalık Excel raporunu mail atar.';

    // 3. Komut çalışınca ne yapsın?
    public function handle()
    {
        $this->info('Rapor hazırlanıyor ve gönderiliyor...');

        // Kime gönderilecek? (Örn: admin@atc.com)
        Mail::to('mrt.ozcan.94@gmail.com')->send(new RaporMail());

        $this->info('✅ Rapor başarıyla gönderildi!');
    }
}
