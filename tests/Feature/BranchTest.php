<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BranchTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testErrorValidationForCreateBranch()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $postData = [
            'name' => null,
        ];

        $response = $this->postJson(route('createBranch'), $postData);
        $responseArray = $response->json();
        $this->assertFalse($responseArray['success']);
    }

    public function testCreateBranch()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $postData = [
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
        ];

        $response = $this->postJson(route('createBranch'), $postData);
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

    public function testErrorValidationForUpdateBranch()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $postData = [
            'id' => 'abc',
            'name' => $this->faker->name(),
        ];

        $response = $this->putJson(route('updateBranch'), $postData);
        $responseArray = $response->json();

        $this->assertFalse($responseArray['success']);
    }

    public function testUpdateBranch()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $branch = Branch::factory()->create();

        $postData = [
            'id' => $branch->id,
            'name' => $this->faker->name(),
        ];

        $response = $this->putJson(route('updateBranch'), $postData);
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

    public function testErrorValidationForDeleteBranch()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $postData = [
            'id' => 'abc'
        ];

        $response = $this->deleteJson(route('deleteBranch'), $postData);
        $responseArray = $response->json();

        $this->assertFalse($responseArray['success']);
    }

    public function testDeleteBranch()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $branch = Branch::factory()->create();

        $postData = [
            'id' => $branch->id
        ];

        $response = $this->deleteJson(route('deleteBranch'), $postData);
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

    public function testReadBranch(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $branch = Branch::factory()->create();

        $response = $this->getJson(route('readBranch', ['id' => 'all']));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readBranch', ['id' => $branch->id]));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readBranch'));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

}
