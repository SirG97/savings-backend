<?php

namespace Tests\Feature;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testErrorValidationForCreateLoan()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $postData = [
            'name' => null,
        ];

        $response = $this->postJson(route('createLoan'), $postData);
        $responseArray = $response->json();

        $this->assertFalse($responseArray['success']);
    }

    public function testCreateLoan()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $postData = [
            'name' => $this->faker->name(),
        ];

        $response = $this->postJson(route('createLoan'), $postData);
        $responseArray = $response->json();

//        $response->assertOk();
        $this->assertFalse($responseArray['success']);
    }

    public function testErrorValidationForUpdateLoan()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $postData = [
            'id' => 'abc',
            'name' => $this->faker->name(),
        ];

        $response = $this->putJson(route('updateLoan'), $postData);
        $responseArray = $response->json();

        $this->assertFalse($responseArray['success']);
    }

    public function testUpdateLoan()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $loan = Loan::factory()->create();

        $postData = [
            'id' => $loan->id,
            'name' => $this->faker->name(),
        ];

        $response = $this->putJson(route('updateLoan'), $postData);
        $responseArray = $response->json();
        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

    public function testErrorValidationForDeleteLoan()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $postData = [
            'id' => 'abc'
        ];

        $response = $this->deleteJson(route('deleteLoan'), $postData);
        $responseArray = $response->json();

        $this->assertFalse($responseArray['success']);
    }

    public function testDeleteLoan()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $loan = Loan::factory()->create();

        $postData = [
            'id' => $loan->id
        ];

        $response = $this->deleteJson(route('deleteLoan'), $postData);
        $responseArray = $response->json();

//        $response->assertOk();
        $this->assertFalse($responseArray['success']);
    }

    public function testReadLoan(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $loan = Loan::factory()->create();

        $response = $this->getJson(route('readLoan', ['id' => 'all']));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readLoan', ['id' => $loan->id]));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readLoan'));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

}
