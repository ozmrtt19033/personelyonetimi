<?php

namespace App\Observers;

use App\Models\Personel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PersonelObserver
{
    /**
     * Handle the Personel "created" event.
     */
    public function created(Personel $personel): void
    {
       Log::info("🆕 Yeni Personel Katıldı: " . $personel->ad_soyad . " (" . $personel->email . ")");
       //bu kısma hoş geldin mailini de taşıyacagız::::

    }

    /**
     * Handle the Personel "updated" event.
     */
    public function updated(Personel $personel): void
    {
       //maaşı güncellendiginde log atalım::
        // Sadece maaşı değiştiyse log atalım mesela
        if ($personel->isDirty('maas')) {
            Log::info("💰 Maaş Güncellemesi: " . $personel->ad_soyad . " yeni maaşı: " . $personel->maas);
        }
    }

    /**
     * Handle the Personel "deleted" event.
     */
    public function deleted(Personel $personel): void
    {
        Log::warning("🗑️ Personel Çöpe Atıldı: " . $personel->ad_soyad);
    }

    /**
     * Handle the Personel "restored" event.
     */
    public function restored(Personel $personel): void
    {
        //
    }

    /**
     * Handle the Personel "force deleted" event.
     */
    public function forceDeleted(Personel $personel): void
    {
        //personel ve dosyaları tamamen silindiginde:::
        Log::alert("💀 Personel ve Dosyaları Tamamen Silindi: " . $personel->ad_soyad);
        // Eğer resmi varsa ve dosya diskte duruyorsa SİL
        if ($personel->gorsel && Storage::disk('public')->exists($personel->gorsel)) { // var mı kontrolü yapıldı
            Storage::disk('public')->delete($personel->gorsel);
            Log::info("📸 Fotoğraf dosyası da temizlendi: " . $personel->gorsel);
        }

    }
}
