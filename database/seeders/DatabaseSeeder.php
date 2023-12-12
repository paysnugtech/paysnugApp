<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\ServiceType;
use App\Models\WalletType;
use Database\Seeders\BankSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\ManagerSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        $this->call([
            CountrySeeder::class,
            BankSeeder::class,
            ManagerSeeder::class,
            RoleSeeder::class,
            ServiceSeeder::class,
            WalletTypeSeeder::class,
        ]);
    }
}
