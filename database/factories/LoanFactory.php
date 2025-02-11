<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => 'Standard loan with flexible tenure from 1 to 6 months',
            'min_amount' => 50000,
            'max_amount' => 1000000,
            'interest_rate' => 8.9,
            'late_payment_interest_rate' => 1.0,
            'min_duration' => 1,
            'max_duration' => 6,
            'active' => '1',
        ];
    }
}
