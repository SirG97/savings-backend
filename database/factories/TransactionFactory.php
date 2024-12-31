<?php

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Enums\TransactionType;
use App\Enums\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'branch_id' => 1,
            'customer_id' => 1,
            'payment_method' => PaymentMethod::BANK->value,
            'reference' => fake()->uuid(),
            'type' => Type::CREDIT->value,
            'transaction_type' => TransactionType::DEPOSIT->value,
            'amount' => 1000,
            'balance_before' => 0,
            'balance_after' => 1000,
            'description' => fake()->text(40),
            'remark' => fake()->text(40),
            'date' => $this->faker->date(),

        ];
    }
}
