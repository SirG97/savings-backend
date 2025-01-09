<?php

namespace Tests\Feature;

use App\Enums\Gender;
use App\Models\Admin;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\SuperAdmin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testErrorValidationForCreateCustomer()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $postData = [
            'name' => null,
        ];

        $response = $this->postJson(route('createCustomer'), $postData);
        $responseArray = $response->json();

        $this->assertFalse($responseArray['success']);
    }

    public function testCreateCustomer()
    {
        $branch = Branch::factory()->create();
        $user = User::factory()->create([
            'branch_id' => $branch->id,
        ]);

        $this->actingAs($user);

        $postData = [
            'first_name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'account_id' => fake()->slug(6),
            'middle_name' => fake()->lastName(),
            'dob' => fake()->date(),
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
//            'branch_id' => $branch->id,
            'user_id' => $user->id,
            'group' => fake()->slug(13),
            'sb_card_no_from' => fake()->slug(13),
            'sb_card_no_to' => fake()->slug(13),
            'sb' => fake()->slug(13),
            'initial_unit' => fake()->sentence(1),
            'bank_name' => fake()->name(),
            'bank_code' => fake()->slug(3),
            'account_name' => fake()->name(),
            'account_number' => fake()->slug(8),
            'daily_amount' => fake()->firstName(000)
        ];

        $response = $this->postJson(route('createCustomer'), $postData);
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

    public function testErrorSuperAdminProvidesBranchToCreateCustomer()
    {
        $branch = Branch::factory()->create();
        $user = User::factory()->create([
            'branch_id' => $branch->id,
            'model' => SuperAdmin::class
        ]);

        $this->actingAs($user);

        $postData = [
            'first_name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'account_id' => fake()->slug(6),
            'middle_name' => fake()->lastName(),
            'dob' => fake()->date(),
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
//            'branch_id' => $branch->id,
            'user_id' => $user->id,
            'group' => fake()->slug(13),
            'sb_card_no_from' => fake()->slug(13),
            'sb_card_no_to' => fake()->slug(13),
            'sb' => fake()->slug(13),
            'initial_unit' => fake()->sentence(1),
            'bank_name' => fake()->name(),
            'bank_code' => fake()->slug(3),
            'account_name' => fake()->name(),
            'account_number' => fake()->slug(8),
            'daily_amount' => fake()->firstName(000)
        ];

        $response = $this->postJson(route('createCustomer'), $postData);
        $responseArray = $response->json();

        $this->assertFalse($responseArray['success']);
    }

    public function testSuperAdminProvidesBranchToCreateCustomer()
    {
        $branch = Branch::factory()->create();
        $user = User::factory()->create([
            'branch_id' => $branch->id,
            'model' => SuperAdmin::class
        ]);

        $this->actingAs($user);

        $postData = [
            'first_name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'account_id' => fake()->slug(6),
            'middle_name' => fake()->lastName(),
            'dob' => fake()->date(),
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
            'branch_id' => $branch->id,
            'user_id' => $user->id,
            'group' => fake()->slug(13),
            'sb_card_no_from' => fake()->slug(13),
            'sb_card_no_to' => fake()->slug(13),
            'sb' => fake()->slug(13),
            'initial_unit' => fake()->sentence(1),
            'bank_name' => fake()->name(),
            'bank_code' => fake()->slug(3),
            'account_name' => fake()->name(),
            'account_number' => fake()->slug(8),
            'daily_amount' => fake()->firstName(000)
        ];

        $response = $this->postJson(route('createCustomer'), $postData);
        $responseArray = $response->json();
        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

    public function testErrorValidationForUpdateCustomer()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $postData = [
            'id' => 'abc',
            'name' => $this->faker->name(),
        ];

        $response = $this->putJson(route('updateCustomer'), $postData);
        $responseArray = $response->json();

        $this->assertFalse($responseArray['success']);
    }

    public function testUpdateCustomer()
    {
        $branch = Branch::factory()->create();
        $user = User::factory()->create([
            'branch_id' => $branch->id,
        ]);

        $this->actingAs($user);

        $customer = Customer::factory()->create([
            'branch_id' => $branch->id,
        ]);

        $postData = [
            'id' => $customer->id,
            'first_name' => $this->faker->name(),
        ];

        $response = $this->putJson(route('updateCustomer'), $postData);
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

    public function testErrorValidationForDeleteCustomer()
    {
        $branch = Branch::factory()->create();
        $user = User::factory()->create([
            'branch_id' => $branch->id,
        ]);

        $this->actingAs($user);

        $postData = [
            'id' => 'abc'
        ];

        $response = $this->deleteJson(route('deleteCustomer'), $postData);
        $responseArray = $response->json();

        $this->assertFalse($responseArray['success']);
    }

    public function testDeleteCustomer()
    {
        $branch = Branch::factory()->create();
        $user = User::factory()->create([
            'branch_id' => $branch->id,
        ]);

        $this->actingAs($user);

        $customer = Customer::factory()->create([
            'branch_id' => $branch->id,
        ]);

        $postData = [
            'id' => $customer->id
        ];

        $response = $this->deleteJson(route('deleteCustomer'), $postData);
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

    public function testReadCustomer(): void
    {
        $branch = Branch::factory()->create();
        $user = User::factory()->create([
            'branch_id' => $branch->id,
        ]);

        $this->actingAs($user);

        $customer = Customer::factory()->create([
            'branch_id' => $branch->id,
        ]);

        $response = $this->getJson(route('readCustomer', ['id' => 'all']));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readCustomer', ['id' => $customer->id]));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readCustomer'));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

    public function testReadCustomerByBranchId(): void
    {
        $branch = Branch::factory()->create();
        $branch2 = Branch::factory()->create();
        $user = User::factory()->create([
            'branch_id' => $branch->id,
        ]);

        $this->actingAs($user);

        $customer = Customer::factory()->create([
            'branch_id' => $branch->id,
        ]);
        $customer3 = Customer::factory(3)->create([
            'branch_id' => $branch2->id,
        ]);

        $response = $this->getJson(route('readCustomerByBranchId', ['id' => 'all', 'branch_id' => $branch->id]));
        $responseArray = $response->json();
        $response->dump();
        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readCustomerByBranchId', ['id' => $customer->id, 'branch_id' => $branch->id]));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readCustomerByBranchId',['branch_id' => $branch->id]));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

}
