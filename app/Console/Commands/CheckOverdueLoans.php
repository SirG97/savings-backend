<?php

namespace App\Console\Commands;

use App\Enums\LoanStatus;
use App\Models\LoanApplication;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckOverdueLoans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loans:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for overdue loans and update their status and interest';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Starting overdue loans check...');

            $dueLoans = LoanApplication::where('status', LoanStatus::DUE->value)
                ->where('due_date', '<', Carbon::now())
                ->get();

            if ($dueLoans->isEmpty()) {
                $this->info('No overdue loans found.');
                return 0;
            }

            foreach ($dueLoans as $loan) {
                $daysOverdue = Carbon::now()->diffInDays($loan->due_date);
                
                // Calculate late payment interest (1% per month prorated daily)
                $dailyLateInterestRate = (0.01 / 30); // 1% monthly rate converted to daily
                $latePaymentInterest = $loan->amount * $dailyLateInterestRate * $daysOverdue;
                
                // Update total payable amount
                $newTotalPayable = $loan->amount + $loan->interest_amount + $latePaymentInterest;
                
                // Update loan status and amounts
                $loan->update([
                    'status' => LoanStatus::OVERDUE->value,
                    'late_payment_interest' => $latePaymentInterest,
                    'total_payable_amount' => $newTotalPayable,
                    'days_overdue' => $daysOverdue
                ]);

                $this->info("Loan ID {$loan->id} marked as overdue. Days overdue: {$daysOverdue}");
            }

            $this->info('Overdue loans check completed successfully.');
            return 0;
        } catch (\Exception $e) {
            Log::error('Error processing overdue loans: ' . $e->getMessage());
            $this->error('An error occurred while processing overdue loans.');
            return 1;
        }
    }
}