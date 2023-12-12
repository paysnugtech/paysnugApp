<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceTypes = [
            ['name' => 'Transfer', 'fee' => '15']
        ];

        foreach ($serviceTypes as $serviceType) {
            \App\Models\ServiceType::factory()->create($serviceType);
        }
    }
}
