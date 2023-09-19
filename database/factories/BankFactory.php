<?php

namespace Database\Factories;

use App\Enums\VirtualAccountEnum;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bank>
 */
class BankFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        $country = Country::where('name', 'Nigeria')->firstOrFail();

        return [
            'name' => 'Paysnug',
            'bank_code' => '0000',
            'address' => 'Ibadan',
            'type' => 'Wallet',
            'is_virtual_account' => VirtualAccountEnum::True->value,
            'country_id' => $country->id,
            'created_by' => 'System',
        ];
    }
}
