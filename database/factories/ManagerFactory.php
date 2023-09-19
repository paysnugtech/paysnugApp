<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\V1\Manager>
 */
class ManagerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $phoneNumber = '080'. fake()->unique()->numerify('########');

        return [
            'first_name' => fake()->firstName(),
            'other_name' => fake()->lastName(),
            'phone_no' => $phoneNumber,
            'email' => fake()->unique()->safeEmail(),
            'whatsapp_no' => $phoneNumber
        ];
    }
}
