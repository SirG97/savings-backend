<?php

namespace Tests\Feature;

use App\Enums\PaymentMethod;
use App\Enums\PerformedAction;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\CustomerWallet;
use App\Models\Loan;
use App\Models\LoanApplication;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoanApplicationTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testErrorValidationForCreateLoanApplication()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $postData = [
            'name' => null,
        ];

        $response = $this->postJson(route('createLoanApplication'), $postData);
        $responseArray = $response->json();

        $this->assertFalse($responseArray['success']);
    }

    public function testCreateLoanApplication()
    {
        $branch = Branch::factory()->create();
        $user = User::factory()->create([
            'branch_id' => $branch->id,
        ]);
        
        $customer = Customer::factory()->create([
            'branch_id' => $branch->id,
        ]);


        $this->actingAs($user);

        $postData = [
            'duration' => 2,
            'amount' => 100000,
            'customer_id' => $customer->id,
        ];

        $response = $this->postJson(route('createLoanApplication'), $postData);
        $responseArray = $response->json();
   
        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

    public function testErrorValidationForUpdateLoanApplication()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $postData = [
            'id' => 'abc',
            'status' => PerformedAction::APPROVED->value,
        ];

        $response = $this->putJson(route('updateLoanApplication'), $postData);
        $responseArray = $response->json();

        $this->assertFalse($responseArray['success']);
    }

    public function testApproveLoanApplication()
    {
        $branch = Branch::factory()->create();
        $user = User::factory()->create([
            'branch_id' => $branch->id,
        ]);

        $customer = Customer::factory()->create([
            'branch_id' => $branch->id,
        ]);

        $wallet = Wallet::factory()->create([
            'branch_id' => $branch->id,
            'cash' => 10000000,
            'bank' => 10000000,
            'balance' => 20000000,
        ]);

        $customerWallet = CustomerWallet::factory()->create([
            'customer_id' => $customer->id,
            'balance' => 0,
        ]);

        $loan = Loan::find(1);

        $this->actingAs($user);

        $loanApplication = LoanApplication::factory()->create([
            'user_id' => $user->id,
            'loan_id' => $loan->id,
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
        ]);

        $postData = [
            'id' => $loanApplication->id,
            'status' => PerformedAction::APPROVED->value,
            'payment_method' => PaymentMethod::CASH->value
        ];

        $response = $this->putJson(route('updateLoanApplication'), $postData);
        $responseArray = $response->json();
       
        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

    public function testRejectLoanApplication()
    {
        $branch = Branch::factory()->create();
        $user = User::factory()->create([
            'branch_id' => $branch->id,
        ]);
        $customer = Customer::factory()->create([
            'branch_id' => $branch->id,
        ]);

        $wallet = Wallet::factory()->create([
            'branch_id' => $branch->id,
            'cash' => 10000000,
            'bank' => 10000000,
            'balance' => 20000000,
        ]);

        $customerWallet = CustomerWallet::factory()->create([
            'customer_id' => $customer->id,
            'balance' => 0,
        ]);

        $loan = Loan::find(1);

        $this->actingAs($user);

        $loanApplication = LoanApplication::factory()->create([
            'user_id' => $user->id,
            'loan_id' => $loan->id,
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
        ]);

        $postData = [
            'id' => $loanApplication->id,
            'status' => PerformedAction::REJECTED->value,
            'reason' => 'Nothing'
        ];

        $response = $this->putJson(route('updateLoanApplication'), $postData);
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

    public function testErrorValidationForDeleteLoanApplication()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $postData = [
            'id' => 'abc'
        ];

        $response = $this->deleteJson(route('deleteLoanApplication'), $postData);
        $responseArray = $response->json();

        $this->assertFalse($responseArray['success']);
    }

    public function testDeleteLoanApplication()
    {
        $branch = Branch::factory()->create();
        $user = User::factory()->create([
            'branch_id' => $branch->id,
        ]);
        $customer = Customer::factory()->create([
            'branch_id' => $branch->id,
        ]);

        $wallet = Wallet::factory()->create([
            'branch_id' => $branch->id,
            'cash' => 10000000,
            'bank' => 10000000,
            'balance' => 20000000,
        ]);

        $customerWallet = CustomerWallet::factory()->create([
            'customer_id' => $customer->id,
            'balance' => 0,
        ]);

        $loan = Loan::find(1);

        $this->actingAs($user);

        $loanApplication = LoanApplication::factory()->create([
            'user_id' => $user->id,
            'loan_id' => $loan->id,
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
        ]);

        $postData = [
            'id' => $loanApplication->id
        ];

        $response = $this->deleteJson(route('deleteLoanApplication'), $postData);
        $responseArray = $response->json();

        // $response->assertOk();
        $this->assertFalse($responseArray['success']);
    }

    public function testReadLoanApplication(): void
    {
        $branch = Branch::factory()->create();
        $user = User::factory()->create([
            'branch_id' => $branch->id,
        ]);
        $customer = Customer::factory()->create([
            'branch_id' => $branch->id,
        ]);

        $wallet = Wallet::factory()->create([
            'branch_id' => $branch->id,
            'cash' => 10000000,
            'bank' => 10000000,
            'balance' => 20000000,
        ]);

        $customerWallet = CustomerWallet::factory()->create([
            'customer_id' => $customer->id,
            'balance' => 0,
        ]);

        $loan = Loan::find(1);

        $this->actingAs($user);

        $loanApplication = LoanApplication::factory()->create([
            'user_id' => $user->id,
            'loan_id' => $loan->id,
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
        ]);

        $response = $this->getJson(route('readLoanApplication', ['id' => 'all']));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readLoanApplication', ['id' => $loanApplication->id]));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readLoanApplication'));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

}
