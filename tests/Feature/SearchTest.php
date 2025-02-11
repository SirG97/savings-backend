<?php

namespace Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSearch()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $postData = [
            'value' => $this->faker->name(),
        ];

        $response = $this->getJson(route('userSearch', ['value' => $postData['value']]));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('customerSearch', ['value' => $postData['value']]));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('customerTransactionSearch', ['value' => $postData['value']]));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('transactionSearch', ['value' => $postData['value']]));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);


    }
}
