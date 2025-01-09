<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\CustomerWallet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerWalletTest extends TestCase
{
    use WithFaker;



    public function testReadCustomerWallet(): void
    {
        $branch = Branch::factory()->create();
        $user = User::factory()->create([
            'branch_id' => $branch->id,
        ]);
        $customer = Customer::factory()->create([
            'branch_id' => $branch->id,
        ]);

        $this->actingAs($user);

        $customerWallet = CustomerWallet::factory()->create([
            'customer_id' => $customer->id,
        ]);

        $response = $this->getJson(route('readCustomerWallet', ['id' => 'all']));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readCustomerWallet', ['id' => $customerWallet->id]));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readCustomerWallet'));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

}
