<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['name' => 'Airtime', 'discount' => '0%',  'charges' => '0','fee' => '0', 'duplicate_time' => '3', 'is_available' => true],
            ['name' => 'betting', 'discount' => '0%',  'charges' => '0','fee' => '0', 'duplicate_time' => '3', 'is_available' => false],
            ['name' => 'Cable', 'discount' => '0%',  'charges' => '0','fee' => '0', 'duplicate_time' => '3', 'is_available' => true],
            ['name' => 'Data', 'discount' => '0%',  'charges' => '0','fee' => '0', 'duplicate_time' => '3', 'is_available' => true],
            ['name' => 'Electricity', 'discount' => '0%',  'charges' => '0','fee' => '0', 'duplicate_time' => '3', 'is_available' => true],
            ['name' => 'Bank Transfer', 'discount' => '0%',  'charges' => '10','fee' => '5', 'duplicate_time' => '3', 'is_available' => true],
        ];

        foreach ($services as $service) {
            \App\Models\Service::factory()->create($service);
        }
    }
}
