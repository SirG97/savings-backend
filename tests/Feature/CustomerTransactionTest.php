<?php

namespace Tests\Feature;

use App\Enums\PaymentMethod;
use App\Enums\TransactionType;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\CustomerTransaction;
use App\Models\CustomerWallet;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerTransactionTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testErrorValidationForCreateCustomerTransaction()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $postData = [
            'name' => null,
        ];

        $response = $this->postJson(route('createCustomerTransaction'), $postData);
        $responseArray = $response->json();

        $this->assertFalse($responseArray['success']);
    }

    public function testCreateCustomerDepositTransaction()
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
            'amount' => 1000,
            'transaction_type' => TransactionType::DEPOSIT->value,
            'payment_method' => PaymentMethod::BANK->value,
            'description' => $this->faker->sentence(1),
            'date' => $this->faker->date(),
        ];

        $response = $this->postJson(route('createCustomerTransaction'), $postData);
        $responseArray = $response->json();


        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

    public function testCustomerWithdrawFailsForInsufficientBalance()
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
        $customerWallet = CustomerWallet::factory()->create(['customer_id' => $customer->id,'balance' => 1000]);

        $postData = [
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'user_id' => $user->id,
            'amount' => 1000,
            'transaction_type' => TransactionType::WITHDRAWAL->value,
            'payment_method' => PaymentMethod::BANK->value,
            'description' => $this->faker->sentence(1),
            'date' => $this->faker->date(),
        ];

        $response = $this->postJson(route('createCustomerTransaction'), $postData);
        $responseArray = $response->json();

        $this->assertFalse($responseArray['success']);
    }

    public function testCustomerWithdrawSuccess()
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

        $postData = [
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'user_id' => $user->id,
            'amount' => 1000,
            'transaction_type' => TransactionType::WITHDRAWAL->value,
            'payment_method' => PaymentMethod::BANK->value,
            'description' => $this->faker->sentence(1),
            'date' => $this->faker->date(),
        ];

        $response = $this->postJson(route('createCustomerTransaction'), $postData);
        $responseArray = $response->json();

        $this->assertTrue($responseArray['success']);
    }

    public function testReadCustomerTransaction(): void
    {
        $user = User::factory()->create();
        $branch = Branch::factory()->create();
        $customer = Customer::factory()->create([
                'branch_id' => $branch->id,
                'user_id' => $user->id]
        );

        $this->actingAs($user);

        $customerTransaction = CustomerTransaction::factory()->create([
            'customer_id' => $customer->id,
            'branch_id' => $branch->id,
            'user_id' => $user->id,
        ]);

        $response = $this->getJson(route('readCustomerTransaction', ['id' => 'all']));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readCustomerTransaction', ['id' => $customerTransaction->id]));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readCustomerTransaction'));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

    public function testReadCustomerTransactionByTransactionType(): void
    {
        $user = User::factory()->create();
        $branch = Branch::factory()->create();
        $customer = Customer::factory()->create([
                'branch_id' => $branch->id,
                'user_id' => $user->id]
        );

        $this->actingAs($user);

        $customerTransaction = CustomerTransaction::factory(3)->create([
            'customer_id' => $customer->id,
            'branch_id' => $branch->id,
            'user_id' => $user->id,
            'transaction_type' => TransactionType::DEPOSIT->value,
        ]);

        $customerTransaction2 = CustomerTransaction::factory(3)->create([
            'customer_id' => $customer->id,
            'branch_id' => $branch->id,
            'user_id' => $user->id,
            'transaction_type' => TransactionType::WITHDRAWAL->value,
        ]);

        $response = $this->getJson(route('readCustomerTransactionByTransactionType', ['transaction_type' => 'deposit','id' => 'all']));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readCustomerTransactionByTransactionType', ['transaction_type' => 'withdrawal','id' => $customerTransaction2[1]->id]));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readCustomerTransactionByTransactionType', ['transaction_type' => 'withdrawal']));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

}
