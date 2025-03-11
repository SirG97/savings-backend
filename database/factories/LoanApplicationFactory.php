<?php

namespace Database\Factories;

use App\Enums\LoanStatus;
use App\Enums\PerformedAction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LoanApplication>
 */
class LoanApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => 500000,
            'interest_amount' => 9,
            'total_amount' => 544500,
            'total_payable_amount' => 544500,
            'duration' => 2,
            'status' => LoanStatus::PENDING->value
        ];
    }
}
