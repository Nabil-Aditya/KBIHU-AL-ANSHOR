<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // COMMENT ATAU HAPUS BARIS INI
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Panggil UserSeeder kita
        $this->call([
            UserSeeder::class,
        ]);
    }
}