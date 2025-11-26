<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PersonelExport;
use Illuminate\Mail\Mailables\Attachment;

class RaporMail extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        return $this->subject('📊 Haftalık Personel Raporu')
            ->view('emails.rapor');
    }

    // Dosya ekleme işlemi (Modern Laravel Yöntemi)
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => Excel::raw(new PersonelExport, \Maatwebsite\Excel\Excel::XLSX), 'haftalik_rapor.xlsx')
                ->withMime('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
        ];
    }
}
