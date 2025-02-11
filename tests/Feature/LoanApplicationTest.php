<?php

namespace Tests\Feature;

use App\Models\LoanApplication;
use App\Models\User;
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
        $user = User::factory()->create();

        $this->actingAs($user);

        $postData = [
            'name' => $this->faker->name(),
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
            'name' => $this->faker->name(),
        ];

        $response = $this->putJson(route('updateLoanApplication'), $postData);
        $responseArray = $response->json();

        $this->assertFalse($responseArray['success']);
    }

    public function testUpdateLoanApplication()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $loanApplication = LoanApplication::factory()->create();

        $postData = [
            'id' => $loanApplication->id,
            'name' => $this->faker->name(),
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
        $user = User::factory()->create();

        $this->actingAs($user);

        $loanApplication = LoanApplication::factory()->create();

        $postData = [
            'id' => $loanApplication->id
        ];

        $response = $this->deleteJson(route('deleteLoanApplication'), $postData);
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

    public function testReadLoanApplication(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $loanApplication = LoanApplication::factory()->create();

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
