<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerWallet>
 */
class CustomerWalletFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => 1,
            'count' => fake()->numberBetween(11, 50),
            'balance' => fake()->numberBetween(10, 10000),

        ];
    }
}
