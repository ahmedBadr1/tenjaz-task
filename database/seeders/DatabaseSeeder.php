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
        User::factory()->normal()->create(['username'=>'normal']);
        User::factory()->silver()->create(['username'=>'silver']);
        User::factory()->gold()->create(['username'=>'gold']);


        $this->call(ProductSeeder::class);
    }
}
