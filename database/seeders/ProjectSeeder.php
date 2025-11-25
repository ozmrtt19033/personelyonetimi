<?php

namespace Database\Seeders; // Laravel 6 ise namespace Database\Seeds olabilir, dikkat et.

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Personel;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        // 1. Önce Projeleri Oluşturuyoruz
        $p1 = Project::create([
            'ad' => 'ATC Yönetim Paneli',
            'bitis_tarihi' => '2025-12-30'
        ]);

        $p2 = Project::create([
            'ad' => 'Mobil IOS Uygulaması',
            'bitis_tarihi' => '2026-01-15'
        ]);

        $p3 = Project::create([
            'ad' => 'Personel Takip Sistemi',
            'bitis_tarihi' => '2025-06-20'
        ]);

        // 2. Bir Personel Bul (İlk sıradakini alalım)
        $personel = Personel::first();

        // Eğer veritabanında hiç personel yoksa hata vermesin diye kontrol koyalım
        if ($personel) {
            // 3. İLİŞKİYİ KURMA (Many-to-Many)
            // attach() içine proje ID'lerini veriyoruz.
            // Bu personel hem Yönetim Paneli'nde hem Mobil App'te çalışsın.
            $personel->projects()->attach([$p1->id, $p2->id]);

            echo "✅ {$personel->ad_soyad} isimli personele projeler atandı.\n";
        } else {
            echo "⚠️ Hiç personel bulunamadı, ilişki kurulamadı.\n";
        }
    }
}
