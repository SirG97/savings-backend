<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */


    public function testReadWallet(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $branch = Branch::factory()->create();
        $wallet = Wallet::factory()->create([
            'branch_id' => $branch->id,
        ]);

        $response = $this->getJson(route('readWallet', ['id' => 'all']));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readWallet', ['id' => $wallet->id]));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);

        $response = $this->getJson(route('readWallet'));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

}
