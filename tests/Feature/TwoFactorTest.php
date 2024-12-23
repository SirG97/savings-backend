<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

use App\Models\User;

class TwoFactorTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateTwoFactor()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('createTwoFactor'));
        $responseArray = json_decode($response->getContent(), true);

        $this->assertEquals(200, $responseArray['status_code']);
        $this->assertEquals( 'success', $responseArray['status'] );
        $this->assertTrue( is_array($responseArray['data']) );
        $this->assertTrue( array_key_exists("qr_code", $responseArray['data']) );
        $this->assertTrue( array_key_exists("uri", $responseArray['data']) );
        $this->assertTrue( array_key_exists("string", $responseArray['data']) );
    }

    public function testErrorValidationForConfirmTwoFactor()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $postData = [
            'code' => 'abcdef', //Wrong format
        ];

        $response = $this->post(route('confirmTwoFactor'), $postData, ['Accept' => 'application/json']);
        $responseArray = json_decode($response->getContent(), true);

        $this->assertTrue(isset($responseArray['errors']));
        $this->assertTrue(isset($responseArray['message']));
    }

    public function testDisableTwoFactor()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('disableTwoFactor'));
        $responseArray = json_decode($response->getContent(), true);
        $response->dump();
        $this->assertEquals(200, $responseArray['status_code']);
        $this->assertEquals( 'success', $responseArray['status'] );
    }
}
