<?php

namespace Database\Factories;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'first_name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'account_id' => fake()->slug(6),
            'middle_name' => fake()->lastName(),
            'dob' => fake()->dateTime(),
            'sex' =>  Gender::MALE->value,
            'resident_state' => fake()->name(),
            'resident_lga' => fake()->name(),
            'resident_address' => fake()->streetAddress(),
            'occupation' => fake()->jobTitle(),
            'office_address' => fake()->address(),
            'state' => fake()->name(),
            'lga' => fake()->name(),
            'hometown' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'next_of_kin'  => fake()->name(),
            'relationship' => fake()->name(),
            'nok_phone' => fake()->phoneNumber(),
            'acc_no'  => fake()->numerify(),
            'branch_id' => 1,
            'user_id' => 1,
            'group' => fake()->numerify(13),
            'sb_card_no_from' => fake()->numerify(13),
            'sb_card_no_to' => fake()->numerify(13),
            'sb' => fake()->numerify(13),
            'initial_unit' => fake()->numberBetween(1, 100),
            'bank_name' => fake()->name(),
            'bank_code' => fake()->numerify(3),
            'account_name' => fake()->name(),
            'account_number' => fake()->numerify(13),
            'daily_amount' => fake()->numberBetween(1000, 5000)
        ];
    }
}
