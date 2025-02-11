<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Loan;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Loan::create([
            'name' => 'Standard Loan',
            'description' => 'Standard loan with flexible tenure from 1 to 6 months',
            'min_amount' => 50000,
            'max_amount' => 1000000,
            'interest_rate' => 8.9,
            'late_payment_interest_rate' => 1.0,
            'min_duration' => 1,
            'max_duration' => 6,
            'active' => true,
        ]);
    }
}
