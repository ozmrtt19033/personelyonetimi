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
            DepartmanSeeder::class,
            PersonelSeeder::class,
            ProjectSeeder::class,
        ]);

        // 1. ADMIN KULLANICISI (Patron)
        \App\Models\User::create([
            'name' => 'Armağan Bey',
            'email' => 'admin@atc.com',
            'password' => bcrypt('password'),
            'role' => 'admin' // <-- Rütbesi Admin
        ]);

        // 2. STANDART KULLANICI (Personel)
        \App\Models\User::create([
            'name' => 'Stajyer Ahmet',
            'email' => 'stajyer@atc.com',
            'password' => bcrypt('password'),
            'role' => 'personel' // <-- Rütbesi Personel
        ]);
    }
}
