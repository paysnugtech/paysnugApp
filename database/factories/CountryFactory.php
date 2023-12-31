<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Country>
 */
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => '9a2b984c-a65d-4947-a978-f709a2e1d601',
            'name' => 'Nigeria',
            'currency' => 'Naira',
            'currency_code' => 'NGN'
        ];
    }
}
