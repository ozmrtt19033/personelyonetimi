<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Departman;

class DepartmanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Eğer departmanlar zaten varsa ekleme
        if (Departman::count() == 0) {
            Departman::create(['ad' => 'Yazılım']);
            Departman::create(['ad' => 'Muhasebe']);
            Departman::create(['ad' => 'İnsan Kaynakları']);
            Departman::create(['ad' => 'Satış']);
            
            $this->command->info('Departmanlar başarıyla eklendi!');
        } else {
            $this->command->info('Departmanlar zaten mevcut!');
        }
    }
}
