<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);

        $this->call([
            DepartmanSeeder::class, // 1. Önce bu
            PersonelSeeder::class,  // 2. Sonra bu (YENİ EKLEDİK)
            ProjectSeeder::class,   // 3. En son bu (İlişki kurar)
        ]);
    }
}
