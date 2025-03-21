<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class ChangePasswordTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testErrorValidationForChangePassword()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $postData = [
            'current_password' => '12345678', //Wrong current password
            'password' => 'password', //Wrong password format
            'password_confirmation' => '1234567' //None matching passwords
        ];

        $response = $this->post(route('changePassword'), $postData, ['Accept' => 'application/json']);
        $responseArray = json_decode($response->getContent(), true);

        $this->assertTrue(isset($responseArray['errors']));
        $this->assertTrue(isset($responseArray['message']));
    }

    public function testChangePassword()
    {
        $user = User::factory()->create([
            'password' => Hash::make("{_'hhtl[N#%H3BXe")
        ]);

        $this->actingAs($user);

        $postData = [
            'current_password' => "{_'hhtl[N#%H3BXe",
            'password' => '$Ty12345678',
            'password_confirmation' => '$Ty12345678'
        ];

        $response = $this->post(route('changePassword'), $postData);
        $responseArray = json_decode($response->getContent(), true);

        $this->assertEquals(200, $responseArray['status_code']);
        $this->assertEquals( 'success', $responseArray['status']);
    }
}
