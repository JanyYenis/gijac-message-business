<?php

namespace Database\Seeders\Conceptos;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            NodosSeeder::class,
            N8nSeeder::class,
            ChatbotIaSeeder::class,
            MensajeSeeder::class,
        ]);
    }
}
