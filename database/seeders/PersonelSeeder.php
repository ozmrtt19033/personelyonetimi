<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Personel;
use App\Models\Departman;

class PersonelSeeder extends Seeder
{
    public function run()
    {
        // Önce veritabanındaki ilk departmanı bulalım (Yazılım vs.)
        $departman = Departman::first();

        // Eğer hiç departman yoksa (Seeder sırası karıştıysa) hata vermesin diye kontrol
        if ($departman) {
            Personel::create([
                'ad_soyad' => 'Murat Özcan',
                'email' => 'murat@atc.com',
                'departman_id' => $departman->id, // Bulduğumuz departmana ata
                'maas' => 50000,
                'ise_baslama_tarihi' => '2025-12-01',
                // Resim şimdilik boş kalsın
            ]);

            Personel::create([
                'ad_soyad' => 'Ahmet Yılmaz',
                'email' => 'ahmet@atc.com',
                'departman_id' => $departman->id,
                'maas' => 42000,
                'ise_baslama_tarihi' => '2024-05-15',
            ]);
        }
    }
}
