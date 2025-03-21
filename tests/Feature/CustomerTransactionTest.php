<?php

namespace Tests\Feature;

use App\Enums\LoanStatus;
use App\Enums\PaymentMethod;
use App\Enums\TransactionType;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\CustomerTransaction;
use App\Models\CustomerWallet;
use App\Models\Loan;
use App\Models\LoanApplication;
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

    public function testPayPartialLoanDuringCustomerDeposit()
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
            'balance' => 100000,
            'cash' => 100000,
            'bank' => 0,
            'loan' => 50000,
        ]);
        $customerWallet = CustomerWallet::factory()->create([
            'customer_id' => $customer->id,
            'balance' => 50000,
            'loan' => 55450
        ]);
        $loan = Loan::find(1);

        $loanApplication = LoanApplication::factory()->create([
            'branch_id' => $branch->id,
            'user_id' =>$user->id,
            'customer_id' => $customer->id,
            'loan_id' => $loan->id,
            'amount' => 50000,
            'total_amount' => 55450,
            'interest_amount' => 5450,
            'total_payable_amount' => 55450,
            'duration' => 1,
            'status' => LoanStatus::APPROVED->value,
            'due_date' => now()->addMonth(),
            'approved_at' => now(),
            'approved_by' => $user->id,
            'late_payment_interest' => 1

        ]);

        $postData = [
            'customer_id' => $customer->id,
            'amount' => 10000,
            'transaction_type' => TransactionType::DEPOSIT->value,
            'payment_method' => PaymentMethod::CASH->value,
            'description' => $this->faker->sentence(1),
            'date' => $this->faker->date(),
        ];

        $response = $this->postJson(route('createCustomerTransaction'), $postData);
        $responseArray = $response->json();
        
        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

    public function testPayFullLoanDuringCustomerDeposit()
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
            'balance' => 100000,
            'cash' => 100000,
            'bank' => 0,
            'loan' => 55450,
        ]);
        $customerWallet = CustomerWallet::factory()->create([
            'customer_id' => $customer->id,
            'balance' => 50000,
            'loan' => 55450
        ]);
        $loan = Loan::find(1);

        $loanApplication = LoanApplication::factory()->create([
            'branch_id' => $branch->id,
            'user_id' =>$user->id,
            'customer_id' => $customer->id,
            'loan_id' => $loan->id,
            'amount' => 50000,
            'total_amount' => 55450,
            'interest_amount' => 5450,
            'total_payable_amount' => 55450,
            'duration' => 1,
            'status' => LoanStatus::APPROVED->value,
            'due_date' => now()->addMonth(),
            'approved_at' => now(),
            'approved_by' => $user->id,
            'late_payment_interest' => 1

        ]);

        $postData = [
            'customer_id' => $customer->id,
            'amount' => 55450,
            'transaction_type' => TransactionType::DEPOSIT->value,
            'payment_method' => PaymentMethod::CASH->value,
            'description' => $this->faker->sentence(1),
            'date' => $this->faker->date(),
        ];

        $response = $this->postJson(route('createCustomerTransaction'), $postData);
        $responseArray = $response->json();
       
        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

    public function testOverPaymentLoanDuringCustomerDeposit()
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
            'balance' => 100000,
            'cash' => 100000,
            'bank' => 0,
            'loan' => 55450,
        ]);
        $customerWallet = CustomerWallet::factory()->create([
            'customer_id' => $customer->id,
            'balance' => 50000,
            'loan' => 55450
        ]);
        $loan = Loan::find(1);

        $loanApplication = LoanApplication::factory()->create([
            'branch_id' => $branch->id,
            'user_id' =>$user->id,
            'customer_id' => $customer->id,
            'loan_id' => $loan->id,
            'amount' => 50000,
            'total_amount' => 55450,
            'interest_amount' => 5450,
            'total_payable_amount' => 55450,
            'duration' => 1,
            'status' => LoanStatus::APPROVED->value,
            'due_date' => now()->addMonth(),
            'approved_at' => now(),
            'approved_by' => $user->id,
            'late_payment_interest' => 1

        ]);

        $postData = [
            'customer_id' => $customer->id,
            'amount' => 65450,
            'transaction_type' => TransactionType::DEPOSIT->value,
            'payment_method' => PaymentMethod::CASH->value,
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

    public function testReadCustomerCommission(): void
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

        $customerTransaction3 = CustomerTransaction::factory(3)->create([
            'customer_id' => $customer->id,
            'branch_id' => $branch->id,
            'user_id' => $user->id,
            'transaction_type' => TransactionType::COMMISSION->value,
        ]);

        $response = $this->getJson(route('readCustomerTransactionByTransactionTypeAndBranchId', 
        [
            'transaction_type' => 'commission',
            'branch_id' => $branch->id,
            'startDate' => '2025-01-01',
            'endDate' => '2025-04-20',
        ]));
        $responseArray = $response->json();
        $response->dump();
        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readCustomerTransactionByTransactionTypeAndBranchId', 
        [
            'transaction_type' => 'commission',
            'branch_id' => $customerTransaction3[1]->branch_id,
            'startDate' => '2024-03-01',
            'endDate' => '2024-03-20',
        ]));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readCustomerTransactionByTransactionType', ['transaction_type' => 'commission']));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

}
