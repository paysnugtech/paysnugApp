<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['id' => '9a2b984c-a65d-4947-a978-f709a2e1d601', 'name' => 'Nigeria', 'currency' => 'Naira', 'currency_code' => 'NGN', 'is_available' => '1']
        ];
    
        foreach ($countries as $country) {
            \App\Models\Country::factory()->create($country);
        }
    }
}
