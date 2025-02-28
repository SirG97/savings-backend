<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class RegistrationTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testErrorValidationForRegistration()
    {
        $postData = [
            'name' => 'Test User 2',
            'email' => 'testuser2gmail.com', //Wrong email format
            'password' => 'password',
            'password_confirmation' => '1234567' //None matching passwords
        ];

        $response = $this->post('/api/auth/register', $postData, ['Accept' => 'application/json']);
        $responseArray = json_decode($response->getContent(), true);

        $this->assertTrue(isset($responseArray['errors']));
        $this->assertTrue(isset($responseArray['message']));

        //This test would also run correctly if an existing email is passed
    }

//    public function testRegistration()
//    {
//        $postData = [
//            'name' => $this->faker->name(),
//            'email' => $this->faker->unique()->safeEmail(),
//            'password' => "{_'hhtl[N#%H3BXe",
//            'password_confirmation' => "{_'hhtl[N#%H3BXe"
//        ];
//
//        $response = $this->post('/api/auth/register', $postData);
//        $responseArray = $response->json();
//        $response->dump();
//        $this->assertEquals(200, $responseArray['status_code']);
//        $this->assertEquals( 'success', $responseArray['status']);
//    }
}
