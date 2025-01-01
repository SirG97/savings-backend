<?php

namespace Tests\Feature;

use App\Enums\PaymentMethod;
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

class TransactionTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testErrorValidationForCreateTransactions()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $postData = [
            'name' => null,
        ];

        $response = $this->postJson(route('createTransaction'), $postData);
        $responseArray = $response->json();

        $this->assertFalse($responseArray['success']);
    }

    public function testCreateTransactions()
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
            'balance' => 0,
            'cash' => 0,
            'bank' => 0]);
        $customerWallet = CustomerWallet::factory()->create(['customer_id' => $customer->id,'balance' => 0]);

        $postData = [
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'user_id' => $user->id,
            'amount' => $this->faker->randomFloat(2, 1100),
            'transaction_type' => TransactionType::DEPOSIT->value,
            'payment_method' => PaymentMethod::BANK->value,
            'description' => $this->faker->sentence(1),
            'date' => $this->faker->date(),
        ];

        $response = $this->postJson(route('createTransaction'), $postData);
        $responseArray = $response->json();
        $response->dump();
        $response->assertOk();

        $this->assertTrue($responseArray['success']);
    }

//    public function testErrorValidationForUpdateTransactions()
//    {
//        $user = User::factory()->create();
//
//        $this->actingAs($user);
//
//        $postData = [
//            'id' => 'abc',
//            'name' => $this->faker->name(),
//        ];
//
//        $response = $this->putJson(route('updateTransaction'), $postData);
//        $responseArray = $response->json();
//
//        $this->assertFalse($responseArray['success']);
//    }
//
//    public function testUpdateTransactions()
//    {
//        $user = User::factory()->create();
//
//        $this->actingAs($user);
//
//        $transactions = Transaction::factory()->create();
//
//        $postData = [
//            'id' => $transactions->id,
//            'name' => $this->faker->name(),
//        ];
//
//        $response = $this->putJson(route('updateTransaction'), $postData);
//        $responseArray = $response->json();
//
//        $response->assertOk();
//        $this->assertTrue($responseArray['success']);
//    }

//    public function testErrorValidationForDeleteTransactions()
//    {
//        $user = User::factory()->create();
//
//        $this->actingAs($user);
//
//        $postData = [
//            'id' => 'abc'
//        ];
//
//        $response = $this->deleteJson(route('deleteTransactions'), $postData);
//        $responseArray = $response->json();
//
//        $this->assertFalse($responseArray['success']);
//    }
//
//    public function testDeleteTransactions()
//    {
//        $user = User::factory()->create();
//
//        $this->actingAs($user);
//
//        $transactions = Transaction::factory()->create();
//
//        $postData = [
//            'id' => $transactions->id
//        ];
//
//        $response = $this->deleteJson(route('deleteTransaction'), $postData);
//        $responseArray = $response->json();
//
//        $response->assertOk();
//        $this->assertTrue($responseArray['success']);
//    }

    public function testReadTransactions(): void
    {
        $user = User::factory()->create();
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

        $this->actingAs($user);

        $transactions = Transaction::factory()->create([
            'customer_id' => $customer->id,
            'branch_id' => $branch->id,
            'user_id' => $user->id,
        ]);

        $response = $this->getJson(route('readTransaction', ['id' => 'all']));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readTransaction', ['id' => $transactions->id]));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readTransaction'));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

    public function testReadTransactionsByTransactionType(): void
    {
        $user = User::factory()->create();
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

        $this->actingAs($user);

        $transactions = Transaction::factory(2)->create([
            'customer_id' => $customer->id,
            'branch_id' => $branch->id,
            'user_id' => $user->id,
            'transaction_type' => TransactionType::DEPOSIT->value,
        ]);

        $transactions = Transaction::factory(3)->create([
            'customer_id' => $customer->id,
            'branch_id' => $branch->id,
            'user_id' => $user->id,
            'transaction_type' => TransactionType::WITHDRAWAL->value,
        ]);

        $response = $this->getJson(route('readTransactionByTransactionType', ['transaction_type' => 'deposit','id' => 'all']));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readTransactionByTransactionType', ['transaction_type' => 'withdrawal','id' => $transactions[2]->id]));
        $responseArray = $response->json();
        $response->dump();
        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readTransactionByTransactionType',['transaction_type' => 'withdrawal']));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

}
