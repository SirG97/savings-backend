<?php

namespace Tests\Feature;

use App\Enums\TransactionType;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\CustomerWallet;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testGetDashboardData(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $branch = Branch::factory()->create();
        $customer = Customer::factory()->create([
                'branch_id' => $branch->id,
                'user_id' => $user->id]
        );
        $branchWallet = Wallet::factory()->create([
            'branch_id' => $branch->id,
            'balance' => 1000,
            'cash' => 0,
            'bank' => 1000]);
        $customerWallet = CustomerWallet::factory()->create(['customer_id' => $customer->id,'balance' => 1000]);
        $transactions = Transaction::factory(4)->create([
            'customer_id' => $customer->id,
            'branch_id' => $branch->id,
            'user_id' => $user->id,
            'transaction_type' => TransactionType::DEPOSIT->value,
        ]);

        $transaction2 = Transaction::factory(3)->create([
            'customer_id' => $customer->id,
            'branch_id' => $branch->id,
            'user_id' => $user->id,
            'transaction_type' => TransactionType::WITHDRAWAL->value,
        ]);
        $response = $this->get(route('readDashboard'));
        $responseArray = $response->json();

        $response->assertStatus(200);
    }

    public function testGetDashboardDataByUser(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $this->actingAs($user);
        $branch = Branch::factory()->create();
        $customer = Customer::factory()->create([
                'branch_id' => $branch->id,
                'user_id' => $user->id]
        );
        $branchWallet = Wallet::factory()->create([
            'branch_id' => $branch->id,
            'balance' => 1000,
            'cash' => 0,
            'bank' => 1000]);
        $customerWallet = CustomerWallet::factory()->create(['customer_id' => $customer->id,'balance' => 1000]);
        $transactions = Transaction::factory(4)->create([
            'customer_id' => $customer->id,
            'branch_id' => $branch->id,
            'user_id' => $user2->id,
            'transaction_type' => TransactionType::DEPOSIT->value,
        ]);

        $transaction2 = Transaction::factory(3)->create([
            'customer_id' => $customer->id,
            'branch_id' => $branch->id,
            'user_id' => $user->id,
            'transaction_type' => TransactionType::WITHDRAWAL->value,
        ]);
        $response = $this->get(route('readDashboardByUserId'));
        $responseArray = $response->json();

        $response->assertStatus(200);
    }

    public function testGetDashboardDataByCustomer(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $branch = Branch::factory()->create();
        $customer = Customer::factory()->create([
                'branch_id' => $branch->id,
                'user_id' => $user->id]
        );
        $branchWallet = Wallet::factory()->create([
            'branch_id' => $branch->id,
            'balance' => 1000,
            'cash' => 0,
            'bank' => 1000]);
        $customerWallet = CustomerWallet::factory()->create(['customer_id' => $customer->id,'balance' => 1000]);
        $transactions = Transaction::factory(4)->create([
            'customer_id' => $customer->id,
            'branch_id' => $branch->id,
            'user_id' => $user->id,
            'transaction_type' => TransactionType::DEPOSIT->value,
        ]);

        $transaction2 = Transaction::factory(3)->create([
            'customer_id' => $customer->id,
            'branch_id' => $branch->id,
            'user_id' => $user->id,
            'transaction_type' => TransactionType::WITHDRAWAL->value,
        ]);
        $response = $this->get(route('readDashboardByCustomerId',['id' => $customer->id]));
        $responseArray = $response->json();
        $response->assertStatus(200);
    }
}
