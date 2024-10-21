<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->normal()->create();
        User::factory()->silver()->create();
        User::factory()->gold()->create();


        $this->call(ProductSeeder::class);
    }
}
