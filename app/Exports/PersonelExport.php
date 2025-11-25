<?php

namespace App\Exports;

use App\Models\Personel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings; // <-- Başlıklar için
use Maatwebsite\Excel\Concerns\WithMapping;  // <-- İlişkili veriler (Departman Adı) için

class PersonelExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Veritabanından veriyi çeken yer
     */
    public function collection()
    {
        // İlişkileriyle beraber getir (Departman adını yazdırmak için lazım)
        return Personel::with('departman')->get();
    }

    /**
     * Excel'in en tepesindeki Başlık Satırı
     */
    public function headings(): array
    {
        return [
            'ID',
            'Ad Soyad',
            'E-Posta',
            'Departman',
            'Maaş',
            'İşe Başlama Tarihi',
        ];
    }

    /**
     * Hangi sütuna hangi veri gelecek? (Mapping)
     * Bunu yapmazsak departman_id olarak (Sayı) gelir, biz ismini istiyoruz.
     */
    public function map($personel): array
    {
        return [
            $personel->id,
            $personel->ad_soyad,
            $personel->email,
            $personel->departman ? $personel->departman->ad : 'Atanmamış',
            $personel->maas,
            $personel->ise_baslama_tarihi,
        ];
    }
}
